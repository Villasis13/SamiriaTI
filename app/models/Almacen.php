<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 12/03/2021
 * Time: 10:30
 */

class Almacen{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    public function listar_almacenes(){
        try{
            $sql = 'select * from almacenes a inner join negocios n on a.id_negocio = n.id_negocio inner join sucursal s on a.id_sucursal = s.id_sucursal  order by rand()';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    //Registrar nuevo almacen
    public function guardar_almacen($model){
        try{
            if(isset($model->id_almacen)){
                $sql = 'update almacenes set
                        almacen_nombre = ?,
                        almacen_capacidad = ?,
                        almacen_estado = ?,
                        id_negocio = ?,
                        id_sucursal = ?
                        where id_almacen = ?';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->almacen_nombre,
                    $model->almacen_capacidad,
                    $model->almacen_estado,
                    $model->id_negocio,
                    $model->id_sucursal,
                    $model->id_almacen
                ]);
            } else {
                $sql = 'insert into almacenes (id_negocio, id_sucursal, almacen_nombre, almacen_capacidad, almacen_estado) values (?,?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_negocio,
                    $model->id_sucursal,
                    $model->almacen_nombre,
                    $model->almacen_capacidad,
                    $model->almacen_estado
                ]);
            }
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //Lista la información del almacen según id
    public function listar_almacen($id_almacen){
        try{
            $sql = 'select * from almacenes a inner join negocios n on a.id_negocio = n.id_negocio inner join sucursal s on a.id_sucursal = s.id_surcursal where a.id_almacen = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_almacen]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    //Eliminar almacen
    public function eliminar_almacen($id_almacen){
        try{
            $sql = 'delete from almacenes where id_almacen = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_almacen]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar_sucursal_negocio($id){
        try{
            $sql = 'select * from sucursal where id_negocio = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //FUNCIONES NUEVAS PARA LAS SALIDAS DE STOCK
    public function listar_productos_almacen(){
        try{
            $sql = 'select * from recursos r inner join recursos_sede rs on r.id_recurso = rs.id_recurso 
                    inner join categorias c on r.id_categoria = c.id_categoria inner join categorias_negocio cn on c.id_categoria = cn.id_categoria
                    inner join negocios n on cn.id_negocio = n.id_negocio
                    where c.categoria_tipo <> 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function guardar_salida_stock($model){
        try{
            $sql = 'insert into almacenes_salida (id_negocio, id_recurso_sede, id_usuario, almacen_salida_cantidad, almacen_salida_fecha_registro, 
                    almacen_salida_estado) values (?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_negocio,
                $model->id_recurso_sede,
                $model->id_usuario,
                $model->almacen_salida_cantidad,
                $model->almacen_salida_fecha_registro,
                $model->almacen_salida_estado
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function disminuir_stock($cantidad,$id_recurso_sede){
        try {
            $sql = 'update recursos_sede set recurso_sede_stock = recurso_sede_stock - ? where id_recurso_sede = ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$cantidad,$id_recurso_sede]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function cantidad_sacada($dato){
        try{
            $sql = 'select almacen_salida_cantidad as total, r.recurso_nombre as nombre from almacenes_salida sa inner join recursos_sede rs on sa.id_recurso_sede = rs.id_recurso_sede 
                    inner join recursos r on rs.id_recurso = r.id_recurso where date(sa.almacen_salida_fecha_registro) = ? 
                    and sa.almacen_salida_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$dato]);
            $return = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    //FUNCION PARA GUARDAR EN LA TABLA NUEVA OJALA FUNCIONE
    public function guardar_tabla_nueva_stock($model){
        try{
            $sql = 'insert into recurso_distribuido (id_usuario, id_recurso_sede, cantidad_distribuida, conversion_estado, cantidad_convertir, id_medida_nueva, 
                    cantidad_nueva_distribuida, fecha_registro, recurso_distribuido_estado) values (?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario,
                $model->id_recurso_sede,
                $model->cantidad_distribuida,
                $model->conversion_estado,
                $model->cantidad_convertir,
                $model->id_medida_nueva,
                $model->cantidad_nueva_distribuida,
                $model->fecha_registro,
                $model->recurso_distribuido_estado
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    //funcion para guardar log de traspaso de stock
    public function guardar_datos_log($id_recurso_sede,$cantidad,$fecha,$estado,$tipo,$cantidad_salida){
        try{
            $sql = 'insert into recurso_log (id_recurso_sede, recurso_log_cantidad, recurso_log_fecha, recurso_log_estado, recurso_log_tipo, 
                    recurso_log_cantidad_salida) values (?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_recurso_sede,$cantidad,$fecha,$estado,$tipo,$cantidad_salida
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //FUNCIONES NUEVAS PARA REALIZAR EL MILAGROS DEL STOCK
    //jalar valor para resta
    public function jalar_para_resta($id_recurso){
        try{
            $sql = 'select * from recursos_sede where id_recurso_sede = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    //jalar el stock antes de que se cambie el estado a 0
    public function jalar_restante($id_recurso_sede){
        try{
            $sql = 'select cantidad_nueva_distribuida from recurso_distribuido where id_recurso_sede = ? and recurso_distribuido_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso_sede]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    //buscar stock anterior para convertir a 0
    public function buscar_stock_anterior($id_recurso_sede){
        try {
            $sql = 'update recurso_distribuido set recurso_distribuido_estado = 0 where id_recurso_sede = ? and recurso_distribuido_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso_sede]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function modificar_stock_almacen_general($stock_sacado,$id_recurso_sede){
        try {
            $sql = 'update recursos_sede set recurso_sede_stock = recurso_sede_stock - ? where id_recurso_sede = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$stock_sacado,$id_recurso_sede]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //FUNCION PARA STOCK DE VISTA RECURSOS
    public function sacar_stock_dis($id_recurso_sede){
        try{
            $sql = 'select * from recurso_distribuido where id_recurso_sede = ? and recurso_distribuido_estado = 1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso_sede]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


}
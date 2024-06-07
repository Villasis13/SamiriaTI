<?php
class Configuracion{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    public function guardar_habitacion($id,$nro){
        try{
            $sql = 'insert into habitaciones (id_habitacion_tipo, habitacion_nro, habitacion_estado) VALUES (?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$nro,1]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_caja($id,$nombre,$est){
        try{
            if($id==0){
                $stm = $this->pdo->prepare('insert into cajas (caja_nombre, caja_estado) VALUES (?,?)');
                $stm->execute([$nombre,1]);
            }else{
                $stm = $this->pdo->prepare('update cajas set caja_nombre=?, caja_estado=? where id_caja=?');
                $stm->execute([$nombre,$est,$id]);
            }
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_producto($id_categoria,$id_usuario,$codigo,$nombre,$descripcion,$stock,$stock_min,$mt){
        try{
            $sql = 'insert into producto (id_categoria, id_usuario, producto_codigo_barra, producto_nombre, producto_descripcion, producto_stock,producto_stock_minimo, producto_creacion, producto_codigo) VALUES (?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_categoria,$id_usuario,$codigo,$nombre,$descripcion,$stock,$stock_min,date('Y-m-d H:i:s'),$mt]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function editar_producto($id_categoria,$codigo,$nombre,$descripcion,$stock,$stock_min,$id){
        try{
            $sql = 'update producto set id_categoria=?, producto_codigo_barra=?, producto_nombre=?, producto_descripcion=?, producto_stock=?,producto_stock_minimo=? where id_producto=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_categoria,$codigo,$nombre,$descripcion,$stock,$stock_min,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_producto_precio($id_producto,$id_proveedor,$id_medida,$cod_afectacion,$precio_unidad,$precio,$x_mayor,$compra){
        try{
            $sql = 'insert into producto_precio (id_producto, id_proveedor, id_medida, producto_precio_codigoafectacion, producto_precio_unidad, producto_precio_valor, producto_precio_valor_xmayor, producto_precio_compra) VALUES (?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto,$id_proveedor,$id_medida,$cod_afectacion,$precio_unidad,$precio,$x_mayor,$compra]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function editar_producto_precio($id_producto,$id_proveedor,$id_medida,$precio,$x_mayor,$compra){
        try{
            $sql = 'update producto_precio set id_proveedor=?, id_medida=?, producto_precio_valor=?, producto_precio_valor_xmayor=?, producto_precio_compra=? where id_producto=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_proveedor,$id_medida,$precio,$x_mayor,$compra,$id_producto]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_config_comanda($nombre,$fecha,$nro){
        try{
            $sql = 'insert into config_comandas (config_comanda_nombre, config_comanda_fecha, config_comanda_numero) VALUES (?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$nombre,$fecha,$nro]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_tipo_habitacion($nombre,$detalle,$soles,$dolares){
        try{
            $sql = 'insert into habitacion_tipo (habitacion_tipo_nombre, habitacion_tipo_detalle, habitacion_tipo_soles, habitacion_tipo_dolares, habitacion_tipo_estado) VALUES (?,?,?,?,1)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$nombre,$detalle,$soles,$dolares]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_asistencia($id,$fecha,$tipo,$hora){
        try{
            $sql = 'insert into asistencia (id_personal, asistencia_fecha, asistencia_tipo, asistencia_hora, asistencia_estado) VALUES (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha,$tipo,$hora,1]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function editar_tipo_habitacion($nombre,$detalle,$soles,$dolares,$id){
        try{
            $sql = 'update habitacion_tipo set habitacion_tipo_nombre=?, habitacion_tipo_detalle=?, habitacion_tipo_soles=?, habitacion_tipo_dolares=? where id_habitacion_tipo=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$nombre,$detalle,$soles,$dolares,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function existe_habitacion_estado($id){
        try{
            $sql = 'select * from habitacion_estado where id_habitacion=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function existe_producto_eliminar($id){
        try{
            $stm = $this->pdo->prepare('select * from rack_detalle where id_producto=? limit 1');
            $stm->execute([$id]);
            $data=$stm->fetch();
            if(!$data){
                $stm = $this->pdo->prepare('select * from ventas_detalle where id_producto_precio=? limit 1');
                $stm->execute([$id]);
                $data=$stm->fetch();
                if(!$data){
                    return false;
                }else{
                    return true;
                }
            }else{
                return true;
            }
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return true;
        }
    }
    public function listar_producto($id){
        try{
            $sql = 'select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto where p.id_producto=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function existe_habitacion_tipo($id){
        try{
            $sql = 'select * from habitaciones where id_habitacion_tipo=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function existe_habitacion_rack($id){
        try{
            $sql = 'select * from rack where id_habitacion=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function editar_habitacion($tipo,$nro,$id){
        try{
            $sql = 'update habitaciones set id_habitacion_tipo=?, habitacion_nro=? where id_habitacion=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$tipo,$nro,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_habitacion($id){
        try{
            $sql = 'delete from habitaciones where id_habitacion=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_producto_precio($id){
        try{
            $sql = 'delete from producto_precio where id_producto=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_producto($id){
        try{
            $sql = 'delete from producto where id_producto=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_habitacion_tipo($id){
        try{
            $sql = 'delete from habitacion_tipo where id_habitacion_tipo=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_config_comanda($id){
        try{
            $sql = 'delete from config_comandas where id_config_comanda=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_caja($id){
        try{
            $sql = 'delete from cajas where id_caja=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
}
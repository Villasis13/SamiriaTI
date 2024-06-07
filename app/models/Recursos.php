<?php

class Recursos
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }


    public function listar_negocios($id){
        try{
            $sql = 'select * from usuarios_negocio un inner join negocios n on un.id_negocio = n.id_negocio where un.id_usuario = ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_negocios_(){
        try{
            $sql = 'select * from negocios';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_sucursales($id){
        try{
            $sql = 'select * from usuarios_sucursal us inner join sucursal s on us.id_sucursal = s.id_sucursal where us.id_usuario = ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_sucursales_(){
        try{
            $sql = 'select * from sucursal where sucursal_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_unidad_medida(){
        try{
            $sql = 'select * from medida where medida_activo = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


    public function listar_categorias(){
        try{
            $sql = 'select * from categorias';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }



    public function guardar_categoria($model){
        try{
            $sql = 'insert into categorias_negocio (id_usuario_creacion, id_negocio, id_categoria, recurso_categoria_estado, recurso_categoria_fecha) values (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario_creacion,
                $model->id_negocio,
                $model->id_categoria,
                $model->recurso_categoria_estado,
                $model->recurso_categoria_fecha,

            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    public function listar_info(){
        try{
            $sql = 'select * from categorias_negocio cn inner join negocios n on cn.id_negocio = n.id_negocio inner join categorias c on cn.id_categoria = c.id_categoria';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_info_detalle_receta(){
        try{
            $sql = 'select * from recursos_sede rs inner join sucursal s on rs.id_sucursal = s.id_sucursal inner join 
                    recursos r on rs.id_recurso = r.id_recurso inner join categorias c on r.id_categoria = c.id_categoria';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_recursos_categoria($id){
        try{
            $sql = 'select * from recursos where id_categoria = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function jalar_categorias($id){
        try{
            $sql = 'select * from sucursal s inner join negocios n on s.id_negocio = n.id_negocio inner join categorias_negocio cn on n.id_negocio = cn.id_negocio inner join categorias c on cn.id_categoria = c.id_categoria where id_sucursal = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar_ultimo_id(){
        try{
            $sql = 'select * from recursos order by id_recurso desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function guardar_recurso($model){
        try{
            $sql = 'insert into recursos_sede (id_usuario_creacion, id_sucursal, id_recurso,id_medida, recurso_sede_factor_unidad, recurso_sede_cantidad,
                    recurso_sede_precio_unit,recurso_sede_peso_inicial,recurso_sede_peso_final, recurso_sede_precio_total, recurso_sede_merma, recurso_sede_precio, recurso_sede_stock, recurso_sede_stock_minimo, 
                    recurso_sede_estado,recurso_sede_fecha) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario_creacion,
                $model->id_sucursal,
                $model->id_recurso,
                $model->id_medida,
                $model->recurso_sede_factor_unidad,
                $model->recurso_sede_cantidad,
                $model->recurso_sede_precio_unit,
                $model->recurso_sede_peso_inicial,
                $model->recurso_sede_peso_final,
                $model->recurso_sede_precio_total,
                $model->recurso_sede_merma,
                $model->recurso_sede_precio,
                $model->recurso_sede_stock,
                $model->recurso_sede_stock_minimo,
                $model->recurso_sede_estado,
                $model->recurso_sede_fecha
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function recursos_por_id_sede($id_sucursal){
        try{
            $sql = 'select * from recursos_sede rs inner join sucursal s on rs.id_sucursal = s.id_sucursal inner join medida um 
                    on rs.id_medida = um.id_medida inner join recursos r on rs.id_recurso = r.id_recurso inner join categorias c 
                    on r.id_categoria = c.id_categoria where rs.id_sucursal = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_sucursal]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_sucursales_xid($id_sucursal){
        try{
            $sql = 'select * from sucursal where id_sucursal = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_sucursal]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function listar_recursos_sede(){
        try{
            $sql = 'select * from recursos_sede rs inner join sucursal s on rs.id_sucursal = s.id_sucursal inner join medida um 
                    on rs.id_medida = um.id_medida inner join recursos r on rs.id_recurso = r.id_recurso inner join categorias c 
                    on r.id_categoria = c.id_categoria';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function cambiar_estado_recurso($id_recurso_sede, $recurso_sede_estado){
        try {
            $sql = "update recursos_sede set
                recurso_sede_estado = ?
                where id_recurso_sede = ?";

            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $recurso_sede_estado, $id_recurso_sede
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function cambiar_estado_categoria($id_categoria_negocio, $recurso_categoria_estado){
        try {
            $sql = "update categorias_negocio set
                recurso_categoria_estado = ?
                where id_categoria_negocio = ? ";

            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $recurso_categoria_estado, $id_categoria_negocio
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function editar_stock_minimo($id_recurso_sede, $recurso_sede_stock_minimo){
        try {
            $sql = "update recursos_sede set
                recurso_sede_stock_minimo = ?
                where id_recurso_sede = ? ";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $recurso_sede_stock_minimo, $id_recurso_sede
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function jalar_id_ultima_categoria(){
        try{
            $sql = 'select * from categorias where categoria_estado = 1 order by id_categoria desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //FUNCION NUEVA NUEVITA
    public function actualizar_stock_precio($precio_compra,$stock_entrante,$id_recurso_sede){
        try {
            $sql = "update recursos_sede set recurso_sede_precio = ?, recurso_sede_stock = recurso_sede_stock + ? where id_recurso_sede = ? and recurso_sede_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $precio_compra,$stock_entrante,$id_recurso_sede
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //RECEMOS (ES PARTE DE LA ORACION) (EL MENSAJE ESTA EN CODIGO)
    public function recurso_nombre($id){
        try{
            $sql = 'select * from recursos_sede r inner join detalle_recetas dr on r.id_recurso_sede = dr.id_recursos_sede 
                    inner join recetas r2 on dr.id_receta = r2.id_receta where r.id_recurso_sede = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function recurso_nombre_($id){
        try{
            $sql = 'select * from recursos_sede where id_recurso_sede = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function real_nombre($id){
        try{
            $sql = 'select * from recursos r inner join recursos_sede rs on r.id_recurso = rs.id_recurso where rs.id_recurso_sede = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    //FUNCION PARA VER LA CONVERSION
    public function ver_conversion($id_recurso_sede){
        try{
            $sql = 'select * from recurso_distribuido rd inner join recursos_sede r on rd.id_recurso_sede = r.id_recurso_sede 
                    left join medida um on rd.id_medida_nueva = um.id_medida where rd.id_recurso_sede = ? 
                    and rd.recurso_distribuido_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso_sede]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function recursos_recetas($id){
        try{
            $sql = 'select r2.receta_nombre,um.medida_nombre,dr.detalle_receta_cantidad, r.id_recurso_sede from recursos_sede r 
                    inner join detalle_recetas dr on r.id_recurso_sede = dr.id_recursos_sede
                    inner join medida um on dr.detalle_receta_unidad_medida = um.id_medida
                    inner join recetas r2 on dr.id_receta = r2.id_receta
                    where r.id_recurso_sede = ?
    				union all
                   
              	    select cad.detalle_texto as receta_nombre, mm.medida_nombre as medida_nombre,rpd.cantidad as detalle_receta_cantidad, rr.id_recurso_sede from recursos_sede rr 
                    inner join recursos re on rr.id_recurso = re.id_recurso
                    inner join receta_preferencia_detalle rpd on re.id_recurso = rpd.id_recurso
                    inner join medida mm on rpd.id_medida = mm.id_medida
                    inner join recete_preferencia rp on rpd.id_receta_preferencia = rp.id_receta_preferencia
                    inner join acompanamiento_detalle cad on rp.id_acompanamiento_detalle = cad.id_acompa_detalle
                    where rr.id_recurso_sede = ?;';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

}
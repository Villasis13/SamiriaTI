<?php

class Acompanamiento
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function lista_productos(){
        try{
            $sql = 'select * from producto where producto_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_acompas(){
        try{
            $sql = 'select * from acompanamiento a inner join producto p on a.id_producto = p.id_producto where producto_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_detalles_acompa(){
        try{
            $sql = 'select * from acompanamiento_detalle ad inner join acompanamiento a on ad.id_acompanamiento = a.id_acompanamiento
                    where a.acompanamiento_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    //FUNCION PARA GUARDAR EL ACOMPAÑAMIENTO
    public function guardar_acompa($model){
        try {
            $sql = 'insert into acompanamiento (id_usuario,id_producto,acompanamiento_texto,acompanamiento_fecha,acompanamiento_estado) values (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario,
                $model->id_producto,
                $model->acompanamiento_texto,
                $model->acompanamiento_fecha,
                $model->acompanamiento_estado
            ]);
            return 1;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2;
        }
    }

    public function guardar_detalle_acompa($model){
        try {
            $sql = 'insert into acompanamiento_detalle (id_acompanamiento,detalle_texto,detalle_fecha,detalle_estado,microtime) values (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_acompanamiento,
                $model->detalle_texto,
                $model->detalle_fecha,
                $model->detalle_estado,
                $model->microtime
            ]);
            return 1;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2;
        }
    }

    public function buscar_los_detalles($id_acompanamiento){
        try{
            $sql = 'select * from acompanamiento_detalle where id_acompanamiento = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_acompanamiento]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function buscar_cantidad($id_acompa_detalle){
        try{
            $sql = 'select * from recete_preferencia rp inner join receta_preferencia_detalle rpd on rp.id_receta_preferencia = rpd.id_receta_preferencia 
                    where id_acompanamiento_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_acompa_detalle]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function eliminar_acompa_detalle($id_acompa_detalle){
        try{
            $sql = 'delete from acompanamiento_detalle where id_acompa_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_acompa_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_receta_preferencia($id_receta_preferencia){
        try{
            $sql = 'delete from recete_preferencia where id_acompanamiento_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_receta_preferencia]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_receta_preferencia_detalle($id_receta_preferencia){
        try{
            $sql = 'delete from receta_preferencia_detalle where id_receta_preferencia = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_receta_preferencia]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //FUNCIONES PARA LA NUEVA VAINA DE RECURSOS
    public function listar_recursos(){
        try{
            $sql = 'select * from recursos where recurso_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function id_detalle($micro){
        try{
            $sql = 'select * from acompanamiento_detalle where microtime=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$micro]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function guardar_nueva_receta($model){
        try {
            $sql = 'insert into recete_preferencia (id_usuario, id_acompanamiento_detalle, receta_preferencia_fecha, receta_preferencia_estado,rec_microtime) values (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario,
                $model->id_acompanamiento_detalle,
                $model->receta_preferencia_fecha,
                $model->receta_preferencia_estado,
                $model->rec_microtime
            ]);
            return 1;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2;
        }
    }

    public function obtener_id_receta_pre($micro){
        try{
            $sql = 'select id_receta_preferencia from recete_preferencia where rec_microtime=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$micro]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function guardar_detalle_preferncia_receta($model){
        try {
            $sql = 'insert into receta_preferencia_detalle (id_receta_preferencia, id_usuario, id_recurso,id_medida, cantidad, preferencia_detalle_fecha, preferencia_detalle_estado) 
                    values (?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_receta_preferencia,
                $model->id_usuario,
                $model->id_recurso,
                $model->id_medida,
                $model->cantidad,
                $model->preferencia_detalle_fecha,
                $model->preferencia_detalle_estado
            ]);
            return 1;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2;
        }
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

    public function buscar_medidita($id_medida){
        try{
            $sql = 'select * from receta_preferencia_detalle rpd inner join medida m on rpd.id_medida = m.id_medida where m.id_medida = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_medida]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function capturar_id($id_acompa_detalle){
        try{
            $sql = 'select * from recete_preferencia where id_acompanamiento_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_acompa_detalle]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


}
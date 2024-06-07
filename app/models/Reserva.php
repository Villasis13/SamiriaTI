<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 10/10/2020
 * Time: 0:38
 */
class Reserva{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    function listar_reservas($fecha){
        try{
            $sql = "select * from reserva where reserva_estado = 1 and ? between reserva_in and reserva_out";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    function listar_hab1(){
        try{
            $sql = "select * from reserva r inner join habitacion_tipo ht on r.id_tipo_habitacion = ht.id_habitacion_tipo
					inner join habitacion_tipo h on r.id_tipo_habitacion_2 = h.id_habitacion_tipo
					inner join habitacion_tipo t on r.id_tipo_habitacion_3 = t.id_habitacion_tipo";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function guardar_reserva($model){
        try{
            $sql = 'insert into reserva (reserva_dni,reserva_nombre, reserva_in, reserva_out, id_tipo_habitacion,cant_tipo_habitacion,tarifa_tipo_habitacion,id_tipo_habitacion_2,cant_tipo_habitacion_2,tarifa_tipo_habitacion_2,id_tipo_habitacion_3,cant_tipo_habitacion_3 ,tarifa_tipo_habitacion_3,reserva_obs,id_usuario,reserva_datetime,reserva_contacto,reserva_origen,reserva_numero,reserva_estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$model->reserva_dni,$model->reserva_nombre,$model->reserva_in,$model->reserva_out,$model->id_tipo_habitacion,$model->cant_tipo_habitacion,$model->tarifa_tipo_habitacion,$model->id_tipo_habitacion_2,$model->cant_tipo_habitacion_2,$model->tarifa_tipo_habitacion_2,$model->id_tipo_habitacion_3,$model->cant_tipo_habitacion_3,$model->tarifa_tipo_habitacion_3,$model->reserva_obs,$model->id_usuario,$model->reserva_datetime,$model->reserva_contacto,$model->reserva_origen,$model->reserva_numero,$model->reserva_estado]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_reserva($id){
        try{
            $sql = 'delete from reserva where id_reserva=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
	public function listar_x_id($id){
		try{
			$sql = 'select * from reserva r inner join habitacion_tipo ht on r.id_tipo_habitacion = ht.id_habitacion_tipo 
    				inner join habitacion_tipo h on r.id_tipo_habitacion_2 = h.id_habitacion_tipo
         			inner join habitacion_tipo t on r.id_tipo_habitacion_3 = t.id_habitacion_tipo
        			where id_reserva = ?' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}

    public function reservas_usarse(){
        try{
            $sql = 'select * from reserva r inner join habitacion_tipo ht on r.id_tipo_habitacion = ht.id_habitacion_tipo where reserva_estado = 1' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
}

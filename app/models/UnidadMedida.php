<?php
/**
 * Created by PhpStorm.
 * User: Lucho
 * Date: 31/08/2020
 * Time: 23:30
 */

class UnidadMedida
{
    private $pdo;
    private $log;
    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    //lista para mostrar medidas activas
    public function listAllactivo(){
        try{
            $sql = "Select * from medida where medida_activo = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }
    //listar todas las medidas sin excepcion
    public function listAll()
    {
        try {
            $sql = "Select * from medida";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_codigo_afectacion(){
        try {
            $sql = "Select * from tipo_afectacion";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function cambiar_estado($estado , $id_medida ){
        try {
            $sql = 'update medida set medida_activo = ? where id_medida = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $estado,
                $id_medida
            ]);
            $result = 1;
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
}
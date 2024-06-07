<?php

class Clientes
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    public function guardar_cliente($model){
        $fecha_actual = date('Y-m-d H:i:s');
        try{
            if(isset($model->id_cliente)){
                $sql = 'update clientes set
                        cliente_razonsocial = ?,
                        cliente_nombre = ?,
                        cliente_numero = ?,
                        cliente_correo = ?,
                        cliente_direccion = ?,
                        cliente_direccion_2 = ?,
                        cliente_telefono = ?,
                        id_tipodocumento = ?
                        where id_cliente = ?';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->cliente_razonsocial,
                    $model->cliente_nombre,
                    $model->cliente_numero,
                    $model->cliente_correo,
                    $model->cliente_direccion,
                    $model->cliente_direccion_2,
                    $model->cliente_telefono,
                    $model->id_tipodocumento,
                    $model->id_cliente
                ]);
            } else {
                $sql = 'insert into clientes (id_tipodocumento, cliente_razonsocial, cliente_nombre, 
                      cliente_numero, cliente_correo, cliente_direccion, cliente_direccion_2 ,cliente_telefono, cliente_fecha, cliente_estado) 
                      values (?,?,?,?,?,?,?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_tipodocumento,
                    $model->cliente_razonsocial,
                    $model->cliente_nombre,
                    $model->cliente_numero,
                    $model->cliente_correo,
                    $model->cliente_direccion,
                    $model->cliente_direccion_2,
                    $model->cliente_telefono,
                    $fecha_actual,
                    $model->cliente_estado
                ]);
            }
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_cliente_x_numero($cliente_numero){
        try{
            $sql = 'select * from clientes where cliente_numero = ? and cliente_estado = 1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$cliente_numero]);
            $result = $stm->fetch();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_tipos_documentos(){
        try{
            $sql = 'select * from tipo_documentos where tipodocumento_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }
    public function guardar($model){
        try{
            if(isset($model->id_cliente)){
                $stm = $this->pdo->prepare('update clientes set cliente_nombre = ?,cliente_razonsocial = ?, id_tipodocumento = ?,cliente_numero = ?, cliente_correo = ?, cliente_direccion = ?, cliente_telefono = ? where id_cliente = ?');
                $stm->execute([$model->cliente_nombre, $model->cliente_razonsocial, $model->id_tipodocumento, $model->cliente_numero, $model->cliente_correo, $model->cliente_direccion, $model->cliente_telefono, $model->id_cliente]);
            }else {
                $stm = $this->pdo->prepare('insert into clientes (cliente_razonsocial, cliente_nombre, id_tipodocumento, cliente_numero, cliente_correo, cliente_direccion, cliente_telefono, cliente_fecha, cliente_estado)  values (?,?,?,?,?,?,?,?,?)');
                $stm->execute([$model->cliente_razonsocial, $model->cliente_nombre, $model->id_tipodocumento, $model->cliente_numero, $model->cliente_correo, $model->cliente_direccion, $model->cliente_telefono, $model->cliente_fecha, $model->cliente_estado]);
            }
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_documentos(){
        try{
            $stm = $this->pdo->prepare('select * from tipo_documentos');
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_clientes(){
        try{
            $stm = $this->pdo->prepare('select * from clientes where cliente_estado = 1');
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_clientes_todos(){
        try{
            $stm = $this->pdo->prepare('select * from clientes');
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_clientes_editar($id){
        try{
            $stm = $this->pdo->prepare('select * from clientes inner join tipo_documentos td on clientes.id_tipodocumento = td.id_tipodocumento where id_cliente = ? limit 1');
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function eliminar_cliente($id_cliente){
        try{
            $stm = $this->pdo->prepare('delete from clientes where id_cliente = ?');
            $stm->execute([$id_cliente]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_cliente_x_numerodoc($doc_numero){
        try{
            $stm = $this->pdo->prepare('select * from clientes where cliente_numero = ? limit 1');
            $stm->execute([$doc_numero]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }
    public function listar_cliente_x_id($id){
        try{
            $stm = $this->pdo->prepare('select * from clientes where id_cliente = ? limit 1');
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }
    public function validar_dni($cliente_numero){
        try{
            $stm = $this->pdo->prepare('select * from clientes where cliente_numero = ? limit 1');
            $stm->execute([$cliente_numero]);
            $resultado = $stm->fetch();
            (isset($resultado->id_cliente))?$result=true:$result=false;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function validar_dni_cliente($cliente_numero, $id_cliente){
        try{
            $stm = $this->pdo->prepare('select * from clientes where cliente_numero = ? and id_cliente <> ?');
            $stm->execute([$cliente_numero,$id_cliente]);
            $resultado = $stm->fetch();
            (isset($resultado->id_cliente))?$result=true:$result=false;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function validar($id_cliente){
        try{
            $stm = $this->pdo->prepare("select * from ventas v inner join clientes c on v.id_cliente = c.id_cliente where v.id_cliente = ?");
            $stm->execute([$id_cliente]);
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    function cambiar_estado_cliente($id_cliente){
        try {
            $stm = $this->pdo->prepare("update clientes set cliente_estado = 0 where id_cliente = ?");
            $stm->execute([$id_cliente]);
            return 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    function edicion_rapida($r,$d,$id_cliente){
        try {
            $stm = $this->pdo->prepare("update clientes set cliente_razonsocial = ?,cliente_nombre=?,cliente_direccion=? where id_cliente = ?");
            $stm->execute([$r,$r,$d,$id_cliente]);
            return 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
}

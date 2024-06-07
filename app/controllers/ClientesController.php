<?php
require 'app/models/Clientes.php';
class ClientesController{
    private $encriptar;
    private $log;
    private $active;
    private $nav;
    private $validar;
    private $clientes;
    public function __construct(){
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->validar = new Validar();
        $this->clientes = new Clientes();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $tipo_documento = $this->clientes->listar_documentos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/index.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar_log($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
    }
    public function agregar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $tipo_documento = $this->clientes->listar_documentos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/agregar.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            $this->log->insertar_log($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
    }
    public function listar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $clientes = $this->clientes->listar_clientes_todos();
            $tipo_documento = $this->clientes->listar_documentos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/listar.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar_log($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
    }
    public function editar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $tipo_documento = $this->clientes->listar_documentos();
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/editar.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar_log($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
    }
    public function guardar_cliente(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            if($ok_data){
                $model = new Clientes();
                if(isset($_POST['id_cliente'])){
                    $validacion = $this->clientes->validar_dni_cliente($_POST['cliente_numero'], $_POST['id_cliente']);
                    $model->id_cliente = $_POST['id_cliente'];
                }else{
                    $validacion = $this->clientes->validar_dni($_POST['cliente_numero']);
                }
                if($validacion){
                    $result = 5;
                }else{
                    if($_POST['id_tipodocumento']==4){
                        $texto = $_POST['cliente_nombre'];
                        $textoDecodificado = urldecode($texto);
                        $model->cliente_razonsocial = $textoDecodificado;
                        $model->cliente_nombre = "";
                    }else{
                        $texto = $_POST['cliente_nombre'];
                        $textoDecodificado = urldecode($texto);
                        $model->cliente_razonsocial = "";
                        $model->cliente_nombre = $textoDecodificado;
                    }
                    $model->id_tipodocumento = $_POST['id_tipodocumento'];
                    $model->cliente_numero = $_POST['cliente_numero'];
                    $model->cliente_correo = $_POST['cliente_correo'];
                    $model->cliente_direccion = $_POST['cliente_direccion'];
                    $model->cliente_telefono = $_POST['cliente_telefono'];
                    $result = $this->clientes->guardar($model);
                }
            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function eliminar_cliente(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_cliente', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data) {
                $id_cliente = $_POST['id_cliente'];
                $result = $this->clientes->eliminar_cliente($id_cliente);
            } else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function cliente_por_id(){
        $result = 2;
        try{
            $result = $this->clientes->listar_cliente_x_id($_POST['id']);
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => $result));
    }
    public function obtener_datos_x_ruc(){
        $datos = [];
        try {
            $result = json_decode(file_get_contents('https://consultaruc.win/api/ruc/'.$_POST['numero_ruc']),true);
            $datos = array(
                'razon_social' => $result['result']['razon_social'],
                'estado' => $result['result']['estado'],
                'condicion' => $result['result']['condicion'],
            );
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
        }
        echo json_encode(array("result" => $datos));
    }
    public function obtener_datos_x_dni(){
        $datos = [];
        try {
            $result = json_decode(file_get_contents('https://consultaruc.win/api/dni/'.$_POST['numero_dni']),true);
            $datos = array(
                'dni' => $result['result']['DNI'],
                'name' => $result['result']['Nombre'],
                'first_name' => $result['result']['Paterno'],
                'last_name' => $result['result']['Materno'],
            );
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
        }
        echo json_encode(array("result" => $datos));
    }

    public function obtener_datos_cliente(){
        //Array donde vamos a recetar los cambios, en caso hagamos alguno
        $cliente = [];
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            $buscar_cliente = $this->clientes->listar_cliente_x_numero($_POST['numero']);
            if(!empty($buscar_cliente)){
                $dni	= $buscar_cliente->cliente_numero;
                if($buscar_cliente->id_tipodocumento == 4){
                    $nombre = $buscar_cliente->cliente_razonsocial;
                }else{
                    $nombre = $buscar_cliente->cliente_nombre;
                }
                $paterno = "";
                $materno = "";
                $direccion = $buscar_cliente->cliente_direccion;
                $result = 1;
            } else {

                $result = 2;

                $dni	= '';
                $nombre = '';
                $paterno = '';
                $materno = '';
                $direccion = "";
                //echo $result['result']['estado'];
            }

            $datos = array(
                'dni' => $dni,
                'name' => $nombre,
                'first_name' => $paterno,
                'last_name' => $materno,
                'direccion' => $direccion,
                'resultado' => $result,
            );

            //$datos = json_decode($datos);

        } catch (Exception $e) {
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => $datos));
    }
    function cambiar_estado_cliente(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_cliente', 'POST',true,$ok_data,11,'texto',0);
            if($ok_data) {
                $id_cliente = $_POST['id_cliente'];
                $result = $this->clientes->cambiar_estado_cliente($id_cliente);
            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
}
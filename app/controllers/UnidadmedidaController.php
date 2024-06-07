<?php
/**
 * Created by PhpStorm.
 * User: Lucho
 * Date: 31/08/2020
 * Time: 23:20
 */
require 'app/models/UnidadMedida.php';
require 'app/models/Active.php';

class UnidadmedidaController
{
    private $encriptar;
    private $menu;
    private $log;
    private $validar;
    private $nav;
    private $unidadmedida;

    public function __construct()
    {
        $this->encriptar = new Encriptar();
        //$this->menu = new Menu();
        $this->log = new Log();
        $this->unidadmedida = new UnidadMedida();
        $this->validar = new Validar();

    }
    public function listar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $unimedida = $this->unidadmedida->listAll();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'unidadmedida/listar.php';
            require _VIEW_PATH_ . 'footer.php';

        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function cambiarestado(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_medida', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('estado', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data) {
                $id_medida = $_POST['id_medida'];
                $estado = $_POST['estado'];
                $result = $this->unidadmedida->cambiar_estado($estado, $id_medida);
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
}
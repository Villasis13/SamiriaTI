<?php
require 'app/models/Ventas.php';
require 'app/models/Inventario.php';
require 'app/models/Clientes.php';
require 'app/models/Hotel.php';
require 'app/models/Usuario.php';
require 'app/models/Pedido.php';

require 'app/models/Nmletras.php';
require 'app/models/ApiFacturacion.php';
require 'app/models/GeneradorXML.php';
require 'app/models/Cuentascobrar.php';

class CuentascobrarController{
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $usuario;
    private $encriptar;
    private $log;
    private $validar;
    private $ventas;
    private $inventario;
    private $clientes;
    private $correlativo;
    private $turno;
    private $hotel;
    private $admin;
    private $apiFacturacion;
    private $numLetra;
    private $pedido;
    private $generadorXML;
    private $cuentascobrar;


    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->usuario = new Usuario();
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();

        $this->ventas = new Ventas();
        $this->inventario = new Inventario();
        $this->clientes = new Clientes();
        $this->hotel = new Hotel();
        //$this->correlativo = new Correlativo();
        $this->generadorXML= new GeneradorXML();
        $this->apiFacturacion= new ApiFacturacion();
        $this->numLetra = new Nmletras();
        $this->pedido = new Pedido();
        $this->cuentascobrar = new Cuentascobrar();
    }

    //VISTAS
    public function gestionar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            $filtro = false;
            if(isset($_POST['enviar_registro'])){
                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $pago_filtro = $_POST['pago_filtro'];
                if($pago_filtro==''){
                    $cuentas_por_cobrar = $this->cuentascobrar->cuentas_cobrar($fecha_ini,$fecha_fin);
                    $pago_filtro = 'TODOS';
                }else{
                    $pago_filtro = $_POST['pago_filtro'];
                    $cuentas_por_cobrar = $this->cuentascobrar->cuentas_cobrar_por_filtro($fecha_ini,$fecha_fin,$pago_filtro);
                }
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cuentascobrar/gestionar.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function ver_detalle(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $sacar_simbolo=$this->cuentascobrar->sacar_simbolo($id);
            if($sacar_simbolo->id_moneda==1){
                $moneda="S/. ";
            }else{
                $moneda="$. ";
            }

            $detalle_cuentas = $this->cuentascobrar->detalles_cuentas($id);
            $sacar_id_cliente = $this->cuentascobrar->sacar_id_cliente($id);
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $data_caja=$this->nav->listar_data_caja_actual();
            $data_caja_=$this->nav->listar_data_caja_actual_();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cuentascobrar/ver_detalle.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    //FUNCIONES
    //FUNCION PRA ELIMINAR EL DETALLE DE LA CUENTA
    public function eliminar_cuenta_detalle(){
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            if($ok_data) {
                $id_cuenta_detalle = $_POST['id_cuenta_detalle'];
                $id_cuenta = $_POST['id_cuenta'];
                if($_POST['id_rack_detalle']==""){
                    $id_rack_detalle=0;
                }else{
                    $id_rack_detalle = $_POST['id_rack_detalle'];
                }
                if($_POST['id_comanda_detalle']==""){
                    $id_comanda_detalle=0;
                }else{
                    $id_comanda_detalle = $_POST['id_comanda_detalle'];
                }
                $id_user = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $clave_tipo=1;
                //PARA LA CONTRASEÑA
                $eliminar_clave = $_POST["eliminar_clave"];
                $existe_eliminacion = $this->hotel->existe_clave_eliminacion();
                if(!empty($existe_eliminacion)){
                    if($eliminar_clave==$existe_eliminacion->clave_eliminacion_clave){
                        $eliminar_cuenta_detalle = $this->cuentascobrar->eliminar_cuenta_detalle($id_cuenta_detalle);
                        $this->hotel->usar_clave_eliminacion($id_user,$id_rack_detalle,$id_comanda_detalle,$clave_tipo,$existe_eliminacion->id_clave_eliminacion);
                        if($eliminar_cuenta_detalle==1){
                            $aviso=0;
                            //PRIMERO SACAMOS EL MONTO A RESTAR YA SEA DE COMANDAS O DE RACK DETALLES
                            if(!empty($id_rack_detalle)){
                                $buscar_monto_resta=$this->cuentascobrar->buscar_monto_resta($id_rack_detalle);
                                $monto_resta = $buscar_monto_resta->rack_detalle_subtotal;
                            }else{
                                $buscar_monto_resta_c=$this->cuentascobrar->buscar_monto_resta_comanda($id_comanda_detalle);
                                $monto_resta = $buscar_monto_resta_c->comanda_detalle_total;
                                //se procede a cambiar estado de eliminado en la tabla de comanda_detalle
                                $fecha_eliminacion = date('Y-m-d H:i:s');
                                $comanda_detalle_eliminacion = 'Eliminado desde cuentas por cobrar';
                                $cambiar_estado = $this->pedido->eliminar_comanda_detalle($comanda_detalle_eliminacion,$fecha_eliminacion,$id_comanda_detalle);
                            }
                            //AQUI REALIZAMOS LA RESTA
                            $result = $this->cuentascobrar->restar_en_cuenta($monto_resta,$id_cuenta);
                            //AQUI HAREMOS LA VALIDACION PARA QUE SE BORRE TODA LA CUENTA SIEMPRE Y CUANDO NO EXISTA MAS DETALLES PENDIENTES DE PAGO
                            if($result==1){
                                $buscar_detalles_pendientes = $this->cuentascobrar->buscar_detalles_pendientes($id_cuenta);
                                if(empty($buscar_detalles_pendientes)){
                                    $result = $this->cuentascobrar->eliminar_toda_cuenta($id_cuenta);
                                    $aviso=1;
                                }else{
                                    //BUSCAMOS SI HAYE DETALLES PENDIENTES DE PAGO
                                    $ver_detalles_pendientes = $this->cuentascobrar->ver_detalles_pendientes($id_cuenta);
                                    if(empty($ver_detalles_pendientes)){
                                        $result=$this->cuentascobrar->actualizar_estado_cuenta($id_cuenta);
                                    }
                                }
                            }
                        }
                    }else{
                        $result=3;
                    }
                }
            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message,"aviso"=>$aviso)));
    }




}
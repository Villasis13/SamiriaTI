<?php
require 'app/models/Pedido.php';
require 'app/models/Archivo.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Mesa.php';
require 'app/models/cantidad_en_letras.php';
require 'app/models/Ventas.php';
require 'app/models/Clientes.php';
require 'app/models/Hotel.php';
require 'app/models/Cuentascobrar.php';

class PedidoController{
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $archivo;
    private $usuario;
    private $rol;
    private $mesa;
    private $pedido;
    private $venta;
    private $cliente;
    private $hotel;
    private $cuentascobrar;

    public function __construct(){
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->archivo = new Archivo();
        $this->usuario = new Usuario();
        $this->rol = new Rol();
        $this->mesa = new Mesa();
        $this->pedido = new Pedido();
        $this->venta = new Ventas();
        $this->cliente = new Clientes();
        $this->hotel = new Hotel();
        $this->cuentascobrar = new Cuentascobrar();
    }
    public function gestionar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            //$mesas = $this->pedido->listar_mesas($id_usuario);
            $fecha_hoy = date('y-m-d');
            $mesas = $this->pedido->listar_mesas_();
            $mesas_ = $this->pedido->listar_mesas_reserva();
            $mesas_junte = $this->pedido->listar_mesas_junte();
            $mesas_tg = $this->pedido->listar_mesas_tg();
            $ocultas = $this->pedido->listar_mesas_ocultas();
            $reservas = $this->pedido->listar_reservas($fecha_hoy);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/gestionar.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function pedidos_eliminados(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
            $fecha_i = date('Y-m-d');
            $fecha_f = date('Y-m-d');
            if(isset($_POST['enviar_fecha'])){
                $fecha_i = $_POST['fecha_filtro'];
                $fecha_f = $_POST['fecha_filtro_fin'];
                $pedidos_eliminados = $this->pedido->listar_pedidos_eliminados($fecha_i,$fecha_f);
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/pedidos_eliminados.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function delivery(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $tipos_documento = $this->cliente->listar_tipos_documentos();
            $familia = $this->pedido->listar_familias();
            $tipo_pago = $this->pedido->listar_tipo_pago();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/delivery.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function detalle_delivery(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);

            $id_mesa = 0;
            //$jalar_id_comanda = $this->pedido->jalar_fila($id);
            $pedidos = $this->pedido->listar_detalle_x_comanda($id);
            $venta_productos = $this->pedido->listar_productos_venta($id);
            $dato_pedido = $this->pedido->jalar_comanda_delivery($id);
            $tipo_pago = $this->pedido->listar_tipo_pago();
            $datos_cliente = $this->pedido->datos_cliente($id);
            if($datos_cliente->id_tipodocumento == 4){
                $cliente_nombre = $datos_cliente->cliente_razonsocial;
            }else{
                $cliente_nombre = $datos_cliente->cliente_nombre;
            }
            if($dato_pedido->comanda_nombre_delivery != ""){
                $cliente_nombre = $dato_pedido->comanda_nombre_delivery;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/detalle_delivery.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_delivery(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = 0;
            $pedidos = $this->pedido->listar_pedidos_delivery($id);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/historial_delivery.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_delivery_entregados(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = 0;
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
            if(isset($_POST['enviar_fecha'])){
                $fecha_filtro = $_POST['fecha_filtro'];
                $fecha_filtro_fin = $_POST['fecha_filtro_fin'];
                $pedidos = $this->pedido->listar_pedidos_delivery_entregados($id,$fecha_filtro,$fecha_filtro_fin);
            } else {
                $pedidos = $this->pedido->listar_pedidos_delivery_entregados($id,$fecha_filtro,$fecha_filtro_fin);
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/historial_delivery_entregados.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function asignar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
            //$tipo_afectacion = $this->pedido->listar_tipo_afectacion();
            $data_mesa=$this->mesa->mesa($id);
            $producto = $this->pedido->listar_productos();
            $familia = $this->pedido->listar_familias();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/asignar.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function listar_grupos(){
        try {
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $grupos = $this->pedido->listar_grupos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/listar_grupos.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function listado_detalle_grupo(){
        try {
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $dato = $this->pedido->listar_grupo_vistas($id);
            $listar_pedidos = $this->pedido->listar_productos_cocina($id);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/listado_detalle_grupo.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function detalle_pedido(){
        try {
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);

            //$dato = $this->pedido->jalar($id);
            $ultimo_valor = $this->pedido->ultimo_pedido($id);
            //Trae pedidos sin pagar a esa comanda, para evitar pedidos perdidos
            $this->pedido->actualizar_ultimos_pedidos_pendientes($ultimo_valor->id_comanda, $id);
            $this->pedido->actualizar_total_comanda($ultimo_valor->id_comanda);

            $ultimo_valor_ = $ultimo_valor->id_comanda;
            $pedidos = $this->pedido->listar_pedidos_por_mesa($id,$ultimo_valor_);
            if(count($pedidos) < 1){
                $this->pedido->actualizar_estado_mesa($id);
            }

            //$tipo_cambio = $this->pedido->obtener_tipo_cambio();
            $dato_pedido = $this->pedido->jalar_comanda($id,$ultimo_valor_);
            $mesas = $this->pedido->listar_mesa();
            $igv = $this->pedido->listar_igv();
            $tipo_pago = $this->pedido->listar_tipo_pago();
            $cliente = $this->pedido->listar_clientes();
            $tipos_documento = $this->cliente->listar_tipos_documentos();
            $mesas_pn = $this->pedido->listar_mesas_pn($id);

            $producto = $this->pedido->listar_productos();
            $familia = $this->pedido->listar_familias();

            $habitaciones = $this->pedido->listar_habitaciones();
            $comanda = $this->pedido->extraer_id_comanda_destino($id);
            $clientes_cuenta = $this->pedido->clientes_cuenta();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/detalle_pedido.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_pedidos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $mesas = $this->mesa->listar_mesas();
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_registro'])){
                if($_POST['id_mesa']!=""){
                    $mesa = $_POST['id_mesa'];
                    $pedidos = $this->pedido->listar_pedidos_x_mesa($_POST['id_mesa'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                }else{
                    $pedidos = $this->pedido->listar_pedidos($_POST['fecha_inicio'], $_POST['fecha_final']);
                }
                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/historial_pedidos.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_pedidos_gratis(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $mesas = $this->mesa->listar_mesas();
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_registro'])){
                if($_POST['id_mesa']!=""){
                    $pedidos = $this->pedido->listar_pedidos_x_mesa_gratis($_POST['id_mesa'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $mesa = $_POST['id_mesa'];
                }else{
                    $pedidos = $this->pedido->listar_pedidos_gratis($_POST['fecha_inicio'], $_POST['fecha_final']);
                }
                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/historial_pedidos_gratis.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function detalle_pedido_ver(){
        try {
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id_mesa = $_GET['id_mesa'];
            $id_comanda = $_GET['id_comanda'];
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
            $dato = $this->pedido->jalar($id_mesa);
//            $ultimo_valor = $this->pedido->ultimo_pedido($id_mesa);
//            $ultimo_valor_ = $ultimo_valor->id_comanda;
            $pedidos = $this->pedido->listar_pedidos_por_mesa($id_mesa,$id_comanda);
            $dato_pedido = $this->pedido->jalar_comanda($id_mesa,$id_comanda);
            $mesas = $this->pedido->listar_mesa();
            $igv = $this->pedido->listar_igv();
            $tipo_pago = $this->pedido->listar_tipo_pago();
            $cliente = $this->pedido->listar_clientes();
            $tipos_documento = $this->cliente->listar_tipos_documentos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'pedido/detalle_pedido_ver.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function ticket_pedido(){
        try{
            $id = $_POST['id'];
            /*$dato = $this->pedido->jalar($id);
            $ultimo_valor = $this->pedido->ultimo_pedido($id);*/
            $ultimo_valor_ = $id;
            $pedidos = $this->pedido->listar_detalle_x_comanda_para_precuenta($ultimo_valor_);
            $dato_pedido = $this->pedido->listar_comanda_x_id($ultimo_valor_);

            $fecha_hoy = date('Y-m-d');
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);

            $jalar_id_caja = $this->pedido->jalar_id_caja_aperturada($id_usuario);
            $caja_dato = $this->pedido->jalar_datos_caja_numero($jalar_id_caja->id_caja_numero);//datos de la caja para la captura del nombre de la impresora

            require _VIEW_PATH_ . 'pedido/ticket_pedido.php';
            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));

    }

    public function ticket_comanda(){
        try{

            $id_comanda = $_POST['comanda_ultimo'];
            $fecha_hoy = date('Y-m-d');
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);

            $jalar_id_caja = $this->pedido->jalar_id_caja_aperturada($id_usuario);
            $caja_dato = $this->pedido->jalar_datos_caja_numero($jalar_id_caja->id_caja_numero);//datos de la caja para la captura del nombre de la impresora

            $comanda = $this->pedido->listar_comanda_x_id($id_comanda);
            $detalle_comanda =$this->pedido->listar_detalle_x_comanda($id_comanda);
            $items_seleccionados = $_POST['imprimir_detalle'];
            $nombre_ticket='Ticketera';

            require _VIEW_PATH_ . 'pedido/ticket_comanda_check.php';

            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));

    }

    //FUNCION PARA TICKET VENTA
    public function ticket_venta(){
        try{

            $id = $_POST['id'];
            //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
            include('libs/ApiFacturacion/phpqrcode/qrlib.php');

            $venta = $this->venta->listar_venta_x_id_res($id);
            $detalle_venta =$this->venta->listar_venta_detalle_x_id_venta($id);
            $comanda = $this->pedido->listar_detalle_x_comanda_detalle($detalle_venta[0]->id_comanda_detalle);
            $empresa = $this->venta->listar_empresa_x_id_empresa($venta->id_empresa);
            $cliente = $this->venta->listar_clienteventa_x_id($venta->id_cliente);
            //INICIO - CREACION QR
            $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
            $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
            $ruta = 'libs/ApiFacturacion/imagenqr/';
            $ruta_qr = $ruta.$nombre_qr.'.png';
            QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
            //FIN - CREACION QR
            if($venta->venta_tipo == "03"){
                $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
            }elseif($venta->venta_tipo == "01"){
                $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
            }elseif($venta->venta_tipo == "20"){
                $venta_tipo = "NOTA DE VENTA";
            }
            if($cliente->id_tipodocumento == "4"){
                $cliente_nombre = $cliente->cliente_razonsocial;
            }else{
                $cliente_nombre = $cliente->cliente_nombre;
            }
            $fecha_hoy = date('Y-m-d');
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);

            $jalar_id_caja = $this->pedido->jalar_id_caja_aperturada($id_usuario);
            $caja_dato = $this->pedido->jalar_datos_caja_numero($jalar_id_caja->id_caja_numero);//datos de la caja para la captura del nombre de la impresora
            require _VIEW_PATH_ . 'pedido/ticket_venta.php';
            //INICIO - TICKET DELIVERY
            //$venta = $this->venta->listar_venta_x_id($id);
            if($venta->id_mesa == 0){
//                $cliente = $this->pedido->listar_clienteventa_x_id_delivery($id_cliente);
                $id_comanda = $comanda->id_comanda;
                $datos_ticket = $this->pedido->jalar_datos_comanda($id_comanda);
                $cambiar_estado_comanda = $this->pedido->cambiar_estado_comanda_delivery($id_comanda);

                if($cliente->id_tipodocumento == "4"){
                    $cliente_nombre = $cliente->cliente_razonsocial;
                }else{
                    $cliente_nombre = $datos_ticket->comanda_nombre_delivery;
                }

                require _VIEW_PATH_ . 'pedido/ticket_venta_delivery.php';
                //echo "<script>window.open("._SERVER_."'Pedido/ticket_venta/".$id_venta."','_blank')</script>";
            }

            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));

    }

    public function ticket_venta_delivery(){
        try{

            $id = $_POST['id'];
            //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
            include('libs/ApiFacturacion/phpqrcode/qrlib.php');

            $venta = $this->venta->listar_venta_x_id($id);
            $cliente = $this->pedido->listar_clienteventa_x_id_delivery($venta->id_cliente);

            if($cliente->id_tipodocumento == "4"){
                $cliente_nombre = $cliente->cliente_razonsocial;
            }else{
                $cliente_nombre = $cliente->cliente_nombre;
            }

            $dato = $this->pedido->jalar($id);
            $ultimo_valor = $this->pedido->ultimo_pedido($id);
            $ultimo_valor_ = $ultimo_valor->id_comanda;
            $pedidos = $this->pedido->listar_pedidos_por_mesa($id,$ultimo_valor_);
            $dato_pedido = $this->pedido->jalar_comanda($id,$ultimo_valor_);
            $fecha_hoy = date('Y-m-d');
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);

            $jalar_id_caja = $this->pedido->jalar_id_caja_aperturada($id_usuario);
            $caja_dato = $this->pedido->jalar_datos_caja_numero($jalar_id_caja->id_caja_numero);//datos de la caja para la captura del nombre de la impresora


            require _VIEW_PATH_ . 'pedido/ticket_venta_delivery.php';
            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));
    }

    //FUNCIONES
    /*public function listar_precio_producto(){

        try{
            $id_producto = $_POST['id_producto'];
            $result = $this->pedido->listar_precio_producto($id_producto);

            $pedido = "<select class='form-control' id='producto_precio_venta' name='producto_precio_venta'>";
            foreach($result as $c) {
                $pedido .= "<option>" . $c->producto_precio_venta . "</option>";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($pedido);
    }*/

    public function ver_productos_x_delivery(){
        try{
            //$id_producto = $_POST['id_producto'];
            //$result = $this->pedido->listar_precio_producto($id_producto);
            $parametro_ = $_POST['parametro'];
            $parametro=str_replace(".--","+",$parametro_);
            $result = $this->pedido->listar_busqueda_productos($parametro);

            $producto = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            $anho = date('Y');
            if($anho == "2021"){
                $icbper = 0.30;
            }elseif($anho == "2022"){
                $icbper = 0.40;
            }else{
                $icbper = 0.50;
            }
            foreach ($result as $r){
                $op_gravadas=0.00;
                $op_exoneradas=0.00;
                $op_inafectas=0.00;
                $op_gratuitas=0.00;
                $igv=0.0;
                $igv_porcentaje=0.18;
                if($r->producto_precio_codigoafectacion == 10){
                    /*$op_gravadas = $r->producto_precio_venta;
                    $igv = $op_gravadas * $igv_porcentaje;
                    $total = $op_gravadas + $igv;*/
                    $total = $r->producto_precio_venta;
                }else{
                    $total = $r->producto_precio_venta;
                }
                /*if($r->id_receta == "131"){
                    $total = $total + $icbper;
                }*/
                $producto .= " <tr>
                                <td>". $r->producto_nombre ."</td>
                                <td>". $total . "</td>
                                <td>
                                    <button class='btn btn-success' data-toggle='modal' onclick='guardar_pedido(".$r->id_producto. ",\"".$r->producto_nombre."\",\"".$total."\",\"".$r->producto_precio_codigoafectacion."\")' data-target='#asignar_pedido'><i class='fa fa-check'></i> </button>
                                    <a class='btn btn-primary' href='"._SERVER_ .$r->producto_foto."' target='_blank'><i class='fa fa-eye'></i></a>
                                    <input type='hidden' id='".$r->producto_precio_codigoafectacion."'>
                                <td>
                            </tr>";
            }
            $producto .= "</tbody></table>";
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($producto);
    }

    //FUNCION PARA BUSCAR CLIENTE EN LA VISTA DE PEDIDOS POR MESA
    public function buscar_cliente(){
        try{
            $parametro = $_POST['parametro_c'];
            $result = $this->pedido->buscar_cliente($parametro);
            $cliente = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>DNI / RUC</th>
                                            <th>Dirección</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            foreach ($result as $r) {
                if($r->id_tipodocumento != 4){
                    $nombre = $r->cliente_nombre;
                }else{
                    $nombre = $r->cliente_razonsocial;
                }
                //$direcciones = $this->pedido->direcciones($r->id_cliente);
                $cliente .= " <tr>
                                <td>" . $nombre . "</td>
                                <td>" . $r->cliente_numero . "</td>
                                 
                                <td>" . $r->cliente_direccion . "
                                </td>
                                
                                <td><button class='btn btn-primary' onclick='guardar_cliente(" . $r->id_cliente . ",\"" . $nombre . "\",\"" . $r->cliente_numero . "\",\"" . $r->cliente_direccion . "\",\"" . $r->id_tipodocumento . "\")' ><i class='fa fa-check'></i></button><td>
                            </tr>";
            }
            $cliente .= "</tbody></table>";
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($cliente);
    }

    //NUEVA FUHNCION
    public function buscar_descuento(){
        try{
            $parametro = $_POST['parametro_piscina']; //dni
            $comanda_ultimo = $_POST['comanda_ultimo']; //8593
            $id = $_POST['id_mesa'];

            $pedidos = $this->pedido->listar_pedidos_por_mesa($id,$comanda_ultimo);
            $dia_actual = strtolower(date("l"));
            $hora_actual = date("H:i");
            //$dias_permitidos = ["monday"];
            $dias_permitidos = ["thursday", "friday", "saturday"];
            $dias_permitidos_2 = ["saturday", "sunday"];
            $hora_inicio = "19:00"; // Hora de inicio permitida
            $hora_fin = "23:30";   // Hora de fin permitida
            $hora_inicio_f = "12:30";
            $hora_fin_f = "17:00";
            $total = 0;
            foreach ($pedidos as $p) {
                $hora_pedido = date('H:i', strtotime($p->comanda_detalle_fecha_registro));
                if (in_array($dia_actual, $dias_permitidos) && ($hora_pedido >= $hora_inicio && $hora_pedido <= $hora_fin)) {
                    $total = 0;
                } elseif (in_array($dia_actual, $dias_permitidos_2) && ($hora_pedido >= $hora_inicio_f && $hora_pedido <= $hora_fin_f)) {
                    $total = 0;
                } else {
                    $comanda_total = $p->comanda_detalle_total;
                    $total = $total + $comanda_total;

                }
            }

            $result = $this->pedido->buscar_consumo_piscina($parametro);
            $cliente_ = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Tipo Desct.</th>
                                            <th>Cliente</th>
                                            <th>DNI / RUC</th>
                                            <th>Valor Desct.</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            foreach ($result as $r) {
                if($r->venta_piscina==1){
                    $tipo_desct='PISCINA';
                    $stylo = "style='color:blue;font-weight: bold;'";
                }elseif($r->venta_piscina==4){
                    $tipo_desct='BARRA LIBRE O BUFFET';
                    $stylo = "style='color:green;font-weight: bold;'";
                }
                if($r->id_tipodocumento != 4){
                    $nombre = $r->cliente_nombre;
                }else{
                    $nombre = $r->cliente_razonsocial;
                }
                //$direcciones = $this->pedido->direcciones($r->id_cliente);
                $cliente_ .= " <tr>
                                <td $stylo>" . $tipo_desct . "</td>
                                <td>" . $nombre . "</td>
                                <td>" . $r->cliente_numero . "</td>
                                <td>" . $r->venta_consumo_valido . "</td>
                                <td><button class='btn btn-primary' onclick='realizar_resta_piscina(" . $r->venta_consumo_valido .",\"" . $r->id_venta . "\",\"" . $r->venta_correlativo . "\",\"" . $r->venta_piscina . "\",\"" . $total ."\")' ><i class='fa fa-check'></i></button></td>
                            </tr>";
            }
            $cliente_ .= "</tbody></table>";
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($cliente_);
    }
    //FUNCION PARA BUSCAR CLIENTE EN LA VISTA QUE SE REALIZA EL PEDIDO DEIVERY
    public function buscar_cliente_delivery(){
        try{
            $parametro = $_POST['parametro_delivery'];
            $result = $this->pedido->buscar_cliente($parametro);
            $cliente_delivery = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>DNI / RUC</th>
                                            <th>Dirección</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            foreach ($result as $r) {
                $nombre_del_cliente = $r->cliente_nombre.$r->cliente_razonsocial;

                //$direcciones = $this->pedido->direcciones($r->id_cliente);
                $cliente_delivery .= " <tr>
                                <td>" . $nombre_del_cliente . "</td>
                                <td>" . $r->cliente_numero . "</td>
                                 
                                <td>
                                    <select name='id_cliente_direccion".$r->id_cliente."' id='id_cliente_direccion".$r->id_cliente."' class='form-control'>";
                if($r->cliente_direccion != ""){
                    $cliente_delivery .="<option >". $r->cliente_direccion."</option>";
                }
                if($r->cliente_direccion_2 != ""){
                    $cliente_delivery .="<option >". $r->cliente_direccion_2."</option>";
                }
                $cliente_delivery .= "</select>
                                </td>
                                
                                <td><button class='btn btn-success' onclick='guardar_cliente_delivery(" . $r->id_cliente . ",\"" . $nombre_del_cliente . "\",\"" . $r->cliente_numero . "\",\"" . $r->cliente_direccion . "\",\"" . $r->cliente_telefono . "\")' ><i class='fa fa-check'></i> Agregar</button><td>
                            </tr>";
            }
            $cliente_delivery .= "</tbody></table>";
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($cliente_delivery);
    }

    //FUNCION PARA BUSCAR CLIENTE EN LA VISTA DE LOS PAGOS DEL CLIENTE
    public function buscar_cliente_delivery_pagos(){
        try{
            $parametro = $_POST['parametro_delivery'];
            $result = $this->pedido->buscar_cliente($parametro);
            $cliente_delivery_pagos = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>DNI / RUC</th>
                                            <th>Dirección</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            foreach ($result as $r) {

                //$direcciones = $this->pedido->direcciones($r->id_cliente);
                $cliente_delivery_pagos .= " <tr>
                                <td>" . $r->cliente_nombre . "</td>
                                <td>" . $r->cliente_numero . "</td>
                                 
                                <td>
                                    <select name='id_cliente_direccion".$r->id_cliente."' id='id_cliente_direccion".$r->id_cliente."' class='form-control'>
                                    <option >". $r->cliente_direccion."</option>
                                    <option >". $r->cliente_direccion_2."</option>
                                    </select>
                                </td>
                                
                                <td><button class='btn btn-success' onclick='guardar_cliente_delivery_pagos(" . $r->id_cliente . ",\"" . $r->cliente_nombre . "\",\"" . $r->cliente_numero . "\",\"" . $r->cliente_direccion . "\",\"" . $r->cliente_telefono . "\")' ><i class='fa fa-check'></i> Agregar</button><td>
                            </tr>";
            }
            $cliente_delivery_pagos .= "</tbody></table>";
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($cliente_delivery_pagos);
    }

    public function ver_productos(){
        try{
            //$id_producto = $_POST['id_producto'];
            //$result = $this->pedido->listar_precio_producto($id_producto);
            $parametro_ = $_POST['parametro'];
            $parametro=str_replace(".--","+",$parametro_);
            $result = $this->pedido->listar_busqueda_productos($parametro);

            $producto = "";
            $anho = date('Y');
            if($anho == "2021"){
                $icbper = 0.30;
            }elseif($anho == "2022"){
                $icbper = 0.40;
            }else{
                $icbper = 0.50;
            }
            foreach ($result as $r){
                $buscar_stock="";
                $buscar_stock=$this->pedido->buscar_stock($r->id_receta);
                //DEBERIAMOS PASAR A REVISAR TODAS LAS COMANDAS PENDIENTES PARA SABER EL REAL STOCK
                $revisar_comandas_stock=$this->pedido->revisar_comandas_stock($r->id_producto);
                $cantidad=0;
                foreach ($revisar_comandas_stock as $rc){
                    $cantidad += $rc->comanda_detalle_cantidad;
                }
                //resta del nuevo stock
                $cantidad_stock=$buscar_stock->cantidad_nueva_distribuida;
                $nuevo_stock = $cantidad_stock - $cantidad;
                $op_gravadas=0.00;
                $op_exoneradas=0.00;
                $op_inafectas=0.00;
                $op_gratuitas=0.00;
                $igv=0.0;
                $igv_porcentaje=0.18;
                if($r->producto_precio_codigoafectacion == 10){
                    $op_gravadas = $r->producto_precio_venta;
                    $igv = $op_gravadas * $igv_porcentaje;
                    $total = $op_gravadas + $igv;
                }else{
                    $total = $r->producto_precio_venta;
                }
                /*if($r->id_receta == "131"){
                    $total = $total + $icbper;
                }*/
                $producto .= " <tr>
                <td>". $r->producto_nombre ."</td>
                <td>". $nuevo_stock ."</td>
                <td>(". $cantidad .")</td>
                <td>". $total . "</td>
                <td>";

                if (!empty($buscar_stock)) {
                    if($buscar_stock->cantidad_nueva_distribuida != 0){
                        $producto .= "<button class='btn btn-success' data-toggle='modal' onclick='guardar_pedido(".$r->id_producto. ",\"".$r->producto_nombre."\",\"".$total."\",\"".$nuevo_stock."\",\"".$r->producto_precio_codigoafectacion."\")' data-target='#asignar_pedido'><i class='fa fa-check'></i> </button>";
                    }else{
                        $producto .= "<button class='btn btn-disabled' disabled><i class='fa fa-check'></i> </button>";
                    }
                } else {
                    $producto .= "<button class='btn btn-success' data-toggle='modal' onclick='guardar_pedido(".$r->id_producto. ",\"".$r->producto_nombre."\",\"".$total."\",\"".$nuevo_stock."\",\"".$r->producto_precio_codigoafectacion."\")' data-target='#asignar_pedido'><i class='fa fa-check'></i> </button>";
                }

                $producto .= "<a  style='display: none' class='btn btn-primary' href='"._SERVER_ .$r->producto_foto."' target='_blank'><i class='fa fa-eye'></i></a>
              <input type='hidden' id='".$r->producto_precio_codigoafectacion."'>
              </td>
            </tr>";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("producto" =>$producto)));
    }

    //FUNCION PARA VER PRODUCTOS NUEVOS
    public function ver_productos_nuevo(){
        try{
            //$id_producto = $_POST['id_producto'];
            //$result = $this->pedido->listar_precio_producto($id_producto);
            $parametro_ = $_POST['parametro'];
            $parametro=str_replace(".--","+",$parametro_);
            $result = $this->pedido->listar_busqueda_productos($parametro);

            $producto_nuevo = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Stock</th>
                                            <th>Stock en Comandas</th>
                                            <th>Precio</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            $anho = date('Y');
            if($anho == "2021"){
                $icbper = 0.30;
            }elseif($anho == "2022"){
                $icbper = 0.40;
            }else{
                $icbper = 0.50;
            }
            foreach ($result as $r){
                $buscar_stock="";
                $buscar_stock=$this->pedido->buscar_stock($r->id_receta);
                //DEBERIAMOS PASAR A REVISAR TODAS LAS COMANDAS PENDIENTES PARA SABER EL REAL STOCK
                $revisar_comandas_stock=$this->pedido->revisar_comandas_stock($r->id_producto);
                $cantidad=0;
                foreach ($revisar_comandas_stock as $rc){
                    $cantidad += $rc->comanda_detalle_cantidad;
                }
                //resta del nuevo stock
                $cantidad_stock=$buscar_stock->cantidad_nueva_distribuida;
                $nuevo_stock = $cantidad_stock - $cantidad;
                $op_gravadas=0.00;
                $op_exoneradas=0.00;
                $op_inafectas=0.00;
                $igv=0.0;
                $igv_porcentaje=0.18;
                if($r->producto_precio_codigoafectacion == 10){
                    $op_gravadas = $r->producto_precio_venta;
                    $igv = $op_gravadas * $igv_porcentaje;
                    $total = $op_gravadas + $igv;
                }else{
                    $total = $r->producto_precio_venta;
                }
                /*if($r->id_receta == "131"){
                    $total = $total + $icbper;
                }*/
                $producto_nuevo .= " <tr>
                                <td>". $r->producto_nombre ."</td>
                                <td>". $nuevo_stock ."</td>
                                <td>(". $cantidad .")</td>
                                <td>". $total . "</td>
                                
                                <td>";
                if (!empty($buscar_stock)) {
                    if($buscar_stock->cantidad_nueva_distribuida != 0){
                        $producto_nuevo .= "<button class='btn btn-success' onclick='guardar_pedido_nuevo(".$r->id_producto. ",\"".$r->producto_nombre."\",\"".$total."\",\"".$nuevo_stock."\")' ><i class='fa fa-check'></i> Agregar</button>";
                    }else{
                        $producto_nuevo .= "<button class='btn btn-disabled' disabled><i class='fa fa-check'></i> </button>";
                    }
                } else {
                    $producto_nuevo .= "<button class='btn btn-success' onclick='guardar_pedido_nuevo(".$r->id_producto. ",\"".$r->producto_nombre."\",\"".$total."\",\"".$nuevo_stock."\")' ><i class='fa fa-check'></i> Agregar</button>";
                }
                $producto_nuevo .= "<input type='hidden' id='".$r->producto_precio_codigoafectacion."'>
                              </td>    
                            </tr>";
                $resultado = $this->pedido->listar_busqueda_productos_seleccion($r->id_producto);
                $ver_seleccion = "<label>Precio por Producto ".$resultado->producto_nombre." : ".$resultado->producto_precio_venta."</label>
                                 <input type='hidden' id='pro_".$resultado->id_producto."' name='id_producto' value='".$resultado->producto_precio_venta."'>";
            }
            $producto_nuevo .= "</tbody></table>";
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("producto_nuevo" => $producto_nuevo, "ver_seleccion" => $ver_seleccion));
    }
    //FUNCION PARA GUARDAR UN PEDIDO O COMANDA
    public function guardar_comanda(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validacion de datos
            if($ok_data) {
                $model = new Pedido();
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $fecha = date('Y-m-d H:i:s');
                $microtime = microtime(true);
                $id_mesa = $_POST['id_mesa'];
                $model->id_mesa = $id_mesa;
                $model->id_usuario = $id_usuario;
                $model->comanda_total = $_POST['comanda_total'];
                $model->comanda_fecha_registro = $fecha;
                $model->comanda_cantidad_personas = $_POST['comanda_cantidad_personas'];
                $model->comanda_estado = 1;
                $model->comanda_codigo = $microtime;
                $fecha_buscar = date('Y-m-d');
                $ultima_comanda = $this->pedido->listar_ultima_comanda($fecha_buscar);
                if(isset($ultima_comanda->id_comanda)){
                    $fila = explode('-',$ultima_comanda->comanda_correlativo);
                    if(count($fila)>0){
                        for($i=0;$i<count($fila)-1;$i++){
                            $suma = $fila[1] + 1;
                            $model->comanda_correlativo = $fila[0].'-'.$suma;
                        }
                    }
                }else{
                    $model->comanda_correlativo = date('dmy').'-'. + 1;
                }
                $guardar_comanda = $this->pedido->guardar_comanda($model);
                if($guardar_comanda == 1){
                    $contenido = $_POST['contenido'];
                    $imprimir = 0;
                    if(count_chars($contenido)>0){
                        $filas=explode('/./.',$contenido);
                        $datos = $this->pedido->listar_comanda_por_mt($microtime);
                        if(count($filas)>0){
                            for ($i=0;$i<count($filas)-1;$i++){
                                $modelDSI=new Pedido();
                                $celdas=explode('-.-.',$filas[$i]);
                                $modelDSI->id_comanda = $datos->id_comanda;
                                $modelDSI->id_usuario_a = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                                $modelDSI->id_producto = $celdas[0];
                                $modelDSI->comanda_detalle_precio = $celdas[2];
                                $modelDSI->comanda_detalle_cantidad = $celdas[3];
                                $modelDSI->comanda_detalle_despacho = $celdas[4];
                                $modelDSI->comanda_detalle_total = $celdas[6];
                                $modelDSI->comanda_detalle_observacion = $celdas[5];
                                $modelDSI->comanda_detalle_fecha_registro = $fecha;
                                $modelDSI->comanda_detalle_estado = 1;
                                $codigo = microtime();
                                $modelDSI->codigo = $codigo;
                                $modelDSI->comanda_detalle_lugar_final = 1;
                                if($celdas[7] == "SI"){
                                    $imprimir ++;
                                }
                                $result = $this->pedido->guardar_detalle_comanda($modelDSI);
                                if($result==1){
                                    //AQUI SE GUARDARAN DATOS EN LA TABLA NUEVA
                                    if(!empty($celdas[8])){
                                        $filitas=explode('/-/',$celdas[8]);
                                        $buscar_guardar_cd = $this->pedido->buscar_guardar_cd($codigo);
                                        if(count($filitas)>0){
                                            for ($j=0;$j<count($filitas)-1;$j++){
                                                $modelillo = new Pedido();
                                                $modelillo->id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                                                $modelillo->id_comanda_detalle = $buscar_guardar_cd->id_comanda_detalle;
                                                $modelillo->id_acompanamiento_detalle=$filitas[$j];
                                                $modelillo->comanda_acomp_fecha=date('Y-m-d H:i:s');
                                                $modelillo->comanda_acomp_estado=1;
                                                $result=$this->pedido->llenar_tabla_fantasma($modelillo);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if($result == 1){
                    $result = $this->pedido->cambiar_estado_mesa($id_mesa);
                    //INICIO - IMPRESION DE TICKET DE COMANDA
                    $id_comanda = $datos->id_comanda;
                    $comanda = $this->pedido->listar_comanda_x_id($id_comanda);
                    $detalle_comanda =$this->pedido->listar_detalle_x_comanda($id_comanda);

                    foreach ($detalle_comanda as $de) {
                        $detalle = $this->pedido->listar_detalle_x_comanda_detalle($de->id_comanda_detalle);
                        if ($detalle->id_grupo != 3) {
                            $nombre_ticket = "$detalle->grupo_ticketera";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
                        } else {
                            $nombre_ticket = "COCINA";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
                            $nombre_ticket = "BARRA";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
                        }
                    }
                    /*$nombre_ticket = "Ticketera";
                    if($imprimir>0){
                        require _VIEW_PATH_ . 'pedido/ticket_comanda.php';
                    }*/
                    //listamos los grupos que hay
//                    $grupos = $this->pedido->listar_grupos();
//                    foreach ($grupos as $g){
//                        if($g->id_grupo != 3){
//                            $nombre_ticket = $g->grupo_ticketera;
//                            if(!empty($detalle_comanda)){
//                                require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
//                            }
//                        }else{
//                            if(!empty($detalle_comanda)) {
//                                $nombre_ticket = "COCINA";
//                                require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
//                                $nombre_ticket = "BARRA";
//                                require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
//                            }
//                        }
//                    }
                    //FIN - IPRESION DE TICKET DE COMANDA
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA GUARDAR COMANDA NUEVA
    public function guardar_comanda_nuevo()
    {
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validacion de datos
            if ($ok_data) {
                //$model = new Pedido();
                $fecha = date('Y-m-d H:i:s');
                $id_comanda = $_POST['id_comanda'];
                $contenido = $_POST['contenido_pedido'];
                if(count_chars($contenido)>0){
                    $filas=explode('/./.',$contenido);
                    if(count($filas)>0){
                        for ($i=0;$i<count($filas)-1;$i++){
                            $modelDSI=new Pedido();
                            $celdas=explode('-.-.',$filas[$i]);
                            //AQUI SE HARA LA JUGADA
                            $id_producto = $celdas[0];
                            $cantidad = $celdas[3];
                            $total = $celdas[6];
                            //$buscar_exite_id_producto = $this->pedido->buscar_id_producto($celdas[0],$id_comanda);
                            //if(empty($buscar_exite_id_producto)){
                            //SI EL PRODUCTO NO EXISTE LO AGREGARA COMO NUEVO
                            $modelDSI->id_comanda = $id_comanda;
                            $modelDSI->id_usuario_a = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                            $modelDSI->id_producto = $celdas[0];
                            $modelDSI->comanda_detalle_precio = $celdas[2];
                            $modelDSI->comanda_detalle_cantidad = $cantidad;
                            $modelDSI->comanda_detalle_despacho = $celdas[4];
                            $modelDSI->comanda_detalle_total = $total;
                            $modelDSI->comanda_detalle_observacion = $celdas[5];
                            $modelDSI->comanda_detalle_fecha_registro = $fecha;
                            $codigo = microtime();
                            $modelDSI->comanda_detalle_estado = 1;
                            $modelDSI->codigo = $codigo;
                            $modelDSI->comanda_detalle_lugar_final = 1;
                            $result = $this->pedido->guardar_detalle_comanda($modelDSI);
                            if($result==1) {
                                $jalar_valor = $this->pedido->jalar_valor($id_comanda);
                                $nuevo_valor = $jalar_valor->total;
                                $actualizar_precio = $this->pedido->actualizar_nuevo_valor($id_comanda,$nuevo_valor);
                                //AQUI SE GUARDARAN DATOS EN LA TABLA NUEVA PARA EL MILAGRO NUMERO 2
                                if(!empty($celdas[7])){
                                    $filitas=explode('/-/',$celdas[7]);
                                    $buscar_guardar_cd = $this->pedido->buscar_guardar_cd($codigo);
                                    if(count($filitas)>0){
                                        for ($k=0;$k<count($filitas)-1;$k++){
                                            $modelillo = new Pedido();
                                            $modelillo->id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                                            $modelillo->id_comanda_detalle = $buscar_guardar_cd->id_comanda_detalle;
                                            $modelillo->id_acompanamiento_detalle=$filitas[$k];
                                            $modelillo->comanda_acomp_fecha=date('Y-m-d H:i:s');
                                            $modelillo->comanda_acomp_estado=1;
                                            $result=$this->pedido->llenar_tabla_fantasma($modelillo);
                                        }
                                    }
                                }
                                //FIN DEL MILAGRO
                            }

//                            }else{
//                                //SI EL PRODUCTO EXISTE SE LO SUMARA AL ANTERIOR
//                                $cantidad_e = $cantidad;
//                                $total_e = $total;
//                                $result = $this->pedido->update_comanda_detalle($cantidad_e,$total_e,$celdas[0],$id_comanda);
//                                if ($result == 1) {
//                                    $jalar_valor = $this->pedido->jalar_valor($id_comanda);
//                                    $nuevo_valor = $jalar_valor->total;
//                                    $actualizar_precio = $this->pedido->actualizar_nuevo_valor($id_comanda, $nuevo_valor);
//                                }
//                            }
                        }
                        //INICIO - IMPRESION DE TICKET DE COMANDA
//                        $id_comanda = $id_comanda;
                        $comanda = $this->pedido->listar_comanda_x_id($id_comanda);
                        $detalle_comanda =$this->pedido->listar_detalle_x_comanda_x_fecha($id_comanda, $fecha);
                        //$items_seleccionados = $_POST['impresion_check'];
                        //if(!empty($_POST['impresion_check'])){
                            //listamos los grupos que hay
                            $grupos = $this->pedido->listar_grupos();
                        foreach ($detalle_comanda as $de) {
                            $detalle = $this->pedido->listar_detalle_x_comanda_detalle($de->id_comanda_detalle);
                            if ($detalle->id_grupo != 3) {
                                $nombre_ticket = "$detalle->grupo_ticketera";
                                require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
                            } else {
                                $nombre_ticket = "COCINA";
                                require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
                                $nombre_ticket = "BARRA";
                                require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
                            }
                        }
                        //                            foreach ($grupos as $g){
                        //                                $nombre_ticket = $g->grupo_ticketera;
                        //                                $detalle_comanda = $this->pedido->listar_detalle_x_comanda_x_grupo_fecha($id_comanda, $g->id_grupo,$fecha);
                        //                                if(!empty($detalle_comanda)){
                        //                                    require _VIEW_PATH_ . 'pedido/ticket_comanda_x_grupo.php';
                        //                                }
                        //
                        //                            }

                        //FIN - IPRESION DE TICKET DE COMANDA
                    }
                }

            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA GUARDAR UN PEDIDO DEL DELIVERY
    public function guardar_delivery(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validacion de datos
            if($ok_data) {
                $model = new Pedido();
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $fecha = date('Y-m-d H:i:s');
                $microtime = microtime(true);
                $id_mesa = $_POST['id_mesa'];
                $model->id_mesa = 0;
                $model->id_usuario = $id_usuario;
                $model->id_cliente = $_POST['id_cliente'];
                $model->comanda_direccion_delivery = $_POST['comanda_direccion_delivery'];
                $model->comanda_telefono_delivery = $_POST['comanda_telefono_delivery'];
                $model->comanda_total = $_POST['comanda_total'];
                $model->comanda_fecha_registro = $fecha;
                $model->comanda_cantidad_personas = 1;
                $model->comanda_estado = 1;
                $model->comanda_codigo = $microtime;

                $fecha_buscar = date('Y-m-d');
                $ultima_comanda = $this->pedido->listar_ultima_comanda($fecha_buscar);
                if(isset($ultima_comanda->id_comanda)){
                    $fila = explode('-',$ultima_comanda->comanda_correlativo);
                    if(count($fila)>0){
                        for($i=0;$i<count($fila)-1;$i++){
                            $suma = $fila[1] + 1;
                            $model->comanda_correlativo = $fila[0].'-'.$suma;
                        }
                    }
                }else{
                    $model->comanda_correlativo = date('dmy').'-'. + 1;
                }
                $guardar_comanda = $this->pedido->guardar_comanda($model);
                if($guardar_comanda == 1){
                    $contenido = $_POST['contenido'];
                    if(count_chars($contenido)>0){
                        $filas=explode('/./.',$contenido);
                        $datos = $this->pedido->listar_comanda_por_mt($microtime);
                        if(count($filas)>0){
                            for ($i=0;$i<count($filas)-1;$i++){
                                $modelDSI=new Pedido();
                                $celdas=explode('-.-.',$filas[$i]);
                                $modelDSI->id_comanda = $datos->id_comanda;
                                $modelDSI->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                                $modelDSI->id_producto = $celdas[0];
                                $modelDSI->comanda_detalle_precio = $celdas[2];
                                $modelDSI->comanda_detalle_cantidad = $celdas[3];
                                $modelDSI->comanda_detalle_despacho = $celdas[4];
                                $modelDSI->comanda_detalle_total = $celdas[6];
                                $modelDSI->comanda_detalle_observacion = $celdas[5];
                                $modelDSI->comanda_detalle_fecha_registro = $fecha;
                                $modelDSI->comanda_detalle_estado = 1;
                                $result = $this->pedido->guardar_detalle_comanda($modelDSI);
                            }
                        }
                    }
                }
                if($result == 1){
                    $result = $this->pedido->cambiar_estado_mesa($id_mesa);
                    //INICIO - IMPRESION DE TICKET DE COMANDA
                    $id_comanda = $datos->id_comanda;
                    $comanda = $this->pedido->listar_comanda_x_id($id_comanda);
                    $detalle_comanda =$this->pedido->listar_detalle_x_comanda($id_comanda);
                    foreach ($detalle_comanda as $de){
                        $detalle = $this->pedido->listar_detalle_x_comanda_detalle($de->id_comanda_detalle);
                        if($detalle->id_grupo != 3){
                            $nombre_ticket = "$detalle->grupo_ticketera";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
                        }else{
                            $nombre_ticket = "CALIENTE_2";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
                            $nombre_ticket = "FRIOS_2";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
                        }

                    }
//                    require _VIEW_PATH_ . 'pedido/ticket_comanda.php';
                    //FIN - IPRESION DE TICKET DE COMANDA
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function guardar_delivery_venta(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validacion de datos
            if($ok_data) {
                $model = new Pedido();
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $fecha = date('Y-m-d H:i:s');
                $microtime = microtime(true);
                $id_mesa = $_POST['id_mesa'];
                $model->id_mesa = 0;
                $model->id_usuario = $id_usuario;
                $model->id_cliente = $_POST['id_cliente'];
                $model->cliente_nombre_d = $_POST['cliente_nombre_d'];
                $model->comanda_direccion_delivery = $_POST['comanda_direccion_delivery'];
                $model->comanda_telefono_delivery = $_POST['comanda_telefono_delivery'];
                $model->comanda_total = $_POST['comanda_total'];
                $model->comanda_fecha_registro = $fecha;
                $model->comanda_cantidad_personas = 1;
                $model->comanda_estado = 1;
                $model->comanda_codigo = $microtime;

                $fecha_buscar = date('Y-m-d');
                $ultima_comanda = $this->pedido->listar_ultima_comanda($fecha_buscar);
                if(isset($ultima_comanda->id_comanda)){
                    $fila = explode('-',$ultima_comanda->comanda_correlativo);
                    if(count($fila)>0){
                        for($i=0;$i<count($fila)-1;$i++){
                            $suma = $fila[1] + 1;
                            $model->comanda_correlativo = $fila[0].'-'.$suma;
                        }
                    }
                }else{
                    $model->comanda_correlativo = date('dmy').'-'. + 1;
                }
                $guardar_comanda = $this->pedido->guardar_comanda($model);
                if($guardar_comanda == 1){
                    $contenido = $_POST['contenido'];
                    if(count_chars($contenido)>0){
                        $filas=explode('/./.',$contenido);
                        $datos = $this->pedido->listar_comanda_por_mt($microtime);
                        if(count($filas)>0){
                            for ($i=0;$i<count($filas)-1;$i++){
                                $modelDSI=new Pedido();
                                $celdas=explode('-.-.',$filas[$i]);
                                $modelDSI->id_comanda = $datos->id_comanda;
                                $modelDSI->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                                $modelDSI->id_producto = $celdas[0];
                                $modelDSI->comanda_detalle_precio = $celdas[2];
                                $modelDSI->comanda_detalle_cantidad = $celdas[3];
                                $modelDSI->comanda_detalle_despacho = $celdas[4];
                                $modelDSI->comanda_detalle_total = $celdas[6];
                                $modelDSI->comanda_detalle_observacion = $celdas[5];
                                $modelDSI->comanda_detalle_fecha_registro = $fecha;
                                $modelDSI->comanda_detalle_estado = 1;
                                $result = $this->pedido->guardar_detalle_comanda($modelDSI);
                            }
                        }
                    }
                }
                if($result == 1){
                    //$result = $this->pedido->cambiar_estado_mesa($id_mesa);
                    //INICIO - IMPRESION DE TICKET DE COMANDA
                    $id_comanda = $datos->id_comanda;
                    $comanda = $this->pedido->listar_comanda_x_id($id_comanda);
                    $detalle_comanda =$this->pedido->listar_detalle_x_comanda($id_comanda);
                    foreach ($detalle_comanda as $de){
                        $detalle = $this->pedido->listar_detalle_x_comanda_detalle($de->id_comanda_detalle);
                        if($detalle->id_grupo != 3){
                            $nombre_ticket = "$detalle->grupo_ticketera";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
                        }else{
                            $nombre_ticket = "CALIENTE_2";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
                            $nombre_ticket = "FRIOS_2";
                            require _VIEW_PATH_ . 'pedido/ticket_comanda_detalle.php';
                        }

                    }
//                    require _VIEW_PATH_ . 'pedido/ticket_comanda.php';
                    //FIN - IPRESION DE TICKET DE COMANDA
                }
                if($_POST['gratis'] == 2) {
                    if ($result == 1) {
                        $modelv = new Pedido();
                        $fecha = date('Y-m-d H:i:s');
                        $tipo_pago = $_POST['id_tipo_pago'];
                        $fecha_hoy = date('Y-m-d');
                        $jalar_id_caja = $this->pedido->jalar_id_caja_aperturada($id_usuario);
                        if (empty($jalar_id_caja)) {
                            $caja_numero = 1;
                        } else {
                            $caja_numero = $jalar_id_caja->id_caja_numero;
                        }
                        $id_mesa = $_POST['id_mesa'];
                        $caja_dato = $this->pedido->jalar_datos_caja_numero($caja_numero);//datos de la caja para la captura del nombre de la impresora

                        $id_cliente = $_POST['id_cliente'];
                        $modelv->id_caja_numero = $caja_numero;
                        $modelv->id_cliente = $id_cliente;
                        $modelv->id_mesa = 0;
                        $modelv->id_usuario = $id_usuario;
                        $modelv->id_moneda = 1;
                        $modelv->venta_tipo = $_POST['tipo_venta'];
                        //obtener serie con el id
                        $serie_ = $this->pedido->listar_correlativos_x_serie($_POST['serie']);
                        $modelv->venta_serie = $serie_->serie;
                        if (isset($_POST['id_venta'])) {
                            if ($_POST['tipo_venta'] == "20") {
                                $correlativo = $_POST['correlativo'];
                            } else {
                                $correlativo = $serie_->correlativo + 1;
                            }
                        } else {
                            $correlativo = $serie_->correlativo + 1;
                        }
                        $modelv->venta_correlativo = $correlativo;
                        $modelv->venta_tipo_moneda = $_POST['tipo_moneda'];
                        $modelv->id_tipo_pago = $tipo_pago;
                        //AL HACER LA VENTA AUTOMATICAMENTE SE LLENA EL CORRELATIVO DE LA VENTA
                        $modelv->venta_totalgratuita = $_POST['op_gratuitas_'];
                        $modelv->venta_totalexonerada = $_POST['op_exoneradas_'];
                        $modelv->venta_totalinafecta = $_POST['op_inafectas_'];
                        $modelv->venta_totalgravada = $_POST['op_gravadas_'];
                        $modelv->venta_totaligv = $_POST['igv_'];
                        $modelv->venta_icbper = $_POST['icbper_'];
                        $modelv->venta_fecha = $fecha;
                        $modelv->tipo_documento_modificar = "";
                        $modelv->tipo_nota_id = 0;
                        $modelv->correlativo_modificar = "";
                        $modelv->venta_total = $_POST['venta_total'];
                        $modelv->pago_cliente = $_POST['pago_cliente'];
                        $modelv->vuelto_ = $_POST['vuelto_'];
                        $guardar_venta = 2;
                        if ($_POST['tipo_venta'] == '20') {
                            //$guardar_venta = $this->pedido->guardar_nota_venta($model);
                            $guardar_venta = $this->pedido->guardar_venta($modelv);

                        } else {
                            $guardar_venta = $this->pedido->guardar_venta($modelv);
                        }

                        if ($guardar_venta == 1) {
                            $model = new Pedido();
                            if ($_POST['tipo_venta'] == '20') {
                                /*$jalar_id_venta = $this->pedido->jalar_id_nota_venta($fecha,$id_cliente);
                                $id_venta = $jalar_id_venta->id_nota_venta;*/
                                $jalar_id_venta = $this->pedido->jalar_id_venta($fecha, $id_cliente);
                                $id_venta = $jalar_id_venta->id_venta;
                            } else {
                                $jalar_id_venta = $this->pedido->jalar_id_venta($fecha, $id_cliente);
                                $id_venta = $jalar_id_venta->id_venta;
                            }
                            $comanda_detalle = $this->pedido->listar_detalle_x_comanda($datos->id_comanda);
                            $ids_detalles_comanda = "";
                            foreach ($comanda_detalle as $cd) {
                                $ids_detalles_comanda .= $cd->id_comanda_detalle . "-.-.";
                            }
                            $datos_detalle_pedido = $ids_detalles_comanda;
                            if (count_chars($datos_detalle_pedido) > 0) {
                                $celdas = explode('-.-.', $datos_detalle_pedido);
                                if (count($celdas) > 0) {
                                    $igv_porcentaje = 0.18;
                                    for ($i = 0; $i < count($celdas) - 1; $i++) {
                                        $model->id_venta = $id_venta;
                                        $id_comanda_detalle = $celdas[$i];


                                        $jalar_datos = $this->pedido->jalar_datos($id_comanda_detalle);
                                        $cantidad = $jalar_datos->comanda_detalle_cantidad;
                                        $precio_unitario = $jalar_datos->comanda_detalle_precio;
                                        $codigo_afectacion = $jalar_datos->producto_precio_codigoafectacion;
                                        $igv_detalle = 0;
                                        $factor_porcentaje = 1;
                                        if ($codigo_afectacion == 10) {
                                            $igv_detalle = $precio_unitario * $cantidad * $igv_porcentaje;
                                            $factor_porcentaje = 1 + $igv_porcentaje;
                                        }

                                        $model->id_comanda_detalle = $id_comanda_detalle;
                                        $model->venta_detalle_valor_unitario = $precio_unitario;
                                        $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                                        $model->venta_detalle_nombre_producto = $jalar_datos->producto_nombre;
                                        $model->venta_detalle_cantidad = $cantidad;
                                        //$model->venta_total_selled = 1;
                                        $model->venta_detalle_total_igv = $igv_detalle;
                                        $model->venta_detalle_porcentaje_igv = $igv_porcentaje * 100;
                                        //$model->id_igv = 1;
                                        $model->venta_detalle_total_icbper = 0.00;
                                        $model->venta_detalle_valor_total = $precio_unitario * $cantidad;
                                        $model->venta_detalle_total_price = $precio_unitario * $cantidad * $factor_porcentaje;

                                        if ($_POST['tipo_venta'] == "20") {
                                            //$result = $this->pedido->guardar_nota_venta_detalle($model);
                                            $result = $this->pedido->guardar_venta_detalle($model);
                                        } else {
                                            $result = $this->pedido->guardar_venta_detalle($model);
                                        }
                                        if ($result == 1) {
                                            $this->pedido->cambiar_estado_comanda($id_comanda_detalle);
                                            $pago = 0;
                                            $id_comanda = $jalar_datos->id_comanda;
                                            $verificar = $this->pedido->verificar_pago($id_comanda);

                                            //VALIDAR PARA QUE NO DISMINUYA EL STOCK DE NUEVO
                                            /*$existe_detalle_comanda = $this->pedido->verificar_existencia_detalle_comanda($id_comanda_detalle);
                                            $existe = false;
                                            if(empty($existe_detalle_comanda)){
                                                $existe = true;
                                            }*/
                                            if (isset($_POST['id_venta'])) {
                                                $entr = false;
                                            } else {
                                                $entr = true; //cambiar a true cuando se deje de emitir las facturas antiguas para no restar stock
                                            }
                                            if ($entr) {
                                                //Aqui ira la disminucion de stock aunque no se como pocta lo hare
                                                $valor_insumos = $this->pedido->valor_insumos($id_comanda_detalle);
                                                foreach ($valor_insumos as $v) {
                                                    $capturar = $v->id_recurso_sede;
                                                    $unidad_medida = $v->id_medida;
                                                    $id_detalle_receta = $v->id_detalle_receta;
                                                    $monto_usado = $v->detalle_receta_cantidad;
                                                    $cantidad = $this->pedido->capturar_cantidad($capturar);
                                                    $valor_cantidad = $cantidad->recurso_sede_stock;
                                                    if ($v->detalle_receta_unidad_medida != 0) {
                                                        $detalle_conversion = $this->pedido->conversion_por_id($v->detalle_receta_unidad_medida);
                                                        $nuevo_monto = ($monto_usado / $detalle_conversion->conversion_cantidad) * (-1);
                                                        $actualizar_stock = $this->pedido->actualizar_stock($nuevo_monto, $capturar);
                                                    } else {
                                                        $montito = $monto_usado * (-1);
                                                        $actualizar_stock = $this->pedido->actualizar_stock($montito, $capturar);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $contenido_tipopago = $_POST['contenido_tipopago'];
                                if (count_chars($contenido_tipopago) > 0) {
                                    $filas = explode('/-/-', $contenido_tipopago);
                                    if (count($filas) > 0) {
                                        for ($i = 0; $i < count($filas) - 1; $i++) {
                                            $modelDSI = new Pedido();
                                            $celdas = explode('-.-.', $filas[$i]);
                                            $modelDSI->id_venta = $id_venta;
                                            $modelDSI->id_tipo_pago = $celdas[0];
                                            $modelDSI->venta_detalle_pago_monto = $celdas[1];
                                            $modelDSI->venta_detalle_pago_estado = 1;
                                            $result__ = $this->pedido->guardar_detalle_pago($modelDSI);

                                        }
                                    }
                                }
                                if ($result == 1) {
                                    $result = $this->pedido->actualizarCorrelativo_x_id_Serie($_POST['serie'], $correlativo);
                                    if (!isset($_POST['id_venta'])) {
                                        if (empty($verificar)) {
                                            $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                                            $this->pedido->ocultar_reserva($id_mesa);
                                            $pago = 1;
                                        }
                                    }
                                    if ($result == 1) {
                                        $result__ = $this->pedido->ocultar_reserva($id_mesa);
                                    }

                                    if ($_POST['tipo_venta'] == "20") {
                                        /*$venta = $this->venta->listar_nota_venta_x_id($id_venta);
                                        $detalle_venta =$this->venta->listar_nota_venta_detalle_x_id_venta($id_venta);
                                        $empresa = $this->venta->listar_empresa_x_id_empresa($venta->id_empresa);
                                        $cliente = $this->venta->listar_cliente_notaventa_x_id($venta->id_cliente);*/
                                        $venta = $this->venta->listar_venta_x_id($id_venta);
                                        $detalle_venta = $this->venta->listar_venta_detalle_x_id_venta($id_venta);
                                        $empresa = $this->venta->listar_empresa_x_id_empresa($venta->id_empresa);
                                        $cliente = $this->venta->listar_clienteventa_x_id($venta->id_cliente);
                                        $venta_tipo = "NOTA DE VENTA";
                                        if ($cliente->id_tipodocumento == "4") {
                                            $cliente_nombre = $cliente->cliente_razonsocial;
                                        } else {
                                            $cliente_nombre = $_POST['cliente_nombre'];
                                        }
                                        if ($_POST['imprimir'] == "1") {
                                            require _VIEW_PATH_ . 'pedido/ticket_nota_venta.php';
                                        }

                                    } else {
                                        //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                                        include('libs/ApiFacturacion/phpqrcode/qrlib.php');

                                        $venta = $this->venta->listar_venta_x_id($id_venta);
                                        $detalle_venta = $this->venta->listar_venta_detalle_x_id_venta($id_venta);
                                        $empresa = $this->venta->listar_empresa_x_id_empresa($venta->id_empresa);
                                        $cliente = $this->venta->listar_clienteventa_x_id($venta->id_cliente);
                                        //INICIO - CREACION QR
                                        $nombre_qr = $empresa->empresa_ruc . '-' . $venta->venta_tipo . '-' . $venta->venta_serie . '-' . $venta->venta_correlativo;
                                        $contenido_qr = $empresa->empresa_ruc . '|' . $venta->venta_tipo . '|' . $venta->venta_serie . '|' . $venta->venta_correlativo . '|' .
                                            $venta->venta_totaligv . '|' . $venta->venta_total . '|' . date('Y-m-d', strtotime($venta->venta_fecha)) . '|' .
                                            $cliente->tipodocumento_codigo . '|' . $cliente->cliente_numero;
                                        $ruta = 'libs/ApiFacturacion/imagenqr/';
                                        $ruta_qr = $ruta . $nombre_qr . '.png';
                                        QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                                        //FIN - CREACION QR
                                        if ($venta->venta_tipo == "03") {
                                            $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
                                        } elseif ($venta->venta_tipo == "01") {
                                            $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
                                        }
                                        if ($cliente->id_tipodocumento == "4") {
                                            $cliente_nombre = $cliente->cliente_razonsocial;
                                        } else {
                                            $cliente_nombre = $_POST['cliente_nombre'];
                                        }
                                        /*if($_POST['imprimir'] == "1"){
                                            require _VIEW_PATH_ . 'pedido/ticket_venta.php';
                                        }*/
                                    }


                                    //INICIO - TICKET DELIVERY
                                    //$venta = $this->venta->listar_venta_x_id($id);
                                    if ($id_mesa == 0) {
                                        $cliente = $this->pedido->listar_clienteventa_x_id_delivery($id_cliente);
                                        $id_comanda = $cliente->id_comanda;
                                        $datos_ticket = $this->pedido->jalar_datos_comanda($id_comanda);

                                        if ($cliente->id_tipodocumento == "4") {
                                            $cliente_nombre = $cliente->cliente_razonsocial;
                                        } else {
                                            $cliente_nombre = $_POST['cliente_nombre'];
                                        }

                                        /*require _VIEW_PATH_ . 'pedido/ticket_venta_delivery.php';*/
                                        //echo "<script>window.open("._SERVER_."'Pedido/ticket_venta/".$id_venta."','_blank')</script>";
                                    }

                                }

                            }

                            //$this->pedido->cambiar_estado_comanda_principal($id_comanda);
                        } else {
                            $result = 2;
                        }

                    }
                }else{
                    if($result == 1){
                        $id_mesa = $_POST['id_mesa'];
                        $fecha = date('Y-m-d H:i:s');
                        $model->id_mesa = $id_mesa;
                        $model->id_usuario = $id_usuario;
                        $model->cliente_numero = $_POST['cliente_numero'];
                        $model->cliente_nombre = $_POST['cliente_nombre'];
                        $model->cliente_direccion = $_POST['cliente_direccion'];
                        $model->venta_total = $_POST['venta_total'];
                        $model->observacion_cortesia = $_POST['observacion_cortesia'];
                        $model->fecha = $fecha;
                        $microtime = microtime(true);
                        $model->codigo = $microtime;
                        $result = $this->pedido->guardar_pedido_gratis($model);
                        if($result == 1){
                            $pedido_gratis = $this->pedido->listar_pedido_gratis_x_mt($microtime);
                            $datos_detalle_pedido = $_POST['datos_detalle_pedido'];
                            $id_pedido_gratis = $pedido_gratis->id_pedido_gratis;
                            if(count_chars($datos_detalle_pedido)>0){
                                $celdas=explode('-.-.',$datos_detalle_pedido);
                                if(count($celdas)>0){

                                    for ($i=0;$i<count($celdas)-1;$i++){

                                        $id_comanda_detalle = $celdas[$i];
                                        $jalar_datos = $this->pedido->jalar_datos($id_comanda_detalle);
                                        $model->id_pedido_gratis = $id_pedido_gratis;
                                        $model->id_comanda_detalle = $id_comanda_detalle;

                                        $result = $this->pedido->guardar_pedido_gratis_detalle($model);
                                        if($result == 1){
                                            $this->pedido->cambiar_estado_comanda($id_comanda_detalle);
                                            $pago = 0;
                                            $id_comanda = $jalar_datos->id_comanda;
                                            $verificar = $this->pedido->verificar_pago($id_comanda);

                                            //VALIDAR PARA QUE NO DISMINUYA EL STOCK DE NUEVO
                                            /*$existe_detalle_comanda = $this->pedido->verificar_existencia_detalle_comanda($id_comanda_detalle);
                                            $existe = false;
                                            if(empty($existe_detalle_comanda)){
                                                $existe = true;
                                            }*/
                                            if(isset($_POST['id_venta'])){
                                                $entr = false;
                                            }else{
                                                $entr = true; //cambiar a true cuando se deje de emitir las facturas antiguas para no restar stock
                                            }
                                            if ($entr){
                                                //Aqui ira la disminucion de stock aunque no se como pocta lo hare
                                                $valor_insumos = $this->pedido->valor_insumos($id_comanda_detalle);
                                                foreach ($valor_insumos as $v){
                                                    $capturar = $v->id_recurso_sede;
                                                    $unidad_medida = $v->id_medida;
                                                    $id_detalle_receta = $v->id_detalle_receta;
                                                    $monto_usado = $v->detalle_receta_cantidad;
                                                    $cantidad = $this->pedido->capturar_cantidad($capturar);
                                                    $valor_cantidad = $cantidad->recurso_sede_stock;
                                                    if($v->detalle_receta_unidad_medida != 0){
                                                        $detalle_conversion = $this->pedido->conversion_por_id($v->detalle_receta_unidad_medida);
                                                        $nuevo_monto = ($monto_usado / $detalle_conversion->conversion_cantidad) * (-1);
                                                        $actualizar_stock = $this->pedido->actualizar_stock($nuevo_monto,$capturar);
                                                    }else{
                                                        $montito = $monto_usado * (-1);
                                                        $actualizar_stock = $this->pedido->actualizar_stock($montito,$capturar);
                                                    }
                                                }
                                            }
                                        }


                                    }
                                }
                                if($result==1){
                                    if(empty($verificar)){
                                        $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                                        $pago = 1;
                                    }
                                }
                            }
                        }
                        if($result==1){
                            $result__ = $this->pedido->ocultar_reserva($id_mesa);
                        }
                    }
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA CAMBIAR LOS ESTADOS DEL PEDIDO
    public function cambiar_estado_pedido(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);
            $ok_data = $this->validar->validar_parametro('comanda_detalle_estado', 'POST',true,$ok_data,11,'numero',0);

            //Validacion de datos
            if($ok_data) {

                $id_comanda_detalle = $_POST['id_comanda_detalle'];
                $comanda_detalle_estado = $_POST['comanda_detalle_estado'];

                $result = $this->pedido->cambiar_estado_pedido($id_comanda_detalle, $comanda_detalle_estado);

                if($result == 1){
                    if ($comanda_detalle_estado == 2){
                        //INICIO - IMPRIMIR TICKET DE ATENCION
                        $detalle_comanda = $this->pedido->listar_detallecomanda_x_id_comanda_detalle($id_comanda_detalle);

                        require _VIEW_PATH_ . 'pedido/ticket_atencion.php';
                        require _VIEW_PATH_ . 'pedido/ticket_atencion.php';
                    }

                }


            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION NUEVITA
    public function actualizar_venta_coman(){
        $result = 2;
        $message = 'OK';
        try {
            $pago=0;
            $id_comanda = $_POST['id_comanda'];
            $id_boleta_descuento = $_POST['id_boleta_pis'];
            $id_mesa = $_POST['id_mesa'];
            $datos_detalle_pedido = $_POST['datos_detalle_pedido'];
            if(count_chars($datos_detalle_pedido)>0) {
                $celdas = explode('-.-.', $datos_detalle_pedido);
                if (count($celdas) > 0) {
                    for ($i = 0; $i < count($celdas); $i++) {
                        $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                        $id_comanda_detalle = $celdas[$i];
                        $result=$this->pedido->cambiar_detalle_comanda_estados($id_usuario,$id_comanda_detalle);

                        //Aqui ira la disminucion de stock de las recetas aunque no se como pocta lo hare
                        $valor_insumos = $this->pedido->valor_insumos($id_comanda_detalle);
                        foreach ($valor_insumos as $v){
                            $capturar = $v->id_recurso_sede;
                            $unidad_medida = $v->id_medida;
                            $id_detalle_receta = $v->id_detalle_receta;
                            $monto_usado = $v->detalle_receta_cantidad * $v->comanda_detalle_cantidad;
                            $cantidad = $this->pedido->capturar_cantidad($capturar);
                            $valor_cantidad = $cantidad->recurso_sede_stock;
                            $montito = $monto_usado * (-1);
                            $stock_actual=$this->pedido->stock_actual_comparacion($capturar);
                            //AQUI HAREMOS LA OPERACION DEL STOCK
                            if($stock_actual->cantidad_nueva_distribuida <= $monto_usado){
                                $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($capturar);
                            }else{
                                $restar_nuevo_stock_tabla = $this->pedido->restar_nueva_tabla_stock($monto_usado,$capturar);
                            }
                        }
                        //AQUI SE REALIZARA LA RESTA DEL STOCK DE LOS INSUMOS DE LAS PREFERENCIAS
                        $search_new_table = $this->pedido->search_new_table($id_comanda_detalle);
                        foreach ($search_new_table as $sn){
                            $cantidad_detalle = $this->pedido->cantida_detalle($id_comanda_detalle);
                            $id_del_detalle = $this->pedido->id_del_detalle($sn->id_acompanamiento_detalle);
                            //ULTIMO ID JALADO
                            $valor_final =$this->pedido->valor_final($id_del_detalle->id_receta_preferencia);
                            $cantidad_cd = $cantidad_detalle->comanda_detalle_cantidad;
                            $cantidad_a_usar = $valor_final->cantidad * $cantidad_cd;
                            $recurso = $valor_final->id_recurso;
                            $id_recurso_sede =  $this->pedido->sacar_id_recurso_sede($recurso);
                            $stock_actual=$this->pedido->stock_actual_comparacion($id_recurso_sede->id_recurso_sede);
                            if($stock_actual->cantidad_nueva_distribuida <= $cantidad_a_usar){
                                $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($id_recurso_sede->id_recurso_sede);
                            }else{
                                $restar_stock_again = $this->pedido->restar_nueva_tabla_stock($cantidad_a_usar,$id_recurso_sede->id_recurso_sede);
                            }
                            //$restar_stock_again = $this->pedido->restar_stock_again($cantidad_a_usar,$recurso);
                        }

                    }
                }
            }
            if($result==1){
                $actualizar_estado_boleta = $this->pedido->actualizar_estado_boleta($id_boleta_descuento);
                if($actualizar_estado_boleta==1){
                    $verificar = $this->pedido->verificar_pago($id_comanda);
                    if(empty($verificar)){
                        $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                        $pago = 1;
                    }
                }

            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "pago"=>$pago)));
    }
    //FIN DE LA FUNCION
    //FUNCION PARA ELIMINAR LOS DETALLES DE LA COMANDA
    public function eliminar_comanda_detalle(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validacion de datos
            if($ok_data) {
                //$id_rol = 7;
                $id_rol = 5;
                if($this->pedido->verificar_password($id_rol, $_POST['password'])){
                    $id_comanda_detalle = $_POST['id_comanda_detalle'];
                    $comanda_detalle_eliminacion = $_POST['comanda_detalle_eliminacion'];
                    $fecha_eliminacion = date('Y-m-d H:i:s');
                    $id_comanda = $_POST['id_comanda'];
                    $id_mesa = $_POST['id_mesa'];
                    $result = $this->pedido->eliminar_comanda_detalle($comanda_detalle_eliminacion,$fecha_eliminacion,$id_comanda_detalle);
                    if($result == 1){
                        $jalar_valor = $this->pedido->jalar_valor($id_comanda);
                        $nuevo_valor = $jalar_valor->total;
                        $actualizar_precio = $this->pedido->actualizar_nuevo_valor($id_comanda, $nuevo_valor);
                        $mesa = 0;
                        //HACER UN CONTEO
                        $resultado = $this->pedido->listar_detalles_x_pedido($id_comanda);
                        $resultado_ventas = $this->pedido->listar_detalles_x_pedido_pagados($id_comanda);
                        if(empty($resultado)){
                            $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                            $mesa = 1;

                        }
                        if(empty($resultado_ventas)){
                            $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                            $mesa = 1;
                        }

                    }
                }else{
                    $result = 5;
                    $message = 'Contraseña Incorrecta';
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "mesa" => $mesa)));
    }

    public function cambiar_comanda_detalle_cantid(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validacion de datos
            if ($ok_data) {
                $id_comanda = $_POST['id_comanda'];
                $id_comanda_detalle = $_POST['id_comanda_detalle'];
                $cantidad_detalle_nuevo = $_POST['cantidad_detalle_nuevo'];
                $total = $_POST['total'];
                $result = $this->pedido->actualizar_comanda_detalle_cantidad($id_comanda_detalle, $cantidad_detalle_nuevo, $total);
                if ($result == 1) {
                    $jalar_valor = $this->pedido->jalar_valor($id_comanda);
                    $nuevo_valor = $jalar_valor->total;
                    $result = $this->pedido->actualizar_nuevo_valor($id_comanda, $nuevo_valor);
                }
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }

    //FUNCION PARA ELIMINAR COMANDA DEL DELIVERY
    public function eliminar_comanda_delivery(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validacion de datos
            if ($ok_data) {
                if($this->pedido->verificar_password($this->encriptar->desencriptar($_SESSION['_n'],_FULL_KEY_), $_POST['password'])) {
                    $id_comanda_detalle = $_POST['id_comanda_detalle'];
                    $id_comanda = $_POST['id_comanda'];
                    $result = $this->pedido->eliminar_comanda_detalle_delivery($id_comanda);
                    if($result == 1){
                        $result = $this->pedido->eliminar_comanda($id_comanda);
                    }
                }
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA CAMBIAR MESA
    public function cambiar_mesa(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_mesa_nuevo', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_mesa', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_comanda', 'POST',true,$ok_data,11,'numero',0);
            //Validacion de datos
            if ($ok_data) {
                $id_mesa_nuevo = $_POST['id_mesa_nuevo'];
                $id_comanda = $_POST['id_comanda'];
                $result = $this->pedido->cambiar_mesa($id_mesa_nuevo, $id_comanda);
                $this->pedido->cambiar_estado_mesa($id_mesa_nuevo);
                $id_mesa = $_POST['id_mesa'];
                $this->pedido->actualizar_estado_mesa($id_mesa);
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function transferir_pedido_(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_mesa_pn', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_mesa', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_comanda', 'POST',true,$ok_data,11,'numero',0);
            //Validacion de datos
            if($ok_data) {
                $id_mesa_pn = $_POST['id_mesa_pn'];
                $id_comanda_ = $_POST['id_comanda'];
                $id_mesa = $_POST['id_mesa'];

                $jalar_pedidos_mesa_actual = $this->pedido->jalar_pedidos_mesa_actual($id_comanda_);
                $extraer_id_comanda = $this->pedido->extraer_id_comanda_destino($id_mesa_pn);
                $jalar_pedidos_mesa_destino = $this->pedido->jalar_pedidos_mesa_destino($extraer_id_comanda->id_comanda);
                foreach ($jalar_pedidos_mesa_actual as $jp) {
                    $model = new Pedido();
                    $model->id_comanda = $extraer_id_comanda->id_comanda;
                    /*foreach ($jalar_pedidos_mesa_destino as $md){
                        if($jp->id_producto == $md->id_producto){
                            if($jp->comanda_detalle_despacho == $md->comanda_detalle_despacho){
                                $cantidad_x_producto = $jp->comanda_detalle_cantidad + $md->comanda_detalle_cantidad;
                            }

                        }
                    }*/

                    $model->id_producto = $jp->id_producto;
                    $model->cantidad_x_producto = $jp->comanda_detalle_cantidad;
                    $model->comanda_detalle_precio = $jp->comanda_detalle_precio;
                    $model->comanda_detalle_cantidad = $jp->comanda_detalle_cantidad;
                    $model->comanda_detalle_despacho = $jp->comanda_detalle_despacho;
                    $model->comanda_detalle_total = $jp->comanda_detalle_total;
                    $model->comanda_detalle_observacion = $jp->comanda_detalle_observacion;
                    $model->comanda_detalle_fecha_registro = $jp->comanda_detalle_fecha_registro;
                    $model->comanda_detalle_estado = $jp->comanda_detalle_estado;
                    $model->comanda_detalle_estado_venta = $jp->comanda_detalle_estado_venta;
                    $model->codigo = microtime();
                    $agregar_pedidos = $this->pedido->agregar_pedidos_mesa_destino($model);
                    $eliminar_pedidos = $this->pedido->eliminar_detalles_transferidos($jp->id_comanda_detalle);

                }
                $eliminar_comanda = $this->pedido->eliminar_comanda_transferida($id_comanda_);
                if($eliminar_comanda == 1){
                    $cambiar_estado_mesa = $this->pedido->actualizar_estado_mesa($id_mesa);
                }


            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function transferir_pedido(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_mesa_pn', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_mesa', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_comanda', 'POST',true,$ok_data,11,'numero',0);
            //Validacion de datos
            if($ok_data) {
                $id_mesa_pn = $_POST['id_mesa_pn'];
                $id_comanda_ = $_POST['id_comanda'];
                $id_mesa = $_POST['id_mesa'];

                $jalar_pedidos_mesa_actual = $this->pedido->jalar_pedidos_mesa_actual($id_comanda_);
                $extraer_id_comanda = $this->pedido->extraer_id_comanda_destino($id_mesa_pn);
                $jalar_pedidos_mesa_destino = $this->pedido->jalar_pedidos_mesa_destino($extraer_id_comanda->id_comanda);

                $total_comanda = 0;
                foreach ($jalar_pedidos_mesa_actual as $jp) {
                    $total_comanda = $total_comanda + $jp->comanda_detalle_total;
                }
                //$valor_comanda_recepcion = $extraer_id_comanda->comanda_total;
                $sumar_comandas = $this->pedido->sumar_valor_comandas($total_comanda,$extraer_id_comanda->id_comanda);
                $actualizar_id_comanda = $this->pedido->actializar_id_comanda($extraer_id_comanda->id_comanda,$id_comanda_);
                $eliminar_comanda = $this->pedido->eliminar_comanda_transferida($id_comanda_);
                if($eliminar_comanda == 1){
                    $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                }

            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA CAMBIAR CANTIDAD DE PERSONAS EN UN MESA
    public function cambiar_cantidad_personas(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validacion de datos
            if ($ok_data) {
                $id_comanda = $_POST['id_comanda'];
                $comanda_cantidad_personas = $_POST['comanda_cantidad_personas'];

                $result = $this->pedido->cambiar_cantidad_personas($id_comanda,$comanda_cantidad_personas);

            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA CONSULTAR SERIE
    public function consultar_serie(){
        try{
            $concepto = $_POST['concepto'];
            $series = "";
            $correlativo = "";
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);

            $fecha_hoy = date('Y-m-d');
            $jalar_id_caja = $this->pedido->jalar_id_caja_aperturada($id_usuario);
            $id_caja = $jalar_id_caja->id_caja_numero;
            if($concepto == "LISTAR_SERIE"){
                if(isset($_POST['concepto'])){
                    $series = $this->pedido->listarSerie($_POST['tipo_venta']);
                }else{
                    $series = $this->pedido->listarSerie_caja($_POST['tipo_venta'], $id_caja);
                }
            }else{
                $correlativo_ = $this->pedido->listar_correlativos_x_serie($_POST['id_serie']);
                $correlativo = $correlativo_->correlativo + 1;
            }
            //$series = $this->pedido->listarSerie($_POST['tipo_venta']);
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        $respuesta = array("serie" => $series, "correlativo" =>$correlativo);
        echo json_encode($respuesta);
    }

    //FUNCION PARA AGREGAR GRUPOS NUEVOS
    public function agregar_grupo(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;

            if ($ok_data) {
                $model = new Pedido();
                $fecha = date('Y-m-d H:i:s');
                $model->grupo_nombre = $_POST['grupo_nombre'];
                $model->grupo_ticketera = $_POST['grupo_ticketera'];
                $model->grupo_fecha_registro = $fecha;
                $model->grupo_estado = 1;

                $result = $this->pedido->guardar_grupos($model);

            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function habilitar_mesa(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $id_mesa = $_POST['id_mesa'];
                $mesa_estado_atencion = $_POST['estado'];

                $result = $this->pedido->habilitar_mesa($mesa_estado_atencion,$id_mesa);
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function guardar_reserva(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $model = new Pedido();
                $model->id_reserva = $_POST['id_reserva'];
                $fecha = date('Y-m-d H:i:s');
                $id_mesa = $_POST['id_mesa'];
                $model->id_mesa = $id_mesa;
                $model->reserva_nombre = $_POST['reserva_nombre'];
                $model->reserva_cantidad = $_POST['reserva_cantidad'];
                $model->reserva_fecha = $_POST['reserva_fecha'];
                $model->reserva_hora = $_POST['reserva_hora'];
                $model->reserva_contacto = $_POST['reserva_contacto'];
                $model->reserva_fecha_registro = $fecha;
                $model->reserva_estado = 1;

                $result = $this->pedido->guardar_reserva($model);

                if($result == 1){
                    $fecha_comparar = date('Y-m-d');
                    $listar_reservas = $this->pedido->listar_ultima_reserva();
                    $fecha_reserva = $listar_reservas->reserva_fecha;
                    if($fecha_reserva == $fecha_comparar){
                        $result = $this->pedido->cambiar_estado_mesa_reserva($id_mesa);
                    }

                }

            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function habilitar_reserva(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $id_mesa = $_POST['id_mesa'];
                $mesa_estado_atencion = $_POST['estado'];
                $result = $this->pedido->habilitar_mesa($mesa_estado_atencion,$id_mesa);
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function eliminar_reserva(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $id_reserva = $_POST['id_reserva'];
                $id_mesa = $_POST['id_mesa'];

                $result = $this->pedido->eliminar_reserva($id_reserva);
                if($result == 1){
                    $result = $this->pedido->mesa_estado_limpio($id_mesa);
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function mesa_visible(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $id_mesa = $_POST['id_mesa'];

                $result = $this->pedido->mostrar_mesita($id_mesa);
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function cambiar_estado_comanda_deliver(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $id_comanda = $_POST['id'];
                $result = $this->pedido->cambiar_estado_comanda_delivery($id_comanda);

            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function guardar_cambio(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $id_mesa = $_POST['id_mesa_oc'];
                $estado = $_POST['estado'];

                $result = $this->pedido->guardar_cambio($estado,$id_mesa);
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA BEBELEX
    public function listar_familias_productos(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $familia = array();
            $listar_familias = $this->pedido->listar_familias();
            foreach ($listar_familias as $lf){
                $productos_familia = $this->pedido->listar_productos_x_familia($lf->id_producto_familia);
                $lf->familias=$productos_familia;

                $productos = array();
                foreach ($productos_familia as $pf){
                    $buscar_acompas = $this->pedido->obtener_acompas($pf->id_producto);
                    $pf->acompanamientos=$buscar_acompas;
                    $detalle_acom = array();
                    foreach ($buscar_acompas as $bb){
                        $buscar_detalle = $this->pedido->obtener_detalles($bb->id_acompanamiento);
                        $pf->acompanamientos_detalle=$buscar_detalle;
                        $detalle_acom[] = array(
                            "id_acompanhamiento" => $bb->id_acompanamiento,
                            "acompanamiento_texto" => $bb->acompanamiento_texto,
                            "acompanamiento_estado" => $bb->acompanamiento_estado,
                            "buscar_detalle" => $buscar_detalle
                        );
                    }
                    $productos[] = array(
                        "id_producto" => $pf->id_producto,
                        "id_receta" => $pf->id_receta,
                        "id_producto_familia" => $pf->id_producto_familia,
                        "producto_nombre" => $pf->producto_nombre,
                        "producto_descripcion" => $pf->producto_descrpcion,
                        "producto_foto" => $pf->producto_foto,
                        "producto_estado" => $pf->producto_estado,
                        "producto_precio_venta" => $pf->producto_precio_venta,
                        "acompanhamientos" => $detalle_acom
                    );
                }
                $familia[] = array(
                    "id_producto_familia" => $lf->id_producto_familia,
                    "producto_familia_nombre" => $lf->producto_familia_nombre,
                    "producto_familia_estado" => $lf->producto_familia_estado,
                    "productos" => $productos
                );

            }
            if($listar_familias){
                $result = 1;
                $message = "Datos encontrados";
            }else{
                $result = 2;
                $message = "Vacio como tu corazón";
            }

        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "datos"=>$familia)));
    }

    //FUNCION NUEVA
    public function eliminar_todo_pedido(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validacion de datos
            if($ok_data) {
                if($this->pedido->verificar_password_cambiado($_POST['password_todo'])){
                    $fecha_eliminacion = date('Y-m-d H:i:s');
                    $id_comanda = $_POST['id_comanda_todo'];
                    $id_mesa = $_POST['id_mesa_todo'];
                    $comanda_detalle_eliminacion = $_POST['motivo_eliminar_todo'];
                    $result = $this->pedido->eliminar_comanda_detalle_todo($comanda_detalle_eliminacion,$fecha_eliminacion,$id_comanda);
                    if($result == 1){
                        //FUNCIONA PARA EL TICKET DE ELIMINACION DE TODA LA COMANDA
                        $comanda = $this->pedido->listar_comanda_x_id($id_comanda);
                        //$detalle_comanda =$this->pedido->listar_detalle_x_comanda_x_fecha($id_comanda, $fecha);
                        $grupos = $this->pedido->listar_grupos();
                        foreach ($grupos as $g){
                            $nombre_ticket = $g->grupo_ticketera;
                            $detalle_comanda = $this->pedido->listar_detalle_x_comanda_x_grupo_($id_comanda,$g->id_grupo);
                            if(!empty($detalle_comanda)){
                                require _VIEW_PATH_ . 'pedido/ticket_eliminar_todo_comanda.php';
                            }
                        }

                        //FIN DE LA FUNCION DEL TICKET DE ELIMINACION XD
                        $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                        $mesa = 1;
                    }
                }else{
                    $result = 5;
                    $message = 'Contraseña Incorrecta';
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "mesa" => $mesa)));
    }
    //FUNCION PARA DIVIDIR PEDIDO
    public function dividir_pedido(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $model = new Pedido();
                $id_comanda_detalle = $_POST['id'];
                //AQUI SE HARA TODA LA JUGADA DE LA DIVISION DE PEDIDO
                $obtener_detalle = $this->pedido->obtener_detalle_comanda($id_comanda_detalle);
                $model->id_comanda = $obtener_detalle->id_comanda;
                $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $model->id_producto = $obtener_detalle->id_producto;
                $model->comanda_detalle_precio = $obtener_detalle->comanda_detalle_precio;
                $model->comanda_detalle_cantidad = 1;
                $model->comanda_detalle_despacho = 'SALON';
                $model->comanda_detalle_total = $obtener_detalle->comanda_detalle_precio;
                $model->comanda_detalle_observacion = '-';
                $model->comanda_detalle_fecha_registro = date('Y-m-d H:i:s');
                $model->comanda_detalle_estado = 1;
                $microtime = microtime();
                $model->codigo = $microtime;
                $result = $this->pedido->guardar_detalle_comanda($model);
                if($result==1){
                    $cambiar_cantridad = $this->pedido->cambiar_cantidad_detalle_comanda($id_comanda_detalle);
                    if($cambiar_cantridad==1){
                        $monto = $obtener_detalle->comanda_detalle_total - $obtener_detalle->comanda_detalle_precio;
                        $mood_monto = $this->pedido->modificar_monto($monto,$id_comanda_detalle);
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
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCIONES DE PREFERENCIAS
    public function jalar_opciones_(){
        try{
            $id_producto = $_POST['id'];
            $obtener_acompas = $this->pedido->obtener_acompas($id_producto);

            $opciones_="<div class='row'>
                            ";
            $a =1;
            foreach ($obtener_acompas as $oa){
                $opciones_ .="<div class='col-lg-3'>
                                    <label>".$a.").- ".$oa->acompanamiento_texto."</label><br>";
                $obtener_detalles=$this->pedido->obtener_detalles($oa->id_acompanamiento);
                foreach ($obtener_detalles as $od){
                    $opciones_ .="<input class='sol_check' type='checkbox' id='".$od->id_acompa_detalle."' value='".$od->detalle_texto."'> <label class='form-check-label'>".$od->detalle_texto."</label><br>";
                }
                $opciones_ .= "</div>";
                $a++;
            }
            $opciones_ .= "</div>";

        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
        //Retornamos el json
        echo json_encode(array("opciones"=>$opciones,"opciones_"=>$opciones_));
    }

    //FUNCION PARA GUARDAR VENTA
    public function guardar_venta(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            //Validacion de datos
            if($ok_data) {
                $model = new Pedido();
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                //$id_cliente = $this->pedido->listar_cliente_x_numero($_POST['cliente_numero']);
                //INICIO - VERIFICAR SI EL CLIENTE EXISTE, SI NO EXISTE PASAR A REGISTRAR
                if($this->cliente->validar_dni($_POST['cliente_numero'])){
                    //Código 5: DNI duplicado
                    $cliente = $this->cliente->listar_cliente_x_numero($_POST['cliente_numero']);
                    $id_cliente = $cliente->id_cliente;
                    $result = 1;
                    $message = "Ya existe un cliente con este DNI registrado";
                }else{
                    $microtime = microtime(true);
                    $model->id_tipodocumento = $_POST['select_tipodocumento'];
                    $model->cliente_razonsocial = "";
                    $model->cliente_nombre = "";
                    if($_POST['select_tipodocumento'] == 4){
                        $model->cliente_razonsocial = $_POST['cliente_nombre'];
                    }else{
                        $model->cliente_nombre = $_POST['cliente_nombre'];
                    }
                    $model->cliente_numero = $_POST['cliente_numero'];
                    $model->cliente_correo = "";
                    $model->cliente_direccion = $_POST['cliente_direccion'];
                    $model->cliente_direccion_2 = "";
                    $model->cliente_telefono = "";
                    $model->cliente_codigo = $microtime;
                    $model->cliente_estado = 1;
                    $result = $this->cliente->guardar_cliente($model);
                    if($result == 1){
                        $cliente = $this->cliente->listar_cliente_x_numero($_POST['cliente_numero']);
                        $id_cliente = $cliente->id_cliente;
                    }else{
                        $result = 5;
                    }
                }

                if($_POST['gratis'] == 2){
                    if($result == 1){
                        $tipo_pago = $_POST['id_tipo_pago'];
                        $fecha_hoy = date('Y-m-d');
                        //$jalar_id_caja = $this->pedido->jalar_id_caja_aperturada_();
                        //$jalar_id_caja = $this->pedido->jalar_id_caja_aperturada($id_usuario);
                        $id_mesa = $_POST['id_mesa'];
                        //$caja_dato = $this->pedido->jalar_datos_caja_numero($jalar_id_caja->id_caja_numero);//datos de la caja para la captura del nombre de la impresora

                        //$caja_cierre = $this->pedido->jalar_id_caja_cierre(1,$id_usuario);
                        $fecha = date('Y-m-d H:i:s');
                        $model->id_caja_numero = 1;
                        $model->id_usuario = $id_usuario;
                        $model->id_mesa = $id_mesa;
                        $model->id_moneda = $_POST['tipo_moneda'];
                        $model->id_turno = 1;
                        $model->id_empresa = 1;
                        //$model->id_cliente = $_POST['id_cliente'];
                        $model->id_cliente = $id_cliente;
                        $model->venta_tipo = $_POST['tipo_venta'];
                        //obtener serie con el id
                        $serie_ = $this->pedido->listar_correlativos_x_serie($_POST['serie']);
                        $model->venta_serie = $serie_->serie;
                        if (isset($_POST['id_venta'])){
                            if($_POST['tipo_venta']== "20"){
                                $correlativo = $_POST['correlativo'];
                            }else{
                                $correlativo = $serie_->correlativo + 1;
                            }
                        }else{
                            $correlativo = $serie_->correlativo + 1;
                        }
                        $model->venta_correlativo = $correlativo;
                        $model->venta_tipo_moneda = $_POST['tipo_moneda'];
                        $model->id_tipo_pago = $tipo_pago;
                        //$correlativo = $this->pedido->listar_correlativos();
                        //AL HACER LA VENTA AUTOMATICAMENTE SE LLENA EL CORRELATIVO DE LA VENTA
                        $model->venta_totalgratuita = $_POST['op_gratuitas_'];
                        $model->venta_totalexonerada = $_POST['op_exoneradas_'];
                        $model->venta_totalinafecta = $_POST['op_inafectas_'];
                        $model->venta_totalgravada = $_POST['op_gravadas_'];
                        $model->venta_totaligv = $_POST['igv_'];
                        $model->venta_icbper = $_POST['icbper_'];
                        $model->venta_fecha = $fecha;
                        $model->tipo_documento_modificar = "";
                        $model->tipo_nota_id = 0;
                        $model->correlativo_modificar = "";
                        if(empty($_POST['des_global_'])){
                            $model->venta_totaldescuento = 0;
                        }else{
                            $model->venta_totaldescuento = $_POST['des_global_'];
                        }
                        $microtime = microtime();
                        $model->correlativo_modificar = "";
                        $model->venta_mt = $microtime;
                        $model->venta_total = $_POST['venta_total'];
                        if(empty($_POST['pago_cliente'])){
                            $model->pago_cliente = 0;
                        }else{
                            $model->pago_cliente = $_POST['pago_cliente'];
                        }
                        if(empty($_POST['vuelto_'])){
                            $model->vuelto_ = 0;
                        }else{
                            $model->vuelto_ = $_POST['vuelto_'];
                        }
                        //si la cajera escoge nota de venta se guardará en nota de venta - SINO SERÁ UUNA VENTA BOLETA O FACTURA
                        $guardar_venta = 2;
                        if(empty($_POST['correlativo_pis'])){
                            $model->venta_piscina_codigo=NULL;
                        }else{
                            $model->venta_piscina_codigo=$_POST['correlativo_pis'];
                        }
                        //VALORES NUEVOS
                        $model->cambiar_concepto = $_POST['cambiar_concepto'];
                        if($_POST['cambiar_concepto']==2){
                            $model->concepto_nuevo = $_POST['concepto_nuevo'];
                        }else{
                            $model->concepto_nuevo = "";
                        }
                        //FIN DE VALORES NUEVOS
						$model->codigo_venta = $_POST['codigo_venta'];
                        if($_POST['tipo_venta'] == '20'){
                            $guardar_venta = $this->pedido->guardar_venta($model);
                        }else{
                            $guardar_venta = $this->pedido->guardar_venta($model);
                        }

                        if($guardar_venta == 1){
                            $model = new Pedido();
                            if($_POST['tipo_venta'] == '20'){
                                /*$jalar_id_venta = $this->pedido->jalar_id_nota_venta($fecha,$id_cliente);
                                $id_venta = $jalar_id_venta->id_nota_venta;*/
                                $jalar_id_venta = $this->pedido->jalar_id_venta($fecha,$id_cliente);
                                $id_venta = $jalar_id_venta->id_venta;
                            }else{
                                $jalar_id_venta = $this->pedido->jalar_id_venta($fecha,$id_cliente);
                                $id_venta = $jalar_id_venta->id_venta;
                            }
                            //ZONA CABEZONA PARA CALCULAR EL DESCUENTO
                            if($_POST['descuentito']!=0){
                                $totally= $_POST['venta_total_antiguo'];
                                //PASO 1
                                $var_r = $_POST['descuentito'] * 100;
                                //PASO 2
                                $var_t = $var_r / $totally;
                                $redondeo = round($var_t,3);
                                $var_f = $redondeo / 100;
                            }
                            //FIN DEL CALCULO
                            $datos_detalle_pedido = $_POST['datos_detalle_pedido'];
                            if(count_chars($datos_detalle_pedido)>0){
                                $celdas=explode('-.-.',$datos_detalle_pedido);
                                if(count($celdas)>0){
                                    $igv_porcentaje = 0.18;
                                    //AQUI SE HARIA LA PARTE DE BUSCAR COMANDAS DE BARRA LIBRE FUERA DE HORA
                                    if($_POST['tipo_desct_v']==4){
                                        $dia_actual = strtolower(date("l"));
                                        $hora_actual = date("H:i");
                                        //$dias_permitidos = ["monday"];
                                        $dias_permitidos = ["thursday", "friday", "saturday"];
                                        $dias_permitidos_2 = ["saturday","sunday"];
                                        $hora_inicio = "19:00"; // Hora de inicio permitida
                                        $hora_fin = "23:30";   // Hora de fin permitida
                                        $hora_inicio_f = "12:30";
                                        $hora_fin_f = "17:00";
                                        for ($i=0;$i<count($celdas);$i++){
                                            $model->id_venta = $id_venta;
                                            $id_comanda_detalle = $celdas[$i];
                                            if($id_comanda_detalle != 0){
                                                $jalar_datos = $this->pedido->jalar_datos($id_comanda_detalle);
                                                $hora_pedido = date('H:i', strtotime($jalar_datos->comanda_detalle_fecha_registro));
                                                if (in_array($dia_actual, $dias_permitidos) && ($hora_pedido >= $hora_inicio && $hora_pedido <= $hora_fin)){

                                                }elseif(in_array($dia_actual, $dias_permitidos_2) && ($hora_pedido >= $hora_inicio_f && $hora_pedido <= $hora_fin_f)){

                                                }else{
                                                    $cantidad = $jalar_datos->comanda_detalle_cantidad;
                                                    $precio_unitario = $jalar_datos->comanda_detalle_precio;
                                                    $codigo_afectacion = $jalar_datos->producto_precio_codigoafectacion;
                                                    $igv_detalle = 0;
                                                    $factor_porcentaje = 1;
                                                    if($codigo_afectacion == 10){
                                                        $igv_detalle = $precio_unitario * $cantidad * $igv_porcentaje;
                                                        $factor_porcentaje = 1 + $igv_porcentaje;
                                                    }
                                                    //INICIO DEL BLOQUE DE DESCUENTO
                                                    if($_POST['descuentito']!=0){
                                                        $pre_item=$precio_unitario;
                                                        $cant_item=$cantidad;
                                                        //VALOR A DESCONTAR
                                                        $var_d = $pre_item * $var_f;
                                                        $valor_resta = $precio_unitario - round($var_d,4);
                                                        $precio_nuevo = $valor_resta;
                                                        $precio_x_cant = $valor_resta * $cant_item;
                                                    }else{
                                                        $precio_nuevo = $precio_unitario;
                                                        $precio_x_cant = $precio_unitario * $cantidad;
                                                    }
                                                    //FINAL DEL BLOQUE DE DESCUNTO
                                                    $model->id_comanda_detalle = $id_comanda_detalle;
                                                    $model->venta_detalle_valor_unitario = $precio_nuevo;
                                                    $model->venta_detalle_precio_unitario = $precio_nuevo  * $factor_porcentaje;
                                                    $model->venta_detalle_nombre_producto = $jalar_datos->producto_nombre;
                                                    $model->venta_detalle_cantidad = $cantidad;
                                                    //$model->venta_total_selled = 1;
                                                    $model->venta_detalle_total_igv = $igv_detalle;
                                                    $model->venta_detalle_porcentaje_igv = $igv_porcentaje * 100;
                                                    //$model->id_igv = 1;
                                                    $model->venta_detalle_total_icbper = 0.00;
                                                    $model->venta_detalle_valor_total = $precio_x_cant;
                                                    $model->venta_detalle_total_price = $precio_x_cant * $factor_porcentaje;
                                                    $model->id_producto_precio=$jalar_datos->id_producto;
                                                    //AQUI SERIA UNA FORMA DE QUE SE HAGA LA NUEVA BOLETA
                                                    if($_POST['tipo_venta']=="20"){
                                                        //$result = $this->pedido->guardar_nota_venta_detalle($model);
                                                        $result = $this->pedido->guardar_venta_detalle($model);
                                                    }else{
                                                        $result = $this->pedido->guardar_venta_detalle($model);
                                                    }
                                                    if($result == 1){
                                                        //ACTUALIZAR ESTADO
                                                        $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                                                        $actu_id_cd = $this->pedido->actu_id_cd($id_usuario,$id_comanda_detalle);
                                                        //FIN DE LA ACTUALIZADO
                                                        $this->pedido->cambiar_estado_comanda($id_comanda_detalle);
                                                        $pago = 0;
                                                        $id_comanda = $jalar_datos->id_comanda;
                                                        $verificar = $this->pedido->verificar_pago($id_comanda);

                                                        //VALIDAR PARA QUE NO DISMINUYA EL STOCK DE NUEVO
                                                        /*$existe_detalle_comanda = $this->pedido->verificar_existencia_detalle_comanda($id_comanda_detalle);
                                                        $existe = false;
                                                        if(empty($existe_detalle_comanda)){
                                                            $existe = true;
                                                        }*/
                                                        if(isset($_POST['id_venta'])){
                                                            $entr = false;
                                                        }else{
                                                            $entr = true; //cambiar a true cuando se deje de emitir las facturas antiguas para no restar stock
                                                        }
                                                        if ($entr){
                                                            //Aqui ira la disminucion de stock de las recetas aunque no se como pocta lo hare
                                                            $valor_insumos = $this->pedido->valor_insumos($id_comanda_detalle);
                                                            foreach ($valor_insumos as $v){
                                                                $capturar = $v->id_recurso_sede;
                                                                $unidad_medida = $v->id_medida;
                                                                $id_detalle_receta = $v->id_detalle_receta;
                                                                $monto_usado = $v->detalle_receta_cantidad * $cantidad;
                                                                $cantidad = $this->pedido->capturar_cantidad($capturar);
                                                                $valor_cantidad = $cantidad->recurso_sede_stock;
                                                                $montito = $monto_usado * (-1);
                                                                $stock_actual=$this->pedido->stock_actual_comparacion($capturar);
                                                                //AQUI HAREMOS LA OPERACION DEL STOCK
                                                                if($stock_actual->cantidad_nueva_distribuida <= $monto_usado){
                                                                    $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($capturar);
                                                                }else{
                                                                    $restar_nuevo_stock_tabla = $this->pedido->restar_nueva_tabla_stock($monto_usado,$capturar);
                                                                }
                                                            }
                                                            //AQUI SE REALIZARA LA RESTA DEL STOCK DE LOS INSUMOS DE LAS PREFERENCIAS
                                                            $search_new_table = $this->pedido->search_new_table($id_comanda_detalle);
                                                            foreach ($search_new_table as $sn){
                                                                $cantidad_detalle = $this->pedido->cantida_detalle($id_comanda_detalle);
                                                                $id_del_detalle = $this->pedido->id_del_detalle($sn->id_acompanamiento_detalle);
                                                                //ULTIMO ID JALADO
                                                                $valor_final =$this->pedido->valor_final($id_del_detalle->id_receta_preferencia);
                                                                $cantidad_cd = $cantidad_detalle->comanda_detalle_cantidad;
                                                                $cantidad_a_usar = $valor_final->cantidad * $cantidad_cd;
                                                                $recurso = $valor_final->id_recurso;
                                                                $id_recurso_sede =  $this->pedido->sacar_id_recurso_sede($recurso);
                                                                $stock_actual=$this->pedido->stock_actual_comparacion($id_recurso_sede->id_recurso_sede);
                                                                if($stock_actual->cantidad_nueva_distribuida <= $cantidad_a_usar){
                                                                    $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($id_recurso_sede->id_recurso_sede);
                                                                }else{
                                                                    $restar_stock_again = $this->pedido->restar_nueva_tabla_stock($cantidad_a_usar,$id_recurso_sede->id_recurso_sede);
                                                                }
                                                                //$restar_stock_again = $this->pedido->restar_stock_again($cantidad_a_usar,$recurso);
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }else{
                                        for ($i=0;$i<count($celdas);$i++){
                                            $model->id_venta = $id_venta;
                                            $id_comanda_detalle = $celdas[$i];
                                            if($id_comanda_detalle != 0){
                                                $jalar_datos = $this->pedido->jalar_datos($id_comanda_detalle);
                                                $cantidad = $jalar_datos->comanda_detalle_cantidad;
                                                $precio_unitario = $jalar_datos->comanda_detalle_precio;
                                                $codigo_afectacion = $jalar_datos->producto_precio_codigoafectacion;
                                                $igv_detalle = 0;
                                                $factor_porcentaje = 1;
                                                if($codigo_afectacion == 10){
                                                    $igv_detalle = $precio_unitario * $cantidad * $igv_porcentaje;
                                                    $factor_porcentaje = 1 + $igv_porcentaje;
                                                }
                                                //INICIO DEL BLOQUE DE DESCUENTO
                                                if($_POST['descuentito']!=0){
                                                    $pre_item=$precio_unitario;
                                                    $cant_item=$cantidad;
                                                    //VALOR A DESCONTAR
                                                    $var_d = $pre_item * $var_f;
                                                    $valor_resta = $precio_unitario - round($var_d,4);
                                                    $precio_nuevo = $valor_resta;
                                                    $precio_x_cant = $valor_resta * $cant_item;
                                                }else{
                                                    $precio_nuevo = $precio_unitario;
                                                    $precio_x_cant = $precio_unitario * $cantidad;
                                                }
                                                //FINAL DEL BLOQUE DE DESCUNTO
                                                $model->id_comanda_detalle = $id_comanda_detalle;
                                                $model->venta_detalle_valor_unitario = $precio_nuevo;
                                                $model->venta_detalle_precio_unitario = $precio_nuevo  * $factor_porcentaje;
                                                $model->venta_detalle_nombre_producto = $jalar_datos->producto_nombre;
                                                $model->venta_detalle_cantidad = $cantidad;
                                                //$model->venta_total_selled = 1;
                                                $model->venta_detalle_total_igv = $igv_detalle;
                                                $model->venta_detalle_porcentaje_igv = $igv_porcentaje * 100;
                                                //$model->id_igv = 1;
                                                $model->venta_detalle_total_icbper = 0.00;
                                                $model->venta_detalle_valor_total = $precio_x_cant;
                                                $model->venta_detalle_total_price = $precio_x_cant * $factor_porcentaje;
                                                $model->id_producto_precio=$jalar_datos->id_producto;
                                                //AQUI SERIA UNA FORMA DE QUE SE HAGA LA NUEVA BOLETA
                                                if($_POST['tipo_venta']=="20"){
                                                    //$result = $this->pedido->guardar_nota_venta_detalle($model);
                                                    $result = $this->pedido->guardar_venta_detalle($model);
                                                }else{
                                                    $result = $this->pedido->guardar_venta_detalle($model);
                                                }
                                                if($result == 1){
                                                    //ACTUALIZAR ESTADO
                                                    $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                                                    $actu_id_cd = $this->pedido->actu_id_cd($id_usuario,$id_comanda_detalle);
                                                    //FIN DE LA ACTUALIZADO
                                                    $this->pedido->cambiar_estado_comanda($id_comanda_detalle);
                                                    $pago = 0;
                                                    $id_comanda = $jalar_datos->id_comanda;
                                                    $verificar = $this->pedido->verificar_pago($id_comanda);

                                                    //VALIDAR PARA QUE NO DISMINUYA EL STOCK DE NUEVO
                                                    /*$existe_detalle_comanda = $this->pedido->verificar_existencia_detalle_comanda($id_comanda_detalle);
                                                    $existe = false;
                                                    if(empty($existe_detalle_comanda)){
                                                        $existe = true;
                                                    }*/
                                                    if(isset($_POST['id_venta'])){
                                                        $entr = false;
                                                    }else{
                                                        $entr = true; //cambiar a true cuando se deje de emitir las facturas antiguas para no restar stock
                                                    }
                                                    if ($entr){
                                                        //Aqui ira la disminucion de stock de las recetas aunque no se como pocta lo hare
                                                        $valor_insumos = $this->pedido->valor_insumos($id_comanda_detalle);
                                                        foreach ($valor_insumos as $v){
                                                            $capturar = $v->id_recurso_sede;
                                                            $unidad_medida = $v->id_medida;
                                                            $id_detalle_receta = $v->id_detalle_receta;
                                                            $monto_usado = $v->detalle_receta_cantidad * $cantidad;
                                                            $cantidad = $this->pedido->capturar_cantidad($capturar);
                                                            $valor_cantidad = $cantidad->recurso_sede_stock;
                                                            $montito = $monto_usado * (-1);
                                                            $stock_actual=$this->pedido->stock_actual_comparacion($capturar);
                                                            //AQUI HAREMOS LA OPERACION DEL STOCK
                                                            if($stock_actual->cantidad_nueva_distribuida <= $monto_usado){
                                                                $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($capturar);
                                                            }else{
                                                                $restar_nuevo_stock_tabla = $this->pedido->restar_nueva_tabla_stock($monto_usado,$capturar);
                                                            }
                                                        }
                                                        //AQUI SE REALIZARA LA RESTA DEL STOCK DE LOS INSUMOS DE LAS PREFERENCIAS
                                                        $search_new_table = $this->pedido->search_new_table($id_comanda_detalle);
                                                        foreach ($search_new_table as $sn){
                                                            $cantidad_detalle = $this->pedido->cantida_detalle($id_comanda_detalle);
                                                            $id_del_detalle = $this->pedido->id_del_detalle($sn->id_acompanamiento_detalle);
                                                            //ULTIMO ID JALADO
                                                            $valor_final =$this->pedido->valor_final($id_del_detalle->id_receta_preferencia);
                                                            $cantidad_cd = $cantidad_detalle->comanda_detalle_cantidad;
                                                            $cantidad_a_usar = $valor_final->cantidad * $cantidad_cd;
                                                            $recurso = $valor_final->id_recurso;
                                                            $id_recurso_sede =  $this->pedido->sacar_id_recurso_sede($recurso);
                                                            $stock_actual=$this->pedido->stock_actual_comparacion($id_recurso_sede->id_recurso_sede);
                                                            if($stock_actual->cantidad_nueva_distribuida <= $cantidad_a_usar){
                                                                $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($id_recurso_sede->id_recurso_sede);
                                                            }else{
                                                                $restar_stock_again = $this->pedido->restar_nueva_tabla_stock($cantidad_a_usar,$id_recurso_sede->id_recurso_sede);
                                                            }
                                                            //$restar_stock_again = $this->pedido->restar_stock_again($cantidad_a_usar,$recurso);
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }
                                $contenido_tipopago = $_POST['contenido_tipopago'];
                                if(count_chars($contenido_tipopago)>0){
                                    $filas=explode('/-/-',$contenido_tipopago);
                                    if(count($filas)>0){
                                        for ($i=0;$i<count($filas)-1;$i++){
                                            $modelDSI=new Pedido();
                                            $celdas=explode('-.-.',$filas[$i]);
                                            $modelDSI->id_venta = $id_venta;
                                            $modelDSI->id_tipo_pago = $celdas[0];
                                            $modelDSI->venta_detalle_pago_monto = $celdas[1];
                                            $modelDSI->venta_detalle_pago_estado = 1;
                                            $modelDSI->venta_detalle_estado = 0;
                                            $result__ = $this->pedido->guardar_detalle_pago($modelDSI);

                                        }
                                    }
                                }

                                if($result==1){
                                    //CAMBIO DE ESTADO DE BOLETA ANTISIPO
                                    $cambio_estado=$this->pedido->cambio_estado_bol_p($_POST['id_boleta_pis']);
                                    //FIN DEL CAMBIO
                                    $result = $this->pedido->actualizarCorrelativo_x_id_Serie($_POST['serie'],$correlativo);
                                    if (!isset($_POST['id_venta'])){
                                        if(empty($verificar)){
                                            $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                                            $this->pedido->ocultar_reserva($id_mesa);
                                            $pago = 1;
                                        }
                                    }

                                    if($_POST['tipo_venta']=="20"){
                                        $venta = $this->venta->listar_venta_x_id($id_venta);
                                        $detalle_venta =$this->venta->listar_venta_detalle_x_id_venta($id_venta);
                                        $empresa = $this->venta->listar_empresa_x_id_empresa($venta->id_empresa);
                                        $cliente = $this->venta->listar_clienteventa_x_id($venta->id_cliente);
                                        $venta_tipo = "NOTA DE VENTA";
                                        if($cliente->id_tipodocumento == "4"){
                                            $cliente_nombre = $cliente->cliente_razonsocial;
                                        }else{
                                            $cliente_nombre = $_POST['cliente_nombre'];
                                        }
                                        if($_POST['imprimir'] == "1"){
                                            require _VIEW_PATH_ . 'pedido/ticket_nota_venta.php';
                                        }
                                    }else{
                                        //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                                        include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                                        $venta = $this->venta->listar_venta_x_id_res($id_venta);
                                        $detalle_venta =$this->venta->listar_venta_detalle_x_id_venta($id_venta);
                                        $comanda = $this->pedido->listar_detalle_x_comanda_detalle($detalle_venta[0]->id_comanda_detalle);
                                        $empresa = $this->venta->listar_empresa_x_id_empresa($venta->id_empresa);
                                        $cliente = $this->venta->listar_clienteventa_x_id($venta->id_cliente);
                                        //INICIO - CREACION QR
                                        $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
                                        $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                                            $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                                            $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
                                        $ruta = 'libs/ApiFacturacion/imagenqr/';
                                        $ruta_qr = $ruta.$nombre_qr.'.png';
                                        QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                                        //FIN - CREACION QR
                                        if($venta->venta_tipo == "03"){
                                            $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
                                        }elseif($venta->venta_tipo == "01"){
                                            $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
                                        }
                                        if($cliente->id_tipodocumento == "4"){
                                            $cliente_nombre = $cliente->cliente_razonsocial;
                                        }else{
                                            $cliente_nombre = $_POST['cliente_nombre'];
                                        }
                                        if($_POST['imprimir'] == "1"){
                                            require _VIEW_PATH_ . 'pedido/ticket_venta.php';
                                        }
                                    }
                                    //INICIO - TICKET DELIVERY
                                    //$venta = $this->venta->listar_venta_x_id($id);
                                    if($id_mesa == 0){
                                        $cliente = $this->pedido->listar_clienteventa_x_id_delivery($id_cliente);
                                        $id_comanda = $cliente->id_comanda;
                                        $datos_ticket = $this->pedido->jalar_datos_comanda($id_comanda);

                                        if($cliente->id_tipodocumento == "4"){
                                            $cliente_nombre = $cliente->cliente_razonsocial;
                                        }else{
                                            $cliente_nombre = $_POST['cliente_nombre'];
                                        }
                                        require _VIEW_PATH_ . 'pedido/ticket_venta_delivery.php';
                                    }
                                }
                            }
                        }else{
                            $result = 2;
                        }
                    }
                }else{
                    if($result == 1){
                        $id_mesa = $_POST['id_mesa'];
                        $fecha = date('Y-m-d H:i:s');
                        $model->id_mesa = $id_mesa;
                        $model->id_usuario = $id_usuario;
                        $model->id_comanda = $_POST['id_comanda'];
                        $model->cliente_numero = $_POST['cliente_numero'];
                        $model->cliente_nombre = $_POST['cliente_nombre'];
                        $model->observacion_cortesia = $_POST['observacion_cortesia'];
                        $model->cliente_direccion = $_POST['cliente_direccion'];
                        $model->venta_total = $_POST['venta_total'];
                        $model->fecha = $fecha;
                        $microtime = microtime(true);
                        $model->codigo = $microtime;
                        $result = $this->pedido->guardar_pedido_gratis($model);
                        if($result == 1){
                            $pedido_gratis = $this->pedido->listar_pedido_gratis_x_mt($microtime);
                            $datos_detalle_pedido = $_POST['datos_detalle_pedido'];
                            $id_pedido_gratis = $pedido_gratis->id_pedido_gratis;
                            if(count_chars($datos_detalle_pedido)>0){
                                $celdas=explode('-.-.',$datos_detalle_pedido);
                                if(count($celdas)>0){
                                    for ($i=0;$i<count($celdas);$i++){
                                        $id_comanda_detalle = $celdas[$i];
                                        $jalar_datos = $this->pedido->jalar_datos($id_comanda_detalle);
                                        $model->id_pedido_gratis = $id_pedido_gratis;
                                        $model->id_comanda_detalle = $id_comanda_detalle;

                                        $result = $this->pedido->guardar_pedido_gratis_detalle($model);
                                        if($result == 1){
                                            $this->pedido->cambiar_estado_comanda($id_comanda_detalle);
                                            $pago = 0;
                                            $id_comanda = $jalar_datos->id_comanda;
                                            $verificar = $this->pedido->verificar_pago($id_comanda);
                                            //VALIDAR PARA QUE NO DISMINUYA EL STOCK DE NUEVO
                                            /*$existe_detalle_comanda = $this->pedido->verificar_existencia_detalle_comanda($id_comanda_detalle);
                                            $existe = false;
                                            if(empty($existe_detalle_comanda)){
                                                $existe = true;
                                            }*/
                                            if(isset($_POST['id_venta'])){
                                                $entr = false;
                                            }else{
                                                $entr = true; //cambiar a true cuando se deje de emitir las facturas antiguas para no restar stock
                                            }
                                            if ($entr){
                                                //Aqui ira la disminucion de stock aunque no se como pocta lo hare
                                                $valor_insumos = $this->pedido->valor_insumos($id_comanda_detalle);
                                                foreach ($valor_insumos as $v){
                                                    $cantidad = $jalar_datos->comanda_detalle_cantidad;
                                                    $capturar = $v->id_recurso_sede;
                                                    $unidad_medida = $v->id_medida;
                                                    $id_detalle_receta = $v->id_detalle_receta;
                                                    $monto_usado = $v->detalle_receta_cantidad * $cantidad;
                                                    $cantidad = $this->pedido->capturar_cantidad($capturar);
                                                    $valor_cantidad = $cantidad->recurso_sede_stock;
                                                    /*if($v->detalle_receta_unidad_medida != 0){
                                                        $detalle_conversion = $this->pedido->conversion_por_id($v->detalle_receta_unidad_medida);
                                                        $nuevo_monto = ($monto_usado / $detalle_conversion->conversion_cantidad) * (-1);
                                                        $actualizar_stock = $this->pedido->actualizar_stock($nuevo_monto,$capturar);
                                                    }else{*/
                                                    $montito = $monto_usado * (-1);
                                                    /*$actualizar_stock = $this->pedido->actualizar_stock($montito,$capturar);
                                                }*/
                                                    $stock_actual=$this->pedido->stock_actual_comparacion($capturar);
                                                    //AQUI HAREMOS LA OPERACION DEL STOCK
                                                    if($stock_actual->cantidad_nueva_distribuida <= $monto_usado){
                                                        $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($capturar);
                                                    }else{
                                                        $restar_nuevo_stock_tabla = $this->pedido->restar_nueva_tabla_stock($monto_usado,$capturar);
                                                    }

                                                    //AQUI SE REALIZARA LA RESTA DEL STOCK DE LOS INSUMOS DE LAS PREFERENCIAS
                                                    $search_new_table = $this->pedido->search_new_table($id_comanda_detalle);
                                                    if($search_new_table){
                                                        $id_del_detalle = $this->pedido->id_del_detalle($search_new_table->id_acompanamiento_detalle);
                                                        //ULTIMO ID JALADO
                                                        $valor_final =$this->pedido->valor_final($id_del_detalle->id_receta_preferencia);
                                                        $cantidad_cd = $v->comanda_detalle_cantidad;
                                                        $cantidad_a_usar = $valor_final->cantidad * $cantidad_cd;
                                                        $recurso = $valor_final->id_recurso;
                                                        $restar_stock_again = $this->pedido->restar_stock_again($cantidad_a_usar,$recurso);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if($result==1){
                                    if(empty($verificar)){
                                        $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                                        $pago = 1;
                                    }
                                }
                            }
                        }
                        if($result==1){
                            $result__ = $this->pedido->ocultar_reserva($id_mesa);
                        }
                    }
                }
            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "pago"=>$pago)));
    }

    public function transferir_mesa_x_pedido(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_mesa_transp', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_comanda_detalle_transferir', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                if($this->pedido->verificar_password_cambiado($_POST['password_xp'])) {
                    $id_mesa_transp = $_POST['id_mesa_transp'];
                    $id_comanda_detalle_transferir = $_POST['id_comanda_detalle_transferir'];
                    $id_mesa = $_POST['id_mesa'];
                    $extraer_id_comanda = $this->pedido->extraer_id_comanda_destino($id_mesa_transp);
                    $extraer_id_comanda_resta = $this->pedido->extraer_id_comanda_destino($id_mesa);
                    $result = $this->pedido->actualizar_id_comanda_en_detalle($extraer_id_comanda->id_comanda, $id_comanda_detalle_transferir);
                    if ($result == 1) {
                        $jalar_pedidos_mesa_actual = $this->pedido->jalar_pedidos_mesa_actual($extraer_id_comanda->id_comanda);
                        $total_comanda = 0;
                        foreach ($jalar_pedidos_mesa_actual as $jp) {
                            $total_comanda = $total_comanda + $jp->comanda_detalle_total;
                        }
                        $result = $this->pedido->sumar_valor_comandas_($total_comanda, $extraer_id_comanda->id_comanda);
                        if ($result == 1) {
                            $jalar_pedidos_mesa_actual = $this->pedido->jalar_pedidos_mesa_actual($extraer_id_comanda_resta->id_comanda);
                            $total_comanda = 0;
                            foreach ($jalar_pedidos_mesa_actual as $jp) {
                                $total_comanda = $total_comanda + $jp->comanda_detalle_total;
                            }
                            $result = $this->pedido->sumar_valor_comandas_($total_comanda, $extraer_id_comanda_resta->id_comanda);
                            $datos_comanda_detalle = $this->pedido->listar_detalle_x_comanda($extraer_id_comanda_resta->id_comanda);
                            $mesa_estado = 0; //la mesa no cambia de estado
                            if (empty($datos_comanda_detalle)) {
                                $result = $this->pedido->actualizar_estado_mesa($id_mesa);
                                if ($result == 1) {
                                    $mesa_estado = 1; //la mesa se actualiza a sucia y sale del detalle para que cargue en asignar
                                }

                            }
                        }
                    }
                }else{
                    $result = 5;
                    $message = 'Contraseña Incorrecta';
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "estado_mesa"=>$mesa_estado)));
    }

    public function llenar_dat_cliente(){
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            if ($ok_data) {
                $result = $this->pedido->dato_cliente_habitacion($_POST['id']);
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //GUARDAR VINCULACION
    public function guardar_vinculacion(){
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            if ($ok_data) {
                if(isset($_POST['id_rack']) && !empty($_POST['id_rack'])){
                    $id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                    $sacar_correlativo = $this->pedido->correlativo($_POST['id_comanda']);
                    $model2 = new Hotel();
                    $comanda = $this->pedido->listar_comanda_detalle_x_id($_POST['id_comanda']);
                    foreach ($comanda as $c){
                        //CODIGO COPIADO
                        $valor_insumos = $this->pedido->valor_insumos($c->id_comanda_detalle);
                        foreach ($valor_insumos as $v) {
                            $cantidad = $v->comanda_detalle_cantidad;
                            $capturar = $v->id_recurso_sede;
                            $unidad_medida = $v->id_medida;
                            $id_detalle_receta = $v->id_detalle_receta;
                            $monto_usado = $v->detalle_receta_cantidad * $cantidad;
                            $cantidad = $this->pedido->capturar_cantidad($capturar);
                            $valor_cantidad = $cantidad->recurso_sede_stock;

                            $stock_actual = $this->pedido->stock_actual_comparacion($capturar);
                            //AQUI HAREMOS LA OPERACION DEL STOCK
                            if ($stock_actual->cantidad_nueva_distribuida <= $monto_usado) {
                                $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($capturar);
                            } else {
                                $restar_nuevo_stock_tabla = $this->pedido->restar_nueva_tabla_stock($monto_usado, $capturar);
                            }
                        }

                        //AQUI SE REALIZARA LA RESTA DEL STOCK DE LOS INSUMOS DE LAS PREFERENCIAS
                        $search_new_table = $this->pedido->search_new_table($c->id_comanda_detalle);
                        foreach ($search_new_table as $sn){
                            $cantidad_detalle = $this->pedido->cantida_detalle($c->id_comanda_detalle);
                            $id_del_detalle = $this->pedido->id_del_detalle($sn->id_acompanamiento_detalle);
                            //ULTIMO ID JALADO
                            $valor_final =$this->pedido->valor_final($id_del_detalle->id_receta_preferencia);
                            $cantidad_cd = $cantidad_detalle->comanda_detalle_cantidad;
                            $cantidad_a_usar = $valor_final->cantidad * $cantidad_cd;
                            $recurso = $valor_final->id_recurso;
                            $id_recurso_sede =  $this->pedido->sacar_id_recurso_sede($recurso);
                            $stock_actual=$this->pedido->stock_actual_comparacion($id_recurso_sede->id_recurso_sede);
                            if($stock_actual->cantidad_nueva_distribuida <= $cantidad_a_usar){
                                $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($id_recurso_sede->id_recurso_sede);
                            }else{
                                $restar_stock_again = $this->pedido->restar_nueva_tabla_stock($cantidad_a_usar,$id_recurso_sede->id_recurso_sede);
                            }
                            //$restar_stock_again = $this->pedido->restar_stock_again($cantidad_a_usar,$recurso);
                        }

                        //FIN DEL CODIDO COPIADO
                        $model2->rack_detalle_fecha= date("Y-m-d");;
                        $model2->id_rack=$_POST['id_rack'];
                        $model2->id_producto=$c->id_producto;
                        $model2->rack_detalle_correlativo_comanda=$sacar_correlativo->comanda_correlativo;
                        $model2->rack_detalle_cantidad=$c->comanda_detalle_cantidad;
                        $model2->rack_detalle_preciounit=$c->comanda_detalle_precio;
                        $model2->rack_detalle_subtotal=$c->comanda_detalle_total;
                        $model2->rack_detalle_estado_fact=0;
                        $model2->id_comanda_detalle=$c->id_comanda_detalle;
                        $result = $this->hotel->guardar_detalle_rack($model2);
                    }
                    //AQUI SE IMPRIMIRA EL TICKET DE LA NUEVA PRE CUENTA PARA SU RESPECTIVA FIRMA
                    //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                    $total_comanda = $this->pedido->listar_comanda_x_id($_POST['id_comanda']);
                    $nombre_cliente = $_POST['nombre_cliente'];
                    $dni_cliente = $_POST['dni_cliente'];
                    $mesero = $total_comanda->persona_nombre.' '.$total_comanda->persona_apellido_paterno;
                    $num_habitacion = $this->pedido->listar_habitaciones_id($_POST['id_habitacion']);
                    $pedidos = $this->pedido->listar_detalle_x_comanda_para_precuenta($_POST['id_comanda']);
                    $dato_pedido = $this->pedido->listar_comanda_x_id($_POST['id_comanda']);
                    $fecha_hoy = date('Y-m-d');
                    //FIN DE LA IMPRESION DE LA PRE CUENTA
                    //$this->pedido->modificar_total_rack($total_comanda->comanda_total,$_POST['id_rack']);
                    $this->pedido->cambiar_estado_detalle_comanda($id_usuario,$_POST['id_comanda']);
                    $this->pedido->actualizar_estado_mesa($_POST['id_mesa']);
                    require _VIEW_PATH_ . 'pedido/ticket_pedido_vinculacion.php';
                }else{
                    $result = 16;
                    $message = "Existe un error con la habitación";
                }
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function guardar_cargo_cuenta()
    {
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            if ($ok_data) {
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                //VALIDACION SI EXISTE CUENTA
                $revisar_cuenta_pasada = $this->cuentascobrar->revisar_cuenta_pasada($_POST['id_cliente']);
                $sacar_correlativo = $this->pedido->correlativo($_POST['id_comanda']);
                $comanda = $this->pedido->listar_comanda_detalle_x_id($_POST['id_comanda']);
                //AQUI SE LLENARIA LA TABLA DE CUENTAS CUANDO LOS PEDIDOS SON ENVIADOS DESDE UNA MESA DEL RESTAURANTE
                if(empty($revisar_cuenta_pasada)) {
                    //LLENAMOS LA TABLA DE LA CABECERA
                    $model = new Hotel();
                    $model->id_usuario = $id_usuario;
                    $model->id_cliente = $_POST['id_cliente'];
                    $model->cuentas_total = $sacar_correlativo->comanda_total;
                    $model->cuentas_total_pagado = 0.00;
                    $model->cuenta_fecha_creacion = date('Y-m-d H:i:s');
                    $model->cuenta_cancelado = 0;
                    $model->cuenta_estado = 1;
                    $microtime_ = microtime();
                    $model->cuenta_codigo = $microtime_;
                    $model->id_moneda = 1;
                    $result = $this->pedido->guardar_cuenta_por_cobrar($model);
                    if ($result == 1) {
                        $id_cuenta = $this->pedido->sacar_id_cuenta($microtime_);
                            foreach ($comanda as $c) {
                                //CODIGO COPIADO
                                $valor_insumos = $this->pedido->valor_insumos($c->id_comanda_detalle);
                                foreach ($valor_insumos as $v) {
                                    $cantidad = $v->comanda_detalle_cantidad;
                                    $capturar = $v->id_recurso_sede;
                                    $unidad_medida = $v->id_medida;
                                    $id_detalle_receta = $v->id_detalle_receta;
                                    $monto_usado = $v->detalle_receta_cantidad * $cantidad;
                                    $cantidad = $this->pedido->capturar_cantidad($capturar);
                                    $valor_cantidad = $cantidad->recurso_sede_stock;
                                    $stock_actual = $this->pedido->stock_actual_comparacion($capturar);
                                    //AQUI HAREMOS LA OPERACION DEL STOCK
                                    if ($stock_actual->cantidad_nueva_distribuida <= $monto_usado) {
                                        $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($capturar);
                                    } else {
                                        $restar_nuevo_stock_tabla = $this->pedido->restar_nueva_tabla_stock($monto_usado, $capturar);
                                    }
                                }

                                //AQUI SE REALIZARA LA RESTA DEL STOCK DE LOS INSUMOS DE LAS PREFERENCIAS
                                $search_new_table = $this->pedido->search_new_table($c->id_comanda_detalle);
                                foreach ($search_new_table as $sn) {
                                    $cantidad_detalle = $this->pedido->cantida_detalle($c->id_comanda_detalle);
                                    $id_del_detalle = $this->pedido->id_del_detalle($sn->id_acompanamiento_detalle);
                                    //ULTIMO ID JALADO
                                    $valor_final = $this->pedido->valor_final($id_del_detalle->id_receta_preferencia);
                                    $cantidad_cd = $cantidad_detalle->comanda_detalle_cantidad;
                                    $cantidad_a_usar = $valor_final->cantidad * $cantidad_cd;
                                    $recurso = $valor_final->id_recurso;
                                    $id_recurso_sede = $this->pedido->sacar_id_recurso_sede($recurso);
                                    $stock_actual = $this->pedido->stock_actual_comparacion($id_recurso_sede->id_recurso_sede);
                                    if ($stock_actual->cantidad_nueva_distribuida <= $cantidad_a_usar) {
                                        $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($id_recurso_sede->id_recurso_sede);
                                    } else {
                                        $restar_stock_again = $this->pedido->restar_nueva_tabla_stock($cantidad_a_usar, $id_recurso_sede->id_recurso_sede);
                                    }
                                    //$restar_stock_again = $this->pedido->restar_stock_again($cantidad_a_usar,$recurso);
                                }
                                //FIN DEL CODIDO COPIADO
                                $model2 = new Hotel();
                                $model2->id_cuenta = $id_cuenta->id_cuenta;
                                $model2->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                                $model2->id_rack_detalle = NULL;
                                $model2->id_comanda_detalle = $c->id_comanda_detalle;
                                $model2->cuentas_detalle_comanda_correlativo = $sacar_correlativo->comanda_correlativo;
                                $model2->cuentas_detalle_fecha_creacion = date('Y-m-d H:i:s');
                                $model2->cuenta_detalle_estado = 1;
                                $result = $this->pedido->guardar_detalle_por_cobrar($model2);
                            }
                            //FIN DEL GUARDADO DEL ENVIO
                        }
                    } else {
                        $total = $sacar_correlativo->comanda_total;
                        $id_cuenta = $revisar_cuenta_pasada->id_cuenta;
                        foreach ($comanda as $c) {
                            //CODIGO COPIADO
                            $valor_insumos = $this->pedido->valor_insumos($c->id_comanda_detalle);
                            foreach ($valor_insumos as $v) {
                                $cantidad = $v->comanda_detalle_cantidad;
                                $capturar = $v->id_recurso_sede;
                                $unidad_medida = $v->id_medida;
                                $id_detalle_receta = $v->id_detalle_receta;
                                $monto_usado = $v->detalle_receta_cantidad * $cantidad;
                                $cantidad = $this->pedido->capturar_cantidad($capturar);
                                $valor_cantidad = $cantidad->recurso_sede_stock;
                                $stock_actual = $this->pedido->stock_actual_comparacion($capturar);
                                //AQUI HAREMOS LA OPERACION DEL STOCK
                                if ($stock_actual->cantidad_nueva_distribuida <= $monto_usado) {
                                    $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($capturar);
                                } else {
                                    $restar_nuevo_stock_tabla = $this->pedido->restar_nueva_tabla_stock($monto_usado, $capturar);
                                }
                            }

                            //AQUI SE REALIZARA LA RESTA DEL STOCK DE LOS INSUMOS DE LAS PREFERENCIAS
                            $search_new_table = $this->pedido->search_new_table($c->id_comanda_detalle);
                            foreach ($search_new_table as $sn) {
                                $cantidad_detalle = $this->pedido->cantida_detalle($c->id_comanda_detalle);
                                $id_del_detalle = $this->pedido->id_del_detalle($sn->id_acompanamiento_detalle);
                                //ULTIMO ID JALADO
                                $valor_final = $this->pedido->valor_final($id_del_detalle->id_receta_preferencia);
                                $cantidad_cd = $cantidad_detalle->comanda_detalle_cantidad;
                                $cantidad_a_usar = $valor_final->cantidad * $cantidad_cd;
                                $recurso = $valor_final->id_recurso;
                                $id_recurso_sede = $this->pedido->sacar_id_recurso_sede($recurso);
                                $stock_actual = $this->pedido->stock_actual_comparacion($id_recurso_sede->id_recurso_sede);
                                if ($stock_actual->cantidad_nueva_distribuida <= $cantidad_a_usar) {
                                    $stock_a_cero = $this->pedido->restar_nueva_tabla_stock_cero($id_recurso_sede->id_recurso_sede);
                                } else {
                                    $restar_stock_again = $this->pedido->restar_nueva_tabla_stock($cantidad_a_usar, $id_recurso_sede->id_recurso_sede);
                                }
                                //$restar_stock_again = $this->pedido->restar_stock_again($cantidad_a_usar,$recurso);
                            }
                            //FIN DEL CODIDO COPIADO
                            $model2 = new Hotel();
                            $model2->id_cuenta = $id_cuenta;
                            $model2->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                            $model2->id_rack_detalle = NULL;
                            $model2->id_comanda_detalle = $c->id_comanda_detalle;
                            $model2->cuentas_detalle_comanda_correlativo = $sacar_correlativo->comanda_correlativo;
                            $model2->cuentas_detalle_fecha_creacion = date('Y-m-d H:i:s');
                            $model2->cuenta_detalle_estado = 1;
                            $result = $this->pedido->guardar_detalle_por_cobrar($model2);
                        }
                        if($result==1){
                            //SE PROCEDE ACTUALIZAR LA SUMA DE LA CUENTA
                            $sumar_cuenta = $this->cuentascobrar->sumar_cuenta($total,$id_cuenta);
                        }
                    }
                    $this->pedido->cambiar_estado_detalle_comanda_cargo($id_usuario, $_POST['id_comanda']);
                    $this->pedido->actualizar_estado_mesa($_POST['id_mesa']);

            } else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

}
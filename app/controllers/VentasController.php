<?php
require 'app/models/Ventas.php';
require 'app/models/Inventario.php';
require 'app/models/Clientes.php';
require 'app/models/Hotel.php';
require 'app/models/Usuario.php';
require 'app/models/Pedido.php';
require 'app/models/Builder.php';
require 'app/models/Cuentascobrar.php';
require 'app/models/Nmletras.php';
require 'app/models/ApiFacturacion.php';
require 'app/models/GeneradorXML.php';
class VentasController
{
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
    private $builder;

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
        $this->generadorXML= new GeneradorXML();
        $this->apiFacturacion= new ApiFacturacion();
        $this->numLetra = new Nmletras();
        $this->pedido = new Pedido();
        $this->cuentascobrar = new Cuentascobrar();
        $this->builder = new Builder();
    }

    public function realizar_venta(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $_SESSION['productos'] = array();
            //LISTAMOS LOS PRODUCTOS
            $productos = $this->inventario->listar_productos_con_stock();
            //LISTAMOS LOS CLIENTES
            $clientes = $this->ventas->listar_clientes();

            $tiponotacredito = $this->ventas->listAllCredito();
            $tiponotadebito = $this->ventas->listAllDebito();
            $tipo_pago = $this->ventas->listar_tipo_pago();
            $tipos_documento = $this->clientes->listar_documentos();


            $fecha = date('Y-m-d');
            $caja_apertura_fecha = $this->admin->listar_ultima_fecha($fecha);
            if($caja_apertura_fecha == true){
                require _VIEW_PATH_ . 'header.php';
                require _VIEW_PATH_ . 'navbar.php';
                require _VIEW_PATH_ . 'ventas/realizar_venta.php';
                require _VIEW_PATH_ . 'footer.php';
            }else{
                echo "<script language=\"javascript\">alert(\"Para realizar una venta debes aperturar Caja. Redireccionando Al Inicio\");</script>";
                echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            }
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function tabla_productos(){
        try{
            require _VIEW_PATH_ . 'ventas/tabla_productos.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<br><br><div style='text-align: center'><h3>Ocurrió Un Error Al Cargar La Informacion</h3></div>";
        }
    }
    public function historial_ventas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            $ventas_cant = $this->ventas->listar_ventas_sin_enviar();

            if(isset($_POST['enviar_registro'])){
                $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario 
                        inner join roles r on u.id_rol = r.id_rol
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 0 and v.venta_tipo <> 20";
                $select = "";
                $where = true;
                if($_POST['tipo_venta']!=""){
                    $where = true;
                    $select = $select . " and v.venta_tipo = '" . $_POST['tipo_venta'] . "'";
                    $tipo_venta = $_POST['tipo_venta'];
                }

                if($_POST['fecha_inicio'] != "" AND $_POST['fecha_final'] != ""){
                    $where = true;
                    $select = $select . " and DATE(v.venta_fecha) between '" . $_POST['fecha_inicio'] ."' and '" . $_POST['fecha_final'] ."'";
                    $fecha_ini = $_POST['fecha_inicio'];
                    $fecha_fin = $_POST['fecha_final'];
                }

                if($where){
                    $datos = true;
                    $order = " order by v.venta_fecha asc";
                    $query = $query . $select . $order;
                    $ventas = $this->ventas->listar_ventas($query);
                }
                $tipo_venta = "";
                /*if($_POST['tipo_venta']!="" && $_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_todo($_POST['tipo_venta'],$_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }elseif($_POST['tipo_venta']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_tipo($_POST['tipo_venta'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                }elseif($_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_estado($_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }else{
                    $ventas = $this->ventas->listar_ventas_filtro_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);
                }*/
                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_ventas.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_ventas_enviadas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            $tipo_venta = '';
            $total_soles = '';
            $total_dolares = '';
            if(isset($_POST['enviar_registro'])){
                $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario 
                        inner join roles r on u.id_rol = r.id_rol
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 1 and v.venta_tipo <> 20";
                $select = "";
                $where = true;
                if($_POST['tipo_venta']!=""){
                    $where = true;
                    $select = $select . " and v.venta_tipo = '" . $_POST['tipo_venta'] . "'";
                    $tipo_venta = $_POST['tipo_venta'];
                }

                if($_POST['fecha_inicio'] != "" AND $_POST['fecha_final'] != ""){
                    $where = true;
                    $select = $select . " and DATE(v.venta_fecha) between '" . $_POST['fecha_inicio'] ."' and '" . $_POST['fecha_final'] ."'";
                    $fecha_ini = $_POST['fecha_inicio'];
                    $fecha_fin = $_POST['fecha_final'];
                }
                if($_POST['lugar']!=""){
                    if($_POST['lugar']==1){
                        $where = true;
                        $select = $select . " and v.id_caja_numero = '" . $_POST['lugar'] . "'";
                        $lugar = $_POST['lugar'];
                    }else{
                        $where = true;
                        $select = $select . " and v.id_caja_numero is null";
                        $lugar = $_POST['lugar'];
                    }
                }

                if($where){
                    $datos = true;
                    $order = " order by v.venta_fecha asc";
                    $query = $query . $select . $order;
                    $ventas = $this->ventas->listar_ventas($query);
                }

                /*if($_POST['tipo_venta']!="" && $_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_todo($_POST['tipo_venta'],$_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }elseif($_POST['tipo_venta']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_tipo($_POST['tipo_venta'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                }elseif($_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_estado($_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }else{
                    $ventas = $this->ventas->listar_ventas_filtro_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);
                }*/
                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_ventas_enviadas.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function imprimir_ticket_pdf(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if ($id == 0) {throw new Exception('ID Sin Declarar');}
            $dato_venta = $this->ventas->listar_venta_x_id_pdf($id);
            $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($id);
            $fecha_hoy = date('d-m-Y H:i:s');
            $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
            if ($dato_venta->venta_tipo == "03") {
                $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                if($dato_venta->cliente_numero == "11111111"){
                    $documento = "DNI:                        SIN DOCUMENTO";
                }else{
                    $documento = "DNI:                        $dato_venta->cliente_numero";
                }
            } else if ($dato_venta->venta_tipo == "01") {
                $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "RUC:                      $dato_venta->cliente_numero";
            } else if ($dato_venta->venta_tipo == "07") {
                $tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "DOCUMENTO: $dato_venta->cliente_numero";
            } else {
                $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "DOCUMENTO: $dato_venta->cliente_numero";
            }
            $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
            $arrayImporte = explode(".", $dato_venta->venta_total);
            $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
            //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
            $dato_impresion = 'DATOS DE IMPRESIÓN:';
            require _VIEW_PATH_ . 'ventas/imprimir_ticket_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }
    public function imprimir_pdf(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if ($id == 0) {throw new Exception('ID Sin Declarar');}
            $dato_venta = $this->ventas->listar_venta_x_id_pdf($id);
            $buscar_tp=$this->ventas->buscar_tipo_pagos_($id);
            $tipo_pago="";
            foreach ($buscar_tp as $bt){
                $tipo_pago .= '- '.$bt->tipo_pago_nombre.' ';
            }
            $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($id);
            $fecha_hoy = $dato_venta->venta_fecha;
            $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
            //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
            if(!file_exists($ruta_qr)){
                include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                $venta = $this->ventas->listar_venta($id);
                $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                //INICIO - CREACION QR
                $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
                $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                    $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                    $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
                $ruta = 'libs/ApiFacturacion/imagenqr/';
                $ruta_qr = $ruta.$nombre_qr.'.png';
                QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                //FIN - CREACION QR
            }

            $dnni="DNI";
            if ($dato_venta->venta_tipo == "03") {
                $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-00000".$dato_venta->venta_correlativo;
                if($dato_venta->cliente_numero == "11111111"){
                    $documento = "SIN DOCUMENTO";
                }else{
                    $documento = "$dato_venta->cliente_numero";
                }
            } else if ($dato_venta->venta_tipo == "01") {
                $dnni="RUC";
                $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-00000".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            } else if ($dato_venta->venta_tipo == "07") {
                if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                $tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-00000".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            } else {
                if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-00000".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            }
            $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
            $arrayImporte = explode(".", $dato_venta->venta_total);
            $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
            //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
            $dato_impresion = 'DATOS DE IMPRESIÓN:';
            require _VIEW_PATH_ . 'ventas/imprimir_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script>window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }
    public function envio_resumenes_diario(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = '';
            if(isset($_POST['enviar_registro'])){
                $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario 
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 0 and v.venta_tipo <> '01' and v.tipo_documento_modificar <> '01'
                        and v.venta_tipo_envio <> 1";
                $select = "";
                $where = true;
                $tipo_venta = $_POST['tipo_venta'];

                if($_POST['fecha_inicio'] != "" ){
                    $where = true;
                    $select = $select . " and DATE(v.venta_fecha) = '" . $_POST['fecha_inicio'] ."'";
                    $fecha_ini = $_POST['fecha_inicio'];
                    //$fecha_fin = $_POST['fecha_final'];
                }

                if($where){
                    $datos = true;
                    $order = " order by v.venta_fecha asc";
                    $query = $query . $select . $order;
                    $ventas = $this->ventas->listar_ventas($query);
                }

                /*if($_POST['tipo_venta']!="" && $_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_todo($_POST['tipo_venta'],$_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }elseif($_POST['tipo_venta']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_tipo($_POST['tipo_venta'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                }elseif($_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_estado($_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }else{
                    $ventas = $this->ventas->listar_ventas_filtro_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);
                }*/
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/envio_resumen_diario.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script>window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_resumen_diario(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_registro'])){

                $resumen = $this->ventas->listar_resumen_diario_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);

                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_resumen_diario.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script>window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_bajas_facturas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_registro'])){

                $bajas = $this->ventas->listar_comunicacion_baja_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);

                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_bajas_facturas.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function generar_nota(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $venta = $this->ventas->listar_venta($id);
            $detalle_venta = $this->ventas->listar_detalle_ventas($id);
            $tipo_pago = $this->ventas->listar_tipo_pago();
            $productos = $this->inventario->listar_productos();
            //LISTAMOS LOS CLIENTES
            $clientes = $this->ventas->listar_clientes();
            $tipos_documento = $this->clientes->listar_documentos();

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/generar_nota.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function ver_detalle_resumen(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $resumen = $this->ventas->listar_resumen_diario_x_id($id);
            $detalle_resumen = $this->ventas->listar_resumen_diario_detalle_x_id($id);


            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/ver_detalle_resumen.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function ver_venta(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){throw new Exception('ID Sin Declarar');}
            $sale = $this->ventas->listar_venta($id);
            $productssale = $this->ventas->listar_detalle_ventas($id);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/ver_venta.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script>window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    //FUNCIONES
    public function addproduct(){
        try{
            if(isset($_POST['codigo']) && isset($_POST['producto']) && isset($_POST['unids']) && isset($_POST['precio']) && isset($_POST['cantidad']) && isset($_POST['tipo_igv'])){
                $repeat = false;
                foreach($_SESSION['productos'] as $p){
                    if($_POST['codigo'] == $p[0]){
                        $repeat = true;
                    }
                }
                if(!$repeat){
                    array_push($_SESSION['productos'], [$_POST['codigo'], $_POST['producto'], $_POST['unids'], round($_POST['precio'], 2), $_POST['cantidad'], $_POST['tipo_igv'], $_POST['product_descuento']]);
                    $result = 1;
                } else {
                    $result = 3;
                }
            } else {
                $result = 2;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo $result;
    }
    public function editar_cantidad_tabla(){
        try{
            if(isset($_POST['id'])){
                $buscar = $_POST['id'];
                $valor_nueva_cantidad = $_POST['valor_nueva_cantidad'];
                $editar = count($_SESSION['productos']);
                for($i=0; $i < $editar; $i++){
                    if($_SESSION['productos'][$i][0] == $buscar){
                        $_SESSION['productos'][$i][4] = $valor_nueva_cantidad;
                    }
                }
                $_SESSION['productos'] = array_values($_SESSION['productos']);
                $result = 1;
            }

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo json_encode($result);
    }
    public function eliminar_producto(){
        try{
            if(isset($_POST['codigo'])){
                $buscar = $_POST['codigo'];
                $totalar = count($_SESSION['productos']);
                for($i=0; $i < $totalar; $i++){
                    if($_SESSION['productos'][$i][0] == $buscar){
                        unset($_SESSION['productos'][$i]);
                    }
                }
                $_SESSION['productos'] = array_values($_SESSION['productos']);
                $result = 1;
            } else {
                $result = 2;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo $result;
    }
    public function guardar_venta_nota(){
        $result = 0;
        $message = 'OK';
        try{
            $id_venta = $_POST["id_venta"];
            $data_venta = $this->ventas->listar_venta($id_venta);
            $data_venta_detalle = $this->ventas->listar_detalle_ventas($id_venta);
            $id_cliente = $data_venta->id_cliente;
            $mostrar_tp = $data_venta->venta_mostrar_tp;
            $id_caja_cierre = $data_venta->id_caja_cierre;
            $comp_obs = "";
            $agrupar = 0;
            $agrupar_des = "";
            $idventa = 0;

            $model = new Ventas();
            $model->venta_tipo_envio = "0";
            $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
            $model->id_turno = $data_venta->id_turno;
            $venta_tipo =  $_POST['venta_tipo'];
            $model->venta_tipo =  $venta_tipo;
            $model->id_cliente = $id_cliente;
            $model->id_tipo_pago =  $data_venta->id_tipo_pago;
            //obtener serie con el id
            $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
            $model->venta_serie = $serie_->serie;
            $new_correlativo=$serie_->correlativo + 1;
            $model->venta_correlativo = $new_correlativo;
            $model->venta_tipo_moneda = $_POST['moneda'];
            $producto_venta_correlativo = 1;
            $model->producto_venta_correlativo = $producto_venta_correlativo;

            $model->producto_venta_totalgratuita = 0;
            $model->producto_venta_totalinafecta = 0;
            $model->producto_venta_totalgravada = 0;
            $model->producto_venta_totaligv = 0;
            $model->producto_venta_icbper = 0;
            $total=$data_venta->venta_total;
            $model->producto_venta_total = $total;
            $model->producto_venta_totalexonerada = $total;
            $model->producto_venta_pago = $total;
            $model->producto_venta_vuelto = 0;
            $model->producto_venta_des_global = 0;
            $model->producto_venta_des_total = 0;
            $producto_venta_fecha = date("Y-m-d H:i:s");
            $model->producto_venta_fecha = $producto_venta_fecha;

            $model->tipo_documento_modificar = $_POST['Tipo_documento_modificar'];
            $model->serie_modificar = $_POST['serie_modificar'];
            $model->numero_modificar = $_POST['numero_modificar'];
            $model->notatipo_descripcion = $_POST['notatipo_descripcion'];
            $model->venta_observaciones = $comp_obs;
            $microtime = microtime(true);
            $model->venta_microtime=$microtime;
            $model->id_caja_cierre=$id_caja_cierre;
            $model->venta_mostrar_tp=$mostrar_tp;
            $model->tipo_cambio = 0.00;
            $model->venta_consumo_valido=0.00;
            $return = $this->ventas->guardar_venta($model);
            if($return == 1){
                $jalar_id = $this->ventas->jalar_id_venta_microtime($microtime);
                $idventa = $jalar_id->id_venta;
                //$this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['tipo_pago'],$idventa,1,$total,"Venta con id: ".$idventa,"");
                //$this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['tipo_pago'],$total);

                //INICIO - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                if($_POST['tipo_pago'] == 5){
                    //GUARDAR EN BASE DE DATOS
                    $tipo_pago = $_POST['id_tipo_pago'];
                    $contenido_cuota = $_POST['contenido_cuota'];
                    $conteo = 1;
                    if(count_chars($contenido_cuota)>0){
                        $filas=explode('/./.',$contenido_cuota);
                        if(count($filas)>0){
                            for ($i=0;$i<count($filas)-1;$i++){
                                $modelDSI = new Ventas();
                                $celdas=explode('-.-.',$filas[$i]);
                                $modelDSI->id_ventas=$idventa;
                                $modelDSI->id_tipo_pago=$tipo_pago;
                                $modelDSI->conteo=$conteo;
                                $modelDSI->venta_cuota_numero=$celdas[0];
                                $modelDSI->venta_cuota_fecha=$celdas[1];
                                $this->ventas->guardar_cuota_venta($modelDSI);
                                $conteo++;
                            }
                        }
                    }
                    //FIN - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                }

            }
            if($idventa != 0) { //despues de registrar la venta se sigue a registrar el detalle

                $fecha_bolsa = date("Y");
                if ($fecha_bolsa == "2021"){
                    $impuesto_icbper = 0.30;
                } else if ($fecha_bolsa == "2022") {
                    $impuesto_icbper = 0.40;
                } else{
                    $impuesto_icbper = 0.50;
                }
                $igv_porcentaje = 0.18;
                $ICBPER = 0;
                $datos_detalle_pedido = $_POST['contenido'];
                if(count_chars($datos_detalle_pedido)>0){
                    $fila = explode('/./.', $datos_detalle_pedido);
                    if(count($fila)>0){
                        for ($i=0;$i<count($fila)-1;$i++){
                            $celdas=explode('-.-.',$fila[$i]);
                            if($return == 1){
                                $cantidad = $celdas[4];
                                $precio_unitario = $celdas[2];
                                $descuento_item = 0;
                                $factor_porcentaje = 1;
                                $porcentaje = 0;
                                $igv_detalle = 0;
                                $subtotal = $precio_unitario * $cantidad;
                                $id_producto_precio = $celdas[0];
                                $model->id_venta = $idventa;
                                $model->id_producto_precio = $id_producto_precio;
                                $model->venta_detalle_valor_unitario = $precio_unitario;
                                $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                                $model->venta_detalle_nombre_producto = $celdas[3];
                                $model->venta_detalle_cantidad = $cantidad;
                                $model->venta_detalle_total_igv = $igv_detalle;
                                $model->venta_detalle_porcentaje_igv = $porcentaje;
                                $model->venta_detalle_valor_total = $subtotal;
                                $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                                $model->venta_detalle_descuento = $descuento_item;
                                $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                                if($guardar_detalle == 1){
                                    $return = 1;
                                }else{
                                    $return = 2;
                                }
                            }
                        }
                    }
                    if($return == 2){
                        //INICIO - ELIMINAR REGISTROS
                        $this->ventas->eliminar_venta_detalle_x_id_venta($idventa);
                        $this->ventas->eliminar_venta_x_id_venta($idventa);
                        //FIN - ELIMINAR REGISTROS
                    }

                }else{
                    $return = 4;
                    //INICIO - ELIMINAR REGISTROS
                    $this->ventas->eliminar_venta_detalle_x_id_venta($idventa);
                    $this->ventas->eliminar_venta_x_id_venta($idventa);
                    //FIN - ELIMINAR REGISTROS
                }
                if($return == 1){
                    //UNULAR FACTURA RELACIONADA - INICIO
                    $serie_afectada = $jalar_id->serie_modificar;
                    $correlativo_afectada = $jalar_id->correlativo_modificar;
                    //editar a anulado la factura
                    if($venta_tipo == "07"){
                        if($_POST['notatipo_descripcion'] == "01" || $_POST['notatipo_descripcion'] == "02"){
                            $this->ventas->editar_factura_a_anulado($serie_afectada,$correlativo_afectada);
                        }
                    }
                    //UNULAR FACTURA RELACIONADA - FIN

                    $return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$new_correlativo);
                    //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                    include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                    $venta = $this->ventas->listar_venta($idventa);
                    $detalle_venta =$this->ventas->listar_detalle_ventas($idventa);
                    $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                    $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
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
                    }elseif($venta->venta_tipo == "07"){
                        $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                        $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                    }else{
                        $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                        $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);
                    }
                    if($cliente->id_tipodocumento == "4"){
                        $cliente_nombre = $cliente->cliente_razonsocial;
                    }else{
                        $cliente_nombre = $cliente->cliente_nombre;
                    }
                    $ruta_guardado="";
                    if($return == 1){
                        //PDF
                        $dato_venta = $this->ventas->listar_venta_x_id_pdf($idventa);
                        $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($idventa);
                        $fecha_hoy = $dato_venta->venta_fecha;
                        $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
                        $dnni="DNI";
                        if ($dato_venta->venta_tipo == "03") {
                            $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            if($dato_venta->cliente_numero == "11111111"){
                                $documento = "SIN DOCUMENTO";
                            }else{
                                $documento = "$dato_venta->cliente_numero";
                            }
                        } else if ($dato_venta->venta_tipo == "01") {
                            $dnni="RUC";
                            $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        } else if ($dato_venta->venta_tipo == "07") {
                            if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                            $tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        } else {
                            if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                            $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        }
                        $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
                        $arrayImporte = explode(".", $dato_venta->venta_total);
                        $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
                        //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
                        $dato_impresion = 'DATOS DE IMPRESIÓN:';
                        require_once _VIEW_PATH_.'ventas/imprimir_pdf2.php';
                        //Fin PDF
                    }
                    $return = 1;
                }
            }else {
                $return = 2;
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $return,"ruta"=>$ruta_guardado, "message" => $message, "idventa"=>$idventa)));
    }
    public function guardar_venta(){
        $result = 0;
        $message = 'OK';
        try{
            $id_cliente = $_POST["id_cliente"];
            $mostrar_tp = $_POST["mostrar_tp"];
            //$caja_cierre = $this->pedido->jalar_id_caja_cierre($jalar_id_caja->id_caja,$id_usuario);
            $id_caja_cierre = $_POST["caja"];
            $comp_obs = $_POST["comp_obs"]??"";
            $agrupar = $_POST["agrupar"];
            //$agrupar_des = $_POST["agrupar_des"] .' + Piscina';
            $agrupar_des = $_POST["agrupar_des"];
            $cadena = explode("--/",$_POST['cadenita']);
            $cadenita_texto=explode("-**-",$_POST['cadenita_textos']);
            $model = new Ventas();
            $model->venta_tipo_envio = "0";
            $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
            $model->id_turno = $_POST["id_turno"];
            $venta_tipo =  $_POST['venta_tipo'];
            $model->venta_tipo =  $venta_tipo;
            if($venta_tipo=="01"){
                $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['ruc']);
                if(isset($existe->id_cliente)){
                    $id_cliente=$existe->id_cliente;
                    $editar_cliente = $this->clientes->edicion_rapida($_POST['razon_social'],$_POST['domicilio'],$id_cliente);
                }else{
                    $model2 = new Hotel();
                    $model2->id_tipodocumento = 4;
                    $texto = $_POST['razon_social'];
                    $textoDecodificado = urldecode($texto);
                    $model2->cliente_razonsocial = $textoDecodificado;
                    $model2->cliente_nombre = $textoDecodificado;
                    $model2->cliente_numero = $_POST['ruc'];
                    $model2->cliente_correo = "";
                    $model2->cliente_direccion =$_POST['domicilio'];
                    $model2->cliente_telefono = "";
                    $this->clientes->guardar($model2);
                    $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['ruc']);
                    $id_cliente=$existe->id_cliente;
                }
            }
            $model->id_cliente = $id_cliente;
            $model->id_tipo_pago =  $_POST['tipo_pago'];
            //obtener serie con el id
            $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
            $model->venta_serie = $serie_->serie;
            $new_correlativo = $serie_->correlativo + 1;
            $model->venta_correlativo = $new_correlativo;
            $model->venta_tipo_moneda = $_POST['moneda'];
            $producto_venta_correlativo = 1;
            $model->producto_venta_correlativo = $producto_venta_correlativo;
            $model->producto_venta_totalgratuita = 0;
            $model->producto_venta_totalinafecta = 0;
            $model->producto_venta_totalgravada = 0;
            $model->producto_venta_totaligv = 0;
            $model->producto_venta_icbper = 0;
            $id_rack = $_POST['id_rack'];

//            foreach ($cadena as $c){
//                if($c!="") {
//                    $d = $this->hotel->listar_detalle_rack_id($c);
//                    $total+=$d->rack_detalle_subtotal;
//                }
//            }
            //NUEVA VARIABLE CON LOS DATOS DEL DETALLE
            $variable_detalle = json_decode($_POST['variable']);
            $total=0;
            foreach ($variable_detalle as $vd){
                $total+=$vd->subtotal;
            }
            //FIN DE AL VARIABLE QUE TRAE LOS DATOS
            $nuevo_total=$total;
            $model->producto_venta_total = $nuevo_total;
            $model->producto_venta_totalexonerada = $nuevo_total;
            $model->producto_venta_pago = $nuevo_total;

            $model->producto_venta_vuelto = 0;
            $model->producto_venta_des_global = 0;
            $model->producto_venta_des_total = 0;
            $producto_venta_fecha = date("Y-m-d H:i:s");
            $model->producto_venta_fecha = $producto_venta_fecha;

            $model->tipo_documento_modificar = "";
            $model->serie_modificar = "";
            $model->numero_modificar = "";
            $model->notatipo_descripcion = "";
            $model->venta_observaciones = $comp_obs;
            $microtime = microtime(true);
            $model->venta_microtime=$microtime;
            $model->id_caja_cierre=$id_caja_cierre;
            $model->venta_mostrar_tp=$mostrar_tp;
            $model->venta_piscina=2;
            $model->venta_consumo_valido=0.00;
            $model->cambiar_concepto = 2;
            $model->concepto_nuevo = "";
            if(empty($_POST['tipo_cambio'])){
                $model->tipo_cambio = 0.00;
            }else{
                $model->tipo_cambio = $_POST['tipo_cambio'];
            }
			$model->codigo_venta_3 = $_POST['codigo_venta'];
            $guardar = $this->ventas->guardar_venta($model);
            if($guardar == 1){
                $jalar_id = $this->ventas->jalar_id_venta_microtime($microtime);
                $idventa = $jalar_id->id_venta;
                $partir_pago = $_POST["partir_pago"];
                if($partir_pago==1){
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['partir_pago_id'],$idventa,1,$_POST["partir_pago_monto"],"Venta con id: ".$idventa,"",$_POST["codigo"]);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['partir_pago_id'],$_POST["partir_pago_monto"]);
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['partir_pago_id2'],$idventa,1,$_POST["partir_pago_monto2"],"Venta con id: ".$idventa,"",$_POST["codigo2"]);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['partir_pago_id2'],$_POST["partir_pago_monto2"]);

                    $this->hotel->guardar_detalle_pago($idventa,$_POST['partir_pago_id'],$_POST["partir_pago_monto"],0);
                    $this->hotel->guardar_detalle_pago($idventa,$_POST['partir_pago_id2'],$_POST["partir_pago_monto2"],0);
                }else{
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['tipo_pago'],$idventa,1,$nuevo_total,"Venta con id: ".$idventa,"",$_POST["codigo"]);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['tipo_pago'],$nuevo_total);
                    $this->hotel->guardar_detalle_pago($idventa,$_POST['tipo_pago'],$nuevo_total,0);
                }

                //INICIO - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                if($_POST['tipo_pago'] == 5){
                    //GUARDAR EN BASE DE DATOS
                    $tipo_pago = $_POST['tipo_pago'];
                    $contenido_cuota = $_POST['contenido_cuota'];
                    $conteo = 1;
                    if(count_chars($contenido_cuota)>0){
                        $filas=explode('/./.',$contenido_cuota);
                        if(count($filas)>0){
                            for ($i=0;$i<count($filas)-1;$i++){
                                $modelDSI = new Ventas();
                                $celdas=explode('-.-.',$filas[$i]);
                                $modelDSI->id_ventas=$idventa;
                                $modelDSI->id_tipo_pago=$tipo_pago;
                                $modelDSI->conteo=$conteo;
                                $modelDSI->venta_cuota_numero=$celdas[0];
                                $modelDSI->venta_cuota_fecha=$celdas[1];
                                $this->ventas->guardar_cuota_venta($modelDSI);
                                $conteo++;
                            }
                        }
                    }
                    //FIN - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                }
            }
            if($idventa != 0) { //despues de registrar la venta se sigue a registrar el detalle
                $fecha_bolsa = date("Y");
                if ($fecha_bolsa == "2021"){
                    $impuesto_icbper = 0.30;
                } else if ($fecha_bolsa == "2022") {
                    $impuesto_icbper = 0.40;
                } else{
                    $impuesto_icbper = 0.50;
                }
                $igv_porcentaje = 0.18;
                $ICBPER = 0;
                if($agrupar==1){
                    foreach ($variable_detalle as $ca){
                        if($ca!="") {
                            //$p=$this->hotel->listar_detalle_rack_id($ca);
                            $this->hotel->actualizar_idventa_detalle_rack($idventa, $ca->id_rack_detalle);
                        }
                    }
                    $cantidad = 1;
                    $precio_unitario = $nuevo_total;
                    $descuento_item = 0;
                    $factor_porcentaje = 1;
                    $porcentaje = 0;
                    $igv_detalle = 0;
                    $subtotal = $precio_unitario * $cantidad;
                    $id_producto_precio = 213;

                    //AQUI SE HARA LA OPERACION DEL TIPO DE CAMBIO
                    $tipo_cambio = $_POST['tipo_cambio'];
                    /*if(empty($_POST['tipo_cambio'])){
                        $nuevo_precio_unit = $nuevo_total;
                        $nuevo_subtotal = $subtotal;
                    }else{
                        //ANTES SE DEBE DETECTAR SI EL TIPO DE CAMBIO ES SOLES O DOLARES =(
                        if($_POST['moneda']==1){
                            //AQUI SERIA PARA MULTIPLICAR LOS TOTALES POR EL TIPO DE CAMBIO
                            $nuevo_subtotal = $subtotal * $tipo_cambio;
                            $nuevo_precio_unit = $nuevo_subtotal / $cantidad;
                        }else{
                            $nuevo_subtotal = $subtotal / $tipo_cambio;
                            $nuevo_precio_unit = $nuevo_subtotal / $cantidad;
                        }
                    }*/
                    //FIN DE LA OPERACIOM
                    $model->id_venta = $idventa;
                    $model->id_producto_precio = $id_producto_precio;
                    $model->venta_detalle_valor_unitario = $precio_unitario;
                    $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                    $model->venta_detalle_nombre_producto = $agrupar_des;
                    $model->venta_detalle_cantidad = $cantidad;
                    $model->venta_detalle_total_igv = $igv_detalle;
                    $model->venta_detalle_porcentaje_igv = $porcentaje;
                    $model->venta_detalle_valor_total = $subtotal;
                    $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                    $model->venta_detalle_descuento = $descuento_item;
                    $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                    $return = 1;
                }else{
                    $ele = 0;
                    foreach ($variable_detalle as $ca){
                        if($ca!="") {
                            //$p=$this->hotel->listar_detalle_rack_id($ca);
                            $this->hotel->actualizar_idventa_detalle_rack($idventa, $ca->id_rack_detalle);
                            $cantidad = $ca->cantidad;
                            $precio_unitario = $ca->precio_final;
                            $descuento_item = 0;
                            $factor_porcentaje = 1;
                            $porcentaje = 0;
                            $igv_detalle = 0;
                            $subtotal = $precio_unitario * $cantidad;
                            //AQUI SE HARA LA OPERACION DEL TIPO DE CAMBIO
                            $tipo_cambio = $_POST['tipo_cambio'];
                            /*if(empty($_POST['tipo_cambio'])){
                                $nuevo_precio_unit = $precio_unitario;
                                $nuevo_subtotal = $subtotal;
                            }else{
                                if($_POST['moneda']==1){
                                    //AQUI SERIA PARA MULTIPLICAR LOS TOTALES POR EL TIPO DE CAMBIO
                                    $nuevo_subtotal = $subtotal * $tipo_cambio;
                                    $nuevo_precio_unit = $nuevo_subtotal / $cantidad;
                                }else{
                                    $nuevo_subtotal = $subtotal / $tipo_cambio;
                                    $nuevo_precio_unit = $nuevo_subtotal / $cantidad;
                                }
                            }*/
                            if($_POST['venta_piscina']==1){
                                //$nuevo_valor=$cadenita_texto[$ele].' + Piscina';
                                $nuevo_valor=$ca->producto.' + Piscina';
                            }else{
                                //$nuevo_valor=$cadenita_texto[$ele];
                                $nuevo_valor=$ca->producto;
                            }
                            $id_producto_precio = $ca->id_producto_precio;
                            $model->id_venta = $idventa;
                            $model->id_producto_precio = $id_producto_precio;
                            $model->venta_detalle_valor_unitario = $precio_unitario;
                            $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                            $model->venta_detalle_nombre_producto = $nuevo_valor;
                            $model->venta_detalle_cantidad = $cantidad;
                            $model->venta_detalle_total_igv = $igv_detalle;
                            $model->venta_detalle_porcentaje_igv = $porcentaje;
                            $model->venta_detalle_valor_total = $subtotal;
                            $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                            $model->venta_detalle_descuento = $descuento_item;
                            $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                            $return = 1;
                            $ele++;
                        }
                    }
                }
                if($return == 1){
                    $return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$new_correlativo);
                    //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                    include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                    $venta = $this->ventas->listar_venta($idventa);
                    $detalle_venta =$this->ventas->listar_detalle_ventas($idventa);
                    $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                    $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
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
                    }elseif($venta->venta_tipo == "07"){
                        $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                        $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                    }else{
                        $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                        $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);
                    }
                    if($cliente->id_tipodocumento == "4"){
                        $cliente_nombre = $cliente->cliente_razonsocial;
                    }else{
                        $cliente_nombre = $cliente->cliente_nombre;
                    }
                    $ruta_guardado="";
                    if($return == 1){
                        //PDF
                        $dato_venta = $this->ventas->listar_venta_x_id_pdf($idventa);
                        $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($idventa);
                        $fecha_hoy = $dato_venta->venta_fecha;
                        $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
                        $dnni="DNI";
                        if ($dato_venta->venta_tipo == "03") {
                            $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            if($dato_venta->cliente_numero == "11111111"){
                                $documento = "SIN DOCUMENTO";
                            }else{
                                $documento = "$dato_venta->cliente_numero";
                            }
                        } else if ($dato_venta->venta_tipo == "01") {
                            $dnni="RUC";
                            $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        } else if ($dato_venta->venta_tipo == "07") {
                            if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                            $tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        } else {
                            if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                            $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        }
                        $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
                        $arrayImporte = explode(".", $dato_venta->venta_total);
                        $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
                        //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
                        $dato_impresion = 'DATOS DE IMPRESIÓN:';
                        require_once _VIEW_PATH_.'ventas/imprimir_pdf2.php';
                        //Fin PDF
                    }
                    $return = 1;
                }
            }else {
                $return = 2;
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $return,"ruta"=>$ruta_guardado, "message" => $message, "idventa"=>$idventa)));
    }
    //FUNCION NUEVA PARA COBRAR DESDE EL MODULO DE CUENTAS POR COBRAR QUE UNE A HOTEL Y RESTAURANTE
    public function guardar_venta_cargo(){
        $result = 0;
        $message = 'OK';
        try{
            $id_cliente = $_POST["id_cliente"];
            $mostrar_tp = $_POST["mostrar_tp"];
            $id_caja_cierre = $_POST["caja"];
            $comp_obs = $_POST["comp_obs"]??"";
            $agrupar = $_POST["agrupar"];
            $agrupar_des = $_POST["agrupar_des"];
            $cadena = explode("--/",$_POST['cadenita']);
            $cadenita_texto=explode("-**-",$_POST['cadenita_textos']);
            $model = new Ventas();
            $model->venta_tipo_envio = "0";
            $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
            $model->id_turno = $_POST["id_turno"];
            $venta_tipo =  $_POST['venta_tipo'];
            $model->venta_tipo =  $venta_tipo;
            if($venta_tipo=="01"){
                $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['ruc']);
                if(isset($existe->id_cliente)){
                    $id_cliente=$existe->id_cliente;
                    $editar_cliente = $this->clientes->edicion_rapida($_POST['razon_social'],$_POST['domicilio'],$id_cliente);
                }else{
                    $model2 = new Hotel();
                    $texto = $_POST['razon_social'];
                    $textoDecodificado = urldecode($texto);
                    $model2->id_tipodocumento = 4;
                    $model2->cliente_razonsocial = $textoDecodificado;
                    $model2->cliente_nombre = $textoDecodificado;
                    $model2->cliente_numero = $_POST['ruc'];
                    $model2->cliente_correo = "";
                    $model2->cliente_direccion =$_POST['domicilio'];
                    $model2->cliente_telefono = "";
                    $this->clientes->guardar($model2);
                    $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['ruc']);
                    $id_cliente=$existe->id_cliente;
                }
            }
            $model->id_cliente = $id_cliente;
            $model->id_tipo_pago =  $_POST['tipo_pago'];
            //obtener serie con el id
            $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
            $model->venta_serie = $serie_->serie;
            $new_correlativo = $serie_->correlativo + 1;
            $model->venta_correlativo = $new_correlativo;
            $model->venta_tipo_moneda = $_POST['moneda'];
            $producto_venta_correlativo = 1;
            $model->producto_venta_correlativo = $producto_venta_correlativo;
            $model->producto_venta_totalgratuita = 0;
            $model->producto_venta_totalinafecta = 0;
            $model->producto_venta_totalgravada = 0;
            $model->producto_venta_totaligv = 0;
            $model->producto_venta_icbper = 0;
            $id_rack = $_POST['id_rack'];
            //total_cargo = $_POST['total'];
            $total=0;
//            foreach ($cadena as $c){
//                if($c!="") {
//                    $d = $this->hotel->listar_detalle_rack_id($c);
//                    $total+=$d->rack_detalle_subtotal;
//                }
//            }

            //NUEVA VARIABLE CON LOS DATOS DEL DETALLE
            $variable_detalle = json_decode($_POST['variable']);
            $total=0;
            foreach ($variable_detalle as $vd){
                $total+=$vd->subtotal;
            }
            //FIN DE AL VARIABLE QUE TRAE LOS DATOS

            //ACA SERIA LA TRANSFORMACION DE LA VARIABLE DEL TOTAL SI VIENE EN UN TIPO DE MONEDA DIFERENTE
            $tipo_moneda_a="";
            $tipo_moneda_n=$_POST['moneda'];

            //
            $nuevo_total=$total;
            $model->producto_venta_total = $total;
            $model->producto_venta_totalexonerada = $total;
            $model->producto_venta_pago = $total;
            $model->producto_venta_vuelto = 0;
            $model->producto_venta_des_global = 0;
            $model->producto_venta_des_total = 0;
            $producto_venta_fecha = date("Y-m-d H:i:s");
            $model->producto_venta_fecha = $producto_venta_fecha;

            $model->tipo_documento_modificar = "";
            $model->serie_modificar = "";
            $model->numero_modificar = "";
            $model->notatipo_descripcion = "";
            $model->venta_observaciones = $comp_obs;
            $microtime = microtime(true);
            $model->venta_microtime=$microtime;
            $model->id_caja_cierre=$id_caja_cierre;
            $model->venta_mostrar_tp=$mostrar_tp;
            $model->venta_piscina=2;
            $model->venta_consumo_valido=0.00;
            $model->cambiar_concepto = 2;
            if(empty($_POST['tipo_cambio'])){
                $model->tipo_cambio = 0.00;
            }else{
                $model->tipo_cambio = $_POST['tipo_cambio'];
            }
            $model->concepto_nuevo = "";
            $model->id_cuenta = $_POST['id_cuenta'];
            $model->codigo_venta_2 = $_POST['codigo_venta_2'];
            $guardar = $this->ventas->guardar_venta_cargo($model);
            if($guardar == 1){
                $jalar_id = $this->ventas->jalar_id_venta_microtime($microtime);
                $idventa = $jalar_id->id_venta;
                $partir_pago = $_POST["partir_pago"];
                if($partir_pago==1){
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['partir_pago_id'],$idventa,1,$_POST["partir_pago_monto"],"Venta con id: ".$idventa,"",$_POST['codigo']);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['partir_pago_id'],$_POST["partir_pago_monto"]);
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['partir_pago_id2'],$idventa,1,$_POST["partir_pago_monto2"],"Venta con id: ".$idventa,"",$_POST['codigo2']);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['partir_pago_id2'],$_POST["partir_pago_monto2"]);

                    $this->hotel->guardar_detalle_pago($idventa,$_POST['partir_pago_id'],$_POST["partir_pago_monto"],0);
                    $this->hotel->guardar_detalle_pago($idventa,$_POST['partir_pago_id2'],$_POST["partir_pago_monto2"],0);
                }else{
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['tipo_pago'],$idventa,1,$total,"Venta con id: ".$idventa,"",$_POST['codigo']);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['tipo_pago'],$total);
                    $this->hotel->guardar_detalle_pago($idventa,$_POST['tipo_pago'],$total,0);
                }

                //INICIO - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                if($_POST['tipo_pago'] == 5){
                    //GUARDAR EN BASE DE DATOS
                    $tipo_pago = $_POST['tipo_pago'];
                    $contenido_cuota = $_POST['contenido_cuota'];
                    $conteo = 1;
                    if(count_chars($contenido_cuota)>0){
                        $filas=explode('/./.',$contenido_cuota);
                        if(count($filas)>0){
                            for ($i=0;$i<count($filas)-1;$i++){
                                $modelDSI = new Ventas();
                                $celdas=explode('-.-.',$filas[$i]);
                                $modelDSI->id_ventas=$idventa;
                                $modelDSI->id_tipo_pago=$tipo_pago;
                                $modelDSI->conteo=$conteo;
                                $modelDSI->venta_cuota_numero=$celdas[0];
                                $modelDSI->venta_cuota_fecha=$celdas[1];
                                $this->ventas->guardar_cuota_venta($modelDSI);
                                $conteo++;
                            }
                        }
                    }
                    //FIN - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                }

            }
                if($idventa != 0) { //despues de registrar la venta se sigue a registrar el detalle
                    $fecha_bolsa = date("Y");
                    if ($fecha_bolsa == "2021"){
                        $impuesto_icbper = 0.30;
                    } else if ($fecha_bolsa == "2022") {
                        $impuesto_icbper = 0.40;
                    } else{
                        $impuesto_icbper = 0.50;
                    }
                    $igv_porcentaje = 0.18;
                    $ICBPER = 0;
                    if($agrupar==1){
                    foreach ($variable_detalle as $ca){
                        if($ca!="") {
                            //$p=$this->hotel->listar_detalle_rack_id($ca);
                            if(empty($ca->id_rack_detalle)){
                                $this->pedido->cambiar_estado_comanda($ca->id_comanda_detalle);
                                $this->pedido->cambiar_estado_detalle_cuenta_pago($ca->id_comanda_detalle);
                            }else{
                                $this->hotel->actualizar_idventa_detalle_rack($idventa, $ca->id_rack_detalle);
                            }
                        }
                    }
                    $cantidad = 1;
                    $precio_unitario = $total;
                    $descuento_item = 0;
                    $factor_porcentaje = 1;
                    $porcentaje = 0;
                    $igv_detalle = 0;
                    $subtotal = $precio_unitario * $cantidad;
                    $id_producto_precio = 213;
                    $model->id_venta = $idventa;
                    $model->id_producto_precio = $id_producto_precio;
                    $model->venta_detalle_valor_unitario = $precio_unitario;
                    $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                    $model->venta_detalle_nombre_producto = $agrupar_des;
                    $model->venta_detalle_cantidad = $cantidad;
                    $model->venta_detalle_total_igv = $igv_detalle;
                    $model->venta_detalle_porcentaje_igv = $porcentaje;
                    $model->venta_detalle_valor_total = $subtotal;
                    $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                    $model->venta_detalle_descuento = $descuento_item;
                    $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                    $return = 1;
                }else{
                    $ele = 0;
                    foreach ($variable_detalle as $ca){
                        if($ca!="") {
                            $sacar_valor = $this->cuentascobrar->sacar_valor($ca->id_cuenta_detalle);
                            if(empty($sacar_valor->id_rack_detalle)){
                                $jalar_datos = $this->pedido->jalar_datos($sacar_valor->id_comanda_detalle);
                                $bc = $this->cuentascobrar->buscar_comanda_detalle($sacar_valor->id_comanda_detalle);
                                //SE DEBE CAMBIAR EL ESTADO DE LAS COMANDAS Y DE LOS DETALLES DE LAS CUENTAS POR COBRAR YA COBRADO PARA QUE FIGURE PAGADO EN EL SISTEMA
                                $this->pedido->cambiar_estado_comanda($sacar_valor->id_comanda_detalle);
                                $this->pedido->cambiar_estado_detalle_cuenta_pago($sacar_valor->id_comanda_detalle);
                                //FIN DEL CAMBIO
//                                $cantidad = $bc->comanda_detalle_cantidad;
//                                $precio_unitario = $bc->comanda_detalle_precio;
                                $cantidad = $ca->cantidad;
                                $precio_unitario = $ca->precio_final;
                                $descuento_item = 0;
                                $factor_porcentaje = 1;
                                $porcentaje = 0;
                                $igv_detalle = 0;
                                /*if($p[5] == 10){
                                    $igv_detalle = $p[3]*$p[4]*$igv_porcentaje;
                                    $factor_porcentaje = 1+ $igv_porcentaje;
                                    $porcentaje = $igv_porcentaje * 100;
                                }*/
                                $subtotal = $precio_unitario * $cantidad;
                                /*if($p[6] > 0){
                                    $subtotal = $subtotal - $descuento_item;
                                }*/
                                if($_POST['venta_piscina']==1){
                                    //$nuevo_valor=$cadenita_texto[$ele].' + Piscina';
                                    $nuevo_valor=$ca->producto.' + Piscina';
                                }else{
                                    //$nuevo_valor=$cadenita_texto[$ele];
                                    $nuevo_valor=$ca->producto;
                                }
                                $id_producto_precio = $jalar_datos->id_producto;
                                $model->id_venta = $idventa;
                                $model->id_comanda_detalle = $sacar_valor->id_comanda_detalle;
                                $model->id_producto_precio = $id_producto_precio;
                                $model->venta_detalle_valor_unitario = $precio_unitario;
                                $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                                $model->venta_detalle_nombre_producto = $nuevo_valor;
                                $model->venta_detalle_cantidad = $cantidad;
                                $model->venta_detalle_total_igv = $igv_detalle;
                                $model->venta_detalle_porcentaje_igv = $porcentaje;
                                $model->venta_detalle_valor_total = $subtotal;
                                $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                                $model->venta_detalle_descuento = $descuento_item;

                                $guardar_detalle = $this->pedido->guardar_venta_detalle($model);
                                $return = 1;
                                $ele++;
                            }else{
                                $p=$this->hotel->listar_detalle_rack_id($sacar_valor->id_rack_detalle);
                                $this->hotel->actualizar_idventa_detalle_rack($idventa, $p->id_rack_detalle);
                                //TAMBIEN ACTUALIZAMOS EL ESTADO DE PAGO DEL RACK DETALLE EN LA TABLA DE LAS CUENTAS DETALLE POR COBRAR
                                $this->hotel->actualizar_estado_pago_cuentadetalle($p->id_rack_detalle);

//                                $cantidad = $p->rack_detalle_cantidad;
//                                $precio_unitario = $p->rack_detalle_preciounit;
                                $cantidad = $ca->cantidad;
                                $precio_unitario = $ca->precio_final;
                                $descuento_item = 0;
                                $factor_porcentaje = 1;
                                $porcentaje = 0;
                                $igv_detalle = 0;
                                /*if($p[5] == 10){
                                    $igv_detalle = $p[3]*$p[4]*$igv_porcentaje;
                                    $factor_porcentaje = 1+ $igv_porcentaje;
                                    $porcentaje = $igv_porcentaje * 100;
                                }*/
                                $subtotal = $precio_unitario * $cantidad;
                                /*if($p[6] > 0){
                                    $subtotal = $subtotal - $descuento_item;
                                }*/
                                if($_POST['venta_piscina']==1){
                                    //$nuevo_valor=$cadenita_texto[$ele].' + Piscina';
                                    $nuevo_valor=$ca->producto.' + Piscina';
                                }else{
                                    //$nuevo_valor=$cadenita_texto[$ele];
                                    $nuevo_valor=$ca->producto;
                                }
                                $id_producto_precio = $p->id_producto;
                                $model->id_venta = $idventa;
                                $model->id_producto_precio = $id_producto_precio;
                                $model->venta_detalle_valor_unitario = $precio_unitario;
                                $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                                $model->venta_detalle_nombre_producto = $nuevo_valor;
                                $model->venta_detalle_cantidad = $cantidad;
                                $model->venta_detalle_total_igv = $igv_detalle;
                                $model->venta_detalle_porcentaje_igv = $porcentaje;
                                $model->venta_detalle_valor_total = $subtotal;
                                $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                                $model->venta_detalle_descuento = $descuento_item;

                                $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                                $return = 1;
                                $ele++;
                            }
                        }
                    }
                }
                if($return == 1){
                    //ACTUALIZAR DATOS EN LAS TABLAS
                    $sumar_valor_pagado = $this->cuentascobrar->sumar_pagado_cuenta($_POST['total'],$_POST['id_cuenta']);
                    //AHORA COMPARAMOS LOS VALORES PARA SABER SI EL ESTADO A CANCELADO CAMBIARAR
                    $buscar_comparacion = $this->cuentascobrar->buscar_comparacion($_POST['id_cuenta']);
                    if($buscar_comparacion->cuentas_total == $buscar_comparacion->cuentas_total_pagado){
                        $update_estado = $this->cuentascobrar->update_estado_cancelado($_POST['id_cuenta']);
                    }else{
                        $update_estado = $this->cuentascobrar->update_estado_pendiente($_POST['id_cuenta']);
                    }
                    //FIN DE ACTUALIZACION


                    $return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$new_correlativo);
                    //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                    include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                    $venta = $this->ventas->listar_venta($idventa);
                    $detalle_venta =$this->ventas->listar_detalle_ventas($idventa);
                    $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                    $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
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
                    }elseif($venta->venta_tipo == "07"){
                        $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                        $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                    }else{
                        $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                        $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);
                    }
                    if($cliente->id_tipodocumento == "4"){
                        $cliente_nombre = $cliente->cliente_razonsocial;
                    }else{
                        $cliente_nombre = $cliente->cliente_nombre;
                    }
                    $ruta_guardado="";
                    if($return == 1){
                        //PDF
                        $dato_venta = $this->ventas->listar_venta_x_id_pdf($idventa);
                        $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($idventa);
                        $fecha_hoy = $dato_venta->venta_fecha;
                        $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
                        $dnni="DNI";
                        if ($dato_venta->venta_tipo == "03") {
                            $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            if($dato_venta->cliente_numero == "11111111"){
                                $documento = "SIN DOCUMENTO";
                            }else{
                                $documento = "$dato_venta->cliente_numero";
                            }
                        } else if ($dato_venta->venta_tipo == "01") {
                            $dnni="RUC";
                            $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        } else if ($dato_venta->venta_tipo == "07") {
                             if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                            $tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        } else {
                             if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
                            $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                            $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                            $documento = "$dato_venta->cliente_numero";
                        }
                        $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
                        $arrayImporte = explode(".", $dato_venta->venta_total);
                        $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
                        //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
                        $dato_impresion = 'DATOS DE IMPRESIÓN:';
                        require_once _VIEW_PATH_.'ventas/imprimir_pdf2.php';
                        //Fin PDF
                    }
                    $return = 1;
                }
            }else {
                $return = 2;
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $return,"ruta"=>$ruta_guardado, "message" => $message, "idventa"=>$idventa)));
    }
    public function guardar_venta_ingreso(){
        $result = 0;
        $message = 'OK';
        try{
            $id_cliente = $_POST["id_cliente"];
            $id_caja_cierre = $_POST["caja"];
            $agrupar = $_POST["agrupar"];
            $agrupar_des = $_POST["agrupar_des"];
            $model = new Ventas();
            $model->venta_tipo_envio = "0";
            $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
            $model->id_turno = $_POST["id_turno"];
            $venta_tipo =  $_POST['venta_tipo'];
            $model->venta_tipo =  $venta_tipo;

            if($venta_tipo=="01"){
                $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['ruc']);
                if(isset($existe->id_cliente)){
                    $id_cliente=$existe->id_cliente;
                }else{
                    $model2 = new Hotel();
                    $texto = $_POST['razon_social'];
                    $textoDecodificado = urldecode($texto);
                    $model2->id_tipodocumento = 4;
                    $model2->cliente_razonsocial = $textoDecodificado;
                    $model2->cliente_nombre = $textoDecodificado;
                    $model2->cliente_numero = $_POST['ruc'];
                    $model2->cliente_correo = "";
                    $model2->cliente_direccion =$_POST['domicilio'];
                    $model2->cliente_telefono = "";
                    $this->clientes->guardar($model2);
                    $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['ruc']);
                    $id_cliente=$existe->id_cliente;
                }
            }else{
                $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['nro_doc_boleta']);
                if(isset($existe->id_cliente)){
                    $id_cliente=$existe->id_cliente;
                }else{
                    $model2 = new Hotel();
                    $model2->id_tipodocumento = 2;
                    $model2->cliente_razonsocial = "";
                    $model2->cliente_nombre = $_POST['nombre_boleta'];
                    $model2->cliente_numero = $_POST['nro_doc_boleta'];
                    $model2->cliente_correo = "";
                    $model2->cliente_direccion ="";
                    $model2->cliente_telefono = "";
                    $this->clientes->guardar($model2);
                    $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['nro_doc_boleta']);
                    $id_cliente=$existe->id_cliente;
                }
            }
            $model->id_cliente = $id_cliente;
            $model->id_tipo_pago =  $_POST['tipo_pago'];
            $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
            $model->venta_serie = $serie_->serie;
            $new_correlativo = $serie_->correlativo + 1;
            $model->venta_correlativo = $new_correlativo;
            $model->venta_tipo_moneda = $_POST['moneda'];
            $producto_venta_correlativo = 1;
            $model->producto_venta_correlativo = $producto_venta_correlativo;
            $model->producto_venta_totalgratuita = 0;
            $model->producto_venta_totalinafecta = 0;
            $model->producto_venta_totalgravada = 0;
            $model->producto_venta_totaligv = 0;
            $model->producto_venta_icbper = 0;
            $detalle_ = explode("---//",$_POST['detalle']);
            $total=0;

            foreach ($detalle_ as $d) {
                $dd = explode("...//",$d);
                $total+=$dd[2] * $dd[3];
            }

            $model->producto_venta_total = $total;
            $model->producto_venta_totalexonerada = $total;
            $model->producto_venta_pago = $total;
            $model->producto_venta_vuelto = 0;
            $model->producto_venta_des_global = 0;
            $model->producto_venta_des_total = 0;
            $producto_venta_fecha = date("Y-m-d H:i:s");
            $model->producto_venta_fecha = $producto_venta_fecha;
            $model->tipo_documento_modificar = "";
            $model->serie_modificar = "";
            $model->numero_modificar = "";
            $model->notatipo_descripcion = "";
            $comp_obs = $_POST["comp_obs"]??"";
            $mostrar_tp = $_POST["mostrar_tp"]??0;
            $model->venta_observaciones = $comp_obs;
            $microtime =microtime(true);
            $model->venta_microtime = $microtime;
            $model->id_caja_cierre=$id_caja_cierre;
            $model->venta_mostrar_tp=$mostrar_tp;
            //AQUI SE HARA LA JUGADA DE LA BARRA LIBRE Y DE LA PISCINA DONDE 4 SERA BARRA LIBRE Y 1 PISCINA
            $model->venta_piscina=$_POST['venta_piscina'];

            if(empty($_POST['venta_consumo_valido'])){
                $model->venta_consumo_valido=0.00;
				$venta_consumo_valid = 0.00;
            }else{
                $model->venta_consumo_valido=$_POST['venta_consumo_valido'];
				$venta_consumo_valid = $_POST['venta_consumo_valido'];
            }

            $model->tipo_cambio=0.00;
			$model->codigo_venta_3 = $_POST['codigo_venta_3'];
//            $guardar = $this->ventas->guardar_venta($model);

			$venta_pis = $_POST['venta_piscina'];
			$guardar = $this->builder->save("ventas",array(
				'id_empresa' => 1,
				'id_usuario' => $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_),
				'id_cliente' => $id_cliente,
				'id_turno' => $_POST["id_turno"],
				'id_tipo_pago' => $_POST['tipo_pago'],
				'id_moneda' => $_POST['moneda'],
				'id_caja_cierre' => $_POST["caja"],
				'venta_condicion_resumen' => 1,
				'venta_tipo_envio' => 0,
				'venta_direccion' => null,
				'venta_tipo' => $_POST['venta_tipo'],
				'venta_serie' => $serie_->serie,
				'venta_correlativo' => $serie_->correlativo + 1,
				'venta_descuento_global' => 0,
				'venta_totalgratuita' => 0,
				'venta_totalexonerada' => $total,
				'venta_totalinafecta' => 0,
				'venta_totalgravada' => 0,
				'venta_totaligv' => 0,
				'venta_incluye_igv' => 1,
				'venta_totaldescuento' => 0,
				'venta_icbper' => 0,
				'venta_total' => $total,
				'venta_pago_cliente' => $total,
				'venta_vuelto' => 0,
				'venta_fecha' => date("Y-m-d H:i:s"),
				'venta_observacion' => null,
				'venta_mostrar_tp' => null,
				'tipo_documento_modificar' => "",
				'serie_modificar' => "",
				'correlativo_modificar' => "",
				'venta_codigo_motivo_nota' => "",
				'venta_observaciones' => $_POST["comp_obs"]??"",
				'venta_estado_sunat' => 0,
				'venta_fecha_envio' => null,
				'venta_rutaXML' => null,
				'venta_rutaCDR' => null,
				'venta_respuesta_sunat' => null,
				'venta_fecha_de_baja' => null,
				'anulado_sunat' => 0,
				'venta_cancelar' => 1,
				'venta_mt' => $microtime,
				'id_caja_numero' => null,
				'id_mesa' => null,
				'venta_piscina' => $venta_pis,
				'venta_piscina_codigo' => null,
				'venta_consumo_valido' => $total,
				'cambiar_concepto' => 1,
				'concepto_nuevo' => null,
				'id_cuenta' => null,
				'tipo_cambio' => 0.00,
				'codigo_venta' => $_POST['codigo_venta_3'],
			));

            if($guardar == 1){
                $jalar_id = $this->ventas->jalar_id_venta_microtime($microtime);
                $idventa = $jalar_id->id_venta;
                $partir_pago = $_POST["partir_pago"];
                if($partir_pago==1){
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['partir_pago_id'],$idventa,1,$_POST["partir_pago_monto"],"Venta con id: ".$idventa,"",$_POST['codigo']);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['partir_pago_id'],$_POST["partir_pago_monto"]);
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['partir_pago_id2'],$idventa,1,$_POST["partir_pago_monto2"],"Venta con id: ".$idventa,"",$_POST['codigo2']);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['partir_pago_id2'],$_POST["partir_pago_monto2"]);

                    $this->hotel->guardar_detalle_pago($idventa,$_POST['partir_pago_id'],$_POST["partir_pago_monto"],0);
                    $this->hotel->guardar_detalle_pago($idventa,$_POST['partir_pago_id2'],$_POST["partir_pago_monto2"],0);
                }else{
                    $this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$_POST['moneda'],$_POST['tipo_pago'],$idventa,1,$total,"Venta con id: ".$idventa,"",$_POST['codigo']);
                    $this->hotel->actualizar_caja_monto($id_caja_cierre,$_POST['moneda'],$_POST['tipo_pago'],$total);
                    $this->hotel->guardar_detalle_pago($idventa,$_POST['tipo_pago'],$total,0);
                }
                //INICIO - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                if($_POST['tipo_pago'] == 5){
                    //GUARDAR EN BASE DE DATOS
                    $tipo_pago = $_POST['tipo_pago'];
                    $contenido_cuota = $_POST['contenido_cuota'];
                    $conteo = 1;
                    if(count_chars($contenido_cuota)>0){
                        $filas=explode('/./.',$contenido_cuota);
                        if(count($filas)>0){
                            for ($i=0;$i<count($filas)-1;$i++){
                                $modelDSI = new Ventas();
                                $celdas=explode('-.-.',$filas[$i]);
                                $modelDSI->id_ventas=$idventa;
                                $modelDSI->id_tipo_pago=$tipo_pago;
                                $modelDSI->conteo=$conteo;
                                $modelDSI->venta_cuota_numero=$celdas[0];
                                $modelDSI->venta_cuota_fecha=$celdas[1];
                                $this->ventas->guardar_cuota_venta($modelDSI);
                                $conteo++;
                            }
                        }
                    }
                    //FIN - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                }
				//GUARDADO DEL DETALLE
				if($idventa != 0) { //despues de registrar la venta se sigue a registrar el detalle
					$fecha_bolsa = date("Y");
					if ($fecha_bolsa == "2020"){
						$impuesto_icbper = 0.20;
					} else if ($fecha_bolsa == "2021"){
						$impuesto_icbper = 0.30;
					} else if ($fecha_bolsa == "2022") {
						$impuesto_icbper = 0.40;
					} else{
						$impuesto_icbper = 0.50;
					}
					$igv_porcentaje = 0.18;
					$ICBPER = 0;

					if($agrupar==1){
						$cantidad = 1;
						$precio_unitario = $total;
						$descuento_item = 0;
						$factor_porcentaje = 1;
						$porcentaje = 0;
						$igv_detalle = 0;
						$subtotal = $precio_unitario * $cantidad;
						$id_producto_precio = 213;
						$model->id_venta = $idventa;
						$model->id_producto_precio = $id_producto_precio;
						$model->venta_detalle_valor_unitario = $precio_unitario;
						$model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
						$model->venta_detalle_nombre_producto = $agrupar_des;
						$model->venta_detalle_cantidad = $cantidad;
						$model->venta_detalle_total_igv = $igv_detalle;
						$model->venta_detalle_porcentaje_igv = $porcentaje;
						$model->venta_detalle_valor_total = $subtotal;
						$model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
						$model->venta_detalle_descuento = $descuento_item;
						$guardar_detalle = $this->ventas->guardar_detalle_venta($model);
						$return = 1;
					}else {
						foreach ($detalle_ as $d) {
							$dd = explode("...//", $d);
							$cantidad = $dd[3];
							$precio_unitario = $dd[2];
							$descuento_item = 0;
							$factor_porcentaje = 1;
							$porcentaje = 0;
							$igv_detalle = 0;
							$subtotal = $precio_unitario * $cantidad;
							$id_producto_precio = $dd[0];
							$model->id_venta = $idventa;
							$model->id_producto_precio = $id_producto_precio;
							$model->venta_detalle_valor_unitario = $precio_unitario;
							$model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
							$model->venta_detalle_nombre_producto = $dd[1];
							$model->venta_detalle_cantidad = $cantidad;
							$model->venta_detalle_total_igv = $igv_detalle;
							$model->venta_detalle_porcentaje_igv = $porcentaje;
							$model->venta_detalle_valor_total = $subtotal;
							$model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
							$model->venta_detalle_descuento = $descuento_item;

							$guardar_detalle = $this->ventas->guardar_detalle_venta($model);
							$return = 1;
						}
					}

					if($return == 1){
						$return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$new_correlativo);
						//INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
						include('libs/ApiFacturacion/phpqrcode/qrlib.php');
						$venta = $this->ventas->listar_venta($idventa);
						$detalle_venta =$this->ventas->listar_detalle_ventas($idventa);
						$empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
						$cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
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
						}elseif($venta->venta_tipo == "07"){
							$venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
							$motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
						}else{
							$venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
							$motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);
						}
						if($cliente->id_tipodocumento == "4"){
							$cliente_nombre = $cliente->cliente_razonsocial;
						}else{
							$cliente_nombre = $cliente->cliente_nombre;
						}
						$ruta_guardado="";
						if($return==1){
							//PDF
							$dato_venta = $this->ventas->listar_venta_x_id_pdf($idventa);
							$detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($idventa);
							$fecha_hoy = $dato_venta->venta_fecha;
							$ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
							$dnni="DNI";
							if ($dato_venta->venta_tipo == "03") {
								$tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
								$serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
								if($dato_venta->cliente_numero == "11111111"){
									$documento = "SIN DOCUMENTO";
								}else{
									$documento = "$dato_venta->cliente_numero";
								}
							} else if ($dato_venta->venta_tipo == "01") {
								$dnni="RUC";
								$tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
								$serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
								$documento = "$dato_venta->cliente_numero";
							} else if ($dato_venta->venta_tipo == "07") {
								if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
								$tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
								$serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
								$documento = "$dato_venta->cliente_numero";
							} else {
								if($cliente->id_tipodocumento == "4"){ $dnni="RUC"; } else{ $dnni="DNI"; }
								$tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
								$serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
								$documento = "$dato_venta->cliente_numero";
							}
							$importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
							$arrayImporte = explode(".", $dato_venta->venta_total);
							$montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
							//$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
							$dato_impresion = 'DATOS DE IMPRESIÓN:';
							require_once _VIEW_PATH_.'ventas/imprimir_pdf2.php';
							//Fin PDF
						}
						$return = 1;
					}
				}else {
					$return = 2;
				}

            }



        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $return, "ruta"=>$ruta_guardado,"message" => $message, "idventa"=>$idventa)));
    }
    public function guardar_egreso(){
        $result = 2;
        try{
            $id_caja_cierre = $_POST["caja_egreso"];
            $moneda_egreso = $_POST["moneda_egreso"];
            $monto_egreso = $_POST["monto_egreso"];
            $descripcion_egreso = $_POST["descripcion_egreso"];
            $obs = $_POST["obs"];
            $result=$this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$moneda_egreso,3,0,0,$monto_egreso,"EGRESO: ".$descripcion_egreso,$obs,"");
            if($result==1){$result=$this->hotel->actualizar_caja_monto($id_caja_cierre,$moneda_egreso,3,$monto_egreso*(-1));}
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function guardar_ingreso(){
        $result = 2;
        try{
            $id_caja_cierre = $_POST["caja_ingreso"];
            $moneda_ingreso = $_POST["moneda_ingreso"];
            $monto_ingreso = $_POST["monto_ingreso"];
            $descripcion_ingreso = $_POST["descripcion_ingreso"];
            $obs = $_POST["obs"];
            $result=$this->hotel->guardar_caja_movimiento($id_caja_cierre,0,$moneda_ingreso,3,0,1,$monto_ingreso,$descripcion_ingreso,$obs,"");
            if($result==1){$result=$this->hotel->actualizar_caja_monto($id_caja_cierre,$moneda_ingreso,3,$monto_ingreso);}
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function generar_pdf(){
        $result = 2;
        try{
            $idventa = $_GET["id"];
            $ruta_guardado="";
            $dato_venta = $this->ventas->listar_venta_x_id_pdf($idventa);
            $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($idventa);
            $fecha_hoy = $dato_venta->venta_fecha;
            $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
            $dnni="DNI";
            if ($dato_venta->venta_tipo == "03") {
                $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                if($dato_venta->cliente_numero == "11111111"){
                    $documento = "SIN DOCUMENTO";
                }else{
                    $documento = "$dato_venta->cliente_numero";
                }
            } else if ($dato_venta->venta_tipo == "01") {
                $dnni="RUC";
                $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            } else if ($dato_venta->venta_tipo == "07") {
                $tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            } else {
                $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            }
            $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
            $arrayImporte = explode(".", $dato_venta->venta_total);
            $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
            //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
            $dato_impresion = 'DATOS DE IMPRESIÓN:';
            require_once _VIEW_PATH_.'ventas/imprimir_pdf3.php';
            //Fin PDF
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function eliminar_detalle(){
        $result = 2;
        try{
            $id_rack = $_POST["id_rack"];
            $eliminar_id = $_POST["eliminar_id"];
            $eliminar_clave = $_POST["eliminar_clave"];
            $eliminar_motivo = $_POST["eliminar_motivo"];
            $pass_validation=false;
            $id_user = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            $clave_tipo=1;
            $id_comanda_detalle=Null;
            $existe_eliminacion = $this->hotel->existe_clave_eliminacion();
            if(!empty($existe_eliminacion)){
                if($eliminar_clave==$existe_eliminacion->clave_eliminacion_clave){
                    $pass_validation=true;
                    $this->hotel->usar_clave_eliminacion($id_user,$eliminar_id,$id_comanda_detalle,$clave_tipo,$existe_eliminacion->id_clave_eliminacion);
                }
            }
            if($pass_validation){
                //DEBEMOS VALIDAR SI SE TRATA DE UN DETALLE DE ALOJAMIENTO PARA RESTAR NOCHES, CALCULAR NUEVO TOTAL
                //SINO ES DETALLE DE ALOJAMIENTO SOLO DEBE ELIMINAR DE RACK_DETALLE
                $bsea =$this->ventas->buscar_datos_alojamiento($eliminar_id);
                $rack =$this->ventas->buscar_datos_rack($bsea->id_rack);
                $fecha=$rack->rack_in;
                if($bsea->rack_detalle_correlativo_comanda==NULL){
                    $result=$this->ventas->eliminar_detalle($eliminar_id,$id_user,$eliminar_motivo);
                    //RESTAR NOCHES (NOTA : DEBEMOS SABER SI O SI SI EL DETALLE ES PINCHE ALOJAMIENTO Y NO ALGUNA OTRA COSA MAS AGREGADA TMRE QUE CAGADA)
                    if($bsea->id_producto=='211'){
                        $update_noches = $this->ventas->restar_noches($bsea->id_rack);
                        if($update_noches==1) {
                            $fecha_out = $rack->rack_out;
                            $nueva_fecha_out = date('Y-m-d', strtotime($fecha_out . "- 1 days"));
                            $update_checkout = $this->ventas->update_check_out($nueva_fecha_out, $bsea->id_rack);
                            //AHORA PASAREMOS A ELIMINAR EL REGISTRO DE LA HABITACION EN EL CUAL PRIMERO DEBEMOS BUSCAR EL REGISTRO CON SUS DETALLES PARA ELIMINAR AL DE ESTADO 1
                            //Y LUEGO MODIFICAR AL ULTIMO REGISTRO QUE QUEDA PARA PASARLO DE 0 A 1
                            $eliminar_registro_valor1=$this->ventas->eliminar_registro_valor1($fecha,$rack->id_habitacion);
                            $result = $this->ventas->buscar_habitacion($rack->id_habitacion,$fecha);
                        }
                    }else{
                        $result=$this->ventas->eliminar_detalle($eliminar_id,$id_user,$eliminar_motivo);
                    }
                }else{
                    $result=$this->ventas->eliminar_detalle($eliminar_id,$id_user,$eliminar_motivo);
                }
            }else{
                $result=3;
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function search_by_barcode(){
        try{
            if(isset($_POST['product_barcode'])){
                $product = $this->ventas->search_by_barcode($_POST['product_barcode']);
                $result = $product;
                if(empty($result)){
                    $result = 2;
                } else {
                    $result = $result->producto_nombre . '|' . $result->id_medida . '|' . $result->producto_stock . '|' . $result->id_producto_precio . '|' . $result->producto_precio_unidad . '|' . $result->producto_precio_valor . '|' . $result->medida_codigo_unidad . '|' . $result->producto_precio_codigoafectacion;
                }
            } else {
                $result = 2;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo $result;
    }
    public function consultar_serie_nota(){
        try{
            $concepto = $_POST['concepto'];
            $series = "";
            $correlativo = "";
            if($concepto == "LISTAR_SERIE"){
                $tipo_documento_modificar = $_POST['tipo_documento_modificar'];
                if($tipo_documento_modificar == "01" && $_POST['tipo_venta'] == "07"){
                    $id_serie = 5;
                }elseif($tipo_documento_modificar == "03" && $_POST['tipo_venta'] == "07"){
                    $id_serie = 6;
                }elseif($tipo_documento_modificar == "01" && $_POST['tipo_venta'] == "08"){
                    $id_serie = 7;
                }elseif($tipo_documento_modificar == "03" && $_POST['tipo_venta'] == "08"){
                    $id_serie = 8;
                }
                $series = $this->ventas->listarSerie_NC_x_id($_POST['tipo_venta'], $id_serie);
                /*if($_POST['tipo_venta'] == "07"){
                    $series = $this->pedido->listarSerie_NC_factura($_POST['tipo_venta']);

                    if($tipo_documento_modificar == "01"){
                        $id =
                        $series = $this->pedido->listarSerie_NC_factura($_POST['tipo_venta']);
                    }else{
                        $series = $this->pedido->listarSerie($_POST['tipo_venta']);
                    }
                }else{

                }*/

                //$series = $this->pedido->listarSerie($_POST['tipo_venta']);
            }else{
                $correlativo_ = $this->ventas->listar_correlativos_x_serie($_POST['id_serie']);
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
    public function consultar_serie(){
        try{
            $concepto = $_POST['concepto'];
            $series = "";
            $correlativo = "";
            if($concepto == "LISTAR_SERIE"){
                $series = $this->ventas->listarSerie($_POST['tipo_venta']);
            }else{
                $correlativo_ = $this->ventas->listar_correlativos_x_serie($_POST['id_serie']);
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
    public function tipo_nota_descripcion(){
        try{
            //$id_producto = $_POST['id_producto'];
            //$result = $this->pedido->listar_precio_producto($id_producto);
            $tipo_comprobante = $_POST['tipo_comprobante'];
            if($tipo_comprobante != ""){
                if($tipo_comprobante == "07"){
                    $dato_nota = $this->ventas->listar_descripcion_segun_nota_credito();
                    $nota = "Tipo Nota de Crédito";
                }else{
                    $dato_nota = $this->ventas->listar_descripcion_segun_nota_debito();
                    $nota = "Tipo Nota de Débito";
                }

                $nota_descripcion = "<label>".$nota."</label>";
                $nota_descripcion .= "<select class='form-control' id='notatipo_descripcion'>";
                foreach ($dato_nota as $dn){
                    $nota_descripcion.= "<option value='".$dn->codigo."'>".$dn->tipo_nota_descripcion."</option>";
                }
                $nota_descripcion .= "</select>";
            }

        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($nota_descripcion);
    }
    public function ticket_electronico(){
        try{

            $id = $_POST['id'];
            //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
            include('libs/ApiFacturacion/phpqrcode/qrlib.php');

            $venta = $this->ventas->listar_venta($id);
            $detalle_venta =$this->ventas->listar_detalle_ventas($id);
            $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
            $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
            //INICIO - CREACION QR
            $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
            $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
            $ruta = 'libs/ApiFacturacion/imagenqr/';
            $ruta_qr = $ruta.$nombre_qr.'.png';
            //QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
            //FIN - CREACION QR
            if($venta->venta_tipo == "03"){
                $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
            }elseif($venta->venta_tipo == "01"){
                $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
            }elseif($venta->venta_tipo == "07"){
                $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
            }else{
                $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);

            }
            if($cliente->id_tipodocumento == "4"){
                $cliente_nombre = $cliente->cliente_razonsocial;
            }else{
                $cliente_nombre = $cliente->cliente_nombre;
            }
            $result = 1;
            require _VIEW_PATH_ . 'ventas/ticket_electronico.php';

        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result)));

    }
    public function excel_ventas_enviadas(){
        try{
            $usuario_nombre = $this->encriptar->desencriptar($_SESSION['p_n'],_FULL_KEY_);
            $usuario_apellido = $this->encriptar->desencriptar($_SESSION['p_p'],_FULL_KEY_);
            $usuario_materno = $this->encriptar->desencriptar($_SESSION['p_m'],_FULL_KEY_);
            $usuario = $usuario_nombre. ' ' .$usuario_apellido. ' ' .$usuario_materno;

            $tipo_venta = $_GET['tipo_venta'];
            $fecha_ini = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_final'];
            $lugar = $_GET['lugar'];
            if($fecha_ini != "" && $fecha_fin != ""){
                $fecha_vacio = "DESDE EL ".date('m-d-Y', strtotime($fecha_ini))." HASTA EL ".date('m-d-Y', strtotime($fecha_fin));
            }else{
                $fecha_vacio = utf8_decode("FECHA SIN LÍMITE");
            }

            $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario   
                        inner join roles r on u.id_rol = r.id_rol
                        inner join personas p on u.id_persona = p.id_persona
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 1 ";
            $select = "";
            $where = true;
            if ($tipo_venta != "") {
                $where = true;
                $select = $select . " and v.venta_tipo = '" . $tipo_venta . "'";
                $tipo_venta_a = $_GET['tipo_venta'];
            }

            if ($fecha_ini != "" and $fecha_fin != "") {
                $where = true;
                $select = $select . " and DATE(v.venta_fecha) between '" . $_GET['fecha_inicio'] . "' and '" . $_GET['fecha_final'] . "'";
                $fecha_ini = $_GET['fecha_inicio'];
                $fecha_fin = $_GET['fecha_final'];
            }
            if($lugar != ""){
                if($lugar==1){
                    $where = true;
                    $select = $select . " and v.id_caja_numero = '" . $lugar . "'";
                    $lugar = $_GET['lugar'];
                }else{
                    $where = true;
                    $select = $select . " and v.id_caja_numero is null";
                    $lugar = $_GET['lugar'];
                }
            }

            if ($where) {
                $datos = true;
                $order = " order by v.venta_fecha asc";
                $query = $query . $select . $order;
                $ventas = $this->ventas->listar_ventas($query);
            }

            $fecha_ini = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_final'];
            $filtro = true;

            if($tipo_venta_a == "03"){
                $tipo_comprobante = "BOLETA";
            }elseif ($tipo_venta_a == "01"){
                $tipo_comprobante = "FACTURA";
            }elseif($tipo_venta_a == "07"){
                $tipo_comprobante = "NOTA DE CRÉDITO";
            }elseif($tipo_venta_a == "08"){
                $tipo_comprobante = "NOTA DE DÉBITO";
            }else{
                $tipo_comprobante = "TODOS";
            }

            $fecha_hoy = date("d-m-y");
            $nombre_excel = 'historial_de_ventas_enviadas' . '_' . $fecha_hoy;

            //creamos el archivo excel
            header( "Content-Type: application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition: attachment; filename=".$nombre_excel.".xls");
            require _VIEW_PATH_ . 'ventas/excel_ventas_enviadas.php';
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
    }
    public function crear_xml_enviar_sunat(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id_venta'];
                $venta = $this->ventas->listar_soloventa_x_id($id_venta);
                $detalle_venta = $this->ventas->listar_detalle_ventas($id_venta);
                $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                //$producto = $this->ventas->listar_producto_x_id($detalle_venta->id_producto);
                //ASIGAMOS NOMBRE AL ARCHIVO XML
                $nombre = $empresa->empresa_ruc.'-'.$venta->venta_tipo.'-'.$venta->venta_serie.'-'.$venta->venta_correlativo;
                $ruta = "libs/ApiFacturacion/xml/";
                if($venta->id_tipo_pago == "5"){
                    $cuotas = $this->ventas->listar_cuotas_x_venta($id_venta);
                }else{
                    $cuotas = '';
                }
                //validamos el tipo de comprobante para crear su archivo XML
                if($venta->venta_tipo == '01' || $venta->venta_tipo == '03'){
                    $this->generadorXML->CrearXMLFactura($ruta.$nombre, $empresa, $cliente, $venta, $detalle_venta, $cuotas);
                }else{
                    $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_venta($id_venta);
                    if ($venta->venta_tipo == '07'){

                        $descripcion_nota = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                        $this->generadorXML->CrearXMLNotaCredito($ruta.$nombre, $empresa, $cliente, $venta, $detalle_venta,$descripcion_nota,$cuotas);
                    }else{
                        $descripcion_nota = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);
                        $this->generadorXML->CrearXMLNotaDebito($ruta.$nombre, $empresa, $cliente, $venta, $detalle_venta,$descripcion_nota,$cuotas);
                    }
                }
                //SE PROCEDE A FIRMAR EL XML CREADO
                $result = $this->apiFacturacion->EnviarComprobanteElectronico($empresa,$nombre,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/","libs/ApiFacturacion/cdr/", $id_venta);
                //FIN FACTURACION ELECTRONICA
                if($result == 1){
                    $result = $this->ventas->guardar_estado_de_envio_venta($id_venta, '1', '1');
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
    public function crear_enviar_resumen_sunat(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $fecha = $_POST['fecha'];
                $ventas = $this->ventas->listar_venta_x_fecha($fecha, '01');
                //CONTROLAMOS VARIOS ENVIOS EL MISMO DIAS
                $serie = date('Ymd');
                $fila_serie = $this->ventas->listar_serie_resumen('RC');

                //$correlativo = 1;
                if($fila_serie->serie != $serie){
                    //$result = $this->ventas->actualizar_serie_resumen('RC', $serie);
                    $correlativo = 1;
                }else{
                    $correlativo = $fila_serie->correlativo + 1;
                }

                if($result == 1){
                    //$result = $this->ventas->actualizar_correlativo_resumen('RC', $correlativo);
                    if($result == 1){
                        $cabecera = array(
                            "tipocomp"		=>"RC",
                            "serie"			=>$serie,
                            "correlativo"	=>$correlativo,
                            "fecha_emision" =>date('Y-m-d'),
                            "fecha_envio"	=>date('Y-m-d')
                        );
                        //$cabecera = $this->ventas->listar_serie_resumen('RC');
                        $items = $ventas;
                        $ruta = "libs/ApiFacturacion/xml/";
                        $emisor = $this->ventas->listar_empresa_x_id_empresa('1');
                        $nombrexml = $emisor->empresa_ruc.'-'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

                        //CREAMOS EL XML DEL RESUMEN
                        $this->generadorXML->CrearXMLResumenDocumentos($emisor, $cabecera, $items, $ruta.$nombrexml, $fecha);

                        $result = $this->apiFacturacion->EnviarResumenComprobantes($emisor,$nombrexml,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/");
                        $ticket = $result['ticket'];
                        if($result['result'] == 1){
                            $ruta_xml = $ruta.$nombrexml.'.XML';
                            $guardar_resumen =$this->ventas->guardar_resumen_diario($fecha,$cabecera['serie'],$cabecera['correlativo'],$ruta_xml,'1',$result['mensaje'],$result['ticket']);
                            if($guardar_resumen == 1){
                                if($fila_serie->serie != $serie){
                                    $this->ventas->actualizar_serie_resumen('RC', $serie);
                                    //$correlativo = 1;
                                }
                                //$this->ventas->actualizar_serie_resumen('RC', $serie);
                                $this->ventas->actualizar_correlativo_resumen('RC', $correlativo);
                                $id_resumen = $this->ventas->listar_envio_resumen_x_ticket($result['ticket']);
                                foreach ($items as $i) {
                                    $guardar_resumen_detalle = $this->ventas->guardar_resumen_diario_detalle($id_resumen->id_envio_resumen,$i->id_venta);
                                    if ($guardar_resumen_detalle == 1){
                                        if ($i->anulado_sunat == "1" && $i->venta_condicion_resumen == "1"){
                                            $result = $this->ventas->guardar_estado_de_envio_venta($i->id_venta, '2', '0');
                                            $this->ventas->editar_venta_condicion_resumen_anulado_x_venta($i->id_venta, '3');
                                        }else{
                                            $result = $this->ventas->guardar_estado_de_envio_venta($i->id_venta, '2', '1');
                                        }
                                    }
                                }
                                if($result == 1){
                                    $result = $this->apiFacturacion->ConsultarTicket($emisor, $cabecera, $ticket,"libs/ApiFacturacion/cdr/", 1);

                                }

                            }
                        }elseif($result['result'] == 4){
                            $result = 4;
                            $message = $result['mensaje'];
                        }elseif($result['result'] == 3){
                            $result = 3;
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
    public function consultar_ticket_resumen()
    {
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if ($ok_data) {
                $id_resumen = $_POST['id_resumen_diario'];
                $resumen_diario = $this->ventas->listar_resumen_diario_x_id($id_resumen);
                $serie = $resumen_diario->envio_resumen_serie;
                $correlativo = $resumen_diario->envio_resumen_correlativo;
                $ticket = $resumen_diario->envio_resumen_ticket;

                if (!empty($resumen_diario)) {
                    //$result = $this->ventas->actualizar_correlativo_resumen('RC', $correlativo);
                    if ($result == 1) {
                        $cabecera = array(
                            "tipocomp" => "RC",
                            "serie" => $serie,
                            "correlativo" => $correlativo,
                            "fecha_emision" => date('Y-m-d'),
                            "fecha_envio" => date('Y-m-d')
                        );
                        //$cabecera = $this->ventas->listar_serie_resumen('RC');

                        $ruta = "libs/ApiFacturacion/xml/";
                        $emisor = $this->ventas->listar_empresa_x_id_empresa('1');
                        $nombrexml = $emisor->empresa_ruc . '-' . $cabecera['tipocomp'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'];

                        $result = $this->apiFacturacion->ConsultarTicket($emisor, $cabecera, $ticket, "libs/ApiFacturacion/cdr/", 1);

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
    public function consultar_ticket_resumen_anula()
    {
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if ($ok_data) {
                $id_resumen = $_POST['id_resumen_diario'];
                $resumen_diario = $this->ventas->listar_resumen_diario_baja_x_id($id_resumen);
                $serie = $resumen_diario->venta_anulado_serie;
                $correlativo = $resumen_diario->venta_anulado_correlativo;
                $ticket = $resumen_diario->venta_anulado_ticket;

                if (!empty($resumen_diario)) {
                    //$result = $this->ventas->actualizar_correlativo_resumen('RC', $correlativo);
                    if ($result == 1) {
                        $cabecera = array(
                            "tipocomp" => "RA",
                            "serie" => $serie,
                            "correlativo" => $correlativo,
                            "fecha_emision" => date('Y-m-d'),
                            "fecha_envio" => date('Y-m-d')
                        );
                        //$cabecera = $this->ventas->listar_serie_resumen('RC');

                        $ruta = "libs/ApiFacturacion/xml/";
                        $emisor = $this->ventas->listar_empresa_x_id_empresa('1');
                        $nombrexml = $emisor->empresa_ruc . '-' . $cabecera['tipocomp'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'];

                        $result = $this->apiFacturacion->ConsultarTicket($emisor, $cabecera, $ticket, "libs/ApiFacturacion/cdr/", 2);

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
    public function comunicacion_baja(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id_venta'];

                //$fecha = $_POST['fecha'];
                //$ventas = $this->ventas->listar_venta_x_fecha($fecha, '03');
                //CONTROLAMOS VARIOS ENVIOS EL MISMO DIAS
                $serie = date('Ymd');
                $fila_serie = $this->ventas->listar_serie_resumen('RA');
                $venta = $this->ventas->listar_venta_x_id($id_venta);

                //$correlativo = 1;
                if($fila_serie->serie != $serie){
                    //$result = $this->ventas->actualizar_serie_resumen('RA', $serie);
                    $correlativo = 1;
                }else{
                    $correlativo = $fila_serie->correlativo + 1;
                }

                if($result == 1){
                    //$result = $this->ventas->actualizar_correlativo_resumen('RA', $correlativo);
                    if($result == 1){
                        $cabecera = array(
                            "tipocomp"		=>"RA",
                            "serie"			=>$serie,
                            "correlativo"	=>$correlativo,
                            "fecha_emision" =>date('Y-m-d'),
                            "fecha_envio"	=>date('Y-m-d')
                        );
                        //$cabecera = $this->ventas->listar_serie_resumen('RA');
                        $items = $venta;
                        $ruta = "libs/ApiFacturacion/xml/";
                        $emisor = $this->ventas->listar_empresa_x_id_empresa('1');
                        $nombrexml = $emisor->empresa_ruc.'-'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

                        //CREAMOS EL XML DEL RESUMEN
                        $this->generadorXML->CrearXmlBajaDocumentos($emisor, $cabecera, $items, $ruta.$nombrexml);

                        $result = $this->apiFacturacion->EnviarResumenComprobantes($emisor,$nombrexml,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/");
                        $ticket = $result['ticket'];
                        if($result['result'] == 1){
                            $id_user = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                            $ruta_xml = $ruta.$nombrexml.'.XML';
                            $guardar_anulacion =$this->ventas->guardar_venta_anulacion(date('Y-m-d', strtotime($venta->venta_fecha)),$cabecera['serie'],$cabecera['correlativo'],$ruta_xml,$result['mensaje'],$id_venta,$id_user,$result['ticket']);
                            if($guardar_anulacion == 1){
                                if($fila_serie->serie != $serie){
                                    $result = $this->ventas->actualizar_serie_resumen('RA', $serie);
                                }
                                $this->ventas->actualizar_correlativo_resumen('RA', $correlativo);
                                $result = $this->ventas->editar_estado_venta_anulado($id_venta);
                                if($result == 1){
                                    $result = $this->apiFacturacion->ConsultarTicket($emisor, $cabecera, $ticket,"libs/ApiFacturacion/cdr/",2);
                                    $datos_pagados=$this->hotel->listar_movimientos_id_venta($venta->id_venta);
                                    foreach ($datos_pagados as $dapa) {
                                        $this->hotel->actualizar_caja_monto($venta->id_caja_cierre,$dapa->id_moneda,$dapa->id_tipo_pago,$dapa->caja_movimiento_monto*(-1));
                                    }
                                    $this->hotel->guardar_caja_movimiento($venta->id_caja_cierre,0,$venta->id_moneda,$venta->id_tipo_pago,$venta->id_venta,0,$venta->venta_total,"Comunicacion de baja Venta con id: ".$venta->id_venta,"","");
                                }

                            }
                        }elseif($result['result'] == 4){
                            $result = 4;
                            $message = $result['mensaje'];
                        }elseif($result['result'] == 3){
                            $result = 3;
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
    public function anular_boleta_cambiarestado(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id_venta'];
                $estado = $_POST['estado'];
                $piscina = 3;
                $result = $this->ventas->actualizar_venta_anulado($id_venta,$estado,$piscina);
                if($result==1){
                    $data_venta = $this->ventas->listar_venta($id_venta);
                    $this->hotel->guardar_caja_movimiento($data_venta->id_caja_cierre,0,$data_venta->id_moneda,$data_venta->id_tipo_pago,$data_venta->id_venta,0,$data_venta->venta_total,"Comunicacion de baja Venta con id: ".$data_venta->id_venta,"","");
                    //AQUI EMPEZARA EL CAMBIO
                    $buscar_resta = $this->ventas->buscar_resta($id_venta);
                    foreach ($buscar_resta as $dv){
                        $monto =  $dv->caja_movimiento_monto * (-1);
                        $this->hotel->actualizar_caja_monto($data_venta->id_caja_cierre,$dv->id_moneda,$dv->id_tipo_pago, $monto);
                    }
                    //AQUI TERMINA EL CAMBIO CON LA BENDICION DE DIOS QUE FUNCIONE BIEN
                    //actualizar monto
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
    public function cambiarestado_enviado(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id'];
                $venta = $this->ventas->listar_venta($id_venta);
                if ($_POST['accion'] == "1033"){
                    $respuesta = "La Factura numero ".$venta->venta_serie."-".$venta->venta_correlativo.", ha sido aceptada";
                    $result = $this->ventas->actualizar_venta_enviado($id_venta,$respuesta);
                }else if($_POST['accion'] == "1032"){
                    $respuesta = "El comprobante ya esta informado y se encuentra con estado anulado o rechazado";
                    $result = $this->ventas->actualizar_venta_enviado_anulado($id_venta,$respuesta);
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
    //FUNCION NUEVA
    public function tipo_pagos_editar(){
        $result = 1;
        $message = 'OK';
        try {
            $id_venta = $_POST['id_venta'];
            $id_caja = $this->ventas->id_caja($id_venta);
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $buscar_tipo_pagos =$this->ventas->buscar_tipo_pagos($id_venta);
            $a=1;
            foreach ($buscar_tipo_pagos as $btp){
                $buscar_id_caja_movi = $this->ventas->id_caja_movimiento($btp->venta);
                $tabla .= "<div class='row'>
                        <div class='col-lg-6'>
                            <input type='hidden' id='id_venta_detalle_pago_$a' value='$btp->id_venta_detalle_pago'>
                            <input type='hidden' id='tipo_antiguo_$a' value='$btp->id_tipo_pago'>
                            <label for=''>Tipo de Pago $a</label>
                            <select class='form-control' name='id_tipo_pago_$a' id='id_tipo_pago_$a'>
                                <option ".(($btp->id_tipo_pago==1)?'selected':'')." value='1'>TARJETA</option>
                                <option ".(($btp->id_tipo_pago==2)?'selected':'')." value='2'>TRANSFERENCIA</option>
                                <option ".(($btp->id_tipo_pago==3)?'selected':'')." value='3'>EFECTIVO / CONTADO</option>
                                <option ".(($btp->id_tipo_pago==5)?'selected':'')." value='5'>CREDITO</option>
                                <option ".(($btp->id_tipo_pago==6)?'selected':'')." value='6'>DEPOSITO</option> 
                            </select>
                        </div>
                        <div class='col-lg-4'>
                            <label>Monto $a</label>
                            <input type='text' class='form-control' name='monto_$a' id='monto_$a' value='$btp->venta_detalle_pago_monto' disabled>
                        </div>
                      </div>";
                $a++;
            }
            $tabla.="<input type='hidden' id='id_caja_cierre' value='$id_caja->id_caja_cierre'>";
            $tabla.="<input type='hidden' id='id_moneda' value='$id_caja->id_moneda'>";
            $tabla.="<input type='hidden' id='id_venta' value='$id_venta'>";

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode($tabla);
    }
    public function guardar_edicion_tp(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            $id_caja_cierre = $_POST['id_caja_cierre'];
            $tipo_antiguo_1 = $_POST['tipo_antiguo_1'];
            $tipo_antiguo_2 = $_POST['tipo_antiguo_2'];
            $moneda =$_POST['id_moneda'];
            $id_venta =$_POST['id_venta'];
            if($ok_data) {
                if(!empty($_POST['id_tipo_pago_1']) && !empty($_POST['id_venta_detalle_pago_1'])){
                    $result=$this->ventas->editar_tipo_pago($_POST['id_tipo_pago_1'],$_POST['id_venta_detalle_pago_1']);
                    if(!empty($_POST['monto1'])){
                        $update =$this->ventas->update_monto_tp($_POST['monto1'],$id_caja_cierre,$moneda,$tipo_antiguo_1);
                        $update =$this->ventas->update_monto_tp_suma($_POST['monto1'],$id_caja_cierre,$moneda,$_POST['id_tipo_pago_1']);
                        $update1 = $this->ventas->update_caja_movimiento($_POST['id_tipo_pago_1'],$id_caja_cierre,$moneda,$id_venta,$tipo_antiguo_1);
                    }
                }
                if(!empty($_POST['id_tipo_pago_2']) && !empty($_POST['id_venta_detalle_pago_2'])){
                    $result=$this->ventas->editar_tipo_pago($_POST['id_tipo_pago_2'],$_POST['id_venta_detalle_pago_2']);
                    if(!empty($_POST['monto2'])){
                        $update =$this->ventas->update_monto_tp($_POST['monto2'],$id_caja_cierre,$moneda,$tipo_antiguo_2);
                        $update =$this->ventas->update_monto_tp_suma($_POST['monto2'],$id_caja_cierre,$moneda,$_POST['id_tipo_pago_2']);
                        $update1 = $this->ventas->update_caja_movimiento($_POST['id_tipo_pago_2'],$id_caja_cierre,$moneda,$id_venta,$tipo_antiguo_2);
                    }

                }
            }else{
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
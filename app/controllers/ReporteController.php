<?php
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Menu.php';
require 'app/models/Archivo.php';
require 'app/models/Reporte.php';
require 'app/models/Hotel.php';
//require 'app/models/Caja.php';

class ReporteController
{
    private $usuario;
    private $rol;
    private $archivo;
    private $reporte;
    private $hotel;
//    private $caja;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $nav;

    public function __construct()
    {
        //Instancias especificas del controlador
        $this->usuario = new Usuario();
        $this->rol = new Rol();
        $this->archivo = new Archivo();
        $this->reporte = new Reporte();
        $this->hotel = new Hotel();
//        $this->caja = new Caja();

        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
    }
    public function reporte_por_producto()
    {
        try {
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));

            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
//            $caja = $this->caja->listar_cajas();
            $fecha_i = date('Y-m-d');
            $fecha_f = date('Y-m-d');
            $datos = false;
            if(isset($_POST['enviar_fecha'])){
                $fecha_i = $_POST['fecha_filtro'];
                $fecha_f = $_POST['fecha_filtro_fin'];
                $productos = $this->reporte->listar_busqueda_venta($fecha_i,$fecha_f);
                $datos = true;
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reporte/reporte_por_producto.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }
    public function reporte_por_producto_pdf(){
        try{
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
            if($_GET['fecha_filtro'] != "" && $_GET['fecha_filtro_fin'] != ""){
                $fecha_i = $_GET['fecha_filtro'];
                $fecha_f = $_GET['fecha_filtro_fin'];
                $productos = $this->reporte->listar_busqueda_venta($fecha_i,$fecha_f);
            }

            require _VIEW_PATH_ . 'reporte/reporte_por_producto_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function ticket_productos_2(){
        try{
            $fecha_i = $_POST['fecha_i'];
            $fecha_f = $_POST['fecha_f'];
            $fecha_ini_caja = $_POST['fecha_i'];
            $fecha_fin_caja = $_POST['fecha_f'];
            $nueva_fecha_i = date('d-m-Y',strtotime($fecha_i));
            $nueva_fecha_f = date('d-m-Y',strtotime($fecha_f));
            $fecha_filtro = strtotime($_POST['fecha_i']);
            $fecha_filtro_fin = strtotime($_POST['fecha_f']);
            require _VIEW_PATH_ . 'reporte/ticket_productos_2.php';
            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));
    }
    public function reporte_general(){
        try {
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
            $caja = $this->reporte->listar_cajas();
            $fecha_hoy = date('Y-m-d');
            $fecha_i = date('Y-m-d');
            $fecha_f = date('Y-m-d');
            $datos = false;
            $usuarios = $this->reporte->listar_cajeros();
            if(isset($_POST['enviar_fecha'])){
                $id_caja_numero = $_POST['id_caja_numero'];
                $id_usuario = $_POST['id_usuario'];
                $fecha_hoy = date('Y-m-d');
                $fecha_i = $_POST['fecha_filtro'];
                $fecha_f = $_POST['fecha_filtro_fin'];
                $fecha_ini_caja = $_POST['fecha_filtro'];
                $fecha_fin_caja = $_POST['fecha_filtro_fin'];
                $fecha_filtro = strtotime($_POST['fecha_filtro']);
                $fecha_filtro_fin = strtotime($_POST['fecha_filtro_fin']);
                $productos = $this->reporte->reporte_productos($fecha_i,$fecha_f,$id_usuario);
//                $listar_egresos = $this->reporte->listar_egresos_descripcion($fecha_i,$fecha_f,$id_usuario);
                //$caja_ = $this->caja->datos_caja_($id_caja_numero);
                $cajas_totales = $this->reporte->datos_por_apertura_caja_($fecha_i,$fecha_f);
                $caja_ = $this->reporte->datos_caja_($id_caja_numero);
                $usuario_ = $this->reporte->listar_usuarios_($id_usuario);
                //$cajas_totales = $this->reporte->datos_por_apertura_caja($id_usuario,$fecha_i,$fecha_f);
                $datos = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reporte/reporte_general.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }
    public function reporte_general_pdf(){
        try{
            if($_GET['fecha_filtro'] != "" && $_GET['fecha_filtro_fin'] != "" && $_GET['id_usuario']) {
                $id_caja_numero = $_GET['id_caja_numero'];
                $id_usuario = $_GET['id_usuario'];
                $fecha_hoy = date('Y-m-d');
                $fecha_i = $_GET['fecha_filtro'];
                $fecha_f = $_GET['fecha_filtro_fin'];
                $fecha_ini_caja = $_GET['fecha_filtro'];
                $fecha_fin_caja = $_GET['fecha_filtro_fin'];
                $fecha_filtro = strtotime($_GET['fecha_filtro']);
                $fecha_filtro_fin = strtotime($_GET['fecha_filtro_fin']);
                $productos = $this->reporte->reporte_productos($fecha_i, $fecha_f, $id_usuario);
                //$listar_egresos = $this->reporte->listar_egresos_descripcion($fecha_i,$fecha_f,$id_usuario);
                //$caja_ = $this->caja->datos_caja_($id_caja_numero);
                $cajas_totales = $this->reporte->datos_por_apertura_caja_($fecha_i, $fecha_f);
                $hab_x_cobrar = $this->reporte->listar_habitacion_comandas_x_cobrar($fecha_i, $fecha_f);
                $hab_cobrado = $this->reporte->listar_habitacion_comandas_cobradas($fecha_i, $fecha_f);
                $caja_ = $this->reporte->datos_caja_($id_caja_numero);
                $usuario_ = $this->reporte->listar_usuarios_($id_usuario);
                //$cajas_totales = $this->reporte->datos_por_apertura_caja($id_usuario,$fecha_i,$fecha_f);
            }
            require _VIEW_PATH_ . 'reporte/reporte_general_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }
    public function habitaciones_pdf(){
        try{
            if($_GET['fecha_filtro'] != "" && $_GET['fecha_filtro_fin'] != "") {
                $fecha_i = $_GET['fecha_filtro'];
                $fecha_f = $_GET['fecha_filtro_fin'];
                $hab_x_cobrar = $this->reporte->listar_habitacion_comandas_x_cobrar($fecha_i, $fecha_f);
                $hab_cobrado = $this->reporte->listar_habitacion_comandas_cobradas($fecha_i, $fecha_f);
            }
            require _VIEW_PATH_ . 'reporte/habitaciones_pdf.php';
        }catch (Throwable $e) {
        $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
        echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
        echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function cuentas_cobrar_pdf(){
        try{
            if($_GET['fecha_filtro'] != "" && $_GET['fecha_filtro_fin'] != "") {
                $fecha_i = $_GET['fecha_filtro'];
                $fecha_f = $_GET['fecha_filtro_fin'];
                $hab_x_cobrar = $this->reporte->listar_cargo_comandas_x_cobrar($fecha_i, $fecha_f);
                $hab_cobrado = $this->reporte->listar_cargo_comandas_cobradas($fecha_i, $fecha_f);
            }
            require _VIEW_PATH_ . 'reporte/cuentas_cobrar_pdf.php';
        }catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function ticket_productos(){
        try{
            $fecha_i = $_POST['fecha_i'];
            $fecha_f = $_POST['fecha_f'];
            $id_usuario = $_POST['id_usuario'];
            $usuario_ = $this->reporte->listar_usuarios_($id_usuario);

            $fecha_ini_caja = $_POST['fecha_i'];
            $fecha_fin_caja = $_POST['fecha_f'];

            $nueva_fecha_i = date('d-m-Y',strtotime($fecha_i));
            $nueva_fecha_f = date('d-m-Y',strtotime($fecha_f));
            $fecha_filtro = strtotime($_POST['fecha_i']);
            $fecha_filtro_fin = strtotime($_POST['fecha_f']);
            //$cajas_totales = $this->reporte->datos_por_apertura_caja_($fecha_i,$fecha_f);
            //$listar_productos = $this->reporte->reporte_productos($fecha_ini_caja,$fecha_fin_caja,$id_caja_numero);

            require _VIEW_PATH_ . 'reporte/ticket_productos.php';
            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));
    }

    public function ticket_reporte(){
        try{
            $fecha_i = $_POST['fecha_i'];
            $fecha_f = $_POST['fecha_f'];
            $id_usuario = $_POST['id_usuario'];
            $usuario_ = $this->reporte->listar_usuarios_($id_usuario);
            //$id_caja_numero = $_POST['id_caja_numero'];
            $fecha_ini_caja = $_POST['fecha_i'];
            $fecha_fin_caja = $_POST['fecha_f'];

            $nueva_fecha_i = date('d-m-Y',strtotime($fecha_i));
            $nueva_fecha_f = date('d-m-Y',strtotime($fecha_f));
            $fecha_filtro = strtotime($_POST['fecha_i']);
            $fecha_filtro_fin = strtotime($_POST['fecha_f']);
            //$listar_egresos = $this->reporte->listar_egresos_descripcion($fecha_ini_caja,$fecha_fin_caja,$id_usuario);
            //$cajas_totales = $this->reporte->datos_por_apertura_caja($id_usuario,$fecha_i,$fecha_f);
            //$cajas_totales = $this->reporte->datos_por_apertura_caja_($fecha_i,$fecha_f);
            require _VIEW_PATH_ . 'reporte/ticket_reporte.php';
            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));
    }

    //REPORTE NUEVO PARA EL STOCK DE ALMACEN
    public function reporte_stock_almacen(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));

            $recuacua = $this->reporte->listar_recursos_reporte();
            $fecha_i = date('Y-m-d');
            $fecha_f = date('Y-m-d');
            $datos = false;
            if(isset($_POST['enviar_fecha'])){
                $fecha_i = $_POST['fecha_filtro'];
                $fecha_f = $_POST['fecha_filtro_fin'];
                $fecha_filtro = strtotime($_POST['fecha_filtro']);
                $fecha_filtro_fin = strtotime($_POST['fecha_filtro_fin']);

                //$buscar_salidas_stock = $this->reporte->buscar_salidad_stock($fecha_i,$fecha_f);
                $datos = true;
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reporte/reporte_stock_almacen.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    //REPORTE NUEVO PARA EL STOCK DE ALMACEN
    /*public function reporte_stock_almacen(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));

            $recuacua = $this->reporte->listar_recursos_reporte();
            $fecha_i = date('Y-m-d');
            $fecha_f = date('Y-m-d');
            $datos = false;
            if(isset($_POST['enviar_fecha'])){
                $fecha_i = $_POST['fecha_filtro'];
                $fecha_f = $_POST['fecha_filtro_fin'];
                $fecha_filtro = strtotime($_POST['fecha_filtro']);
                $fecha_filtro_fin = strtotime($_POST['fecha_filtro_fin']);

                //$buscar_salidas_stock = $this->reporte->buscar_salidad_stock($fecha_i,$fecha_f);
                $datos = true;
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reporte/reporte_stock_almacen.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }*/

    public function mostrar_detalle_habitacion(){
        try{
            $tipo = $_POST['valor'];
            $fecha_i = $_POST['fecha_i'];
            $fecha_f = $_POST['fecha_f'];
            $row = '';
            if($tipo == 1){
                $hab_x_cobrar = $this->reporte->listar_habitacion_comandas_x_cobrar($fecha_i, $fecha_f);
                foreach ($hab_x_cobrar as $h){
                    $row .= '<div class="col-lg-6">
                            <label for="">Hab. / Cant. / Pedido / N° Comanda</label>
                            <input type="text" id="n_habitacion" name="n_habitacion" value="'.$h->habitacion_nro.' | '.$h->comanda_detalle_cantidad.' | '.$h->producto_nombre.' | '.$h->comanda_correlativo.'" class="form-control" disabled>
                        </div> 
                            <div class="col-lg-2">
                            <label for="">Fecha de Comanda</label>
                            <input type="text" id="fecha_comanda_detalle" name="fecha_comanda_detalle" value="'.$h->comanda_detalle_fecha_registro.'" class="form-control" disabled>
                        </div>
                          </div> 
                            <div class="col-lg-2">
                            <label for="">Envio Habitación</label>
                            <input type="text" id="rack_detalle_datetime" name="rack_detalle_datetime" value="'.$h->rack_detalle_datetime.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Monto</label>
                            <input type="text" id="monto_habitacion" name="monto_habitacion" value="'.$h->comanda_detalle_total.'" class="form-control" disabled>
                        </div>';
                }


            }else{
                $hab_cobrado = $this->reporte->listar_habitacion_comandas_cobradas($fecha_i, $fecha_f);
                foreach ($hab_cobrado as $h){
                    $row .= '<div class="col-lg-2">
                            <label for="">Comprobante</label>
                            <input type="text" id="n_habitacion" name="n_habitacion" value="'.$h->venta_serie.'-'.$h->venta_correlativo.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Fecha Venta</label>
                            <input type="text" id="venta_fecha" name="venta_fecha" value="'.$h->venta_fecha.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Hab. / Cant. / Pedido / N° Comanda</label>
                            <input type="text" id="mix" name="mix" value="'.$h->habitacion_nro.' | '.$h->comanda_detalle_cantidad.' | '.$h->producto_nombre.' | '.$h->comanda_correlativo.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Monto</label>
                            <input type="text" id="monto_habitacion" name="monto_habitacion" value="'.$h->rack_detalle_subtotal.'" class="form-control" disabled>
                        </div>';
                }
            }
            $result = 1;


        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
            $result = 2;
            $row = '';
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "row" => $row, "message" => $message)));
    }

    public function mostrar_detalle_cargo_cuenta(){
        try{
            $tipo = $_POST['valor'];
            $fecha_i = $_POST['fecha_i'];
            $fecha_f = $_POST['fecha_f'];
            $row = '';
            if($tipo == 1){
                $hab_x_cobrar = $this->reporte->listar_cargo_comandas_x_cobrar($fecha_i, $fecha_f);
                foreach ($hab_x_cobrar as $h){
                    $nombre = $h->cliente_nombre.''.$h->cliente_razonsocial;
                    $row .= '<div class="col-lg-6">
                            <label for="">Cliente de Cuenta / Cant. / Pedido / N° Comanda</label>
                            <input type="text" id="n_habitacion" name="n_habitacion" value="'.$nombre.' | '.$h->comanda_detalle_cantidad.' | '.$h->producto_nombre.' | '.$h->comanda_correlativo.'" class="form-control" disabled>
                        </div> 
                            <div class="col-lg-2">
                            <label for="">Fecha de Comanda</label>
                            <input type="text" id="fecha_comanda_detalle" name="fecha_comanda_detalle" value="'.$h->comanda_detalle_fecha_registro.'" class="form-control" disabled>
                        </div>
                          </div> 
                            <div class="col-lg-2">
                            <label for="">Envio a Cuenta</label>
                            <input type="text" id="rack_detalle_datetime" name="rack_detalle_datetime" value="'.$h->cuentas_detalle_fecha_creacion.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Monto</label>
                            <input type="text" id="monto_habitacion" name="monto_habitacion" value="'.$h->comanda_detalle_total.'" class="form-control" disabled>
                        </div>';
                }
            }else{
                $hab_cobrado = $this->reporte->listar_cargo_comandas_cobradas($fecha_i, $fecha_f);
                foreach ($hab_cobrado as $h){
                    $bc = $this->reporte->buscar_comprobantes_cargo($h->id_comanda_detalle);
                    $nombre = $h->cliente_nombre.''.$h->cliente_razonsocial;
                    $row .= '<div class="col-lg-2">
                            <label for="">Comprobante</label>
                            <input type="text" id="n_habitacion" name="n_habitacion" value="'.$bc->venta_serie.'-'.$bc->venta_correlativo.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Fecha Venta</label>
                            <input type="text" id="venta_fecha" name="venta_fecha" value="'.$bc->venta_fecha.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Cliente Cuenta / Cant. / Pedido / N° Comanda</label>
                            <input type="text" id="mix" name="mix" value="'.$nombre.' | '.$h->comanda_detalle_cantidad.' | '.$h->producto_nombre.' | '.$h->comanda_correlativo.'" class="form-control" disabled>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Monto</label>
                            <input type="text" id="monto_habitacion" name="monto_habitacion" value="'.$h->comanda_detalle_total.'" class="form-control" disabled>
                        </div>';
                }
            }
            $result = 1;


        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
            $result = 2;
            $row = '';
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "row" => $row, "message" => $message)));
    }
    public function reporte_gerencia(){
        try {
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $fecha=date("Y-m-d");
            $fecha_formateada=$this->validar->obtener_nombre_fecha($fecha,"Date","Date","es");
            $habitaciones=$this->reporte->habitaciones_por_estado_fecha($fecha);
            $cajas = $this->hotel->listar_cajas();
            $turnos = $this->nav->listar_turnos();
            $ultima_=date("Y-m-d",strtotime($fecha."- 7 days"));
            $hoy=strtotime($fecha);
            $ultima=strtotime($ultima_);
            $ocupaciones=[];
            for($i=$ultima; $i<=$hoy; $i+=86400){
                $fechita=date("Y-m-d", $i);
                $fechita2=date("d-m", $i);
                $cant=$this->reporte->habitaciones_por_estado_fecha($fechita);
                $ocupaciones[]=array("fecha"=>$fechita2,"cant"=>$cant);
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reporte/reporte_gerencia.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script>window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    //FUNCION PARA LA VISTA DEL REPORTE DE MESEROS
    public function reporte_meseros(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            $fecha_hoy = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_fecha'])){
                $fecha_hoy = $_POST['fecha_hoy'];
                $fecha_fin = $_POST['fecha_fin'];
                $meseros = $this->reporte->datos_meseros($fecha_hoy, $fecha_fin);
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reporte/reporte_meseros.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function reporte_meseros_pdf(){
        try{
            if($_GET['fecha_hoy'] != "" && $_GET['fecha_fin'] != ""){
                $fecha_hoy = $_GET['fecha_hoy'];
                $fecha_fin = $_GET['fecha_fin'];
                $meseros = $this->reporte->datos_meseros($fecha_hoy, $fecha_fin);
            }
            require _VIEW_PATH_ . 'reporte/reporte_meseros_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }



}
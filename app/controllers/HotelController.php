<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 19/10/2020
 * Time: 19:54
 */
require 'app/models/Hotel.php';
require 'app/models/Ventas.php';
require 'app/models/Clientes.php';
require 'app/models/Pedido.php';
require 'app/models/Cuentascobrar.php';
class HotelController{
    //Variables especi$movimientosficas del controlador
    private $hotel;
    private $clientes;
    private $ventas;
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $pedido;
    private $cuentascobrar;
    public function __construct()
    {
        $this->hotel = new Hotel();
        $this->ventas = new Ventas();
        $this->clientes = new Clientes();
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->pedido = new Pedido();
        $this->cuentascobrar = new Cuentascobrar();
    }
    //Vistas/Opciones
    //Vista de Inicio de La Gestión de Menús
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $habitaciones = $this->hotel->listar_habitaciones();
            $habitaciones_m = $this->hotel->listar_habitaciones();
            $monedas = $this->ventas->listar_monedas();
            $tipo_doc = $this->hotel->listar_tipodoc();
            $tipos_habitacion = $this->hotel->listar_tipos_habitacion();
            $fecha_busqueda = date('Y-m-d');
            $tip = "0";
            $esta = "0";
            if(isset($_GET['id'])){
                $exx = explode("99932",$_GET['id']);
                $fecha_busqueda = $exx[0];
                if(count($exx)>1){
                    $tip = $exx[1];
                    $esta = $exx[2];
                    if($tip!=0 && $esta !=0){
                        $habitaciones=$this->hotel->listar_habitaciones_por_tipo_estado_fecha($tip,$esta,$fecha_busqueda);
                    }elseif ($tip!=0){
                        $habitaciones = $this->hotel->listar_habitaciones_por_tipo($tip);
                    }elseif($tip==0 && $esta ==0){
                        $habitaciones = $this->hotel->listar_habitaciones();
                    }else{
                        $habitaciones=$this->hotel->listar_habitaciones_por_estado_fecha($esta,$fecha_busqueda);
                    }
                }
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'hotel/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function rooming(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $habitaciones = $this->hotel->listar_habitaciones();
            $fecha_busqueda = date('Y-m-d');
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'hotel/rooming.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function rooming_excel(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $habitaciones = $this->hotel->listar_habitaciones();
            $fecha_busqueda = date('Y-m-d');
            header( "Content-Type: application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition: attachment; filename=Rooming List - ".$fecha_busqueda.".xls");
            require _VIEW_PATH_ . 'hotel/rooming_excel.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function detalle_habitacion(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'];
            $fecha = $_GET['id2'];
            $data_rack = $this->hotel->listar_rack_por_id_fecha($id,$fecha);
            $detalle_rack = $this->hotel->listar_detalle_rack($data_rack->id_rack);
            $buscar_ = $this->hotel->listar_detalle_rack_fecha($data_rack->id_rack);
            $clientes_cuenta = $this->clientes->listar_clientes();
            $data_cliente = $this->clientes->listar_cliente_x_id($data_rack->id_cliente);
            $extras = $this->hotel->listar_extras($data_rack->id_rack);
            $cliente=$this->clientes->listar_clientes_editar($data_rack->id_cliente);
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $categorias=$this->hotel->listar_categorias();
            $data_caja=$this->nav->listar_data_caja_actual();
            $data_caja_=$this->nav->listar_data_caja_actual_();
            $tipo_doc=$this->hotel->listar_tipodoc();
            $habs_dis = $this->hotel->listar_habitaciones_libres();
            $productos = $this->hotel->ver_productos_todos();
            $id_comanda =$this->hotel->listar_id_comanda_x_rack($data_rack->id_rack);
            if(count($detalle_rack)<=0){
                echo "<script>alert(\"La habitación se encuentra vacía.\");window.location.href=\"". _SERVER_ ."\";</script>";
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'hotel/detalle_habitacion.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function ticket_detalle(){
        try{
            $id = $_GET['id_h'];
            $fecha = $_GET['fecha'];
            $data_rack = $this->hotel->listar_rack_por_id_fecha($id,$fecha);
            $detalle_rack = $this->hotel->listar_detalle_rack_($data_rack->id_rack);
            $cliente=$this->clientes->listar_clientes_editar($data_rack->id_cliente);
            require _VIEW_PATH_ . 'hotel/ticket_detalle.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_habitacion(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'];
            $fecha = $_GET['id2'];
            $exp=explode("al",$fecha);
            $fecha_i = $exp[0];
            $fecha_f = $exp[1];
            $habitacion = $this->hotel->listar_habitacion_por_id($id);
            $racks = $this->hotel->listar_racks_por_id_habitacion($id,$fecha_i,$fecha_f);
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $data_caja=$this->nav->listar_data_caja_actual();
            $tipos_moneda=$this->ventas->listar_monedas();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'hotel/historial_habitacion.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function movimientos_caja(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $monedas = $this->ventas->listar_monedas();
            $cajas = $this->hotel->listar_cajas();
            $turnos = $this->nav->listar_turnos();
            $tipo_doc = $this->hotel->listar_tipodoc();
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $categorias=$this->hotel->listar_categorias();
            $categorias_gasto=$this->hotel->config_cat_egresos();
            $data_caja=$this->nav->listar_data_caja_actual();
            $productos = $this->hotel->ver_productos_todos();
            if(isset($_GET['id'])){
                $exp = explode("al",$_GET['id']);
                $desde=$exp[0];
                $hasta=$exp[1];
            }else{
                $desde = date('Y-m-d');
                $hasta = date('Y-m-d');
            }
            $top_productos = $this->ventas->listar_top_productos($desde,$hasta);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'hotel/movimientos_caja.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function movimientos_caja_detalle(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $monedas = $this->ventas->listar_monedas();
            $cajas = $this->hotel->listar_cajas();
            $turnos = $this->nav->listar_turnos();
            $tipo_doc = $this->hotel->listar_tipodoc();
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $categorias=$this->hotel->listar_categorias();
            $categorias_gasto=$this->hotel->config_cat_egresos();
            $data_caja=$this->nav->listar_data_caja_actual();
            $productos = $this->hotel->ver_productos_todos();
            if(isset($_GET['id'])){
                $exp = explode("al",$_GET['id']);
                $desde=$exp[0];
                $hasta=$exp[1];
            }else{
                $desde = date('Y-m-d');
                $hasta = date('Y-m-d');
            }
            $top_productos = $this->ventas->listar_top_productos($desde,$hasta);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'hotel/movimientos_caja_detalle.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function movimientos_caja_excel_det(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $monedas = $this->ventas->listar_monedas();
            $cajas = $this->hotel->listar_cajas();
            $turnos = $this->nav->listar_turnos();
            $tipo_doc = $this->hotel->listar_tipodoc();
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $categorias=$this->hotel->listar_categorias();
            $categorias_gasto=$this->hotel->config_cat_egresos();
            $data_caja=$this->nav->listar_data_caja_actual();
            $productos = $this->hotel->ver_productos_todos();
            if(isset($_GET['id'])){
                $exp = explode("al",$_GET['id']);
                $desde=$exp[0];
                $hasta=$exp[1];
            }else{
                $desde = date('Y-m-d');
                $hasta = date('Y-m-d');
            }
            header( "Content-Type: application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition: attachment; filename=Caja - ".$desde." al ".$hasta.".xls");
            require _VIEW_PATH_ . 'hotel/movimientos_caja_excel_det.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function movimientos_caja_excel(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $monedas = $this->ventas->listar_monedas();
            $cajas = $this->hotel->listar_cajas();
            $turnos = $this->nav->listar_turnos();
            $tipo_doc = $this->hotel->listar_tipodoc();
            $tipos_pago=$this->ventas->listar_tipo_pago();
            $categorias=$this->hotel->listar_categorias();
            $data_caja=$this->nav->listar_data_caja_actual();
            if(isset($_GET['id'])){
                $exp = explode("al",$_GET['id']);
                $desde=$exp[0];
                $hasta=$exp[1];
            }else{
                $desde = date('Y-m-d');
                $hasta = date('Y-m-d');
            }
            header( "Content-Type: application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition: attachment; filename=REPORTE DE CAJA - ".$desde." al ".$hasta.".xls");
            require _VIEW_PATH_ . 'hotel/movimientos_caja_excel.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    //Funciones/Permisos
    //Funcion para guardar un nuevo menú creado
    public function guardar_rack(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_habitacion', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('rack_out', 'POST',true,$ok_data,50,'fecha',0);
            //VALIDACION
            $date=date('Y-m-d');
            if($_POST['rack_out'] > $date){
                $ok_data=true;
            }else{
                $ok_data =false;
            }
            if($ok_data){
                $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['rack_dni']);
                if(isset($existe->id_cliente)){
                    $id_cliente=$existe->id_cliente;
                }else{
                    $model2 = new Hotel();
                    $model2->id_tipodocumento = $_POST['tipo_doc'];
                    $model2->cliente_razonsocial = "";
                    if($_POST['tipo_doc']==4){
                    $model2->cliente_razonsocial = $_POST['rack_nombre'];}
                    $model2->cliente_nombre = $_POST['rack_nombre'];
                    $model2->cliente_numero = $_POST['rack_dni'];
                    $model2->cliente_correo = "";
                    $model2->cliente_direccion = "";
                    $model2->cliente_telefono = "";
                    $model2->cliente_fecha = date('Y-m-d H:i:s');
                    $model2->cliente_estado = 1;
                    $this->clientes->guardar($model2);
                    $existe = $this->clientes->listar_cliente_x_numerodoc($_POST['rack_dni']);
                    $id_cliente=$existe->id_cliente;
                }

                $model = new Hotel();
                $model->id_habitacion = $_POST['id_habitacion'];
                $model->id_cliente = $id_cliente;
                $model->rack_in = $_POST['rack_in'];
                $model->hora_check_in = date('H:i:s');
                $model->rack_out = $_POST['rack_out'];
                $model->rack_noches = $_POST['rack_noches'];
                $model->rack_tarifa = $_POST['rack_tarifa'];
                $model->rack_moneda = $_POST['rack_moneda'];
                $model->rack_agencia = $_POST['rack_agencia'];
                $rack_desayuno = $_POST['rack_desayuno'];
                $model->rack_desayuno = $rack_desayuno;
                $rack_desayuno_costo = $_POST['rack_desayuno_costo'];
                $model->rack_observaciones = $_POST['rack_observaciones'];
                $model->rack_estado = $_POST['menu_estado'];

                $mt = microtime(true);
                $model->rack_mt = $mt;
                if($_POST['menu_estado'] == 1){
                    $result = $this->hotel->guardar_rack($model);
                    $modificar_estado_reserva=$this->hotel->modificar_estado_reserva($_POST['id_reserva']);
                    if($result == 1){
                        $data = $this->hotel->listar_rack_por_mt($mt);
                        $extras = $_POST['extras'];
                        if($extras!=""){
                            $exp = explode("//--",$extras);
                            foreach ($exp as $e){
                                if($e!=""){
                                    $this->hotel->guardar_extras($data->id_rack,$e);
                                }
                            }
                        }
                        $fechaInicio=strtotime($_POST['rack_in']);
                        $fechaFin=strtotime($_POST['rack_out']);
                        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                            $fechita = date("Y-m-d", $i);
                            if($fechita!=$_POST['rack_out']){
                                $model2 = new Hotel();
                                $model2->rack_detalle_fecha= $fechita;
                                $model2->id_rack=$data->id_rack;
                                $model2->id_producto=211;
                                $model2->rack_detalle_cantidad=1;
                                $model2->rack_detalle_preciounit=$_POST['rack_tarifa'];
                                $model2->rack_detalle_subtotal=$_POST['rack_tarifa'];
                                $model2->rack_detalle_estado_fact=0;
                                $result=$this->hotel->guardar_detalle_rack($model2);
                                $this->hotel->guardar_habitacion_estado($_POST['id_habitacion'],$fechita,2,0,$_POST['rack_moneda']);
                            }else{
                                $this->hotel->guardar_habitacion_estado($_POST['id_habitacion'],$fechita,2,1,$_POST['rack_moneda']);
                            }
                            
                        }
                        /*if($rack_desayuno>0){
                            $model3 = new Hotel();
                            $model3->rack_detalle_fecha= $_POST['rack_in'];
                            $model3->id_rack=$data->id_rack;
                            $model3->id_producto=212;
                            $model3->rack_detalle_cantidad=$rack_desayuno;
                            $model3->rack_detalle_preciounit=$rack_desayuno_costo;
                            $model3->rack_detalle_subtotal=$rack_desayuno * $rack_desayuno_costo;
                            $model3->rack_detalle_estado_fact=0;
                            $result=$this->hotel->guardar_detalle_rack($model3);
                        }*/
                    }
                }else{
                    $fechaInicio=strtotime($_POST['rack_in']);
                    $fechaFin=strtotime($_POST['rack_out']);
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400) {
                        $model->fecha = date("Y-m-d", $i);
                        $result = $this->hotel->guardar_deshabilitado($model);
                        $this->hotel->guardar_habitacion_estado($_POST['id_habitacion'],date("Y-m-d", $i),3,0,$_POST['rack_moneda']);
                    }
                }
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function deshabilitar_habitacion(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_habitacion', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $model = new Hotel();
                $model->id_habitacion = $_POST['id_habitacion'];
                $model->rack_nombre = $_POST['rack_nombre'];
                $model->rack_in = $_POST['rack_in'];
                $model->rack_out = $_POST['rack_out'];
                $model->rack_noches = $_POST['rack_noches'];
                $model->rack_tarifa = $_POST['rack_tarifa'];
                $model->rack_agencia = $_POST['rack_agencia'];
                $model->rack_desayuno = $_POST['rack_desayuno'];
                $model->rack_observaciones = $_POST['rack_observaciones'];
                $model->rack_estado = $_POST['menu_estado'];
                $mt = microtime(true);
                $model->rack_mt = $mt;
                //Guardamos el menú y recibimos el resultado
                $result = $this->hotel->guardar_rack($model);
                if($result == 1){
                    $data = $this->hotel->listar_rack_por_mt($mt);
                    $fechaInicio=strtotime($_POST['rack_in']);
                    $fechaFin=strtotime($_POST['rack_out']);
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                        $model2 = new Hotel();
                        $model2->rack_detalle_fecha= date("Y-m-d", $i);
                        $model2->id_rack=$data->id_rack;
                        $model2->id_producto=1;
                        $model2->rack_detalle_cantidad=1;
                        $model2->rack_detalle_preciounit=$_POST['rack_tarifa'];
                        $model2->rack_detalle_subtotal=$_POST['rack_tarifa'];
                        $result=$this->hotel->guardar_detalle_rack($model2);
                        $this->hotel->guardar_habitacion_estado($_POST['id_habitacion'],date("Y-m-d", $i),2,0);
                    }
                }
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function cambiar_habitacion(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('new_hab', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_rack', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $id_rack = $_POST['id_rack'];
                $new_hab = $_POST['new_hab'];
                $data_rack = $this->hotel->listar_rack_por_id($id_rack);
                $ini = strtotime($data_rack->rack_in);
                $fifi = strtotime($data_rack->rack_out);
                for ($ij = $ini; $ij <= $fifi; $ij += 86400) {
                    $new = date('Y-m-d', $ij);
                    $data_puede = $this->hotel->habitacion_por_fecha_Estado($new, $data_rack->id_habitacion);
                    if (isset($data_puede->id_habitacion_estado)) {
                        $this->hotel->cambiar_habitacion_estado($new_hab,$data_puede->id_habitacion_estado);
                    }
                }
                $result = $this->hotel->cambiar_habitacion($new_hab,$id_rack);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function guardar_detalle_rack(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_rack', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_pro', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                //AQUI SE DEBE REALIZAR UN FOR PARA PODER GUARDAR LOS ITEMS POR SEPARADO DE LOS ALOJAMIENTOS SIEMPRE Y CUANDO TENGA ID=211
                $cantidad_dias = $_POST['cantidad'];
                    $model2 = new Hotel();
                    $model2->rack_detalle_fecha= date("Y-m-d");
                    $model2->id_rack=$_POST['id_rack'];
                    $model2->id_producto=$_POST['id_pro'];
                    $model2->rack_detalle_cantidad=$_POST['cantidad'];
                    $model2->rack_detalle_preciounit=$_POST['precio_unit'];
                    $model2->rack_detalle_subtotal=$_POST['cantidad'] * $_POST['precio_unit'];
                    $result=$this->hotel->guardar_detalle_rack($model2);
                //LO QUE HAREMOS DESPUES DE AGREGAR UN NUEVO DETALLE SERA VALIDAR PARA QUE SUME EN ESTADIA SI EL PRODUCTO ES ALOJAMIENTO
                if($result==1){
                    $rack =$this->ventas->buscar_datos_rack($_POST['id_rack']);
                    if($_POST['id_pro'] == '211'){
                        //PRIMERO SUMAMOS LA NOCHE QUE SE ESTA AGREGANDO A LA TABLA RACK
                        $update_noches = $this->ventas->sumar_noches($_POST['id_rack']);
                        if($update_noches==1){
                            $fecha_add = $rack->rack_out;
                            $nueva_fecha_add = date('Y-m-d', strtotime($fecha_add . "+ $cantidad_dias days"));
                            //USAMOS LA MISMA FUNCION DEL RESTAR DIAS PARA EL SUMAR DIAS PORQUE SOLO ACTUALIZA LA FECHA QUE YA LE ENVIO SUMADO
                            $update_checkout = $this->ventas->update_check_out($nueva_fecha_add, $_POST['id_rack']);
                            //Y LUEGO MODIFICAR LOS REGISTROS DE LA TABLA HABITACION_ESTADO CON LAS FECHAS QUE SE AGREGUEN
                        }
                    }
                }
            } else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function abrir_caja(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('caja', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('turno', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $model = new Hotel();
                $model->fecha= date("Y-m-d");
                $model->id_caja=$_POST['caja'];
                $model->id_turno=$_POST['turno'];
                $model->tipo_cambio=$_POST['tipo_cambio'];
                $montos_=explode(".-.-",$_POST['montos']);
                $a=1;
                foreach ($montos_ as $m_){
                    if($a<count($montos_)){
                        $mon = explode("--..--",$m_);
                        $arra[]=array($mon[0]=>$mon[1]);
                        $arra2[]=array($mon[0]=>$mon[1]);
                    }
                    $a++;
                }
                $soles = $arra[0][1];
                $dolares = $arra[1][2];
                $model->id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $mt=microtime(true);
                $model->caja_cierre_mt=$mt;
                $result=$this->hotel->abrir_caja($model,$soles,$dolares);
                if($result==1){
                    $data_ = $this->hotel->listar_caja_cierre_mt($mt);
                    $id_caja_cierre=$data_->id_caja_cierre;
                    $tipos_pago = $this->ventas->listar_tipo_pago();
                    $monedas = $this->ventas->listar_monedas();
                    foreach ($tipos_pago as $tp){
                        foreach ($monedas as $m){
                            if($tp->id_tipo_pago==3) {
                            foreach ($arra as $ar) {
                                foreach ($ar as $a__ => $aa_) {
                                    $id_moneda = $a__;
                                    $monto = $aa_;
                                    if ($m->id_moneda == $id_moneda) {
                                        $result = $this->hotel->guardar_caja_monto($id_caja_cierre, $m->id_moneda, $tp->id_tipo_pago, $monto);
                                    }
                                }
                            }
                            }else{
                                $result = $this->hotel->guardar_caja_monto($id_caja_cierre, $m->id_moneda, $tp->id_tipo_pago, 0);
                            }
                        }
                    }
                    if($result==1){
                        $id_producto_precio=0;
                        $id_tipo_pago=3;
                        $tipo=1;
                        $detalle="APERTURA DE CAJA";
                        $obs="";
                        foreach ($arra2 as $ar2) {
                        foreach ($ar2 as $a__=>$aa_) {
                            $id_moneda = $a__;
                            $monto = $aa_;
                            $result=$this->hotel->guardar_caja_movimiento($id_caja_cierre,$id_producto_precio,$id_moneda,$id_tipo_pago,0,$tipo,$monto,$detalle,$obs,'');
                        }}
                    }
                }
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function cerrar_caja(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('caja', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $id_caja_cierre=$_POST['caja'];
                $result=$this->hotel->cerrar_caja($id_caja_cierre);
                $montos_=explode(".-.-",$_POST['montos']);
                $a=1;
                foreach ($montos_ as $m_){
                    if($a<count($montos_)){
                        $mon = explode("--..--",$m_);
                        $arra[]=array($mon[0]=>$mon[1]);
                        $arra2[]=array($mon[0]=>$mon[1]);
                    }
                    $a++;
                }
                if($result==1){
                    $id_producto_precio=0;
                    $id_tipo_pago=3;
                    $tipo=3;
                    $detalle="CIERRE DE CAJA";
                    $obs="";
                    foreach ($arra2 as $ar2) {
                        foreach ($ar2 as $a__=>$aa_) {
                            $id_moneda = $a__;
                            $monto = $aa_;
                            $result=$this->hotel->guardar_caja_movimiento($id_caja_cierre,$id_producto_precio,$id_moneda,$id_tipo_pago,0,$tipo,$monto,$detalle,$obs,"");
                        }}
                }
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }

    public function confirmar_clave(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('val', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $val=$_POST['val'];
                $id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $result=$this->hotel->guardar_clave_eliminacion($val,$id_usuario);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function buscar_cliente(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('ruc', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $ruc=$_POST['ruc'];
                $data=$this->hotel->buscar_cliente($ruc);
                $razon_social="";
                $domicilio="";
                if(isset($data->id_cliente)){
                    $result=1;
                    $razon_social = $data->cliente_nombre;
                    $domicilio = $data->cliente_direccion;
                }
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result,"razon_social"=>$razon_social,"domicilio"=>$domicilio)));
    }

    public function guardar_checkout(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_rack', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_habitacion', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $fecha_hoy=date("Y-m-d");
                $id_rack=$_POST['id_rack'];
                $id_habitacion=$_POST['id_habitacion'];
                $data_rack=$this->hotel->listar_rack_por_id($id_rack);
                if($fecha_hoy<$data_rack->rack_out){
                    $fechaInicio=strtotime($fecha_hoy);
                    $fechaFin=strtotime($data_rack->rack_out);
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                        $this->hotel->eliminar_habitacion_estado($data_rack->id_habitacion,date('Y-m-d',$i));
                    }
                }else{
                    $this->hotel->eliminar_habitacion_estado($data_rack->id_habitacion,$data_rack->rack_out);
                }
                $result=$this->hotel->guardar_checkout($id_rack);
            } else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function guardar_add_checkout(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_rack', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_habitacion', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $id_rack=$_POST['id_rack'];
                $id_habitacion=$_POST['id_habitacion'];
                $new_checkout=$_POST['new_checkout'];
                $new_precio=$_POST['new_precio'];
                $noches = $_POST['noches_new'];
                $data_rack=$this->hotel->listar_rack_por_id($id_rack);
                //SE DEBERIA BUSCAR EL ULTIMO REGISTRO CON VALOR 1 PARA CAMBIARLO A 0 Y AGREGAR LAS FECHAS PROXIMAS
                $eliminar_fecha = $this->hotel->eliminar_valor_1($id_habitacion);
                $fechaInicio=strtotime($data_rack->rack_out) * 1;
                $fechaFin=strtotime($new_checkout);
                for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                    $fechita = date("Y-m-d", $i);
                    $moneda = 1;
                    if($fechita!=$new_checkout){
                        $model2 = new Hotel();
                        $model2->rack_detalle_fecha= $fechita;
                        $model2->id_rack=$id_rack;
                        $model2->id_producto=211;
                        $model2->rack_detalle_cantidad=1;
                        $model2->rack_detalle_preciounit=$new_precio;
                        $model2->rack_detalle_subtotal=$new_precio;
                        $model2->rack_detalle_estado_fact=0;
                        $result=$this->hotel->guardar_detalle_rack($model2);
                        $this->hotel->guardar_habitacion_estado($id_habitacion,$fechita,2,0,$moneda);
                    }else{
                        $this->hotel->guardar_habitacion_estado($id_habitacion,$fechita,2,1,$moneda);
                    }
                }
                $result=$this->hotel->actualizar_rack_out_n($new_checkout,$noches,$id_rack);
            } else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function ver_productos(){
        $result = "<option value=''>Seleccione</option>";
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('tipo', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $tipo = $_POST['tipo'];
                $data = $this->hotel->ver_productos($tipo);
                foreach ($data as $d){
                    $result.="<option value='$d->id_producto///$d->producto_precio_valor'>$d->producto_nombre</option>";
                }
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode($result);
    }

    public function consultar_rack_detalle(){
        $result=array();
        try{
            $cadena = explode("--/",$_POST['cadena']);
            $total=0;$a=1;
            foreach ($cadena as $c){
                if($c!=""){
                    $d=$this->hotel->listar_detalle_rack_id($c);
                    $result[]=array(
                        'id'=>$a,
                        'id_rack_detalle'=>$d->id_rack_detalle,
                        'producto'=>$d->producto_nombre,
                        'id_producto_precio'=>$d->id_producto,
                        'cantidad'=>$d->rack_detalle_cantidad,
                        'precio'=>$d->rack_detalle_preciounit,
                        'subtotal'=>$d->rack_detalle_subtotal,
                        'precio_final'=>$d->rack_detalle_preciounit
                    );
                    $a++;
                }
            }
        } catch (Exception $e){$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);}
        //echo json_encode($result);
        echo json_encode(array("result" => $result));
    }

    public function consultar_rack_detalle_cargo(){
        $result=array();
        try{
            $cadena = explode("--/",$_POST['cadena']);
            $total=0;$a=1;
            foreach ($cadena as $c){
                if($c!=""){
                    //CONSULTA DE CUENTA DETALLE
                    $sacar_valor = $this->cuentascobrar->sacar_valor($c);
                    //ACA SE SACARA LOS DATOS DE LA TABLA DETALLE PARA SABER SI SON DE HOTEL O DE RESTAURANTE
                    if(empty($sacar_valor->id_rack_detalle)){
                        $bc = $this->cuentascobrar->buscar_comanda_detalle($sacar_valor->id_comanda_detalle);
                        $result[]=array(
                            'id'=>$a,
                            'id_cuenta_detalle'=>$sacar_valor->id_cuenta_detalle,
                            'id_comanda_detalle'=>$bc->id_comanda_detalle,
                            'producto'=>$bc->producto_nombre,
                            'id_producto_precio'=>$bc->id_producto,
                            'cantidad'=>$bc->comanda_detalle_cantidad,
                            'precio'=>$bc->comanda_detalle_precio,
                            'subtotal'=>$bc->comanda_detalle_total,
                            'precio_final'=>$bc->comanda_detalle_precio
                        );
//                        $result.="<tr><td>$a</td><td><input id='cad_texto_$c' type='text' class='form-control' value='$bc->producto_nombre'></td><td>$bc->comanda_detalle_cantidad</td><td>$bc->comanda_detalle_precio</td><td>$bc->comanda_detalle_total</td></tr>";
//                        $total+=$bc->comanda_detalle_total;
                    }else{
                        $br = $this->cuentascobrar->buscar_rack_detalle($sacar_valor->id_rack_detalle);
                        $result[]=array(
                            'id'=>$a,
                            'id_cuenta_detalle'=>$sacar_valor->id_cuenta_detalle,
                            'id_rack_detalle'=>$br->id_rack_detalle,
                            'producto'=>$br->producto_nombre,
                            'id_producto_precio'=>$br->id_producto,
                            'cantidad'=>$br->rack_detalle_cantidad,
                            'precio'=>$br->rack_detalle_preciounit,
                            'subtotal'=>$br->rack_detalle_subtotal,
                            'precio_final'=>$br->rack_detalle_preciounit
                        );

//                        $result.="<tr><td>$a</td><td><input id='cad_texto_$c' type='text' class='form-control' value='$br->producto_nombre'></td><td>$br->rack_detalle_cantidad</td><td>$br->rack_detalle_preciounit</td><td>$br->rack_detalle_subtotal</td></tr>";
//                        $total+=$br->rack_detalle_subtotal;
                    }

                }
                $a++;
            }
            //$result.="<tr><td colspan=\"4\" class=\"text-center\" style=\"font-size: 18pt;font-weight: bold\">TOTAL</td><td><span id='totalito' style=\"font-size: 18pt;font-weight: bold\">".round($total,2)."</span><input id='totalito_inpu' type='hidden' value='".round($total,2)."'></td></tr>";
        } catch (Exception $e){$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);}
        //echo json_encode($result);
        echo json_encode(array("result" => $result));
    }
    //FUNCION ANTIGUA
    public function consultar_rack_detalle_(){
        $result = "";
        try{
            $cadena = explode("--/",$_POST['cadena']);
            $total=0;$a=1;
            foreach ($cadena as $c){
                if($c!=""){
                    $d=$this->hotel->listar_detalle_rack_id($c);
                    $result.="<tr><td>$a</td><td><input id='cad_texto_$c' type='text' class='form-control' value='$d->producto_nombre'></td><td>$d->rack_detalle_cantidad</td><td><label id='precio_unit_$a'>$d->rack_detalle_preciounit</label></td><td>$d->rack_detalle_subtotal</td></tr>";
                    $total+=$d->rack_detalle_subtotal;
                    $a++;
                }

            }
            $result.="<tr><td colspan=\"4\" class=\"text-center\" style=\"font-size: 18pt;font-weight: bold\">TOTAL</td><td><span id='totalito' style=\"font-size: 18pt;font-weight: bold\">".round($total,2)."</span><input id='totalito_inpu' type='hidden' value='".round($total,2)."'></td></tr>";
        } catch (Exception $e){$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);}
        //echo json_encode($result);
        echo json_encode(array("result" => $result,"total"=>$total));
    }

    //FUNCION ANTIGUA COMO EN EL CASO ANTERIOR
    public function consultar_rack_detalle_cargo_(){
        $result = "";
        try{
            $cadena = explode("--/",$_POST['cadena']);
            $total=0;$a=1;
            foreach ($cadena as $c){
                if($c!=""){
                    //CONSULTA DE CUENTA DETALLE
                    $sacar_valor = $this->cuentascobrar->sacar_valor($c);
                    //ACA SE SACARA LOS DATOS DE LA TABLA DETALLE PARA SABER SI SON DE HOTEL O DE RESTAURANTE
                    if(empty($sacar_valor->id_rack_detalle)){
                        $bc = $this->cuentascobrar->buscar_comanda_detalle($sacar_valor->id_comanda_detalle);
                        $result.="<tr><td>$a</td><td><input id='cad_texto_$c' type='text' class='form-control' value='$bc->producto_nombre'></td><td>$bc->comanda_detalle_cantidad</td><td>$bc->comanda_detalle_precio</td><td>$bc->comanda_detalle_total</td></tr>";
                        $total+=$bc->comanda_detalle_total;
                    }else{
                        $br = $this->cuentascobrar->buscar_rack_detalle($sacar_valor->id_rack_detalle);
                        $result.="<tr><td>$a</td><td><input id='cad_texto_$c' type='text' class='form-control' value='$br->producto_nombre'></td><td>$br->rack_detalle_cantidad</td><td>$br->rack_detalle_preciounit</td><td>$br->rack_detalle_subtotal</td></tr>";
                        $total+=$br->rack_detalle_subtotal;
                    }
                }
                $a++;
            }
            $result.="<tr><td colspan=\"4\" class=\"text-center\" style=\"font-size: 18pt;font-weight: bold\">TOTAL</td><td><span id='totalito' style=\"font-size: 18pt;font-weight: bold\">".round($total,2)."</span><input id='totalito_inpu' type='hidden' value='".round($total,2)."'></td></tr>";
        } catch (Exception $e){$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);}
        echo json_encode($result);
    }

    public function habilitar_habitacion(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_habitacion', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $id_habitacion = $_POST['id_habitacion'];
                $fecha = $_POST['fecha'];
                $dias = $this->hotel->listar_deshabilitados_fecha_id($id_habitacion,$fecha);
                    foreach($dias as $d){
                        $this->hotel->eliminar_habitacion_estado($id_habitacion,$d->deshabilitado_fecha);
                        $this->hotel->eliminar_habitacion_deshabilitado($id_habitacion,$d->deshabilitado_fecha);
                    }
                    $result=1;
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }

    //FUNCION PARA CARGAR CUENTA
    public function cargar_datos_cuenta(){
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            if ($ok_data) {
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $id_rack=$_POST['id_rack'];
                $sacar_dolar = $this->cuentascobrar->sacar_dolar($id_usuario);
                $sacar_tipo_moneda = $this->cuentascobrar->sacar_tipo_moneda($id_rack);
                $moneda_raiz = $sacar_tipo_moneda->id_moneda;
                //AQUI VALIDAREMOS SI YA EXISTE UN REGISTRO PENDIENTE DE PAGO CON ESE CLIENTE, SI NO EXISTE CREAMOS UN NUEVO REGISTRO, SI EXISTE SUMAMOS AL REGISTRO ANTIGUO
                $revisar_cuenta_pasada = $this->cuentascobrar->revisar_cuenta_pasada($_POST['id_cliente']);
                $moneda_destino = $revisar_cuenta_pasada->id_moneda;
                $cadena = explode("--/",$_POST['cadenita']);
                //VARIABLE NUEVA DEL CAMBIO
                $variable_detalle = json_decode($_POST['variable']);
                $total=0;
                foreach ($variable_detalle as $c){
                    $total+=$c->subtotal;
                    /*if($c!="") {
                        $d = $this->hotel->listar_detalle_rack_id_cuentacargo($c);
                        $total+=$d->rack_detalle_subtotal;
                    }*/
                }
                if(empty($revisar_cuenta_pasada)){
                    //LLENAMOS LA TABLA DE LA CABECERA
                    $model = new Hotel();
                    $model->id_usuario = $id_usuario;
                    $model->id_cliente = $_POST['id_cliente'];
                    $model->cuentas_total = $total;
                    $model->cuentas_total_pagado = 0.00;
                    $model->cuenta_fecha_creacion = date('Y-m-d H:i:s');
                    $model->cuenta_cancelado = 0;
                    $model->cuenta_estado = 1;
                    $microtime_ = microtime();
                    $model->cuenta_codigo = $microtime_;
                    $model->id_moneda=$sacar_tipo_moneda->id_moneda;
                    $result = $this->pedido->guardar_cuenta_por_cobrar($model);
                    if ($result == 1) {
                        //LLENAMOS LA TABLA DEL CUERPO O DETALLE
                        foreach ($variable_detalle as $c) {
                            if($c!=""){
                                $id_cuenta = $this->pedido->sacar_id_cuenta($microtime_);
                                $model2 = new Hotel();
                                $model2->id_cuenta = $id_cuenta->id_cuenta;
                                $model2->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                                $model2->id_rack_detalle = $c->id_rack_detalle;
                                $model2->id_comanda_detalle = NULL;
                                $model2->cuentas_detalle_comanda_correlativo = NULL;
                                $model2->cuentas_detalle_fecha_creacion = date('Y-m-d H:i:s');
                                $model2->cuenta_detalle_estado = 1;
                                $result = $this->pedido->guardar_detalle_por_cobrar($model2);
                                $appdeit_estado = $this->cuentascobrar->uppdeit_estado($c->id_rack_detalle);
                                $envio = 0;
                            }
                        }
                        if($result==1){
                            $verificar = $this->hotel->verificar_rack_detalle($id_rack);
                            if(empty($verificar)){
                                $envio = 1;
                                $fecha_hoy=date("Y-m-d");
                                //$id_habitacion=$_POST['id_habitacion'];
                                $data_rack=$this->hotel->listar_rack_por_id($id_rack);
                                if($fecha_hoy<$data_rack->rack_out){
                                    $fechaInicio=strtotime($fecha_hoy);
                                    $fechaFin=strtotime($data_rack->rack_out);
                                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                                        $this->hotel->eliminar_habitacion_estado($data_rack->id_habitacion,date('Y-m-d',$i));
                                    }
                                }else{
                                    $this->hotel->eliminar_habitacion_estado($data_rack->id_habitacion,$data_rack->rack_out);
                                }
                                $result=$this->hotel->guardar_checkout($id_rack);
                            }
                        }else{
                            $result=9;
                        }
                    }
                }else{
                    $id_cuenta=$revisar_cuenta_pasada->id_cuenta;
                    //LLENAMOS LA TABLA DEL CUERPO O DETALLE
                    foreach ($variable_detalle as $c) {
                        if($c!="") {
                            $model2 = new Hotel();
                            $model2->id_cuenta = $id_cuenta;
                            $model2->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                            $model2->id_rack_detalle = $c->id_rack_detalle;
                            $model2->id_comanda_detalle = NULL;
                            $model2->cuentas_detalle_comanda_correlativo = NULL;
                            $model2->cuentas_detalle_fecha_creacion = date('Y-m-d H:i:s');
                            $model2->cuenta_detalle_estado = 1;
                            $result = $this->pedido->guardar_detalle_por_cobrar($model2);
                            $appdeit_estado = $this->cuentascobrar->uppdeit_estado($c->id_rack_detalle);
                            //AQUI PUDIERAMOS ACTUALIZAR EL VALOR DE CAMBIO DE MONEDA PARA QUE LOS DETALLES CUADREN DE ACUERDO AL MONTO GENERADO
                            $valor_dolar =$sacar_dolar->tipo_cambio;
                            if($moneda_raiz == $moneda_destino){
                               //EN CASO DE SER IGUAL NO SE REALIZA NINGUNA MODIFICACION Y SE SIGUE EL PROCESO DE FORMA NORMAL
                            }else{
                                if($moneda_raiz==1 && $moneda_destino == 2){
                                    $monto_unitario=$c->precio / $valor_dolar;
                                    $monto_subtotal=$c->subtotal / $valor_dolar;
                                    $monto_unitario_=round($monto_unitario,3);
                                    $monto_subtotal_=round($monto_subtotal,3);
                                }else{
                                    $monto_unitario=$c->precio * $valor_dolar;
                                    $monto_subtotal=$c->subtotal * $valor_dolar;
                                    $monto_unitario_=round($monto_unitario,3);
                                    $monto_subtotal_=round($monto_subtotal,3);
                                }
                                $actualizar_precios_x_moneda = $this->cuentascobrar->actualizar_montos_moneda($monto_unitario_,$monto_subtotal_,$c->id_rack_detalle);
                            }
                            $envio = 0;
                        }
                    }
                    if($result==1){
                        $valor_dolar =$sacar_dolar->tipo_cambio;
                        //AQUI HAREMOS UNA VALIDACUON DE MONEDA, SI LA MONEDA DE RAIZ ES IGUAL A LA QUE VIENE SE SUMA NORMAL SINO SE REALIZA LA CONVERSION
                        if($moneda_raiz == $moneda_destino){
                            //LUEGO SE PROCEDE ACTUALIZAR LA SUMA DE LA CUENTA SI AMBOS TIPOS DE MONEDA SON IGUALES
                            $sumar_cuenta = $this->cuentascobrar->sumar_cuenta($total,$id_cuenta);
                        }else{
                            //SINO TRANSFORMAMOS EL TOTAL AL TIPO DE MONEDA DE LA CUENTA DE DESTINO
                            if($moneda_raiz==1 && $moneda_destino == 2){
                                $total_nuevo= $total / $valor_dolar;
                                $total_nuevo_ = round($total_nuevo,3);
                            }else{
                                $total_nuevo= $total * $valor_dolar;
                                $total_nuevo_ = round($total_nuevo,3);
                            }
                            $sumar_cuenta = $this->cuentascobrar->sumar_cuenta($total_nuevo_,$id_cuenta);
                        }
                        //PROCEDEREMOS A VERIFICAR SI TODAS LOS DETALLES DEL RACK HAN SIDO ENVIADOS
                        $verificar = $this->hotel->verificar_rack_detalle($id_rack);
                        if(empty($verificar)){
                            $envio = 1;
                            $fecha_hoy=date("Y-m-d");
                            //$id_habitacion=$_POST['id_habitacion'];
                            $data_rack=$this->hotel->listar_rack_por_id($id_rack);
                            if($fecha_hoy<$data_rack->rack_out){
                                $fechaInicio=strtotime($fecha_hoy);
                                $fechaFin=strtotime($data_rack->rack_out);
                                for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                                    $this->hotel->eliminar_habitacion_estado($data_rack->id_habitacion,date('Y-m-d',$i));
                                }
                            }else{
                                $this->hotel->eliminar_habitacion_estado($data_rack->id_habitacion,$data_rack->rack_out);
                            }
                            $result=$this->hotel->guardar_checkout($id_rack);
                        }else{
                            $result=7;
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
        echo json_encode(array("result" => array("code" => $result, "message" => $message,"envio"=>$envio)));
    }

    //FUNCION PARA GUARDAR EL PRECIO EDITADO
    public function guardar_precio_editado(){
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_rack_detalle_editar', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $id_rack_detalle = $_POST['id_rack_detalle_editar'];
                $precio_actual = $_POST['precio_actual'];
                $eliminar_clave = $_POST['contra_editar'];
                $cantidad_editada = $_POST['cantidad_editada'];
                $subtotal= $precio_actual * $cantidad_editada;
                $clave_tipo=2;
                $id_comanda_detalle="";
                $pass_validation=false;
                $id_user = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $existe_eliminacion = $this->hotel->existe_clave_eliminacion();
                if(!empty($existe_eliminacion)){
                    if($eliminar_clave==$existe_eliminacion->clave_eliminacion_clave){
                        $pass_validation=true;
                        $this->hotel->usar_clave_eliminacion($id_user,$id_rack_detalle,$id_comanda_detalle,$clave_tipo,$existe_eliminacion->id_clave_eliminacion);
                    }
                }else{
                    $result=3;
                }
                if($pass_validation){
                    $result=$this->hotel->cambiar_precio_detalle($precio_actual,$subtotal,$id_rack_detalle);
                }
            }else {
                $result = 6;
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //NUEVA FUNCION PARA LOS COMPLEMENTARY
    public function cargar_datos_comple(){
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            if ($ok_data) {
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $id_rack=$_POST['id_rack'];
                //VARIABLE NUEVA DEL CAMBIO
                $variable_detalle = json_decode($_POST['variable']);
                foreach ($variable_detalle as $c) {
                    if($c!=""){
                        $result = $this->hotel->act_estado($c->id_rack_detalle);
                    }
                }
                //SE BUSCARIA SI EXISTEN MAS REGISTROS PENDIENTES DE PAGO
                $buscar_registros = $this->hotel->buscar_registros($id_rack);
                if($buscar_registros->total > 0){
                    $envio=0;
                }else{
                    $envio=1;
                    if($envio==1){
                        //$result=$this->hotel->guardar_checkout($id_rack);
                    }else{
                       $result=9;
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
        echo json_encode(array("result" => array("code" => $result, "message" => $message,"envio"=>$envio)));
    }


}
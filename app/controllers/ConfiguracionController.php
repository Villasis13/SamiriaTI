<?php
require 'app/models/Configuracion.php';
require 'app/models/Hotel.php';
require 'app/models/Usuario.php';
class ConfiguracionController{
    //Variables especificas del controlador
    private $configuracion;
    private $hotel;
    private $usuario;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    public function __construct(){
        //Instancias especificas del controlador
        $this->configuracion = new Configuracion();
        $this->hotel = new Hotel();
        $this->usuario = new Usuario();
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'configuracion/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function habitaciones(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $tipos_hab = $this->hotel->listar_tipos_habitacion();
            $habitaciones = $this->hotel->listar_habitaciones();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'configuracion/habitaciones.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function productos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $datos=false;
            $categorias=$this->hotel->listar_categorias();
            $proveedores=$this->hotel->listar_proveedores();
            $medidas=$this->hotel->listar_medidas();
            if(isset($_GET['id'])){$buscar=$_GET['id'];$datos=true;}else{$buscar=1;}
            if($buscar==1){
                $productos = $this->hotel->listar_productos();
            }else{
                $productos = $this->hotel->buscar_productos($buscar);
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'configuracion/productos.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function cajas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $cajas = $this->hotel->listar_cajas();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'configuracion/cajas.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function asistencia(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'configuracion/asistencia.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function comandas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $comandas = $this->hotel->listar_comandas();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'configuracion/comandas.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function eliminados(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $eliminados = $this->hotel->listar_eliminados();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'configuracion/eliminados.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function guardar_habitacion(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('nro', 'POST',true,$ok_data,100,'texto',0);
            if($ok_data){
                $result = $this->configuracion->guardar_habitacion($_POST['id'],$_POST['nro']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function guardar_caja(){
        $result = 2;
        try{
            if(isset($_POST['id_caja'])){
                $result = $this->configuracion->guardar_caja($_POST['id_caja'],$_POST['caja_nombre'],$_POST['caja_estado']);
            }else{
                $result = $this->configuracion->guardar_caja(0,$_POST['caja_nombre'],1);
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function guardar_producto(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_categoria', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('nombre', 'POST',true,$ok_data,100,'texto',0);
            if($ok_data){
                if(isset($_POST['id_producto'])){
                    $result = $this->configuracion->editar_producto($_POST['id_categoria'],$_POST['codigo'],$_POST['nombre'],$_POST['descripcion'],$_POST['stock'],$_POST['stock_minimo'],$_POST["id_producto"]);
                    if($result==1){
                        $result=$this->configuracion->editar_producto_precio($_POST['id_producto'],$_POST['id_proveedor'],$_POST['id_medida'],$_POST['precio_venta'],$_POST['precio_venta_mayor'],$_POST['precio_venta_compra']);
                    }
                }else{
                    $id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                    $mt=microtime(true);
                    $result = $this->configuracion->guardar_producto($_POST['id_categoria'],$id_usuario,$_POST['codigo'],$_POST['nombre'],$_POST['descripcion'],$_POST['stock'],$_POST['stock_minimo'],$mt);
                    if($result==1){
                        $data = $this->hotel->listar_producto_por_mt($mt);
                        $result=$this->configuracion->guardar_producto_precio($data->id_producto,$_POST['id_proveedor'],$_POST['id_medida'],'20',1,$_POST['precio_venta'],$_POST['precio_venta_mayor'],$_POST['precio_compra']);
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
    public function producto_por_id(){
        $result = 2;$data = [];
        try{
            $data = $this->configuracion->listar_producto($_POST['id']);
            if(count($data)>0){
                $result=1;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result,"data"=>$data)));
    }
    public function validar_csv(){
        $result = 2;$data = [];$tabla ="";$data_subir=[];
        try{
            if($_FILES['archivo_csv']['name'] !=null) {
                $path = $_FILES['archivo_csv']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_path = "media/csvs/".date('YmdHis').".".$ext;
                if($ext=="csv"){
                    if(move_uploaded_file($_FILES['archivo_csv']['tmp_name'],$file_path)){
                        $file = fopen( $file_path, "r");
                        while (!feof($file)) {
                            $data[] = fgetcsv($file,null,';');
                        }
                        fclose($file);
                        if(count($data)>0){
                            $a=1;
                            foreach ($data as $d){
                                if($a<count($data)) {
                                    if($a==1){
                                        $tmre = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $d[0]);
                                        $d[0]=$tmre;
                                    }
                                    $id_personal =trim($d[0]);
                                    $fecha = $d[1];
                                    $tipo = $d[2];
                                    switch ($tipo) {
                                        case "Ingreso":$tipo_id = 1;break;
                                        case "Almuerzo entrada":$tipo_id =2 ;break;
                                        case "Almuerzo salida":$tipo_id = 3;break;
                                        case "Salida":$tipo_id = 4;break;
                                    }
                                    $hora = $d[3];
                                    $data_persona = $this->hotel->listar_personal($id_personal);
                                    $nombre = $data_persona->persona_nombre . " " . $data_persona->persona_apellido_paterno . " " . $data_persona->persona_apellido_materno;
                                    $dni = $data_persona->persona_dni;
                                    $cargo = $data_persona->personal_cargo;
                                    $tabla .= "<tr><td>$fecha</td><td>$nombre</td><td>$dni</td><td>$cargo</td><td>$tipo</td><td>$hora</td></tr>";
                                    $datita=[$d[0],$d[1],$tipo_id,$d[3],"//--"];
                                    array_push($data_subir,$datita);
                                    $a++;
                                }
                            }
                            $result=1;
                        }else{
                            $result=5;
                        }
                    }
                }else{
                    $result=4;
                }
            }else{
                $result=3;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result,"data"=>$data,"tabla"=>$tabla,"data_subir"=>$data_subir)));
    }
    public function guardar_tipo_habitacion(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('nombre', 'POST',true,$ok_data,100,'texto',0);
            if($ok_data){
                $result = $this->configuracion->guardar_tipo_habitacion($_POST['nombre'],$_POST['detalle'],$_POST['soles'],$_POST['dolares']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function editar_tipo_habitacion(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('nombre_e', 'POST',true,$ok_data,100,'texto',0);
            if($ok_data){
                $result = $this->configuracion->editar_tipo_habitacion($_POST['nombre_e'],$_POST['detalle_e'],$_POST['soles_e'],$_POST['dolares_e'],$_POST['id']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function editar_habitacion(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_habitacion_e', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_habitacion_tipo_e', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('nro_habitacion_e', 'POST',true,$ok_data,100,'texto',0);
            if($ok_data){
                $result = $this->configuracion->editar_habitacion($_POST['id_habitacion_tipo_e'],$_POST['nro_habitacion_e'],$_POST['id_habitacion_e']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function eliminar_habitacion(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $result = $this->configuracion->eliminar_habitacion($_POST['id']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function eliminar_producto(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $result = $this->configuracion->eliminar_producto_precio($_POST['id']);
                if($result=1){
                    $result = $this->configuracion->eliminar_producto($_POST['id']);
                }
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function eliminar_habitacion_tipo(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $result = $this->configuracion->eliminar_habitacion_tipo($_POST['id']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function guardar_config_comanda(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('comandas_nombre', 'POST',true,$ok_data,300,'texto',0);
            $ok_data = $this->validar->validar_parametro('comandas_numero', 'POST',true,$ok_data,100,'texto',0);
            if($ok_data){
                $result = $this->configuracion->guardar_config_comanda($_POST['comandas_nombre'],$_POST['comandas_fecha'],$_POST['comandas_numero']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function eliminar_config_comanda(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $result = $this->configuracion->eliminar_config_comanda($_POST['id']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function eliminar_caja(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $result = $this->configuracion->eliminar_caja($_POST['id']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function guardar_asistencias(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('data', 'POST',true,$ok_data,10000,'texto',0);
            if($ok_data){
                $data = explode("//--",$_POST["data"]);
                $a=1;
                $cont = count($data);
                foreach ($data as $dat){
                    if($a<$cont){
                        if($a==1){
                            $d=explode(",",$dat);
                            $result = $this->configuracion->guardar_asistencia($d[0],$d[1],$d[2],$d[3]);
                        }else{
                            $d=explode(",",$dat);
                            $result = $this->configuracion->guardar_asistencia($d[1],$d[2],$d[3],$d[4]);
                        }
                    }
                    $a++;
                }
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function buscar_asistencias(){
        $result = 2;$tabla = "";
        try{
            $desde = $_POST["desde"];
            $hasta = $_POST["hasta"];
            $data=$this->hotel->buscar_asistencias($desde,$hasta);
            foreach ($data as $d){
                $id_personal =$d->id_personal;
                $fecha = $d->asistencia_fecha;
                $tipo = $d->asistencia_tipo;
                switch ($tipo) {
                    case 1:$tipo_id = "Ingreso";break;
                    case 2:$tipo_id ="Almuerzo entrada" ;break;
                    case 3:$tipo_id = "Almuerzo salida";break;
                    case 4:$tipo_id = "Salida";break;
                }
                $hora = $d->asistencia_hora;
                $data_persona = $this->hotel->listar_personal($id_personal);
                $nombre = $data_persona->persona_nombre . " " . $data_persona->persona_apellido_paterno . " " . $data_persona->persona_apellido_materno;
                $dni = $data_persona->persona_dni;
                $cargo = $data_persona->personal_cargo;
                $tabla .= "<tr><td>$fecha</td><td>$nombre</td><td>$dni</td><td>$cargo</td><td>$tipo_id</td><td>$hora</td></tr>";
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        echo json_encode(array("result" => $tabla));
    }
}

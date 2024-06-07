<?php
require 'app/models/Pedido.php';
require 'app/models/Archivo.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Acompanamiento.php';

class AcompanamientoController
{
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $archivo;
    private $usuario;
    private $rol;
    private $acompanamiento;

    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->archivo = new Archivo();
        $this->usuario = new Usuario();
        $this->rol = new Rol();
        $this->acompanamiento = new Acompanamiento();
    }

    //VISTAS
    public function gestionar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            //$id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            $product = $this->acompanamiento->lista_productos();

            $recursos = $this->acompanamiento->listar_recursos();
            $acompas = $this->acompanamiento->listar_acompas();
            $detalle = $this->acompanamiento->listar_detalles_acompa();
            $unidad_medida = $this->acompanamiento->listar_unidad_medida();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'acompanamiento/gestionar.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }


    //FUNCIONES
    public function guardar_acompa(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $model = new Acompanamiento();
                $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $model->id_producto = $_POST['id_producto'];
                $model->acompanamiento_texto = $_POST['acompanamiento_texto'];
                $model->acompanamiento_fecha = date('Y-m-d H:i:s');
                $model->acompanamiento_estado = 1;
                $result = $this->acompanamiento->guardar_acompa($model);
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function guardar_detalle_acompa(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            if ($ok_data) {
                $model = new Acompanamiento();
                $model->id_acompanamiento = $_POST['id_acompanamiento'];
                $model->detalle_texto = $_POST['detalle_texto'];
                $model->detalle_fecha = date('Y-m-d H:i:s');
                $model->detalle_estado = 1;
                $micro = microtime();
                $model->microtime = $micro;
                $result = $this->acompanamiento->guardar_detalle_acompa($model);
                //NUEVAS FUNCIONES PARA LOS GUARDADOS LLENAR LAS TABLAS DE RECETAS
                if($result==1){
                    $obtener_id=$this->acompanamiento->id_detalle($micro);
                    $model_ = new Acompanamiento();
                    $model_->id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                    $model_->id_acompanamiento_detalle=$obtener_id->id_acompa_detalle;
                    $model_->receta_preferencia_fecha = date('Y-m-d H:i:s');
                    $model_->receta_preferencia_estado = 1;
                    $micro_ = microtime();
                    $model_->rec_microtime = $micro_;
                    $result=$this->acompanamiento->guardar_nueva_receta($model_);
                    if($result==1){
                        $obtener_id_receta_pre = $this->acompanamiento->obtener_id_receta_pre($micro_);
                        //AQUI SE GUARDARA EL DETALLE DE LA RECETA NUEVA CREADA PARA LAS PREFERENCIAS
                        $modelito = new Acompanamiento();
                        $modelito->id_receta_preferencia = $obtener_id_receta_pre->id_receta_preferencia;
                        $modelito->id_usuario=$this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                        if(empty($_POST['recurso'])){
                            $modelito->id_recurso = 0;
                        }else{
                            $modelito->id_recurso = $_POST['recurso'];
                        }
                        if(empty($_POST['cantidad'])){
                            $modelito->cantidad = 0;
                        }else{
                            $modelito->cantidad = $_POST['cantidad'];
                        }
                        if(empty($_POST['unidad_medida'])){
                            $modelito->id_medida = NULL;
                        }else{
                            $modelito->id_medida = $_POST['unidad_medida'];
                        }
                        
                        $modelito->preferencia_detalle_fecha = date('Y-m-d H:i:s');
                        $modelito->preferencia_detalle_estado = 1;
                        $result=$this->acompanamiento->guardar_detalle_preferncia_receta($modelito);
                    }
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
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function consultar_datos_acompa(){
        try{
            $id_acompanamiento = $_POST['id_acompanamiento'];
            $buscar_los_detalles = $this->acompanamiento->buscar_los_detalles($id_acompanamiento);

            $detallitos = " <table class='table table-bordered' width='100%'>
                                    <thead class='text-capitalize'>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción / Recurso</th>
                                        <th>Cantidad</th>
                                        <th>Unidad Medida</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>";
            $a = 1;
            foreach ($buscar_los_detalles as $b){
                $buscar_cantidad=$this->acompanamiento->buscar_cantidad($b->id_acompa_detalle);
                if($buscar_cantidad->cantidad != 0.00){
                    $cantidad=$buscar_cantidad->cantidad;
                }else{
                    $cantidad='----';
                }
                if($buscar_cantidad->id_medida != NULL){
                    $buscar_medidita = $this->acompanamiento->buscar_medidita($buscar_cantidad->id_medida);
                    $medida= $buscar_medidita->medida_nombre;
                }else{
                    $medida="----";
                }
                $detallitos .= "<tr>
                            <td>". $a ."</td>
                            <td>". $b->detalle_texto ."</td>
                            <td>". $cantidad ."</td>
                            <td>". $medida ."</td>
                            <td>
                                <a style='color: red' data-toggle='tooltip' title='Eliminar' onclick='preguntar(\"¿Esta seguro de eliminar este registro?\",\"eliminar_detalle_acompa\",\"Si\",\"No\",\".$b->id_acompa_detalle.\")'>
                                <i class='fa fa-times eliminar margen'></i></a>
                            </td>
                        </tr>";
                $a++;
            }
            $detallitos .= "</tbody></table>";
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
        //Retornamos el json
        echo json_encode(array("tabla_detalles"=>$detallitos));
    }

    //eliminar los acompanamientos
    public function eliminar_detalle_acompa_(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id_acompa_detalle', 'POST', true, $ok_data, 11, 'numero', 0);
            if ($ok_data) {
                $capturar_id = $this->acompanamiento->capturar_id($_POST['id_acompa_detalle']);
                if(!empty($capturar_id)){
                    $result = 1;
                    foreach ($capturar_id as $ca){
                        $id_receta_preferencia = $ca->id_receta_preferencia;
                        if($result==1){
                            $result = $this->acompanamiento->eliminar_receta_preferencia_detalle($id_receta_preferencia);
                        }else{
                            $result=2;
                            $message = "Hubo error al eliminar";
                        }
                    }
                }
                if($result==1){
                    $result = $this->acompanamiento->eliminar_receta_preferencia($_POST['id_acompa_detalle']);
                    if($result==1){
                        $result = $this->acompanamiento->eliminar_acompa_detalle($_POST['id_acompa_detalle']);
                    }
                }
            }else{
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }


//    //FUNCION DE GUARDAR TODO LO NUEVO
//    public function guardar_acompa_recur_new(){
//        $result = 2;
//        $message = 'OK';
//        try{
//            $ok_data = true;
//            if($ok_data){
//
//            }else{
//                $result = 6;
//                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
//            }
//        }catch (Exception $e) {
//            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
//            $message = $e->getMessage();
//        }
//        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
//    }

}
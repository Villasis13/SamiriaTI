<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 29/10/2020
 * Time: 10:18

 OTA WEB, DIRECTO
 */
require 'app/models/Reserva.php';
require 'app/models/Builder.php';
require 'app/models/Hotel.php';
class ReservaController{
    //Variables especificas del controlador
    private $reserva;
    private $hotel;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $builder;
    public function __construct()
    {
        //Instancias especificas del controlador
        $this->reserva = new Reserva();
        $this->hotel = new Hotel();
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->builder = new Builder();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $fecha_busqueda = date('Y-m-d');
            if(isset($_GET['id'])){
                $fecha_busqueda = $_GET['id'];
            }
            $reservas = $this->reserva->listar_reservas($fecha_busqueda);
            $tipos_hab = $this->hotel->listar_tipos_habitacion();
			$origen = $this->hotel->listar_origen();
			$hab1 = $this->reserva->listar_hab1();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reserva/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function reporte_excel(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $fecha_busqueda = date('Y-m-d');
            if(isset($_GET['id'])){
                $fecha_busqueda = $_GET['id'];
            }
            $reservas = $this->reserva->listar_reservas($fecha_busqueda);
            $tipos_hab = $this->hotel->listar_tipos_habitacion();
            header( "Content-Type: application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition: attachment; filename=Reporte Reservas - ".$fecha_busqueda.".xls");
            require _VIEW_PATH_ . 'reserva/reporte_excel.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    //Funciones
    public function guardar_reserva(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('nombre', 'POST',true,$ok_data,100,'texto',0);
            if($ok_data){
                //AQUI DEBEMOS REALIZAR EL CAMBIO PARA QUE CUANDO VENGAN MAS DE 2 REGISTROS EN UN MISMO FORMULARIO SE GUARDE POR SEPARADO
                $model = new Reserva();
                if($_POST['cant_tipo_habitacion']!=0){
                    $model->reserva_nombre = $_POST['nombre'];
                    $model->reserva_dni = $_POST['dni'];
                    $model->reserva_in = $_POST['desde'];
                    $model->reserva_out = $_POST['hasta'];
                    $model->id_tipo_habitacion = $_POST['id_tipo_hab'];
                    if(empty($_POST['cant_tipo_habitacion'])){
                        $model->cant_tipo_habitacion = 0;
                    }else{
                        $model->cant_tipo_habitacion = $_POST['cant_tipo_habitacion'];
                    }
                    if(empty($_POST['tarifa_tipo_habitacion'])){
                        $model->tarifa_tipo_habitacion = 0;
                    }else{
                        $model->tarifa_tipo_habitacion = $_POST['tarifa_tipo_habitacion'];
                    }
                    $model->reserva_obs = $_POST['reserva_obs'];
                    $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                    $model->reserva_datetime = date('Y-m-d H:i:s');
                    $model->reserva_contacto = $_POST['reserva_contacto'];
                    $model->reserva_origen = $_POST['reserva_origen'];
                    $model->reserva_numero = $_POST['reserva_numero'];
                    $model->reserva_estado = 1;
                    $result = $this->reserva->guardar_reserva($model);
                }
                if($_POST['cant_tipo_habitacion_2']!=0){
                    $model->reserva_nombre = $_POST['nombre'];
                    $model->reserva_dni = $_POST['dni'];
                    $model->reserva_in = $_POST['desde'];
                    $model->reserva_out = $_POST['hasta'];
                    $model->id_tipo_habitacion = $_POST['id_tipo_hab_2'];
                    if(empty($_POST['cant_tipo_habitacion_2'])){
                        $model->cant_tipo_habitacion = 0;
                    }else{
                        $model->cant_tipo_habitacion = $_POST['cant_tipo_habitacion_2'];
                    }
                    if(empty($_POST['tarifa_tipo_habitacion_2'])){
                        $model->tarifa_tipo_habitacion = 0;
                    }else{
                        $model->tarifa_tipo_habitacion = $_POST['tarifa_tipo_habitacion_2'];
                    }
                    $model->reserva_obs = $_POST['reserva_obs'];
                    $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                    $model->reserva_datetime = date('Y-m-d H:i:s');
                    $model->reserva_contacto = $_POST['reserva_contacto'];
                    $model->reserva_origen = $_POST['reserva_origen'];
                    $model->reserva_numero = $_POST['reserva_numero'];
                    $model->reserva_estado = 1;
                    $result = $this->reserva->guardar_reserva($model);
                }
                if($_POST['cant_tipo_habitacion_3']!=0){
                    $model->reserva_nombre = $_POST['nombre'];
                    $model->reserva_dni = $_POST['dni'];
                    $model->reserva_in = $_POST['desde'];
                    $model->reserva_out = $_POST['hasta'];
                    $model->id_tipo_habitacion = $_POST['id_tipo_hab_3'];
                    if(empty($_POST['cant_tipo_habitacion_3'])){
                        $model->cant_tipo_habitacion = 0;
                    }else{
                        $model->cant_tipo_habitacion = $_POST['cant_tipo_habitacion_3'];
                    }
                    if(empty($_POST['tarifa_tipo_habitacion_3'])){
                        $model->tarifa_tipo_habitacion = 0;
                    }else{
                        $model->tarifa_tipo_habitacion = $_POST['tarifa_tipo_habitacion_3'];
                    }
                    $model->reserva_obs = $_POST['reserva_obs'];
                    $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                    $model->reserva_datetime = date('Y-m-d H:i:s');
                    $model->reserva_contacto = $_POST['reserva_contacto'];
                    $model->reserva_origen = $_POST['reserva_origen'];
                    $model->reserva_numero = $_POST['reserva_numero'];
                    $model->reserva_estado = 1;
                    $result = $this->reserva->guardar_reserva($model);
                }
                }else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }
    public function eliminar_reserva(){
        $result = 2;
        try{
            $ok_data = true;
            $ok_data = $this->validar->validar_parametro('id', 'POST',true,$ok_data,11,'numero',0);
            if($ok_data){
                $result = $this->reserva->eliminar_reserva($_POST['id']);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result)));
    }
	public function edicion_reserva(){
		$ok_data = true;
		$result = 2;
		$message = 'OK';
		try{
			if($ok_data){
				$id = $_POST['guardarid'];
				$result = $this->reserva->listar_x_id($id);
			} else {
				$result = 6;
			}
		} catch (Exception $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
	public function editar_reserva(){
		$result = 2;
		$message = 'OK';
		try{
			$ok_data = true;
			if($ok_data){
				$id = $_POST['id_reserva_edit'];
				if($id != null){
					$cantidad_1 = $_POST['cliente_cantidad'];
					if($cantidad_1 == null || $cantidad_1 == 0){
						$result = 2;
					}else{
						$result = 1;
						$cantidad_2 = $_POST['cliente_cantidad_2'];
						if($cantidad_2 == null){
							$cantidad_2 = 0;
						}
						$cantidad_3 = $_POST['cliente_habitacion_3'];
						if($cantidad_3 == null){
							$cantidad_3 = 0;
						}
						$id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
						$reserva_datetime = date('Y-m-d H:i:s');
						if($result == 1){
							$result = $this->builder->update("reserva", array(
								"reserva_nombre" => $_POST['cliente_nombre'],
								"reserva_dni" => $_POST['cliente_dni'],
								"reserva_in" => $_POST['cliente_desde'],
								"reserva_out" => $_POST['cliente_hasta'],
								"id_tipo_habitacion" => $_POST['cliente_habitacion'],
								"cant_tipo_habitacion" => $cantidad_1,
								"tarifa_tipo_habitacion" => $_POST['cliente_tarifa'],
								"id_tipo_habitacion_2" => $_POST['cliente_habitacion_2'],
								"cant_tipo_habitacion_2" => $cantidad_2,
								"tarifa_tipo_habitacion_2" => $_POST['cliente_tarifa_2'],
								"id_tipo_habitacion_3" => $cantidad_3,
								"cant_tipo_habitacion_3" => $_POST['cliente_cantidad_3'],
								"tarifa_tipo_habitacion_3" => $_POST['cliente_tarifa_3'],
								"reserva_obs" => $_POST['cliente_observaciones'],
								"id_usuario" => $id_usuario,
								"reserva_datetime" => $reserva_datetime,
								"reserva_contacto" => $_POST['cliente_contacto'],
								"reserva_origen" => $_POST['cliente_origen'],
								"reserva_numero" => $_POST['cliente_numero']
							), array(
								"id_reserva" => $id
							));
						}
					}
				}
			}else {
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

    public function ver_reservas(){
        try{
            $result = $this->reserva->reservas_usarse();

            $tabla= " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>N° Documento</th>
                                            <th>Nombre Reserva</th>
                                            <th>Tipo Habitación</th>
                                            <th>Tarifa</th>
                                            <th>Fecha Reserva</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            foreach ($result as $r) {
                $tabla .= "  <tr class='AndreTabla'>
                                    <td style='padding: 0.5rem;vertical-align: middle; '>" . $r->reserva_dni . "</td>
                                    <td style='padding: 0.5rem;vertical-align: middle; ' >" . $r->reserva_nombre . "</td>
                                    <td style='padding: 0.5rem;vertical-align: middle; ' >" . $r->habitacion_tipo_nombre . "</td>
                                    <td style='padding: 0.5rem;vertical-align: middle; ' >" . $r->tarifa_tipo_habitacion . "</td>
                                    <td style='padding: 0.5rem;vertical-align: middle; ' >" . date('d-m-Y H:i:s',strtotime($r->reserva_datetime)) . "</td>
                                    <td style='padding: 0.5rem;vertical-align: middle; ' class='d-flex'>
                                        <button class='btn btn-success' data-toggle='modal' onclick='llenar_reserva(" . $r->id_reserva . ",\"" . $r->reserva_dni . "\",\"" . $r->tarifa_tipo_habitacion. "\",\"" . $r->reserva_in. "\",\"" . $r->reserva_out . "\",\"" . $r->reserva_origen . "\")' data-target='#asignar_pedido'><i class='fa fa-check'></i> </button>
                                        <input type='hidden' id='" . $r->producto_precio_codigoafectacion . "'>
                                    </td>
                                </tr>";
            }
            $tabla .= "</tbody></table>";
        }catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("tabla_nuevo" => $tabla));
    }



}

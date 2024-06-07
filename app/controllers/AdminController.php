<?php
require 'app/models/Ventas.php';
require 'app/models/Hotel.php';
require 'app/models/Pedido.php';
class AdminController{
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $ventas;
    private $hotel;
    private $pedido;
    public function __construct(){
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->ventas = new Ventas();
        $this->hotel = new Hotel();
        $this->pedido = new Pedido();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $data_caja=$this->nav->listar_data_caja_actual_modificado();
            $cajas=$this->nav->listar_cajas_apertura();
            $monedas=$this->ventas->listar_monedas();
            $tipo_pago=$this->ventas->listar_tipo_pago();
            $turnos=$this->nav->listar_turnos();
            $cant = $this->hotel->listar_habitaciones();
            $ocupadas=$this->hotel->listar_habitaciones_por_estado(2);
            $deshabilitados=$this->hotel->listar_habitaciones_por_estado(3);
            $libres=count($cant)*1 - count($ocupadas) * 1 - count($deshabilitados)*1;
            $ventas_cant = $this->ventas->listar_ventas_sin_enviar();
            $mesas_estado = $this->pedido->listar_mesas_();
            $verde = 0;
            $rojo = 0;
            $amarillo = 0;
            foreach ($mesas_estado as $m){
                if($m->mesa_estado_atencion == 0){
                   $verde++;
                }
                if($m->mesa_estado_atencion == 1){
                    $amarillo++;
                }
                if($m->mesa_estado_atencion == 2){
                    $rojo++;
                }
            }
            $receta_si=$this->pedido->receta_sin_insunmos();
            $receta_faltante = $this->pedido->receta_faltante();
            $id_rol = $this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'admin/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function finalizar_sesion(){$this->sesion->finalizar_sesion();}
}


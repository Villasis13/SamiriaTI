<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 19/10/2020
 * Time: 20:01
 */
class Hotel{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    //Listamos todos los menus creados en el sistema
    public function listar_habitacion_por_id($id){
        try{
            $sql = 'select * from habitaciones h inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo where h.id_habitacion=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_habitaciones(){
        try{
            $stm = $this->pdo->prepare('select * from habitaciones h inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo order by id_habitacion');
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_productos(){
        try{
            $stm = $this->pdo->prepare('select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto inner join categorias c on p.id_categoria = c.id_categoria inner join proveedores p2 on pp.id_proveedor = p2.id_proveedor inner join medida m on pp.id_medida = m.id_medida');
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_producto_por_mt($mt){
        try{
            $stm = $this->pdo->prepare('select * from producto where producto_codigo=? limit 1');
            $stm->execute([$mt]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_medidas(){
        try{
            $stm = $this->pdo->prepare('select * from medida');
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function buscar_asistencias($desde,$hasta){
        try{
            $stm = $this->pdo->prepare('select * from asistencia where asistencia_fecha between ? and ?');
            $stm->execute([$desde,$hasta]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function buscar_productos($nombre){
        try{
            $sql = "select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto inner join categorias c on p.id_categoria = c.id_categoria inner join proveedores p2 on pp.id_proveedor = p2.id_proveedor inner join medida m on pp.id_medida = m.id_medida where p.producto_nombre like concat('%',?,'%')";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$nombre]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_personal($id){
        try{
            $id = intval($id);
            $sql = 'select * from personal pl inner join personas p on pl.id_persona=p.id_persona where pl.id_personal = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $return =$stm->fetch();
            return $return;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_comandas(){
        try{
            $sql = 'select * from config_comandas order by id_config_comanda desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_eliminados(){
        try{
            $sql = 'select * from claves_eliminacion order by id_clave_eliminacion desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function habitacion_eliminada($id){
        try{
            $sql = 'select * from rack_detalle rd inner join rack r on rd.id_rack = r.id_rack
					inner join habitaciones h on r.id_habitacion = h.id_habitacion
					inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo 
                    where rd.id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function id_rack_detalle(){
        try{
            $sql = 'select id_rack_detalle from rack_detalle';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_habitaciones_libres(){
        try{
            $date=date('Y-m-d');
            $sql = 'select * from habitaciones h inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo where h.id_habitacion not in 
                    (SELECT id_habitacion from habitacion_estado where habitacion_estado_fecha=? order by id_habitacion_estado desc) order by id_habitacion';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$date]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function habitacion_por_fecha($fecha,$id){
        try{
            $sql = 'select * from habitaciones h inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo where h.id_habitacion not in 
                    (SELECT id_habitacion from habitacion_estado where habitacion_estado_fecha=?  and h.id_habitacion=? and habitacion_last=0 order by id_habitacion_estado desc)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha,$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function habitacion_por_fecha_Estado($fecha,$id){
        try{
            $sql = 'select * from habitaciones h 
    				inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo 
    				inner join habitacion_estado he on h.id_habitacion=he.id_habitacion 
         			where habitacion_estado_fecha=? and h.id_habitacion=? order by id_habitacion_estado desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha,$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function buscar_cliente($ruc){
        try{
            $sql = 'select * from clientes where cliente_numero = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$ruc]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_tipodoc(){
        try{
            $sql = 'select * from tipo_documentos';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_tipos_habitacion(){
        try{
            $sql = 'select * from habitacion_tipo where habitacion_tipo_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_origen(){
        try{
            $sql = 'select reserva_origen from reserva';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_tipo_habitacion_por_id($id){
        try{
            $sql = 'select * from habitacion_tipo where id_habitacion_tipo=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_habitaciones_por_estado($est){
        try{
            $fecha=date('Y-m-d');
            $sql = 'select * from habitacion_estado where habitacion_estado_fecha=? and habitacion_estado_estado=? group by id_habitacion';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha,$est]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_habitaciones_por_tipo_estado_fecha($tipo,$est,$fecha){
        try{
            $sql = 'select * from habitaciones h inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo inner join habitacion_estado he on h.id_habitacion = he.id_habitacion where h.id_habitacion_tipo=? and he.habitacion_estado_estado=? and he.habitacion_estado_fecha=? order by h.id_habitacion';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$tipo,$est,$fecha]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_habitaciones_por_estado_fecha($est,$fecha){
        try{
            $sql = 'select * from habitaciones h inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo inner join habitacion_estado he on h.id_habitacion = he.id_habitacion where he.habitacion_estado_estado=? and he.habitacion_estado_fecha=? order by h.id_habitacion';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$est,$fecha]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_habitaciones_por_tipo($tipo){
        try{
            $sql = 'select * from habitaciones h inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo where h.id_habitacion_tipo=? order by h.id_habitacion';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$tipo]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_caja_cierre_mt($mt){
        try{
            $sql = 'select * from caja_cierre where caja_cierre_mt=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mt]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_racks_por_id_habitacion($id,$fecha_i,$fecha_f){
        try{
            $sql = 'select * from rack r inner join monedas m on r.id_moneda = m.id_moneda inner join clientes c on r.id_cliente = c.id_cliente inner join habitaciones h on r.id_habitacion = h.id_habitacion where r.id_habitacion = ? and r.rack_in between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha_i,$fecha_f]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_rack_por_id_fecha($id,$fecha){
        try{
            $sql = 'select * from rack r inner join clientes c on r.id_cliente=c.id_cliente inner join monedas m on r.id_moneda = m.id_moneda 
                    inner join habitaciones h on r.id_habitacion = h.id_habitacion inner join habitacion_tipo ht on h.id_habitacion_tipo = ht.id_habitacion_tipo 
                    where r.id_habitacion = ? and ? between r.rack_in and r.rack_out order by r.id_rack desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_rack_por_mt($mt){
        try{
            $sql = 'select * from rack where rack_mt = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mt]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_categorias(){
        try{
            $sql = 'select * from categorias';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_proveedores(){
        try{
            $sql = 'select * from proveedores';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function config_cat_egresos(){
        try{
            $sql = 'select * from config_cat_egresos';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function config_cat_egreso($id){
        try{
            $sql = 'select * from config_cat_egresos where id_config_cat_egreso=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_cajas(){
        try{
            $sql = 'select * from cajas where caja_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_movimientos($id_caja,$id_turno,$desde,$hasta){
        try{
            $sql = 'select * from caja_movimientos cm inner join caja_cierre cc on cm.id_caja_cierre = cc.id_caja_cierre inner join monedas m on cm.id_moneda = m.id_moneda 
                    inner join tipo_pago tp on cm.id_tipo_pago = tp.id_tipo_pago where cc.id_caja=? and cc.id_turno=? and DATE(cm.caja_movimiento_datetime) 
                    between ? and ? and cm.caja_movimiento_tipo<>3';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$id_turno,$desde,$hasta]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_movimientos_mj($id_caja,$id_turno,$desde,$hasta){
        try{
            $sql = 'select cm.id_tipo_pago as tipo_paguito_new, cm.*,cc.*,m.*,tp.*,ve.*,cl.* from caja_movimientos cm 
                                inner join caja_cierre cc on cm.id_caja_cierre = cc.id_caja_cierre 
                                inner join monedas m on cm.id_moneda = m.id_moneda 
                                inner join tipo_pago tp on cm.id_tipo_pago = tp.id_tipo_pago 
                                inner join ventas ve on ve.id_venta=cm.id_venta 
                                inner join clientes cl on cl.id_cliente=ve.id_cliente 
where cc.id_caja=? and cc.id_turno=? and DATE(cm.caja_movimiento_datetime) between ? and ? and cm.caja_movimiento_tipo<>3 and ve.id_venta!=0 order by ve.venta_serie,ve.venta_correlativo';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$id_turno,$desde,$hasta]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_movimientos_id_venta($id_venta){
        try{
            $sql = 'select * from caja_movimientos cm inner join tipo_pago tp on cm.id_tipo_pago =tp.id_tipo_pago where cm.id_venta =?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_venta]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_movimiento_cierre($id_caja,$id_turno,$desde,$hasta){
        try{
            $sql = 'select * from caja_movimientos cm inner join caja_cierre cc on cm.id_caja_cierre = cc.id_caja_cierre inner join monedas m on cm.id_moneda = m.id_moneda inner join tipo_pago tp on cm.id_tipo_pago = tp.id_tipo_pago where cc.id_caja=? and cc.id_turno=? and DATE(cm.caja_movimiento_datetime) between ? and ? and cm.caja_movimiento_tipo=3';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$id_turno,$desde,$hasta]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function buscar_usuarios_caja($id_caja,$id_turno,$desde,$hasta){
        try{
            $sql = 'select * from caja_cierre cc inner join caja_movimientos cm on cc.id_caja_cierre = cm.id_caja_cierre 
                    inner join usuarios u on cc.id_usuario = u.id_usuario inner join personas p on u.id_persona = p.id_persona
                    where cc.id_caja = ? and cc.id_turno = ? and date(cm.caja_movimiento_datetime) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$id_turno,$desde,$hasta]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_rack_por_id($id){
        try{
            $sql = 'select * from rack where id_rack = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_rack_por_id_habitacion($id,$fecha){
        try{
            $sql = 'select * from rack r inner join clientes c on r.id_cliente = c.id_cliente where id_habitacion = ? and ? between r.rack_in and r.rack_out 
                    and rack_estado=1 order by id_rack desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_rack_por_id_habitacion_($id){
        try{
            $sql = 'select * from rack r inner join clientes c on r.id_cliente = c.id_cliente where id_habitacion = ?
                    and rack_estado=1 order by id_rack desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_detalle_rack($id){
        try{
            $sql = 'select * from rack_detalle rd inner join producto_precio pp on rd.id_producto = pp.id_producto inner join 
                    producto p on pp.id_producto = p.id_producto where rd.id_rack = ? and rd.rack_detalle_estado=1 and pp.producto_precio_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_detalle_rack_fecha($id){
        try{
            $sql = 'select * from rack_detalle where id_rack = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_detalle_rack_($id){
        try{
            $sql = 'select * from rack_detalle rd inner join producto_precio pp on rd.id_producto = pp.id_producto inner join 
                    producto p on pp.id_producto = p.id_producto where rd.id_rack = ? and rd.rack_detalle_estado=1 and pp.producto_precio_estado=1 and rack_detalle_estado_fact = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_detalle_rack_id($id){
        try{
            $sql = 'select * from rack_detalle rd inner join producto_precio pp on rd.id_producto = pp.id_producto inner join producto p on pp.id_producto = p.id_producto where rd.id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_detalle_rack_id_cuentacargo($id){
        try{
            $sql = 'select * from rack_detalle rd inner join producto_precio pp on rd.id_producto = pp.id_producto inner join producto p on pp.id_producto = p.id_producto 
                    where rd.id_rack_detalle = ? and rd.rack_detalle_estado_fact=0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_extras($id){
        try{
            $sql = 'select * from extras where id_rack = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_deshabilitado($id,$fecha){
        try{
            $sql = 'select * from deshabilitados where id_habitacion = ? and deshabilitado_fecha=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_deshabilitados_fecha_id($id,$fecha){
        try{
            $sql = 'select * from deshabilitados where id_habitacion = ? and deshabilitado_fecha>=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function buscar_estado_habitacion($fecha,$id){
        try{
            $sql = 'select * from habitacion_estado where habitacion_estado_fecha = ? and id_habitacion=? order by id_habitacion_estado desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha,$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function buscar_estado_rack($fecha,$id){
        try{
            $sql = 'select * from rack where ? between rack_in and rack_out and id_habitacion = ? order by id_rack desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha,$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function ver_productos($tipo){
        try{
            $sql = 'select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto where p.id_categoria = ? and p.producto_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$tipo]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function ver_productos_todos(){
        try{
            $sql = 'select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto inner join categorias c on p.id_categoria = c.id_categoria where p.producto_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function guardar_rack($model){
        try{
            $sql = 'insert into rack (id_habitacion, id_cliente,id_moneda, rack_tarifa, rack_in, hora_check_in, rack_out, rack_noches, rack_agencia, rack_desayuno, rack_observaciones, rack_estado,rack_mt) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$model->id_habitacion,$model->id_cliente,$model->rack_moneda,$model->rack_tarifa,$model->rack_in,$model->hora_check_in,$model->rack_out,$model->rack_noches,$model->rack_agencia,$model->rack_desayuno,$model->rack_observaciones,$model->rack_estado,$model->rack_mt]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_deshabilitado($model){
        try{
            $sql = 'insert into deshabilitados (id_habitacion, deshabilitado_fecha, deshabilitado_descripcion, deshabilitado_estado) VALUES (?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$model->id_habitacion,$model->fecha,$model->rack_observaciones,1]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_detalle_rack($model){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'insert into rack_detalle (id_rack, id_producto,rack_detalle_correlativo_comanda, rack_detalle_fecha, rack_detalle_cantidad, rack_detalle_preciounit, rack_detalle_subtotal,rack_detalle_datetime, rack_detalle_estado,rack_detalle_estado_fact,id_comanda_detalle) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$model->id_rack,$model->id_producto,$model->rack_detalle_correlativo_comanda,$model->rack_detalle_fecha,$model->rack_detalle_cantidad,$model->rack_detalle_preciounit,$model->rack_detalle_subtotal,$datetime,1,0,$model->id_comanda_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function abrir_caja($model,$soles,$dolares){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'insert into caja_cierre (id_caja, id_turno, id_usuario, caja_cierre_fecha, caja_cierre_apertura_datetime, caja_cierre_cierre_datetime, 
                    caja_cierre_observaciones, caja_cierre_estado,caja_cierre_mt,caja_cierre_apertura_mon_s,caja_cierre_apertura_mon_d,tipo_cambio) 
                    values (?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$model->id_caja,$model->id_turno,$model->id_usuario,$model->fecha,$datetime,null,null,0,$model->caja_cierre_mt,$soles,$dolares,$model->tipo_cambio]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_caja_monto($id_caja_cierre,$id_moneda,$id_tipo_pago, $monto){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'insert into caja_montos (id_caja_cierre, id_moneda, id_tipo_pago, caja_monto_monto, caja_monto_estado) VALUES (?,?,?,?,1)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja_cierre,$id_moneda,$id_tipo_pago, $monto]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function actualizar_caja_monto($id_caja_cierre,$id_moneda,$id_tipo_pago, $monto){
        try{
            $sql = 'update caja_montos set caja_monto_monto=caja_monto_monto+? where id_caja_cierre=? and id_moneda=? and id_tipo_pago=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$monto,$id_caja_cierre,$id_moneda,$id_tipo_pago]);
            $sql2 = 'insert into logcito (id_caja_cierre,id_moneda,tipo_pago,monto) values( ?,?,?,?)';
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute([$id_caja_cierre,$id_moneda,$id_tipo_pago,$monto]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function cambiar_habitacion($new_hab,$id_rack){
        try{
            $sql = 'update rack set id_habitacion=? where id_rack=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$new_hab,$id_rack]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function cambiar_habitacion_estado($new_hab,$id){
        try{
            $sql = 'update habitacion_estado set id_habitacion=? where id_habitacion_estado=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$new_hab,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function cerrar_caja($id_caja_cierre){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'update caja_cierre set caja_cierre_cierre_datetime=?, caja_cierre_estado=1 where id_caja_cierre=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$datetime,$id_caja_cierre]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_caja_movimiento($id_caja_cierre,$id_producto_precio,$id_moneda,$id_tipo_pago,$id_venta,$tipo,$monto,$detalle,$obs,$codigo){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'insert into caja_movimientos (id_caja_cierre, id_producto_precio, id_moneda, id_tipo_pago,id_venta, caja_movimiento_tipo, caja_movimiento_monto, 
                    caja_movimiento_detalle, caja_movimiento_observacion, caja_movimiento_datetime, caja_movimiento_estado,codigo_pago) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja_cierre,$id_producto_precio,$id_moneda,$id_tipo_pago,$id_venta,$tipo,$monto,$detalle,$obs,$datetime,1,$codigo]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_clave_eliminacion($val,$id){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'insert into claves_eliminacion (id_usuario, clave_eliminacion_clave, clave_eliminacion_datetime,
                    clave_eliminacion_estado) VALUES (?,?,?,1)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$val,$datetime]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function existe_clave_eliminacion(){
        try{
            $sql = 'select * from claves_eliminacion where clave_eliminacion_estado=1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function usar_clave_eliminacion($id_usuario,$id_rel,$id_rel_2,$clave_tipo,$id){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'update claves_eliminacion set id_usuario_uso=?, clave_eliminacion_uso_datetime=?, clave_eliminacion_estado=0,id_relacionado=?,id_relacion_comanda=?,clave_tipo=? where id_clave_eliminacion=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_usuario,$datetime,$id_rel,$id_rel_2,$clave_tipo,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function guardar_extras($id,$nombre){
        try{
            $sql = 'insert into extras (id_rack, extra_nombre) VALUES (?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$nombre]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_checkout($id){
        try{
            $datetime=date('Y-m-d H:i:s');
            $sql = 'update rack set rack_checkout = ? where id_rack = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$datetime,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function actualizar_rack_out($fecha,$id){
        try{
            $sql = 'update rack set rack_out = ? where id_rack = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function actualizar_rack_out_n($fecha,$noches,$id){
        try{
            $sql = 'update rack set rack_out = ?, rack_noches = rack_noches + ? where id_rack = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha,$noches,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function actualizar_idventa_detalle_rack($id,$id_detalle){
        try{
            $sql = 'update rack_detalle set rack_detalle_estado_fact = ?,rack_detalle_estado_venta = 1 where id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$id_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function act_estado($id_detalle){
        try{
            $sql = 'update rack_detalle set rack_detalle_estado_venta = 2 where id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function buscar_registros($id){
        try {
            $sql = "select count(id_rack_detalle) as total from rack_detalle where id_rack = ? and rack_detalle_estado_venta = 0";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function actualizar_estado_pago_cuentadetalle($id_detalle){
        try{
            $sql = 'update cuentas_detalle set cuentas_detalle_estado_pago = 1 where id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function guardar_habitacion_estado($id,$fecha,$est,$t,$moneda){
        try{
            $sql = 'insert into habitacion_estado (id_habitacion, habitacion_estado_fecha, habitacion_estado_estado,habitacion_last,id_moneda) VALUES (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha,$est,$t,$moneda]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_valor_1($id){
        try{
            $sql = 'delete from habitacion_estado where id_habitacion = ? and habitacion_last = 1 order by habitacion_estado_fecha desc ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_habitacion_estado($id,$fecha){
        try{
            $sql = 'delete from habitacion_estado where id_habitacion=? and habitacion_estado_fecha=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function eliminar_habitacion_deshabilitado($id,$fecha){
        try{
            $sql = 'delete from deshabilitados where id_habitacion=? and deshabilitado_fecha=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$fecha]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_comanda_por_habitacion ($id){
        try {
            $sql = "SELECT * FROM comanda c inner join comanda_detalle cd on c.id_comanda=cd.id_comanda inner join producto p on cd.id_producto=p.id_producto where c.id_rack=?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_detalle_comanda_id ($id){
        try {
            $sql = "SELECT * FROM comanda_detalle cd inner join producto p on cd.id_producto=p.id_producto where cd.id_comanda_detalle=?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_id_comanda_x_rack ($id){
        try {
            $sql = "SELECT * FROM comanda c inner join comanda_detalle cd on c.id_comanda=cd.id_comanda where id_rack=?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function guardar_detalle_pago($id_venta,$tipo_pago,$monto,$valor){
        try {
            $sql = 'insert into ventas_detalle_pagos (id_venta, id_tipo_pago, venta_detalle_pago_monto, venta_detalle_pago_estado,venta_detalle_estado) 
                    values(?,?,?,1,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_venta,$tipo_pago,$monto,$valor
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function verificar_rack_detalle($id){
        try {
            $sql = "select * from rack_detalle where rack_detalle_estado_fact = 0 and rack_detalle_envio = 0 and id_rack = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function cambiar_precio_detalle($precio_actual,$subtotal,$id_rack_detalle){
        try{
            $sql = 'update rack_detalle set rack_detalle_preciounit = ?,rack_detalle_subtotal = ? where id_rack_detalle=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$precio_actual,$subtotal,$id_rack_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function modificar_estado_reserva($id_reserva){
        try{
            $sql = 'update reserva set reserva_estado=0 where id_reserva=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_reserva]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


}

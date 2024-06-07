<?php

class Reporte
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    public function listar_busqueda_venta($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'SELECT DISTINCT(p.id_producto)  FROM ventas v 
                    inner join ventas_detalle vd on v.id_venta=vd.id_venta 
                    inner join producto_precio pp on vd.id_producto_precio=pp.id_producto_precio 
                    inner join producto p on pp.id_producto=p.id_producto where date(v.venta_fecha) between  ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro, $fecha_filtro_fin]);
            $return = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function listar_prod($id,$inicio,$fin){
        try {
            $sql = "select p.producto_nombre, sum(vd.venta_detalle_cantidad) venta_cantidad_final from producto p
                    inner join producto_precio pp on p.id_producto=pp.id_producto
                    inner join ventas_detalle vd on pp.id_producto_precio=vd.id_producto_precio
                    inner join ventas v on vd.id_venta=v.id_venta
                    where p.id_producto=? and date(v.venta_fecha) between  date(?) and date(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$inicio,$fin]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_cajas(){
        try {
            $sql = "select * from cajas where caja_estado=1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([]);
            return $stm->fetchAll();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function ingreso_caja_chica_x_caja($id_caja,$fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select sum(egreso_monto) total from movimientos m inner join caja c on m.id_caja_numero = c.id_caja_numero
                    where c.id_caja =? and egreso_fecha_registro between ? and ? and movimiento_tipo = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function reporte_productos($fecha_filtro,$fecha_filtro_fin,$id_caja){
        try{
            $sql = 'select sum(vd.venta_detalle_cantidad) total, p.producto_nombre from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta
                    inner join comanda_detalle cd on vd.id_comanda_detalle = cd.id_comanda_detalle inner join 
                    producto p on cd.id_producto = p.id_producto where v.anulado_sunat = 0 and v.venta_cancelar = 1 and v.venta_fecha between ? and ? and v.id_usuario = ? group by p.id_producto ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin,$id_caja]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }
    public function datos_por_apertura_caja_($fecha_i,$fecha_f){
        try{
            $sql = 'select id_caja_cierre,id_caja from caja_cierre where date(caja_cierre_apertura_datetime) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_i,$fecha_f]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function datos_caja_($id_caja_numero){
        try{
            $sql = 'select * from cajas where id_caja = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja_numero]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_usuarios_($id_usuario){
        try{
            $sql = 'select * from personas p inner join usuarios u on p.id_persona = u.id_persona where u.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_usuario]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_cajeros(){
        try{
            $sql = 'select * from personas p inner join usuarios u on p.id_persona = u.id_persona where u.id_rol = 5 or u.id_rol = 7 or u.id_rol = 4 and u.usuario_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function datitos_caja($id_caja){
        try{
            $sql = 'select * from caja_cierre where id_caja_cierre = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function reporte_productos_($fecha_filtro,$fecha_filtro_fin, $id_usuario){
        try{
            $sql = 'select sum(vd.venta_detalle_cantidad) total, p.producto_nombre from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta 
                    inner join comanda_detalle cd on vd.id_comanda_detalle = cd.id_comanda_detalle inner join 
                    producto p on cd.id_producto = p.id_producto where v.anulado_sunat = 0 and v.venta_cancelar = 1 
                    and date(v.venta_fecha) between ? and ? and v.id_usuario = ? group by p.id_producto ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin, $id_usuario]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function reporte_productos_2($fecha_filtro,$fecha_filtro_fin, $id_usuario){
        try{
            $sql = 'select sum(cd.comanda_detalle_cantidad) total, p.producto_nombre from comanda_detalle cd inner join 
                    producto p on cd.id_producto = p.id_producto where date(cd.comanda_detalle_fecha_registro) between ? and ? and cd.id_usuario_a = ?
                    and cd.comanda_detalle_estado_venta <> 0 and cd.comanda_detalle_estado =1 group by p.id_producto order by total desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin, $id_usuario]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    //PURO PARA REPORTES
    public function n_ventas_salon($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select count(v.id_venta) total from ventas v 
                    where date(venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and id_mesa <> 0 and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function reporte_caja_x_caja($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select * from caja_cierre where date(caja_cierre_apertura_datetime) between ? and ? and id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    //FUNCIONES PARA VENTAS
    public function ventas_efectivo($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 3 and vdp.venta_detalle_pago_estado = 1 and v.id_moneda=1
                    and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_efectivo_d($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 3 and vdp.venta_detalle_pago_estado = 1 and v.id_moneda=2
                    and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_tarjeta($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 1 and vdp.venta_detalle_pago_estado = 1 and v.id_mesa <> 0 and v.id_moneda=1
                    and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_tarjeta_d($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 1 and vdp.venta_detalle_pago_estado = 1 and v.id_mesa <> 0 
                      and id_moneda=2 and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_trans_plin($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 2 and vdp.venta_detalle_pago_estado = 1 and v.id_moneda=1 
                    and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_trans_plin_d($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where  date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 2 and vdp.venta_detalle_pago_estado = 1 and v.id_moneda=2
                    and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_trans_yape($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 2 and vdp.venta_detalle_pago_estado = 1 
                      and v.id_moneda=1 and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_trans_yape_d($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 2 and vdp.venta_detalle_pago_estado = 1 and v.id_moneda=2
                     and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_trans_otros($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 6 and vdp.venta_detalle_pago_estado = 1 and v.id_moneda=1
                     and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
    public function ventas_trans_otros_d($fecha_ini_caja, $fecha_fin_caja, $id_usuario){
        try{
            $sql = 'select SUM(vdp.venta_detalle_pago_monto) total from ventas v inner join ventas_detalle_pagos vdp on v.id_venta = vdp.id_venta
                    where date(v.venta_fecha) between (?) and (?) and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and vdp.id_tipo_pago = 6 and vdp.venta_detalle_pago_estado = 1 
                      and v.id_moneda=2 and v.id_usuario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja, $id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    //NUEVA FUNCION PARA LOS REPORTES DE ALMACEN
    public function listar_recursos_reporte(){
        try{
            $sql = 'select * from recursos r inner join detalle_recetas dr on r.id_recurso = dr.id_recurso 
                    group by r.id_recurso';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    //FUNCION PARA BUSCAR SALIDAD DE STOCK
    public function buscar_salidad_stock($dato){
        try{
            $sql = 'select rl.*, r.recurso_nombre as nombre from recurso_log rl 
                    inner join recursos_sede rs on rl.id_recurso_sede = rs.id_recurso_sede
                    inner join recursos r on rs.id_recurso = r.id_recurso
                    where date(rl.recurso_log_fecha) = ? and rl.recurso_log_tipo = 2';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$dato]);
            $return = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    //FUNCIONES PARA HABITACIONES COBRADAS Y NO COBRADAS

    public function habitacion_comandas_x_cobrar($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select sum(cd.comanda_detalle_total) total from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda 
                    inner join rack_detalle rd on cd.id_comanda_detalle = rd.id_comanda_detalle            
                    where cd.comanda_detalle_estado_venta = 2 and date(cd.comanda_detalle_fecha_registro) between ? and ? and rd.rack_detalle_estado_fact = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function habitacion_comandas_cobradas($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select sum(cd.comanda_detalle_total) total from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda
                    inner join rack_detalle rd on cd.id_comanda_detalle = rd.id_comanda_detalle
            where cd.comanda_detalle_estado_venta = 2 and date(cd.comanda_detalle_fecha_registro) between ? and ? and rd.rack_detalle_estado_fact <> 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function listar_habitacion_comandas_x_cobrar($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto pr on cd.id_producto = pr.id_producto
                    inner join rack_detalle rd on cd.id_comanda_detalle = rd.id_comanda_detalle inner join rack r on rd.id_rack = r.id_rack
                    inner join habitaciones h on r.id_habitacion = h.id_habitacion
                    where cd.comanda_detalle_estado_venta = 2 and producto_estado =1 and date(cd.comanda_detalle_fecha_registro) between ? and ? and rd.rack_detalle_estado_fact = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function listar_habitacion_comandas_cobradas($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto pr on cd.id_producto = pr.id_producto
                    inner join rack_detalle rd on cd.id_comanda_detalle = rd.id_comanda_detalle inner join rack r on rd.id_rack = r.id_rack
                    inner join habitaciones h on r.id_habitacion = h.id_habitacion inner join ventas v on rd.rack_detalle_estado_fact = v.id_venta
                    inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago
                    where cd.comanda_detalle_estado_venta = 2 and date(cd.comanda_detalle_fecha_registro) between ? and ? and rd.rack_detalle_estado_fact <> 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    //FUNCIONES PARA LISTAR LOS PEDIOS DE RESTAURANTES PENDIENTES DE PAGO Y LOS QUE YA ESTAN PAGADOS
    public function comandas_pendientes_cuentas_cobrar($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select sum(cd.comanda_detalle_total) total from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda 
                    inner join cuentas_detalle d on cd.id_comanda_detalle = d.id_comanda_detalle          
                    where cd.comanda_detalle_lugar_final = 3 and date(cd.comanda_detalle_fecha_registro) between ? and ? and d.cuentas_detalle_estado_pago = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function comandas_pagadas_cuentas_cobrar($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select sum(cd.comanda_detalle_total) total from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda 
                    inner join cuentas_detalle d on cd.id_comanda_detalle = d.id_comanda_detalle          
                    where cd.comanda_detalle_estado_venta = 1 and date(cd.comanda_detalle_fecha_registro) between ? and ? and d.cuentas_detalle_estado_pago = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }


    public function listar_cargo_comandas_x_cobrar($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto pr on cd.id_producto = pr.id_producto
                    inner join cuentas_detalle d on cd.id_comanda_detalle = d.id_comanda_detalle inner join cuentas c2 on d.id_cuenta = c2.id_cuenta
                    inner join clientes c3 on c2.id_cliente = c3.id_cliente
                    where cd.comanda_detalle_estado_venta = 0 and producto_estado =1 and date(cd.comanda_detalle_fecha_registro) between ? and ? and d.cuentas_detalle_estado_pago = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function listar_cargo_comandas_cobradas($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto pr on cd.id_producto = pr.id_producto
                    inner join cuentas_detalle d on cd.id_comanda_detalle = d.id_comanda_detalle inner join cuentas c2 on d.id_cuenta = c2.id_cuenta
                    inner join clientes c3 on c2.id_cliente = c3.id_cliente
                    where cd.comanda_detalle_estado_venta = 1 and pr.producto_estado =1 and date(cd.comanda_detalle_fecha_registro) between ? and ? 
                    and d.cuentas_detalle_estado_pago = 1 group by cd.id_comanda_detalle';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function buscar_comprobantes_cargo($id){
        try{
            $sql = 'select * from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta 
                    inner join comanda_detalle cd on vd.id_comanda_detalle = cd.id_comanda_detalle where vd.id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function reporte_productos_3($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select sum(cd.comanda_detalle_cantidad) as total, p.producto_nombre,p.id_producto from comanda_detalle cd inner join 
                    producto p on cd.id_producto = p.id_producto where date(cd.comanda_detalle_fecha_registro) between ? and ?
                    and comanda_detalle_estado = 1 group by p.id_producto order by total desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }
    public function pedidos_sin_pagar($fecha_i,$fecha_f,$id_producto){
        try{
            $sql = 'select sum(cd.comanda_detalle_cantidad) total from comanda_detalle cd inner join producto p on cd.id_producto = p.id_producto where date(cd.comanda_detalle_fecha_registro) between ? and ? 
                    and p.id_producto=? and comanda_detalle_estado_venta = 0 and comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_i,$fecha_f,$id_producto]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }
    public function habitaciones_por_estado_fecha($fecha){
        $result=0;
        try{
            $stm = $this->pdo->prepare("SELECT COUNT(h.id_habitacion) AS total, SUM(CASE WHEN he.habitacion_estado_estado IS NULL THEN 1 ELSE 0 END) AS libres, SUM(CASE WHEN he.habitacion_estado_estado = 2 THEN 1 ELSE 0 END) AS ocupadas, SUM(CASE WHEN he.habitacion_estado_estado = 3 THEN 1 ELSE 0 END) AS deshabilitadas,ROUND(SUM(CASE WHEN he.habitacion_estado_estado IS NULL THEN 1 ELSE 0 END) / COUNT(h.id_habitacion) * 100, 2) AS porcentaje_libres,
    ROUND(SUM(CASE WHEN he.habitacion_estado_estado = 2 THEN 1 ELSE 0 END) / COUNT(h.id_habitacion) * 100, 2) AS porcentaje_ocupadas,
    ROUND(SUM(CASE WHEN he.habitacion_estado_estado = 0 THEN 1 ELSE 0 END) / COUNT(h.id_habitacion) * 100, 2) AS porcentaje_deshabilitadas FROM habitaciones h LEFT JOIN habitacion_estado he ON h.id_habitacion = he.id_habitacion AND he.habitacion_estado_fecha = ?");
            $stm->execute([$fecha]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        }
        return $result;
    }

    //FUNCION PARA REPORTES DE MESEROS
    public function datos_meseros($fecha_i,$fecha_f){
        try{
            $sql = 'select count(c.id_comanda) total, sum(comanda_total) total_comanda,p.persona_nombre from comanda c inner join usuarios u 
                    on c.id_usuario = u.id_usuario inner join personas p on u.id_persona = p.id_persona inner join roles r on u.id_rol = r.id_rol 
                    where r.id_rol = 6 and date(c.comanda_fecha_registro) between ? and ? group by u.id_persona order by total_comanda desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_i,$fecha_f]);
            $return = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }


}
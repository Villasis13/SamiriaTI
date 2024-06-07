<?php

class Cuentascobrar
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }


    public function clientes_cuenta(){
        try{
            $sql = 'select * from clientes where cliente_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    //FUNCION PARA SABER LAS CUENTAS POR COBRAR
    public function cuentas_cobrar($fecha_ini,$fecha_fin){
        try{
            $sql = 'select * from cuentas c inner join clientes c2 on c.id_cliente = c2.id_cliente 
                    where date(c.cuenta_fecha_creacion) between ? and ? and c.cuenta_estado = 1 and c2.cliente_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini,$fecha_fin]);
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function cuentas_cobrar_por_filtro($fecha_ini,$fecha_fin,$pago){
        try{
                $sql = 'select * from cuentas c inner join clientes c2 on c.id_cliente = c2.id_cliente 
                        where date(c.cuenta_fecha_creacion) between ? and ? and c.cuenta_cancelado = ? and c.cuenta_estado = 1 and c2.cliente_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini,$fecha_fin,$pago]);
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function buscar_comprobantes($id_cuenta){
        try{
            $sql = 'select * from ventas where id_cuenta=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cuenta]);
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    //FUNCION PARA LISTAR LOS DETALLES DE LAS CUENTAS TANTO DE COMANDAS COMO DE SERVICIO DE HOTEL
    public function detalles_cuentas($id){
        try{
            $sql = 'select * from cuentas_detalle where id_cuenta = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }


    public function buscar_comanda_detalle($id_comanda_detalle){
        try{
            $sql = 'select * from comanda_detalle cd inner join producto p on cd.id_producto = p.id_producto where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function buscar_rack_detalle($id_rack_detalle){
        try{
            $sql = 'select * from rack_detalle rd inner join producto p on rd.id_producto = p.id_producto where id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_rack_detalle]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function buscar_habitacion($id_rack_detalle){
        try{
            $sql = 'select * from rack_detalle rd inner join rack r on rd.id_rack = r.id_rack inner join habitaciones h on r.id_habitacion = h.id_habitacion 
                    where rd.id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_rack_detalle]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }
    //FUNCION PARA VALIDAR REGISTROS EXISTENTES
    public function revisar_cuenta_pasada($id_cliente){
        try{
            $sql = 'select * from cuentas where id_cliente = ? and cuenta_cancelado <> 2';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function sumar_cuenta($total,$id){
        try{
            $sql = 'update cuentas set cuentas_total = cuentas_total + ? where id_cuenta = ? and cuenta_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$total,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function sacar_valor($id){
        try{
            $sql = 'select * from cuentas_detalle where id_cuenta_detalle=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function sacar_id_cliente($id){
        try{
            $sql = 'select * from cuentas c inner join clientes c2 on c.id_cliente = c2.id_cliente where c.id_cuenta=? and c2.cliente_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function sumar_pagado_cuenta($total,$id){
        try{
            $sql = 'update cuentas set cuentas_total_pagado = cuentas_total_pagado + ? where id_cuenta = ? and cuenta_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$total,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function buscar_comparacion($id){
        try{
            $sql = 'select * from cuentas where id_cuenta = ? and cuenta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function update_estado_cancelado($id){
        try{
            $sql = 'update cuentas set cuenta_cancelado = 2 where id_cuenta = ? and cuenta_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    public function update_estado_pendiente($id){
        try{
            $sql = 'update cuentas set cuenta_cancelado = 1 where id_cuenta = ? and cuenta_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function uppdeit_estado($id){
        try{
            $sql = 'update rack_detalle set rack_detalle_envio = 1 where id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function actualizar_montos_moneda($monto_unitario,$monto_subtotal,$id){
        try{
            $sql = 'update rack_detalle set rack_detalle_preciounit=?, rack_detalle_subtotal=? where id_rack_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$monto_unitario,$monto_subtotal,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    //FUNCION PARA ELIMINAR UNA CUENTA DEL DETALLE
    public function eliminar_cuenta_detalle($id_cuenta_detalle){
        try{
            $sql = 'delete from cuentas_detalle where id_cuenta_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cuenta_detalle]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function buscar_monto_resta($id_rack_detalle){
        try{
            $sql = 'select * from rack_detalle where id_rack_detalle=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_rack_detalle]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function buscar_monto_resta_comanda($id_comanda_detalle){
        try{
            $sql = 'select * from comanda_detalle where id_comanda_detalle=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function restar_en_cuenta($monto_resta,$id_cuenta){
        try{
            $sql = 'update cuentas set cuentas_total=cuentas_total - ? where id_cuenta=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$monto_resta,$id_cuenta]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function buscar_detalles_pendientes($id_cuenta){
        try{
            $sql = 'select * from cuentas_detalle where id_cuenta=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cuenta]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function eliminar_toda_cuenta($id_cuenta){
        try{
            $sql = 'delete from cuentas where id_cuenta = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cuenta]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function ver_detalles_pendientes($id_cuenta){
        try{
            $sql = 'select * from cuentas_detalle where id_cuenta=? and cuentas_detalle_estado_pago=0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cuenta]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function actualizar_estado_cuenta($id_cuenta){
        try{
            $sql = 'update cuentas set cuenta_cancelado=2 where id_cuenta=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cuenta]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function sacar_tipo_moneda($id_rack){
        try{
            $sql = 'select id_moneda from rack where id_rack=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_rack]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function sacar_dolar($id_usuario){
        try{
            $sql = 'select tipo_cambio from caja_cierre where id_usuario=? and caja_cierre_estado = 0 order by id_caja_cierre desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_usuario]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function sacar_simbolo($id_cuenta){
        try{
            $sql = 'select id_moneda from cuentas where id_cuenta=? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cuenta]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

}
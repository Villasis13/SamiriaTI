<?php
class Pedido
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }


    public function listar_mesas($id){
        try {
            $sql = 'select * from mesas m inner join usuarios_sucursal us on m.id_sucursal = us.id_sucursal inner join sucursal s 
                    on us.id_sucursal = s.id_sucursal where id_usuario = ? and id_mesa <> 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    public function listar_mesas_(){
        try {
            $sql = 'select * from mesas where mesa_estado = 1 and id_mesa >= 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_mesas_tg(){
        try {
            $sql = 'select * from mesas where mesa_estado = 1 and id_mesa < 0 order by id_mesa desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_mesas_reserva(){
        try {
            $sql = 'select * from mesas where id_mesa > 0 and mesa_estado_atencion = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_mesas_junte(){
        try {
            $sql = 'select * from mesas where id_mesa > 0 and mesa_estado_atencion = 0 and mesa_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_mesas_ocultas(){
        try {
            $sql = 'select * from mesas where id_mesa > 0 and mesa_estado_atencion = 0 and mesa_estado = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_productos(){
        try {
            $sql = 'select * from producto where producto_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_familias(){
        try{
            $sql = "select * from producto_familia where producto_familia_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_productos_x_familia($id_producto_familia){
        try{
            $sql = "select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto 
                    where p.id_producto_familia = ? and p.producto_estado = 1 and pp.producto_precio_estado = 1
                    and pp.id_producto_precio<>-1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto_familia]);
            $result = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function listar_clienteventa_x_id_delivery($id_cliente){
        try{
            $sql = "select * from comanda c inner join clientes c2 on c.id_cliente = c2.id_cliente inner join tipo_documentos td on c2.id_tipodocumento = td.id_tipodocumento
                    where c.id_cliente = ? order by c.id_comanda desc";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            $result = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function jalar_datos_comanda($id_comanda){
        try{
            $sql = "select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join ventas_detalle vd on cd.id_comanda_detalle = vd.id_comanda_detalle
                    inner join ventas v on vd.id_venta = v.id_venta inner join mesas m on c.id_mesa = m.id_mesa where c.id_comanda = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar($id){
        try {
            $sql = 'select * from comanda where id_mesa = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_precio_producto($id_producto){
        try{
            $sql = 'select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto where p.id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //FUNCION PARA GUARDAR LA COMANDA
    public function guardar_comanda($model){
        try{
            $sql = 'insert into comanda (id_mesa, id_usuario,id_cliente, comanda_nombre_delivery, comanda_direccion_delivery, 
                     comanda_telefono_delivery,comanda_cantidad_personas, comanda_correlativo, comanda_total, 
                     comanda_fecha_registro, comanda_estado, comanda_codigo) values (?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_mesa,
                $model->id_usuario,
                $model->id_cliente,
                $model->cliente_nombre_d,
                $model->comanda_direccion_delivery,
                $model->comanda_telefono_delivery,
                $model->comanda_cantidad_personas,
                $model->comanda_correlativo,
                $model->comanda_total,
                $model->comanda_fecha_registro,
                $model->comanda_estado,
                $model->comanda_codigo
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function guardar_reserva($model){
        try{
            if(empty($model->id_reserva)){
                $sql = 'insert into reservas (id_mesa, reserva_nombre, reserva_cantidad, reserva_fecha, reserva_hora,reserva_contacto, reserva_fecha_registro, 
                    reserva_estado) values (?,?,?,?,?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_mesa,
                    $model->reserva_nombre,
                    $model->reserva_cantidad,
                    $model->reserva_fecha,
                    $model->reserva_hora,
                    $model->reserva_contacto,
                    $model->reserva_fecha_registro,
                    $model->reserva_estado
                ]);
            }else{
                $sql = "update reservas
                set
                id_mesa = ?,
                reserva_nombre = ?,
                reserva_cantidad = ?,
                reserva_fecha = ?,
                reserva_hora = ?,
                reserva_contacto = ?
                where id_reserva = ?";

                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_mesa,
                    $model->reserva_nombre,
                    $model->reserva_cantidad,
                    $model->reserva_fecha,
                    $model->reserva_hora,
                    $model->reserva_contacto,
                    $model->id_reserva
                ]);
            }
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_comanda_por_mt($micro){
        try{
            $sql = 'select * from comanda where comanda_codigo = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$micro]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_comanda_x_id($id_comanda){
        try{
            $sql = 'select * from comanda c inner join usuarios u on c.id_usuario = u.id_usuario inner join mesas m 
                    on c.id_mesa = m.id_mesa inner join personas p on u.id_persona = p.id_persona where c.id_comanda = ? 
                    limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function guardar_detalle_comanda($model){
        try {
            $sql = 'insert into comanda_detalle (id_comanda,id_usuario_a, id_producto, comanda_detalle_precio, comanda_detalle_cantidad, 
                    comanda_detalle_despacho, comanda_detalle_total, comanda_detalle_observacion, comanda_detalle_fecha_registro, 
                    comanda_detalle_estado,codigo,comanda_detalle_lugar_final) values(?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_comanda,
                $model->id_usuario_a,
                $model->id_producto,
                $model->comanda_detalle_precio,
                $model->comanda_detalle_cantidad,
                $model->comanda_detalle_despacho,
                $model->comanda_detalle_total,
                $model->comanda_detalle_observacion,
                $model->comanda_detalle_fecha_registro,
                $model->comanda_detalle_estado,
                $model->codigo,
                $model->comanda_detalle_lugar_final
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function guardar_detalle_pago($model){
        try {
            $sql = 'insert into ventas_detalle_pagos (id_venta, id_tipo_pago, venta_detalle_pago_monto, venta_detalle_pago_estado,venta_detalle_estado) 
                    values(?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_venta,
                $model->id_tipo_pago,
                $model->venta_detalle_pago_monto,
                $model->venta_detalle_pago_estado,
                $model->venta_detalle_estado
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function jalar_valor($id_comanda){
        try{
            $sql = "select sum(comanda_detalle_total) total from comanda_detalle where id_comanda = ? and comanda_detalle_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_ultima_fecha($fecha){
        try{
            $sql = 'select * from caja_cierre where caja_cierre_fecha = ? and caja_cierre_estado = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);
            $result = $stm->fetch();
            if(!empty($result)){
                $result = true;
            } else {
                $result = false;
            }
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = false;
        }
        return $result;
    }

    public function jalar_id_caja_aperturada($id_usuario){
        try{
            $sql = 'select * from cajas where caja_estado = 1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_usuario]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function jalar_id_caja_aperturada_(){
        try{
            $sql = 'select * from cajas where caja_estado = 1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function jalar_datos_caja_numero($id_caja_numero){
        try{
            $sql = 'select * from caja_numero where  
                    id_caja_numero = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja_numero]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }


    public function actualizar_nuevo_valor($id_comanda, $nuevo_valor){
        try{
            $sql = 'update comanda set comanda_total = ? where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$nuevo_valor, $id_comanda]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function actualizar_comanda_detalle_cantidad($id_comanda_detalle, $cantidad_detalle_nuevo, $total){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_cantidad = ?, comanda_detalle_total = ? where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$cantidad_detalle_nuevo, $total, $id_comanda_detalle]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_comanda_detalle($comanda_detalle_eliminacion,$fecha_eliminacion,$id_comanda_detalle){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_eliminacion = ?, comanda_detalle_fecha_eliminacion = ?, comanda_detalle_estado = 0 where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$comanda_detalle_eliminacion,$fecha_eliminacion,$id_comanda_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_comanda_detalle_delivery($id_comanda){
        try{
            $sql = 'delete from comanda_detalle where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar_grupos(){
        try{
            $sql = 'select * from grupos where grupo_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function listar_productos_cocina($id){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo 
                    where g.id_grupo = ? and comanda_detalle_estado <> 2 order by mesa_nombre asc ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_grupo_vistas($id){
        try{
            $sql = 'select * from grupos where id_grupo = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_pedidos_por_mesa($id_mesa, $id_comanda){
        try{
            $sql = 'select c.*,cd.*,m.*,p.*,g.*,pp.*,u.id_usuario as id_user,p2.* from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda 
                    inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto left join grupos g on p.id_grupo = g.id_grupo 
                    inner join producto_precio pp on p.id_producto = pp.id_producto inner join usuarios u on cd.id_usuario_a = u.id_usuario
                    inner join personas p2 on u.id_persona = p2.id_persona
                    where c.id_mesa = ? and c.id_comanda = ? and pp.producto_precio_estado=1 and cd.comanda_detalle_estado = 1 and cd.comanda_detalle_estado_venta <> 2
                    order by cd.comanda_detalle_fecha_registro asc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa,$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_pedidos_eliminados($fecha_i,$fecha_f){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo 
                    inner join producto_precio pp on p.id_producto = pp.id_producto
                    where date(cd.comanda_detalle_fecha_eliminacion) between ? and ? and pp.producto_precio_estado=1 and cd.comanda_detalle_estado = 0
                    order by date(cd.comanda_detalle_fecha_eliminacion) desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_pedidos($fecha_i, $fecha_f){
        try{
            $sql = 'select * from comanda c inner join mesas m on c.id_mesa = m.id_mesa inner join usuarios u on c.id_usuario = u.id_usuario
                    inner join personas p on u.id_persona = p.id_persona
                    where DATE(c.comanda_fecha_registro) between ? and ? order by c.comanda_fecha_registro desc ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_pedidos_x_mesa($id_mesa,$fecha_i, $fecha_f){
        try{
            $sql = 'select * from comanda c inner join mesas m on c.id_mesa = m.id_mesa inner join usuarios u on c.id_usuario = u.id_usuario
                    inner join personas p on u.id_persona = p.id_persona
                    where c.id_mesa = ? and date(c.comanda_fecha_registro) between ? and ? order by c.comanda_fecha_registro desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa,$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_productos_venta($id_comanda){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo 
                    inner join producto_precio pp on p.id_producto = pp.id_producto
                    where c.id_comanda = ? and pp.producto_precio_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function listar_pedidos_delivery($id_mesa){
        try{
            $sql = 'select * from comanda c inner join clientes c2 on c.id_cliente = c2.id_cliente inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo 
                    inner join producto_precio pp on p.id_producto = pp.id_producto
                    where c.id_mesa = ? and pp.producto_precio_estado=1 and c.comanda_estado = 0 group by c.id_comanda';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_pedidos_delivery_entregados($id_mesa,$fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from comanda c inner join clientes c2 on c.id_cliente = c2.id_cliente inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo 
                    inner join producto_precio pp on p.id_producto = pp.id_producto
                    where c.id_mesa = ? and pp.producto_precio_estado=1 and comanda_detalle_estado_venta = 1 and c.comanda_estado = 1 and date(comanda_fecha_registro) between 
                    ? and ? group by c.id_comanda ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa,$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_detalle_x_comanda($id_comanda){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto p 
                    on cd.id_producto = p.id_producto where cd.id_comanda = ? and comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_detalle_x_comanda_x_grupo($id_comanda, $grupo){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto p 
                    on cd.id_producto = p.id_producto where cd.id_comanda = ? and p.id_grupo = ? and comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda, $grupo]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_detalle_x_comanda_x_grupo_fecha($id_comanda, $grupo,$fecha){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto p 
                    on cd.id_producto = p.id_producto where cd.id_comanda = ? and p.id_grupo = ? and cd.comanda_detalle_estado = 1 and cd.comanda_detalle_fecha_registro=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda, $grupo,$fecha]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_detalle_x_comanda_detalle($id_comanda_detalle){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto p 
                    on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo inner join usuarios u on c.id_usuario = u.id_usuario
                    inner join personas p2 on u.id_persona = p2.id_persona
                    where cd.id_comanda_detalle = ? and comanda_detalle_estado = 1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_detalle_x_comanda_para_precuenta($id_comanda){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto p 
                    on cd.id_producto = p.id_producto where cd.id_comanda = ? and cd.comanda_detalle_estado_venta = 0
                    and cd.comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_detalle_x_comanda_x_fecha($id_comanda,$fecha){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto p 
                    on cd.id_producto = p.id_producto where cd.id_comanda = ? and cd.comanda_detalle_fecha_registro=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda, $fecha]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function datos_cliente($id_comanda){
        try{
            $sql = 'select * from comanda c inner join clientes c2 on c.id_cliente = c2.id_cliente inner join tipo_documentos td on c2.id_tipodocumento = td.id_tipodocumento
                    where c.id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar_fila($id_comanda){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda where cd.id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_precio_x_producto($id_comanda,$id_producto){
        try{
            $sql = 'select count(cd.id_producto) total_producto, sum(comanda_detalle_total) suma_precio from comanda_detalle cd inner join producto p on cd.id_producto = p.id_producto inner join producto_precio pp on p.id_producto = pp.id_producto
                    where cd.id_producto = ? and cd.id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto,$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function jalar_producto($id_comanda){
        try{
            $sql = "select * from comanda_detalle cd inner join producto p on cd.id_producto = p.id_producto inner join 
                    producto_precio pp on p.id_producto = pp.id_producto where id_comanda = ? and pp.producto_precio_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function cantidad_x_productos($id_producto, $id_comanda){
        try{
            $sql = "select count(id_producto) total from comanda_detalle where id_producto = ? and id_comanda = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto,$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listarSerie($tipo_venta){
        try{
            $sql = 'select * from serie where tipocomp = ? and estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$tipo_venta]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listarSerie_caja($tipo_venta, $id_caja){
        try{
            $sql = 'select * from serie where id_caja_numero = ? and tipocomp = ? and estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$tipo_venta]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listarSerie_NC_x_id($tipo_venta, $id){
        try{
            $sql = 'select * from serie where tipocomp = ? and id_serie=? and estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$tipo_venta, $id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar_comanda($id_mesa, $id_comanda){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo 
                    where c.id_mesa = ? and c.id_comanda = ? and cd.comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa,$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_detalles_x_id($id){
        try{
            $sql = 'select count(comanda_detalle_estado_venta) total from comanda_detalle where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar_comanda_delivery($id_comanda){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo where c.id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function ultimo_pedido($id){
        try{
            $sql = 'select * from comanda c inner join mesas m on c.id_mesa = m.id_mesa inner join comanda_detalle cd on c.id_comanda = cd.id_comanda
                    where c.id_mesa = ? and cd.comanda_detalle_estado_venta = 0 and cd.comanda_detalle_estado = 1 and cd.comanda_detalle_lugar_final = 1
                    order by c.id_comanda desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    //FUNCION PARA CAMBIAR ESTADO DE LOS PEDIDOS
    public function cambiar_estado_pedido($id_comanda_detalle, $comanda_detalle_estado){
        try {
            $sql = "update comanda_detalle set
                comanda_detalle_estado = ?
                where id_comanda_detalle = ?";

            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $comanda_detalle_estado,$id_comanda_detalle
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listar_detallecomanda_x_id_comanda_detalle($id_detalle_comanda){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join mesas m on c.id_mesa = m.id_mesa
                    inner join producto p on cd.id_producto = p.id_producto inner join grupos g on p.id_grupo = g.id_grupo
                    inner join usuarios u on c.id_usuario = u.id_usuario inner join personas p2 on u.id_persona = p2.id_persona
                    where cd.id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_detalle_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function cambiar_estado_mesa($id_mesa){
        try {
            $sql = "update mesas set mesa_estado_atencion = 1 where id_mesa = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_mesa
            ]);

            $fecha_hoy = date('Y-m-d H:i:s');

            $sql2 = 'insert into mesa_log (id_mesa, mesa_log_estado_atencion, mesa_log_fecha_hora, mesa_log_estado) values (?,?,?,?)';
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute([
                $id_mesa, 1 , $fecha_hoy , 1
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function mesa_estado_limpio($id_mesa){
        try {
            $sql = "update mesas set
                mesa_estado_atencion = 0
                where id_mesa = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_mesa
            ]);

            $fecha_hoy = date('Y-m-d H:i:s');

            $sql2 = 'insert into mesa_log (id_mesa, mesa_log_estado_atencion, mesa_log_fecha_hora, mesa_log_estado) values (?,?,?,?)';
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute([
                $id_mesa, 0 , $fecha_hoy , 1
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function cambiar_estado_mesa_reserva($id_mesa){
        try {
            $sql = "update mesas set mesa_estado_atencion = 5 where id_mesa = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_mesa
            ]);

            $fecha_hoy = date('Y-m-d H:i:s');

            $sql2 = 'insert into mesa_log (id_mesa, mesa_log_estado_atencion, mesa_log_fecha_hora, mesa_log_estado) values (?,?,?,?)';
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute([
                $id_mesa, 5 , $fecha_hoy , 1
            ]);

            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function buscar_datos_reserva($id_mesa,$fecha){
        try{
            $sql = "select * from mesas m inner join reservas r on m.id_mesa = r.id_mesa where r.id_mesa = ? and date(reserva_fecha) = ? 
                    and reserva_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa,$fecha]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_ultima_reserva(){
        try{
            $sql = "select * from reservas order by id_reserva desc";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_busqueda_productos($parametro){
        try{
            $sql = "select * from producto p 
                    left join grupos g on p.id_grupo = g.id_grupo 
                    inner join producto_precio pp on p.id_producto = pp.id_producto
                    where pp.producto_precio_estado = 1 and p.producto_estado=1 and p.producto_nombre like ? and pp.id_producto_precio <> -1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(['%'.$parametro.'%']);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_busqueda_productos_seleccion($id_producto){
        try{
            $sql = "select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto
                    where p.id_producto = ? and pp.producto_precio_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    //FUNCION DE BUSQUEDA DE CLIENTE
    public function buscar_cliente($parametro){
        try{
            $sql = "select * from clientes where cliente_estado = 1 and cliente_nombre like ? or clientes.cliente_razonsocial like ? or cliente_numero like ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(['%'.$parametro.'%', '%'.$parametro.'%', '%'.$parametro.'%']);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    //NUEVA FUNCION PARA LO DE LA PISCINA
    public function buscar_consumo_piscina($parametro){
        try{
            $sql = "select * from ventas v inner join clientes c on v.id_cliente = c.id_cliente where (v.venta_correlativo like ? or c.cliente_nombre 
                    like ? or c.cliente_numero like ?) 
                    and (v.venta_piscina = 1 or v.venta_piscina = 4) and v.anulado_sunat = 0 and v.venta_cancelar = 1 order by v.id_venta desc";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(['%'.$parametro.'%', '%'.$parametro.'%', '%'.$parametro.'%']);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function direcciones($id_cliente){
        try{
            $sql = "select cliente_direccion as direccion, cliente_direccion_2 as direccion from clientes where id_cliente = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //FUNCION PARA LISTAR LOS TIPOS DE IGV
    public function listar_igv(){
        try{
            $sql = 'select * from igv where igv_estado = 1 order by igv_codigoafectacion asc ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_tipo_afectacion(){
        try{
            $sql = 'select * from tipo_afectacion';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_mesa(){
        try{
            $sql = 'select * from mesas where mesa_estado_atencion = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_mesas_pn($id){
        try{
            $sql = 'select * from mesas where id_mesa <> ? and mesa_estado_atencion = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //FUNCION PARA LISTAR LOS TIPOS DE PAGOS
    public function listar_tipo_pago(){
        try{
            $sql = 'select * from tipo_pago where tipo_pago_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_correlativos(){
        try{
            $sql = 'select * from correlativos limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = [];
        }
        return $return;
    }
    public function listar_correlativos_x_serie($id_serie){
        try{
            $sql = 'select * from serie where id_serie = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_serie]);
            $return = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = [];
        }
        return $return;
    }

    public function listar_clientes(){
        try{
            $sql = 'select * from clientes';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = [];
        }
        return $return;
    }
    public function listar_empresa_1(){
        try{
            $sql = 'select * from empresa where id_empresa = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = [];
        }
        return $return;
    }

    public function listar_cliente_x_numero($numero){
        try{
            $sql = 'select * from clientes where cliente_numero = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$numero]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function jalar_id_venta($fecha ,$id_cliente){
        try{
            $sql = 'select id_venta from ventas where venta_fecha = ? and id_cliente = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha ,$id_cliente]);
            $result = $stm->fetch();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function jalar_id_nota_venta($fecha ,$id_cliente){
        try{
            $sql = 'select id_nota_venta from nota_venta where nota_venta_fecha = ? and id_cliente = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha ,$id_cliente]);
            $result = $stm->fetch();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    //FUNCION PARA GUARDAR UNA VENTA
    public function guardar_pedido_gratis($model){
        try {
            $sql = 'insert into pedidos_gratis (id_mesa, id_usuario,id_comanda, pedido_gratis_numero, pedido_gratis_nombre, pedido_gratis_direccion, pedido_gratis_total,
                           pedido_gratis_observacion, pedido_gratis_datetime, pedido_gratis_codigo) 
                    values (?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_mesa,
                $model->id_usuario,
                $model->id_comanda,
                $model->cliente_numero,
                $model->cliente_nombre,
                $model->cliente_direccion,
                $model->venta_total,
                $model->observacion_cortesia,
                $model->fecha,
                $model->codigo
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function guardar_venta($model){
        try {
            $sql = 'insert into ventas (id_empresa,id_caja_numero, id_caja_cierre,id_usuario,id_mesa, id_cliente,id_turno, id_tipo_pago, id_moneda, venta_direccion, venta_tipo, venta_serie,
                    venta_correlativo, venta_totalgratuita, venta_totalexonerada, venta_totalinafecta, venta_totalgravada, venta_totaligv, 
                    venta_icbper,venta_totaldescuento,venta_total, venta_pago_cliente, venta_vuelto, venta_fecha,venta_mostrar_tp,tipo_documento_modificar, 
                    correlativo_modificar, venta_codigo_motivo_nota, venta_fecha_de_baja,venta_mt,venta_piscina_codigo,cambiar_concepto,concepto_nuevo,codigo_venta) 
                    values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_empresa,
                $model->id_caja_numero,
                $model->id_caja_cierre,
                $model->id_usuario,
                $model->id_mesa,
                $model->id_cliente,
                $model->id_turno,
                $model->id_tipo_pago,
                $model->id_moneda,
                $model->venta_direccion,
                $model->venta_tipo,
                $model->venta_serie,
                $model->venta_correlativo,
                $model->venta_totalgratuita,
                $model->venta_totalexonerada,
                $model->venta_totalinafecta,
                $model->venta_totalgravada,
                $model->venta_totaligv,
                $model->venta_icbper,
                $model->venta_totaldescuento,
                $model->venta_total,
                $model->pago_cliente,
                $model->vuelto_,
                $model->venta_fecha,
                $model->venta_mostrar_tp,
                $model->tipo_documento_modificar,
                $model->correlativo_modificar,
                $model->tipo_nota_id,
                $model->venta_fecha_de_baja,
                $model->venta_mt,
                $model->venta_piscina_codigo,
                $model->cambiar_concepto,
                $model->concepto_nuevo,
                $model->codigo_venta
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    public function guardar_nota_venta($model){
        try {
            $sql = 'insert into nota_venta (id_usuario, id_mesa, id_cliente, id_tipo_pago, id_moneda, nota_venta_tipo, 
                        nota_venta_serie, nota_venta_correlativo, nota_venta_total, nota_venta_pago_cliente, nota_venta_vuelto,
                        nota_venta_fecha) 
                        value (?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario,
                $model->id_mesa,
                $model->id_cliente,
                $model->id_tipo_pago,
                $model->id_moneda,
                $model->venta_tipo,
                $model->venta_serie,
                $model->venta_correlativo,
                $model->venta_total,
                $model->pago_cliente,
                $model->vuelto_,
                $model->venta_fecha
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function  actualizarCorrelativo_x_id_Serie($id_serie, $correlativo){
        try {
            $sql = 'update serie set correlativo = ? where id_serie = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $correlativo, $id_serie
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function guardar_venta_detalle($model){
        try {
            $sql = 'insert into ventas_detalle (id_venta,id_comanda_detalle, venta_detalle_valor_unitario, 
                    venta_detalle_precio_unitario, venta_detalle_nombre_producto, venta_detalle_cantidad, venta_detalle_total_igv,
                    venta_detalle_porcentaje_igv, venta_detalle_total_icbper, venta_detalle_valor_total, venta_detalle_importe_total,id_producto_precio)
                    values(?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_venta,
                $model->id_comanda_detalle,
                $model->venta_detalle_valor_unitario,
                $model->venta_detalle_precio_unitario,
                $model->venta_detalle_nombre_producto,
                $model->venta_detalle_cantidad,
                $model->venta_detalle_total_igv,
                $model->venta_detalle_porcentaje_igv,
                $model->venta_detalle_total_icbper,
                $model->venta_detalle_valor_total,
                $model->venta_detalle_total_price,
                $model->id_producto_precio
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    public function guardar_pedido_gratis_detalle($model){
        try {
            $sql = 'insert into pedidos_gratis_detalles (id_pedido_gratis, id_comanda_detalle)
                    values(?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_pedido_gratis,
                $model->id_comanda_detalle
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    public function guardar_nota_venta_detalle($model){
        try {
            $sql = 'insert into nota_venta_detalle (id_nota_venta, id_comanda_detalle, 
                                nota_venta_detalle_valor_unitario, nota_venta_detalle_precio_valor, nota_venta_detalle_nombre_producto, 
                                nota_venta_detalle_cantidad, nota_venta_detalle_total_igv, nota_venta_detalle_porcentaje_igv, 
                                nota_venta_detalle_total_icbper, nota_venta_detalle_valor_total, nota_venta_detalle_importe_total, 
                                id_producto_precio) 
                                value (?,?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_venta,
                $model->id_comanda_detalle,
                $model->venta_detalle_valor_unitario,
                $model->venta_detalle_precio_unitario,
                $model->venta_detalle_nombre_producto,
                $model->venta_detalle_cantidad,
                $model->venta_detalle_total_igv,
                $model->venta_detalle_porcentaje_igv,
                $model->venta_detalle_total_icbper,
                $model->venta_detalle_valor_total,
                $model->venta_detalle_total_price,
                $model->id_producto_precio
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //FUNCION PARA JALAR DATOS DEL DETALLE Y PONERLO EN LA VENTA DETALLE
    public function jalar_datos($id_comanda_detalle){
        try{
            $sql = 'select * from comanda_detalle cd inner join producto p on cd.id_producto = p.id_producto 
                    inner join producto_precio pp on p.id_producto = pp.id_producto where cd.id_comanda_detalle = ? and pp.producto_precio_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetch();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_pedido_gratis_x_mt($mt){
        try{
            $sql = 'select * from pedidos_gratis where pedido_gratis_codigo = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mt]);
            $result = $stm->fetch();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_pedidos_x_mesa_gratis($id_mesa,$fecha_i, $fecha_f){
        try{
            $sql = 'select * from pedidos_gratis pg inner join mesas m on pg.id_mesa = m.id_mesa inner join usuarios u on pg.id_usuario = u.id_usuario
                    where pg.id_mesa = ? and DATE(pg.pedido_gratis_datetime) between ? and ? order by pg.pedido_gratis_datetime desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa,$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_pedidos_gratis($fecha_i, $fecha_f){
        try{
            $sql = 'select * from pedidos_gratis pg inner join mesas m on pg.id_mesa = m.id_mesa inner join usuarios u on pg.id_usuario = u.id_usuario
                    where DATE(pg.pedido_gratis_datetime) between ? and ? order by pg.pedido_gratis_datetime desc ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function consultar($id_comanda_detalle){
        try{
            $sql = 'select * from ventas_detalle vd inner join comanda_detalle cd on vd.id_comanda_detalle = cd.id_comanda_detalle 
                    where cd.id_comanda_detalle = ? and cd.comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetchAll();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function consultar_($id_comanda_detalle){
        try{
            $sql = 'select * from comanda_detalle where comanda_detalle_estado_venta = 1 and id_comanda_detalle = ? and comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetchAll();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function consultar_existe_en_nota_venta_detalle($id_comanda){
        try{
            $sql = 'select * from nota_venta_detalle vd inner join comanda_detalle cd on vd.id_comanda_detalle = cd.id_comanda_detalle 
                    where cd.id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function cambiar_estado_comanda($id_comanda_detalle){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_estado_venta = 1 where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function cambiar_estado_detalle_cuenta_pago($id_comanda_detalle){
        try{
            $sql = 'update cuentas_detalle set cuentas_detalle_estado_pago = 1 where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function cambiar_estado_comanda_principal($id_comanda){
        try{
            $sql = 'update comanda set comanda_estado = 2 where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function actualizar_estado_mesa($id_mesa){
        try{
            $sql = 'update mesas set mesa_estado_atencion = 2 where id_mesa = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa]);
            $fecha_hoy = date('Y-m-d H:i:s');
            $sql2 = 'insert into mesa_log (id_mesa, mesa_log_estado_atencion, mesa_log_fecha_hora, mesa_log_estado) values (?,?,?,?)';
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute([
                $id_mesa, 2 , $fecha_hoy , 1
            ]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function ocultar_reserva($id_mesa){
        try{
            $sql = 'update reservas set reserva_estado = 2 where id_mesa = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function habilitar_mesa($mesa_estado_atencion,$id_mesa){
        try{
            $sql = 'update mesas set mesa_estado_atencion = ? where id_mesa = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mesa_estado_atencion,$id_mesa]);
            $fecha_hoy = date('Y-m-d H:i:s');

            $sql2 = 'insert into mesa_log (id_mesa, mesa_log_estado_atencion, mesa_log_fecha_hora, mesa_log_estado) values (?,?,?,?)';
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute([
                $id_mesa, $mesa_estado_atencion , $fecha_hoy , 1
            ]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    //FUNCION PARA CAMBIAR EL ESTADO DE LAS MESAS
    public function cambiar_mesa($id_mesa_nuevo, $id_comanda){
        try{
            $sql = 'update comanda set id_mesa = ? where id_comanda = ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa_nuevo,$id_comanda]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function cambiar_cantidad_personas($id_comanda , $comanda_cantidad_personas){
        try{
            $sql = 'update comanda set comanda_cantidad_personas = ? where id_comanda = ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$comanda_cantidad_personas,$id_comanda]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar_ultima_comanda($fecha_buscar){
        try{
            $sql = "select * from comanda where date(comanda_fecha_registro) = ? order by id_comanda desc limit 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_buscar]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function listar_detalles_x_pedido($id_comanda){
        try{
            $sql = "select * from comanda_detalle where id_comanda = ? and comanda_detalle_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function listar_detalles_x_pedido_pagados($id_comanda){
        try{
            $sql = "select * from comanda_detalle where id_comanda = ? and comanda_detalle_estado_venta = 0 and comanda_detalle_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function eliminar_comanda($id_comanda){
        try{
            $sql = 'delete from comanda where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function verificar_pago($id_comanda){
        try{
            $sql = "select * from comanda_detalle where id_comanda = ? and comanda_detalle_estado_venta = 0 and comanda_detalle_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function verificar_existencia_detalle_comanda($id_comanda_detalle){
        try{
            $sql = "select * from nota_venta_detalle where id_comanda_detalle = ? limit 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function verificar_password($user, $pass){
        $result = false;
        try{
            $sql = "Select usuario_contrasenha from usuarios 
                    where id_rol = ? and usuario_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$user]);
            $info = $stm->fetchAll();
            foreach ($info as $i){
                if(password_verify($pass, $i->usuario_contrasenha)){
                    $result = true;
                }
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = false;
        }
        return $result;
    }

    public function verificar_password_cambiado($pass){
        $result = false;
        try{
            $sql = "Select usuario_contrasenha from usuarios 
                    where (id_rol = 5 or id_rol = 2 or id_rol = 3) and usuario_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$pass]);
            $info = $stm->fetchAll();
            foreach ($info as $i){
                if(password_verify($pass, $i->usuario_contrasenha)){
                    $result = true;
                }
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = false;
        }
        return $result;
    }

    public function valor_insumos($id_comanda_detalle){
        try{
            $sql = "select * from comanda_detalle cd inner join producto p on cd.id_producto = p.id_producto inner join detalle_recetas dr on p.id_receta = dr.id_receta
                    inner join recursos_sede rs on dr.id_recursos_sede = rs.id_recurso_sede inner join recursos r on rs.id_recurso = r.id_recurso
                    inner join medida um on rs.id_medida = um.id_medida where cd.id_comanda_detalle = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function capturar_cantidad($id_recurso_sede){
        try{
            $sql = "select * from recursos_sede where id_recurso_sede = ? limit 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso_sede]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function saber_conversion($id_recurso_sede){
        try{
            $sql = "select * from conversiones c inner join recursos_sede rs on c.id_recurso_sede = rs.id_recurso_sede where c.id_recurso_sede = ? limit 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso_sede]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function conversion_por_id($id){
        try{
            $sql = "select * from conversiones c where c.id_conversion = ? limit 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function actualizar_stock($monto, $id){
        try{
            $sql = 'update recursos_sede set recurso_sede_stock = recurso_sede_stock + ? where id_recurso_sede = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$monto, $id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    public function guardar_grupos($model){
        try{
            $sql = 'insert into grupos (grupo_nombre, grupo_ticketera, grupo_fecha_registro, grupo_estado) 
                    values (?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->grupo_nombre,
                $model->grupo_ticketera,
                $model->grupo_fecha_registro,
                $model->grupo_estado
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar_reservas($fecha_hoy){
        try{
            $sql = "select * from reservas r inner join mesas m on r.id_mesa = m.id_mesa where date(reserva_fecha) >= ? and r.reserva_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_hoy]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function eliminar_reserva($id_reserva){
        try{
            $sql = 'delete from reservas where id_reserva = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_reserva]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function mostrar_mesita($id_mesa){
        try{
            $sql = 'update mesas set mesa_estado = 1 where id_mesa = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function jalar_valor_puntos(){
        try{
            $sql = "select * from puntos where puntos_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function guardar_puntos_ventas($modelc){
        try{
            $sql = 'insert into puntos_cliente (id_cliente, id_puntos, puntos_cliente_acumulado, puntos_cliente_fecha_registro, puntos_cliente_estado) 
                    values (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $modelc->id_cliente,
                $modelc->id_puntos,
                $modelc->puntos_cliente_acumulado,
                $modelc->puntos_cliente_fecha_registro,
                $modelc->puntos_cliente_estado
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_venta_x_comanda($id_comanda){
        try{
            $sql = "select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join ventas_detalle vd on cd.id_comanda_detalle = vd.id_comanda_detalle
                    inner join ventas v on vd.id_venta = v.id_venta where c.id_comanda = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function cambiar_estado_comanda_delivery($id){
        try{
            $sql = 'update comanda set comanda_estado = 1 where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function guardar_cambio($estado, $id_mesa){
        try{
            $sql = 'update mesas set mesa_estado = ? where id_mesa = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $estado, $id_mesa
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function jalar_pedidos_mesa_actual($id_comanda){
        try{
            $sql = "select * from comanda_detalle where id_comanda = ? and comanda_detalle_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar_pedidos_mesa_destino($id_comanda){
        try{
            $sql = "select * from comanda_detalle where id_comanda = ? and comanda_detalle_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function extraer_id_comanda_destino($id_mesa_pn){
        try{
            $sql = "select * from comanda where id_mesa = ? order by id_comanda desc limit 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_mesa_pn]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function agregar_pedidos_mesa_destino($model){
        try {
            $sql = 'insert into comanda_detalle (id_comanda, id_producto, comanda_detalle_precio, comanda_detalle_cantidad, comanda_detalle_despacho, 
                    comanda_detalle_total, comanda_detalle_observacion, comanda_detalle_fecha_registro, comanda_detalle_estado,comanda_detalle_estado_venta,codigo) 
                    values(?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_comanda,
                $model->id_producto,
                $model->comanda_detalle_precio,
                $model->comanda_detalle_cantidad,
                $model->comanda_detalle_despacho,
                $model->comanda_detalle_total,
                $model->comanda_detalle_observacion,
                $model->comanda_detalle_fecha_registro,
                $model->comanda_detalle_estado,
                $model->comanda_detalle_estado_venta,
                $model->codigo
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function eliminar_detalles_transferidos($id_comanda_detalle){
        try{
            $sql = 'delete from comanda_detalle where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_comanda_transferida($id_comanda){
        try{
            $sql = 'delete from comanda where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function actializar_id_comanda($id_comanda_nuevo,$id_comanda_){
        try{
            $sql = 'update comanda_detalle set id_comanda = ? where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_nuevo,$id_comanda_]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function sumar_valor_comandas($total_comanda,$id_comanda_nuevo){
        try{
            $sql = 'update comanda set comanda_total = comanda_total + ? where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$total_comanda,$id_comanda_nuevo]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //FUNCIONES NUEVAS
    public function actualizar_ultimos_pedidos_pendientes($id_comanda, $id_mesa){
        try{
            $sql = 'update comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda set cd.id_comanda = ?
                    where c.id_mesa = ? and cd.comanda_detalle_estado_venta = 0 and cd.comanda_detalle_estado = 1 and cd.comanda_detalle_lugar_final = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda, $id_mesa]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function actualizar_total_comanda($id_comanda){
        try{
            $sql = 'select sum(comanda_detalle_total) total from comanda_detalle c where c.id_comanda = ? and c.comanda_detalle_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $resultadito = $stm->fetch();

            $sql2 = 'update comanda set comanda_total = ? where id_comanda = ?';
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute([$resultadito->total, $id_comanda]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function eliminar_comanda_detalle_todo($comanda_detalle_eliminacion,$fecha_eliminacion,$id_comanda){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_eliminacion = ?, comanda_detalle_fecha_eliminacion = ?, comanda_detalle_estado = 0 
                    where id_comanda = ? and comanda_detalle_estado_venta = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$comanda_detalle_eliminacion,$fecha_eliminacion,$id_comanda]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar_detalle_x_comanda_x_grupo_($id_comanda, $grupo){
        try{
            $sql = 'select * from comanda c inner join comanda_detalle cd on c.id_comanda = cd.id_comanda inner join producto p 
                    on cd.id_producto = p.id_producto where cd.id_comanda = ? and (p.id_grupo = ? or p.id_grupo = 3)  and cd.comanda_detalle_estado = 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda, $grupo]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //FUNCIONES NUEVAS PARA DIVIDIR PEDIDO
    public function obtener_detalle_comanda($id){
        try{
            $sql = 'select * from comanda_detalle where id_comanda_detalle = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function cambiar_cantidad_detalle_comanda($id){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_cantidad = comanda_detalle_cantidad - 1 where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function modificar_monto($monto,$id){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_total = ? where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$monto,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function buscar_id_producto($id_producto,$id_comanda){
        try{
            $sql = 'select * from comanda_detalle where id_producto=? and id_comanda =? and comanda_detalle_estado_venta = 0 and comanda_detalle_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto,$id_comanda]);
            $result = $stm->fetch();
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function update_comanda_detalle($cantidad_e,$total_e,$id_producto,$id_comanda){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_cantidad = comanda_detalle_cantidad + ?, comanda_detalle_total = comanda_detalle_total+?
                    where id_producto = ? and id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$cantidad_e,$total_e,$id_producto,$id_comanda]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_habitaciones(){
        try {
            $sql ='SELECT * FROM habitacion_estado he
                    inner join habitaciones h on he.id_habitacion=h.id_habitacion
                    where habitacion_estado_fecha= curdate() order by h.habitacion_nro asc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([]);
            return $stm->fetchAll();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar_habitaciones_id($id){
        try {
            $sql ='select habitacion_nro from habitaciones where id_habitacion = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function dato_cliente_habitacion($id){
        try{
            $sql="SELECT * FROM `rack` r inner join clientes c on r.id_cliente=c.id_cliente inner join monedas m on r.id_moneda=m.id_moneda 
                  where curdate() BETWEEN rack_in and rack_out and rack_checkout is NULL and id_habitacion=? order by r.id_rack desc";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function vincular_habitacion($id_comanda,$id_rack){
        try {
            $sql = "update comanda set id_rack=? where id_comanda=?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_rack,$id_comanda]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function cambiar_estado_detalle_comanda($id_usuario,$id_comanda){
        try {
            $sql = "update comanda_detalle set comanda_detalle_estado=1,comanda_detalle_estado_venta=2,comanda_detalle_lugar_final=2,id_usuario_a=? where id_comanda=?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_usuario,$id_comanda]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function cambiar_estado_detalle_comanda_cargo($id_usuario,$id_comanda){
        try {
            $sql = "update comanda_detalle set comanda_detalle_estado=1,comanda_detalle_lugar_final=3,id_usuario_a=? where id_comanda=?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_usuario,$id_comanda]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function listar_comanda_detalle_x_id($id_comanda){
        try {
            $sql = "select * from comanda_detalle where id_comanda=? and comanda_detalle_estado_venta=0 and comanda_detalle_estado = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            return $stm->fetchAll();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function correlativo($id_comanda){
        try {
            $sql = "select * from comanda where id_comanda=? and comanda_estado=1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function jalar_id_caja_cierre($id_caja,$id_usuario){
        try {
            $sql ="SELECT * FROM caja_cierre where id_caja=? and id_usuario=? and date(caja_cierre_apertura_datetime)=curdate() order by id_caja_cierre desc limit 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$id_usuario]);
            return $stm->fetch();
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function modificar_total_rack($total,$id_rack){
        try {
            $sql = "update rack set rack_tarifa= rack_tarifa + ? where id_rack=?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$total,$id_rack]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(),get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //FUNCIONES NUEVAS
    public function obtener_acompas($id_producto){
        try {
            $sql = 'select * from acompanamiento
                    where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function obtener_detalles($id_acompanamiento){
        try {
            $sql = 'select * from acompanamiento_detalle
                    where id_acompanamiento = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_acompanamiento]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function buscar_guardar_cd($micro){
        try {
            $sql = 'select id_comanda_detalle from comanda_detalle where codigo = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$micro]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }


    public function llenar_tabla_fantasma($model){
        try {
            $sql = 'insert into comanda_acomp_detalle (id_usuario, id_comanda_detalle, id_acompanamiento_detalle, comanda_acomp_fecha, comanda_acomp_estado) 
                    values (?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario,
                $model->id_comanda_detalle,
                $model->id_acompanamiento_detalle,
                $model->comanda_acomp_fecha,
                $model->comanda_acomp_estado
            ]);
            return 1;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2;
        }
    }


    public function search_new_table($id_comanda_detalle){
        try {
            $sql = 'select id_acompanamiento_detalle from comanda_acomp_detalle where id_comanda_detalle=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function id_del_detalle($id){
        try {
            $sql = 'select * from recete_preferencia where id_acompanamiento_detalle=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function valor_final($id){
        try {
            $sql = 'select * from receta_preferencia_detalle where id_receta_preferencia=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function restar_stock_again($cantidad, $recurso){
        try{
            $sql = 'update recursos_sede set recurso_sede_stock = recurso_sede_stock - ? where id_recurso = ? and recurso_sede_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$cantidad, $recurso]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function sacar_id_recurso_sede($recurso){
        try {
            $sql = 'select id_recurso_sede from recursos_sede where id_recurso = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$recurso]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function restar_stock_again_($cantidad, $recurso){
        try{
            $sql = 'update recursos_sede set recurso_sede_stock = recurso_sede_stock - ? where id_recurso = ? and recurso_sede_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$cantidad, $recurso]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //FUNCIONES NUEVAS PARA LA RESTA DE STOCK
    //FUNCION NUEVA PARA OBTENER EL STOCK ACTUAL DE UN INSUMO
    public function stock_actual_comparacion($id_recurso){
        try{
            $sql = 'select cantidad_nueva_distribuida from recurso_distribuido where id_recurso_sede =? and recurso_distribuido_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function restar_nueva_tabla_stock_cero($id_recurso){
        try{
            $sql = 'update recurso_distribuido set cantidad_nueva_distribuida = 0 where id_recurso_sede = ? and recurso_distribuido_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_recurso]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //FUNCION PARA RESTAR STOCK DE LA NUEVA TABLA
    public function restar_nueva_tabla_stock($cantidad_restar,$id_recurso){
        try{
            $sql = 'update recurso_distribuido set cantidad_nueva_distribuida = cantidad_nueva_distribuida - ? 
                    where id_recurso_sede = ? and recurso_distribuido_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$cantidad_restar,$id_recurso]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function cantida_detalle($id_comanda_detalle){
        try{
            $sql = 'select * from comanda_detalle where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda_detalle]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    //CAMBIO DE ESTADO
    public function cambio_estado_bol_p($id){
        try{
            $sql = 'update ventas set venta_piscina = 0 where id_venta = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function actu_id_cd($id_user,$id){
        try{
            $sql = 'update comanda_detalle set id_usuario_a = ? where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_user,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function cambiar_detalle_comanda_estados($id_user,$id){
        try{
            $sql = 'update comanda_detalle set comanda_detalle_estado_venta = 1, id_usuario_a = ? where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_user,$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //FUNCION CREADA EN EL SAMIRIA PARA JUSTIFICAR EL SUELDO
    public function buscar_cortesia($id_comanda){
        try{
            $sql = 'select * from pedidos_gratis where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function actualizar_estado_boleta($id){
        try{
            $sql = 'update ventas set venta_piscina = 2 where id_venta = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    public function actualizar_id_comanda_en_detalle($id_comanda,$id_detalle_comanda){
        try{
            $sql = 'update comanda_detalle set id_comanda = ? where id_comanda_detalle = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_comanda,$id_detalle_comanda]);
            return 1;
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function sumar_valor_comandas_($total_comanda,$id_comanda_nuevo){
        try{
            $sql = 'update comanda set comanda_total =  ? where id_comanda = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$total_comanda,$id_comanda_nuevo]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function buscar_user($id_user){
        try{
            $sql = 'select * from personas p inner join usuarios u on p.id_persona = u.id_persona where u.id_usuario=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_user]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //FUNCIONES PARA LAS CUENTAS POR COBRAR

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

    public function guardar_cuenta_por_cobrar($model){
        try{
            $sql = 'insert into cuentas (id_usuario, id_cliente, cuentas_total, cuentas_total_pagado, cuenta_fecha_creacion, cuenta_cancelado, cuenta_estado, cuenta_codigo,id_moneda)
                    values (?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$model->id_usuario,$model->id_cliente,$model->cuentas_total,$model->cuentas_total_pagado,$model->cuenta_fecha_creacion,$model->cuenta_cancelado,
                            $model->cuenta_estado,$model->cuenta_codigo,$model->id_moneda]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function sacar_id_cuenta($codigo){
        try{
            $sql = 'select id_cuenta from cuentas where cuenta_codigo = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$codigo]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function guardar_detalle_por_cobrar($model){
        try{
            $sql = 'insert into cuentas_detalle (id_cuenta, id_usuario, id_rack_detalle, id_comanda_detalle, cuentas_detalle_comanda_correlativo, 
                    cuentas_detalle_fecha_creacion, cuenta_detalle_estado) values (?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$model->id_cuenta,$model->id_usuario,$model->id_rack_detalle,$model->id_comanda_detalle,$model->cuentas_detalle_comanda_correlativo,
                          $model->cuentas_detalle_fecha_creacion,$model->cuenta_detalle_estado]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function receta_sin_insunmos(){
        try{
            $sql = 'select count(distinct id_receta) as total from detalle_recetas';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function receta_faltante(){
        try{
            $sql = 'SELECT COUNT(*) AS cantidad_faltante FROM recetas WHERE id_receta NOT IN (SELECT DISTINCT id_receta FROM detalle_recetas)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    //FUNCION PARA BUSCAR STOCK
    public function buscar_stock($id_receta){
        try{
            $sql = 'select * from producto p inner join recetas r on p.id_receta = r.id_receta inner join detalle_recetas dr on r.id_receta = dr.id_receta 
                    inner join recurso_distribuido rd on dr.id_recursos_sede = rd.id_recurso_sede
                    where r.id_receta = ? and rd.recurso_distribuido_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_receta]);
            return $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];
        }
    }

    public function revisar_comandas_stock($id_producto){
        try{
            $sql = 'select * from comanda_detalle where id_producto=? and comanda_detalle_estado = 1 and comanda_detalle_estado_venta = 0 and comanda_detalle_lugar_final = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            return $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return [];

        }
    }

}
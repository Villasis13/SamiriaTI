<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 29/10/2018
 * Time: 9:59
 */

class Inventario{
    private $pdo;
    private $log;
    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    //Listar Productos Registrados
    public function listar_productos(){
        try{
            $sql = "Select * from producto p inner join producto_precio p2 on p.id_producto = p2.id_producto inner join proveedores p3 on p2.id_proveedor = p3.id_proveedor inner join categorias c 
                    on p.id_categoria = c.id_categoria inner join medida m on p2.id_medida = m.id_medida  where p.producto_estado = 1 order by p.producto_nombre";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    public function listar_productos_con_stock(){
        try{
            $sql = "Select * from producto p inner join producto_precio p2 on p.id_producto = p2.id_producto inner join proveedores p3 on p2.id_proveedor = p3.id_proveedor inner join categorias c 
                    on p.id_categoria = c.id_categoria inner join medida m on p2.id_medida = m.id_medida  where p.producto_estado = 1 and p.producto_stock > 0 order by p.producto_nombre";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //VALIDACION
    public function validar($id_producto){
        try{
            $sql = "select * from ventas_detalle pvd inner join producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio 
                    inner join producto p on pp.id_producto = p.id_producto where p.id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Obtener ID Producto Según Fecha Única de Registro
    public function jalar_producto($dato_creacion){
        try{
            $sql = 'select id_producto from producto where producto_codigo = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$dato_creacion]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result->id_producto;
    }

    //Agregar o Editar Producto
    public function save($model){
        try {
            if(empty($model->id_producto)){
                $sql = 'insert into producto (id_categoria, id_usuario, producto_codigo_barra, producto_nombre, 
                        producto_stock, producto_creacion, producto_estado,producto_codigo) 
                        values(?,?,?,?,?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_categoria,
                    $model->id_usuario,
                    $model->producto_codigo_barra,
                    $model->producto_nombre,
                    $model->producto_stock,
                    $model->producto_creacion,
                    $model->producto_estado,
                    $model->producto_codigo
                ]);
            } else {
                $sql = "update producto
                set
                id_categoria = ?,
                id_usuario = ?,
                producto_codigo_barra = ?,
                producto_nombre = ?,
                producto_stock = ?
                where id_producto = ?";

                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_categoria,
                    $model->id_usuario,
                    $model->producto_codigo_barra,
                    $model->producto_nombre,
                    $model->producto_stock,
                    $model->id_producto
                ]);
            }
            $result = 1;
        } catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }

    //Listar Producto Registrado
    public function listProducs($id){
        try{
            $sql = "Select * from producto where id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Listar Producto Registrado
    public function listProductwithprice($id){
        try{
            $sql = "Select * from producto p inner join producto_precio p2 on p.id_producto = p2.id_producto inner join proveedores p3 on 
                    p2.id_proveedor = p3.id_proveedor inner join medida m on p2.id_medida = m.id_medida where p.id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }

    //Obtener ID Producto Por Nombre
    public function getProductID($name){
        try{
            $sql = "Select * from producto where producto_nombre = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$name]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result->id_producto;
    }

    //Obtener ID Productsale Por ID Product
    public function getIdProductSaleForIdProduct($id){
        try{
            $sql = "Select id_producto_precio from producto_precio where id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }

        return $result->id_producto_precio;
    }

    //Obtener ID Product Por ID Productsale
    public function getIdProductIdForProductSale($id){
        try{
            $sql = "Select id_product from productforsale where id_productforsale = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }

        return $result->id_product;
    }

    //Eliminar Producto Registrado
    public function eliminar_producto_precio($id){
        try{
            $sql = "delete from producto_precio where id_producto_precio = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Eliminar Producto Registrado
    public function eliminar_producto($id){
        try{
            $sql = 'update producto set producto_estado = 0 where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function quitar_producto($id){
        try{
            $sql = 'delete from startproduct where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function borrar_todo($id){
        try{
            $sql = 'delete from producto where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Listar Productos Para Venta
    public function listProductsforsale($id){
        try{
            $sql = "select * from product p inner join productforsale pf on p.id_product = pf.id_product where p.id_product = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    //Listar Producto Registrado
    public function listar_producto($id){
        try{
            $sql = "Select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto where p.id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Listar Productos Para Venta
    public function listProductsforsale2($id){
        try{
            $sql = "select * from producto p inner join producto_precio pf on p.id_producto = pf.id_producto where p.id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    //Lista Nombre Del Producto
    public function listProductname($id){
        try{
            $sql = "select id_product, product_name from product where id_product = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    //Guardar Precio Producto
    public function guardar_precio($model2){
        try {
            if(empty($model2->id_producto_precio)){
                $sql = 'insert into producto_precio (id_producto, id_proveedor, id_medida, producto_precio_codigoafectacion, producto_precio_unidad, 
                        producto_precio_valor,producto_precio_valor_xmayor, producto_precio_compra) values(?,?,?,?,?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model2->id_producto,
                    $model2->id_proveedor,
                    $model2->id_medida,
                    $model2->producto_precio_codigoafectacion,
                    $model2->producto_precio_unidad,
                    $model2->producto_precio_valor,
                    $model2->producto_precio_valor_xmayor,
                    $model2->producto_precio_compra
                ]);
            } else {
                $sql = "update producto_precio set
                producto_precio_valor = ?,
                producto_precio_valor_xmayor = ?,
                id_proveedor = ?
                where id_producto_precio = ?";
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model2->producto_precio_valor,
                    $model2->producto_precio_valor_xmayor,
                    $model2->id_proveedor,
                    $model2->id_producto_precio
                ]);
            }
            $result = 1;
        } catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }
    //Listar Datos Precio Producto
    public function listProductprice($id){
        try{
            $sql = "Select * from productforsale where id_productforsale = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }
    //Listar ID Product desde ProductSale
    public function listIdproducforproductsale($id){
        try{
            $sql = "Select * from product p inner join productforsale p2 on p.id_product = p2.id_product where p2.id_productforsale = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            $result = $stm->fetch();
            $result = $result->id_product;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }

        return $result;
    }
    //Listar Datos Precio Productos
    public function listProductprices(){
        try{
            $sql = "Select * from productforsale pr inner join product p on pr.id_product = p.id_product inner join categoryp c on p.id_categoryp = c.id_categoryp";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }
    //Eliminar Precio Producto Registrado
    public function deleteProductprice($id){
        try{
            $sql = "delete from productforsale where id_productforsale = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Listar Alquileress Registrados
    public function listRents(){
        try{
            $sql = "Select * from rent";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();

            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }

    //Guardar Precio Alquiler
    public function saveRent($model){
        try {
            if(empty($model->id_rent)){
                $sql = 'insert into rent(
                    rent_name, rent_description, rent_timeminutes, rent_cost
                    ) values(?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->rent_name,
                    $model->rent_description,
                    $model->rent_timeminutes,
                    $model->rent_cost
                ]);

            } else {
                $sql = "update rent
                set
                rent_name = ?,
                rent_description = ?,
                rent_timeminutes = ?,
                rent_cost = ?
                where id_rent = ?";

                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->rent_name,
                    $model->rent_description,
                    $model->rent_timeminutes,
                    $model->rent_cost,
                    $model->id_rent
                ]);
            }
            $result = 1;
        } catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }

    //Listar Renta
    public function listRent($id){
        try{
            $sql = "Select * from rent where id_rent = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }
    //Borrar Alquiler
    public function deleteRent($id){
        try{
            $sql = "delete from rent where id_rent = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Listar Objetos
    public function listObjects(){
        try{
            $sql = "Select * from object";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Guardar Precio Alquiler
    public function saveObject($model){
        try {
            if(empty($model->id_object)){
                $sql = 'insert into object(
                    object_name, object_description, object_total
                    ) values(?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->object_name,
                    $model->object_description,
                    $model->object_total
                ]);

            } else {
                $sql = "update object
                set
                object_name = ?,
                object_description = ?,
                object_total = ?
                where id_object = ?";

                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->object_name,
                    $model->object_description,
                    $model->object_total,
                    $model->id_object
                ]);
            }
            $result = 1;
        } catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }

    //Listar Objeto
    public function listObject($id){
        try{
            $sql = "Select * from object where id_object = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Borrar Objeto
    public function deleteObject($id){
        try{
            $sql = "delete from object where id_object = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Actualizar Stock
    public function saveProductstock($producto_stock, $id_producto){
        try {
            $sql = 'update producto set producto_stock = producto_stock + ? where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $producto_stock,
                $id_producto
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function guardar($id_proveedor, $precio,$producto_precio_valor_xmayor, $producto_precio_compra, $id_producto){
        try{
            $sql = 'update producto_precio set id_proveedor = ?, producto_precio_valor = ?,producto_precio_valor_xmayor = ?, producto_precio_compra = ? where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_proveedor,
                $precio,
                $producto_precio_valor_xmayor,
                $producto_precio_compra,
                $id_producto
            ]);
            $result = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function save_stocklog($model){
        try{
            $sql = 'insert into stocklog (id_producto, id_turno,id_proveedor, stocklog_precio_compra_producto, stocklog_added, stocklog_guide, stocklog_description, stocklog_date) values (?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_producto,
                $model->id_turno,
                $model->id_proveedor,
                $model->stocklog_precio_compra_producto,
                $model->stocklog_added,
                $model->stocklog_guide,
                $model->stocklog_description,
                $model->stocklog_date
            ]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function saveoutProductstock($stock, $id_producto){
        try {
            $sql = 'update producto set producto_stock = producto_stock - ? where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $stock,
                $id_producto
            ]);
            $result = 1;
        } catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        return $result;
    }

    //FUNCION PARA GUARDAR LA SALIDA DEL STOCK
    public function salida_stock($model){
        try{
            $sql = 'insert into stockout (id_producto, id_turno, stockout_out, stockout_guide, stockout_description, stockout_destiny, stockout_ruc, stockout_origin, stockout_date) values (?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_producto,
                $model->id_turno,
                $model->stockout_out,
                $model->stockout_guide,
                $model->stockout_description,
                $model->stockout_destiny,
                $model->stockout_ruc,
                $model->stockout_origin,
                $model->stockout_date
            ]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    //Listar Locaciones Alquiler
    public function listlocations(){
        try {
            $sql = 'select * from location order by location_name asc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Ver si Locacion está disponible
    public function viewstatuslocacion(){
        try {
            $sql = 'select * from location l  inner join salerent order by location_name asc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function listTypelocations(){
        try {
            $sql = 'select * from typelocation order by typelocation_name asc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function selectLocationstype($id){
        try {
            $sql = 'select * from location l inner join typelocation t on l.id_typelocation = t.id_typelocation where t.id_typelocation = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    //Insertar Stock Producto Recien Creado
    public function setStockNew($id_producto, $fecha, $stock){
        try {
            $sql = 'insert into startproduct(id_producto, fecha_registro, startproduct_stock) values (?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_producto,
                $fecha,
                $stock
            ]);
            $result = 1;

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
        return $result;
    }

    public function consultar_agregados_inve($id){
        try{
            $sql = 'select * from stocklog st inner join producto p on st.id_producto = p.id_producto where st.id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function consultar_agregados_inve_($id){
        try{
            $sql = 'select * from producto where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function consultar_ventas_inve($id_producto){
        try{
            $sql = 'select * from ventas pv inner join ventas_detalle vd on 
                    pv.id_venta = vd.id_venta inner join producto_precio pp on vd.id_producto_precio = pp.id_producto_precio inner join producto p on pp.id_producto = p.id_producto
                    inner join clientes c on pv.id_cliente = c.id_cliente inner join usuarios u on p.id_usuario = u.id_usuario 
                    inner join personas p2 on u.id_persona = p2.id_persona where pp.id_producto = ? 
                    and pv.venta_total <> 0 and pv.venta_cancelar <> 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function salidas_stock_inve($id_producto){
        try{
            $sql = 'select * from stockout st inner join producto p on st.id_producto = p.id_producto where st.id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function salidas_stock_inve_($id_producto){
        try{
            $sql = 'select * from stockout st inner join producto p on st.id_producto = p.id_producto where st.id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function valor_total($id_producto){
        try{
            $sql = 'select sum(venta_detalle_importe_total) total from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta inner join producto_precio pp on vd.id_producto_precio = pp.id_producto_precio
                    where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

}
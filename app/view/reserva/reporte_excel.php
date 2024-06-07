<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservas</h1>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table border="1">
                <thead>
                <tr>
                    <td style="text-align: center">Cliente</td>
                    <td style="text-align: center">Fechas</td>
                    <td style="text-align: center">Habitaciones</td>
                    <td style="text-align: center">Observaciones</td>
                    <td style="text-align: center">Num</td>
                    <td style="text-align: center">Contacto</td>
                    <td style="text-align: center">Origen</td>
                    <!--<td>Eliminar</td>-->
                </tr>
                </thead>
                <tbody>
                <?php
                if(count($reservas)>0){
                    foreach ($reservas as $dc){
                        $detalle="";
                        if($dc->cant_tipo_habitacion!=0){
                            $data_tipo=$this->hotel->listar_tipo_habitacion_por_id($dc->id_tipo_habitacion);
                            $detalle.=$dc->cant_tipo_habitacion." ".$data_tipo->habitacion_tipo_nombre." S/. ".$dc->tarifa_tipo_habitacion;
                        }if($dc->cant_tipo_habitacion_2!=0){
                            $data_tipo=$this->hotel->listar_tipo_habitacion_por_id($dc->id_tipo_habitacion_2);
                            $detalle.=$dc->cant_tipo_habitacion_2." ".$data_tipo->habitacion_tipo_nombre." S/. ".$dc->tarifa_tipo_habitacion_2;
                        }if($dc->cant_tipo_habitacion_3!=0){
                            $data_tipo=$this->hotel->listar_tipo_habitacion_por_id($dc->id_tipo_habitacion_3);
                            $detalle.=$dc->cant_tipo_habitacion_3." ".$data_tipo->habitacion_tipo_nombre." S/. ".$dc->tarifa_tipo_habitacion_3;
                        }
                        ?>
                            <tr>
                            <td style="text-align: center"><?= $dc->reserva_dni." ".$dc->reserva_nombre ?></td>
                            <td style="text-align: center"><?= $dc->reserva_in. " al ". $dc->reserva_out ?></td>
                            <td style="text-align: center"><?= $detalle ?></td>
                            <td style="text-align: center"><?= $dc->reserva_obs ?></td>
                            <td style="text-align: center"><?= $dc->reserva_numero ?></td>
                            <td style="text-align: center"><?= $dc->reserva_contacto ?></td>
                            <td style="text-align: center"><?= $dc->reserva_origen ?></td>
                            <!--<td><button class="btn btn-danger" onclick="eliminar_reserva(<?= $dc->id_reserva ?>)"><i class="fa fa-times"></i></button></td>-->
                        </tr>
                        <?php
                    }
                }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
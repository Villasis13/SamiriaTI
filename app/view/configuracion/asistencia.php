<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Asistencia</h1>
    </div>
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <form id="fupForm1" enctype="multipart/form-data" method="post">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">SUBIR CSV</div>
                            <div class="row">
                                <div class="col-md-4">
                                    <br><input type="file" class="form-control" id="archivo_csv" name="archivo_csv" accept="text/csv">
                                </div>
                                <div class="col-md-2">
                                    <br><button type="submit" id="btn-validar-archivo" class="btn btn-success" style="width: 100%"><i class="fa fa-check-square"></i> Validar archivo</button><br><br>
                                </div>
                            </div>
                            </form>
                            <div class="row" id="result_csv" style="display: none">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Resultados del archivo subido</div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Cargo</th>
                                        <th>Tipo</th>
                                        <th>Hora</th>
                                    </tr>
                                    </thead>
                                    <tbody id="result_tabla"></tbody>
                                </table>
                                <button type="submit" onclick="subir_asistencias()" id="btn-subir-asistencias" class="btn btn-danger" style="width: 100%"><i class="fa fa-upload"></i> Subir asistencias</button>
                                <input type="hidden" id="data_subir">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">REPORTE DE ASISTENCIA</div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="desde">Desde</label>
                                    <input type="date" class="form-control" id="desde">
                                </div>
                                <div class="col-md-3">
                                    <label for="hasta">Hasta</label>
                                    <input type="date" class="form-control" id="hasta">
                                </div>
                                <div class="col-md-2">
                                    <br><button id="btn-buscar-asistencias" onclick="buscar_asistencias()" class="btn btn-primary" style="width: 100%"><i class="fa fa-search"></i> Buscar</button><br><br>
                                </div>
                            </div>
                            <div class="row" id="results_div" style="display: none">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Listado de habitaciones</div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Cargo</th>
                                        <th>Tipo</th>
                                        <th>Hora</th>
                                    </tr>
                                    </thead>
                                    <tbody id="resultados_asistencia">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    var datita = [];
    $(document).ready( function () {
        $("#fupForm1").on('submit', function(e){
            e.preventDefault();
            $("#result_csv").hide();
            $.ajax({
                type:"POST",
                url: urlweb + "api/Configuracion/validar_csv",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                dataType: 'json',
                beforeSend: function () {
                    $("#btn-validar-archivo").attr("disabled", true);
                },
                success:function (r) {
                    $("#btn-validar-archivo").attr("disabled", false);
                    datita=r.result.data;
                    console.log(r.result.tabla);
                    switch(r.result.code){
                        case 1:
                            respuesta("Leído correctamente","success");
                            $("#result_csv").show();
                            $("#result_tabla").html(r.result.tabla);
                            $("#data_subir").val(r.result.data_subir);
                            break;
                        case 2:respuesta("Ocurrió un error al leer el archivo","error");break;
                        case 3:respuesta("No se subió ningun archivo","error");break;
                        case 4:respuesta("No se subió archivo con formato CSV","error");break;
                        case 5:respuesta("EL archivo subido está vacío","error");break;
                    }
                }
            });
        });
    });
    function subir_asistencias() {
        $.ajax({
            type:"POST",
            url: urlweb + "api/Configuracion/guardar_asistencias",
            data: "data="+$("#data_subir").val(),
            dataType: 'json',
            beforeSend: function () {
                $("#btn-subir-asistencias").attr("disabled", true);
            },
            success:function (r) {
                $("#btn-subir-asistencias").attr("disabled", false);
                if(r.result.code==1){
                    location.reload();
                }else{
                    respuesta("Ocurrió un error","error");
                }
            }
        });
    }
    function buscar_asistencias() {
        $.ajax({
            type:"POST",
            url: urlweb + "api/Configuracion/buscar_asistencias",
            data: "desde="+$("#desde").val()+"&hasta="+$("#hasta").val(),
            dataType: 'json',
            beforeSend: function () {
                $("#btn-buscar-asistencias").attr("disabled", true);
            },
            success:function (r) {
                $("#btn-buscar-asistencias").attr("disabled", false);
                $("#results_div").show();
                $("#resultados_asistencia").html(r.result);
            }
        });
    }
</script>
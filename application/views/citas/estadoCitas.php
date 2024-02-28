<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>

    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Secuencia de proceso</h4>
        </header>
        <div class="panel-body">

            <div class="row">
                
                <!-- Agregar 5 pasos con iconos -->
                <div class="col-md-12">
                    <div class="row">

                        

                        <div class="col-md-2">
                            <div class="text-center">
                                <p><strong>INICIO</strong></p>

                                <i class="fas fa-check-circle fa-3x text-success"></i>
                                <p>Reserva de matrícula </p>
                                <p><strong>COMPLETADO</strong></p>

                            </div>

                        </div>

                        <?php if (empty($documentos)) : ?>
                            <div class="col-md-2">
                                <div class="text-center">
                                <p><strong>PASO 1</strong></p>

                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <p>Subida de archivos</p>
                                    <p><strong>COMPLETADO</strong></p>

                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-md-2">
                                <div class="text-center">
                                <p><strong>PASO 1</strong></p>

                                <i class="fas fa-hourglass-half fa-3x text-warning"></i>
                                    <p>Subida de archivos</p>
                                    <p><strong>PENDIENTE</strong></p>

                                    <?php foreach ($documentos as $documento): ?>
                                        <p>-<?php echo $documento['nombre_document']; ?></p>
                                    <?php endforeach; ?>
                                
                                </div>

                            </div>
                        <?php endif; ?>


                        <?php if ($citaAcademicaSeparada['Resultado']=='Aprobado') : ?>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <p><strong>PASO 2</strong></p>
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <p class="font-weight-bold text-success">Cita Academica</p>
                                    <p><strong>COMPLETADA</strong></p>
                                </div>
                            </div>
                        <?php else : ?>
                            <?php if ($citaAcademicaSeparada['Resultado']=='Pendiente' ) : ?>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <p><strong>PASO 2</strong></p>
                                        <i class="fas fa-hourglass-half fa-3x text-warning"></i>
                                        <p class="font-weight-bold text-warning">Cita Academica</p>
                                        <p><strong>RESERVADA</strong></p>
                                    </div>
                                </div>
                            <?php else : ?>

                                <?php if ($citaAcademicaSeparada['Resultado']=='Desaprobado' ) : ?>

                                <div class="col-md-2">
                                    <div class="text-center">
                                        <p><strong>PASO 2</strong></p>
                                        <i class="fas fa-times fa-3x text-danger"></i>
                                        <p class="font-weight-bold text-warning">Cita Academica</p>
                                        <p><strong>DESAPROBADA</strong></p>
                                    </div>
                                </div>

                                <?php else : ?>

                                    <div class="col-md-2">
                                    <div class="text-center">
                                        <p><strong>PASO 2</strong></p>
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                                        <p class="font-weight-bold text-warning">Cita Academica</p>
                                        <p><strong>AÚN NO REALIZADA</strong></p>
                                    </div>
                                    </div>
                                <?php endif; ?>

                            <?php endif; ?>

                            
                        <?php endif; ?>




                        <?php if ($citaPsicologica['Resultado']=='Aprobado') : ?>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <p><strong>PASO 3</strong></p>
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <p class="font-weight-bold text-success">Cita Psicológica</p>
                                    <p><strong>COMPLETADA</strong></p>
                                </div>
                            </div>
                            <?php elseif ($citaPsicologica['Resultado'] == 'Desaprobado') : ?>
                                <div class="col-md-2">
                                <div class="text-center">
                                    <p><strong>PASO 3</strong></p>
                                    <i class="fas fa-times fa-3x text-danger"></i>
                                    <p class="font-weight-bold text">Cita Psicológica</p>
                                    <p><strong>DESAPROBADA</strong></p>
                                </div>
                            </div>
                        <?php else : ?>
                            <?php if ($citaPsicologica['estado']=='Pendiente' || ($citaPsicologica['estado']=='Aceptado'&& $citaPsicologica['Resultado']=='Pendiente') ) : ?>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <p><strong>PASO 3</strong></p>
                                        <i class="fas fa-hourglass-half fa-3x text-warning"></i>
                                        <p class="font-weight-bold text-warning">Cita Psicológica</p>
                                        <p><strong>RESERVADA</strong></p>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <p><strong>PASO 3</strong></p>
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                                        <p class="font-weight-bold text-warning">Cita Psicológica</p>
                                        <p><strong>AÚN NO REALIZADA</strong></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>



                        


                        <?php if ($estadoMatricula[0]['contratoFirmado']) : ?>
                            <div class="col-md-2">
                                <div class="text-center">
                                <p><strong>PASO 4</strong></p>

                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <p>Contrato y firma</p>
                                    <p><strong>COMPLETADO</strong></p>

                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-md-2">
                                <div class="text-center">
                                <p><strong>PASO 4</strong></p>

                                <i class="fas fa-hourglass-half fa-3x text-warning"></i>
                                    <p>Contrato y firma</p>
                                    <p><strong>PENDIENTE</strong></p>

                                  
                                
                                </div>

                            </div>
                        <?php endif; ?>






                        <div class="col-md-2">
                            <div class="text-center">
                                <p><strong>FINALIZACIÓN</strong></p>
                                <i class="fas fa-flag-checkered fa-3x "></i>
                                <p><strong>FIN</strong></p>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Fin de los 5 pasos -->

                
            </div>
        </div>

    </div>

<?php echo form_close(); ?>


<script>
     type="text/javascript">
$('#user_id').on('change', function() {
    var selected_trabajador = $(this).val();

    $.ajax({
        type: 'POST',
        url: base_url +  'Citas/obtenerHorariosPortrabajador', // Ruta del método para obtener los horarios del psicólogo
        data: { trabajador_id: selected_trabajador },
        dataType: 'json',
        success: function(response) {
            var options = '';
            $.each(response, function(index, horario) {
                options += '<option value="' + horario.schedule_id + '">' +horario.fecha+'-' 
                + horario.nombre_aula + '-'  +  horario.dia_semana + '  ' + horario.hora_inicio +' '+  horario.hora_fin +'  '  +'</option>';
            });
            $('#horariotrabajador').html(options);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});


</script>
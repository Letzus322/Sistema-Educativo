<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>


    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> <?php echo $xd?>Enviar contrato</h4>
        </header>

        <?php if ($citaSeparada){ ?>
            <div class="panel-body">
                <p>Cita ya separada.</p>

                <div class="mt-3">
                    <p>¿Desea reprogramar su cita o faltó a su cita? <a href="CitaPsicologicaReprogramar" class="btn-link">Presione aquí</a>.</p>
                </div>

                
                
            </div>

           
           


        <?php }else { ?>

        <div class="panel-body">
                


                <div class="row" id="exist_list">
                    <div class="col-md-12 mb-md">
                        <label class="control-label"><?= translate('Alumno') ?> <span class="required">*</span></label>
                        <div class="form-group">
                            <?php
                            // Crear un array solo con last_name
                            $options = array();
                            foreach ($students as $alumno) {
                                $options[$alumno['id']] = $alumno['last_name'] . ' ' . $alumno['first_name'] ;
                            }

                            // Agregar la opción por defecto "Elegir alumno" al principio del array
                            $options = ['' => 'Elegir alumno'] + $options;

                            echo form_dropdown("id", $options, set_value('id'), "class='form-control' id='parent_id' data-plugin-selectTwo data-width='100%' required");

                            ?>
                            <span class="error"><?= form_error('parent_id') ?></span>
                        </div>
                    </div>
                </div>






    



                <div class="form-group">
                    <label for="archivo">Subir documento de contrato:</label>
                    <input type="file" class="form-control" id="archivo1" name="archivo1" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>
                </div>
             


                <div class="form-group">
                    <label for="archivo">Subir declaracion Jurada 1:</label>
                    <input type="file" class="form-control" id="archivo2" name="archivo2" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>
                </div>

                <div class="form-group">
                    <label for="archivo">Subir declaracion Jurada 2:</label>
                    <input type="file" class="form-control" id="archivo3" name="archivo3" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>
                </div>

                <div class="form-group">
                    <label for="archivo">Subir declaracion Jurada 3:</label>
                    <input type="file" class="form-control" id="archivo4" name="archivo4" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>
                </div>

                <div class="form-group mt-3"> <!-- Agrega un margen top (mt) de 3 -->
                    <button type="submit" class="btn btn-primary">Enviar Resultados</button>
                </div>
        </div>

        <?php } ?>                  
    </div>

<?php echo form_close(); ?>

<script>
     type="text/javascript">
$('#user_id').on('change', function() {
    var selected_psicologo = $(this).val();

    $.ajax({
        type: 'POST',
        url: base_url +  'student/obtenerHorariosPorPsicologo', // Ruta del método para obtener los horarios del psicólogo
        data: { psicologo_id: selected_psicologo },
        dataType: 'json',
        success: function(response) {
            var options = '';
            $.each(response, function(index, horario) {
                options += '<option value="' + horario.schedule_id + '">' +horario.fecha+' ' 
                + horario.dia_semana + ' '+ horario.hora_inicio +' '+  horario.hora_fin + '</option>';
            });
            $('#horarioPsicologo').html(options);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});


</script>
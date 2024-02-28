<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>


    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Solicitud de Cita Psicologica</h4>
        </header>

        <?php if ($citaSeparada_array['reprogramada']){ ?>
            <div class="panel-body">
                <p><strong> Su cita ya ha sido reprogramada una vez...<strong></p>

              

                
                
            </div>

           
           


        <?php }else { ?>

        <div class="panel-body">
                
                <!-- Formulario para subir archivos -->
                <div class="form-group">

                    <label for="tipo_documento">Psicologos Disponibles:</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">Seleccionar Psicólogo</option>
                        <?php foreach ($psicologos as $psicologo) : ?>
                            <?php $selected = ($psicologo['user_id'] == $psicologoSeleccionado) ? 'selected' : ''; ?>
                            <option value="<?php echo $psicologo['user_id']; ?>" <?php echo $selected; ?>>
                                <?php echo $psicologo['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                  
                    <label for="tipo_documento">Horarios Disponibles:</label>
                    <select class="form-control" id="horarioPsicologo" name="horarioPsicologo" required>
                        <?php foreach ($horarioPsicologo as $horario) : ?>
                            <option value="<?php echo $horario['id']; ?>"><?php echo $horario['dia_semana']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pregunta1">¿Su niño (niña) presentó alguna dificultad para socializar?</label>
                    <input type="text" class="form-control" id="pregunta1" value="<?php echo $citaSeparada_array['pregunta1']; ?>"  name="pregunta1" required>
                </div>

                <div class="form-group">
                    <label for="pregunta2">¿Qué normas rigen en su hogar que le permiten contribuir a la formación de su hijo?</label>
                    <input type="text" class="form-control" id="pregunta2"  value="<?php echo $citaSeparada_array['pregunta2']; ?>" name="pregunta2" required>
                </div>

                <div class="form-group">
                    <label for="pregunta3">¿Qué expectativas tiene usted de nuestra I.E. para el año 2024?</label>
                    <input type="text" class="form-control" id="pregunta3"value="<?php echo $citaSeparada_array['pregunta3']; ?>"  name="pregunta3" required>
                </div>

                <input type="hidden" name="citaAntigua" value="<?php echo $citaSeparada_array['id']; ?>">

                <input type="hidden" name="horarioPsicologoAntiguo" value="<?php echo $citaSeparada_array['schedule_id']; ?>">

                <div class="form-group mt-3"> <!-- Agrega un margen top (mt) de 3 -->
                    <button type="submit" class="btn btn-primary">Separar Cita</button>
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
        url: base_url +  'Citas/obtenerHorariosPorPsicologo', // Ruta del método para obtener los horarios del psicólogo
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
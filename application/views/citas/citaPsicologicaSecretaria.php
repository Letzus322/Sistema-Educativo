<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>


    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Solicitud de Cita Psicologica</h4>
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
                


                <div class="row" id="exist_list" >
					<div class="col-md-12 mb-md">
						<label class="control-label"><?=translate('Alumno')?> <span class="required">*</span></label>
						<div class="form-group">
							<?php
								// Crear un array solo con last_name
                            $options = array();
                            foreach ($alumnosBranch as $alumno) {
                                $options[$alumno['student_id']] = $alumno['last_name'].' '.$alumno['first_name'];
                            }
                            
                            echo form_dropdown("student_id", $options, set_value('student_id'), "class='form-control' id='parent_id' data-plugin-selectTwo data-width='100%' ");
                            

							?>
							<span class="error"><?=form_error('parent_id')?></span>
						</div>
					</div>
				</div>




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
                    <input type="text" class="form-control" id="pregunta1" name="pregunta1" required>
                </div>

                <div class="form-group">
                    <label for="pregunta2">¿Qué normas rigen en su hogar que le permiten contribuir a la formación de su hijo?</label>
                    <input type="text" class="form-control" id="pregunta2" name="pregunta2" required>
                </div>

                <div class="form-group">
                    <label for="pregunta3">¿Qué expectativas tiene usted de nuestra I.E. para el año 2024?</label>
                    <input type="text" class="form-control" id="pregunta3" name="pregunta3" required>
                </div>


                <div class="form-group mt-3"> <!-- Agrega un margen top (mt) de 3 -->
                    <button type="submit" class="btn btn-primary">Separar Cita</button>
                </div>
        </div>

        <?php } ?>                  
    </div>

<?php echo form_close(); ?>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#horariosModal">Mostrar Alumnos Registrados</button>
<!-- Modal -->
<div class="modal fade" id="horariosModal" tabindex="-1" role="dialog" aria-labelledby="horariosModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="horariosModalLabel">Horarios del Auxiliar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Aquí se mostrarán los horarios -->
                <?php if (!empty($horarios)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>Psicologo</th>

                                <th>Fecha</th>

                                <th>Día de la semana</th>
                                <th>Hora de inicio</th>
                                <th>Hora de fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horarios as $horario): ?>
                                <tr>
                                     <td><?= $horario['apellidoEstudiante'].' '.$horario['nombreEstudiante'] ?></td>
                                     <td><?= $horario['psicologoName'] ?></td>

                                    <td><?= $horario['fecha'] ?></td>

                                    <td><?= $horario['dia_semana'] ?></td>
                                    <td><?= $horario['hora_inicio'] ?></td>
                                    <td><?= $horario['hora_fin'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay horarios disponibles.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
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
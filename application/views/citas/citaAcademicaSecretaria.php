<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>


    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Solicitud de Cita Academica</h4>
        </header>

        <?php if ($citaSeparada){ ?>
            <div class="panel-body">
                <p>Cita ya separada.</p>
                <!-- Aquí podrías agregar el código HTML o mensajes que desees mostrar -->
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


                <div class="form-group">

                    <label for="tipo_documento">Trabajador Disponibles:</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">Seleccionar Trabajador</option>
                        <?php foreach ($trabajadores as $trabajador) : ?>
                            <?php $selected = ($trabajador['user_id'] == $trabajadorSeleccionado) ? 'selected' : ''; ?>
                            <option value="<?php echo $trabajador['user_id']; ?>" <?php echo $selected; ?>>
                                <?php echo $trabajador['name'].'  '.$trabajador['id']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                  
                    <label for="tipo_documento">Horarios Disponibles:</label>
                    <select class="form-control" id="horariotrabajador" name="horariotrabajador" required>
                        <?php foreach ($horariotrabajador as $horario) : ?>
                            <option value="<?php echo $horario['id']; ?>"><?php echo $horario['dia_semana']; ?></option>
                        <?php endforeach; ?>
                    </select>
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

                                <th>Fecha</th>

                                <th>Día de la semana</th>
                                <th>Hora de inicio</th>
                                <th>Hora de fin</th>
                                <th>Nombre del aula</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horarios as $horario): ?>
                                <tr>
                                     <td><?= $horario['apellidoEstudiante'].' '.$horario['nombreEstudiante'] ?></td>
                                    <td><?= $horario['fecha'] ?></td>

                                    <td><?= $horario['dia_semana'] ?></td>
                                    <td><?= $horario['hora_inicio'] ?></td>
                                    <td><?= $horario['hora_fin'] ?></td>
                                    <td><?= $horario['nombre_aula'] ?></td>
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
    var selected_trabajador = $(this).val();

    $.ajax({
        type: 'POST',
        url: base_url +  'student/obtenerHorariosPortrabajador', // Ruta del método para obtener los horarios del psicólogo
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
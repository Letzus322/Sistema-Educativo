<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>


    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Agregar horario de trabajo</h4>
        </header>

     

        <div class="panel-body">
                
               

            <div class="form-group">
            <label class="control-label"><?= translate('Elegir fecha') ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fas fa-calendar"></i></span>
                <input class="form-control" name="fecha" autocomplete="off" required value="<?= set_value('fecha') ?>" data-plugin-datepicker data-plugin-options='{ "startView": 2 }' type="text">
            </div>
            </div>

            <div class="form-group">
                <label for="hora_inicio">Hora de Inicio:</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="hora_fin">Hora de Fin:</label>
                <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nombre_aula"> Aula :</label>
                <input  name="nombre_aula" id="nombre_aula" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="capacidad_aula">Capacidad de aula:</label>
                <input type="number" name="capacidad_aula" id="capacidad_aula" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Agregar horario de atención</button>
            </div>

                    
        </div>
                
    </div>

<?php echo form_close(); ?>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#horariosModal">Mostrar Horarios Registrados</button>
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
                                <th>Fecha</th>

                                <th>Día de la semana</th>
                                <th>Hora de inicio</th>
                                <th>Hora de fin</th>
                                <th>Nombre del aula</th>
                                <th>Cupos disponibles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horarios as $horario): ?>
                                <tr>
                                <td><?= $horario['fecha'] ?></td>

                                    <td><?= $horario['dia_semana'] ?></td>
                                    <td><?= $horario['hora_inicio'] ?></td>
                                    <td><?= $horario['hora_fin'] ?></td>
                                    <td><?= $horario['nombre_aula'] ?></td>
                                    <td><?= $horario['disponible'] ?></td>
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
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
                
                <!-- Formulario para subir archivos -->
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
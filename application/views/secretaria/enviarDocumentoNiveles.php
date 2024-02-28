<?php echo form_open_multipart($this->uri->uri_string()); ?>


    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> <?php echo $xd?>Enviar contrato</h4>
        </header>

  

        <div class="panel-body">
                


        <div class="row" id="exist_list">
            <div class="col-md-12 mb-md">
                <label class="control-label"><?= translate('Nivel') ?> <span class="required">*</span></label>
                <div class="form-group">
                    <?php
                    // Crear un array con todos los niveles
                    $options = array(
                        '1' => 'Nivel Inicial',
                        '2' => 'Primaria',
                        '3' => 'Secundaria'
                    );

                    // Verificar si hay resultados en $this->data['niveles'] y filtrar las opciones
                    if (!empty($this->data['niveles'])) {
                        foreach ($this->data['niveles'] as $nivel) {
                            // Eliminar del array de opciones los niveles encontrados en la base de datos
                            $nivelDB = $nivel['nivel'];
                            if (isset($options[$nivelDB])) {
                                unset($options[$nivelDB]);
                            }
                        }
                    }

                    // Agregar la opción por defecto "Elegir nivel" al principio del array
                    $options = ['' => 'Elegir nivel'] + $options;

                    echo form_dropdown("id", $options, set_value('id'), "class='form-control' id='parent_id' data-plugin-selectTwo data-width='100%' required");
                    ?>
                    <span class="error"><?= form_error('parent_id') ?></span>
                </div>
            </div>
        </div>








    



                <div class="form-group">
                    <label for="archivo">Subir Contrato:</label>
                    <input type="file" class="form-control" id="archivo1" name="archivo1" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>
                </div>

              
                <div class="form-group mt-3"> <!-- Agrega un margen top (mt) de 3 -->
                    <button type="submit"  name="subirArchivo" class="btn btn-primary">Enviar Contrato</button>
                </div>
        </div>




                  
    </div>

<?php echo form_close(); ?>

    <?php
               
                $branch =get_loggedin_branch_id(); 
                $session=get_session_id();
                ?>



<?php echo form_open_multipart($this->uri->uri_string()); ?>

<div class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-file-upload"></i> <?php echo $xd ?> Ver contratos</h4>
    </header>
    <div class="panel-body">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><strong>Contrato a firmar INICIAL:</strong></h5>
                <?php if (pathinfo('Documento_1_1_' . $branch . '_' . $session . '.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                    <a href="<?php echo base_url(str_replace(' ', '_', 'student/verPDFsedes/' . 'Documento_1_1_' . $branch . '_' . $session . '.pdf')); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Descargar contrato inicial
                    </a>
                    <button class="btn btn-danger" data-toggle="modal" name='eliminaArchivo' value="1" data-target="#deleteContractModal1">Eliminar</button>
                <?php else : ?>
                    <span>Tipo de archivo no compatible.</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><strong>Contrato a firmar PRIMARIA:</strong></h5>
                <?php if (pathinfo('Documento_1_2_' . $branch . '_' . $session . '.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                    <a href="<?php echo base_url(str_replace(' ', '_', 'student/verPDFsedes/' . 'Documento_1_2_' . $branch . '_' . $session . '.pdf')); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Descargar contrato inicial
                    </a>
                    <button class="btn btn-danger" data-toggle="modal" name='eliminaArchivo'value="2" data-target="#deleteContractModal1">Eliminar</button>
                <?php else : ?>
                    <span>Tipo de archivo no compatible.</span>
                <?php endif; ?>
            </div>
        </div>


        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><strong>Contrato a firmar SECUNDARIA:</strong></h5>
                <?php if (pathinfo('Documento_1_3_' . $branch . '_' . $session . '.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                    <a href="<?php echo base_url(str_replace(' ', '_', 'student/verPDFsedes/' . 'Documento_1_3_' . $branch . '_' . $session . '.pdf')); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Descargar contrato inicial
                    </a>

                    <button class="btn btn-danger" data-toggle="modal" name='eliminaArchivo' value="3" data-target="#deleteContractModal1">Eliminar</button>
                <?php else : ?>
                    <span>Tipo de archivo no compatible.</span>
                <?php endif; ?>
            </div>
        </div>
        <!-- Repite el mismo patrón para los otros contratos (primaria y secundaria) -->
        <!-- ... -->
    </div>
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
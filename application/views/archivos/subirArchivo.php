<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>
    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Cargar Archivos</h4>
        </header>

        <?php if (empty($documentos)) : ?>
            <div class="panel-body">
                <p>Todos los archivos ya han sido subidos.</p>
                <!-- Aquí podrías agregar el código HTML o mensajes que desees mostrar -->
            </div>
        <?php else : ?>
            <div class="panel-body">
                <p>Por favor, debes cargar los siguientes archivos uno por uno:</p>
                <ul>
                    <!-- Lista de documentos -->
                    <?php foreach ($documentos as $documento) : ?>
                        <li><?php echo $documento['nombre_document']; ?></li>
                    <?php endforeach; ?>
                </ul>

                <!-- Formulario para subir archivos -->
                <div class="form-group">
                  
                    <label for="tipo_documento">Tipo de documento:</label>
                    <select class="form-control" id="tipo_documento" name="tipo_documento" required>
                        <?php foreach ($documentos as $documento) : ?>
                            <option value="<?php echo $documento['id']; ?>"><?php echo $documento['nombre_document']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="archivo">Seleccionar archivo:</label>
                    <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>
                </div>
                <div class="form-group mt-3"> <!-- Agrega un margen top (mt) de 3 -->
                    <button type="submit" class="btn btn-primary">Subir Archivo</button>
                </div>
            </div>
        <?php endif; ?>


    </div>
<?php echo form_close(); ?>

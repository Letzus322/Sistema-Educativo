<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>
    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Enviar Contrato firmado</h4>
        </header>

        <?php if (false) : ?>
            <div class="panel-body">
                <p>Aun no ha completado los procesos anteriores para poder recibir el contrato a firmar.</p>
                <!-- Aquí podrías agregar el código HTML o mensajes que desees mostrar -->
            </div>
            
        <?php else : ?>
            <?php if ($documentos[0]['contratoFirmado']) : ?>
                <div class="panel-body">
                    <p>Ya ha enviado los documentos firmados.</p>
                    <!-- Aquí podrías agregar el código HTML o mensajes que desees mostrar -->
                </div>
            <?php else : ?>

            <?php if (!$reservation_exists) : ?>
                <div class="panel-body">
                    <p>No ha hecho reserva aún para este periodo</p>
            <?php else : ?>

            <div class="panel-body">
            

                <!-- Formulario para subir archivos -->
                <?php
                $user_id=get_loggedin_user_id();
                $this->db->select('*');
                $this->db->from('ewgcgdaj_instituto.student');
                $this->db->join('enroll', 'student.id = enroll.student_id');
                $this->db->where('enroll.session_id', get_session_id());
                $this->db->where('enroll.student_id', $user_id);

                $query = $this->db->get();
                $result = $query->row_array();


                $class = $result['class_id']; // Accede directamente al valor de 'class_id'
                if (in_array($class, [1, 2, 3, 25, 26, 27, 44, 45, 46, 63, 64, 65])) {
                    $nivel = 1;
                } elseif (in_array($class, [4, 5, 6, 7, 8, 9, 28, 29, 30, 31, 32, 33])) {
                    $nivel = 2;
                } elseif (in_array($class, [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 34, 35, 36, 37, 38, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 66, 67, 68, 69, 70])) {
                    $nivel = 3;
                } else {
                    // Asignar un valor por defecto si no coincide con ningún grupo
                    $nivel = 'Valor por defecto';
                }

                $branch =get_loggedin_branch_id(); 
                $session=get_session_id();
                ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><strong> Contrato a firmar:</strong></h5>
                        <?php if (pathinfo('Documento_1_'.$documentos[0]['first_name'].'_'.$documentos[0]['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                            <a href="<?php echo base_url( str_replace(' ', '_','archivos/verPDFsedes/'.'Documento_1_'.$nivel.'_'.$branch.'_'.$session.'.pdf') ); ?>" target="_blank" class="btn btn-primary">
                                
                            <i class="fas fa-file-pdf"></i> Descargar contrato para firmar
                            </a>
                            
                        
                        <?php else : ?>
                            <span>Tipo de archivo no compatible.</span>
                        <?php endif; ?>
                    </div>
                </div>



               


                <div class="form-group">
                    <label for="archivo">Subir archivo firmado:</label>
                    <input type="file" class="form-control" id="archivo1" name="archivo1" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>
                </div>

                <input type="hidden" id="tuIdOculta" name="tuIdOculta" value="tu_valor_oculto">


                <br>
                <div class="form-group mt-3"> <!-- Agrega un margen top (mt) de 3 -->
                    <button type="submit" class="btn btn-primary">Subir archivos</button>
                </div>
            </div>

        <?php endif; ?>

        <?php endif; ?>
        <?php endif; ?>


    </div>
<?php echo form_close(); ?>

    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i>Resultados</h4>
        </header>

        <?php if ( !$documentos[0]['envioDocumento']) : ?>
            <div class="panel-body">
                <p>Aun no ha completado los procesos anteriores para poder ver sus resultados.</p>
                <!-- Aquí podrías agregar el código HTML o mensajes que desees mostrar -->
            </div>
            
          
            <?php else : ?>

            
            <div class="panel-body">
            

                <!-- Formulario para subir archivos -->
               
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><strong> Resultados academicos:</strong></h5>
                        <?php if (pathinfo('ResultadosAcademicos_'.$documentos[0]['first_name'].'_'.$documentos[0]['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                            <a href="<?php echo base_url( str_replace(' ', '_','archivos/verPDF/'.'ResultadosAcademicos_'.$documentos[0]['first_name'].'_'.$documentos[0]['last_name'].'.pdf') ); ?>" target="_blank" class="btn btn-primary">
                                
                            <i class="fas fa-file-pdf"></i> Descargar resultados academicos
                            </a>
                            
                        
                        <?php else : ?>
                            <span>Tipo de archivo no compatible.</span>
                        <?php endif; ?>
                    </div>
                </div>


                <br>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><strong>Resultados psicológicos</strong> </h5>
                        <?php if (pathinfo('ResultadosPsicologicos_'.$documentos[0]['first_name'].'_'.$documentos[0]['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                            <a href="<?php echo base_url( str_replace(' ', '_','archivos/verPDF/'.'ResultadosPsicologicos_'.$documentos[0]['first_name'].'_'.$documentos[0]['last_name'].'.pdf') ); ?>" target="_blank" class="btn btn-primary">
                                
                            <i class="fas fa-file-pdf"></i> Descargar resultados psicológicos
                            </a>
                            
                        
                        <?php else : ?>
                            <span>Tipo de archivo no compatible.</span>
                        <?php endif; ?>
                    </div>
                </div>

            
                <br>
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title"><strong>Carta de aceptación / rechazo</strong> </h5>
                        <?php if (pathinfo('DocumentoEnviadoConfirmar_'.$documentos[0]['first_name'].'_'.$documentos[0]['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                            <a href="<?php echo base_url( str_replace(' ', '_','archivos/verPDF/'.'DocumentoEnviadoConfirmar_'.$documentos[0]['first_name'].'_'.$documentos[0]['last_name'].'.pdf') ); ?>" target="_blank" class="btn btn-primary">
                                
                            <i class="fas fa-file-pdf"></i> Descargar carta
                            </a>
                            
                        
                        <?php else : ?>
                            <span>Tipo de archivo no compatible.</span>
                        <?php endif; ?>
                    </div>
                </div>


                <br>                    
           


                <br>
             
            </div>
        <?php endif; ?>


    </div>

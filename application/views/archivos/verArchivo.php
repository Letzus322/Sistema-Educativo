
		
                
    
<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-file-upload"></i>  Archivos subidos</h4>
    </header>
    <div class="panel-body">




        <?php foreach ($documentos as $documento) : ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $documento['nombre_document']; ?></h5>
                <?php if (pathinfo($documento['ruta_archivo'], PATHINFO_EXTENSION) == 'pdf') : ?>
                    <a href="<?php echo base_url('archivos/verPDF/'.$documento['ruta_archivo']); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Abrir PDF
                    </a>
                    
                <?php elseif (in_array(strtolower(pathinfo($documento['ruta_archivo'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) : ?>
                    <a href="<?php echo base_url('archivos/verPDF/'.$documento['ruta_archivo']); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-image"></i> Abrir imagen
                    </a>
                <?php else : ?>
                    <span>Tipo de archivo no compatible o no necesario para este grado.</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>




    </div>


</section>




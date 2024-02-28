<?php  $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
				<?php endif; //ELIMINAR ESTO SI SE DESEA QUITAR EL FILTRADO POR SECCION Y GRADO?>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,1)'
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>

					

					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
							<?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'), true);
								echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="search" value="1" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</footer>
			
      

    



    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-file-upload"></i> Ver estado Matricula</h4>
        </header>

        

        <div class="panel-body">
                


        <div class="panel-body mb-md">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                              
                                <th><?=translate('name')?></th>
                                <th><?=translate('Nivel/Grado')?></th>

                                <th><?=translate('Subida Archivos')?></th>
                                <th><?=translate('Cita Psicologica')?></th>

                                <th><?=translate('Cita Academica')?></th>
                                <th><?=translate('Firma de Contrato')?></th>
                                <th><?=translate('Revisión Final')?></th>


                           

                                <th><?=translate('Enviar aceptación / rechazo de matricula')?></th>


                            </tr>
                            
                        </thead>
                        <tbody>
                            <?php
                            foreach($students as $row):
                            ?>

                            <tr>
                               
                                <td><?php echo $row['last_name'].' '.$row['first_name'];?></td>
                                <td><?php echo $row['className'];?></td>

                                
                                <td>
                                    <?php if ($row['subidaArchivos']) : ?>
                                        <span class="badge badge-success" style="background-color: green;">
                                            Realizado
                                        </span>

                                       
                                    <?php elseif ($row['subidaArchivos'] == 0) : ?>
                                        <span class="badge badge-danger" style="background-color: red;">
                                            No realizado
                                        </span>
                                    <?php endif; ?>

                                </td> 

                               

                                <td>
                                    <?php if ($row['citaPsicologica']) : ?>
                                        <span class="badge badge-success" style="background-color: green;">
                                            Realizado 

                                        </span>
                                        <?php echo $row['resultadopsicologica']; ?>

                                       <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><strong> Resultados Psicológicos: <?php echo $row['resultadopsicologica']; ?></strong></h5>
                                                <?php if (pathinfo('ResultadosPsicologico_'.$row['first_name'].'_'.$row['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                                                    <a href="<?php echo base_url(  str_replace(' ', '_','student/verPDF/ResultadosPsicologicos_'.$row['first_name'].'_'.$row['last_name'].'.pdf'.'?parametro='. $row['idEstudiante'])); ?>" target="_blank" class="btn btn-primary">

                                                    <i class="fas fa-file-pdf"></i> Ver resultados Psicológicos
                                                    </a>
                                                    
                                                
                                                <?php else : ?>
                                                    <span>Tipo de archivo no compatible.</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>   
                                      


                                    <?php elseif ($row['citaPsicologica'] == 0) : ?>
                                        <span class="badge badge-danger" style="background-color: red;">
                                            No realizado
                                        </span>

                                        <?php 
                                            switch ($row['estadopsicologica']  ) {
                                              
                                                case 'Pendiente':
                                                    echo '<span class="text-warning">Reservada</span><br>';

                                                    echo '<span class="text">Resultado: ' . $row['resultadopsicologica'] . '</span>';

                                                    break;

                                                case 'Aceptado':
                                                   
                                                        echo '<span class="text-warning">Reservada</span><br>';

                                                        echo '<span class="text">Resultado: ' . $row['resultadopsicologica'] . '</span>';

                                                        break;
                                                        

                                                default:
                                                    echo '<span class="text-danger">No reservado</span>';

                                            }
                                        ?>


                                    <?php endif; ?>

                                </td> 

                                <td>
                                    <?php if ($row['citaAcademica']) : ?>
                                        <span class="badge badge-success" style="background-color: green;">
                                            Realizado
                                        </span>

                                        <div class="card">
       



                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><strong> Resultados academicos: <?php echo $row['resultadoacademica']; ?></strong></h5>
                                                
                                                <?php if (pathinfo('ResultadosAcademicos_'.$row['first_name'].'_'.$row['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                                                    <a href="<?php echo base_url(  str_replace(' ', '_','student/verPDF/ResultadosAcademicos_'.$row['first_name'].'_'.$row['last_name'].'.pdf'.'?parametro='. $row['idEstudiante'])); ?>" target="_blank" class="btn btn-primary">

                                                    <i class="fas fa-file-pdf"></i> Ver resultados academicos
                                                    </a>
                                                    
                                                
                                                <?php else : ?>
                                                    <span>Tipo de archivo no compatible.</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>   

                                    <?php elseif ($row['citaAcademica'] == 0) : ?>
                                        <span class="badge badge-danger" style="background-color: red;">
                                            No realizado
                                        </span>

                                        <?php 
                                            switch ($row['estadoacademica']) {
                                              
                                                case 'Pendiente':
                                                    echo '<span class="text-warning">Reservada</span><br>';
                                                    echo '<span class="text">Resultado: ' . $row['resultadoacademica'] . '</span>';

                                                    break;
                                                default:
                                                    echo '<span class="text-danger">No reservado</span><br>';

                                            }
                                        ?>
                                    <?php endif; ?>

                                </td> 
                                
                               

                                <td>
                                    <?php if ($row['contratoFirmado']) : ?>
                                        <span class="badge badge-success" style="background-color: green;">
                                            Realizado
                                        </span>
                                    <?php elseif ($row['contratoFirmado'] == 0) : ?>
                                        <span class="badge badge-danger" style="background-color: red;">
                                            No realizado
                                        </span>
                                    <?php endif; ?>

                                </td> 

                                <td>
                                    <?php if ($row['contratoValidado']) : ?>
                                        <span class="badge badge-success" style="background-color: green;">
                                            Realizado
                                        </span>
                                    <?php elseif ($row['contratoValidado'] == 0) : ?>
                                        <span class="badge badge-danger" style="background-color: red;">
                                            No realizado
                                        </span>
                                    <?php endif; ?>

                                </td> 


                                <td>
                                <?php if ($row['envioDocumento']) : ?>
                                        <span class="badge badge-success" style="background-color: green;">
                                            Realizado
                                        </span>
                                    <?php elseif ($row['envioDocumento'] == 0) : ?>
                                        <?php echo form_open_multipart($this->uri->uri_string()); ?>
                                        <div class="col-md-6">
                        <label for="archivo_<?= $row['id'] ?>" class="btn btn-primary btn-block btn-sm">
                            Seleccionar archivo
                        </label>
                        <input type="file" style="display: none;" id="archivo_<?= $row['id'] ?>" name="archivo" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif">
                    </div>

                    <div class="col-md-6">
                        <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="accion" class="btn btn-primary btn-block btn-sm" id="boton-enviar_<?= $row['id'] ?>" disabled>Enviar</button>
                    </div>
                                        <?php echo form_close(); ?>

                                    <?php endif; ?>
                                
                                </td> 
                            
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>


        </div>

    </div>
    <script>
    <?php foreach($students as $row): ?>
        <?php if ($row['envioDocumento'] == 0) : ?>
            document.getElementById('archivo_<?= $row['id'] ?>').addEventListener('change', function(e) {
                var fileName = e.target.files[0].name;
                document.querySelector('label[for=archivo_<?= $row['id'] ?>]').innerText = fileName;
                
                // Habilitar el botón de enviar si se selecciona un archivo
                document.getElementById('boton-enviar_<?= $row['id'] ?>').disabled = false;
            });
        <?php endif; ?>
    <?php endforeach; ?>
</script>


            
            
            
    <?php echo form_close();?>




</section>


	
	</div>

</div>


<?php if (get_permission('student', 'is_delete')): ?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#student_bulk_delete').on('click', function() {
			var btn = $(this);
			var arrayID = [];
			$("input[type='checkbox'].cb_bulkdelete").each(function (index) {
				if(this.checked) {
					arrayID.push($(this).attr('id'));
				}
			});
			if (arrayID.length != 0) {
				swal({
					title: "<?php echo translate('are_you_sure')?>",
					text: "<?php echo translate('delete_this_information')?>",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn btn-default swal2-btn-default",
					cancelButtonClass: "btn btn-default swal2-btn-default",
					confirmButtonText: "<?php echo translate('yes_continue')?>",
					cancelButtonText: "<?php echo translate('cancel')?>",
					buttonsStyling: false,
					footer: "<?php echo translate('deleted_note')?>"
				}).then((result) => {
					if (result.value) {
						$.ajax({
							url: base_url + "student/bulk_delete",
							type: "POST",
							dataType: "JSON",
							data: { array_id : arrayID },
							success:function(data) {
								swal({
								title: "<?php echo translate('deleted')?>",
								text: data.message,
								buttonsStyling: false,
								showCloseButton: true,
								focusConfirm: false,
								confirmButtonClass: "btn btn-default swal2-btn-default",
								type: data.status
								}).then((result) => {
									if (result.value) {
										location.reload();
									}
								});
							}
						});
					}
				});
			}
		});
	});
</script>
<?php endif; ?>




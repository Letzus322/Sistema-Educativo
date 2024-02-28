<?php  $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string());?>
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
        <h4 class="panel-title"><i class="fas fa-file-upload"></i> <?php echo $xd?>Documentos a revisar</h4>
    </header>

    

<div class="panel-body">
            


    <div class="panel-body mb-md">
                <table class="table table-bordered table-condensed table-hover table-export">
                    <thead>
                        <tr>
                          
                            <th><?=translate('name')?></th>
                            <th><?=translate('Grado ')?></th>
                            <th><?=translate('Seccion ')?></th>

                            <th><?=translate('Contrato Firmado')?></th>
                            <th><?=translate('Estado Declaracion Jurada 1')?></th>
                            <th><?=translate('Estado Declaracion Jurada 2')?></th>
                            <th><?=translate('Estado Declaracion Jurada 3')?></th>


                       

                            <th><?=translate('Acciones')?></th>


                        </tr>
                        
                    </thead>
                    <tbody>
                        <?php
                        foreach($students as $row):
                        ?>

                        <tr>
                           
                            <td><?php echo $row['last_name'].' '.$row['first_name'];?></td>
                            <td><?php echo $row['className'];?></td>
                            <td><?php echo $row['sectionName'];?></td>

                            <td>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $documento['nombre_document']; ?></h5>
                                            <a href="<?php echo base_url(  str_replace(' ', '_','student/verPDF/Documento_Firmado_1_'.$row['first_name'].' '.$row['last_name'].'_'.get_session_id().'.pdf'.'?parametro='. $row['id'])); ?>" target="_blank" class="btn btn-primary">
                                                <i class="fas fa-file-pdf"></i> Abrir PDF
                                            </a>
                                    </div>
                                </div>
                            </td> 
                            
                            <td>
                                <?php if ($row['contratoValidado'] == 0) : ?>
                                    <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                                    <select name="opcion_declaracion_0" >
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                <?php elseif ($row['contratoValidado'] == 1 && $row['estadoDeclaracion1'] == 1) : ?>
                                    Si
                                <?php elseif ($row['contratoValidado'] == 1 && $row['estadoDeclaracion1'] == 0) : ?>
                                    No
                                <?php endif; ?>
                            </td>



                            <td>
                                <?php if ($row['contratoValidado'] == 0) : ?>
                                        <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                                        <select name="opcion_declaracion_1" >
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                <?php elseif ($row['contratoValidado'] == 1 && $row['estadoDeclaracion2'] == 1) : ?>
                                    Si
                                <?php elseif ($row['contratoValidado'] == 1 && $row['estadoDeclaracion2'] == 0) : ?>
                                    No
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($row['contratoValidado'] == 0) : ?>
                                        <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                                        <select name="opcion_declaracion_2" >
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                <?php elseif ($row['contratoValidado'] == 1 && $row['estadoDeclaracion3'] == 1) : ?>
                                    Si
                                <?php elseif ($row['contratoValidado'] == 1 && $row['estadoDeclaracion3'] == 0) : ?>
                                    No
                                <?php endif; ?>
                            </td>



                            <td>
                                <?php if ($row['contratoValidado']) : ?>
                                    Aceptado

                                <?php elseif ($row['contratoValidado'] == 0) : ?>
                                           
                                    <span>
                                            <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="accion" value="1" class="btn btn-success btn-circle icon">
                                                <i class="fas fa-check"></i>
                                            </button>

                                        </span>
                                    <span>
                                            <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="accion" value="0" class="btn btn-danger btn-circle icon">
                                                <i class="fas fa-times"></i>
                                            </button>
                                    </span>

                                 
                                
                                <?php endif; ?>
                            </td>
                        
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
    </div>


    </div>

</div>
            
            
            
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

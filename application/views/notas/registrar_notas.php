<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">

					
						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('class_list')?></h4>
							</header>
							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
                                    <table class="table table-bordered table-condensed table-hover mb-none tbr-top table-export">
										<thead>
											<tr>
												<th>#</th>
												<th><?=translate('branch')?></th>
												<th><?=translate('class_name')?></th>
												<th><?=translate('section')?></th>
                                                <th>Area</th>

                                                <th>Curso</th>
												<th><?=translate('action')?></th>

											</tr>
										</thead>
										<tbody>
											<?php
											$count = 1;
											if (count($classlist)){
												foreach($classlist as $row):
											?>
											<tr>
												<td><?php echo $count++;?></td>
												<td><?php echo $row['branch_name'];?></td>
												<td><?php echo $row['name'];?></td>
                                                <td><?php echo $row['sectionName'];?></td>
                                                <td><?php echo $row['area_name'];?></td>

												<td><?php echo $row['subject_name'];?></td>

												<td>
												
                                                <select  onchange="redirectPage(this)">
                                                    <option value="">Ver registro de notas</option>

                                                    <option value="<?php echo base_url('notas/subir_notas/' . $row['subject_assign_id'] . '/1'); ?>">Bimestre I</option>
                                                    <option value="<?php echo base_url('notas/subir_notas/' . $row['subject_assign_id'] . '/2'); ?>">Bimestre II</option>
                                                    <option value="<?php echo base_url('notas/subir_notas/' . $row['subject_assign_id'] . '/3'); ?>">Bimestre III</option>
                                                    <option value="<?php echo base_url('notas/subir_notas/' . $row['subject_assign_id'] . '/4'); ?>">Bimestre IV</option>
                                                </select>
												<?php
																if(loggedin_role_id()==1):
																?>
															

												<button type="button" class="btn btn-default btn-l mb-l hidden-print" id="prorroga" data-toggle="modal" data-target="#modalProrroga-<?php echo $row['subject_assign_id']; ?>">
													<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Prórroga
												</button>
												<?php endif;?>
												<div class="modal fade" id="modalProrroga-<?php echo $row['subject_assign_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalProrrogaLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">


															

																
																<div class="modal-header">
																	<h5 class="modal-title" id="modalProrrogaLabel">Prórroga</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																	</button>
																	
																</div>
																
															<div class="modal-body">
																<!-- Formulario para ingresar la nueva fecha de cierre y el bimestre -->
																<form>
																	<div class="form-group">
																		<label for="nuevaFechaCierre">Nueva fecha de cierre</label>
																		<input type="text" class="form-control datepicker" name="fecha_inicio_unidad_" 
																		id="nuevaFechaCierre-<?php echo $row['subject_assign_id']; ?>" value="" required autocomplete="off" />


																	</div>
																	<div class="form-group">
																	<br>
																	

																	<select id="bimestreSelect-<?php echo $row['subject_assign_id']; ?>">

																			<option value="">Elegir Bimestre aplazar</option>

																			<option value="1">Bimestre I</option>
																			<option value="2">Bimestre II</option>
																			<option value="3">Bimestre III</option>
																			<option value="4">Bimestre IV</option>
																		</select>
																	</div>
																</form>
															</div>

															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																
																<button type="button" class="btn btn-default btn-l mb-l hidden-print prorrogaEnviar" data-assign-id="<?php echo $row['subject_assign_id']; ?>">
																	<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Prórroga
																</button>
																
															</div>
														</div>
													</div>
												</div>


												</td>
											</tr>
										<?php
											endforeach;
										}else{
											echo '<tr><td colspan="6"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</section>
				</div>
			</div>
		</div>
	</div>
</section>


<script>
    $(document).ready(function () {
		$(".prorrogaEnviar").on("click", function () {
            // Obtener los valores del formulario
			var subjectAssignId = $(this).data('assign-id');
			var nuevaFechaCierre = $('#nuevaFechaCierre-'+subjectAssignId).val();
    		var bimestre = $('#bimestreSelect-'+subjectAssignId).val();

            // Realizar una solicitud AJAX para enviar los datos al controlador
            $.ajax({
                type: 'POST',
                url: base_url + 'Notas/prorroga',
                data: {
                    nuevaFechaCierre: nuevaFechaCierre,
                    bimestre: bimestre,
                    subjectAssignId: subjectAssignId
                },
                success: function(response) {
                    // Manejar la respuesta del servidor, por ejemplo, mostrar un mensaje de éxito
                    console.log('Datos guardados correctamente:', response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Manejar errores en la solicitud AJAX
                    console.error('Error al enviar los datos:', error);
                }
            });
        });
    });
</script>




<script>
    function redirectPage(select) {
        var url = select.value;
        window.location.href = url;
    }
</script>   

<script type="text/javascript">
	
    $(function() {
  $(".datepicker").datepicker({
  

    format: "yyyy-mm-dd",
        autoclose: true,
        orientation: "bottom",
    // maxDate: "+1M"
  });
});



</script>
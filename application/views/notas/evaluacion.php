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

												<td>
												
													<!--update link-->
													<a href="<?php echo base_url('notas/evaluacion_curso/' . $row['id_grado_seccion']);?>" class="btn btn-default btn-circle icon">
														<i class="fas fa-pen-nib"> Ver Evaluaciones</i>

													</a>
												
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
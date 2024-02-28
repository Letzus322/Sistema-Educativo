<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">

					
						<section class="panel panel-custom">
							
							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
                                    <table class="table table-bordered table-condensed table-hover mb-none tbr-top table-export">
										<thead>
											<tr>
												<th>#</th>
												<th><?=translate('branch')?></th>
												<th><?=translate('class_name')?></th>
												<th><?=translate('section')?></th>
                                                <th><?=translate('Alumnos')?></th>
												<th><?=translate('Tutor')?></th>

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

                                                <?php
                                                    $this->db->select('*'); // Selecciona el conteo de registros y renómbralo como "total_registros"
                                                    $this->db->from('enroll');
                                                    $this->db->where('enroll.class_id',  $row['id']);
                                                    $this->db->where('enroll.section_id',  $row['section_id']);
                                                    $this->db->where('enroll.session_id',  get_session_id());
                                                
                                                    $query = $this->db->get(); // Ejecuta la consulta
                                                    $result = $query->result_array(); // Obtiene el resultado como un array
                                                    $total_registros = count($result); // Obtiene el número total de registros

                                                    
											    ?>

                                                <td><?php echo $total_registros;?></td>
                                                <td><?php echo $row['profesorNombre'];?></td>

												<td>
                                                    <!-- Enlace de actualización con icono "Ver Alumnos" -->
                                                    <a href="<?php echo base_url('libretas/ver_alumnos/' . $row['id_grado_seccion']);?>" class="btn btn-default btn-circle icon">
                                                        <i class="fas fa-eye"></i> Ver Alumnos
                                                    </a>

                                                    <!-- Enlace de actualización con icono "Reporte de Méritos" -->
                                                    <a href="<?php echo base_url('libretas/evaluacion_curso/' . $row['id_grado_seccion']);?>" class="btn btn-default btn-circle icon">
                                                        <i class="fas fa-file-alt"></i> Reporte de Méritos
                                                    </a>

                                                    <!-- Enlace de actualización con icono "SIAGIE" -->
                                                    <a href="<?php echo base_url('libretas/evaluacion_curso/' . $row['id_grado_seccion']);?>" class="btn btn-default btn-circle icon">
                                                        <i class=""></i> SIAGIE
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
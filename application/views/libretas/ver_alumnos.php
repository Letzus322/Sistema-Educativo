<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">

					
						
                           
                <div class="panel-body panel-body-custom">

                    <div class="input-group">
                        <span class="input-group-addon">Año académico:</span>
                        <input type="text" class="form-control" value="<?php echo $informacionSalon->school_year; ?>" readonly>
                        <span class="input-group-addon">Sede:</span>


                        <input type="text" class="form-control" value="<?php echo $informacionSalon->branch; ?>" readonly>

                        <span class="input-group-addon">Sección:</span>

                        <input type="text" class="form-control" value="<?php echo $informacionSalon->class_name .' '.$informacionSalon->section; ?>" readonly>


                    </div>
                    <br>

                    <div class="input-group">
                        <span class="input-group-addon">Periodo</span>
                        <select onchange="redirectPage(this)" class="form-control">
                            <option value="">Seleccionar</option>
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <?php $selected = ($bimestre == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo base_url('libretas/ver_alumnos/' . $informacionSalon->sections_allocation_id . '/' . $i); ?>" <?php echo $selected; ?>>
                                    Bimestre <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <br>


                    <div class="input-group">
                        <span class="input-group-addon">Tutor:</span>
                        <input type="text" class="form-control" value="<?php echo $informacionSalon->profesor; ?>" readonly>
                    </div>

                    
                        <br>
                        <div class="btn-group">
                            <a href="#" class="btn btn-default">Generar libretas</a>
                            <a href="#" class="btn btn-default">Ver documentos</a>
                            <a href="#" class="btn btn-default">Descargar</a>
                        </div>
                </div>
                                       

							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
                                    <table class="table table-bordered table-condensed table-hover mb-none tbr-top table-export">
										<thead>
											<tr>
												<th>#</th>
												<th>Alumno(a)</th>
												<th>Cursos Ingresados</th>
												<th>Cursos Exonerados</th>
                                                <th>Orden de mérito</th>
												<th>Promedio Redondeado</th>

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
                                                <td><?= mb_strtoupper($row['apellido'], 'UTF-8').' '.mb_strtoupper($row['nombre'], 'UTF-8') ?></td>
												<td></td>
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

                                                <td></td>
                                                <td><?php echo $row['profesorNombre'];?></td>

												<td>
                                                    <!-- Enlace de actualización con icono "Ver Alumnos" 
                                                    <a href="<?php echo base_url('libretas/ver_alumnos/' . $row['id_grado_seccion']);?>" class="btn btn-default btn-circle icon">
                                                        <i class="fas fa-eye"></i> Ver libreta
                                                    </a>-->

                                                <?php if (!empty($bimestre)): ?>
                                                    <a href="<?php echo base_url('libretas/ver_libreta/' . $row['student_id'] . '/' . $bimestre); ?>" class="btn btn-default btn-circle icon">
                                                        <i class="fas fa-file-alt"></i> Ver Documento
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-danger">No se ha seleccionado ningún bimestre.</span>
                                                <?php endif; ?>


                                                   
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
				</div>
			</div>
		</div>
	</div>
</section>

<script>
    function redirectPage(select) {
        var url = select.value;
        window.location.href = url;
    }
</script>   
<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">

					
						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title"> EVALUACIONES A COPIAR (APLICA 4 BIMESTRES)</h4>
                                <h4 class="panel-title">Grado: <?php echo $curso['class'];?></h4>

                                <h4 class="panel-title">Seccion: <?php echo $curso['section'];?></h4>

                                <h4 class="panel-title">Curso: <?php echo $curso['name'];?></h4>
								<br>
								<button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="copiarDatos"  >
								<i class="fas fa-copy fa-fw"></i> Copiar el tipo de evaluaciones para los cursos seleccionados en otras secciones
								</button>

							</header>
							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
								<table class="table table-bordered table-condensed table-hover mb-none tbr-top ">
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
												
													
												
												</td>
											</tr>

                                                                                     
                                            <?php
                                            
                                            $this->db->select('subject.*, subject.name as name, subject_assign.id as subject_assign_id');
                                            $this->db->from('subject_assign');
                                            $this->db->join('subject', 'subject.id = subject_assign.subject_id');
                                            $this->db->where('subject_assign.class_id', $row['class_id']);
                                            $this->db->where('subject_assign.section_id', $row['section_id']);
											
											$this->db->where('subject_assign.session_id', get_session_id());

                                            $query2 = $this->db->get();
                                            $cursoSeccion = $query2->result_array();
                                            ?>
                                            <?php foreach($cursoSeccion as $curso1):
                                            ?>
     
                                                <tr>
													<td></td>

                                                    <td>Curso</td>
                                                    <td><?php echo $curso1['name'];?></td>
                                                    <td></td>
                                                    <td>

													<input type="checkbox" class="form-check-input checkbox-custom" value="<?= $curso1['subject_assign_id']?>">

													</td>
                                                </tr>

                                            <?php endforeach;?>     

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


$(document).ready(function() {
    $('#copiarDatos').on('click', function() {
        var selectedCourses = [];

        // Recorre todos los checkboxes seleccionados y recopila sus valores
        $('.checkbox-custom:checked').each(function() {
			var courseId = parseInt($(this).val());
            // Añade el valor convertido al array
            selectedCourses.push(courseId);

		});

        // Crea un objeto de datos para enviar al controlador
        var postData = {
            selectedCourses: selectedCourses,
			cursoBase: <?php echo $curso['curso_asignado'];?> 

        };

        // Realiza la solicitud AJAX al controlador
        $.ajax({
            type: "POST",
			url: base_url + 'Notas/copiar_entre_secciones',
            data: postData,
            success: function(response) {
                console.log(response);
                alert("Se guardó correctamente");
                location.reload();
            },
            error: function(error) {
                alert("Error");
                console.error("Error en la solicitud AJAX:", error);
            }
        });
    });
});
</script>






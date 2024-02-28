


<section class="panel panel-custom">
    <header class="panel-heading panel-heading-custom">
        <h4 class="panel-title"> Exonerar </h4>
    </header>
    <div class="panel-body panel-body-custom">
        <div class="table-responsive">
            

            <div class="form-group">
                <?php
                    $arrayParent = $this->app_lib->getSelectListStudent('student');
                    echo form_dropdown("student_id", $arrayParent, set_value('student_id'), "class='form-control' id='student_id' data-plugin-selectTwo data-width='100%' onchange='updateSubjectAssignList()' ");

                ?>
                <span class="error"><?=form_error('student_id')?></span>
            </div>

            <div class="form-group" id="subject_assign_group">
                <select name="subject_assign_id" class="form-control" id="subject_assign_id" data-plugin-selectTwo data-width="100%">
                    <option value="">Selecciona una alumno</option>
                </select>

            </div>
            <br>

            <script>
                function updateSubjectAssignList() {
                    var student_id = document.getElementById('student_id').value;
                    var url = '<?php echo base_url("Notas/obtener_asignaturas_por_estudiante/"); ?>' + student_id;

                    // Realizar una solicitud AJAX al servidor para obtener las asignaturas del estudiante seleccionado
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la solicitud AJAX');
                            }
                            return response.json();
                        })
                        .then(data => {
                            var optionsHtml = '';
                            data.forEach(item => {
                                optionsHtml += '<option value="' + item.id + '">' + item.name + '</option>';
                            });

                            // Actualizar el dropdown de asignaturas
                            document.getElementById('subject_assign_group').innerHTML = '<select name="subject_assign_id" class="form-control" id="subject_assign_id" data-plugin-selectTwo data-width="100%">' + optionsHtml + '</select>';
                        })
                        .catch(error => {
                            console.error('Error en la solicitud AJAX:', error);
                        });
                }
            </script>

            <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="guardarExoneracion" data-toggle="guardarExoneracion" >
                                        <i class="fas fa-save fa-fw"></i> Guardar Exoneración
            </button>        
        </div>
    </div>
</section>
    

<script>


$(document).ready(function () {
       


        $("#guardarExoneracion").on("click", function () {
            var studentId = $("#student_id").val();
            var subjectAssignId = $("#subject_assign_id").val();

            if (studentId && subjectAssignId) {
            // Crear el objeto de datos a enviar
            var postData = {
                student_id: studentId,
                subject_assign_id: subjectAssignId
            };


            $.ajax({
                type: "POST",
                url: base_url + 'Notas/guardar_exonerar',
                data: postData,
                success: function (response) {
                    console.log(response);
                    alert("Se guardó correctamente");
                    location.reload();
                },
                error: function (error) {
                    alert("Error");
                    console.error("Error en la solicitud AJAX:", error);
                }
            });
        } else {
            // Si no se han seleccionado valores, mostrar un mensaje de error
            alert("Por favor, selecciona un alumno y una asignatura para exonerar.");
        }
    });








    });

</script>    



<section class="panel">
	
			

					
						
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title"><i class="fas fa-list-ul"></i> Lista de exonerados</h4>
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
												<th><?=translate('Nombre')?></th>
                                                <th><?=translate('Curso')?></th>

											</tr>
										</thead>
										<tbody>
											<?php
											$count = 1;
											if (count($classlist_exonerados)){
												foreach($classlist_exonerados as $row):
											?>
											<tr>
												<td><?php echo $count++;?></td>
												<td><?php echo $row['branch'];?></td>
												<td><?php echo $row['class'];?></td>
                                                <td><?php echo $row['section'];?></td>

                                                <td><?= mb_strtoupper($row['apellido'], 'UTF-8').' '.mb_strtoupper($row['nombre'], 'UTF-8') ?></td>
                                                <td><?php echo $row['curso'];?></td>

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
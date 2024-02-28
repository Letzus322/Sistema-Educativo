<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> Lista de temas</a>
			</li>



			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> Crear Tema</a>
			</li>

			
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane active">
				<table class="table table-bordered table-hover mb-none table-export">
					<thead>
						<tr>
							<th width="60"><?=translate('sl')?></th>
							<th>Bimestre</th>
							<th>Unidad</th>
							<th>Grado</th>
							<th>Curso</th>
							<th>Tema</th>
							<th>Acciones</th>

						</tr>
					</thead>
					<tbody>
						<?php 
						$count = 1;
						foreach($temas as $row):
						?>
						<tr>
							<td><?php echo $count++ ;?></td>
							<td><?php echo $row['bimestre'];?></td>
							<td><?php echo $row['unidad'];?></td>
							    
							

							<td><?php echo $row['grado'];?></td>

							<td><?php echo $row['curso'];?></td>
							<td><?php echo $row['tema'];?></td>

							<td>
								<?php if (get_permission('subject', 'is_edit')): ?>
									<!-- subject update link -->
									<a href="<?php echo base_url('temarios/edit_tema/' . $row['id']);?>" class="btn btn-circle btn-default icon" >
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('subject', 'is_delete')): ?>
									<!-- delete link -->
									<?php echo btn_delete('temarios/delete/' . $row['id']);?>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>


		

<?php if (get_permission('subject', 'is_add')): ?>
			<div class="tab-pane" id="create">
				<?php echo form_open('subject/save', array('class' => 'form-horizontal form-bordered frm-submit'));?>
					
                   
					

                    <div class="form-group">
                        <label class="col-md-3 control-label">Bimestre <span class="required">*</span></label>
                        <div class="col-md-6">
                            <?php
                                $bimestres = $this->db->get('bimestre')->result_array();
                                $arrayBimestres = array('' => 'Seleccione un bimestre'); // Agregar opción predeterminada
                                foreach ($bimestres as $row) {
                                    $arrayBimestres[$row['id']] = $row['name'];
                                }
                                echo form_dropdown("bimestre_id", $arrayBimestres, set_value('bimestre_id'), "class='form-control' 
								id='bimestre_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' onchange='updateSubjectAssignList()'");
                            ?>
                            <span class="error"></span>
                        </div>
                    </div>


                    <div class="form-group" id="subject_assign_group">
                        <label class="col-md-3 control-label">Unidad <span class="required">*</span></label>
                            <div class="col-md-6">

                                <select name="unidad_id" class="form-control" id="unidad_id" data-plugin-selectTwo data-width="100%">
                                    <option value="">Seleccione un bimestre</option>
                                </select>
                            </div>

                    </div>

           
                    <script>
                        function updateSubjectAssignList() {
                            var bimestre_id = document.getElementById('bimestre_id').value;
                            var url = '<?php echo base_url("temarios/obtener_unidad/"); ?>' + bimestre_id;

                            // Realizar una solicitud AJAX al servidor para obtener las asignaturas del estudiante seleccionado
                            fetch(url)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Error en la solicitud AJAX');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    var optionsHtml = '<option value="">Selecciona una unidad</option>'; // Agregar opción predeterminada
                                    data.forEach(item => {
                                        optionsHtml += '<option value="' + item.id + '">' + item.name + '</option>';
                                    });

                                    // Actualizar el dropdown de asignaturas
                                    document.getElementById('unidad_id').innerHTML = optionsHtml;
                                })
                                .catch(error => {
                                    console.error('Error en la solicitud AJAX:', error);
                                });
                        }
                    </script>


					<div class="form-group">
						<label class="col-md-3 control-label">Grado <span class="required">*</span></label>
						<div class="col-md-6">
                        <?php
								$this->db->select('*');
								$this->db->from('grado');
                                $arrayGrado = array('' => 'Seleccione un grado'); // Agregar opción predeterminada

								$grado = $this->db->get()->result_array();
								foreach ($grado as $row) {
									$arrayGrado[$row['id']] =  $row['name'];
								}
								echo form_dropdown("grado_id", $arrayGrado, set_value('grado_id'), "class='form-control' id='grado_id' '
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' onchange='updateSubjectList()' ");
							?>
							<span class="error"></span>
						</div>
					</div>


					<div class="form-group" id="unidad_group">
                        <label class="col-md-3 control-label">Curso <span class="required">*</span></label>
                            <div class="col-md-6">

                                <select name="curso_id" class="form-control" id="curso_id" data-plugin-selectTwo data-width="100%">
                                    <option value="">Seleccione un grado</option>
                                </select>
                            </div>

                    </div>
					


					<script>
                        function updateSubjectList() {
                            var grado_id = document.getElementById('grado_id').value;
                            var url = '<?php echo base_url("temarios/obtener_curso/"); ?>' + grado_id;

                            // Realizar una solicitud AJAX al servidor para obtener las asignaturas del estudiante seleccionado
                            fetch(url)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Error en la solicitud AJAX');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    var optionsHtml = '<option value="">Selecciona un curso</option>'; // Agregar opción predeterminada
                                    data.forEach(item => {
                                        optionsHtml += '<option value="' + item.id + '">' + item.name + '</option>';
                                    });

                                    // Actualizar el dropdown de asignaturas
                                    document.getElementById('curso_id').innerHTML = optionsHtml;
                                })
                                .catch(error => {
                                    console.error('Error en la solicitud AJAX:', error);
                                });
                        }
                    </script>







                
					

                    <div class="form-group">
						<label class="col-md-3 control-label">Temas </label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="tema" id="tema" />
						</div>
					</div>


					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
							<button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="guardarTema" data-toggle="guardarTema" >
                                        <i class="fas fa-save fa-fw"></i> Guardar Tema
            				</button>     
							</div>
						</div>
					</footer>

					
				<?php echo form_close(); ?>
			</div>


			<div class="tab-pane" id="createArea">
				<?php echo form_open('subject/saveArea', array('class' => 'form-horizontal form-bordered frm-submit'));?>
					
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('Area')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('subject_code')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="subject_code" />
							<span class="error"></span>
						</div>
					</div>
					
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php endif; ?>
		</div>
	</div>
</section>


<script>


$(document).ready(function () {
       


        $("#guardarTema").on("click", function () {
            var bimestreId = $("#bimestre_id").val();
            var unidadId = $("#unidad_id").val();
			var gradoId = $("#grado_id").val();
            var cursoId = $("#curso_id").val();
            var tema = $("#tema").val();

            if (cursoId && bimestreId && unidadId && gradoId && tema) {
            // Crear el objeto de datos a enviar
            var postData = {
                curso_id: cursoId,
                bimestre_id: bimestreId,
				unidad_id: unidadId,
				grado_id:gradoId,
				tema: tema
            };


            $.ajax({
                type: "POST",
                url: base_url + 'temarios/guardar_tema',
                data: postData,
                success: function (response) {
                    console.log(response);
                    alert("Se guardó correctamente");
					$("#tema").val("");

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
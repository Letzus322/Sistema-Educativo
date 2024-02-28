<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> Lista de temas Registrados</a>
			</li>



			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> Registrar Tema</a>
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
                            <th>Sede</th>

							<th>Grado</th>
                            <th>Seccion</th>

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
							    
							
							<td><?php echo $row['branch'];?></td>
							<td><?php echo $row['grado'];?></td>

							<td><?php echo $row['section'];?></td>

							<td><?php echo $row['curso'];?></td>
							<td><?php echo $row['tema'];?></td>

							<td>
									<!-- subject update link -->
									<a href="<?php echo base_url('temarios/edit_tema_dictado/' . $row['id']);?>" class="btn btn-circle btn-default icon" >
										<i class="fas fa-pen-nib"></i>
									</a>
									<!-- delete link -->
									<?php echo btn_delete('temarios/delete_tema_dictado/' . $row['id']);?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>


		

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
								id='bimestre_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'  onchange='updateSubjectAssignList(); updateTemasList();'");
                            ?>
                            <span class="error"></span>
                        </div>
                    </div>


                    <div class="form-group" id="subject_assign_group">
                        <label class="col-md-3 control-label">Unidad <span class="required">*</span></label>
                            <div class="col-md-6">

                                <select name="unidad_id" class="form-control" id="unidad_id" data-plugin-selectTwo data-width="100% " onchange="updateTemasList()">
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
						<label class="col-md-3 control-label">Sede <span class="required">*</span></label>
						<div class="col-md-6">
                        <?php
								$this->db->select('*');
								$this->db->from('branch');
                                $arrayBrach = array('' => 'Seleccione una Sede'); // Agregar opción predeterminada

								$branch = $this->db->get()->result_array();
								foreach ($branch as $row) {
									$arrayBrach[$row['id']] =  $row['name'];
								}
								echo form_dropdown("branch_id", $arrayBrach, set_value('branch_id'), "class='form-control' id='branch_id' '
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' onchange='updateGradoList()' ");
							?>
							<span class="error"></span>
						</div>
					</div>

					<script>
                        function updateGradoList() {
                            var branch_id = document.getElementById('branch_id').value;
                            var url = '<?php echo base_url("temarios/obtener_grados/"); ?>' + branch_id;

                            // Realizar una solicitud AJAX al servidor para obtener las asignaturas del estudiante seleccionado
                            fetch(url)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Error en la solicitud AJAX');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    var optionsHtml = '<option value="">Selecciona un grado</option>'; // Agregar opción predeterminada
                                    data.forEach(item => {
                                        optionsHtml += '<option value="' + item.id + '">' + item.name + '</option>';
                                    });

                                    // Actualizar el dropdown de asignaturas
                                    document.getElementById('grado_id').innerHTML = optionsHtml;
                                })
                                .catch(error => {
                                    console.error('Error en la solicitud AJAX:', error);
                                });
                        }
                    </script>




					<div class="form-group" id="subject_assign_group">
                        <label class="col-md-3 control-label">Grado <span class="required">*</span></label>
                            <div class="col-md-6">

                                <select name="grado_id" class="form-control" id="grado_id" data-plugin-selectTwo data-width="100%" onchange="updateSubjectList(); updateTemasList();">
                                    <option value=" ">Seleccione una sede</option>
                                </select>
                            </div>

                    </div>
					


                    <div class="form-group" id="subject_assign_group">
                        <label class="col-md-3 control-label">Seccion <span class="required">*</span></label>
                            <div class="col-md-6">

                                <select name="section_id" class="form-control" id="section_id" data-plugin-selectTwo data-width="100%">
                                    <option value="">Seleccione un grado</option>
                                </select>
                            </div>

                    </div>
					

					<script>
                        function updateSubjectList() {
                            var grado_id = document.getElementById('grado_id').value;
                            var url = '<?php echo base_url("temarios/obtener_curso_subirTema/"); ?>' + grado_id;

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
									
									var subjectList = data.subject_list;

                                    subjectList .forEach(item => {
                                        optionsHtml += '<option value="' + item.id + '">' + item.name + '</option>';
                                    });

                                    // Actualizar el dropdown de asignaturas
                                    document.getElementById('curso_id').innerHTML = optionsHtml;


                                    var optionsHtml2 = '<option value="">Selecciona una sección</option>'; // Agregar opción predeterminada
									var sectionList = data.section_list;
									sectionList .forEach(item => {
                                        optionsHtml2 += '<option value="' + item.id + '">' + item.name + '</option>';
                                    });
                                    document.getElementById('section_id').innerHTML = optionsHtml2;


                                })
                                .catch(error => {
                                    console.error('Error en la solicitud AJAX:', error);
                                });



                        }
                    </script>
					

					<div class="form-group" id="unidad_group">
                        <label class="col-md-3 control-label">Curso <span class="required">*</span></label>
                            <div class="col-md-6">

                                <select name="curso_id" class="form-control" id="curso_id" data-plugin-selectTwo data-width="100%" onchange="updateTemasList()">
                                    <option value="">Seleccione un grado</option>
                                </select>
                            </div>

                    </div>
					

					<div class="form-group" id="unidad_group">
                        <label class="col-md-3 control-label">Tema <span class="required">*</span></label>
                            <div class="col-md-6">

                                <select name="tema_id" class="form-control" id="tema_id" data-plugin-selectTwo data-width="100%">
                                    <option value="">Seleccione un curso</option>
                                </select>

								
                            </div>

                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Fecha de Clase</label>
                        <div class="col-md-6">
                            <?php
                                $fecha_actual = date('Y-m-d');
                            ?>
                            <input type="text" class="form-control datepicker" name="fecha_clase" id="fecha_clase" value="<?php echo $fecha_actual; ?>" required autocomplete="off" />  
                        </div>
                    </div>

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
					


                    <div class="form-group" id="unidad_group">
                        <label class="col-md-3 control-label">Comentario <span class="required">*</span></label>
                        <div class="col-md-6">
                            <textarea class="form-control" name="comentario" id="comentario" rows="2" placeholder="Comentario opcional"></textarea>
                        </div>
                    </div>



					<script>
					function updateTemasList() {
						var bimestre_id = $("#bimestre_id").val();
						var unidad_id = $("#unidad_id").val();
						var grado_id = $("#grado_id").val();
						var curso_id = $("#curso_id").val();
						

						// Crear el objeto de datos a enviar
						var postData = {
							bimestre_id: bimestre_id,
							unidad_id: unidad_id,
							grado_id: grado_id,
							curso_id: curso_id
						};

						$.ajax({
							type: "POST",
							url: base_url + 'temarios/obtener_tema',
							data: postData,
							dataType: 'json', // Especificamos que esperamos JSON como respuesta

							success: function (response) {
								var optionsHtml = '<option value="">Selecciona un tema</option>'; // Agregar opción predeterminada
								response.forEach(item => {
									optionsHtml += '<option value="' + item.id + '">' + item.name + '</option>';
								});

								// Actualizar el dropdown de temas
								$("#tema_id").html(optionsHtml);
							},

							error: function (error) {
								console.error('Error en la solicitud AJAX:', error);
							}
						});
					}
					</script>




					


					

					

                    


					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
							<button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="guardarTema" data-toggle="guardarTema" >
                                        <i class="fas fa-save fa-fw"></i> Guardar Tema Dictado
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
		</div>
	</div>
</section>


<script>


$(document).ready(function () {
       


        $("#guardarTema").on("click", function () {
            var bimestreId = $("#bimestre_id").val();
            var unidadId = $("#unidad_id").val();
			var gradoId = $("#grado_id").val();
            var sectionId = $("#section_id").val();
            var cursoId = $("#curso_id").val();
            var temaId = $("#tema_id").val();
            var comentario = $("#comentario").val();
			var fecha_clase = $("#fecha_clase").val();
            if (bimestreId && unidadId && gradoId && sectionId && cursoId  && temaId &&  fecha_clase){
            // Crear el objeto de datos a enviar
            var postData = {
                bimestre_id: bimestreId,
				unidad_id: unidadId,
				grado_id:gradoId,
                section_id:sectionId,
                curso_id: cursoId,
				tema_id: temaId,
                comentario:comentario,
                fecha_clase:fecha_clase
            };


            $.ajax({
                type: "POST",
                url: base_url + 'temarios/guardar_tema_dictado',
                data: postData,
                success: function (response) {
                    console.log(response);
                    alert("Se guardó correctamente");
                    // Limpiar la selección
                    location.reload();

                   
                },
                error: function (error) {
                    alert("Error");
                    console.error("Error en la solicitud AJAX:", error);
                }
            });
        } else {
            // Si no se han seleccionado valores, mostrar un mensaje de error
            alert("Por favor llene todo.");
        }
    });








    });

</script>    
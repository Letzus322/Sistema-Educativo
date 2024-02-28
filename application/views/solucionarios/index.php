<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> Lista de Solucionarios Registrados</a>
			</li>



			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> Registrar Solucionario</a>
			</li>

			
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane active">
				<table class="table table-bordered table-hover mb-none table-export">
					<thead>
						<tr>
							<th width="60"><?=translate('sl')?></th>
							<th>Titulo</th>
							<th>Tipo</th>
                            <th>Fecha / Hora</th>

							<th>Grado</th>
                            <th>Seccion</th>

							
							<th>Acciones</th>

						</tr>
					</thead>
					<tbody>
						<?php 
						$count = 1;
						foreach($solucionarios as $row):
						?>
						<tr>
							<td><?php echo $count++ ;?></td>
							<td><?php echo $row['titulo'];?></td>
							<td><?php echo $row['tipo'];?></td>
							    
							
							<td><?php echo $row['fecha'];?></td>
							<td><?php echo $row['grado'];?></td>

							<td><?php echo $row['section'];?></td>

						

							<td>
										<div class="card">
                                            <div class="card-body">
                                                <?php if (pathinfo('ResultadosPsicologico_'.$row['first_name'].'_'.$row['last_name'].'.pdf', PATHINFO_EXTENSION) == 'pdf') : ?>
                                                    <a href="<?php echo base_url('Solucionarios/verPDF/Solucionario-'.$row['id'].'.pdf'); ?>" target="_blank" class="btn btn-primary">
                                                        <i class="fas fa-file-pdf"></i> Ver solucionario
                                                    </a>
                                                <?php else : ?>
                                                    <span>Tipo de archivo no compatible.</span>
                                                <?php endif; ?>

												<?php echo btn_delete('solucionarios/delete_solucionario/' . $row['id']);?>

                                            </div>
											
                                        </div>


									<!-- delete link -->
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>


		

			<div class="tab-pane" id="create">
				

<?php echo form_open_multipart($this->uri->uri_string()); ?>


	<div class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-file-upload"></i> Subir </h4>
		</header>



		<div class="panel-body">
				

					<div class="form-group">
						<label class="col-md-3 control-label">Titulo </label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="titulo" id="titulo" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Tipo </label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="tipo" id="tipo" />
						</div>
					</div>

				<!-- Agrega los enlaces a las bibliotecas jQuery, DateTimePicker CSS y JS -->
					<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

					<div class="form-group">
						<label class="col-md-3 control-label">Fecha/hora visualización</label>
						<div class="col-md-6">
							<input type="text" class="form-control datetimepicker" name="fecha" id="fecha" value="" required autocomplete="off" />
						</div>
					</div>

					<script type="text/javascript">
						$(document).ready(function() {
							$(".datetimepicker").datetimepicker({
								format: "Y-m-d H:i", // Formato de fecha y hora
								step: 15, // Intervalo de tiempo en minutos
								// Otros ajustes opcionales...
							});
						});
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

								<select name="section_id" class="form-control" id="section_id" data-plugin-selectTwo data-width="100%" onchange="updateListaAlumnos();">
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
								


									var optionsHtml2 = '<option value="">Selecciona un curso</option>'; // Agregar opción predeterminada
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

<style>.alumno-checkbox {
    margin-top: 5px; /* Ajusta según sea necesario */
}
</style>

					<script>
						function updateListaAlumnos() {
							
							var grado_id = $("#grado_id").val();
							var section_id = $("#section_id").val();

							// Crear el objeto de datos a enviar
							var postData = {
							
								grado_id: grado_id,
								section_id: section_id
							};

							$.ajax({
								type: "POST",
								url: base_url + 'solucionarios/obtener_alumnos',
								data: postData,
								dataType: 'json',

								success: function(response) {
									console.error('datos', response);

									var alumnosHtml = ''; // Variable para almacenar el HTML de los checkboxes de los alumnos
									response.forEach(item => {
										alumnosHtml += '<div  class="col-md-12 checkbox alumno-checkbox">';
										alumnosHtml += '<label><input type="checkbox" name="alumno[]" value="'+ item.id +'" checked> ' + item.last_name +' '+ item.first_name + '</label>';
										alumnosHtml += '</div>';

									});

									// Agregar los checkboxes de los alumnos al contenedor
									$("#alumnosDisponibles").html(alumnosHtml);


									console.log('Array de alumnos:', $('input[name="alumno[]"]:checked').map(function(){return this.value;}).get());


								},

								error: function(error) {
									console.error('Error en la solicitud AJAX:', response);
								}
							});
						}
					</script>







					<div class="form-group" id="subject_assign_group">
						<label class="col-md-3 control-label">Archivo <span class="required">*</span></label>
							<div class="col-md-6">

							<input type="file" class="form-control" id="archivo1" name="archivo1" accept=".pdf, .doc, .docx, image/jpeg, image/png, image/gif" required>

							</div>

							
					</div>							


					<div class="form-group">
						<div class="col-md-6">
							<button type="button" class="btn btn" id="btnMostrarAlumnos">Editar visibilidad para Alumnos</button>
							
						</div>
					</div>

					<div id="alumnosDisponibles" style="display: none; " >
									
					</div>

			
					<script>
						$(document).ready(function() {
							// Manejar el clic en el botón
							$("#btnMostrarAlumnos").click(function() {
								// Mostrar u ocultar el contenedor de alumnos disponibles
								$("#alumnosDisponibles").toggle();
							});
						});
					</script>

				<div class="form-group mt-3"> <!-- Agrega un margen top (mt) de 3 -->
					<button type="submit"  name="subirArchivo" class="btn btn-primary">Enviar Solucionario</button>
				</div>
		</div>




				
	</div>

<?php echo form_close(); ?>


			</div>



		</div>
	</div>
</section>














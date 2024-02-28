<?php  $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
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
			<?php echo form_close();?>
		</section>

		<?php if (isset($students)):?>
		<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
			<header class="panel-heading">
			
				<h4 class="panel-title"><i class="fas fa-user-graduate"></i> <?php echo translate('Alumnos con matricula reservada');?></h4>

					<div>
						<span class="badge badge-success" style="background-color: #DFF0D8; color: black;">Promovido y reserva</span>
						<span class="badge badge-warning" style="background-color: #FFF3CD; color: black;">Solo reserva</span>
						<span class="badge badge-warning" style="background-color: #FFFFFF; color: black;">No hizo reserva</span>
					</div>

			</header>
			<div class="panel-body mb-md">
				<table class="table table-bordered table-condensed table-hover table-export">
					<thead>
						<tr>
                            
							<th width="10" class="no-sort">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
								</div>
							</th>
							<th class="no-sort"><?=translate('photo')?></th>
                            <th ><?=translate('Reserva')?></th>

							<th><?=translate('name')?></th>
							<th><?=translate('class')." actual"?></th>
							<th><?=translate('section')?></th>
							<th><?=translate('register_no')?></th>
							<th width="80"><?=translate('roll')?></th>
							<th><?=translate('guardian_name')?></th>
						<?php
						$show_custom_fields = custom_form_table('student', $branch_id);
						if (count($show_custom_fields)) {
							foreach ($show_custom_fields as $fields) {
						?>
							<th><?=$fields['field_label']?></th>
						<?php } } ?>
							<th><?=translate('Generar Documento')?></th>

						</tr>
					</thead>
					<tbody>
						<?php
						foreach($students as $row):


							$this->db->select('*');
							$this->db->from('enroll');
							$this->db->where('student_id', $row['id']);
							$this->db->where('session_id', 6);
							$query = $this->db->get();

							if ($query->num_rows() > 0) {
								// Si hay al menos un registro
								$promovido = 1;
							} else {
								// Si no hay registros
								$promovido = 0;
							}


							$fee_progress = $this->student_model->getFeeProgress($row['student_id']);
						?>
						<tr <?php
							if ($row['RESERVA'] == 1 && $promovido == 1) {
								echo 'style="background-color: #DFF0D8;"'; // Verde suave
							} elseif ($row['RESERVA'] == 1 && $promovido == 0) {
								echo 'style="background-color: #ffeeba;"'; // Amarillo suave
							}
						?>>
							<td class="checked-area">
								<div class="checkbox-replace">
									<label class="i-checks">
										<input type="checkbox" class="cb_bulkdelete" id="<?=$row['student_id']?>"><i></i>
									</label>
								</div>
							</td>
							<td class="center"><img src="<?php echo get_image_url('student', $row['photo']); ?>" height="50"></td>
                            <td>
                                <?php 
                                if ($row['RESERVA'] == 1) {
                                    echo 'Sí';
                                } else {
                                    echo 'No';
                                }
                                ?>
                            </td>

							<td class="<?=($row['active'] == 0 ? 'text-danger' : '')?>"><?php echo $row['fullname'];?></td>
							<td><?php echo $row['class_name'];?></td>
							<td><?php echo $row['section_name'];?></td>
							<td><?php echo $row['register_no'];?></td>
							<td><?php echo $row['roll'];    ?></td>
						
							<td><?php echo (!empty($row['parent_id']) ? get_type_name_by_id('parent', $row['parent_id']) : 'N/A');?></td>
						<?php
						if (count($show_custom_fields)) {
							foreach ($show_custom_fields as $fields) {
						?>
							<td><?php echo get_table_custom_field_value($fields['id'], $row['id']);?></td>
						<?php } } ?>
							<td>
									<?php 
									if ($row['RESERVA'] == 1) {
										$parametro = $row['roll']; 
										echo '<a class="btn btn-primary" href="' . base_url('Reservation/pdf?parametro='.urlencode($parametro)) . '" target="_blank"><i class="fas fa-file-pdf"></i></a>';
									} else {
										echo '<a class="btn btn-primary disabled" disabled><i class="fas fa-file-pdf"></i></a>';
									}
									?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</section>
		<?php endif;?>
	</div>
</div>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="quickView">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-user-circle"></i> <?=translate('quick_view')?>
			</h4>
		</header>
		<div class="panel-body">
			<div class="quick_image">
				<img alt="" class="user-img-circle" id="quick_image" src="<?=base_url('uploads/app_image/defualt.png')?>" width="120" height="120">
			</div>
			<div class="text-center">
				<h4 class="text-weight-semibold mb-xs" id="quick_full_name"></h4>
				<p><?=translate('student')?> / <span id="quick_category"></p>
			</div>
			<div class="table-responsive mt-md mb-md">
				<table class="table table-striped table-bordered table-condensed mb-none">
					<tbody>
						<tr>
							<th><?=translate('register_no')?></th>
							<td><span id="quick_register_no"></span></td>
							<th><?=translate('roll')?></th>
							<td><span id="quick_roll"></span></td>
						</tr>
						<tr>
							<th><?=translate('admission_date')?></th>
							<td><span id="quick_admission_date"></span></td>
							<th><?=translate('date_of_birth')?></th>
							<td><span id="quick_date_of_birth"></span></td>
						</tr>
						<tr>
							<th><?=translate('blood_group')?></th>
							<td><span id="quick_blood_group"></span></td>
							<th><?=translate('religion')?></th>
							<td><span id="quick_religion"></span></td>
						</tr>
						<tr>
							<th><?=translate('email')?></th>
							<td colspan="3"><span id="quick_email"></span></td>
						</tr>
						<tr>
							<th><?=translate('mobile_no')?></th>
							<td><span id="quick_mobile_no"></span></td>
							<th><?=translate('state')?></th>
							<td><span id="quick_state"></span></td>
						</tr>
						<tr class="quick-address">
							<th><?=translate('address')?></th>
							<td colspan="3" height="80px;"><span id="quick_address"></span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss"><?=translate('close')?></button>
				</div>
			</div>
		</footer>
	</section>
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
<style type="text/css">
	#my_details .text-dark {
		font-weight: 600;
	}
</style>
<?php
$widget = (is_superadmin_loggedin() ? 3 : 4);
$getParent = $this->student_model->get('parent', array('id' => $student['parent_id']), true);
$branchID = $student['branch_id'];
if (empty($student['previous_details'])) {
	$previous_details = ['school_name' => '', 'qualification' => '', 'remarks' => ''];
} else {
	$previous_details = json_decode($student['previous_details'], true);
}
$currency_symbol = $global_config['currency_symbol'];
//SOLO SIRVE PARA OBTENER EL ESTATUS ( SI ES NECESARIO O NO EL CAMPO)
$first_name = $this->student_fields_model->getStatusProfile('first_name', $branchID);
$last_name = $this->student_fields_model->getStatusProfile('last_name', $branchID);
$gender = $this->student_fields_model->getStatusProfile('gender', $branchID);
$blood_group = $this->student_fields_model->getStatusProfile('blood_group', $branchID);
$birthday = $this->student_fields_model->getStatusProfile('birthday', $branchID);
$religion = $this->student_fields_model->getStatusProfile('religion', $branchID);
$mother_tongue = $this->student_fields_model->getStatusProfile('mother_tongue', $branchID);
$caste = $this->student_fields_model->getStatusProfile('caste', $branchID);
$present_address = $this->student_fields_model->getStatusProfile('present_address', $branchID); 
$permanent_address = $this->student_fields_model->getStatusProfile('permanent_address', $branchID);
$student_mobile_no = $this->student_fields_model->getStatusProfile('student_mobile_no', $branchID); 
$student_email = $this->student_fields_model->getStatusProfile('student_email', $branchID);
$city = $this->student_fields_model->getStatusProfile('city', $branchID); 
$state = $this->student_fields_model->getStatusProfile('state', $branchID);
$student_photo = $this->student_fields_model->getStatusProfile('student_photo', $branchID);
$previous_school_details = $this->student_fields_model->getStatusProfile('previous_school_details', $branchID);

$personal = false;
if ($first_name['status'] == 1 || $last_name['status'] == 1 || $gender['status'] == 1 || $blood_group['status'] == 1 || $birthday['status'] == 1 || $religion['status'] == 1 || $mother_tongue['status'] == 1 || $caste['status'] == 1 || $present_address['status'] == 1 || $permanent_address['status'] == 1 || $student_mobile_no['status'] == 1 || $student_email['status'] == 1 || $city['status'] == 1 || $state['status'] == 1 || $student_photo['status'] == 1) {
	$personal = true;
}
?>

<div class="row">

    <?php if ($reservationState == 0 && $deudaState == 1 && $utilesState == 1 && $comportamientoState == 1) { ?>

	<div class="col-md-12">
		<section class="panel">
			<div class="tabs-custom">
				
				<div class="tab-content">
				
					<?php if ($personal == true || $previous_school_details['status'] == 1) { ?>
					<div id="profile" class="tab-pane active">
						<?php 
						echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data'));
						$category = $this->student_fields_model->getStatusProfile('category', $branchID);
						$admission_date = $this->student_fields_model->getStatusProfile('admission_date', $branchID);
						if ($category['status'] == 1 || $admission_date['status'] == 1) {
			                $v = (floatval($category['status']) + floatval($admission_date['status']));
			                $div = floatval(12 / $v);
						?>
						<input type="hidden" name="student_id" value="<?php   if($loggedinRoleID==7){
         get_loggedin_user_id();
    }else{
       ($this->input->get('hola'));

    } ?>">
						<!-- academic details-->
					
						<div class="row">
							


							
						</div>
					<?php } ?>

					<?php if ($personal == true) { ?>
						<!-- student details -->
						<div class="headers-line mt-md">
							<i class="fas fa-user-check"></i> <?=translate('student_details')?>
						</div>

						<?php
						$v = (floatval($first_name['status']) + floatval($last_name['status']) + floatval($gender['status']));
						$div = ($v == 0) ? 12 : floatval(12 / $v);
						?>
						<div class="row">
							<?php if ($first_name['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('first_name')?><?php echo $first_name['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-user-graduate"></i></span>
										<input type="text" class="form-control" name="first_name" value="<?=set_value('first_name', $student['first_name'])?>"/>
									</div>
									<span class="error"></span>
								</div>
							</div>


							<input type="hidden" name="id" value="<?= set_value('id', $student['id']) ?>" />


							
							<?php } if ($last_name['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('last_name')?><?php echo $last_name['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-user-graduate"></i></span>
										<input type="text" class="form-control" name="last_name" value="<?=set_value('last_name', $student['last_name'])?>" />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } if ($gender['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('gender')?><?php echo $gender['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<?php
										$arrayGender = array(
											'male' => translate('male'),
											'female' => translate('female')
										);
										echo form_dropdown("gender", $arrayGender, set_value('gender', $student['gender']), "class='form-control'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="row">
							<?php 
							$v = (floatval($blood_group['status']) + floatval($birthday['status']));
							$div = ($v == 0) ? 12 : floatval(12 / $v);
							if ($blood_group['status'] == 1) {
								?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('blood_group')?><?php echo $blood_group['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<?php
										$bloodArray = $this->app_lib->getBloodgroup();
										echo form_dropdown("blood_group", $bloodArray, set_value("blood_group", $student['blood_group']), "class='form-control populate' data-plugin-selectTwo 
										data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</div>
							<?php } if ($birthday['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('birthday')?><?php echo $birthday['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
										<input type="text" class="form-control" name="birthday" value="<?=set_value('birthday', $student['birthday'])?>" data-plugin-datepicker
										data-plugin-options='{ "startView": 2 }' />
									</div>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="row">
							<?php
							$v = (floatval($religion['status']) + floatval($mother_tongue['status']) + floatval($caste['status']));
							$div = ($v == 0) ? 12 : floatval(12 / $v);

							if ($mother_tongue['status'] == 1) {
								?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('mother_tongue')?><?php echo $mother_tongue['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<input type="text" class="form-control" name="mother_tongue" value="<?=set_value('mother_tongue', $student['mother_tongue'])?>" />
									<span class="error"></span>
								</div>
							</div>
							<?php } if ($religion['status'] == 0) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('religion')?><?php echo $religion['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<input type="text" class="form-control" name="religion" value="<?=set_value('religion', $student['religion'])?>" />
									<span class="error"></span>
								</div>
							</div>
							<?php } if ($mother_tongue['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('caste')?><?php echo $caste['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<input type="text" class="form-control" name="caste" value="<?=set_value('caste', $student['caste'])?>" />
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="row">
							<?php
							$v = (floatval($student_mobile_no['status']) + floatval($student_email['status']) + floatval($city['status']) + floatval($state['status']));
							$div = ($v == 0) ? 12 : floatval(12 / $v);
							if ($student_mobile_no['status'] == 1) {
								?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('mobile_no')?><?php echo $student_mobile_no['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
										<input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno', $student['mobileno'])?>" />
									</div>
									<span class="error"></span>
								</div>
							</div>
						<?php } if ($student_email['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('email')?><?php echo $student_email['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
										<input type="text" class="form-control" name="email" id="email" value="<?=set_value('email', $student['email'])?>" />
									</div>
									<span class="error"></span>
								</div>
							</div>
						<?php } if ($city['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('city')?><?php echo $city['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<input type="text" class="form-control" name="city" value="<?=set_value('city', $student['city'])?>" />
									<span class="error"></span>
								</div>
							</div>
						<?php } if ($state['status'] == 1) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('state')?><?php echo $state['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<input type="text" class="form-control" name="state" value="<?=set_value('state', $student['state'])?>" />
									<span class="error"></span>
								</div>
							</div>
						<?php } ?>
						</div>
						<div class="row">
							<?php 
							$v = (floatval($present_address['status']) + floatval($permanent_address['status']));
							$div = ($v == 0) ? 12 : floatval(12 / $v);
							if ($present_address['status'] == 1) {
								?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
                                    
									<label class="control-label"><?=translate('present_address')?><?php echo $present_address['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<textarea name="present_address" rows="2" class="form-control" aria-required="true"><?=set_value('present_address', $student['current_address'])?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<?php } if ($permanent_address['status']) { ?>
							<div class="col-md-<?php echo $div ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('permanent_address')?><?php echo $permanent_address['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<textarea name="permanent_address" rows="2" class="form-control" aria-required="true"><?=set_value('permanent_address', $student['permanent_address'])?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>
						<?php if ($student_photo['status'] == 1) { ?>
						<div class="row mb-md">
							<div class="col-md-12">
								<div class="form-group">
									<label for="input-file-now"><?=translate('profile_picture')?><?php echo $student_photo['required'] == 1 ? ' <span class="required">*</span>' : ''; ?></label>
									<input type="file" name="user_photo" class="dropify" data-default-file="<?=get_image_url('student', $student['photo'])?>" />
									<input type="hidden" name="old_user_photo" value="<?php echo $student['photo']; ?>" />
									<span class="error"></span>
								</div>
							</div>
						</div>
						<?php } ?>
					
						<?php }   ?>
						


                        <!-- parents details -->
						<div class="headers-line mt-md">
							<i class="fas fa-user-check"></i> <?=translate('parents_details')?>
						</div>

                        <div class="row">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('name')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-user"></i></span>
										<input class="form-control" name="name" type="text" value="<?=set_value('name', $getParent  ['name'])?>" autocomplete="off" />
									</div>
									<span class="error"><?php echo form_error('name'); ?></span>
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('relation')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="relation" value="<?=set_value('relation', $getParent['relation'])?>" autocomplete="off" />
									<span class="error"><?php echo form_error('relation'); ?></span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('father_name')?></label>
									<input class="form-control" name="father_name" type="text" value="<?=set_value('father_name', $getParent['father_name'])?>" autocomplete="off" />
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('mother_name')?></label>
									<input type="text" class="form-control" name="mother_name" value="<?=set_value('mother_name', $getParent['mother_name'])?>" autocomplete="off" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('occupation')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="occupation" value="<?=set_value('occupation', $getParent['occupation'])?>" autocomplete="off" />
									<span class="error"><?php echo form_error('occupation'); ?></span>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('income')?></label>
									<input type="text" class="form-control" name="income" value="<?=set_value('income', $getParent['income'])?>" autocomplete="off" />
									<span class="error"><?php echo form_error('income'); ?></span>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('education')?></label>
									<input type="text" class="form-control" name="education" value="<?=set_value('education', $getParent['education'])?>" autocomplete="off" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('city')?></label>
									<input type="text" class="form-control" name="cityParent" value="<?=set_value('cityParent', $getParent['city'])?>" autocomplete="off" />
								</div>
							</div>

							<input type="hidden" name="idPadre" value="<?= set_value('idPadre', $getParent['id']) ?>" />


							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('state')?></label>
									<input type="text" class="form-control" name="stateParent" value="<?=set_value('state', $getParent['state'])?>" autocomplete="off" />
								</div>
							</div>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
										<input type="text" class="form-control" name="mobilenoParent" value="<?=set_value('mobileno', $getParent['mobileno'])?>" autocomplete="off" />
									</div>
									<span class="error"><?php echo form_error('mobileno'); ?></span>
								</div>
							</div>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('email')?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
										<input type="email" class="form-control" name="emailParent" id="email" value="<?=set_value('email', $getParent['email'])?>" />
									</div>
									<span class="error"><?php echo form_error('email'); ?></span>
								</div>
							</div>
						</div>
						<div class="row mb-md">
							<div class="col-md-12 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('address')?></label>
									<textarea name="addressParent" rows="2" class="form-control" aria-required="true"><?=set_value('address', $getParent['address'])?></textarea>
								</div>
							</div>
						</div>
						

                        <div class="panel-footer">
							<div class="row">

								<div class="col-md-offset-9 col-md-3">
                                    <label>
                                        <input type="checkbox" id="termsCheckbox"> Al dar clic en "Reservar" doy señal de conformidad y aceptación con todos los términos y condiciones
                                    </label>
                                </div>

                             <!-- Modal -->
                                <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h5 class="modal-title">Términos y condiciones</h5>

                                            </div>

                                            <div class="modal-body">
                                                <!-- Aquí colocas el texto de los términos y condiciones -->
                                                <p>SOLICITO: Reservar vacante de la matrícula 2024 a favor de mi menor hijo(a) para que continúe sus estudios y me comprometo a efectuar la ratificación de la misma en los plazos establecidos.
                                                   <br> DECLARO: Conocer, que al momento de reserva de vacante y ratificar la matrícula para el año escolar 2024 procederá únicamente si yo me encuentro al día en el cumplimiento del pago de las pensiones de enseñanza u otros conceptos correspondientes al año escolar 2021.
                                                   <br> DECLARO: Conocer que para la ratificación de matrícula en el grado correspondiente al año escolar 2024, depende de que mi menor hijo haya sido promovido de grado en el año 2023, según la normativa vigente establecida por el Ministerio de Educación. No se puede asegurar la vacante del estudiante en el año de repitencia por lo cual no existirá devolución ni reembolso de ningún concepto en caso esto ocurra. 
                                                   <br>ACEPTO: Que, si mi menor hijo presenta nota desaprobatoria en conducta en EL COLEGIO, documentado a través de la libreta de notas o el acta de notas de fin de año, podrá ser admitido previa firma de un compromiso de conducta con EL COLEGIO, caso contrario se retirará la reserva de vacante y la ratificación de matrícula para el año escolar 2024 y no existirá devolución ni reembolso de ningún concepto en caso esto ocurra.</p>
                                                                                                </div>
                                            <!-- Si se quiere un footer -->
                                            <!-- <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="col-md-offset-9 col-md-3" data-toggle="modal" data-target="#termsModal">Ver términos y condiciones</a>


								<div class="col-md-offset-9 col-md-3">
                                    <button class="btn btn-default btn-block" id="reservationButton" type="submit" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="fas fa-plus-circle"></i> RESERVAR</button>
								</div>	
							</div>
						</div>
					<?php echo form_close(); ?>
					</div>
					<?php } ?>
				</div>
			</div>
			
		</section>
	</div>
    <?php } else { ?>
    <div class="container">
        <?php if ($deudaState != 1) { ?>
            <div class="alert alert-danger" role="alert">
                Deudas pendientes con la institución.
            </div>
        <?php } ?>
        <?php if ($utilesState != 1) { ?>
            <div class="alert alert-warning" role="alert">
                Pendiente la entrega de útiles.
            </div>
        <?php } ?>
        <?php if ($comportamientoState != 1) { ?>
            <div class="alert alert-warning" role="alert">
				Ya no hay vacantes disponibles para el grado y sede.
            </div>
        <?php } ?>
        <?php if ($reservationState == 1) { ?>
            <div class="alert alert-success" role="alert">
                Reserva realizada correctamente.
            </div>
            <a class="btn btn-success" href="<?php echo base_url();?>Reservation/pdf" target="_blank">
        Generar documento de Reserva de Vacante
      </a>
        <?php } ?>
    </div>
<?php } ?>
<script>
document.getElementById('reservationButton').disabled = true; // Deshabilitar el botón al inicio

document.getElementById('termsCheckbox').addEventListener('change', function() {
    var reservationButton = document.getElementById('reservationButton');
    reservationButton.disabled = !this.checked; // Habilitar o deshabilitar el botón según el estado del checkbox
});
    

var termsLink = document.getElementById('termsLink');
var modal = document.getElementById('termsModal');
var closeBtn = document.getElementsByClassName('close')[0];

// Mostrar el modal al hacer clic en el enlace
termsLink.addEventListener('click', function() {
    modal.style.display = 'block';
});

// Ocultar el modal al hacer clic en el botón de cierre
closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
});
</script>

</div>
<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
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
				<?php endif; ?>
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
<?php if (isset($invoicelist)): ?>
		<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations'];?>" data-appear-animation-delay="100">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?=translate('invoice_list')?>
					<div class="panel-btn">
						<button type="submit" class="btn btn-default btn-circle" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-print"></i> <?=translate('generate')?>
						</button>
					</div>
				</h4>
			</header>
			<div class="panel-body">
				<div class="mb-md mt-md">
					<div class="export_title"><?=translate('invoice') . " " . translate('list')?></div>
					<table class="table table-bordered table-condensed table-hover mb-none tbr-top table-export">
						<thead>
							<tr>
								
								<th><?=translate('student')?></th>
								<th><?=translate('class')?></th>
								<th><?=translate('section')?></th>
								<th><?=translate('Razon de pago')?></th>
								<th><?=translate('Monto')?></th>
								<th><?=translate('Numero operacion')?></th>
								<th><?=translate('Fecha')?></th>
								<th><?=translate('status')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							foreach($invoicelist as $row):
								?>
							<tr>
								
								<td><?php echo $row['last_name'] . ' ' . $row['first_name'];?></td>
								<td><?php echo $row['class_name'];?></td>
								<td><?php echo $row['section_name'];?></td>
								<td><?php echo $row['fees_type'];?></td>
								<td><?php echo $row['amount'];?></td>
								<td><?php echo $row['numero_operacion'];?></td>
								<td><?php echo $row['date'];?></td>

								<td>

									<?php
										$labelmode = '';
										$status = $this->fees_model->getInvoiceStatus($row['student_id'])['status'];
										if($status == 'unpaid') {
											$status = translate('unpaid');
											$labelmode = 'label-danger-custom';
										} elseif($status == 'partly') {
											$status = translate('partly_paid');
											$labelmode = 'label-info-custom';
										} elseif($status == 'total') {
											$status = translate('total_paid');
											$labelmode = 'label-success-custom';
										}
										echo "<span class='value label " . $labelmode . " '>" . $status . "</span>";
									?>
								</td>
								<td>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $documento['nombre_document']; ?></h5>
                                                <a href="<?php echo base_url(  str_replace(' ', '_','Student/verPDF/'.$row['ruta'].'?parametro='. $row['student_id'])); ?>" target="_blank" class="btn btn-primary">
                                                    <i class="fas fa-file-pdf"></i> Abrir PDF
                                                </a>

												<?php echo btn_delete('fees2/delete_comprobante_pago/' . $row['id']);?>

                                        </div>


                                
                                </td>
                               

                                
							</tr>
							<?php  endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
        $('form.printIn').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: 'html',
                cache: false,
                beforeSend: function () {
                    btn.button('loading');
                },
                success: function (data) {
                	fn_printElem(data, true);
                },
                error: function () {
	                btn.button('reset');
	                alert("An error occured, please try again");
                },
	            complete: function () {
	                btn.button('reset');
	            }
            });
        });
	});
</script>

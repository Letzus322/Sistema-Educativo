










<?php $widget = 4?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?php echo translate('Elige Alumno'); ?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
				
					<div class="col-md-4">
						

                        <div class="form-group">
							<?php
								$arrayParent = $this->app_lib->getSelectListStudent('student');
								echo form_dropdown("student_id", $arrayParent, set_value('student_id'), "class='form-control' id='student_id'
								data-plugin-selectTwo data-width='100%' ");
							?>
							<span class="error"><?=form_error('student_id')?></span>
						</div>

					</div>
				
				
				</div>

                <div class="row mb-sm">
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
                        <div class="col-md-<?php echo $widget; ?> mb-sm">
                            <div class="form-group">
                                <label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
                                <?php
                                    $arrayClass = $this->app_lib->getClass($branch_id);
                                    echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
                                    required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
                                ?>
                            </div>
                        </div>
                        <div class="col-md-<?php echo $widget; ?> mb-sm">
                            <div class="form-group">
                                <label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
                                <?php
                                    $arraySection = $this->app_lib->getSections(set_value('class_id'));
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
						<button type="submit" name="submit" value="search" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('Actualizar')?></button>
					</div>
				</div>
			</footer>

		<?php echo form_close();?>
		</section>

	</div>
</div>

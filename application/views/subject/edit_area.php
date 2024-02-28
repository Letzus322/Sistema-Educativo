<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('subject/index')?>"><i class="fas fa-list-ul"></i> <?=translate('subject') . ' ' . translate('list')?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit') . ' ' . translate('Area')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="edit" class="tab-pane active">
				<?php echo form_open('subject/saveArea', array('class' => 'form-horizontal form-bordered frm-submit'));?>
					
					<input type="hidden" name="area_id" value="<?=$subject['id']?>">
					
					


					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('Area')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name" value="<?=$subject['name']?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('subject_code')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="subject_code" value="<?=$subject['name_code']?>" />
							<span class="error"></span>
						</div>
					</div>

				
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>
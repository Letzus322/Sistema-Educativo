<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('assign'). ' ' . translate('list')?></a>
			</li>
<?php if (get_permission('subject_class_assign', 'is_add')): ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('assign')?></a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div  id="list" class="tab-pane active">
				<table class="table table-bordered table-hover table-condensed mb-none tbr-top table-export">
					<thead>
						<tr>
							<th><?=translate('sl')?></th>
						<?php if (is_superadmin_loggedin()) { ?>
							<th><?=translate('branch')?></th>
						<?php } ?>
							<th><?=translate('class')?></th>
							<th><?=translate('section')?></th>
							<th><?=translate('subject')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php 	
							$count = 1;
							if (count($assignlist)){
								foreach ($assignlist as $row):
									?>
						<tr>
							<td><?php echo $count++;?></td>
						<?php if (is_superadmin_loggedin()) { ?>
							<td><?php echo $row['branch_name'];?></td>
						<?php } ?>
							<td><?php echo $row['class_name'];?></td>
							<td><?php echo $row['section_name'];?></td>
							<td class="text-dark"><?php echo $this->subject_model->get_subject_list($row['class_id'], $row['section_id']);?></td>
							<td>
							<?php if (get_permission('subject_class_assign', 'is_edit')): ?>
								<!-- update link -->
								<a href="javascript:void(0);" class="btn btn-circle btn-default icon" onclick="getClassAssignM(<?=$row['class_id']?>,<?=$row['section_id']?>)">
									<i class="fas fa-pen-nib"></i>
								</a>
							<?php endif; if (get_permission('subject_class_assign', 'is_delete')): ?>
								<!-- delete link -->
								<?php echo btn_delete('subject/class_assign_delete/'. $row['class_id'] . '/' . $row['section_id']);?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('subject_class_assign', 'is_add')): ?>
			<div class="tab-pane" id="create">
				<?php echo form_open('subject/class_assign_save', array('class' => 'form-horizontal form-bordered frm-submit')); ?>
					
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('class')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$subjects = $this->db->get('grado')->result();
								$subjects = $this->db->get('grado')->result_array();

								$grados = $this->db->get('grado')->result_array();
								foreach ($grados as $row) {
									$arrayClass[$row['id']] = $row['name'];
								}
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' '
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('subject')?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<select name="subjects[]" class="form-control" data-plugin-selectTwo multiple id='subject_holder' data-width="100%"
							data-plugin-options='{"placeholder": "<?=translate('select_multiple_subject')?>"}'>
								<?php 
								$this->db->select('subject.*, area.name as area_name');
								$this->db->from('subject');
								$this->db->join('area', 'area.id = subject.area_id');
								
								$subjects = $this->db->get()->result();

								foreach ($subjects as $subject):
								?>
								<option value="<?=$subject->id?>" <?=set_select('subjects[]', $subject->id)?>><?=html_escape($subject->area_name.'-'.$subject->name)?></option>
								<?php endforeach; ?>
							</select>
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
				<?php echo form_close();?>
			</div>
<?php endif; ?>
		</div>
</section>

<?php if (get_permission('subject_class_assign', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-edit"></i> <?php echo translate('edit_assign'); ?>
			</h4>
		</header>
		<?php echo form_open('subject/class_assign_edit', array('class' => 'frm-submit')); ?>
			<div class="panel-body">
				<input type="hidden" name="branch_id" id="ebranch_id" value="" />
				<input type="hidden" name="class_id" id="eclass_id" value="" />
				<input type="hidden" name="section_id" id="esection_id" value="" />
				<div class="form-group mt-mb mb-lg">
					<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
					<select name="subjects[]" class="form-control" data-plugin-selectTwo multiple id='esubject_holder' data-width="100%"
					data-plugin-options='{ "placeholder": "<?=translate('select_branch_first')?>" }'>
					</select>
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default mr-xs" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?=translate('update')?>
						</button>
						<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<?php endif; ?>


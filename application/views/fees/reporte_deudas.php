<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">
	
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
								
								<th><?=translate('Numero de registro')?></th>
								<th><?=translate('Apellidos y Nombres: ')?></th>
								<th><?=translate('Grado Sede')?></th>
								<th><?=translate('Monto')?></th>
				
								<th><?=translate('Fecha Vencimiento')?></th>
							</tr>
						</thead>
						<tbody>
							<?php


							$count = 1;
							foreach($invoicelist as $row):


												$fine = $this->fees_model->feeFineCalculation($row['allocation_id'], $row['fee_type_id']);	
									            $b = $this->fees_model->getBalance($row['allocation_id'], $row['fee_type_id']);
									            $fine = abs($fine - $b['fine']);
									            $fully_total_fine += $fine;

								?>
							<tr>
								
                            <td><?php echo sprintf('%012d', $row['username']); ?></td>

								<td><?php echo $row['last_name'] . ' ' . $row['first_name'];?></td>
								<td><?php echo $row['class_name']. ' '.$row['branch_name'];?></td>
								<td><?php echo number_format($row['net_amount'] + $fine, 2);?></td>
                                <td><?php echo $row['due_date'];?></td>

								
                               

                                
							</tr>
							<?php  endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
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

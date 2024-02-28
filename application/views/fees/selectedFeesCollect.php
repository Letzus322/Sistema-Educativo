<thead>
	<th><?=translate('fees_type')?> <span class="required">*</span></th>
	<th><?=translate('Número Documento')?> <span class="required">*</span></th>

	<th><?=translate('Fecha Canjeo')?> <span class="required">*</span></th>
	<th><?=translate('Fecha Pago')?> <span class="required">*</span></th>

	<th><?=translate('amount')?> <span class="required">*</span></th>
	<th><?=translate('discount')?> <span class="required">*</span></th>
	<th><?=translate('fine')?> <span class="required">*</span></th>
	<th><?=translate('payment_method')?> <span class="required">*</span></th>
	<th><?=translate('Tipo Documento')?> <span class="required">*</span></th>


<?php
$colspan = 7;
$links = $this->fees_model->get('transactions_links', array('branch_id' => $branch_id), true);
if ($links['status'] == 1) {
	$colspan +=1;
?>
	<th><?=translate('account')?> <span class="required">*</span></th>
<?php } ?>
	<th><?=translate('Numero de Operacion')?></th>
</thead>
<tbody>
	<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
	<input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
<?php
$total_fine = 0;
$total_discount = 0;
$total_paid = 0;
$total_balance = 0;
$total_amount = 0;
$count = 0;

$this->db->from('fee_payment_history');
$this->db->join('fee_allocation', 'fee_allocation.id = fee_payment_history.allocation_id');
$this->db->where('fee_allocation.branch_id', $branch_id);
$this->db->where('metodopago', '1');

$boletaNumero = $this->db->count_all_results()+1;
$formato = 'B001-%06d'; // %06d indica que debe haber 6 dígitos, rellenados con ceros si es necesario
$branch_id_padded = str_pad($branch_id, 3, '0', STR_PAD_LEFT); // Ajusta a una longitud de 3 caracteres
$formato = 'B' . $branch_id_padded . '-%06d'; // Concatenar $branch_id al inicio del formato
$boletaFormateado = sprintf($formato, $boletaNumero);

$this->db->from('fee_payment_history');
$this->db->join('fee_allocation', 'fee_allocation.id = fee_payment_history.allocation_id');
$this->db->where('fee_allocation.branch_id', $branch_id);
$this->db->where('metodopago', '2');
$reciboNumero = $this->db->count_all_results()+1;
$formato2 = 'R' . $branch_id_padded . '-%06d'; // Concatenar $branch_id al inicio del formato

$reciboFormateado = sprintf($formato2, $reciboNumero);

foreach ($record_array as $key => $value) {
	$b = $this->fees_model->getBalance($value->allocationID, $value->feeTypeID);
	$balance = $b['balance'];
	if ($balance != 0) {
	$count++;
	$fine = $this->fees_model->feeFineCalculation($value->allocationID, $value->feeTypeID);
	$fine = abs($fine - $b['fine']);
	$typeDetails = $this->db->select('name,fee_code')->where('id', $value->feeTypeID)->get('fees_type')->row();
	
	$this->db->where('allocation_id',$value->allocationID);
    $this->db->where('type_id', $value->feeTypeID);
    $query = $this->db->get('descuentos');
    $descuento = $query->row();

	$boletaFormateado = sprintf($formato, $boletaNumero+$key);


 ?>
	<tr>
		
		<input type="hidden" name="collect_fees[<?php echo $key ?>][allocation_id]" value="<?php echo $value->allocationID; ?>">
		<input type="hidden" name="collect_fees[<?php echo $key ?>][type_id]" value="<?php echo $value->feeTypeID; ?>">
		<td class="fee-modal">
			<p style="margin-bottom: 2px; margin-left:5px"><?php echo $typeDetails->name; ?></p>
			<span style="color: #606060; margin-left: 8px;">- <?php echo $typeDetails->fee_code; ?></span>
		</td>



		<td class="fee-modal">
			<div class="form-group">
				<input type="text" class="form-control" name="collect_fees[<?php echo $key ?>][numeroDocumento]" value='<?=$boletaFormateado?>' autocomplete="off" id="numeroDocumento<?php echo $key; ?>" />
				<span class="error"></span>
			</div>
		</td>


		<td class="fee-modal">
			<div class="form-group">
				<input type="text" class="form-control datepicker" name="collect_fees[<?php echo $key ?>][date]" value="<?=date('Y-m-d')?>" autocomplete="off" />
				<span class="error"></span>
			</div>
		</td>

		<td class="fee-modal">
			<div class="form-group">
				<input type="text" class="form-control datepicker" name="collect_fees[<?php echo $key ?>][datePay]" placeholder="Ingrese Fecha" required autocomplete="off" />
				<span class="error"></span>
			</div>
		</td>



		<td class="fee-modal">
			<div class="form-group">
				<input type="text" class="form-control" name="collect_fees[<?php echo $key ?>][amount]" value="<?=number_format($balance, 2, '.', '')?>" autocomplete="off" />
				<span class="error"></span>
			</div>
		</td>
		<td class="fee-modal">
			<div class="form-group">
				<input type="text" class="form-control" name="collect_fees[<?php echo $key ?>][discount_amount]" value='<?php echo $descuento->discount; ?>' autocomplete="off" />
				<span class="error"></span>
			</div>
		</td>
		<td class="fee-modal">
			<div class="form-group">
				<input type="text" class="form-control" name="collect_fees[<?php echo $key ?>][fine_amount]" value="<?php echo number_format($fine, 2, '.', ''); ?>" autocomplete="off" />
				<span class="error"></span>
			</div>
		</td>
		

	

		<td class="fee-modal">
			<div class="form-group">
				<?php
				$payvia_list = array(
					'1' => 'Boleta Electrónica',
					'2' => 'Recibo'
				);

				echo form_dropdown(
					"collect_fees[$key][pay_document]",
					$payvia_list,
					'',
					"class='form-control selectTwo' data-width='100%' data-minimum-results-for-search='Infinity' onchange='changeDocumentNumber(this, $key)'"
				);
				?>
				<span class="error"></span>
			</div>
		</td>

		<td class="fee-modal">
			<div class="form-group">
				<?php
					$payvia_list = $this->app_lib->getSelectList('payment_types');
					echo form_dropdown("collect_fees[$key][pay_via]", $payvia_list, 1, "class='form-control selectTwo' data-width='100%'
					data-minimum-results-for-search='Infinity' ");
				?>
				<span class="error"></span>
			</div>
		</td>

    <?php if ($links['status'] == 1) { ?>
		<td class="fee-modal">
			<div class="form-group">
	            <?php
	            $accounts_list = $this->app_lib->getSelectByBranch2('accounts', $branch_id);

	            echo form_dropdown("collect_fees[$key][account_id]", $accounts_list, $links['deposit'], "class='form-control selectTwo' data-width='100%'");
	            ?>
	            <span class="error"></span>
        	</div>
		</td>
    <?php } ?>



	

		<td class="fee-modal">
			<div class="form-group">
				<input type="text" class="form-control" name="collect_fees[<?php echo $key ?>][remarks]" value='' autocomplete="off" id="remarks<?php echo $key; ?>" required />
				<span class="error"></span>
			</div>
		</td>


	</tr>
<?php } }
if ($count == 0) {
	echo '<tr><td colspan="'.$colspan.'"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
}
?>

</tbody>

<script type="text/javascript">
	
$(function() {
  $( ".adatepicker" ).datepicker({ 

        format: "yyyy-mm-dd",
        autoclose: true,
        orientation: "bottom",
        endDate: "today"

   });
});


</script>

<script>
function changeDocumentNumber(selectElement, key) {
    var selectedValue = selectElement.value;
	

	var boletaKey = parseInt('<?php echo $boletaNumero; ?>') + parseInt(key);
    var reciboKey = parseInt('<?php echo $reciboNumero; ?>') + parseInt(key);
    var branch = parseInt('<?php echo $branch_id; ?>') ;

    // Se formatean los números con el formato deseado en JavaScript
    var boletaFormatted = 'B00'+branch+'-' + ('00000' + boletaKey).slice(-6);
    var reciboFormatted = 'R00'+branch+'-' + ('00000' + reciboKey).slice(-6);


    var documentNumberField = document.getElementById('numeroDocumento' + key);

    if (selectedValue === '1') {
        // Cambiar el valor del campo de texto a $boletaNumero
        documentNumberField.value = boletaFormatted;
    } else if (selectedValue === '2') {
        // Cambiar el valor del campo de texto a $reciboNumero
        documentNumberField.value = reciboFormatted;
    }
}
</script>
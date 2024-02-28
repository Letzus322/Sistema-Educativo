
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
                    

                    <div class="col-md-4 mb-sm">
                        <div class="form-group">
                            <label class="control-label"><?= translate('Año de inicio') ?> <span class="required">*</span></label>
                            <input type="number" name="anio" class="form-control" value="<?= $anio ?>" required />
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?= translate('Mes de inicio') ?> <span class="required">*</span></label>
                            <select name="mes" class="form-control" required>
                                <option value="1" <?= ($mes == 1) ? 'selected' : '' ?>>Enero</option>
                                <option value="2" <?= ($mes == 2) ? 'selected' : '' ?>>Febrero</option>
                                <option value="3" <?= ($mes == 3) ? 'selected' : '' ?>>Marzo</option>
                                <option value="4" <?= ($mes == 4) ? 'selected' : '' ?>>Abril</option>
                                <option value="5" <?= ($mes == 5) ? 'selected' : '' ?>>Mayo</option>
                                <option value="6" <?= ($mes == 6) ? 'selected' : '' ?>>Junio</option>
                                <option value="7" <?= ($mes == 7) ? 'selected' : '' ?>>Julio</option>
                                <option value="8" <?= ($mes == 8) ? 'selected' : '' ?>>Agosto</option>
                                <option value="9" <?= ($mes == 9) ? 'selected' : '' ?>>Septiembre</option>
                                <option value="10" <?= ($mes == 10) ? 'selected' : '' ?>>Octubre</option>
                                <option value="11" <?= ($mes == 11) ? 'selected' : '' ?>>Noviembre</option>
                                <option value="12" <?= ($mes == 12) ? 'selected' : '' ?>>Diciembre</option>
                            </select>
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
		<section class="panel appear-animation" data-appear-animation="" data-appear-animation-delay="100">
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
                                <th>Codigo</th>
                                <th>Sede</th>
								<th>Monto</th>
								<th>Adquiriente</th>
								<th>Estado/Sunat</th>
                                <th>Acciones</th>

							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							foreach($invoicelist as $row):
								?>
                               
							<tr>
                                
								<td><?php echo $row['nro_documento'];?></td>
                                <td><?php echo $row['branchName'];?></td>
								<td><?php echo $row['total_amount'];?></td>
								<td><?php echo $row['adquiriente'];?></td>
                                <td>
                                    <?php
                                        $estado = $row['estado'];
                                        $estadoSunat= $row['estado_envio'];
                                        if ($estadoSunat == 1) {
                                            echo '<span style="color: green;">&#10004; Enviado</span>'; // Check verde
                                        } elseif ($estadoSunat == 0) {
                                            if ($row['metodo_pago'] == 2) {
                                                echo '<span style="color: orange;">&#9679;Es un recibo</span> '; // Círculo naranja con raya

                                            } 
                                           else {

                                            echo '<span style="color: red;">&#10008; No enviado</span>'; // X roja
                                        } 
                                        }
                                    ?>
                                </td>
                                

                                <td>
                                    
                                              

                                                <a href="<?php echo base_url( $row['direccion_xml']); ?>" class="btn btn-link btn-lg text-block" target="_blank" download>
                                                    <i class="dripicons-download"></i> Descargar XML 
                                                </a>

                                                <a href="<?php echo base_url( $row['direccion_cdr']); ?>" class="btn btn-link btn-lg text-block" target="_blank" download>
                                                    <i class="dripicons-download"></i> Descargar CDR 
                                                </a>

     
                                                <button type="button"  class="btn btn-default btn-block" onclick="mostrarBoletaElectronica('<?php echo $row['boletaID']; ?>')">                                                    
                                                <i class="fas fa-info-circle text-info"></i> Descargar PDF </button>

                                                <script type="text/javascript">
                                                    function mostrarBoletaElectronica(boletaID) {
                                                        // Abre la URL del controlador y la función en una nueva ventana con el parámetro
                                                        window.open('<?php echo site_url("fees/boleta_electronica"); ?>/' + boletaID, '_blank');
                                                    }
                                                </script>
                                           
                                                
                                           
                                    
                                </td>



								
							
							</tr>




                            <div class="modal fade" id="exampleModal<?= $row['idDocumento'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">


                        
                                            <div class="row">
                                                <div class="col-md-3 mb-sm">
                                                    <div class="form-group">
                                                        <label class="control-label">Tipo de documento:</label>
                                                        <?php if ($row['metodo_pagoID'] == 2): ?>
                                                            <p class="text-dark">Recibo</p>
                                                        <?php elseif ($row['metodo_pagoID'] == 1): ?>
                                                            <p class="text-dark">Boleta</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-sm">
                                                    <div class="form-group">
                                                        <label class="control-label">Número de documento</label>
                                                        <p class="text-dark"><?=$row['nro_documento']?></p>

                                                    </div>
                                                </div>

                                                <div class="col-md-3 mb-sm">
                                                    <div class="form-group">
                                                        <label class="control-label">Fecha :</label>
                                                        <p class="text-dark"><?=$row['date']?></p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 mb-sm">
                                                    <div class="form-group">
                                                        <label class="control-label">Medio de Pago :	</label>
                                                        <p class="text-dark"><?=$row['name']?></p>
                                                    </div>
                                                </div>
                                                
                                            </div>


                                            <div class="row">
                                                <div class="col-md-3 mb-sm">
                                                    <div class="form-group">
                                                        <label class="control-label">Sede :	</label>
                                                        <p class="text-dark"><?php echo $row['branchName'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-sm">
                                                    <div class="form-group">
                                                        <label class="control-label">Nro de Cuenta	</label>
                                                        <p class="text-dark"><?php echo $row['class_name'] ?></p>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-sm">
                                                    <div class="form-group">
                                                        <label class="control-label">Adquiriente :	<span class="required">*</span></label>
                                                        <p class="text-dark"><?php echo $row['section_name'] ?></p>

                                                    </div>
                                                </div>
                                                
                                            </div>

                                         
                                          

                                            <button type="button" onclick="mostrarTicketPDF('<?php echo $row['boletaID']; ?>')">Mostrar Ticket PDF</button>

                                            <script type="text/javascript">
                                                function mostrarTicketPDF(boletaID) {
                                                    // Abre la URL del controlador y la función en una nueva ventana con el parámetro
                                                    window.open('<?php echo site_url("fees/ticket"); ?>/' + boletaID, '_blank');
                                                }
                                            </script>







                                           
                                        </div>









                                        <div class="modal-footer">
                                            <!-- Botones de pie de página del modal, si es necesario -->
                                        </div>
                                    </div>
                                </div>
                            </div>


							<?php  endforeach; ?>
						</tbody>
					</table>

                  

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





<script type="text/javascript">
	
    $(function() {
  $(".datepicker").datepicker({
  

    format: "yyyy-mm-dd",
        autoclose: true,
        orientation: "bottom",
        endDate: "today"
    // maxDate: "+1M"
  });
});



</script>
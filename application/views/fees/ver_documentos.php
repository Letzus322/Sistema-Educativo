
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
				
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?=translate('Fecha de inicio')?> <span class="required">*</span></label>
                            <input type="text" name="fecha_inicio" class="form-control datepicker" value="<?= $fecha_inicio ?>" required />
                        </div>


                
                    </div>

                    <div class="col-md-4 mb-sm">
                        <div class="form-group">
                            <label class="control-label"><?=translate('Fecha de fin')?> <span class="required">*</span></label>
                            <input type="text" name="fecha_fin" class="form-control datepicker" value="<?= $fecha_fin ?>" required />
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
							
								
								<th>Documento</th>
                                <th>Emisión</th>
								<th>Pago</th>
								<th>Adquiriente</th>
								<th>Total</th>
								<th>Registrado [ Fecha - Hora y Usuario]	</th>
								<th>Estado</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							foreach($invoicelist as $row):
								?>
                               
							<tr>
                                
								<td><?php echo $row['nro_documento'];?></td>
                                <td><?php echo $row['date'];?></td>
								<td><?php echo $row['name'];?></td>
								<td><?php echo $row['adquiriente'];?></td>
								<td><?php echo $row['total_amount'];?></td>
								<td><?php echo $row['secretaria'];?></td>
                                <td>
                                    <?php
                                        $estado = $row['estado'];
                                        
                                        if ($estado == 'Cancelado') {
                                            echo '<span style="color: green;">&#10004; Cancelado</span>'; // Check verde
                                        } elseif ($estado == 'Anulado') {
                                            echo '<span style="color: orange;">&#9679;Anulado</span> '; // Círculo naranja con raya
                                        } elseif ($estado == 'Eliminado') {
                                            echo '<span style="color: red;">&#10008; Eliminado</span>'; // X roja
                                        } else {
                                            echo $estado; // Otro estado
                                        }
                                    ?>
                                </td>
                                

                                            
                                <td>
                                
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="optionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-center text-center" style="right: 50%; transform: translateX(-50%);">

                                            <a class="dropdown-item" style="margin-left: 0px;" data-toggle="modal" data-target="#exampleModal<?= $row['idDocumento'] ?>">
                                                <button type="submit" name="Detalle" value="1" class="btn btn-default btn-block">
                                                    <i class="fas fa-info-circle text-info"></i> Detalle
                                                </button>
                                            </a>


                                            <?php $estado = $row['estado']; ?>
                                            <?php if ($estado == 'Cancelado'): ?>

                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" style="margin-left: 0px;">
                                                    <button type="submit" name="Caje" value="1" class="btn btn-default btn-block">
                                                        <i class="fas fa-exchange-alt text-success"></i> Canje Documento
                                                    </button>
                                                </a>


                                                <div class="dropdown-divider"></div>

                                                <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>

                                                    <input type="hidden" name="fecha_inicio" value="<?= $fecha_inicio ?>" />
                                                    <input type="hidden" name="fecha_fin" value="<?= $fecha_fin ?>" />
                                                    <input type="hidden" name="idBoleta" value="<?=  $row['boletaID'] ?>" />

                                                    <a class="dropdown-item" href="" style="margin-left: 0px;">
                                                        <button type="submit" name="anular" value="1" class="btn btn-default btn-block">
                                                            <i class="fas fa-times-circle text-danger"></i> Anular
                                                        </button>
                                                    </a>

                                                <?php echo form_close(); ?>

                                            <?php endif; ?>


                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" style="margin-left: 0px;">

                                                <button type="submit" name="Remover" value="1" class="btn btn-default btn-block">
                                                    <i class="fas fa-trash-alt text-gray"></i> Remover
                                                </button>
                                            </a>
                                        </div>





                                    </div>
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
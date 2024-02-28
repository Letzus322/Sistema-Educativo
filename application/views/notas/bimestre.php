<section class="panel">


		</ul>
        

		<div class="tab-content">
			
			
                <!-- Your form content goes here -->
                <form id="feeForm">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bimestre <span class="required">*</span></th>
                            <th>Unidad <span class="required">*</span></th>
                            <th>Fecha Inicio <span class="required">*</span></th>
                            <th>Fecha Fin <span class="required">*</span></th>
                            <th>Fecha Inicio Notas<span class="required">*</span></th>
                            <th>Fecha Fin Notas<span class="required">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                      
                            <?php foreach ($bimestres as $bimestre): ?>
                                <tr>
                                    <td><?php echo $bimestre['name']; ?></td>
                                    <td></td>

                                    <td>
                                        <input type="text" class="form-control datepicker" name="fecha_inicio" id="fecha_inicio_bimestre_<?php echo $bimestre['id_bimestre_fecha']; ?>" value="<?php echo $bimestre['fechaInicio']; ?>" required autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control datepicker" name="fecha_fin" id="fecha_fin_bimestre_<?php echo $bimestre['id_bimestre_fecha']; ?>" placeholder="Ingrese Fecha Fin" value="<?php echo $bimestre['fechaFin']; ?>" required autocomplete="off" />  
                                    </td>


                                    <td>
                                        <input type="text" class="form-control datepicker" name="fecha_inicio_notas" id="fecha_inicio_bimestre_notas<?php echo $bimestre['id_bimestre_fecha']; ?>" value="<?php echo $bimestre['fechaInicioNotas']; ?>" required autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control datepicker" name="fecha_fin_notas" id="fecha_fin_bimestre_notas<?php echo $bimestre['id_bimestre_fecha']; ?>" placeholder="Ingrese Fecha Fin" value="<?php echo $bimestre['fechaFinNotas']; ?>" required autocomplete="off" />  
                                    </td>



                                    <?php  

                                    
                                    $this->db->select('unidad.*,periodo_unidad.id as id_unidad_fecha ,periodo_unidad.fecha_inicio as fechaInicio, periodo_unidad.fecha_fin as fechaFin')->from('bimestre_unidad');
                                    $this->db->join('unidad', 'unidad.id = bimestre_unidad.id_unidad');
                                    $this->db->join('periodo_unidad', 'periodo_unidad.id_unidad = unidad.id');
                                    $this->db->where('periodo_unidad.id_session',get_session_id() );
                                    $this->db->where('id_bimestre',$bimestre['id'] );

                                    $query = $this->db->get();
                                    $result = $query->result_array();
                                    ?>

                                        <?php foreach ($result as $row): ?>
                                            <tr>

                                                <td> </td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td>
                                                <input type="text" class="form-control datepicker" name="fecha_inicio_unidad_" id="fecha_inicio_unidad_<?php echo $row['id_unidad_fecha']; ?>" value="<?php echo $row['fechaInicio']; ?>" required autocomplete="off" />
                                                </td>

                                                <td >
                                                <input type="text" class="form-control datepicker" name="fecha_fin_unidad_" id="fecha_fin_unidad_<?php echo $row['id_unidad_fecha']; ?>"  value="<?php echo $row['fechaFin']; ?>"  required autocomplete="off" />  



                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        


                                </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>


                <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="guardarFechas" data-toggle="guardarFechas" >
                        <i class="fas fa-coins fa-fw"></i> Guardar Fechas
                    </button>
                </form>

                


		</div>
	</div>


    
</section>

<script>
    $(document).ready(function () {
        $("#guardarFechas").on("click", function () {
            // Definir el objeto postData con la información que deseas enviar
            var postData = {
                bimestres: [],
                unidades: []
            };
            
     
            <?php foreach ($bimestres as $bimestre): ?>
                var bimestreData = {
                    id: <?php echo $bimestre['id_bimestre_fecha']; ?>,
                    fechaInicio: $("#fecha_inicio_bimestre_<?php echo $bimestre['id_bimestre_fecha']; ?>").val(),
                    fechaFin: $("#fecha_fin_bimestre_<?php echo $bimestre['id_bimestre_fecha']; ?>").val(),
                    fechaInicioNotas: $("#fecha_inicio_bimestre_notas<?php echo $bimestre['id_bimestre_fecha']; ?>").val(),
                    fechaFinNotas: $("#fecha_fin_bimestre_notas<?php echo $bimestre['id_bimestre_fecha']; ?>").val()
                };
                postData.bimestres.push(bimestreData);
            <?php endforeach; ?>

            <?php 
            $this->db->select('unidad.*,periodo_unidad.id as id_unidad_fecha ,periodo_unidad.fecha_inicio as fechaInicio, periodo_unidad.fecha_fin as fechaFin')->from('bimestre_unidad');
            $this->db->join('unidad', 'unidad.id = bimestre_unidad.id_unidad');
            $this->db->join('periodo_unidad', 'periodo_unidad.id_unidad = unidad.id');
            $this->db->where('periodo_unidad.id_session',get_session_id() );

            $query = $this->db->get();
            $result2 = $query->result_array(); ?>


            <?php foreach ($result2 as $row): ?>
                var unidadData = {
                    id: <?php echo $row['id_unidad_fecha']; ?>,
                    fechaInicio: $("#fecha_inicio_unidad_<?php echo $row['id_unidad_fecha']; ?>").val(),
                    fechaFin: $("#fecha_fin_unidad_<?php echo $row['id_unidad_fecha']; ?>").val()
                };
                postData.unidades.push(unidadData);
            <?php endforeach; ?>


            $.ajax({
                type: "POST",
                url: base_url + 'Notas/actualizar_fechas',
                data: postData,
                success: function (response) {
                    console.log(response);
                    
                    alert("Se guardó correctamente");
                    location.reload();

                },
                error: function (error) {
                    alert("Error");
                    console.error("Error en la solicitud AJAX:", error);
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
    // maxDate: "+1M"
  });
});



</script>
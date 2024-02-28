<style>

.table-responsive {
    overflow: visible; /* o auto, según tus necesidades */
}


</style>


<section class="panel">
	<div class="tabs-custom">

		<div class="tab-content">
			<div class="tab-pane active">



            
				<div class="row">

                        <?php $subject_assign_id = $curso_asignado['id_subject_assign'];
                        $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                        $query = $this->db->get_where('subject_assign_competencias', array(
                            'subject_assign_id' => $subject_assign_id,
                            'bimestre' => $bimestre
                        ));

                        $result = $query->result_array();
                        $numResultados = count($result);

                        ?>



						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title">Periodo:</h4>
                                <h4 class="panel-title">Curso: <?php echo $curso_asignado['curso'];?></h4>

                                <h4 class="panel-title">Ingreso de Notas: Numeros</h4>


							</header>

                        <div id="contenedorTablas" >

                            <div id="" class="table-responsive">

                                <table class="table responsive-table">
                                    <tbody id="tbodyFinal">
                                        <tr class="no-border">
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-fw fa-ban"></i>
                                                    </span>
                                                    <input type="text" class="form-control font-weight-bold" value="PROMEDIO FINAL" disabled="">
                                                </div>
                                            </td>

                                            <td style="display: none;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-fw fa-ban"></i>
                                                    </span>
                                                    <input type="text" class="form-control font-weight-bold" value="0" id="cantidadCompetencias" disabled="">
                                                </div>
                                            </td>

                                            <td>
                                                <div style="width: 13rem;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Notas<br>obligatorias
                                                        </span>
                                                        <div class="form-control">
                                                            <input type="checkbox" id="chkNotaObligatoriaEvaluacion" onclick="attrChecked(this)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="width: 13rem;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Notas<br>válidas
                                                        </span>
                                                        <input type="text" value=" <?php echo $numResultados;?>" id="CantNotasPromediar" class="form-control" onkeypress="return validateNumerics(event)">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="width: 15rem;">
                                                    <div class="input-group hide">
                                                        <span data-format="alias" class="input-group colorpicker-component btn-block color_pickerMe colorpicker-element">
                                                            <span class="input-group-addon">
                                                                <i class="notaColorContentIcon"></i>
                                                            </span>
                                                            <input disabled="" class="form-control">
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="width: 12rem;">
                                                    <div class="input-group hide">
                                                        <span class="input-group-addon">PESO</span>
                                                        <input type="text" class="form-control text-right" value="1" disabled="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-inline-block dropdown">
                                                    <button type="button" class="btn btn-default" data-toggle="dropdown">
                                                        <i class="fa fa-fw fa-navicon"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="agregarCompetencia(document.getElementById('cantidadCompetencias').value)">

                                                                <i class="fa fa-fw fa-plus text-primary"></i> Agregar Competencia
                                                            </a>
                                                        </li>
                                                    
                                                    </ul>
                                                </div>
                                            </td>
                                        
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                           
                            <?php 
                            $count = 1; // Inicializa la variable de conteo

                            foreach ($result as $row): ?>

                            <div class="table-responsive">
                                <table class="table ">
                                

                                    <thead>
                                        <tr style="background-color: #4CAF50;">
                                            <th colspan="6" style="text-align: center; background-color: green; color: white;">Competencias</th>

                                        </tr>
                                    </thead>

                                    <tbody id="tbodyFinal">
                                        <tr class="no-border">
                                            <td class="align-middle">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-fw fa-arrows-alt"></i>
                                                    </span>
                                                    <input type="text" class="form-control font-weight-bold" value="<?= $row['name'] ?>" id="name2-<?= $count ?>">

                                                </div>
                                            </td>

                                            <td class="align-middle" style="display: none;">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-arrows-alt"></i>
                                                </span>
                                                <input type="text" class="form-control font-weight-bold" value="<?= $row['id'] ?>" id="idActualizar-<?= $count ?>">
                                            </div>
                                            </td>


                                            
                                            
                                            <td class="align-middle">
                                                <div style="width: 13rem;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Notas<br>obligatorias
                                                        </span>
                                                        <div class="form-control">
                                                            <input type="checkbox" id="chkNotaObligatoriaEvaluacion" onclick="attrChecked(this)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div style="width: 13rem;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Notas<br>válidas
                                                        </span>
                                                        <input type="text" value="0" id="CantNotasPromediar"  class="form-control" onkeypress="return validateNumerics(event)">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div style="width: 15rem;">
                                                    <div class="input-group colorpicker-component">
                                                        <span class="input-group-addon">
                                                            <i class="notaColorContentIcon"></i>
                                                        </span>
                                                        
                                                        <input  type="color" class="form-control" value="<?= $row['color'] ?>"  id="color2-<?=$count ?>">
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <div style="width: 12rem;">
                                                    <div class="input-group ">
                                                        <span class="input-group-addon">PESO</span>
                                                        <input type="text" class="form-control text-right" value="<?= $row['peso'] ?>" id="peso2-<?= $count ?>">
                                                    </div>
                                                </div>
                                            </td>

                                        

                                            <td class="align-middle">
                                                <div class="d-inline-block dropdown">
                                                    <button type="button" class="btn btn-default" data-toggle="dropdown">
                                                        <i class="fa fa-fw fa-navicon"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>

                                                            <a class="dropdown-item" href="<?php echo base_url('notas/evaluacion_curso_capacidad_crear/'.$row['id']);?>"     onclick="">
                                                                <i class="fa fa-fw fa-plus text-primary"></i> Agregar Capacidad
                                                            </a>
                                                        </li>
                                                        
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php $count++;?>
                            <?php endforeach; ?>

                        </div>









                        <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="guardarCambios" data-toggle="guardarFechas" >
                            <i class="fas fa-coins fa-fw"></i> Guardar cambios
                        </button>
                        <?php if($bimestre ==1):?>                        
                        
                        <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="copiarDatos" data-toggle="guardarFechas" >
                            <i class="fas fa-coins fa-fw"></i> Copiar para los demás bimestres
                        </button>
                        <?php endif; ?>

                
						</section>
				</div>
			</div>
		</div>

	</div>
</section>


<script>



function agregarCompetencia(competenciaID) {
    
    var nuevaCantidad = parseInt(document.getElementById('cantidadCompetencias').value) + 1;

    document.getElementById('cantidadCompetencias').value = nuevaCantidad;  
    
    
    var nuevaCantidad2 = parseInt(document.getElementById('CantNotasPromediar').value) + 1;
    document.getElementById('CantNotasPromediar').value = nuevaCantidad2;  

    
    // Crear la nueva tabla con el contenido deseado
    var nuevaTabla = `

    <div class="table-responsive">
                            <table class="table id="competencia-${competenciaID}">
                             

                                <thead>
                                    <tr style="background-color: #4CAF50;">
                                        <th colspan="6" style="text-align: center; background-color: green; color: white;">Competencias</th>

                                    </tr>
                                </thead>

                                <tbody id="tbodyFinal">
                                    <tr class="no-border">
                                        <td class="align-middle">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-arrows-alt"></i>
                                                </span>
                                                <input type="text" class="form-control font-weight-bold" value="GRUPO DE PROMEDIOS ${nuevaCantidad}" id="name-${nuevaCantidad}">

                                            </div>
                                        </td>



                                        
                                        <td class="align-middle">
                                            <div style="width: 13rem;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        Notas<br>obligatorias
                                                    </span>
                                                    <div class="form-control">
                                                        <input type="checkbox" id="chkNotaObligatoriaEvaluacion" onclick="attrChecked(this)">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div style="width: 13rem;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        Notas<br>válidas
                                                    </span>
                                                    <input type="text" value="0" id="CantNotasPromediar"  class="form-control" onkeypress="return validateNumerics(event)">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div style="width: 15rem;">
                                                <div class="input-group colorpicker-component">
                                                    <span class="input-group-addon">
                                                        <i class="notaColorContentIcon"></i>
                                                    </span>
                                                    <input  type="color" class="form-control" id="color-${nuevaCantidad}"  value="#ffffff">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div style="width: 12rem;">
                                                <div class="input-group ">
                                                    <span class="input-group-addon">PESO</span>
                                                    <input type="text" class="form-control text-right" id="peso-${nuevaCantidad}" value="1">
                                                </div>
                                            </div>
                                        </td>

                                       

                                        <td class="align-middle">
                                            <div class="d-inline-block dropdown">
                                                <button type="button" class="btn btn-default" data-toggle="dropdown">
                                                    <i class="fa fa-fw fa-navicon"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>

                                                     
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        

    `;
  

    // Agregar la nueva tabla al contenedor
    $("#contenedorTablas").append(nuevaTabla);
}

$(document).ready(function () {
        $("#guardarCambios").on("click", function () {
            // Definir el objeto postData con la información que deseas enviar
            var postData = {
                competencias: [],
                compatenciasActualizar:[],  

            };
            
            <?php 

            for ($i = 1; $i <= 10; $i++): ?>
                
                var competenciasData = {
                    name: $("#name-" + <?php echo $i; ?>).val(),
                    color: $("#color-" + <?php echo $i; ?>).val(),
                    peso: $("#peso-" + <?php echo $i; ?>).val(),
                    subject_assign_id:  <?php echo $curso_asignado['id_subject_assign'];?>,
                    bimestre:  <?php echo $bimestre;?>,

                };


                postData.competencias.push(competenciasData);
            <?php endfor; ?>
            <?php 

            for ($i = 1; $i < $count; $i++): ?>
                
                var competenciasData = {
                    name: $("#name2-" + <?php echo $i; ?>).val(),
                    color: $("#color2-" + <?php echo $i; ?>).val(),
                    peso: $("#peso2-" + <?php echo $i; ?>).val(),
                    id: $("#idActualizar-" + <?php echo $i; ?>).val(),


                };


                postData.compatenciasActualizar.push(competenciasData);
            <?php endfor; ?>
                    

            $.ajax({
                type: "POST",
                url: base_url + 'Notas/evaluacion_curso_competencias_agregar',
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



    $(document).ready(function () {
        $("#copiarDatos").on("click", function () {
            // Definir el objeto postData con la información que deseas enviar
            var postData = {
                curso: <?php echo $curso_asignado['id']; ?>,

            };
            
            
                    

            $.ajax({
                type: "POST",
                url: base_url + 'Notas/copiar_entre_bimestres',
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



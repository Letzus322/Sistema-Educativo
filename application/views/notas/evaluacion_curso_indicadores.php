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

                        <?php $capacidad_id = $capacidad['id'];

                        $query = $this->db->get_where('subject_assign_indicadores', array('capacidad_assign_id' => $capacidad_id));

                        $result = $query->result_array();
                        $numResultados = count($result);

                        ?>



						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title">Periodo:</h4>
                                <h4 class="panel-title">Capacidad: <?php echo $capacidad['name'];?></h4>

                                <h4 class="panel-title">Ingreso de Notas: Numeros</h4>


							</header>

                        <div id="contenedorTablas" >

                            <div id="" class="table-responsive">

                                <table class="table responsive-table">

                                    <thead>
                                        <tr>
                                            <th colspan="6" style="text-align: center; background-color: blue; color: white;">Capacidad</th>
                                        </tr>
                                    </thead>

                                    <tbody id="tbodyFinal">
                                        <tr class="no-border">
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-fw fa-ban"></i>
                                                    </span>
                                                    <input type="text" class="form-control font-weight-bold" value="<?= $capacidad['name'] ?>" disabled="">
                                                </div>
                                            </td>

                                            <td style="display: none;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-fw fa-ban"></i>
                                                    </span>
                                                    <input type="text" class="form-control font-weight-bold" value="0" id="cantidadIndicadores" disabled="">
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
                                                            <a class="dropdown-item" href="#" onclick="agregarCapacidad(document.getElementById('cantidadIndicadores').value)">

                                                                <i class="fa fa-fw fa-plus text-primary"></i> Agregar Indicador
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
                            $count = 1; 

                            foreach ($result as $row): ?>

                            <div class="table-responsive">
                                <table class="table ">
                                

                                    <thead>
                                        <tr>

                                            <th colspan="6" style="text-align: center; background-color: gray; color: white;">Indicadores</th>
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
                                                        <input  type="color" class="form-control" id="color2-<?=$count ?>"  value='<?= $row['color'] ?>'>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <div style="width: 12rem;">
                                                    <div class="input-group ">
                                                        <span class="input-group-addon">PESO</span>
                                                        <input type="text" class="form-control text-right" id="peso2-<?= $count ?>" value="<?= $row['peso'] ?>">
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

                                                            <a class="dropdown-item" href=""     onclick="">
                                                                <i class="fa fa-fw fa-plus text-primary"></i> Eliminar
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


                
						</section>
				</div>
			</div>
		</div>

	</div>
</section>


<script>



function agregarCapacidad(competenciaID) {
    
    var nuevaCantidad = parseInt(document.getElementById('cantidadIndicadores').value) + 1;

    document.getElementById('cantidadIndicadores').value = nuevaCantidad;  
    
    
    var nuevaCantidad2 = parseInt(document.getElementById('CantNotasPromediar').value) + 1;
    document.getElementById('CantNotasPromediar').value = nuevaCantidad2;  

    
    // Crear la nueva tabla con el contenido deseado
    var nuevaTabla = `

    <div class="table-responsive">
                            <table class="table id="competencia-${competenciaID}">
                             

                                <thead>
                                    <tr>

                                        <th colspan="6" style="text-align: center; background-color: gray; color: white;">Indicadores</th>
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

                                                        <a class="dropdown-item" href="#" >
                                                            <i class="fa fa-fw fa-plus text-primary"></i> Eliminar
                                                        </a>
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



</script>

<script>


$(document).ready(function () {
        $("#guardarCambios").on("click", function () {
            // Definir el objeto postData con la información que deseas enviar
            var postData = {
                indicadores: [],
                indicadoresActualizar:[],  

            };
            
            <?php 

            for ($i = 1; $i <= 10; $i++): ?>
                
                var indicadoresData = {
                    name: $("#name-" + <?php echo $i; ?>).val(),
                    color: $("#color-" + <?php echo $i; ?>).val(),
                    peso: $("#peso-" + <?php echo $i; ?>).val(),
                    subject_assign_id:  <?php echo $capacidad['id'];?>,
                };


                postData.indicadores.push(indicadoresData);
            <?php endfor; ?>

          
            <?php 

            for ($i = 1; $i < $count; $i++): ?>
                
                var capacidadesData = {
                    name: $("#name2-" + <?php echo $i; ?>).val(),
                    color: $("#color2-" + <?php echo $i; ?>).val(),
                    peso: $("#peso2-" + <?php echo $i; ?>).val(),
                    id: $("#idActualizar-" + <?php echo $i; ?>).val(),


                };


                postData.indicadoresActualizar.push(capacidadesData);
            <?php endfor; ?>



            $.ajax({
                type: "POST",
                url: base_url + 'Notas/evaluacion_curso_indicadores_agregar',
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
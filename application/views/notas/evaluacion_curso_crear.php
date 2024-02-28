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

					



						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title">Periodo:</h4>
                                <h4 class="panel-title">Curso: <?php echo $curso_asignado['curso'];?></h4>

                                <h4 class="panel-title">Ingreso de Notas: Numeros</h4>


							</header>

                           
                        <div id="contenedorTablas" class="table-responsive">

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
                                                <input type="text" class="form-control font-weight-bold" value="1" id="cantidadCompetencias" disabled="">
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
                                                    <input type="text" value="0" id="CantNotasPromediar" class="form-control" onkeypress="return validateNumerics(event)">
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

                  
						</section>
				</div>
			</div>
		</div>
	</div>
</section>


<script>
function agregarIndicador(competenciaID, capacidadID,indicadorID) {
    

    var nuevaCantidad = parseInt(document.getElementById('cantidadCapacidades'+'-'+competenciaID).value) + 1;
    document.getElementById('cantidadCapacidades'+'-'+competenciaID).value = nuevaCantidad;
    
    // Crear la nueva tabla con el contenido deseado
    var nuevaTabla = `
    
                      
                        <div class="table-responsive">
                            <table class="table"  id="indicador-${competenciaID}-${capacidadID}-${indicadorID}">
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
                                                <input id="descripcionInput" type="text" class="form-control font-weight-bold" placeholder="Descripción">
                                            </div>
                                        </td>
                                        
                                        <td class="align-middle">
                                            <div style="width: 15rem;">
                                                <div class="input-group colorpicker-component">
                                                    <span class="input-group-addon">
                                                        <i class="notaColorContentIcon"></i>
                                                    </span>
                                                    <input id="colorInput" type="text" class="form-control" value="#ffffff">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div style="width: 12rem;">
                                                <div class="input-group ">
                                                    <span class="input-group-addon">PESO</span>
                                                    <input type="text" class="form-control text-right" value="1">
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
                                                        <a class="dropdown-item" href="#" onclick="GrupoAdd()">
                                                            <i class="fa fa-fw fa-plus text-primary"></i> Agregar Competencia
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="openContenedores()">
                                                            <i class="fa fa-fw fa-plus text-primary"></i> Agregar Contenedor
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
    $("#indicador-"+competenciaID+"-"+capacidadID+"-"+indicadorID).append(nuevaTabla);
}




function agregarCapacidad(competenciaID, capacidadID) {
    

    var nuevaCantidad = parseInt(document.getElementById('cantidadCapacidades'+'-'+competenciaID).value) + 1;
    document.getElementById('cantidadCapacidades'+'-'+competenciaID).value = nuevaCantidad;
    


    $(`#cantidadCapacidades-${competenciaID}`).val(capacidadID);

    var indicadorID =1;

    $(`#cantidadIndicadores-${competenciaID}-${capacidadID}`).val(indicadorID);

    // Crear la nueva tabla con el contenido deseado
    var nuevaTabla = `
    
                        <div class="table-responsive">
                            <table class="table" id="capacidad-${competenciaID}-${capacidadID}">
                                <thead>
                                    <tr>
                                    <th colspan="6" style="text-align: center; background-color: blue; color: white;">Capacidades</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyFinal">
                                    <tr class="no-border">
                                        <td class="align-middle">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-arrows-alt"></i>
                                                </span>
                                                <input type="text" class="form-control font-weight-bold" value="PROMEDIO  ${capacidadID}">
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
                                                    <input type="text" value="0" id="CantNotasPromediar" class="form-control" onkeypress="return validateNumerics(event)">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div style="width: 15rem;">
                                                <div class="input-group colorpicker-component">
                                                    <span class="input-group-addon">
                                                        <i class="notaColorContentIcon"></i>
                                                    </span>
                                                    <input id="colorInput" type="text" class="form-control" value="#ffffff">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div style="width: 12rem;">
                                                <div class="input-group ">
                                                    <span class="input-group-addon">PESO</span>
                                                    <input type="text" class="form-control text-right" value="1">
                                                </div>
                                            </div>
                                        </td>


                                       






                                        <td style="display: none;">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-ban"></i>
                                                </span>
                                                <input type="text" class="form-control font-weight-bold" value="1" id="cantidadIndicadores-${competenciaID}-${capacidadID}" disabled="">
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="d-inline-block dropdown">
                                                <button type="button" class="btn btn-default" data-toggle="dropdown">
                                                    <i class="fa fa-fw fa-navicon"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="agregarIndicador(${competenciaID},${capacidadID},${indicadorID})">
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

                        <div class="table-responsive">
                            <table class="table"  id="indicador-${competenciaID}-${capacidadID}-${indicadorID}">
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
                                                <input id="descripcionInput" type="text" class="form-control font-weight-bold" placeholder="Descripción">
                                            </div>
                                        </td>
                                        
                                        <td class="align-middle">
                                            <div style="width: 15rem;">
                                                <div class="input-group colorpicker-component">
                                                    <span class="input-group-addon">
                                                        <i class="notaColorContentIcon"></i>
                                                    </span>
                                                    <input id="colorInput" type="text" class="form-control" value="#ffffff">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div style="width: 12rem;">
                                                <div class="input-group ">
                                                    <span class="input-group-addon">PESO</span>
                                                    <input type="text" class="form-control text-right" value="1">
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
                                                        <a class="dropdown-item" href="#" onclick="GrupoAdd()">
                                                            <i class="fa fa-fw fa-plus text-primary"></i> Agregar Competencia
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="openContenedores()">
                                                            <i class="fa fa-fw fa-plus text-primary"></i> Agregar Contenedor
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
    $("#capacidad-"+competenciaID+"-"+capacidadID).append(nuevaTabla);
}


function agregarCompetencia(competenciaID) {
    var capacidadID = 1;

    $(`#cantidadCapacidades-${competenciaID}`).val(capacidadID);

    var indicadorID =1;

    $(`#cantidadIndicadores-${competenciaID}-${capacidadID}`).val(indicadorID);

    
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
                                                <input type="text" class="form-control font-weight-bold" value="GRUPO DE PROMEDIOS ${competenciaID}" >
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
                                                    <input type="text" value="0" id="CantNotasPromediar" class="form-control" onkeypress="return validateNumerics(event)">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div style="width: 15rem;">
                                                <div class="input-group colorpicker-component">
                                                    <span class="input-group-addon">
                                                        <i class="notaColorContentIcon"></i>
                                                    </span>
                                                    <input id="colorInput" type="text" class="form-control" value="#ffffff">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div style="width: 12rem;">
                                                <div class="input-group ">
                                                    <span class="input-group-addon">PESO</span>
                                                    <input type="text" class="form-control text-right" value="1">
                                                </div>
                                            </div>
                                        </td>

                                        <td style="display: none;">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-ban"></i>
                                                </span>
                                                <input type="text" class="form-control font-weight-bold" value="1" id="cantidadCapacidades-${competenciaID}" disabled="">
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="d-inline-block dropdown">
                                                <button type="button" class="btn btn-default" data-toggle="dropdown">
                                                    <i class="fa fa-fw fa-navicon"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>

                                                        <a class="dropdown-item" href="#" onclick="agregarCapacidad(${competenciaID},document.getElementById('cantidadCapacidades-${competenciaID}').value)">
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

                        <div class="table-responsive" id ="capacidad-${competenciaID}-${capacidadID}">
                            <table class="table" id="">
                                <thead>
                                    <tr>
                                        <th colspan="6" style="text-align: center; background-color: blue; color: white;">Capacidades</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyFinal">
                                    <tr class="no-border">
                                        <td class="align-middle">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-arrows-alt"></i>
                                                </span>
                                                <input type="text" class="form-control font-weight-bold" value="PROMEDIO ${capacidadID}">
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
                                                    <input type="text" value="0" id="CantNotasPromediar" class="form-control" onkeypress="return validateNumerics(event)">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div style="width: 15rem;">
                                                <div class="input-group colorpicker-component">
                                                    <span class="input-group-addon">
                                                        <i class="notaColorContentIcon"></i>
                                                    </span>
                                                    <input id="colorInput" type="text" class="form-control" value="#ffffff">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div style="width: 12rem;">
                                                <div class="input-group ">
                                                    <span class="input-group-addon">PESO</span>
                                                    <input type="text" class="form-control text-right" value="1">
                                                </div>
                                            </div>
                                        </td>

                                        <td style="display: none;">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-ban"></i>
                                                </span>
                                                <input type="text" class="form-control font-weight-bold" value="1" id="cantidadIndicadores-${competenciaID}-${capacidadID}" disabled="">
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="d-inline-block dropdown">
                                                <button type="button" class="btn btn-default" data-toggle="dropdown">
                                                    <i class="fa fa-fw fa-navicon"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="agregarIndicador(${competenciaID},${capacidadID},${indicadorID})">
                                                            <i class="fa fa-fw fa-plus text-primary"></i> Agregar Indicador
                                                        </a>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="table-responsive"  id="indicador-${competenciaID}-${capacidadID}-${indicadorID}">
                                <table class="table" >
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
                                                    <input id="descripcionInput" type="text" class="form-control font-weight-bold" placeholder="Descripción">
                                                </div>
                                            </td>
                                            
                                            <td class="align-middle">
                                                <div style="width: 15rem;">
                                                    <div class="input-group colorpicker-component">
                                                        <span class="input-group-addon">
                                                            <i class="notaColorContentIcon"></i>
                                                        </span>
                                                        <input id="colorInput" type="text" class="form-control" value="#ffffff">
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <div style="width: 12rem;">
                                                    <div class="input-group ">
                                                        <span class="input-group-addon">PESO</span>
                                                        <input type="text" class="form-control text-right" value="1">
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
                                                            <a class="dropdown-item" href="#" onclick="openContenedores()">
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

                        </div>

                        
                        

    `;
    var nuevaCantidad = parseInt(document.getElementById('cantidadCompetencias').value) + 1;

    document.getElementById('cantidadCompetencias').value = nuevaCantidad;

    // Agregar la nueva tabla al contenedor
    $("#contenedorTablas").append(nuevaTabla);
}
</script>



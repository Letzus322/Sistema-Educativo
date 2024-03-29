<style>

.modal-body {
    max-height: 400px; /* ajusta la altura máxima según sea necesario */
    overflow-y: auto;
}
</style>

<section class="panel">


		</ul>
        

		<div class="tab-content">
			
			
                <!-- Your form content goes here -->
                <form id="feeForm">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sede <span class="required"></span></th>
                                <th>Tipo Documento <span class="required">*</span></th>
                                <th>Número de Documento <span class="required">*</span></th>

                                <!-- Other table headers here -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                               
                                <td class="fee-modal">
                                    <?php
                                    $arrayBranch = $this->app_lib->getSelectList('branch');
                                    echo form_dropdown(
                                        "branch_id",
                                        $arrayBranch,
                                        set_value('branch_id', $branchID),
                                        "id='branch_id' class='form-control' onchange='updateDocumentNumbers(this.value, document.getElementById(\"pay_document\").value)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'"
                                    
                                    );
                                    ?>
                                </td>

                                <td class="fee-modal">
                                    <div class="form-group">
                                        <?php
                                        $payvia_list = array(
                                            '1' => 'Boleta Electrónica',
                                            '2' => 'Recibo'
                                        );

                                        echo form_dropdown(
                                            'pay_document',
                                            $payvia_list,
                                            set_value('pay_document',$pay_documentID),
                                            "id='pay_document' class='form-control selectTwo' data-width='100%' data-minimum-results-for-search='Infinity' onchange='updateDocumentNumbers(document.getElementById(\"branch_id\").value, this.value)'"

                                       
                                        );
                                        ?>
                                        <span class="error"></span>
                                    </div>
                                </td>


                                <td class="fee-modal">
                                    <div class="form-group">
                                    <input type="text" class="form-control" name="numeroDocumento" id="numeroDocumento" value='<?php echo $numeroDocumentoID; ?>' autocomplete="off" />
                                        <span class="error"></span>
                                    </div>
                                </td>

                            </tr>

                            <tr>
                                <th>Fecha de emisión<span class="required"></span></th>
                                <th>Medio de pago <span class="required">*</span></th>
                                <th>Cuenta Bancaria <span class="required">*</span></th>

                            </tr>

                            <tr>
                              
                            <td class="fee-modal">
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" name="date" id="date" value="<?=date('Y-m-d')?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </td>



                            <td class="fee-modal">
                                <div class="form-group">
                                    <?php
                                        $payvia_list = $this->app_lib->getSelectList('payment_types');
                                        echo form_dropdown("pay_via", $payvia_list, 1, "class='form-control selectTwo' data-width='100%'
                                        data-minimum-results-for-search='Infinity' ");
                                    ?>
                                    <span class="error"></span>
                                </div>
                            </td>
                                
                            <td class="fee-modal">
                                <div class="form-group">
                                    <?php
                                    $accounts_list = $this->app_lib->getSelectByBranch2('accounts', 1);

                                    echo form_dropdown("account_id", $accounts_list, $links['deposit'], "class='form-control selectTwo' data-width='100%'");
                                    ?>
                                    <span class="error"></span>
                                </div>
                            </td>



                            </tr>

                            <tr>
                                <th>Fecha de Pago<span class="required"></span></th>
                                <th>Nro. de operación<span class="required">*</span></th>
                                <th>Adquiriente<span class="required">*</span></th>


                            </tr>

                            

                            <tr>


                                <td class="fee-modal">
                                    <div class="form-group">
                                        <input type="text" class="form-control datepicker" id="datePay" name="datePay" placeholder="Ingrese Fecha" required autocomplete="off" />
                                        <span class="error"></span>
                                    </div>
                                </td>



                                <td class="fee-modal">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="remarks" name="remarks" value='' autocomplete="off" id="remarks<?php echo $key; ?>" required />
                                        <span class="error"></span>
                                    </div>
                                </td>
                                

                                

                                <td class="fee-modal">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="adquiriente" name="adquiriente" value='' autocomplete="off" id="adquiriente" required />
                                        <span class="error"></span>
                                    </div>
                                </td>

                                
                            </tr>




                            <tr>
                                <th>DNI adquiriente<span class="required"></span></th>
                              


                            </tr>

                            

                            <tr>


                                <td class="fee-modal">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="dni_adquiriente" name="dni_adquiriente" value='' autocomplete="off" id="dni_adquiriente" required />
                                        <span class="error"></span>
                                    </div>
                                </td>

                                
                            </tr>



                            <tr>
                                <td class="fee-modal">

                                    <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="agregarPagoPer" data-toggle="agregarPagoPer" data-target="#ModalAgregarPagoPer">
                                        <i class="fas fa-coins fa-fw"></i> Agregar Pago Personalizado
                                    </button>
                                </td>

                              

                            </tr>

                        </tbody>
                    </table>
                </form>

                <div class="form-group">
                        <div class="form-group">
                            <?php
                                $arrayParent = $this->app_lib->getSelectListStudent('student');
                                echo form_dropdown("student_id", $arrayParent, set_value('student_id'), "class='form-control' id='student_id' data-plugin-selectTwo data-width='100%' ' ");
                            ?>
                            <span class="error"><?=form_error('student_id')?></span>
                        </div>
                </div>
                

                <br>              
                                        
                <table class="table" id ="tablaEstudiantes">
                                        
                        <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Descripción <span class="required"></span></th>
                                <th>Deuda <span class="required">*</span></th>
                                <th>Vencimiento <span class="required">*</span></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $row): ?>
                                <tr>
                                    
                                    <td>
                                        <input type="checkbox" class="seleccionar-fila" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td><?php echo $row['descripcion']; ?></td>
                                    <td><?php echo $row['net_amount']; ?></td>
                                    <td><?php echo $row['due_date']; ?></td>

                                    <!-- Agrega más columnas según sea necesario -->
                                </tr>
                            <?php endforeach; ?>

                               



                        </tbody>
                </table>


                <td class="fee-modal">
                    <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="agregarFilas" data-toggle="agregarPagoPer" data-target="#ModalAgregarPagoPer">
                        <i class="fas fa-coins fa-fw"></i> Agregar Seleccionados
                    </button>
                </td>

            
                <table class="table" id="tablaSegunda">
                        <thead>
                            <tr>
                                <th>Alumno <span class="required"></span></th>
                                <th>Descripción <span class="required">*</span></th>
                                <th>Cantidad <span class="required">*</span></th>
                                <th>P. Unitario <span class="required">*</span></th>
                                <th>P. Total  <span class="required">*</span></th>

                                <!-- Other table headers here -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                             
                                
                               


                               
                            </tr>



                        </tbody>
                </table>

               
             


                <div>
                    <span>Total</span>
                    <span id="montoTotal" data-value="0">
                        <span class="required">*</span>
                        <script>
                            // La siguiente línea de código se puede eliminar ya que no es necesaria
                            // var valor = document.getElementById("montoTotal").getAttribute("data-value");
                            // document.write(valor);
                        </script>
                    </span>
                </div>


                <td class="fee-modal">
                    <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="generarVenta" data-toggle="guardarVenta" >
                        <i class="fas fa-coins fa-fw"></i> Guardar Venta
                    </button>
                </td>


		</div>
	</div>


    
</section>

<script>
    $(document).ready(function () {
        $("#generarVenta").on("click", function () {
            // Validar que los campos requeridos estén llenos
            if (
                $("#branch_id").val() === "" ||
                $("#pay_document").val() === "" ||
                $("#numeroDocumento").val() === "" ||
                $("#date").val() === "" ||
                $("select[name='pay_via']").val() === "" ||
                $("select[name='account_id']").val() === "" ||
                $("#datePay").val() === "" ||
                $("#remarks").val() === ""||
                $("#adquiriente").val() === ""||
                $("#dni_adquiriente").val() === ""
            ) {
                alert("Todos los campos son obligatorios. Por favor, llénelos.");
                return;
            }

            if ($("#tablaSegunda tbody tr").length === 1) {
            alert("La boleta está vacía.");
            return;
        }

            // Obtener los valores del formulario
            var formData = {
                branch_id: $("#branch_id").val(),
                pay_document: $("#pay_document").val(),
                numeroDocumento: $("#numeroDocumento").val(),
                date: $("#date").val(),
                pay_via: $("select[name='pay_via']").val(),
                account_id: $("select[name='account_id']").val(),
                datePay: $("#datePay").val(),
                remarks: $("#remarks").val(),
                adquiriente: $("#adquiriente").val(),
                dni_adquiriente: $("#dni_adquiriente").val() 

                
            };

            // Obtener los datos de las filas de la tabla tablaSegunda
            var tableData = [];
            $("#tablaSegunda tbody tr").each(function () {
                var rowData = {
                    cantidad: $(this).find("td:nth-child(3) input").val(),
                    amountPersonalizado: parseFloat($(this).find("td:nth-child(4) input").val()),    
                    feeType: $(this).find("td:nth-child(7)").text(),
                    allocationID: $(this).find("td:nth-child(6)").text(),
                    amount: $(this).find("td:nth-child(8)").text(),
                    descuento: $(this).find("td:nth-child(9)").text(),
                    // Agrega aquí las demás columnas que necesites
                };
                tableData.push(rowData);
            });

            // Combinar los datos del formulario y de la tabla
            var postData = {
                formData: formData,
                tableData: tableData
            };

            // Enviar los datos al controlador mediante una petición AJAX
            $.ajax({
                url: base_url + 'fees/generar_boleta',
                type: 'POST',
                data: postData,
                success: function (response) {
                    // Manejar la respuesta del controlador
                    console.log(response);
                    alert("Pago realizado");

                    location.reload();

                },
                error: function (error) {
                    // Manejar errores
                    console.error(error);
                }
            });
        });
    });
</script>











<script>
    $(document).ready(function () {
        // Manejar el clic en el botón "Agregar Pago Personalizado"
        $("#agregarPagoPer").on("click", function () {
            // Obtener los valores necesarios para la nueva fila (puedes ajustar esto según tus necesidades)
            var selectedStudentName = "PERSONALIZADO";
            var descripcion = "<input type='text' class='form-control' name='descripcion[]' value='Descripción'>";
            var cantidad = "<input type='number' class='form-control' name='cantidad[]' value='1'>";
            var pUnitario = "<input type='number' class='form-control' name='pUnitario[]' value='10'>";

            // Agregar la nueva fila a la tabla
            $("#tablaSegunda tbody").append(
                "<tr>" +
                "<td>" + selectedStudentName + "</td>" +
                "<td>" + descripcion + "</td>" +
                "<td>" + cantidad + "</td>" +
                "<td>" + pUnitario + "</td>" +
               

                "<td class='pTotal'>"+10+"</td>" +
                "</tr>"
            );

            // Actualizar el valor de #montoTotal
            updateMontoTotal();
        });

        // Actualizar P. Total al cambiar la cantidad o el P. Unitario
        $("#tablaSegunda tbody").on("change", "input[name^='cantidad'], input[name^='pUnitario']", function () {
            var row = $(this).closest("tr");
            var cantidad = parseFloat(row.find("input[name^='cantidad']").val()) || 0;
            var pUnitario = parseFloat(row.find("input[name^='pUnitario']").val()) || 0;
            var pTotal = cantidad * pUnitario;

            row.find(".pTotal").text(pTotal.toFixed(2));

            // Actualizar el valor de #montoTotal
            updateMontoTotal();
        });

        function updateMontoTotal() {
            var total = 0;
            $(".pTotal").each(function () {
                total += parseFloat($(this).text()) || 0;
            });

            $("#montoTotal").attr("data-value", total.toFixed(2));
            $("#montoTotal").find(".required").text(total.toFixed(2));
        }

    });
</script>


<script>
    $(document).ready(function () {
        // Manejar el clic en el botón "Agregar Seleccionados"
        $("#agregarFilas").on("click", function () {
            // Obtener las filas seleccionadas
            var filasSeleccionadas = $(".seleccionar-fila:checked").closest("tr");

            // Iterar sobre las filas seleccionadas y agregarlas a la segunda tabla
            filasSeleccionadas.each(function () {
                var selectedStudentId = $("#student_id").val();
                var selectedStudentName = $("#student_id option:selected").text();
                var descripcion = $(this).find("td:nth-child(2)").text();
                var cantidad = 1;  // Puedes ajustar esto según tus necesidades
                var pUnitario = parseFloat($(this).find("td:nth-child(3) ").text());
                var pTotal = cantidad * parseFloat(pUnitario);
                var feeTypeID = $(this).find("td:nth-child(5)").text();
                var allocationID = $(this).find("td:nth-child(6)").text();
                var monto = $(this).find("td:nth-child(7)").text();
                var descuento = $(this).find("td:nth-child(8)").text(); 
                console.log("Valor de pUnitario:", pUnitario);

                // Agregar la fila a la segunda tabla
                $("#tablaSegunda tbody").append(
                    "<tr>" +
                    "<td>" + selectedStudentName + "</td>" +
                    "<td>" + descripcion + "</td>" +
                    "<td> <input type='number' class='form-control' name='cantidad[]' value='" + cantidad + "' disabled></td>"+
                    '<td> <input type="number" class="form-control" name="pUnitario[]" value="'+pUnitario +'"></td>' +


                    "<td class='pTotal'>" + pTotal.toFixed(2) + "</td>" +

                  
                    '<td style="display:none;">' + feeTypeID + '</td>' +
                    '<td style="display:none;">' + allocationID + '</td>' +
                    '<td style="display:none;">' + monto + '</td>' +

                    '<td style="display:none;">' + descuento + '</td>' +

                    
                    "</tr>"
                );
            });

            // Limpiar las filas seleccionadas en la primera tabla
            filasSeleccionadas.remove();

            // Actualizar el valor de #montoTotal después de agregar filas
            updateMontoTotal();
        });

        function updateMontoTotal() {
            var total = 0;
            $(".pTotal").each(function () {
                total += parseFloat($(this).text()) || 0;
            });

            $("#montoTotal").attr("data-value", total.toFixed(2));
            $("#montoTotal").find(".required").text(total.toFixed(2));
        }
    });
</script>






<script>
$(document).ready(function () {
    // Manejar el cambio en el menú desplegable
    $('#student_id').change(function () {

        var selectedStudentId = $(this).val();

        $.ajax({
            url: base_url + 'fees/actualizar_tabla_pagos',
            type: 'POST',
            data: { student_id: selectedStudentId },
            dataType: 'json',
            success: function (response) {
                // Actualizar la tabla con los datos recibidos
                actualizarTabla(response);
                
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Función para actualizar la tabla con los datos recibidos
    function actualizarTabla(data) {
        var tablaBody = $('#tablaEstudiantes tbody');
        tablaBody.empty(); // Limpiar el contenido actual

        // Iterar sobre los datos y agregar filas a la tabla
        $.each(data, function (index, row) {
            var newRow = '<tr>' +
                '<td><input type="checkbox" class="seleccionar-fila" value="' + row.id + '"></td>' +
                '<td>' + row.descripcion + '</td>' +
                '<td>' + row.net_amount + '</td>' +
                    '<td>' + row.due_date + '</td>' +
                '<td style="display:none;">' + row.feeTypeID + '</td>' +
                '<td style="display:none;">' + row.allocationID + '</td>' +
                '<td style="display:none;">' + row.amount + '</td>' +
                '<td style="display:none;">' + row.descuento + '</td>' +
                '</tr>';

            tablaBody.append(newRow);
        });
    }
});
</script>






<script>









$(document).ready(function () {
    // Evento para detectar el clic en el botón "Agregar"
    $('.btn-primary').click(function () {
        // Recorrer las filas seleccionadas
        $('.fila-seleccionada').each(function () {
            // Obtener los datos de la fila seleccionada
            var descripcion = $(this).find('td:eq(1)').text();
            var net_amount = $(this).find('td:eq(2)').text();
            var due_date = $(this).find('td:eq(3)').text();

            // Agregar una nueva fila a la tabla existente
            var nuevaFila = '<tr>' +
                                '<td>   </td>' +
                                '<td>' + descripcion + '</td>' +
                                '<td></td>' +
                                '<td></td>' +
                                '<td>' + net_amount + '</td>' +
                            '</tr>';

            $('.table tbody').append(nuevaFila);
        });

        // Limpiar las filas seleccionadas
        $('.fila-seleccionada').hide();
    });
});

</script>

<script>
    $(document).ready(function () {
        // Evento para detectar el cambio en el menú desplegable
        $('#student_id').change(function () {
            // Vaciar el contenido de la tabla
            $('#tablaResultados tbody').empty();

            // Obtener el valor seleccionado
            var selectedStudentId = $(this).val();

            // Realizar una solicitud AJAX para obtener y mostrar la tabla
            $.ajax({
                url: base_url + 'fees/actualizar_pagos_pendientes',
                type: 'POST',
                data: { student_id: selectedStudentId },
                success: function (response) {
                    // Actualizar el contenido de la tabla con la respuesta
                    $('#tablaResultados').html(response);
                },
                error: function (error) {
                    console.log(error);
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

<script type="text/javascript">
    function updateDocumentNumbers(branchId, payDocument) {
        $.ajax({
            url: base_url + 'fees/actualizar_numeros_documentos',
            method: 'POST',
            data: {
                branch_id: branchId,
                pay_document: payDocument
            },
            dataType: 'json',
            success: function(response) {
                // Actualiza los valores del número de documento en el formulario
                var documentNumberField = document.getElementById('numeroDocumento');
                documentNumberField.value = (payDocument === '1') ? response.boletaFormatted : response.reciboFormatted;
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>

  
                               

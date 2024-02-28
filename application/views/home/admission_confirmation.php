<head>
  <!-- Otras etiquetas head ... -->
  
  <!-- Enlaces para SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.all.min.js"></script>
  
</head>


<style type="text/css">
    #print {
        margin-bottom: 20px;
        margin-top: 0px;
        padding: 2px 15px;
        font-size: 18px;
        font-weight: 500;
    }

	   /* Ocultar el botón de imprimir en la versión impresa */
	   @media print {
        .btn-print {
            display: none;
        }
    }

	
</style>


 


<!-- Main Banner Starts -->
<div class="main-banner" style="background: url(<?php echo base_url('uploads/frontend/banners/' . $page_data['banner_image']); ?>) center top;">
    <div class="container px-md-0">
        <h2><span><?php echo $page_data['page_title']; ?></span></h2>
    </div>
</div>
<!-- Main Banner Ends -->
<!-- Breadcrumb Starts -->
<div class="breadcrumb">
    <div class="container px-md-0">
        <ul class="list-unstyled list-inline">
            <li class="list-inline-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
            <li class="list-inline-item active"><?php echo $page_data['page_title']; ?></li>
        </ul>
    </div>
</div>
<div class="container px-md-0 main-container">
<div class="row">
    <div class="col-md-12">
        <div class="box2 form-box">
	        <?php
	        if($this->session->flashdata('success')) {
	            echo '<div class="alert alert-success"><i class="icon-text-ml far fa-check-circle"></i>' . $this->session->flashdata('success') . '</div>';
	        }
	        ?>
        	<div id="card_holder">
                <div id="card">
                    	
				<style type="text/css">
					@media print {
						.pagebreak {
							page-break-before: always;
						}
					}
					.mark-container {
					    background: #fff;
					    width: 1000px;
					    position: relative;
					    z-index: 2;
					    margin: 0 auto;
					    padding: 20px 30px;
					}
					table {
					    border-collapse: collapse;
					    width: 100%;
					    margin: 0 auto;
					}

					
					.table {
						width: 100%;
						border-collapse: collapse;
						margin-top: 20px;
					}

					.table th, .table td {
						padding: 8px;
						border: 1px solid #ccc;
					}

					.table th {
						background-color: #f0f0f0;
						text-align: left;
					}

					.table tr:nth-child(even) {
						background-color: #f5f5f5;
					}

					.terms-accordion {
						margin-top: 20px;
					}

					.terms-card {
						border: 1px solid #ccc;
					}

					.terms-header {
						background-color: #f8f8f8;
						padding: 10px 20px;
						cursor: pointer;
					}

					.terms-heading {
						font-size: 18px;
						margin: 0;
					}

					.terms-content {
						background-color: #fff;
						padding: 20px;
						border-top: 1px solid #ccc;
					}

					.terms-button {
						margin-top: 10px;
					}

					

					body {
							text-align: justify;
						}
					.centered-table {
    					border-collapse: collapse;
            			border: 1px solid #ccc;
            			margin: auto;
        			}
        			.centered-table th, .centered-table td {
           	 			border: 1px solid #ccc;
            			padding: 10px;
           	 			text-align: center;
       			 	}

						.swal2-popup {
      background-color: #f7f7f7;
      border-radius: 10px;
      border: 2px solid #3085d6;
    }

    .swal2-title {
      color: #3085d6;
    }

    .swal2-confirm {
      background-color: #3085d6;
    }


				</style>

				</style>
				<?php $getSchool = $this->db->where(array('id' => $student['branch_id']))->get('branch')->row_array(); ?>
					<div class="mark-container">
						<table border="0" style="margin-top: 20px; height: 100px;">
							<tbody>
								<tr>
								<td style="width:40%;vertical-align: top;"><img style="max-width:225px;" src="<?=$this->application_model->getBranchImage($student['branch_id'], 'report-card-logo')?>"></td>
								<td style="width:60%;vertical-align: top;">
									<table align="right" class="table-head text-right" >
										<tbody>
											<tr><th style="font-size: 26px;" class="text-right"><?=$getSchool['school_name']?></th></tr>
											<tr><td><?=$getSchool['address']?></td></tr>
											<tr><td><?=$getSchool['mobileno']?></td></tr>
											<tr><td><?=$getSchool['email']?></td></tr>
										</tbody>
									</table>
								</td>
								</tr>
							</tbody>
						</table>
						<h4 style="padding-top: 30px">Ficha de Admisión</h4>
						<table class="table table-condensed table-bordered" style="margin-top: 20px;">
							<tbody>
								<tr>
									<th>ID DE ADMISIÓN</td>
									<td colspan="2"><?=$student['id'] ?></td>
									<th>Fecha de Aplicación</td>
									<td colspan="2"><?=_d($student['apply_date'])?></td>
								</tr>
								<tr>
									<th>Periodo de Admisión</td>
									<td><?=get_type_name_by_id('schoolyear', get_global_setting('session_id'), "school_year")?></td>
									<th>Grado</td>
									<td colspan><?=$student['class_name'] ?></td>
									<th>Sección</td>
									<td><?=(empty($student['section_name'])) ? "N/A" : $student['section_name'] ?></td>
								</tr>
							</tbody>
						</table>

						<table class="table table-condensed table-bordered" style="margin-top: 20px;">
							<tbody>
								<tr>
									<th>Nombres</td>
									<td><?=$student['first_name']?></td>
									<th>Apellidos</td>
									<td><?=$student['last_name']?></td>
									<th>Genero</td>
									<td><?=ucfirst($student['gender'])?></td>
								</tr>
								<tr>
									<th>Cumpleaños</td>
									<td><?=_d($student['birthday'])?></td>
									<th>N° Celular</td>
									<td><?=$student['mobile_no'] ?></td>
									<th>Email</td>
									<td><?=$student['email']?></td>
								</tr>
								<tr>
									<th>Nombres del Padre</td>
									<td colspan="6"><?=(empty($student['father_name'])) ? "N/A" : $student['father_name'] ?></td>
									
								</tr>
								<tr>
									<th>Nombres de Madre</td>
									<td colspan="6"><?=(empty($student['mother_name'])) ? "N/A" : $student['mother_name'] ?></td>
									
								</tr>
								<tr>
									<th>Dirección</td>
									<td colspan="6"><?=(empty($student['address'])) ? "N/A" : $student['address'] ?></td>
								</tr>
							</tbody>
						</table>

						<table class="table table-condensed table-bordered" style="margin-top: 20px;">
							<tbody>
								<tr>
									<th>Relación de Apoderado</td>
									<td><?=(empty($student['guardian_relation'])) ? "N/A" : $student['guardian_relation'] ?></td>
									<th>Nombre del Apoderado</td>
									<td><?=(empty($student['guardian_name'])) ? "N/A" : $student['guardian_name'] ?></td>
									
								</tr>
								<tr>
									
									<th>Correo del Apoderado</td>
									<td><?=(empty($student['grd_email'])) ? "N/A" : $student['grd_email'] ?></td>
									<th>N° Celular de Apoderado</td>
									<td><?=(empty($student['grd_mobile_no'])) ? "N/A" : $student['grd_mobile_no'] ?></td>
								</tr>
								<tr>
									<th>Dirección de Apoderado</td>
									<td colspan="6"><?=(empty($student['grd_address'])) ? "N/A" : $student['grd_address'] ?></td>
								</tr>
							</tbody>
						</table>
	
						
						<button type="button" class="btn btn-primary btn-print" id="print" >
							<i class="fas fa-print"></i> Generar ficha de matrícula
						</button>

						<div class="accordion terms-accordion" id="termsAccordion">
    						<div class="card terms-card">
        						<div class="card-header terms-header" id="termsHeading">
            						<h2 class="mb-0">
                					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#termsCollapse" aria-expanded="true" aria-controls="termsCollapse">
                    				Ver Términos y Condiciones
                					</button>
            						</h2>
        						</div>
       							 <div id="termsCollapse" class="collapse show" aria-labelledby="termsHeading" data-parent="#termsAccordion">
           					 		<div class="terms-content">
                						<h3>CONDICIONES DEL PROCESO DE ADMISIÓN 2024</h3>
        								<p>El <strong>GRUPO EDUCATIVO AIAPAEC</strong> con RUC 20477480463 con domicilio legal en la Av. Honorio 550, la Entidad Promotora de los colegios Aiapaec (en adelante “EL COLEGIO”), cumpliendo con las disposiciones legales vigentes y con el propósito que los padres de familia dispongan de toda la información respecto a las condiciones del proceso de admisión para el año escolar 2024, ponen en conocimiento la siguiente información:</p>

                						<!-- Agrega más contenido de términos aquí -->
										<h4>I. Datos del Postulante</h4>
        								<p><strong>Nombres y apellidos:</strong>  <?=$student['first_name']?> <?=$student['last_name']?></p>
       									
        								<p><strong>Sede:</strong>  <?=$getSchool['school_name']?></p>
        								<p><strong>Grado:</strong> <?=$student['class_name']?></p>
        								<p><strong>Periodo Escolar:</strong> <?=get_type_name_by_id('schoolyear', get_global_setting('session_id'), "school_year")?></p>
										<h4>II. Datos del apoderado</h4>
       									<p><strong>Nombres y apellidos:</strong> <?=(empty($student['guardian_name'])) ? "N/A" : $student['guardian_name'] ?></p>
        								
        								<p><strong>Celular:</strong> <?=(empty($student['grd_mobile_no'])) ? "N/A" : $student['grd_mobile_no'] ?></p>
        								<p><strong>Correo electrónico:</strong> <?=(empty($student['grd_email'])) ? "N/A" : $student['grd_email'] ?></p>

        								<h4>III. El procedimiento de admisión 2024 consiste en lo siguiente:</h4>
        								<p>1.  El servicio educativo que presta EL COLEGIO se encuentra debidamente autorizado y podrá ser prestado en forma presencial de acuerdo a las disposiciones emitidas por el Ministerio de Educación en el “Instructivo con disposiciones específicas para cada tipo de proceso de matrícula para el año escolar 2024”.</p>
										<p>2.  El padre, madre o apoderado, reconoce que EL COLEGIO se encuentra sujeto a las disposiciones emitidas por el Ministerio de Educación, respecto a la forma de prestación del servicio educativo y acepta que en caso exista una modificación de la prestación del servicio educativo responde al cumplimiento de la normativa vigente y no a una decisión unilateral. </p>
										<p>3.  El padre, madre o apoderado, reconoce que el aforo y los horarios de la prestación del servicio educativo de EL COLEGIO podrá verse modificado en función a las disposiciones que emita el Ministerio de Educación y la reorganización interna.</p>
										<p>4.  Quien realice el proceso de admisión y matrícula del postulante debe ser padre, madre o apoderado con poderes del mismo. En caso no sea ninguna de estas personas, EL COLEGIO podrá revocar la vacante sin derecho a devolución de los montos pagados o de los gastos en los que haya incurrido a la fecha. Esta persona autorizada será llamada “EL APODERADO” a lo largo del proceso de admisión y de matrícula.  </p>
										<h4>5.  REQUISITOS DEL PROCESO DE ADMISIÓN:</h4>
										<p>  5.1  La admisión de los estudiantes a EL COLEGIO se ajusta a lo que determina la ley, teniendo en cuenta que el criterio fundamental es la elección voluntaria de EL COLEGIO por parte de los padres o apoderado del estudiante, sobre la base de la confianza que depositan en el centro de estudios.  </p>
										<p> 5.2  El ingreso de los estudiantes estará supeditado a las vacantes que tenga EL COLEGIO, dándose prioridad, en caso exista un número mayor de postulantes que vacantes, se tendrá en cuenta el orden de solicitud de ingreso.</p>
										<p> 5.3  Cuando un estudiante ha sido separado definitivamente de una de las Sedes de EL COLEGIO o no se ha renovado su matrícula, no podrá postular ni ser admitido a otra Sede de EL COLEGIO. </p>
										<p>6.  EL APODERADO ingresará a la web https://colegioaiapaec.edu.pe, sección “Admisión” (en adelante “LA WEB”) para seleccionar la vacante en la sede, grado y turno de su interés.  </p>
										<p>7.  EL APODERADO deberá tener en cuenta que la fecha de corte establecida por el Ministerio de Educación para calcular el grado que le corresponde estudiar al postulante en el nivel inicial, es el 31 de marzo. En caso de que EL APODERADO matricule al menor en un grado que no le corresponda por edad, se dejará sin efecto la vacante y sin responsabilidad de EL COLEGIO.  </p>
										<p>8.  EL APODERADO deberá completar sus datos y la del postulante que solicitará la Web. EL APODERADO es responsable de verificar la exactitud de los datos declarados en la Web. </p>
										<p>9.  Se deberá leer y aceptar los “Términos y Condiciones del Proceso de Admisión”, las mismas que llegarán en un documento en formato PDF al correo electrónico registrado por EL APODERADO.  </p>
										<p>10.  De aceptar las presentes condiciones, EL APODERADO abonará el pago por “Concepto de Evaluación” mediante los canales de pago virtuales y se enviará la constancia de abono por los medios de pagos disponibles en un plazo máximo de cinco (05) días hábiles. Ver detalle en la siguiente tabla:  </p>
										<table class="centered-table">
											<tr>
												<th>GRADO - NIVEL</th>
												<th>TIPO DE EVALUACIÓN</th>
												<th>INVERSIÓN</th>
											</tr>
											<tr>
												<td>De 3 a 5 Años - Inicial</td>
												<td>Psicopedagógica</td>
												<td>S/ 30.00 </td>
											</tr>
											<tr>
												<td>1° Grado – Primaria</td>
												<td>Psicopedagógica</td>
												<td>S/ 30.00 </td>
											</tr>
											<tr>
												<td>2° Grado a 6° Grado - Primaria </td>
												<td>Psicopedagógica</td>
												<td>S/ 60.00 </td>
											</tr>
											<tr>
												<td>1° Año a 5°Año - Secundaria</td>
												<td>Psicopedagógica</td>
												<td>S/ 60.00 </td>
											</tr>
										</table>
										<p>11.  Realizado el pago se seguirán los siguientes pasos: </p>
										<p>11.1  EL APODERADO agendará la fecha y horario para que el postulante rinda las evaluaciones presenciales.  </p>
										<p>11.2  EL APODERADO deberá completar y enviar la Ficha Integral del postulante en LA WEB y solo en caso se requiera, anexará los documentos en un plazo máximo de 2 días previo a la cita pactada, caso contrario perderá su turno y tendrá que reprogramar por única vez su entrevista, de no cumplir con lo último se dará por finalizado el proceso de admisión, sin lugar a reclamos ni devolución de los pagos efectuados.  </p>
										<p>11.3 EL APODERADO subirá a nuestra intranet el expediente de evaluación que consta de: </p>
										<ul>
											<li>DNI del estudiante</li>
											<li>DNI de apoderado</li>
											<li>Constancia de Estudios del Grado en curso. </li>
											<li>Constancia de No Adeudo del colegio de procedencia (excepto I.E. de Gestión Pública). . </li>
											<li>Recibo de luz o agua (máximo 2 meses de antigüedad). . </li>
										</ul>
										<p> Si EL APODERADO no envía los documentos solicitados se tomará la evaluación, pero no se comunicará el resultado. EL APODERADO tendrá un máximo de siete (07) días hábiles a partir del pago por cuota de evaluación para la presentación de dichos documentos, caso contrario el resultado será de NO ADMITIDO por falta de documentos. </p>
										<p>12.  Si de la revisión realizada, se genera la necesidad de tener una entrevista adicional, EL COLEGIO se comunicará con EL APODERADO al correo registrado en la web y se deberá coordinar una nueva cita dentro de los siguientes cuatro (04) días hábiles. De no poderse concretar la cita en el plazo indicado, se considerará como NO ADMITIDO por incumplir con el proceso de admisión sin lugar a reclamos ni devolución de los pagos efectuados.  </p>
										<p>13.  EL COLEGIO comunicará a EL APODERADO los resultados de la postulación a través de la “Carta de Admisión”, que se enviará al correo electrónico que ha registrado en la web dentro de cuatro (04) días hábiles luego de la última evaluación rendida.  </p>
										<p>14.  De ser admitido, deberá leer el “Contrato de prestación de servicios educativos”, “Guía Informativa” y anexos, las mismas que llegarán en un documento en formato PDF al correo electrónico registrado por EL APODERADO.  </p>
										<p>15.  En caso se encuentre de acuerdo con el “Contrato de prestación de servicios educativos”, la “Guía Informativa” y anexos, deberá realizar el abono de la “Cuota de Ingreso” y “Concepto de Matrícula” a través de los canales de pago, además presentar de manera presencial o virtual (intranet), el contrato firmado y anexar a él, la copia de los comprobantes de pago realizados en un plazo máximo de cinco (05) días hábiles, después de haber recibido la “Carta de Admisión”. </p>
										<p>16.  Efectuados todos los pagos mencionados, habiendo aceptado y firmado el “Contrato de Prestación de servicios” y sus respectivos anexos, EL COLEGIO enviará la “Constancia de Vacante” al correo electrónico registrado por EL APODERADO, quien a su vez debe subir la siguiente documentación, teniendo como plazo máximo hasta el 26 de febrero del 2024:  </p>
										<ul>
											<li>Tarjeta de Vacunación (3 años o primer año de estudio de etapa escolar)</li>
											<li>Ficha Única de matrícula del SIAGIE (excepto estudiantes que ingresen a inicial de 3 años o inicien por primera vez su etapa escolar).</li>
											<li>Certificado de Estudios (opcional). </li>
											
											
											<li>Certificado de Conducta (5° primaria a 5° secundaria). </li>
											<li>De ser el caso, presentar el certificado, resolución o carnet de discapacidad emitidos por el CONADIS o informe médico emitido por un establecimiento de salud autorizado. </li>
										</ul>

										<h4>IV. Condiciones del proceso de admisión 2024:</h4>
										<h4>EL APODERADO: </h4>


										<p>1. DECLARA: conocer que el presupuesto de operación e inversión de los colegios Aiapaec se financia, fundamentalmente, con las pensiones de enseñanza, que a su vez solventan el pago de remuneraciones del personal docente, administrativo, servicio, así como la adquisición de bienes y pago de servicios (luz, agua, teléfono, internet, etc.); y que el pago oportuno y puntual de dichas pensiones evita el cobro de intereses moratorios que se establecen en la Institución de acuerdo a Ley.  </p>
										<p>2. SE COMPROMETE: A seguir cada uno de los pasos del Proceso de Admisión 2024 antes mencionados. De no cumplir con alguno de los plazos, procedimientos o requisitos establecidos, se liberará la vacante reservada sin derecho a devolución de los gastos cancelados al tratarse de servicios efectivamente prestados ni reembolso de ningún gasto en el que hubieran incurrido.  </p>
										<p>3. DECLARA: Aceptar que la emisión de los comprobantes de pago del servicio educativo se realiza de manera electrónica a nombre de EL APODERADO y se encontrará disponible para su descarga en la intranet (AIAPEC).  </p>
										<p>4. DECLARA: Conocer que no pagará Cuota de Ingreso si lo pagó efectivamente en algún año anterior respecto del mismo postulante y esta no fue devuelta.  </p>
										<p>5. ACEPTA: Que los documentos del proceso de evaluación no se proporcionarán en físico ni en virtual, por ser documentos confidenciales y de manejo netamente institucional. En caso de no ser admitido, los resultados del proceso de admisión son inapelables y no cabe la reevaluación. Estos resultados se mantienen para todo el proceso de admisión 2024. </p>
										<p>6. SE COMPROMETE: En caso el postulante haya cursado estudios en el extranjero, presentará los documentos que le sean oportunamente requeridos por esta condición y realizará todos los trámites de revalidación y/o convalidación de grado ante las autoridades competentes. EL APODERADO DECLARA conocer que EL COLEGIO podrá retirar la condición de admitido y el derecho a la matrícula para el año 2024, en caso no se cumpla con la revalidación y/o convalidación mencionada, no existiendo opción a devolución de los montos pagados ni reembolso de ningún gasto en el que se hubiera incurrido.  </p>
										<p>7. ACEPTA: Conocer los precios del proceso de admisión 2023, los cuales han sido entregados en el prospecto informativo 2024 e incluyen los siguientes conceptos: Evaluación exploratoria, cuota de ingreso, matrícula y pensión. </p>
										<p>8. ACEPTA: Que no habrá devolución de los montos pagados (concepto de evaluación exploratoria, cuota de ingreso y matrícula) ni reembolso de ningún gasto en el que se hubiera incurrido, en caso el postulante culmine o no el proceso de admisión 2024 y EL APODERADO decida que no continuará sus estudios en el periodo 2024 en la sede elegida, debido a que la institución ha cumplido con brindar los servicios contratados.  </p>
										<p>9. DECLARA: Que no mantienen ninguna deuda con EL COLEGIO por el postulante ni por otro estudiante al que representa, y que no ha recibido en el pasado carta de no renovación de la Sede a la que postula o de cualquier otra Sede de EL COLEGIO. En caso se verifique que esta información no sea veraz, EL COLEGIO se reserva el derecho de retirar la vacante no existiendo opción a devolución de los montos pagados ni reembolso de ningún gasto en el que se hubiera incurrido.  </p>
										<p>10. ACEPTA: Cumplir con entregar de forma virtual los documentos detallados en la “Constancia de Vacante” (debidamente llenados y firmados) dentro de las fechas que se le enviará oportunamente. </p>
										<p>11. DECLARA: Que debe cumplir con los plazos informados para cada una de las etapas del proceso de admisión, así como realizar los pagos y entrega de documentos mencionados en la “Constancia de Vacante”, según el cronograma establecido. EL APODERADO DECLARA conocer que luego de estas fechas, EL COLEGIO tendrá el derecho de liberar la vacante reservada y de disponer de ella.  </p>
										<p>12. SE COMPROMETE: a realizar todos los trámites ante las autoridades competentes (colegio de origen, UGEL, Defensoría del Pueblo, INDECOPI), a fin de obtener la matrícula o traslado del postulante en el SIAGIE del colegio, cuando ésta sea rechazada por algún problema en el colegio anterior o con el postulante. La aprobación de la matrícula o del traslado en el SIAGIE deberá ocurrir hasta el 25 de marzo del 2024, de lo contrario, EL APODERADO DECLARA conocer que el postulante no podrá ser registrado oficialmente como alumno de la sede elegida, ante el Ministerio de Educación (SIAGIE) y libera a EL COLEGIO de cualquier responsabilidad.  </p>
										<p>13. ACEPTA: Que el postulante deberá permanecer durante el año escolar 2024 en la sede donde fue admitido. No se aceptarán traslados durante el primer año de estudios, solo se aceptarán traslados de manera excepcional por mudanza, y el mismo estará sujeto a la disponibilidad de vacantes en la sede de destino y a las condiciones establecidas para dicho proceso. El traslado deberá ser solicitado en la Sede de origen por el apoderado, no se deberá tener ningún tipo de deuda con EL COLEGIO, ni tampoco carta de compromiso conductual. El traslado es aprobado tanto por la Sede de origen como de destino.  </p>
										<p>14. DECLARA: Conocer que la matrícula en el grado correspondiente al año 2024, depende de que el postulante haya sido promovido de grado en el colegio de procedencia en el año 2023, según la normativa vigente establecida por el Ministerio de Educación. En caso el estudiante no haya sido promovido al siguiente grado en donde reservó la vacante, no se puede asegurar la vacante del postulante en el año de repitencia y de no encontrar la disponibilidad de la misma, no existirá devolución ni reembolso de ningún concepto.  </p>
										<p>15. ACEPTA: Que, si el postulante presenta nota desaprobatoria en conducta en el colegio de procedencia, documentado a través de la libreta de notas o el acta de notas de fin de año, EL APODERADO deberá firmar la carta de compromiso conductual como requisito adicional, caso contrario se retirará la condición de admitido para el año escolar 2024 y no existirá devolución ni reembolso de ningún concepto.  </p>
										<p>16. DECLARA: Que toda la información consignada durante el proceso de admisión ha sido veraz. Asimismo, DECLARA conocer que en caso EL APODERADO oculte información para el proceso de admisión o falte a la verdad, EL COLEGIO podrá retirar la condición de admitido y/o matriculado y retirar el derecho a la vacante reservada para el año 2024, además no existirá devolución ni reembolso de ningún concepto.  </p>
										<p>17. DECLARA: Conocer y aceptar que EL COLEGIO se comunicará con el Apoderado por medio del correo electrónico declarado en LA WEB, motivo por el cual asume la responsabilidad de revisar este medio y de comunicar a EL COLEGIO cualquier actualización de los datos proporcionados. </p>
										<p>18. ACEPTA: que al realizar el pago de la “Cuota de Ingreso” y “Matrícula” conoce y está de acuerdo con los “Términos y Condiciones del Proceso de Admisión 2024” y “La Guía Informativa” remitidos a su correo electrónico, con la metodología y propuesta pedagógica de EL COLEGIO a la que ha tenido acceso mediante el prospecto informativo, página web de la Institución, redes sociales, etc. </p>
										<p>19. ACEPTA: que al realizar el pago por “Concepto de Evaluación Exploratoria” conoce y está de acuerdo con la información relacionada con el costo de este servicio educativo. </p>
										<p>20. SE COMPROMETE: a verificar “La Guía Informativa” y sus anexos. Asimismo, EL APODERADO declara que acatará y hará cumplir al estudiante las disposiciones del Reglamento Interno.  </p>
										<p>Al hacer clic en “Acepto la declaración” doy señal de conformidad con todos los términos y condiciones del presente documento de EL COLEGIO, el día <?=_d($student['apply_date'])?></p>
										<p> </p>

                						
                						
           					 		</div>
        						</div>
    						</div>
						</div>

						

						<?php if ($student['payment_status'] == 1) {
							$paymentDetails = json_decode($student['payment_details'], true);

							?>
						<h4 style="padding-top: 30px">Payment Details</h4>
						<table class="table table-condensed table-bordered" style="margin-top: 20px;">
							<tbody>
								<tr>
									<th>Paid Amount</td>
									<td><?=$student['symbol'] . " " .  $student['payment_amount'] ?></td>
									<th>Payment Method</td>
									<td colspan="2"><?=ucfirst($paymentDetails['payment_method'])?></td>
								</tr>
							</tbody>
						</table>
						<?php } ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#print').on('click', function(e){
            var oContent = document.getElementById('card').innerHTML;
            var frame1 = document.createElement('iframe');
            frame1.name = "frame1";
            frame1.style.position = "absolute";
            frame1.style.top = "-1000000px";

            document.body.appendChild(frame1);
            var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title></title>');
            frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'assets/vendor/bootstrap/css/bootstrap.min.css">');
            frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'assets/css/custom-style.css">');
            frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'assets/css/certificate.css">');
            frameDoc.document.write('</head><body>');
            frameDoc.document.write(oContent);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 500);
        });
    });
</script>




<script>


document.addEventListener('DOMContentLoaded', function() {
  const termsButton = document.querySelector('.terms-button');

  termsButton.addEventListener('click', function() {
    const messageContainer = document.querySelector('.message-container');
    messageContainer.textContent = 'Gracias por aceptar los términos y condiciones.';
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const termsButton = document.querySelector('.terms-button');

  termsButton.addEventListener('click', function() {
    Swal.fire({
      title: 'Gracias por aceptar los términos y condiciones',
      icon: 'success',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Cerrar'
    });
  });
});
</script>


<script type="text/javascript">
  // Seleccionar el elemento <title>
  var titleElement = document.querySelector('title');

  // Cambiar el contenido del título
  if (titleElement) {
    titleElement.innerText = "Ficha de Admisión 2024 - AIAPAEC";
  }
</script>




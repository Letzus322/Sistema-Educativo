<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH.'third_party/PHPMailder/Exception.php';
require_once APPPATH.'third_party/PHPMailder/PHPMailer.php';
require_once APPPATH.'third_party/PHPMailder/SMTP.php';
require_once APPPATH.'third_party/TCPDF/tcpdf.php';




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Home.php
 * @copyright : Reserved RamomCoder Team
 */

class Home extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers('custom_fields');
        $this->load->model('student_fields_model');
        $this->load->model('email_model');
        $this->load->model('testimonial_model');
        $this->load->model('gallery_model');
        $this->load->library('mailer');
    }

    public function index()
    {
        $this->home();
    }

    public function home()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['sliders'] = $this->home_model->getCmsHome('slider', $branchID, 1, false);
        $this->data['features'] = $this->home_model->getCmsHome('features', $branchID, 1, false);
        $this->data['wellcome'] = $this->home_model->getCmsHome('wellcome', $branchID);
        $this->data['teachers'] = $this->home_model->getCmsHome('teachers', $branchID);
        $this->data['testimonial'] = $this->home_model->getCmsHome('testimonial', $branchID);
        $this->data['services'] = $this->home_model->getCmsHome('services', $branchID);
        $this->data['cta_box'] = $this->home_model->getCmsHome('cta', $branchID);
        $this->data['statistics'] = $this->home_model->getCmsHome('statistics', $branchID);
        $this->data['page_data'] = $this->home_model->get('front_cms_home_seo', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/index', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function about()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_about', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/about', $this->data, true);
        $this->load->view('home/layout/index/', $this->data);
    }

    public function faq()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_faq', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/faq', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function events()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_events', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/events', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function event_view($id)
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['event'] = $this->home_model->get('event', array('id' => $id, 'branch_id' => $branchID, 'status' => 1, 'show_web' => 1), true);
        $this->data['page_data'] = $this->home_model->get('front_cms_events', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/event_view', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function teachers()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_teachers', array('branch_id' => $branchID), true);
        $this->data['departments'] = $this->home_model->get_teacher_departments($branchID);
        $this->data['doctor_list'] = $this->home_model->get_teacher_list("", $branchID);
        $this->data['main_contents'] = $this->load->view('home/teachers', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }



    public function mailSend( $arrayData){

        $pdf2 = new TCPDF();
        $pdf2->SetCreator(PDF_CREATOR);
        $pdf2->SetAuthor('Autor');
        $pdf2->SetTitle('Reserva de Matricula');
        $pdf2->AddPage();

        $branchName= $this->db->select('name')->from('branch')->where('id', $arrayData['branch_id']);
        $branchName= $branchName ->get()->row()->name;

        $className=$this->db->select('name')->from('class')->where('id', $arrayData['class_id']);
        $className= $className->get()->row()->name;

        $yearName=($this->db->select('school_year')->from('schoolyear')->where('id',  6)) ;
        $yearName= $yearName ->get() ->row()->school_year;
        
        $numeroSecretaria=($this->db->select('mobileno')->from('branch')->where('id', $arrayData['branch_id']));
        $numeroSecretaria =$numeroSecretaria->get()->row()->mobileno;

        $html = "
        <style >
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

<div class=\"terms-content\">
                        <h3>CONDICIONES DEL PROCESO DE ADMISIÓN 2024</h3>
                        <p>El <strong>GRUPO EDUCATIVO AIAPAEC</strong> con RUC 20477480463 con domicilio legal en la Av. Honorio 550, la Entidad Promotora de los colegios Aiapaec (en adelante “EL COLEGIO”), cumpliendo con las disposiciones legales vigentes y con el propósito que los padres de familia dispongan de toda la información respecto a las condiciones del proceso de admisión para el año escolar 2024, ponen en conocimiento la siguiente información:</p>

                        <!-- Agrega más contenido de términos aquí -->
                        <h4>I. Datos del Postulante  </h4>
                        <p><strong>Nombres y apellidos:  ".$arrayData['first_name'].' '.$arrayData['last_name']."</strong> 
                           
                        <p><strong>Sede : ". $branchName." </strong> 

                        <p><strong>Grado :".$className ."</strong> 
                        <p><strong>Periodo Escolar: " . $yearName. "</strong> 
                        <h4>II. Datos del apoderado</h4>
                           <p><strong>Nombres y apellidos: ".$arrayData['guardian_name']."</strong> 
                        
                        <p><strong>Celular :  ".$arrayData['grd_mobile_no']."</strong> 
                        <p><strong>Correo electrónico:  ".$arrayData['grd_email']." </strong> 

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
                        <table class=\"centered-table\">
                            <tbody><tr>
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
                        </tbody></table>
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
                        <p> </p>

                        
                        
                        </div>
        
        ";


        $pdf2->writeHTML($html, true, false, true, false, '');
        
    
        $pdfContent = $pdf2->Output('', 'S');

        $mail = new PHPMailer(true);   
        //2 desactivar         
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->CharSet='UTF-8';
        $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'admision@colegioaiapaec.edu.pe';                     //SMTP username
        $mail->Password   = 'Aiapaec2023';    
        //tsl                           //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
    
        //Recipients
        $mail->setFrom('admision@colegioaiapaec.edu.pe', 'Aiapaec');
        $mail->addAddress($arrayData['grd_email'], '');     //Add a recipient
        

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        $mail->addStringAttachment($pdfContent, utf8_decode('solicitud_reserva_vacante_'.$arrayData['first_name']."_" .$arrayData['last_name']).'.pdf', 'base64', 'application/pdf');
        //Content

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Confirmacion de reserva de matrícula';
        $imagePath = APPPATH . 'logo.png';
        // Obtener el contenido de la imagen como base64
      
        $mail->Body =  $mail->Body = '<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="ES"
        style="font-family:arial, \'helvetica neue\', helvetica, sans-serif">
    
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta name="x-apple-disable-message-reformatting">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="telephone=no" name="format-detection">
        <title>Correo concurso</title>
        <!--[if (mso 16)]><style type="text/css">     a {text-decoration: none;}     </style><![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]><xml> <o:OfficeDocumentSettings> <o:AllowPNG></o:AllowPNG> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml>
    <![endif]--><!--[if !mso]><!-- -->
        <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@300&display=swap" rel="stylesheet">
        <!--<![endif]--><!--[if !mso]><!-- -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet"><!--<![endif]-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Gentona:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
            <style type="text/css">
                 /* Estilo para el encabezado de la tabla */
            th {
                background-color: red; /* Fondo rojo */
                color: white; /* Texto blanco */
            }
            #outlook a {
                padding: 0;
            }
    
            .es-button {
                mso-style-priority: 100 !important;
                text-decoration: none !important;
            }
    
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
    
            .es-desk-hidden {
                display: none;
                float: left;
                overflow: hidden;
                width: 0;
                max-height: 0;
                line-height: 0;
                mso-hide: all;
            }
    
            @media only screen and (max-width:600px) {
    
                p,
                ul li,
                ol li,
                a {
                    line-height: 150% !important
                }
    
                h1,
                h2,
                h3,
                h1 a,
                h2 a,
                h3 a {
                    line-height: 120% !important
                }
    
                h1 {
                    font-size: 30px !important;
                    text-align: left
                }
    
                h2 {
                    font-size: 24px !important;
                    text-align: left
                }
    
                h3 {
                    font-size: 20px !important;
                    text-align: left
                }
    
                .es-header-body h1 a,
                .es-content-body h1 a,
                .es-footer-body h1 a {
                    font-size: 30px !important;
                    text-align: left
                }
    
                .es-header-body h2 a,
                .es-content-body h2 a,
                .es-footer-body h2 a {
                    font-size: 24px !important;
                    text-align: left
                }
    
                .es-header-body h3 a,
                .es-content-body h3 a,
                .es-footer-body h3 a {
                    font-size: 20px !important;
                    text-align: left
                }
    
                .es-menu td a {
                    font-size: 14px !important
                }
    
                .es-header-body p,
                .es-header-body ul li,
                .es-header-body ol li,
                .es-header-body a {
                    font-size: 14px !important
                }
    
                .es-content-body p,
                .es-content-body ul li,
                .es-content-body ol li,
                .es-content-body a {
                    font-size: 14px !important
                }
    
                .es-footer-body p,
                .es-footer-body ul li,
                .es-footer-body ol li,
                .es-footer-body a {
                    font-size: 14px !important
                }
    
                .es-infoblock p,
                .es-infoblock ul li,
                .es-infoblock ol li,
                .es-infoblock a {
                    font-size: 12px !important
                }
    
                *[class="gmail-fix"] {
                    display: none !important
                }
    
                .es-m-txt-c,
                .es-m-txt-c h1,
                .es-m-txt-c h2,
                .es-m-txt-c h3 {
                    text-align: center !important
                }
    
                .es-m-txt-r,
                .es-m-txt-r h1,
                .es-m-txt-r h2,
                .es-m-txt-r h3 {
                    text-align: right !important
                }
    
                .es-m-txt-l,
                .es-m-txt-l h1,
                .es-m-txt-l h2,
                .es-m-txt-l h3 {
                    text-align: left !important
                }
    
                .es-m-txt-r img,
                .es-m-txt-c img,
                .es-m-txt-l img {
                    display: inline !important
                }
    
                .es-button-border {
                    display: inline-block !important
                }
    
                a.es-button,
                button.es-button {
                    font-size: 18px !important;
                    display: inline-block !important
                }
    
                .es-adaptive table,
                .es-left,
                .es-right {
                    width: 100% !important
                }
    
                .es-content table,
                .es-header table,
                .es-footer table,
                .es-content,
                .es-footer,
                .es-header {
                    width: 100% !important;
                    max-width: 600px !important
                }
    
                .es-adapt-td {
                    display: block !important;
                    width: 100% !important
                }
    
                .adapt-img {
                    width: 100% !important;
                    height: auto !important
                }
    
                .es-m-p0 {
                    padding: 0 !important
                }
    
                .es-m-p0r {
                    padding-right: 0 !important
                }
    
                .es-m-p0l {
                    padding-left: 0 !important
                }
    
                .es-m-p0t {
                    padding-top: 0 !important
                }
    
                .es-m-p0b {
                    padding-bottom: 0 !important
                }
    
                .es-m-p20b {
                    padding-bottom: 20px !important
                }
    
                .es-mobile-hidden,
                .es-hidden {
                    display: none !important
                }
    
                tr.es-desk-hidden,
                td.es-desk-hidden,
                table.es-desk-hidden {
                    width: auto !important;
                    overflow: visible !important;
                    float: none !important;
                    max-height: inherit !important;
                    line-height: inherit !important
                }
    
                tr.es-desk-hidden {
                    display: table-row !important
                }
    
                table.es-desk-hidden {
                    display: table !important
                }
    
                td.es-desk-menu-hidden {
                    display: table-cell !important
                }
    
                .es-menu td {
                    width: 1% !important
                }
    
                table.es-table-not-adapt,
                .esd-block-html table {
                    width: auto !important
                }
    
                table.es-social {
                    display: inline-block !important
                }
    
                table.es-social td {
                    display: inline-block !important
                }
    
                .es-desk-hidden {
                    display: table-row !important;
                    width: auto !important;
                    overflow: visible !important;
                    max-height: inherit !important
                }
    
                .h-auto {
                    height: auto !important
                }
    
                .es-m-p5 {
                    padding: 5px !important
                }
    
                .es-m-p5t {
                    padding-top: 5px !important
                }
    
                .es-m-p5b {
                    padding-bottom: 5px !important
                }
    
                .es-m-p5r {
                    padding-right: 5px !important
                }
    
                .es-m-p5l {
                    padding-left: 5px !important
                }
    
                .es-m-p10 {
                    padding: 10px !important
                }
    
                .es-m-p10t {
                    padding-top: 10px !important
                }
    
                .es-m-p10b {
                    padding-bottom: 10px !important
                }
    
                .es-m-p10r {
                    padding-right: 10px !important
                }
    
                .es-m-p10l {
                    padding-left: 10px !important
                }
    
                .es-m-p15 {
                    padding: 15px !important
                }
    
                .es-m-p15t {
                    padding-top: 15px !important
                }
    
                .es-m-p15b {
                    padding-bottom: 15px !important
                }
    
                .es-m-p15r {
                    padding-right: 15px !important
                }
    
                .es-m-p15l {
                    padding-left: 15px !important
                }
    
                .es-m-p20 {
                    padding: 20px !important
                }
    
                .es-m-p20t {
                    padding-top: 20px !important
                }
    
                .es-m-p20r {
                    padding-right: 20px !important
                }
    
                .es-m-p20l {
                    padding-left: 20px !important
                }
    
                .es-m-p25 {
                    padding: 25px !important
                }
    
                .es-m-p25t {
                    padding-top: 25px !important
                }
    
                .es-m-p25b {
                    padding-bottom: 25px !important
                }
    
                .es-m-p25r {
                    padding-right: 25px !important
                }
    
                .es-m-p25l {
                    padding-left: 25px !important
                }
    
                .es-m-p30 {
                    padding: 30px !important
                }
    
                .es-m-p30t {
                    padding-top: 30px !important
                }
    
                .es-m-p30b {
                    padding-bottom: 30px !important
                }
    
                .es-m-p30r {
                    padding-right: 30px !important
                }
    
                .es-m-p30l {
                    padding-left: 30px !important
                }
    
                .es-m-p35 {
                    padding: 35px !important
                }
    
                .es-m-p35t {
                    padding-top: 35px !important
                }
    
                .es-m-p35b {
                    padding-bottom: 35px !important
                }
    
                .es-m-p35r {
                    padding-right: 35px !important
                }
    
                .es-m-p35l {
                    padding-left: 35px !important
                }
    
                .es-m-p40 {
                    padding: 40px !important
                }
    
                .es-m-p40t {
                    padding-top: 40px !important
                }
    
                .es-m-p40b {
                    padding-bottom: 40px !important
                }
    
                .es-m-p40r {
                    padding-right: 40px !important
                }
    
                .es-m-p40l {
                    padding-left: 40px !important
                }
            }
        </style>
    </head>
    
    <body
        style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
        <div dir="ltr" class="es-wrapper-color" lang="ES" style="background-color:#FFFFFF">
            <!--[if gte mso 9]><v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t"> <v:fill type="tile" color="#fff"></v:fill> </v:background><![endif]-->
            <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" role="none"
                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#FFFFFF">
                <tr>
                    <td valign="top" style="padding:0;Margin:0">
                        <table class="es-header" cellspacing="0" cellpadding="0" align="center" role="none"
                            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                            <tr>
                                <td align="center" style="padding:0;Margin:0">
                                    <table class="es-header-body" cellspacing="0" cellpadding="0" align="center"
                                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px"
                                        role="none">
                                        <tr>
                                            <td align="left" bgcolor="#631410"
                                                style="Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:30px;background-color:#631410">
                                                <table cellspacing="0" cellpadding="0" width="100%" role="none"
                                                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                    <tr>
                                                        <td class="es-m-p0r" valign="top" align="center"
                                                            style="padding:0;Margin:0;width:560px">
                                                            <table width="100%" cellspacing="0" cellpadding="0"
                                                                role="presentation"
                                                                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                <tr>
                                                                    <td align="center"
                                                                        style="padding:0;Margin:0;padding-bottom:25px;font-size:0px">
                                                                        <a target="_blank"
                                                                            href="https://www.academiaaiapaec.edu.pe/"
                                                                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#516365;font-size:14px"><img
                                                                                src="https://ecfmggf.stripocdn.email/content/guids/CABINET_6bb4331f6d2615b961384575ba5ab832b3a72a41035c22d0986924bf622b5711/images/sin_titulo1_mesa_de_trabajo_1.png"
                                                                                alt="Logo Aiapaec"
                                                                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                                                                title="Logo Aiapaec" width="135"
                                                                                height="47"></a>
                                                                    </td>
                                                                </tr>
                                                                 <tr>
                                                                    <td align="center" class="h-auto" valign="middle"
                                                                        height="67" style="padding:0;Margin:0">
                                                                        <p
                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family: \'Gentona\' , sans-serif;line-height:60px;color:#f5ad4d;font-size:30px">
                                                                            <strong>Reserva de mátricula 2024</strong>
                                                                        </p>
                                                                    </td>
                                                                </tr> 
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table class="es-content" cellspacing="0" cellpadding="0" align="center" role="none"
                            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                            <tr>
                                <td align="center" style="padding:0;Margin:0">
                                    <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#fff"
                                        align="center" role="none"
                                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                                        <tr>
                                            <td align="left" style="padding:0;Margin:0">
                                                <table cellpadding="0" cellspacing="0" width="100%" role="none"
                                                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                    <tr>
                                                        <td align="center" valign="top"
                                                            style="padding:0;Margin:0;width:600px">
                                                            <table cellpadding="0" cellspacing="0" width="100%"
                                                                role="presentation"
                                                                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                <tr>
                                                                    <td align="center"
                                                                        style="padding:0;Margin:0;font-size:0px"><a
                                                                            target="_blank" href="https://viewstripo.email"
                                                                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#631410;font-size:18px"><img
                                                                                class="adapt-img"
                                                                                src="https://ecfmggf.stripocdn.email/content/guids/CABINET_6bb4331f6d2615b961384575ba5ab832b3a72a41035c22d0986924bf622b5711/images/mexterna.png"
                                                                                alt
                                                                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                                                                width="600" height="337"></a></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left"
                                                style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px">
                                                <table width="100%" cellspacing="0" cellpadding="0" role="none"
                                                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                    <tr>
                                                        <td class="es-m-p0r es-m-p20b" valign="top" align="center"
                                                            style="padding:0;Margin:0;width:560px">
                                                            <table width="100%" cellspacing="0" cellpadding="0"
                                                                role="presentation"
                                                                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                <tr>
                                                                    <td align="left" style="padding:0;Margin:0">
                                                                        <h2
                                                                            style="Margin:0;line-height:56px;mso-line-height-rule:exactly;font-family: \'Gentona\', sans-serif;font-size:28px;font-style:normal;font-weight:bold;color:#631410">
                                                                            Estimado(a) '.$arrayData['guardian_name'].'</h2>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" style="padding:0;Margin:0">
                                                                        <p
                                                                            style="Margin:5px;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family: \'Gentona\', 
                                                                            sans-serif;font-weight: 300px;line-height:30px;color:#000000;font-size:15px">
                                                                          
                                                                            Te damos la bienvenida a ti y a tu hijo(a) '.$arrayData['first_name'].' '.$arrayData['last_name'] . ' a COLEGIOS AIAPAEC! Tu Viaje Educativo Comienza Aquí 📚✨  

                                                                            ¡Saludos cordiales desde COLEGIOS AIAPAEC! Estamos emocionados de recibirte en nuestro proceso de admisión y queremos darte una cálida bienvenida a nuestra familia educativa.

                                                                            En COLEGIOS AIAPAEC, nos enorgullece brindar una educación de calidad que fomente el desarrollo integral de cada estudiante. Creemos en la excelencia académica, el crecimiento personal y la construcción de una comunidad comprometida con el aprendizaje y la innovación.
                                                                            
                                                                            Aquí, encontrarás profesionales apasionados y dedicados, instalaciones modernas y programas educativos diseñados para desafiar y inspirar. En COLEGIOS AIAPAEC, no solo adquirirás conocimientos, sino que también cultivarás habilidades que te prepararán para enfrentar los desafíos del futuro.
                                                                            
                                                                            Si tienes alguna pregunta o necesitas asistencia, no dudes en ponerte en contacto con nuestras asesoras en '. $numeroSecretaria.' .
                                                                            
                                                                            Te animamos a explorar nuestro sitio web y descubrir más sobre COLEGIOS AIAPAEC: https://colegioaiapaec.edu.pe.
                                                                            
                                                                            Estamos ansiosos por conocerte y ser parte de tu viaje educativo. ¡Bienvenido a COLEGIOS AIAPAEC, donde el aprendizaje se transforma en una experiencia inolvidable!
                                                                            
                                                                            Atentamente,
                                                                           
                                                                        </p>
                                                                                
                                                                                
                                                                            
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                      
                     
                        <table cellpadding="0" cellspacing="0" class="es-footer" align="center" role="none"
                            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                            <tr>
                                <td align="center" style="padding:0;Margin:0">
                                    <table bgcolor="#3c2c4c" class="es-footer-body" align="center" cellpadding="0"
                                        cellspacing="0" role="none"
                                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#631410;width:600px">
                                        <tr>
                                            <td align="left"
                                                style="Margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;padding-top:40px">
                                                <table cellpadding="0" cellspacing="0" width="100%" role="none"
                                                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                    <tr>
                                                        <td align="left" style="padding:0;Margin:0;width:560px">
                                                            <table cellpadding="0" cellspacing="0" width="100%"
                                                                role="presentation"
                                                                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                <tr>
                                                                    <td style="padding:0;Margin:0">
                                                                        <table cellpadding="0" cellspacing="0" width="100%"
                                                                            class="es-menu" role="presentation"
                                                                            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tr class="links">
                                                                                <td align="center" valign="top" width="25%"
                                                                                    style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0">
                                                                                    <a target="_blank"
                                                                                        href="https://colegioaiapaec.edu.pe/"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family: \'Gentona\', sans-serif;color:#f5ad4d;font-size:14px;font-weight:bold">Inicio</a>
                                                                                </td>
                                                                                <td align="center" valign="top" width="25%"
                                                                                    style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0">
                                                                                    <a target="_blank"
                                                                                        href="https://colegioaiapaec.edu.pe/nosotros/"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family: \'Gentona\', sans-serif;color:#f5ad4d;font-size:14px;font-weight:bold">Nosotros</a>
                                                                                </td>
                                                                                <td align="center" valign="top" width="25%"
                                                                                    style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0">
                                                                                    <a target="_blank"
                                                                                        href="https://colegioaiapaec.edu.pe/admision/"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family: \'Gentona\', sans-serif;color:#f5ad4d;font-size:14px;font-weight:bold">Admision</a>
                                                                                </td>
                                                                                <td align="center" valign="top" width="25%"
                                                                                    style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0">
                                                                                    <a target="_blank"
                                                                                        href="https://colegioaiapaec.edu.pe/metodologia/"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family: \'Gentona\', sans-serif;color:#f5ad4d;font-size:14px;font-weight:bold">Propuesta Educativa</a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"
                                                                        style="padding:0;Margin:0;padding-top:20px;padding-bottom:20px;font-size:0">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            class="es-table-not-adapt es-social"
                                                                            role="presentation"
                                                                            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tr>
                                                                                <td align="center" valign="top"
                                                                                    style="padding:0;Margin:0;padding-right:20px">
                                                                                    <a target="_blank"
                                                                                        href="https://www.facebook.com/academia.aiapaec/"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#FFFFFF;font-size:14px"><img
                                                                                            title="Facebook"
                                                                                            src="https://ecfmggf.stripocdn.email/content/assets/img/social-icons/rounded-colored/facebook-rounded-colored.png"
                                                                                            alt="Fb" width="24" height="24"
                                                                                            style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a>
                                                                                </td>
                                                                                <td align="center" valign="top"
                                                                                    style="padding:0;Margin:0;padding-right:20px">
                                                                                    <a target="_blank"
                                                                                        href="https://www.instagram.com/academia.aiapaec/"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#FFFFFF;font-size:14px"><img
                                                                                            title="Instagram"
                                                                                            src="https://ecfmggf.stripocdn.email/content/assets/img/social-icons/rounded-colored/instagram-rounded-colored.png"
                                                                                            alt="Inst" width="24"
                                                                                            height="24"
                                                                                            style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a>
                                                                                </td>
                                                                                <td align="center" valign="top"
                                                                                    style="padding:0;Margin:0;padding-right:20px">
                                                                                    <a target="_blank"
                                                                                        href="https://youtube.com/@academia.aiapaec?si=zgGb4ZGArKFfJBnE"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#FFFFFF;font-size:14px"><img
                                                                                            title="Youtube"
                                                                                            src="https://ecfmggf.stripocdn.email/content/assets/img/social-icons/rounded-colored/youtube-rounded-colored.png"
                                                                                            alt="Yt" width="24" height="24"
                                                                                            style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a>
                                                                                </td>
                                                                                <td align="center" valign="top"
                                                                                    style="padding:0;Margin:0"><a
                                                                                        target="_blank"
                                                                                        href="https://www.tiktok.com/@academia.aiapaec"
                                                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#FFFFFF;font-size:14px"><img
                                                                                            title="TikTok"
                                                                                            src="https://ecfmggf.stripocdn.email/content/assets/img/social-icons/rounded-colored/tiktok-rounded-colored.png"
                                                                                            alt="Tt" width="24" height="24"
                                                                                            style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"
                                                                        style="padding:0;Margin:0;padding-top:10px">
                                                                        <p
                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family: \'Gentona\', sans-serif;line-height:21px;color:#ffffff;font-size:14px">
                                                                            <span
                                                                                style="font-family: \'Gentona\', sans-serif">Está
                                                                                recibiendo este correo electrónico porque
                                                                                visitó nuestro sitio o nos preguntó sobre el
                                                                                boletín informativo habitual. Asegúrese de
                                                                                que nuestros mensajes lleguen a su bandeja
                                                                                de entrada (y no a sus carpetas masivas o no
                                                                                deseadas).</span><br><strong><a
                                                                                    target="_blank"
                                                                                    href="https://www.academiaaiapaec.edu.pe/?c=politica"
                                                                                    style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#f5ad4d;font-size:14px">Politícas
                                                                                    de Privacidad</a>
                                                                                | <a target="_blank"
                                                                                    style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#f5ad4d;font-size:14px"
                                                                                    href="https://www.academiaaiapaec.edu.pe/?c=contacto">Contáctanos</a></strong>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none"
                            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                            <tr>
                                <td align="center" style="padding:0;Margin:0">
                                    <table class="es-content-body" align="center" cellpadding="0" cellspacing="0"
                                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px"
                                        bgcolor="#fff" role="none">
                                        <tr>
                                            <td align="left" bgcolor="#631410"
                                                style="padding:20px;Margin:0;background-color:#631410;border-radius:0px 0px 20px 20px">
                                                <table cellpadding="0" cellspacing="0" width="100%" role="none"
                                                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                    <tr>
                                                        <td align="left" style="padding:0;Margin:0;width:560px">
                                                            <table cellpadding="0" cellspacing="0" width="100%"
                                                                role="presentation"
                                                                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                <tr>
                                                                    <td align="center" class="es-infoblock made_with"
                                                                        style="padding:0;Margin:0;line-height:14px;font-size:0px;color:#FFFFFF">
                                                                        <a target="_blank"
                                                                            href="https://www.academiaaiapaec.edu.pe/"
                                                                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#FFFFFF;font-size:12px"><img
                                                                                src="https://ecfmggf.stripocdn.email/content/guids/CABINET_6bb4331f6d2615b961384575ba5ab832b3a72a41035c22d0986924bf622b5711/images/790332b6b036454986880dd35cc990b7.png"
                                                                                alt width="125"
                                                                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                                                                height="44"></a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
    </html>';
        
     
       
        $mail->send();
    }







    public function admission()
    {
        if (!$this->data['cms_setting']['online_admission']) {
            redirect(site_url('home'));
        }
        $branchID = $this->home_model->getDefaultBranch();
        $captcha = $this->data['cms_setting']['captcha_status'];
        if ($captcha == 'enable') {
            $this->load->library('recaptcha');
            $this->data['recaptcha'] = array(
                'widget' => $this->recaptcha->getWidget(),
                'script' => $this->recaptcha->getScriptTag(),
            );
        }
        if ($_POST) {
            $this->form_validation->set_rules("first_name", "First Name", "trim|required");

            $this->form_validation->set_rules("guardian_document_type", "Type document", "trim|required");
            $this->form_validation->set_rules("guardian_document", " Document", "trim|required");

            $this->form_validation->set_rules("class_id", "Class", "trim|required");
            $this->form_validation->set_rules("guardian_photo", "Guardian Photo", "callback_handle_upload[guardian_photo]");
            $this->form_validation->set_rules("student_photo", "Student Photo", "callback_handle_upload[student_photo]");

            $validationArr = $this->student_fields_model->getOnlineStatusArr($branchID);
            unset($validationArr[0]);
            foreach ($validationArr as $key => $value) {
                if ($value->status && $value->required) {
                    if ($value->prefix == 'student_email' || $value->prefix == 'guardian_email') {
                        $this->form_validation->set_rules("$value->prefix", "Email", 'trim|required|valid_email');
                    } else if($value->prefix == 'student_mobile_no' || $value->prefix == 'guardian_mobile_no') {
                        $this->form_validation->set_rules("$value->prefix", "Mobile No", 'trim|required|numeric');
                    } else if($value->prefix == 'student_photo' || $value->prefix == 'guardian_photo' || $value->prefix == 'upload_documents') {
                        if (isset($_FILES["$value->prefix"]) && empty($_FILES["$value->prefix"]['name'])) {
                            $this->form_validation->set_rules("$value->prefix", ucwords(str_replace('_', ' ', $value->prefix)), "required" );
                        }
                    } else if($value->prefix == 'previous_school_details') {
                        $this->form_validation->set_rules("school_name", "School Name", "trim|required" );
                        $this->form_validation->set_rules("qualification", "Qualification", "trim|required" );
                    } else {
                        $this->form_validation->set_rules($value->prefix, ucwords(str_replace('_', ' ', $value->prefix)), 'trim|required');
                    }
                }  
            }

            if ($captcha == 'enable') {
                $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|required');
            }
            // custom fields validation rules
            $customFields = getOnlineCustomFields('student', $branchID);
            foreach ($customFields as $fields_key => $fields_value) {
                if ($fields_value['required']) {
                    $fieldsID = $fields_value['id'];
                    $fieldLabel = $fields_value['field_label'];
                    $this->form_validation->set_rules("custom_fields[student][" . $fieldsID . "]", $fieldLabel, 'trim|required');
                }
            }

            if ($this->form_validation->run() == true) {
                $admissionDate = !empty($_POST['admission_date']) ? date("Y-m-d", strtotime($this->input->post('admission_date'))) : "";
                $birthday = !empty($_POST['birthday']) ? date("Y-m-d", strtotime($this->input->post('birthday'))) : "";
                
                $previous_details = $this->input->post('school_name');
                if (!empty($previous_details)) {
                    $previous_details = array(
                        'school_name' => $this->input->post('school_name'),
                        'qualification' => $this->input->post('qualification'),
                        'remarks' => $this->input->post('previous_remarks'),
                    );
                    $previous_details =  json_encode($previous_details);
                } else {
                    $previous_details = "";
                }

                $arrayData = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'gender' => $this->input->post('gender'),
                    'birthday' => $birthday,
                    'admission_date' => $admissionDate,
                    'religion' => $this->input->post('religion'),
                    'caste' => $this->input->post('caste'),
                    'blood_group' => $this->input->post('blood_group'),
                    'mobile_no' => $this->input->post('student_mobile_no'),
                    'mother_tongue' => $this->input->post('mother_tongue'),
                    'present_address' => $this->input->post('present_address'),
                    'permanent_address' => $this->input->post('permanent_address'),
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'category_id' => $this->input->post('category'),
                    'email' => $this->input->post('student_email'),
                    'student_photo' => $this->uploadImage('images/student', 'student_photo'),
                    'previous_school_details' => $previous_details,
                    'guardian_name' => $this->input->post('guardian_name'),
                    'guardian_relation' => $this->input->post('guardian_relation'),
                    'father_name' => $this->input->post('father_name'),
                    'mother_name' => $this->input->post('mother_name'),
                    'grd_occupation' => $this->input->post('guardian_occupation'),
                    'grd_income' => $this->input->post('guardian_income'),
                    'grd_education' => $this->input->post('guardian_education'),
                    'grd_email' => $this->input->post('guardian_email'),
                    'grd_mobile_no' => $this->input->post('guardian_mobile_no'),
                    'grd_address' => $this->input->post('guardian_address'),
                    'grd_city' => $this->input->post('guardian_city'),
                    'grd_state' => $this->input->post('guardian_state'),
                    'grd_photo' => $this->uploadImage('images/parent', 'guardian_photo'),
                    'status' => 1,
                    'branch_id' => $branchID,
                    'class_id' => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section'),
                    'doc' => $this->uploadImage('online_ad_documents', 'upload_documents'),
                    'apply_date' => date("Y-m-d H:i:s"),
                    'created_date' => date("Y-m-d H:i:s"),
                    'dni_parent ' => $this->input->post('guardian_document'),

                );

                $class=$this->input->post('class_id');
                $this->db->where('branch_id', $branchID);
                $query = $this->db->get('front_cms_admission');
                $data['fee_elements']= json_decode($query->row()->fee_elements, true);

                if ($data['fee_elements'][$class]['vacantes'] > 0) {


                
                $this->db->insert('online_admission', $arrayData);
                $studentID = $this->db->insert_id();


               
                $data['fee_elements'][$class]['vacantes'] = $data['fee_elements'][$class]['vacantes']-1;
                
                $updated_fee_elements = json_encode($data['fee_elements']);
                $this->db->set('fee_elements', $updated_fee_elements);
                $this->db->where('branch_id', $branchID);
                $this->db->update('front_cms_admission');

                $this->mailSend($arrayData);

            } else {
                $array = array('status' => 'error', 'message' => 'No quedan vacantes para este grado');
                set_alert('error', 'No quedan vacantes para este grado');

                echo json_encode($array);
                exit();
            }


                // handle custom fields data
                $class_slug = 'student';
                $customField = $this->input->post("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFieldsOnline($customField, $studentID);
                }
                // check out admission payment status
                $this->load->model('admissionpayment_model');
                $getStudent = $this->admissionpayment_model->getStudentDetails($studentID);
                if ($getStudent['fee_elements']['status'] == 0) {
                    $url = base_url("home/admission_confirmation/" . $studentID);
                   
                   if (empty($arrayData['section_id'])) {
                       $section_name = "N/A";
                   } else {
                       $section_name = get_type_name_by_id('section', $arrayData['section_id']);
                   }
                   // applicant email send 
                    $arrayData['institute_name'] = get_type_name_by_id('branch', $arrayData['branch_id']);
                    $arrayData['admission_id'] = $studentID;
                    $arrayData['student_name'] = $arrayData['first_name'] . " " . $arrayData['last_name'];
                    $arrayData['class_name'] = get_type_name_by_id('class', $arrayData['class_id']);
                    $arrayData['section_name'] = $section_name;
                    $arrayData['payment_url'] = "";
                    $arrayData['admission_copy_url'] = $url;
                    $arrayData['paid_amount'] = 0;
                    $this->email_model->onlineAdmission($arrayData);
                    
                    $success = "Thank you for submitting the online registration form. Please you can print this copy.";
                    $this->session->set_flashdata('success', $success);
                } else {
                    $url = base_url("admissionpayment/index/" . $studentID);
                }
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }


            echo json_encode($array);
            exit();
        }

        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_admission', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/admission', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function handle_upload($str, $fields)
    {
        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
            $file_size = $_FILES["$fields"]["size"];
            $file_name = $_FILES["$fields"]["name"];
            $allowedExts = array('jpg', 'jpeg', 'png');
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($files = filesize($_FILES["$fields"]['tmp_name'])) {
                if (!in_array(strtolower($extension), $allowedExts)) {
                    $this->form_validation->set_message('handle_upload', translate('this_file_type_is_not_allowed'));
                    return false;
                }
                if ($file_size > 2097152) {
                    $this->form_validation->set_message('handle_upload', translate('file_size_shoud_be_less_than') . " 2048KB.");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', translate('error_reading_the_file'));
                return false;
            }
            return true;
        }
    }

    public function uploadImage($role, $fields) {
        $return_photo = '';
        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
            $config['upload_path'] = './uploads/' . $role . '/';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            if ($this->upload->do_upload("$fields")) {
                $return_photo = $this->upload->data('file_name');
            }
        }
        return $return_photo;
    }

    public function admission_confirmation($studentID = '')
    {
        $this->load->model('admissionpayment_model');
        $getStudent = $this->admissionpayment_model->getStudentDetails($studentID);
        if (empty($getStudent['id'])) {
            set_alert('error', "This application was not found.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->data['student'] = $getStudent;
        $this->data['page_data'] = $this->home_model->get('front_cms_admission', array('branch_id' => $this->data['student']['branch_id']), true);
        $this->data['main_contents'] = $this->load->view('home/admission_confirmation', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function contact()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $captcha = $this->data['cms_setting']['captcha_status'];
        if ($captcha == 'enable') {
            $this->load->library('recaptcha');
            $this->data['recaptcha'] = array(
                'widget' => $this->recaptcha->getWidget(),
                'script' => $this->recaptcha->getScriptTag(),
            );
        }

        if ($_POST) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('phoneno', 'Phone', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            if ($captcha == 'enable') {
                $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|required');
            }
            if ($this->form_validation->run() !== false) {
                if ($captcha == 'enable') {
                    $captchaResponse = $this->recaptcha->verifyResponse($this->input->post('g-recaptcha-response'));
                } else {
                    $captchaResponse = array('success' => true);
                }
                if ($captchaResponse['success'] == true) {
                    $name = $this->input->post('name');
                    $email = $this->input->post('email');
                    $phoneno = $this->input->post('phoneno');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');
                    $msg = '<h3>Sender Information</h3>';
                    $msg .= '<br><br><b>Name: </b> ' . $name;
                    $msg .= '<br><br><b>Email: </b> ' . $email;
                    $msg .= '<br><br><b>Phone: </b> ' . $phoneno;
                    $msg .= '<br><br><b>Subject: </b> ' . $subject;
                    $msg .= '<br><br><b>Message: </b> ' . $message;
                    $data = array(
                        'branch_id' => $branchID,
                        'recipient' => $this->data['cms_setting']['receive_contact_email'],
                        'subject' => 'Contact Form Email',
                        'message' => $msg,
                    );
                    if ($this->mailer->send($data)) {
                        $this->session->set_flashdata('msg_success', 'Message Successfully Sent. We will contact you shortly.');
                    } else {
                        $this->session->set_flashdata('msg_error', $this->email->print_debugger());
                    }
                } else {
                    $error = 'Captcha is invalid';
                    $this->session->set_flashdata('error', $error);
                }
                redirect(base_url('home/contact'));
            }
        }
        $this->data['page_data'] = $this->home_model->get('front_cms_contact', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/contact', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function admit_card()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_admitcard', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/admit_card', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function admitCardprintFn()
    {
        if ($_POST) {
            $this->load->model('card_manage_model');
            $this->load->model('timetable_model');
            $this->load->library('ciqrcode', array('cacheable' => false));
            $this->form_validation->set_rules('exam_id', translate('exam'), 'trim|required');
            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required');
            if ($this->form_validation->run() == true) {
                //get all QR Code file
                $files = glob('uploads/qr_code/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file); //delete file
                    }
                }
                $registerNo = $this->input->post('register_no');
                $userID = $this->db->select('id')->where('register_no', $registerNo)->get('student')->row_array();
                if (empty($userID)) {
                    $array = array('status' => '0', 'error' => "Register No Not Found.");
                    echo json_encode($array);
                    exit();
                }
                $templateID = $this->input->post('templete_id');
                if (empty($templateID) || $templateID == 0) {
                    $array = array('status' => '0', 'error' => "No Default Template Set.");
                    echo json_encode($array);
                    exit();
                }
                $this->data['exam_id'] = $this->input->post('exam_id');
                $this->data['userID'] = $userID;
                $this->data['template'] = $this->card_manage_model->get('card_templete', array('id' => $templateID), true);
                $this->data['print_date'] = date('Y-m-d');
                $card_data = $this->load->view('home/admitCardprintFn', $this->data, true);
                $array = array('status' => 'success', 'card_data' => $card_data);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function exam_results()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_exam_results', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/exam_results', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function examResultsPrintFn()
    {
        $this->load->model('exam_model');
        if ($_POST) {
            $this->form_validation->set_rules('exam_id', translate('exam'), 'trim|required');
            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required');
            $this->form_validation->set_rules('session_id', translate('academic_year'), 'trim|required');
            if ($this->form_validation->run() == true) {
                $sessionID = $this->input->post('session_id');
                $registerNo = $this->input->post('register_no');
                $examID = $this->input->post('exam_id');
                $userID = $this->db->select('id')->where('register_no', $registerNo)->get('student')->row_array();
                if (empty($userID)) {
                    $array = array('status' => '0', 'error' => "Register No Not Found.");
                    echo json_encode($array);
                    exit();
                }
                $result = $this->exam_model->getStudentReportCard($userID['id'], $examID, $sessionID);
                if (empty($result['exam'])) {
                    $array = array('status' => '0', 'error' => "Exam Results Not Found.");
                    echo json_encode($array);
                    exit();
                }
                $this->data['result'] = $result;
                $this->data['sessionID'] = $sessionID;
                $this->data['userID'] = $userID['id'];
                $this->data['examID'] = $examID;
                $this->data['grade_scale'] = $this->input->post('grade_scale');
                $this->data['attendance'] = $this->input->post('attendance');
                $this->data['print_date'] = date('Y-m-d');
                $card_data = $this->load->view('home/reportCard', $this->data, true);
                $array = array('status' => 'success', 'card_data' => $card_data);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function certificates()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_certificates', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/certificates', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function certificatesPrintFn()
    {
        if ($_POST) {
            $this->load->model('certificate_model');
            $this->load->library('ciqrcode', array('cacheable' => false));
            //get all QR Code file
            $files = glob('uploads/qr_code/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); //delete file
                }
            }

            $this->form_validation->set_rules('templete_id', translate('certificate'), 'trim|required');
            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required');
            if ($this->form_validation->run() == true) {

                $registerNo = $this->input->post('register_no');
                $examID = $this->input->post('exam_id');
                $userID = $this->db->select('id')->where('register_no', $registerNo)->get('student')->row_array();
                if (empty($userID)) {
                    $array = array('status' => '0', 'error' => "Register No Not Found.");
                    echo json_encode($array);
                    exit();
                }

                $this->data['user_type'] = 1;
                $templateID = $this->input->post('templete_id');
                $this->data['template'] = $this->certificate_model->get('certificates_templete', array('id' => $templateID), true);
                $this->data['userID'] = $userID['id'];
                $this->data['print_date'] = date('Y-m-d');
                $card_data = $this->load->view('home/certificatesPrintFn', $this->data, true);
                $array = array('status' => 'success', 'card_data' => $card_data);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function gallery()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_gallery', array('branch_id' => $branchID), true);
        $this->data['category'] = $this->home_model->getGalleryCategory($branchID);
        $this->data['galleryList'] = $this->home_model->getGalleryList($branchID);
        $this->data['main_contents'] = $this->load->view('home/gallery', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function gallery_view($alias = '')
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_gallery', array('branch_id' => $branchID), true);
        $this->data['gallery'] = $this->home_model->get('front_cms_gallery_content', array('branch_id' => $branchID, 'alias' => $alias), true);
        $this->data['category'] = $this->home_model->getGalleryCategory($branchID);
        $this->data['galleryList'] = $this->home_model->getGalleryList($branchID);
        $this->data['main_contents'] = $this->load->view('home/gallery_view', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function page($url = '')
    {
        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.alias,front_cms_pages.*');
        $this->db->from('front_cms_menu');
        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'inner');
        $this->db->where('front_cms_menu.alias', $url);
        $this->db->where('front_cms_menu.publish', 1);
        $getData = $this->db->get()->row_array();
        if (empty($getData)) {
            redirect('404_override');
        }
        $this->data['page_data'] = $getData;
        $this->data['active_menu'] = 'page';
        $this->data['main_contents'] = $this->load->view('home/page', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function getSectionByClass()
    {
        $html = "";
        $classID = $this->input->post("class_id");
        if (!empty($classID)) {
            $result = $this->db->select('sections_allocation.section_id,section.name')
                ->from('sections_allocation')
                ->join('section', 'section.id = sections_allocation.section_id', 'left')
                ->where('sections_allocation.class_id', $classID)
                ->get()->result_array();
            if (is_array($result) && count($result)) {
                $html .= '<option value="">' . translate('select') . '</option>';
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_selection_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_class_first') . '</option>';
        }
        echo $html;
    }

    public function get_branch_url()
    {
        $branch_id = $this->input->post("branch_id");
        $url = $this->db->where('branch_id', $branch_id)->get('front_cms_setting')->row_array();
        $school = "";
        if ($this->uri->segment(4)) {
            $school = $this->uri->segment(4);
        } else {
            $school = $this->uri->segment(3);
        }
        echo json_encode(array('url_alias' => base_url("home/index/" . $url['url_alias'])));
    }

}

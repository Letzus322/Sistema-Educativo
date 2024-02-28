<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH.'third_party/PHPMailder/Exception.php';
require_once APPPATH.'third_party/PHPMailder/PHPMailer.php';
require_once APPPATH.'third_party/PHPMailder/SMTP.php';
require_once APPPATH.'third_party/TCPDF/tcpdf.php';




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class Reservation extends Admin_Controller
{
    public function dniApoderado($apoderadoID){
        $this->db->select('*');
        $this->db->from('login_credential');
        $this->db->where('login_credential.role', 6);
        $this->db->where('login_credential.user_id', $apoderadoID);
        $query = $this->db->get();

        return $query->row_array();
    }
    public function directorData($branchID){
        $this->db->select('*');
        $this->db->from('staff_designation sd');
        $this->db->join('staff s', 'sd.id = s.designation');
        $this->db->where("(sd.name = 'DIRECTOR (a)' OR sd.name = 'SUBDIRECTOR (a)')");
        $this->db->where('s.branch_id' , $branchID );

        $query = $this->db->get();
        return $query->row_array();
    }
    public function datosEstudiante($estudianteID){

        $this->db->select('class.name AS class_name, branch.school_name AS branch_name, section.name AS section_name, branch.id AS branchId');
        $this->db->from('enroll');
        $this->db->join('branch', 'branch.id = enroll.branch_id');
        $this->db->join('student', 'student.id = enroll.student_id');
        $this->db->join('section', 'section.id = enroll.section_id');
        $this->db->join('class', 'class.id = enroll.class_id');
        $this->db->where('student.id', $estudianteID);

        $query = $this->db->get();
        return $query->row_array();
    }
    public function removeFirstWord($str) {
        $words = explode(' ', $str, 2); // Divide la cadena en un array, máximo 2 elementos
        if(count($words) > 1) {
            return $words[1]; // Retorna la cadena sin la primera palabra
        }
        return ''; // Retorna una cadena vacía si solo hay una palabra
    }
    
    public function mailSend( $userID= ''){

        require_once APPPATH.'third_party/fpdf/fpdf.php';

        $estudiante = $this->student_model->getSingleStudent($userID);

        $padre = $this->parents_model->getSingleParent($estudiante['parent_id']);

        $dni = $this->dniApoderado($padre['id']);
        $datosEstudiante = $this ->datosEstudiante($userID);

        $datosDirector =$this -> directorData($datosEstudiante['branchId']);
        $pdf = new FPDF('L', 'mm', 'A4');

        // Cambiar los márgenes
        $pdf->SetLeftMargin(30);    // Márgen izquierdo de 30 mm
        $pdf->SetRightMargin(30);   // Márgen derecho de 30 mm
        $pdf->SetTopMargin(25);     // Márgen superior de 20 mm
        
        $pdf->AddPage('P','A4',0);
        $pdf->SetFont('Arial', '', 11);
    
        $imagePath = APPPATH.'logo.png';
        $pdf->Image($imagePath, 30, 25, 30, 0, 'PNG');
         $texto = "                                                                    Solicito: RESERVA DE VACANTE";
         $anio_texto = "                                                                    PARA AÑO ESCOLAR 2024";
    
    
         
         $texto2 = "Señor(a) ";
         $texto3 =  $datosDirector['name']." de la I.E AIAPAEC";
         $texto4 = "SEDE ".$this->removeFirstWord (strtoupper($datosEstudiante['branch_name']));
         $texto5 = "Yo, ". $padre['name']. ", identificado con DNI ".$dni['username'] ." con domicilio en ".$padre['address']."  apoderado(a) de mi menor hijo(a) ". $estudiante['first_name']." " .$estudiante['last_name'] .", estudiante que cursa ". $datosEstudiante['class_name']." en la sede ". $this->removeFirstWord (strtoupper($datosEstudiante['branch_name'])) ." me dirijo a usted con el debido respeto y expongo:";
         
         $texto6 ="Siguiendo el cronograma establecido por EL COLEGIO";
         $tabla="TABLA -----------------";
         $pdf->Image( APPPATH.'tabla.png', 8, 143, 200, 0, 'PNG');

    
         $texto7="SOLICITO: Reservar vacante de la matrícula 2024 a favor de mi menor hijo(a) para que continúe sus estudios y me comprometo a efectuar la ratificación de la misma en los plazos establecidos.";
         $texto8 ="DECLARO: Conocer, que al momento de reserva de vacante y ratificar la matrícula para el año escolar 2024 procederá únicamente si yo me encuentro al día en el cumplimiento del pago de las pensiones de enseñanza u otros conceptos correspondientes al año escolar 2021.";
         $texto9 ="DECLARO: Conocer que para la ratificación de matrícula en el grado correspondiente al año escolar 2024, depende de que mi menor hijo haya sido promovido de grado en el año 2023, según la normativa vigente establecida por el Ministerio de Educación. No se puede asegurar la vacante del estudiante en el año de repitencia por lo cual no existirá devolución ni reembolso de ningún concepto en caso esto ocurra. ";
         $texto10="ACEPTO: Que, si mi menor hijo presenta nota desaprobatoria en conducta en EL COLEGIO, documentado a través de la libreta de notas o el acta de notas de fin de año, podrá ser admitido previa firma de un compromiso de conducta con EL COLEGIO, caso contrario se retirará la reserva de vacante y la ratificación de matrícula para el año escolar 2024 y no existirá devolución ni reembolso de ningún concepto en caso esto ocurra.";
         
         $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto)); // Codifica el texto a UTF-8
         $pdf->Ln(); // Salto de línea después del segundo bloque
    
         $pdf->Write(10, iconv('UTF-8', 'windows-1252', $anio_texto)); // Codifica el texto del año a UTF-8
         $pdf->Ln(); // Salto de línea después del segundo bloque
         $pdf->Ln(); // Salto de línea después del segundo bloque
         
         $pdf->Ln(); // Salto de línea después del segundo bloque
    
    
         $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto2)); // Codifica el texto del año a UTF-8
         $pdf->Ln(); // Salto de línea después del segundo bloque
    
         $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto3)); // Codifica el texto del año a UTF-8
         $pdf->Ln(); // Salto de línea después del segundo bloque
    
         $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto4)); // Codifica el texto del año a UTF-8
         $pdf->Ln(); // Salto de línea después del segundo bloque
         $pdf->Ln(); // Salto de línea después del segundo bloque
         
         $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto5)); // Codifica el texto del año a UTF-8
         $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Ln(); // Salto de línea después del segundo bloque
    
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto7)); // Codifica el texto del año a UTF-8
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto8)); // Codifica el texto del año a UTF-8
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto9)); // Codifica el texto del año a UTF-8
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto10)); // Codifica el texto del año a UTF-8
        $pdf->Ln(); // Salto de línea después del segundo bloque
        $pdf->SetTitle(utf8_decode('Solicitud de reserva de vacante de alumano(a) '.$estudiante['first_name']." " .$estudiante['last_name'] ));
    
        $pdfContent = $pdf->Output('', 'S');

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
        $mail->addAddress($padre['email'], '');     //Add a recipient
        
        //$mail->addAddress('omarcortijocanaza@gmail.com', '');     //Add a recipient

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        $mail->addStringAttachment($pdfContent, utf8_decode('solicitud_reserva_vacante_'.$estudiante['first_name']."_" .$estudiante['last_name']).'.pdf', 'base64', 'application/pdf');
        //Content

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Confirmacion de reserva de matrícula';
        $imagePath = APPPATH . 'logo.png';
        // Obtener el contenido de la imagen como base64
      
        $mail->Body = '<!DOCTYPE html
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
                                                                                src="https://ecfmggf.stripocdn.email/content/guids/CABINET_6bb4331f6d2615b961384575ba5ab832b3a72a41035c22d0986924bf622b5711/images/minterna.png"
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
                                                                            Estimado(a) '. $padre['name'].'</h2>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" style="padding:0;Margin:0">
                                                                        <p
                                                                            style="Margin:5px;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family: \'Gentona\', 
                                                                            sans-serif;font-weight: 300px;line-height:30px;color:#000000;font-size:15px">
                                                                          
                                                                       
                                                                           Le escribimos para confirmar que hemos recibido su solicitud de reserva de matrícula para su hijo/a '.$estudiante['first_name']." " .$estudiante['last_name'].'.                                                                       <br>
                                                                           Apreciamos tu entusiasmo por ser parte de nuestra Institución. Queremos recordarte algunos detalles importantes:<br>
                                                                    
                                                                           
                                                                           <strong> Detalles de la Reserva:</strong>
                                                                            <br>
                                                                            
                                                                            <li><strong>Nombre del Estudiante:</strong> '.$estudiante['first_name']." " .$estudiante['last_name'].'</li>
                                                                            <li><strong>Año Escolar:</strong> 2024</li>
                                                                           
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

    public function pdf()
{       
   
    

     require_once APPPATH.'third_party/fpdf/fpdf.php';
    
     if (!class_exists('FPDF')) {
        die('Error: No se pudo cargar la clase FPDF');
    } else {
        if(!($this->input->get('parametro'))){
        $userID = get_loggedin_user_id();}
        else {
            
            $dni = $this->input->get('parametro');
            $this->db->select('*');
            $this->db->from('login_credential');
            $this->db->join('student', 'student.id = login_credential.user_id');
            $this->db->where('username',$dni);
            $query = $this->db->get();
            $result = $query->row();
            $userID = $result->user_id;        }

        $estudiante = $this->student_model->getSingleStudent($userID);

        $padre = $this->parents_model->getSingleParent($estudiante['parent_id']);

        $dni = $this->dniApoderado($padre['id']);
        $datosEstudiante = $this ->datosEstudiante($userID);

        $datosDirector =$this -> directorData($datosEstudiante['branchId']);
    $pdf = new FPDF('L', 'mm', 'A4');

    // Cambiar los márgenes
    $pdf->SetLeftMargin(30);    // Márgen izquierdo de 30 mm
    $pdf->SetRightMargin(30);   // Márgen derecho de 30 mm
    $pdf->SetTopMargin(25);     // Márgen superior de 20 mm
    
    $pdf->AddPage('P','A4',0);
    $pdf->SetFont('Arial', '', 11);

    $imagePath = APPPATH.'logo.png';
    $pdf->Image($imagePath, 30, 25, 30, 0, 'PNG');
     $texto = "                                                                    Solicito: RESERVA DE VACANTE";
     $anio_texto = "                                                                    PARA AÑO ESCOLAR 2024";


     
     $texto2 = "Señor(a) ";
     $texto3 =  $datosDirector['name']." de la I.E AIAPAEC";
     $texto4 = "SEDE ".$this->removeFirstWord (strtoupper($datosEstudiante['branch_name']));
     $texto5 = "Yo, ". $padre['name']. ", identificado con DNI ".$dni['username'] ." con domicilio en ".$padre['address']."  apoderado(a) de mi menor hijo ". $estudiante['first_name']." " .$estudiante['last_name'] .", estudiante que cursa ". $datosEstudiante['class_name']." en la sede ". $this->removeFirstWord (strtoupper($datosEstudiante['branch_name'])) ." me dirijo a usted con el debido respeto y expongo:";
     $texto6 ="Siguiendo el cronograma establecido por EL COLEGIO";
     $tabla="TABLA -----------------";
     $pdf->Image( APPPATH.'tabla.png', 8, 143, 200, 0, 'PNG');


     $texto7="SOLICITO:";
     $texto8=" Reservar vacante de la matrícula 2024 a favor de mi menor hijo(a) para que continúe sus estudios y me comprometo a efectuar la ratificación de la misma en los plazos establecidos.";
     $texto9 ="DECLARO:";
     $texto10 =" Conocer, que al momento de reserva de vacante y ratificar la matrícula para el año escolar 2024 procederá únicamente si yo me encuentro al día en el cumplimiento del pago de las pensiones de enseñanza u otros conceptos correspondientes al año escolar 2023.";
     $texto11 ="DECLARO: ";
     $texto12="Conocer que para la ratificación de matrícula en el grado correspondiente al año escolar 2024, depende de que mi menor hijo haya sido promovido de grado en el año 2023, según la normativa vigente establecida por el Ministerio de Educación. No se puede asegurar la vacante del estudiante en el año de repitencia por lo cual no existirá devolución ni reembolso de ningún concepto en caso esto ocurra. ";
     $texto13="ACEPTO: ";
     $texto14="Que, si mi menor hijo presenta nota desaprobatoria en conducta en EL COLEGIO, documentado a través de la libreta de notas o el acta de notas de fin de año, podrá ser admitido previa firma de un compromiso de conducta con EL COLEGIO, caso contrario se retirará la reserva de vacante y la ratificación de matrícula para el año escolar 2024 y no existirá devolución ni reembolso de ningún concepto en caso esto ocurra.";
     
     $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto)); // Codifica el texto a UTF-8
     $pdf->Ln(); // Salto de línea después del segundo bloque

     $pdf->Write(10, iconv('UTF-8', 'windows-1252', $anio_texto)); // Codifica el texto del año a UTF-8
     $pdf->Ln(); // Salto de línea después del segundo bloque
     $pdf->Ln(); // Salto de línea después del segundo bloque
     
     $pdf->Ln(); // Salto de línea después del segundo bloque


     $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto2)); // Codifica el texto del año a UTF-8
     $pdf->Ln(); // Salto de línea después del segundo bloque

     $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto3)); // Codifica el texto del año a UTF-8
     $pdf->Ln(); // Salto de línea después del segundo bloque

     $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto4)); // Codifica el texto del año a UTF-8
     $pdf->Ln(); // Salto de línea después del segundo bloque
     $pdf->Ln(); // Salto de línea después del segundo bloque
     
     $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto5)); // Codifica el texto del año a UTF-8
     $pdf->Ln(); // Salto de línea después del segundo bloque
    $pdf->Ln(); // Salto de línea después del segundo bloque
    $pdf->Ln(); // Salto de línea después del segundo bloque
    $pdf->Ln(); 
    $pdf->Ln(); 
    $pdf->Ln(); 
    $pdf->SetFont('Arial', 'B', 11);

    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto7)); 
    $pdf->SetFont('Arial', '', 11);
    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto8)); 

    $pdf->Ln(); 
    $pdf->SetFont('Arial', 'B', 11);

    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto9)); 
    $pdf->SetFont('Arial', '', 11);
    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto10)); 
    $pdf->Ln(); 
    $pdf->Ln(); 

    $pdf->SetFont('Arial', 'B', 11);

    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto11)); 
    $pdf->SetFont('Arial', '', 11);

    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto12)); 
    $pdf->Ln(); 
    $pdf->SetFont('Arial', 'B', 11);

    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto13));
    $pdf->SetFont('Arial', '', 11);

    $pdf->Write(10, iconv('UTF-8', 'windows-1252', $texto14)); 

    $pdf->Ln(); 
    $pdf->SetTitle(utf8_decode('Solicitud de reserva de vacante de alumano(a) '.$estudiante['first_name']." " .$estudiante['last_name'] ));

    $pdf->Output(utf8_decode('solicitud_reserva_vacante_'.$estudiante['first_name']."_" .$estudiante['last_name']).'.pdf', 'I');

  

    }

    
}

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee_model');
        $this->load->model('student_model');
        $this->load->model('fees_model');
        $this->load->model('parents_model');
        $this->load->model('profile_model');
        $this->load->model('reservation_model');

        $this->load->model('email_model');
        $this->load->model('student_fields_model');

///////////REVISAR ESTAS DE ACÁ ABAJO

        $this->load->helpers('download');
        $this->load->helpers('custom_fields');
        $this->load->model('sms_model');
    }

    
    public function index()
    {     

        $loggedinRoleID = loggedin_role_id();

       if($loggedinRoleID==7){
        $userID = get_loggedin_user_id();
        
    }else{
        $userID = intval($this->input->get('hijoId'));
       // $userID = intval($this->input->post('hola'));

    }

        $branchID = get_loggedin_branch_id();
        if ($loggedinRoleID == 5) {
            
        } elseif ($loggedinRoleID == 7 || $loggedinRoleID == 6) {
            if ($_POST) {

                $this->form_validation->set_rules('student_id', translate('student'), 'trim');
                // system fields validation rules
                $validArr = array();
                $validationArr = $this->student_fields_model->getStatusProfileArr($branchID);
                foreach ($validationArr as $key => $value) {
                    if ($value->status && $value->required) {
                        $validArr[$value->prefix] = 1;
                    }
                }

                $this->form_validation->set_rules('user_photo', 'profile_picture', 'callback_photoHandleUpload[user_photo]');
             

                if (isset($validArr['student_photo'])) {
                    if (isset($_FILES["user_photo"]) && empty($_FILES["user_photo"]['name']) && empty($_POST['old_user_photo'])) {
                        $this->form_validation->set_rules('user_photo', translate('profile_picture'), 'required');
                    }
                }

                if (isset($validArr['first_name'])) {
                    $this->form_validation->set_rules('first_name', translate('first_name'), 'trim|required');
                }
                if (isset($validArr['last_name'])) {
                    $this->form_validation->set_rules('last_name', translate('last_name'), 'trim|required');
                }
                if (isset($validArr['gender'])) {
                    $this->form_validation->set_rules('gender', translate('gender'), 'trim|required');
                }
                if (isset($validArr['birthday'])) {
                    $this->form_validation->set_rules('birthday', translate('birthday'), 'trim|required');
                }
            
                if (isset($validArr['religion'])) {
                    $this->form_validation->set_rules('religion', translate('religion'), 'trim|required');
                }
                if (isset($validArr['caste'])) {
                    $this->form_validation->set_rules('caste', translate('caste'), 'trim|required');
                }
                if (isset($validArr['blood_group'])) {
                    $this->form_validation->set_rules('blood_group', translate('blood_group'), 'trim|required');
                }
                if (isset($validArr['mother_tongue'])) {
                    $this->form_validation->set_rules('mother_tongue', translate('mother_tongue'), 'trim|required');
                }
                //TENER EN CUENTA QUE current_address ES EL NOMBRE EN STUDENT TABLE Y EN LA TABLA STUDENT FIELDS ES present_address ( SE TUVO QUE CAMBIAR UANS COSAS EN PROFILE_MODEL PARA QUE SE ACTUALICE BIEN)
                if (isset($validArr['present_address'])) {
                    $this->form_validation->set_rules('present_address', translate('present_address'), 'trim|required');
                }
                if (isset($validArr['permanent_address'])) {
                    $this->form_validation->set_rules('permanent_address', translate('permanent_address'), 'trim|required');
                }
                if (isset($validArr['city'])) {
                    $this->form_validation->set_rules('city', translate('city'), 'trim|required');
                }
                if (isset($validArr['state'])) {
                    $this->form_validation->set_rules('state', translate('state'), 'trim|required');
                }
                if (isset($validArr['student_email'])) {
                    $this->form_validation->set_rules('email', translate('email'), 'trim|required|valid_email');
                }
                if (isset($validArr['student_mobile_no'])) {
                    $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'trim|required|numeric');
                }
                if (isset($validArr['previous_school_details'])) {
                    $this->form_validation->set_rules('school_name', translate('school_name'), 'trim|required');
                    $this->form_validation->set_rules('qualification', translate('qualification'), 'trim|required');
                }
                
                $this->form_validation->set_rules('name', 'Name', 'trim|required');

                if ($this->form_validation->run() == true) {
                    $data = $this->input->post();
                    $userID = $this->input->post('id');
                    $padreID= $this->input->post('idPadre'); 
                    $this->profile_model->studentUpdate($data, $userID); 

                  $this->profile_model->parentUpdateReservation($data, $padreID); 


                $this->reservation_model->insertReservation(get_session_id()+1, $userID); 
                   
                  $this->mailSend($userID);

                    set_alert('success', translate('information_has_been_updated_successfully') );

                    
                    $array = array('status' => 'success');

                } else {
                    $error = $this->form_validation->error_array();
                    $array = array('status' => 'fail', 'error' => $error);
                }
                echo json_encode($array);

                exit();
            }
            $this->data['student'] = $this->student_model->getSingleStudent($userID);


            $this->data['sub_page'] = 'vacancy_reservation/reservationForm';
        } else {
            
        }

        $this->data['title'] = translate('Reservación de ') . " " . translate('Vacante');
        $this->data['main_menu'] = 'reservation';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
       //0 no hizo reservación , 1 hizo reservacion
        
       //$this->data['reservationState'] = 1; 

            $this->data['reservationState'] = $this->reservation_model->reservationCheck(get_session_id()+1, $userID); 

       // 0 que no cumple la condición , 1 si sumple 
           //En caso tenga deudas pendientes con la institucion, generadas atravez del reporte.

           $this->db->select('*');
           $this->db->from('login_credential');

           $this->db->where('id',get_loggedin_id());
           $this->db->limit(1);
           $query = $this->db->get();
           $row = $query->row();
           $username = $row->username;
       
       
       
           $this->db->select('*');
           $this->db->from('registrodeudastemporal');
           $this->db->where('id_student',$username);
           $query = $this->db->get();
           $row = $query->row();
           $deuda = $row->deuda;
            if($deuda <= 0){
           $this->data['deudaState'] = 1; }
           else{
            $this->data['deudaState'] = 0;
           }
           //En caso que tenga pendiente la entrega de utiles .
           $this->data['utilesState'] = 1; 


           $this->db->select('*');
           $this->db->from('registroconductatemporal');
           $this->db->where('id_student',$username);
           $query = $this->db->get();
           $row = $query->row();
           if ($query->num_rows() > 0) {
                $this->data['comportamientoState'] = 0; // Existe un registro
            } else {
                $this->data['comportamientoState'] = 1; // No existe ningún registro
            }
          

       //         $this->load->view(prueba);
        $this -> data['url']=$templatePath;
       $this->load->view('vacancy_reservation/reservation', $this->data);
    }



    // unique valid username verification is done here
    public function unique_username($username)
    {
        if (empty($username)) {
            return true;
        }
        $this->db->where_not_in('id', get_loggedin_id());
        $this->db->where('username', $username);
        $query = $this->db->get('login_credential');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_username", translate('username_has_already_been_used'));
            return false;
        } else {
            return true;
        }
    }
    public function vista(){
        // check access permission
      

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $this->data['students'] = $this->application_model->getStudentReservationListByClassSection($classID, $sectionID, $branchID, false, true);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('Reserva de Matricula');
        $this->data['main_menu'] = 'admission';
        $this->data['sub_page'] = 'vacancy_reservation/vistaAdmin';
        $this->data['headerelements'] = array(
            'js' => array(
                'js/student.js'
            ),
        );
        $this->load->view('layout/index', $this->data);


        // check access permission
      
      
    }
    

    // when user change his password
    public function password()
    {
        if ($_POST) {
            $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[4]|callback_check_validate_password');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[4]|matches[new_password]');
            if ($this->form_validation->run() == true) {
                $new_password = $this->input->post('new_password');
                $this->db->where('id', get_loggedin_id());
                $this->db->update('login_credential', array('password' => $this->app_lib->pass_hashed($new_password)));
                // password change email alert
                $emailData = array(
                    'branch_id' => get_loggedin_branch_id(),
                    'password' => $new_password, 
                );
                $this->email_model->changePassword($emailData);
                set_alert('success', translate('password_has_been_changed'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['sub_page'] = 'vacancy_reservation/reservationForm';
        $this->data['main_menu'] = 'reservation';
        $this->data['title'] = translate('profile');
        $this->load->view('layout/index', $this->data);
    }

    // when user change his username
 

    // current password verification is done here
    public function check_validate_password($password)
    {
        if ($password) {
            $getPassword = $this->db->select('password')
                ->where('id', get_loggedin_id())
                ->get('login_credential')->row()->password;
            $getVerify = $this->app_lib->verify_password($password, $getPassword);
            if ($getVerify) {
                return true;
            } else {
                $this->form_validation->set_message("check_validate_password", translate('current_password_is_invalid'));
                return false;
            }
        }
    }
}

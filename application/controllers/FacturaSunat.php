<?php
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\XMLSecLibs\Sunat\SignedXml;


use Greenter\Report\Resolver\DefaultTemplateResolver;
use Greenter\Report\HtmlReport;

use Response_sunat_model;

class FacturaSunat extends Admin_Controller {

    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('fees_model');
        $this->load->model('Type_response_sunat_model');
    }

    public function index()
    {
        redirect(base_url('factura_electronica'));
    }

    public function resumen_diario_boletas(){

        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
    
            $this->data['fecha_inicio'] = $this->input->post('fecha_inicio');
    
            $fechaInicio = $this->input->post('fecha_inicio');
    
    
            $this->db->select('boleta.*,fee_payment_history.fine as mora, boleta.id as boletaID,
             payment_types.*, SUM(fee_payment_history.amount +  fee_payment_history.fine) as total_amount, 
             staff.name as secretaria, boleta.id as idDocumento, 
              branch.name as branchName');
            $this->db->from('boleta');
            $this->db->join('fee_payment_history', 'boleta.id = fee_payment_history.boleta_id', 'left');
            $this->db->join('payment_types', 'boleta.pay_via = payment_types.id', 'left');
            $this->db->join('staff', 'staff.id = boleta.id_secretaria', 'left');
            $this->db->join('branch', 'branch.id = boleta.sede', 'left');
            
    
    
            $this->db->group_by('boleta.id');
    
            $this->db->where('boleta.datePay >=', $fechaInicio);
            $this->db->where('boleta.datePay <=', $fechaInicio);
            $this->db->where('boleta.sede =', $branchID);
            
            $query = $this->db->get();
            $resultados = $query->result_array();
    
            $this->data['invoicelist'] =  $resultados ;
            //$this->data['invoicelist'] = True;
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Ventas';
        $this->data['sub_page'] = 'factura_electronica/resumen_diario_boletas';
        $this->data['main_menu'] = 'factura_electronica';
    
     
        $this->load->view('layout/index', $this->data);
    
    }
    
    public function  consulta_envio_individual(){
    
        $branchID = $this->application_model->get_branch_id();
        
        if ($this->input->post('search')) {
    
            $this->data['fecha_inicio'] = $this->input->post('fecha_inicio');
            $this->data['fecha_fin'] = $this->input->post('fecha_fin');
    
            $fechaInicio = $this->input->post('fecha_inicio');
            $fechaFin = $this->input->post('fecha_fin');
    
    
            $this->db->select('boleta.*,fee_payment_history.fine as mora, boleta.id as boletaID, payment_types.*
            , SUM(fee_payment_history.amount +  fee_payment_history.fine) as total_amount, staff.name as secretaria
            , boleta.id as idDocumento, boleta.metodo_pago as metodo_pagoID, branch.name as branchName');
            $this->db->from('boleta');
            $this->db->join('fee_payment_history', 'boleta.id = fee_payment_history.boleta_id', 'left');
            $this->db->join('payment_types', 'boleta.pay_via = payment_types.id', 'left');
            $this->db->join('staff', 'staff.id = boleta.id_secretaria', 'left');
            $this->db->join('branch', 'branch.id = boleta.sede', 'left');
            
    
    
            $this->db->group_by('boleta.id');
    
            $this->db->where('boleta.datePay >=', $fechaInicio);
            $this->db->where('boleta.datePay <=', $fechaFin);
            
            $this->db->where('boleta.estado !=', 'Anulado');
            $this->db->where('boleta.metodo_pago =', 1);


            $query = $this->db->get();
            $resultados = $query->result_array();
    
            $this->data['invoicelist'] =  $resultados ;
            //$this->data['invoicelist'] = True;
        }
    
        $this->data['title'] = 'Ventas';
        $this->data['sub_page'] = 'factura_electronica/consulta_envio_individual';
        $this->data['main_menu'] = 'factura_electronica';
    
     
        $this->load->view('layout/index', $this->data);
    
    }


    public function  consulta_boletas_emitidas(){
    
        $branchID = $this->application_model->get_branch_id();
        
        if ($this->input->post('search')) {
    
            $this->data['mes'] = $this->input->post('mes');
            $this->data['anio'] = $this->input->post('anio');
    
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
    
            $fechaInicio = date("$anio-$mes-01");
            $ultimoDiaMes = date('t', strtotime("$anio-$mes")); 
            $fechaFin = date("$anio-$mes-$ultimoDiaMes");

            $this->db->select('boleta.*,fee_payment_history.fine as mora, boleta.id as boletaID, payment_types.*
            , SUM(fee_payment_history.amount +  fee_payment_history.fine) as total_amount, staff.name as secretaria
            , boleta.id as idDocumento, boleta.metodo_pago as metodo_pagoID, branch.name as branchName');
            $this->db->from('boleta');
            $this->db->join('fee_payment_history', 'boleta.id = fee_payment_history.boleta_id', 'left');
            $this->db->join('payment_types', 'boleta.pay_via = payment_types.id', 'left');
            $this->db->join('staff', 'staff.id = boleta.id_secretaria', 'left');
            $this->db->join('branch', 'branch.id = boleta.sede', 'left');
            
    
    
            $this->db->group_by('boleta.id');
    
            $this->db->where('boleta.datePay >=', $fechaInicio);
            $this->db->where('boleta.datePay <=', $fechaFin);
            
            $this->db->where('boleta.estado !=', 'Anulado');
            $this->db->where('boleta.metodo_pago =', 1);
            $this->db->where('boleta.estado_envio =', 1);


            $query = $this->db->get();
            $resultados = $query->result_array();
    
            $this->data['invoicelist'] =  $resultados ;
            //$this->data['invoicelist'] = True;
        }
    
        $this->data['title'] = 'Ventas';
        $this->data['sub_page'] = 'factura_electronica/consulta_boletas_emitidas';
        $this->data['main_menu'] = 'factura_electronica';
    
     
        $this->load->view('layout/index', $this->data);
    
    }









    public function send_sunat_boleta($boletaData){
        // Crear una nueva instancia de Invoice
        $invoice = new Invoice();
        // Configurar los datos de la boleta
        $invoice->setFecVencimiento(new \DateTime())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101')
                ->setTipoDoc('01')
                ->setSerie('F001')
                ->setCorrelativo('123')
                ->setFechaEmision(new \DateTime())
                ->setFormaPago(new FormaPagoContado())
                ->setTipoMoneda('PEN')
                ->setClient($this->getClient())
                ->setCompany($this->getCompany())
                ->setMtoOperExoneradas(200)
                ->setMtoIGV(0)
                ->setTotalImpuestos(0)
                ->setValorVenta(200)
                ->setSubTotal(200)
                ->setMtoImpVenta(200);
        
        // Crear un detalle de venta para la boleta
        $detail = new SaleDetail();
        $detail->setCodProducto('P001')
                ->setUnidad('NIU')
                ->setDescripcion('PROD 1')
                ->setCantidad(2)
                ->setMtoValorUnitario(100)
                ->setMtoValorVenta(200)
                ->setMtoBaseIgv(200)
                ->setPorcentajeIgv(18)
                ->setIgv(0)
                ->setTipAfeIgv('20')
                ->setTotalImpuestos(0)
                ->setMtoPrecioUnitario(100);
    
        // Agregar el detalle a la boleta
        $invoice->setDetails([$detail])
                ->setLegends([
                    (new Legend())
                        ->setCode('1000')
                        ->setValue('SON DOSCIENTOS CON OO/100 SOLES')
                ]);
    
        // Enviar la boleta a Greenter para su procesamiento
        $envio = new SendBill();
        $res = $envio->send($invoice);
    
        // Verificar si se pudo enviar correctamente
        if ($res->isSuccess()) {
            // Obtener el número de serie y correlativo asignado por Sunat
            $cdr = $res->getCdrResponse();
            $serie = $cdr->getSerie();
            $correlativo = $cdr->getCorrelativo();
    
            // Actualizar la boleta en tu base de datos con el número de serie y correlativo asignado
            $this->updateBoleta($boletaData['id'], $serie, $correlativo);
    
            // Retornar true si todo fue exitoso
            return true;
        } else {
            // Si hubo un error, puedes manejarlo de acuerdo a tus necesidades
            $errors = $res->getError();
            // Retornar false si hubo un error
            return false;
        }
    }

    public function send_sunat_test(){

        require __DIR__.'/..'.'/..'.'/vendor/autoload.php';
        $see = require __DIR__.'/sunat/config.php';
    
        if ($this->input->is_ajax_request()) {
            // Procesar la solicitud Ajax
            $data = $this->input->post('idDocumento'); // Obtén los datos enviados por Ajax
            
            $this->db->select('boleta.*,fee_payment_history.fine as mora, boleta.id as boletaID, payment_types.*, SUM(fee_payment_history.amount +  fee_payment_history.fine) as total_amount, staff.name as secretaria, boleta.id as idDocumento, boleta.metodo_pago as metodo_pagoID, branch.name as branchName,
            branch.city as branchCity,branch.state as branchState,branch.address as branchAddress');
            $this->db->from('boleta');
            $this->db->join('fee_payment_history', 'boleta.id = fee_payment_history.boleta_id', 'left');
            $this->db->join('payment_types', 'boleta.pay_via = payment_types.id', 'left');
            $this->db->join('staff', 'staff.id = boleta.id_secretaria', 'left');
            $this->db->join('branch', 'branch.id = staff.branch_id', 'left');
            
    
    
            $this->db->group_by('boleta.id');
        
            $this->db->where('boleta.id', $data);
        
            $query = $this->db->get();
            $boleta = $query->row_array();

    
            $nro_documento = $boleta['nro_documento'];
    
            // Dividir el string en dos partes usando el guion como separador
            $partes = explode('-', $nro_documento);
    
            // La primera parte es 'B001'
            $primera_parte = $partes[0];
    
            // La segunda parte es '000001', la convertimos a entero
            $segunda_parte = strval((int)$partes[1]);
    
            //echo json_decode($primera_parte.' '.$segunda_parte);

    
            // Cliente
            $client = new Client();
            $client->setTipoDoc('1') // Tipo DNI
                ->setNumDoc($boleta['dni_adquiriente']) 
                ->setRznSocial($boleta['adquiriente']);
    
            // Emisor
            $address = new Address();
            $address->setUbigueo('13007') //NO TIENE UBIGEO SU BD
                ->setDepartamento('La Libertad')
                ->setProvincia('Trujillo')
                ->setDistrito('Trujillo') //ESTO ESTARIA MAL EN EL CASO DE ACADEMIA
                ->setUrbanizacion('-')
                ->setDireccion('Av. Honorio Delgado Mz. o Lote 44 Urb. el Bosque');
    
            $company = new Company();
            $company->setRuc('20477480463')
                ->setRazonSocial('GRUPO EDUCATIVO AI APAEC S.A.C.')
                ->setNombreComercial('I.E.P. AI APAEC')
                ->setAddress($address);
    
    


            $this->db->select('fee_payment_history.cantidad AS cantidad, fee_payment_history.amount AS amount , fee_payment_history.fine as fine, fee_payment_history.discount as discount,
            student.first_name AS nombreAlumno, student.last_name AS apellidoAlumno, fee_payment_history.type_id AS tipoVenta, fees_type.name as nombreProducto,
            fees_type.id as feeTypeId, fees_type.fee_code as fee_code');
            $this->db->from('ewgcgdaj_instituto.boleta');
            $this->db->join('ewgcgdaj_instituto.fee_payment_history', 'fee_payment_history.boleta_id = boleta.id');
            $this->db->join('ewgcgdaj_instituto.fee_allocation', 'fee_allocation.id = fee_payment_history.allocation_id', 'left');
            $this->db->join('ewgcgdaj_instituto.fees_type', 'fees_type.id = fee_payment_history.type_id', 'left');
            $this->db->join('ewgcgdaj_instituto.student', 'fee_allocation.student_id = student.id', 'left');
            $this->db->where('boleta.id', $data);
            
            $query = $this->db->get();
            //$items = $query->result();
            $items2= $query->result_array();
            $total=0;

            $items=[];
            // Lista de ítems (pagos de matrículas)
            //PAGOS PERSONALIZADOS SE MULTIPLICA POR SU CANTIDAD ALLOCATION_ID Y TYPE_ID == 0
            foreach ($items2 as $item){
                $items[]= array(
                    strval($item['feeTypeId']),
                    'NIU',
                    floatval($item['cantidad']),
                    strtoupper(strval($item['fee_code'])),
                    floatval($item['cantidad']* ($item['amount'] + $item['fine'] - $item['discount']) ),
                    0,
                    0,
                    '20',
                    0,
                    floatval($item['cantidad']*($item['amount'] + $item['fine'] - $item['discount']) ),
                    floatval(($item['amount'] + $item['fine'] - $item['discount']) ),
                    floatval(($item['amount'] + $item['fine'] - $item['discount']))

                );
            }
            
           

            
            
            
            
            
            
            
            $invoiceDetails = [];
                
            foreach ($items as $itemData) {
              
                
                
                $item = (new SaleDetail())
                ->setCodProducto($itemData[0])         
                ->setUnidad($itemData[1])             
                ->setCantidad($itemData[2])             
                ->setDescripcion($itemData[3])          
                ->setMtoBaseIgv($itemData[4])          
                ->setPorcentajeIgv($itemData[5])       
                ->setIgv($itemData[6])                 
                ->setTipAfeIgv($itemData[7])           
                ->setTotalImpuestos($itemData[8])       
                ->setMtoValorVenta($itemData[9])        
                ->setMtoValorUnitario($itemData[10])    
                ->setMtoPrecioUnitario($itemData[11]); 

              
                    
                
                $invoiceDetails[] = $item;
               
            }
           
            // Venta
            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc('03')
                ->setSerie($primera_parte)
                ->setCorrelativo($segunda_parte)
                ->setFechaEmision(new DateTime())
                ->setTipoMoneda('PEN')
                ->setClient($client)
                ->setCompany($company)
                ->setMtoOperExoneradas(200)
                ->setMtoIGV(0)
                ->setTotalImpuestos(0)
                ->setValorVenta(200)
                ->setSubTotal(200)
                ->setMtoImpVenta(200);


            



            
            
            $legend = (new Legend())
                ->setCode('1000')
                ->setValue('SON CIENTO DIECIOCHO CON 00/100 SOLES');
    
            $invoice->setDetails($invoiceDetails)
                    ->setLegends([$legend]);


            try {
                // Intentar enviar la factura a SUNAT
                $result = $see->send($invoice);
    
                // Ruta base donde se guardarán los archivos
                $ruta_base = __DIR__ .'/..'.'/..'.'/uploads/Facturas';
                // Verificar si la carpeta Facturas existe, si no, crearla
                if (!file_exists($ruta_base)) {
                    mkdir($ruta_base, 0777, true);
                }
            
                // Ruta de la carpeta del primer segmento (B001, B002, etc.)
                $ruta_primera_parte = $ruta_base . '/' . $primera_parte;
            
                // Verificar si la carpeta del primer segmento existe, si no, crearla
                if (!file_exists($ruta_primera_parte)) {
                    mkdir($ruta_primera_parte, 0777, true);
                }
            
                // Ruta de la carpeta del segundo segmento (1, 2, etc.)
                $ruta_segunda_parte = $ruta_primera_parte . '/' . $segunda_parte;
            
                // Verificar si la carpeta del segundo segmento existe, si no, crearla
                if (!file_exists($ruta_segunda_parte)) {
                    mkdir($ruta_segunda_parte, 0777, true);
                }
            
                // Guardar XML firmado digitalmente en la carpeta del segundo segmento
                $ruta_xml = $ruta_segunda_parte . '/' . $invoice->getName() . '.xml';
                if (file_put_contents($ruta_xml, $see->getFactory()->getLastXml()) === false) {
                    die("Error al guardar el archivo XML en $ruta_xml");
                }
            
                // Verificamos que la conexión con SUNAT fue exitosa.
                if (!$result->isSuccess()) {
                    // Mostrar error al conectarse a SUNAT.
                    $response = array('status' => 'error', 'message' => 'Hubo un error al conectar con SUNAT.', 'data2' => $items,'data' =>$primera_parte);

             

                } else {
                    // Guardamos el CDR
                    $ruta_zip = $ruta_segunda_parte . '/' . 'B-' . $invoice->getName() . '.zip';
                    if (file_put_contents($ruta_zip, $result->getCdrZip()) === false) {
                        die("Error al guardar el archivo ZIP en $ruta_zip");
                    }
            
                    $cdr = $result->getCdrResponse();
            
                    $code = (int)$cdr->getCode();
            
                    if ($code === 0) {
                        $response = array('status' => 'success', 'message' => 'La factura ha sido enviada a SUNAT.', 'data2' => $items,'data' =>$primera_parte);
                        $nombre_xml = '/uploads/Facturas/' . $primera_parte . '/' . $segunda_parte.'/'.$invoice->getName() . '.xml';
                        $nombre_cdr = '/uploads/Facturas/'.$primera_parte.'/'.$segunda_parte.'/'.'B-' . $invoice->getName() . '.zip' ;
                        $data2 = array(

                            'direccion_xml' =>   $nombre_xml,
                            'direccion_cdr' =>  $nombre_cdr,
                            'estado_envio' => 1, // Nuevo valor para el estado_envio
                            'respuesta_sunat' => 'Aceptado'
                        );

                        $this->db->where('id', $data); // Suponiendo que $id_boleta contiene el ID de la boleta que deseas actualizar
                        $this->db->update('boleta', $data2);


                        $xml = file_get_contents($ruta_base .'/'. $primera_parte . '/' . $segunda_parte.'/'.$invoice->getName() . '.xml');
                        $cert = file_get_contents( __DIR__.'/sunat/certificate.pem');
                        
                        $signer = new SignedXml();
                        $signer->setCertificate($cert);
                        
                        $xmlSigned = $signer->signXml($xml);
                        
                        file_put_contents($ruta_base .'/'. $primera_parte . '/' . $segunda_parte.'/'.$invoice->getName() . '.xml' , $xmlSigned);
                        

                    } else if ($code >= 2000 && $code <= 3999) {
                        $response = array('status' => 'error', 'message' => 'La factura fue rechazada por SUNAT.');
                    } else {
                        $response = array('status' => 'error', 'message' => 'Hubo un error inesperado al procesar la respuesta de SUNAT.');
                    }
                }
                // Envía la respuesta en formato JSON
                header('Content-Type: application/json');
                echo json_encode($response);
    
            } catch (\Exception $e) {
                // Capturar y mostrar cualquier excepción ocurrida durante el envío a SUNAT
                $response = array('status' => 'error', 'message' => 'Hubo un error al conectar con SUNAT. Detalles: ' .  __DIR__.'sunat/certificate.pem');
    
                // Envía la respuesta en formato JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit; // Detener la ejecución del script
            }
    
        } else {
            echo json_encode(['error' => true]);
        }
    }
    
   
   
    
    // Función para obtener el cliente
    private function getClient(){
        // Implementa la lógica para obtener los datos del cliente
    }
    
    // Función para obtener los datos de la empresa
    private function getCompany(){
        // Implementa la lógica para obtener los datos de la empresa
    }
    
    // Función para actualizar la boleta en la base de datos con el número de serie y correlativo asignado por Sunat
    private function updateBoleta($boletaId, $serie, $correlativo){
        // Implementa la lógica para actualizar la boleta en la base de datos
    }
    
    public function factura($boletaID){

        $this->db->select('boleta.*, staff.name AS nombreSecretaria');
        $this->db->from('boleta');
        $this->db->join('staff', 'staff.id = boleta.id_secretaria');
    
        $this->db->where('boleta.id', $boletaID);
    
        $query = $this->db->get();
        $boleta = $query->row_array();   
        $tipo = ($boleta['pay_via'] == 1) ? "Boleta" : (($boleta['pay_via'] == 2) ? "Recibo" : "Otro");
    
        require_once APPPATH."third_party/code128.php";
    
        $pdf = new PDF_Code128('P','mm','A4');
        $pdf->SetMargins(4,10,4);
        $pdf->AddPage();
        
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0,0,0);
        
        // Encabezado izquierdo
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper("GRUPO EDUCATIVO AI APAEC S.A.C. INICIAL-PRIMARIA-SECUNDARIA")),0,'L',false);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","RUC: 20477480463"),0,'L',false);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Direccion LA LIBERTAD - TRUJILLO - TRUJILLO"),0,'L',false);
        
        $pdf->Ln(1);
        
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Fecha: ".date("d/m/Y", strtotime($boleta['datePay']))." ".date("h:s A")),0,'L',false);
        $pdf->SetFont('Arial','B',10);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper("FACTURA Nro: ". $boleta['nro_documento'])),0,'L',false);
        $pdf->SetFont('Arial','',9);
        
        $pdf->Ln(1);
        
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cliente: ". $boleta['adquiriente']),0,'L',false);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Documento: DNI 00000000"),0,'L',false);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","N° OPERACION: ".$boleta['numeroOperacion']),0,'L',false);
        
        $pdf->Ln(1);
        
        // Encabezado derecho
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,10,iconv("UTF-8", "ISO-8859-1","FACTURA ELECTRÓNICA"),1,1,'C');
        $pdf->Cell(0,10,iconv("UTF-8", "ISO-8859-1","Nro: ".$boleta['nro_documento']),1,1,'C');
        $pdf->Ln();
        
        $pdf->Cell(80); // Espacio para centrar la imagen
        $pdf->Image('https://ecfmggf.stripocdn.email/content/guids/CABINET_6bb4331f6d2615b961384575ba5ab832b3a72a41035c22d0986924bf622b5711/images/sin_titulo1_mesa_de_trabajo_1.png', $pdf->GetPageWidth() - 85, $pdf->GetY(), 80);
        $pdf->Ln(5);
        
        
        // Tabla de factura
        $pdf->Cell(10,5,iconv("UTF-8", "ISO-8859-1","Cant."),1,0,'C');
        $pdf->Cell(100,5,iconv("UTF-8", "ISO-8859-1","Descripción"),1,0,'C');
        $pdf->Cell(30,5,iconv("UTF-8", "ISO-8859-1","Precio Unitario"),1,0,'C');
        $pdf->Cell(30,5,iconv("UTF-8", "ISO-8859-1","Total"),1,0,'C');
        $pdf->Ln();
        
        // Llenar con los datos de la factura
        foreach ($result as $row) {
            // Llenar la tabla con los datos de la factura
        }
        
        $pdf->Output("I","Factura_Nro_1.pdf",true);
        
        $this->db->select('fee_payment_history.cantidad AS cantidad, fee_payment_history.amount AS amount , fee_payment_history.fine as fine,
        student.first_name AS nombreAlumno, student.last_name AS apellidoAlumno, fee_payment_history.type_id AS tipoVenta, fees_type.name as nombreProducto');
        $this->db->from('ewgcgdaj_instituto.boleta');
        $this->db->join('ewgcgdaj_instituto.fee_payment_history', 'fee_payment_history.boleta_id = boleta.id');
        $this->db->join('ewgcgdaj_instituto.fee_allocation', 'fee_allocation.id = fee_payment_history.allocation_id', 'left');
        $this->db->join('ewgcgdaj_instituto.fees_type', 'fees_type.id = fee_payment_history.type_id', 'left');
        $this->db->join('ewgcgdaj_instituto.student', 'fee_allocation.student_id = student.id', 'left');
        $this->db->where('boleta.id', $boletaID);
        
        $query = $this->db->get();
        $result = $query->result();
        $total=0;
        
        foreach ($result as $row) {
        
            if($row-> tipoVenta ==0){
                $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1","Pago Personalizado"),0,'C',false);
            } else {
                $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1",$row-> nombreProducto."--".$row->nombreAlumno." ".$row->apellidoAlumno ),0,'C',false);
            }
            $pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1",$row->cantidad),1,0,'C');
            $pdf->Cell(100,4,iconv("UTF-8", "ISO-8859-1",$row->nombreProducto),1,0,'C');
            $pdf->Cell(30,4,iconv("UTF-8", "ISO-8859-1",number_format($row->amount + $row->fine,2)),1,0,'C');
            $pdf->Cell(30,4,iconv("UTF-8", "ISO-8859-1",number_format($row->cantidad*($row->amount + $row->fine),2)),1,0,'C');
            $pdf->Ln(4);
            $total += $row->cantidad*($row->amount + $row->fine);
        
        }
        $pdf->Ln(7);
        
        $pdf->Cell(110,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
        
        $pdf->Ln(5);
        
        $pdf->Cell(110,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
        
        $pdf->Ln(5);
        
        $pdf->Cell(70,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $pdf->Cell(30,5,iconv("UTF-8", "ISO-8859-1","TOTAL A PAGAR"),0,0,'C');
        $pdf->Cell(30,5,iconv("UTF-8", "ISO-8859-1",number_format($total,2)),0,0,'C');
        
        $pdf->Ln(5);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Vendedor: ". $boleta['nombreSecretaria'] ),0,'C',false);
        
        $pdf->Ln(10);
        
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** ¡GRACIAS POR SU PREFERENCIA!¡EDUCACIÓN INTEGRAL, INNOVADORA Y DE CALIDAD! ***"),0,'C',false);
        
        
        $pdf->Ln(9);
        
        $pdf->Code128(5,$pdf->GetY(),$boleta['nro_documento'],70,20);
        $pdf->SetXY(0,$pdf->GetY()+21);
        $pdf->SetFont('Arial','',14);
        $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",$boleta['nro_documento']),0,'C',false);
        
        $pdf->Output("I","Factura_Nro_1.pdf",true);
        
        
    
    }
    

}

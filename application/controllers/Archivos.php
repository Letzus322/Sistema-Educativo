<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Userrole.php
 * @copyright : Reserved RamomCoder Team
 */

class Archivos extends User_Controller
{

    public function __construct()
    {   
        parent::__construct();
        $this->load->model('student_model');

        $this->load->model('userrole_model');
        $this->load->model('leave_model');
        $this->load->model('fees_model');
        $this->load->model('exam_model');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Archivo_model'); 


    }

    public function index()
    {
        redirect(base_url(), 'refresh');
    }



    public function subirArchivo()
    {
        $userID = get_loggedin_user_id();
        $this->db->from('student');
        $this->db->where('id', $userID);
        $query = $this->db->get();
        $estudiante = $query->row_array(); // Obteniendo la fila como un array asociativo


        if ($_POST) {
                   
           
            set_alert('success', translate('information_has_been_updated_successfully'));
           
          
           

            // Directorio de destino para subir archivos
           
           // $upload_dir = APPPATH . 'upload/';

            // Configuración de la subida de archivos
            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);

            if (!empty($_FILES['archivo']['name'])) {
                // Obtiene la extensión del archivo
                $file_extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
                
                // Directorio base para subir los archivos de estudiantes
                $base_path = 'uploads/estudiantes/';
            
                // Nombre de la carpeta del estudiante
                $student_folder = $estudiante['first_name'] . '_' . $estudiante['last_name'];
            
                // Ruta completa del directorio del estudiante
                $student_path = $base_path . $student_folder;
            
                // Si la carpeta no existe, la crea
                if (!file_exists($student_path)) {
                    mkdir($student_path, 0777, true);
                }
                $tipo_documento_id = $_POST['tipo_documento'];

                $documentoSeleccionado = $this->Archivo_model->obtenerArchivoPorId($tipo_documento_id);

                // Nuevo nombre de archivo
                $new_filename = $documentoSeleccionado['nombre_document'].'_' . $estudiante['first_name'] .'_'. $estudiante['last_name'] . '.' . $file_extension;
                $new_filename = str_replace(' ', '_', $new_filename);

                // Establece el nuevo nombre del archivo
                $_FILES['archivo']['name'] = $new_filename;
            
                // Actualiza la ruta de carga para el archivo
                $config['upload_path'] = $student_path;
            
                $this->upload->initialize($config);
            
                // Continúa con la subida del archivo
                if ($this->upload->do_upload('archivo')) {
                    // Archivo subido exitosamente
                    // Puedes realizar operaciones adicionales si es necesario
                } else {
                    // Error al subir el archivo
                    set_alert('fail', translate('Algo salió mal'));
                }

            }

            $tipo_documento_id = $_POST['tipo_documento'];

            $rutaArchivo= $new_filename;
            // Crear un array para la respuesta JSON

            $registroSubida = array(
                'student_id' => $userID,
                'document_id' => $tipo_documento_id,
                'ruta_archivo' => $rutaArchivo,
                'fecha_subida' => date('Y-m-d H:i:s') // Fecha y hora actual
            );
            //REGISTRA LA SUBIDA
            $this->db->insert('subida', $registroSubida);

            // Enviar la respuesta JSON




            $this->db->select('d.id, d.nombre_document, d.descripcion');
            $this->db->from('document d');
            $this->db->join('subida s', 'd.id = s.document_id AND s.student_id = ' . $userID, 'left');
            $this->db->where('s.id IS NULL');
    
            $query = $this->db->get();
        
            $this->data['documentos'] = $query->result_array();

            if(empty($this->data['documentos'])){
                // Verificar si el estudiante ya tiene un registro en la tabla
                $this->db->where('idEstudiante', $userID);
                $query = $this->db->get('estadoMatricula');
                
                if ($query->num_rows() > 0) {
                    // Si existe, realizar un update
                    $this->db->set('subidaArchivos', 1);
                    $this->db->where('idEstudiante', $userID);
                    $this->db->update('estadoMatricula');
                } else {
                    // Si no existe, realizar un insert
                    $data = array(
                        'idEstudiante' => $userID,
                        'subidaArchivos' => 1
                        // Puedes establecer otros campos en su valor predeterminado aquí si es necesario
                    );
                
    
    
                    $this->db->insert('estadoMatricula', $data);
                }
                
            }
            $array = array('status' => 'success');

            echo json_encode($array);
            exit();
        }
        

        $this->db->select('d.id, d.nombre_document, d.descripcion');
        $this->db->from('document d');
        $this->db->join('subida s', 'd.id = s.document_id AND s.student_id = ' . $userID, 'left');
        $this->db->where('s.id IS NULL');

        $query = $this->db->get();

        //MANDA A LA VISTA LA LISTA DE LOS DOCUMENTOS QUE FALTAN SUBIR

        $this->data['documentos'] = $query->result_array();

        
        $this->data['title'] = translate('Expediente de postulante');

        $this->data['sub_page'] = 'archivos/subirArchivo';
        $this->data['main_menu'] = 'archivos';
        $this->load->view('layout/index', $this->data);
    }

  
    
    public function verArchivo()
    {
        $this->data['title'] = translate('Expediente de postulante');
        $this->data['sub_page'] = 'archivos/verArchivo';
        $this->data['main_menu'] = 'archivos';

        $userID = get_loggedin_user_id();

        $this->db->select('d.id, d.nombre_document, d.descripcion,s.ruta_archivo');
        $this->db->from('document d');
        $this->db->join('subida s', 'd.id = s.document_id ');
        $this->db->where('s.Student_id',$userID );

        $query = $this->db->get();

       

        //MANDA A LA VISTA LA LISTA DE LOS DOCUMENTOS QUE YA SE HAN SUBIDO
        $this->data['documentos'] = $query->result_array();
        $this->load->view('layout/index', $this->data);

    }




    public function verPDFsedes($nombre_archivo) {


        $nombre_archivo = urldecode($nombre_archivo);

  
        $ruta_pdf = 'uploads/documentos/'. $nombre_archivo;


        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        if (strtolower($extension) == 'pdf') {

        // Comprueba si el archivo existe antes de mostrarlo
        if (file_exists($ruta_pdf)) {
            // Establece la cabecera Content-Type como PDF
            header('Content-Type: application/pdf');
            
            // Abre el archivo en una nueva pestaña
            readfile($ruta_pdf);
        } else {
            // Maneja el caso en que el archivo no exista
            echo "El archivo no existe.";
        }
    } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
        // Es una imagen
        $ruta_imagen = $ruta_pdf ;

        if (file_exists($ruta_imagen)) {
            // Mostrar la imagen en el navegador
            header('Content-Type: image/' . $extension);
            readfile($ruta_imagen);
        } else {
            echo "La imagen no existe.";
        }
    } else {
        echo "Tipo de archivo no compatible.";
    }



    }
    

    public function verPDF($nombre_archivo) {


        $userID = get_loggedin_user_id();
        $estudiante = $this->student_model->getSingleStudent($userID);
        $student_folder = $estudiante['first_name'] . '_' . $estudiante['last_name'];
        $nombre_archivo = urldecode($nombre_archivo);

        $ruta_pdf = 'uploads/estudiantes/'.$student_folder.'/' . $nombre_archivo;


        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        if (strtolower($extension) == 'pdf') {

        // Comprueba si el archivo existe antes de mostrarlo
        if (file_exists($ruta_pdf)) {
            // Establece la cabecera Content-Type como PDF
            header('Content-Type: application/pdf');
            
            // Abre el archivo en una nueva pestaña
            readfile($ruta_pdf);
        } else {
            // Maneja el caso en que el archivo no exista
            echo "El archivo no existe.";
        }
    } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
        // Es una imagen
        $ruta_imagen = $ruta_pdf ;

        if (file_exists($ruta_imagen)) {
            // Mostrar la imagen en el navegador
            header('Content-Type: image/' . $extension);
            readfile($ruta_imagen);
        } else {
            echo "La imagen no existe.";
        }
    } else {
        echo "Tipo de archivo no compatible.";
    }



    }



    

}



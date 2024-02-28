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

class Citas extends User_Controller
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
        $this->load->model('Archivo_model'); // Carga el modelo si no lo has hecho


    }

    public function index()
    {
        redirect(base_url(), 'refresh');
    }

    public function CitaPsicologicaReprogramar()
    {
        $branch_id= get_loggedin_branch_id();
        $this->db->select('*');
        $this->db->from('login_credential lc');
        $this->db->join('staff s', 'lc.user_id = s.id');
        $this->db->where('lc.role', 11);
        $this->db->where('s.branch_id', $branch_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data ['psicologos']=$result ;


        $userID = get_loggedin_user_id();

        // Realiza la consulta para verificar si hay registros para el usuario actual
        $this->db->select('*');
        $this->db->from('reservasPsicologicas');
        $this->db->where('id_student', $userID);
        $query = $this->db->get();
        $result_array = $query->row_array();
        $result_object = $query->row();
        $this->data['citaSeparada_array'] = $result_array;
        $this->data['citaSeparada_object'] = $result_object;

        // Determina si hay registros para mostrar el mensaje en la vista
        $this->data['citaSeparada'] = $result;


        if ($_POST) {
            $horarioPsicologoAntiguo = $_POST['horarioPsicologoAntiguo'];

            $horarioPsicologo = $_POST['horarioPsicologo'];
            $pregunta1 = $_POST['pregunta1'];
            $pregunta2 = $_POST['pregunta2'];
            $pregunta3 = $_POST['pregunta3'];
            $idCitaAntigua = $_POST['citaAntigua'];

            $this->db->set('disponible', 0);
            $this->db->where('schedule_id', $horarioPsicologo);
            $this->db->update('work_schedules');

            $this->db->set('disponible', 1);
            $this->db->where('schedule_id', $horarioPsicologoAntiguo);
            $this->db->update('work_schedules');

            $userId= get_loggedin_user_id();
            $data = array(
                'id_student' =>$userId, // Reemplaza con el ID del estudiante correspondiente
                'schedule_id' => $horarioPsicologo, // Reemplaza con el ID del horario del psicólogo
                'pregunta1' => $pregunta1, // Reemplaza con el ID del horario del psicólogo
                'pregunta2' => $pregunta2, // Reemplaza con el ID del horario del psicólogo
                'pregunta3' => $pregunta3, // Reemplaza con el ID del horario del psicólogo
                'reprogramada'=> 1
            );
            $this->db->insert('reservasPsicologicas', $data);

            $this->db->where('id', $idCitaAntigua);
            $this->db->delete('reservasPsicologicas');

            set_alert('success', translate('information_has_been_updated_successfully'));
            $array = array('status' => 'success');
            echo json_encode($array);
            exit();
           }

        $this->data['title'] = translate('Cita Psicologica');
        $this->data['sub_page'] = 'citas/CitaPsicologicaReprogramar';
        $this->data['main_menu'] = 'citas';
        $this->load->view('layout/index', $this->data);
    }


    public function CitaPsicologica()
    {
        $branch_id= get_loggedin_branch_id();
        $this->db->select('*');
        $this->db->from('login_credential lc');
        $this->db->join('staff s', 'lc.user_id = s.id');
        $this->db->where('lc.role', 11);
        $this->db->where('s.branch_id', $branch_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data ['psicologos']=$result ;


        $userID = get_loggedin_user_id();

        // Realiza la consulta para verificar si hay registros para el usuario actual
        $this->db->select('*');
        $this->db->from('reservasPsicologicas');
        $this->db->where('id_student', $userID);
        $this->db->where_not_in('estado', ['Rechazado']);

        $query = $this->db->get();
        $result = $query->result_array();

        // Determina si hay registros para mostrar el mensaje en la vista
        $this->data['citaSeparada'] = (count($result) > 0);


        if ($_POST) {
                   
            $horarioPsicologo = $_POST['horarioPsicologo'];
            $pregunta1 = $_POST['pregunta1'];
            $pregunta2 = $_POST['pregunta2'];
            $pregunta3 = $_POST['pregunta3'];

           // Verificar si ya existe un registro con el mismo schedule_id
            $this->db->where('schedule_id', $horarioPsicologo);
            $query = $this->db->get('reservasPsicologicas');

            if ($query->num_rows() > 0) {
                // Si ya existe un registro, muestra un mensaje y detiene la operación
                $array = array('status' => 'error', 'message' => 'Elegir otro horario');
                set_alert('error', 'Elige otro horario por favor');

                echo json_encode($array);
                exit();
            } else {
                // Si no existe, procede con la inserción del nuevo registro
                $this->db->set('disponible', 0);
                $this->db->where('schedule_id', $horarioPsicologo);
                $this->db->update('work_schedules');

                $userId = get_loggedin_user_id();
                $data = array(
                    'id_student' => $userId,
                    'schedule_id' => $horarioPsicologo,
                    'pregunta1' => $pregunta1,
                    'pregunta2' => $pregunta2,
                    'pregunta3' => $pregunta3
                );

                $this->db->insert('reservasPsicologicas', $data);

                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success');
                echo json_encode($array);
                exit();
            }
           }

        $this->data['title'] = translate('Cita Psicologica');
        $this->data['sub_page'] = 'citas/citaPsicologica';
        $this->data['main_menu'] = 'citas';
        $this->load->view('layout/index', $this->data);
    }

  
    public function obtenerHorariosPorPsicologo() {
        $psicologo_id = $this->input->post('psicologo_id');
        $this->db->select('*');
        $this->db->from('work_schedules');
        $this->db->join('staff', 'staff.id = work_schedules.staff_id');
        $this->db->where('staff.id', $psicologo_id);        
        $this->db->where('work_schedules.disponible', 1);        
        $this->db->where('DATE(work_schedules.fecha) >= CURDATE()');

        $query = $this->db->get();
        $resultados = $query->result_array(); // Obtiene los resultados como un array

        // Realiza la lógica para obtener los horarios del psicólogo con $psicologo_id
    
        $horarios = $resultados; // Array para almacenar los valores de 'dia_semana'

       
        echo json_encode($horarios); // Reemplaza $horarios con tu array de horarios
    }
    

    public function obtenerHorariosPorTrabajador() {
        $psicologo_id = $this->input->post('trabajador_id');
        $this->db->select('*');
        $this->db->from('work_schedules');
        $this->db->join('staff', 'staff.id = work_schedules.staff_id');
        $this->db->where('staff.id', $psicologo_id);        
        $this->db->where('work_schedules.disponible >', 0);
        $this->db->where('DATE(work_schedules.fecha) > CURDATE()');

        $query = $this->db->get();
        $resultados = $query->result_array(); // Obtiene los resultados como un array

        // Realiza la lógica para obtener los horarios del psicólogo con $psicologo_id
    
        $horarios = $resultados; // Array para almacenar los valores de 'dia_semana'

       
        echo json_encode($horarios); // Reemplaza $horarios con tu array de horarios
    }

    public function citaAcademica()
    {   //TRABAJADORES DISPONIBLES PARA ESTE ESTUDIANTE
        $branch_id= get_loggedin_branch_id();
        $this->db->select('*');
        $this->db->from('login_credential lc');
        $this->db->join('staff s', 'lc.user_id = s.id');
        $this->db->where('lc.role', 10);
        $this->db->where('s.branch_id', $branch_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data ['trabajadores']=$result ;

        // Determina si ya se registró
        $userID = get_loggedin_user_id();

        $this->db->select('*');
        $this->db->from('reservasAcademicas');
        $this->db->where('id_student', $userID);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data['citaSeparada'] = (count($result) > 0);


        if ($_POST) {
                   
            $horariotrabajador = $_POST['horariotrabajador'];
            $this->db->select('*');
            $this->db->from('work_schedules');
            $this->db->where('schedule_id', $horariotrabajador);
            $queryCantidad = $this->db->get();
            $resultCantidad = $queryCantidad->result_array();
            $cantidad = $resultCantidad[0]['disponible'];

            $this->db->set('disponible', $cantidad-1);
            $this->db->where('schedule_id', $horariotrabajador);
            $this->db->update('work_schedules');
            
            $userId= get_loggedin_user_id();
            $data = array(
                'id_student' =>$userId, // Reemplaza con el ID del estudiante correspondiente
                'schedule_id' => $horariotrabajador, // Reemplaza con el ID del horario del psicólogo
            );
            $this->db->insert('reservasAcademicas', $data);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array = array('status' => 'success');
            echo json_encode($array);
            exit();
           }

        $this->data['title'] = translate('Cita Academica');
        $this->data['sub_page'] = 'citas/citaAcademica';
        $this->data['main_menu'] = 'citas';
        $this->load->view('layout/index', $this->data);
    }


    public function estadoCitas()
    {   //TRABAJADORES DISPONIBLES PARA ESTE ESTUDIANTE
        $branch_id= get_loggedin_branch_id();
        $this->db->select('*');
        $this->db->from('login_credential lc');
        $this->db->join('staff s', 'lc.user_id = s.id');
        $this->db->where('lc.role', 10);
        $this->db->where('s.branch_id', $branch_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data ['trabajadores']=$result ;

        // Determina si ya se registró academicamente
        $userID = get_loggedin_user_id();
        $this->db->select('*');
        $this->db->from('reservasAcademicas');
        $this->db->where('id_student', $userID);
        $query = $this->db->get();
        $result = $query->row_array();
        $this->data['citaAcademicaSeparada'] = $result;




        // Determina si ya se registró con psicologia

        $this->db->select('*');
        $this->db->from('reservasPsicologicas');
        $this->db->where('id_student', $userID);
        $this->db->where("(estado IN ('Aceptado', 'Pendiente') OR (estado = 'Rechazado' AND NOT EXISTS (
            SELECT 1
            FROM reservasPsicologicas
            WHERE id_student = '".$userID."'
            AND estado IN ('Aceptado', 'Pendiente')
        )))");
        $query = $this->db->get();
        $result = $query->row_array();
        $this->data['citaPsicologica'] = $result;


        //determinar si ya hay una aceptada

        //documentos que faltan
        $this->db->select('d.id, d.nombre_document, d.descripcion');
        $this->db->from('document d');
        $this->db->join('subida s', 'd.id = s.document_id AND s.student_id = ' . $userID, 'left');
        $this->db->where('s.id IS NULL');
        $query = $this->db->get();
        $this->data['documentos'] = $query->result_array();


   

        $this->db->select('s.id as codigoEstudiante, s.*, e.*');
        $this->db->from('student s');
        $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
        $this->db->where('s.id', $userID);
        $query = $this->db->get();
        $this->data['estadoMatricula'] = $query->result_array();


        $this->data['title'] = translate('Estados de citas');
        $this->data['sub_page'] = 'citas/estadoCitas';
        $this->data['main_menu'] = 'estadoCitas';
        $this->load->view('layout/index', $this->data);
    }
    


    public function subirContrato()
    {
        $userID = get_loggedin_user_id();

        if ($_POST) {
            set_alert('success', translate('information_has_been_updated_successfully'));

            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);

            for ($i = 1; $i <= 4; $i++) {
                $nombreCampo = 'archivo' . $i;
            
                if (!empty($_FILES[$nombreCampo]['name'])) {
                    $this->db->select('*');
                    $this->db->from('student');
                    $this->db->where('id', $userID);
                    $query = $this->db->get();
                    $estudiante = $query->row_array();
            
                    $file_extension = pathinfo($_FILES[$nombreCampo]['name'], PATHINFO_EXTENSION);
                    
                    $base_path = 'uploads/estudiantes/';
                
                    $student_folder = $estudiante['first_name'] . '_' . $estudiante['last_name'];
                
                    $student_path = $base_path . $student_folder;
                
                    if (!file_exists($student_path)) {
                        mkdir($student_path, 0777, true);
                    }
            
                    $new_filename = 'Documento_Firmado_' . $i . '_' . $estudiante['first_name'] . '_' . $estudiante['last_name'] . '_'.get_session_id().'.' . $file_extension;
                    $new_filename = str_replace(' ', '_', $new_filename);
            
                    $_FILES[$nombreCampo]['name'] = $new_filename;
                
                    $config['upload_path'] = $student_path;
                
                    $this->upload->initialize($config);
                
                    if ($this->upload->do_upload($nombreCampo)) {
                        // Archivo subido exitosamente
                        // Puedes realizar operaciones adicionales si es necesario
                    } else {
                        // Error al subir el archivo
                        set_alert('fail', translate('Algo salió mal con el archivo ' . $i));
                    }
                }
            }
            
            
                $this->db->set('contratoFirmado', 1);
                $this->db->where('student_id', $userID);
                $this->db->where('session_id', get_session_id());
                $this->db->update('enroll');
           
                $this->db->where('idEstudiante', $userID);
                $query = $this->db->get('estadoMatricula');
                
                if ($query->num_rows() > 0) {
                    // Si existe, realizar un update
                    $this->db->set('contratoFirmado', 1);
                    $this->db->where('idEstudiante', $userID);
                    $this->db->update('estadoMatricula');


                } else {
                    // Si no existe, realizar un insert
                    $data = array(
                        'idEstudiante' => $userID,
                        'contratoFirmado' => 1
                        // Puedes establecer otros campos en su valor predeterminado aquí si es necesario
                    );
                
    
    
                    $this->db->insert('estadoMatricula', $data);
                }
           




            // Enviar la respuesta JSON
            $array = array('status' => 'success');

            echo json_encode($array);
            exit();
        }
        

    

        $this->db->select('s.id as codigoEstudiante, s.*, e.*');
        $this->db->from('student s');
        $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
        $this->db->where('s.id', $userID);
        $query = $this->db->get();
        $this->data['documentos'] = $query->result_array();





        $this->data['title'] = translate('Expediente de postulante');
        $this->data['sub_page'] = 'citas/subirContrato';
        $this->data['main_menu'] = 'subirContrato';
        $this->load->view('layout/index', $this->data);
    }


    public function resultadoCitas()
    {
        $userID = get_loggedin_user_id();

        

    

        $this->db->select('s.id as codigoEstudiante, s.*, e.*');
        $this->db->from('student s');
        $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
        $this->db->where('s.id', $userID);
        $query = $this->db->get();
        $this->data['documentos'] = $query->result_array();





        $this->data['title'] = translate('Resultados Citas');
        $this->data['sub_page'] = 'citas/resultadoCitas';
        $this->data['main_menu'] = 'citas';
        $this->load->view('layout/index', $this->data);
    }



}
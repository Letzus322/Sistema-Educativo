<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Dashboard.php
 * @copyright : Reserved RamomCoder Team
 */

class Auxiliar extends Admin_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('dashboard_model');
    }

    public function index()
    {
         
        if ($_POST) {
                   
            $fecha = $_POST['fecha'];
            $timestamp = strtotime($fecha);
            $nombre_dia = date('l', $timestamp); 
            $dias_semana = [
                'Monday' => 'Lunes',
                'Tuesday' => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday' => 'Jueves',
                'Friday' => 'Viernes',
                'Saturday' => 'Sábado',
                'Sunday' => 'Domingo'
            ];
            $nombre_dia_espanol = $dias_semana[$nombre_dia]; // Obtener el nombre en español

            $hora_inicio = $_POST['hora_inicio'];
            $hora_fin = $_POST['hora_fin'];
            $capacidad = $_POST['capacidad_aula'];
            $nombre_aula = $_POST['nombre_aula'];

            
            
            $userId= get_loggedin_user_id();
            $data = array(
                'staff_id' =>$userId,
                'dia_semana' => $nombre_dia_espanol,
                'hora_inicio' => $hora_inicio,
                'hora_fin' => $hora_fin,
                'nombre_aula'=>$nombre_aula,
                'disponible' => $capacidad ,
                'fecha'=>  $fecha

            );
            $this->db->insert('work_schedules', $data);

            set_alert('success', translate('information_has_been_updated_successfully'));
            $array = array('status' => 'success');
            echo json_encode($array);
            exit();
           }


           $auxiliar_id =get_loggedin_user_id();
           $this->db->select('*');
           $this->db->from('work_schedules');
           $this->db->join('staff', 'staff.id = work_schedules.staff_id');
           $this->db->where('staff.id', $auxiliar_id);        
           $query = $this->db->get();
           $horarios = $query->result_array(); // Obtiene los resultados como un array
           $this->data['horarios'] = $horarios ;

        $this->data['title'] = 'Crear Horario';
            
           
        $this->data['sub_page'] = 'auxiliar/crearHorario';
        
        $language = 'en';
        $jsArray = array(
            'vendor/chartjs/chart.min.js',
            'vendor/echarts/echarts.common.min.js',
            'vendor/moment/moment.js',
            'vendor/fullcalendar/fullcalendar.js',
        ); 
       
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/fullcalendar/fullcalendar.css',
            ),
            'js' => $jsArray
        );
        $this->data['language'] = $language;
        $this->data['main_menu'] = 'Citas';
        $this->load->view('layout/index', $this->data);
    }


    public function citasPendientes()
    {
         
        $this->db->select('rp.*, s.*, ws.*, en.*, schoolyear.*, branch.*, parent.*,
        parent.id AS parent_id, parent.name AS parent_name,
        parent.relation AS parent_relation,
        parent.father_name AS parent_father_name,
        parent.mother_name AS parent_mother_name,
        parent.occupation AS parent_occupation,
        parent.income AS parent_income,
        parent.education AS parent_education,
        parent.email AS parent_email,
        parent.mobileno AS parent_mobileno,
        parent.address AS parent_address,
        parent.city AS parent_city,
        parent.state AS parent_state,
        parent.branch_id AS parent_branch_id,
        parent.photo AS parent_photo,
        parent.facebook_url AS parent_facebook_url,
        parent.linkedin_url AS parent_linkedin_url,
        parent.twitter_url AS parent_twitter_url,
        parent.created_at AS parent_created_at,
        parent.updated_at AS parent_updated_at,
        parent.active AS parent_active,
        class.name AS class_name,
        section.name AS section_name,
        student_category.name AS category_name,
        estadoMatricula.citaAcademica AS resultadoEnviado,
        branch.name AS branch_name,
        s.id as idEstudiante');
        $this->db->from('reservasAcademicas rp');
        $this->db->join('student s', 'rp.id_student = s.id');
        $this->db->join('work_schedules ws', 'rp.schedule_id = ws.schedule_id');
        $this->db->join('enroll en', 'en.student_id = rp.id_student');
        $this->db->join('class', 'class.id = en.class_id');
        $this->db->join('section', 'section.id = en.section_id');
        $this->db->join('student_category', 'student_category.id = s.category_id');
        $this->db->join('branch', 'branch.id = en.branch_id');
        $this->db->join('schoolyear', 'schoolyear.id = en.session_id');
        $this->db->join('parent', 'parent.id = s.parent_id');
        $this->db->join('estadoMatricula', 'estadoMatricula.idEstudiante = s.id');

        $this->db->where('ws.staff_id' ,get_loggedin_user_id());

        $query = $this->db->get();
        
        $result = $query->result_array();

        $this->data['students'] = $result;
        
       

           
        $this->data['title'] = 'Lista de Citas';
        $this->data['sub_page'] = 'auxiliar/citasPendientes';
        
        $language = 'en';
        $jsArray = array(
            'vendor/chartjs/chart.min.js',
            'vendor/echarts/echarts.common.min.js',
            'vendor/moment/moment.js',
            'vendor/fullcalendar/fullcalendar.js',
        ); 
       
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/fullcalendar/fullcalendar.css',
            ),
            'js' => $jsArray
        );
        $this->data['language'] = $language;
        $this->data['main_menu'] = 'Citas';
        $this->load->view('layout/index', $this->data);
    }


    public function enviarResultado()
    {
         
        $this->db->select('rp.*, s.*, ws.*, en.*, schoolyear.*, branch.*, parent.*,
        parent.id AS parent_id, parent.name AS parent_name,
        parent.relation AS parent_relation,
        parent.father_name AS parent_father_name,
        parent.mother_name AS parent_mother_name,
        parent.occupation AS parent_occupation,
        parent.income AS parent_income,
        parent.education AS parent_education,
        parent.email AS parent_email,
        parent.mobileno AS parent_mobileno,
        parent.address AS parent_address,
        parent.city AS parent_city,
        parent.state AS parent_state,
        parent.branch_id AS parent_branch_id,
        parent.photo AS parent_photo,
        parent.facebook_url AS parent_facebook_url,
        parent.linkedin_url AS parent_linkedin_url,
        parent.twitter_url AS parent_twitter_url,
        parent.created_at AS parent_created_at,
        parent.updated_at AS parent_updated_at,
        parent.active AS parent_active,
        class.name AS class_name,
        section.name AS section_name,
        student_category.name AS category_name,
        branch.name AS branch_name');
        $this->db->from('reservasAcademicas rp');
        $this->db->join('student s', 'rp.id_student = s.id');
        $this->db->join('work_schedules ws', 'rp.schedule_id = ws.schedule_id');
        $this->db->join('enroll en', 'en.student_id = rp.id_student');
        $this->db->join('class', 'class.id = en.class_id');
        $this->db->join('section', 'section.id = en.section_id');
        $this->db->join('student_category', 'student_category.id = s.category_id');
        $this->db->join('branch', 'branch.id = en.branch_id');
        $this->db->join('schoolyear', 'schoolyear.id = en.session_id');
        $this->db->join('parent', 'parent.id = s.parent_id');
        $this->db->where('ws.staff_id' ,get_loggedin_user_id());
        $this->db->where('rp.resultado' ,'Pendiente');

        $query = $this->db->get();
        
        

        
        $result = $query->result_array();
        
        $this->data['students'] = $result;
        
       



        if ($_POST) {
                   
           
            set_alert('success', translate('information_has_been_updated_successfully'));
           
          
           

            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);

            if (!empty($_FILES['archivo']['name'])) {
                // Obtiene la extensión del archivo
                $student_id = $this->input->post('student_id');
                $this->db->select('*');
                $this->db->from('student');
                $this->db->where('id', $student_id);
                $query = $this->db->get();
                $estudiante = $query->row_array();

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
               

                // Nuevo nombre de archivo
                $new_filename = 'ResultadosAcademicos'.'_' . $estudiante['first_name'] .'_'. $estudiante['last_name'] . '.' . $file_extension;
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
            $student_id = $this->input->post('student_id');

            $resultado= $this->input->post('estadoCita');
            $this->db->set('Resultado', $resultado); // Nuevo valor para la columna Resultado
            $this->db->where('id_student',$student_id); // Tabla que se va a actualizar
            $this->db->update('reservasAcademicas'); // Tabla que se va a actualizar



            if($resultado=='Aprobado'){
            // Verificar si el estudiante ya tiene un registro en la tabla
            $this->db->where('idEstudiante', $student_id);
            $query = $this->db->get('estadoMatricula');
            
            if ($query->num_rows() > 0) {
                // Si existe, realizar un update
                $this->db->set('citaAcademica', 1);
                $this->db->where('idEstudiante', $student_id);
                $this->db->update('estadoMatricula');
            } else {
                // Si no existe, realizar un insert
                $data = array(
                    'idEstudiante' => $student_id,
                    'citaAcademica' => 1
                    // Puedes establecer otros campos en su valor predeterminado aquí si es necesario
                );
            


                $this->db->insert('estadoMatricula', $data);
            }
            
        }





            // Enviar la respuesta JSON
            $array = array('status' => 'success');

            echo json_encode($array);
            exit();
        }









           
        $this->data['title'] = 'Enviar Resultados';
        $this->data['sub_page'] = 'auxiliar/enviarResultado';
        
        $language = 'en';
        $jsArray = array(
            'vendor/chartjs/chart.min.js',
            'vendor/echarts/echarts.common.min.js',
            'vendor/moment/moment.js',
            'vendor/fullcalendar/fullcalendar.js',
        ); 
       
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/fullcalendar/fullcalendar.css',
            ),
            'js' => $jsArray
        );
        $this->data['language'] = $language;
        $this->data['main_menu'] = 'Citas';
        $this->load->view('layout/index', $this->data);}

}



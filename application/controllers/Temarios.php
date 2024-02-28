<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Subject.php
 * @copyright : Reserved RamomCoder Team
 */

class Temarios extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('subject_model');
    }

    public function index()
    {
        if (!get_permission('subject', 'is_view')) {
            access_denied();
        } 
        

        $this->data['title'] = "Temarios";
        $this->data['sub_page'] = 'temarios/index';
        $this->data['main_menu'] = 'temarios';


        $this->db->select('temario.id as id ,bimestre.name as bimestre, unidad.name as unidad, subject.name as curso , grado.name as grado, temario.name as tema'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('temario');
        $this->db->join('bimestre', 'bimestre.id = temario.bimestre_id');
        $this->db->join('unidad', 'unidad.id = temario.unidad_id');
        $this->db->join('subject', 'subject.id = temario.curso_id');
        $this->db->join('grado', 'grado.id = temario.grado_id');
        $this->db->where('temario.session_id', get_session_id() );
        
        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();
        $this->data['temas'] = $result2;


        $this->load->view('layout/index', $this->data);
    }



    public function obtener_unidad($bimestre_id) {
        $this->db->select('unidad.id as id, unidad.name as name'); 
        $this->db->from('bimestre_unidad');
        $this->db->join('unidad', 'unidad.id = bimestre_unidad.id_unidad');
        
        $this->db->where('bimestre_unidad.id_bimestre',$bimestre_id);

        $query = $this->db->get(); // Ejecuta la consulta

        // Retorna los resultados como un array
        $result = $query->result_array();
        echo json_encode($result);

    }


    public function obtener_grados($branch_id) {
       
        $this->db->select('class.id as id, class.name as name'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('class');
        $this->db->where('class.branch_id', $branch_id);
        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();
        echo json_encode($result2);


  

    }

    public function obtener_curso($grado_id) {
        $this->db->select('class.id as class_id, class.name as name,sections_allocation.section_id as section_id '); 
        $this->db->from('grado');
        $this->db->join('class', 'grado.id = class.grado_id');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id');

        $this->db->where('grado.id', $grado_id);
        $this->db->limit(1); // Limitar los resultados a solo 1 registro
        $query = $this->db->get(); // Ejecuta la consulta
        $result = $query->row_array();

        $this->db->select('subject.id as id, subject.name as name'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('subject_assign');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id');
        $this->db->where('subject_assign.class_id', $result['class_id']);
        $this->db->where('subject_assign.section_id', $result['section_id']);
        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();
        echo json_encode($result2);


  

    }

    public function obtener_curso_subirTema($grado_id) {
        $this->db->select('class.id as class_id, class.name as name,sections_allocation.section_id as section_id '); 
        $this->db->from('class');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id');

        $this->db->where('class.id', $grado_id);
        $this->db->limit(1); // Limitar los resultados a solo 1 registro
        $query = $this->db->get(); // Ejecuta la consulta
        $result = $query->row_array();

        $this->db->select('subject.id as id, subject.name as name'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('subject_assign');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id');
        $this->db->where('subject_assign.class_id', $result['class_id']);
        $this->db->where('subject_assign.section_id', $result['section_id']);
        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();


        $this->db->select('section.id as id, section.name as name'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('sections_allocation');
        $this->db->join('section', 'section.id = sections_allocation.section_id');

        $this->db->where('sections_allocation.class_id', $grado_id);
        $query3 = $this->db->get(); 
        $result3 = $query3->result_array();

        $response = array(
            'section_list' => $result3,
            'subject_list' => $result2
        );
        echo json_encode($response);


  

    }


    public function obtener_tema() {
       
        $bimestre_id = $this->input->post('bimestre_id');
        $unidad_id = $this->input->post('unidad_id');
        $grado_id = $this->input->post('grado_id');
        $curso_id = $this->input->post('curso_id');

        $this->db->select('temario.id as id, temario.name as name'); 
        $this->db->from('temario');
        $this->db->join('class', 'class.grado_id = temario.grado_id');

        $this->db->where('temario.bimestre_id', $bimestre_id);
        $this->db->where('temario.unidad_id', $unidad_id);
        $this->db->where('class.id', $grado_id);
        $this->db->where('temario.curso_id', $curso_id);

        $this->db->where('temario.session_id', get_session_id() );

       
        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();
       
        echo json_encode($result2);



  

    }


    public function guardar_tema() {
       
        $curso_id = $this->input->post('curso_id');
        $bimestre_id = $this->input->post('bimestre_id');
        $unidad_id = $this->input->post('unidad_id');
        $grado_id = $this->input->post('grado_id');
        $tema = $this->input->post('tema');
        $data = array(
            'bimestre_id' => $bimestre_id,
            'unidad_id' => $unidad_id,
            'curso_id' => $curso_id,

            'grado_id' => $grado_id,

            'name' => $tema,
            'session_id' => get_session_id() // Obtener el ID de sesión según tu lógica de aplicación
        );
    
        // Insertar los datos en la tabla exonerados
        $this->db->insert('temario', $data);
    
        }

    // subject edit page
    public function edit($id = '')
    {
        if (!get_permission('subject', 'is_edit')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->data['subject'] =  $this->db->get('subject')->row_array();  
        $this->data['title'] = translate('subject');
        $this->data['sub_page'] = 'subject/edit';
        $this->data['main_menu'] = 'subject';
        $this->load->view('layout/index', $this->data);
    }
    public function edit_area($id = '')
    {
        if (!get_permission('subject', 'is_edit')) {
            access_denied();
        }
        $this->db->where('id', $id);

        $this->data['subject'] = $this->db->get('area')->row_array();
        $this->data['title'] = translate('subject');
        $this->data['sub_page'] = 'subject/edit_area';
        $this->data['main_menu'] = 'subject';
        $this->load->view('layout/index', $this->data);
    }
    // moderator subject all information
    public function save()
    {
        if ($_POST) {
            $this->form_validation->set_rules('area', translate('Necesita Area'), 'trim|required');

            $this->form_validation->set_rules('name', translate('subject_name'), 'trim|required');
            $this->form_validation->set_rules('subject_code', translate('subject_code'), 'trim|required');
            $this->form_validation->set_rules('subject_type', translate('subject_type'), 'trim|required');
            if ($this->form_validation->run() !== false) {
                $arraySubject = array(
                    'area_id' => $this->input->post('area'),
                    'name' => $this->input->post('name'),
                    'subject_code' => $this->input->post('subject_code'),
                    'subject_type' => $this->input->post('subject_type'),
                    'subject_author' => $this->input->post('subject_author'),
                    'branch_id' => 1,
                );
                $subjectID = $this->input->post('subject_id');
                if (empty($subjectID)) {
                    if (get_permission('subject', 'is_add')) {
                        $this->db->insert('subject', $arraySubject);
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    if (get_permission('subject', 'is_edit')) {
                        if (!is_superadmin_loggedin()) {
                            $this->db->where('branch_id', get_loggedin_branch_id());
                        }
                        $this->db->where('id', $subjectID);
                        $this->db->update('subject', $arraySubject);
                    }
                    set_alert('success', translate('information_has_been_updated_successfully'));
                }
                $url = base_url('subject');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
                
            }
            echo json_encode($array);
        }
    }

    public function saveArea()
    {
        if ($_POST) {

            $this->form_validation->set_rules('name', translate('subject_name'), 'trim|required');
            $this->form_validation->set_rules('subject_code', translate('subject_code'), 'trim|required');
            if ($this->form_validation->run() !== false) {
                $arraySubject = array(
                    'name' => $this->input->post('name'),
                    'name_code' => $this->input->post('subject_code'),
                   
                );
                $areaID = $this->input->post('area_id');
                if (empty($areaID)) {
                    if (get_permission('subject', 'is_add')) {
                        $this->db->insert('area', $arraySubject);
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    if (get_permission('subject', 'is_edit')) {
                        if (!is_superadmin_loggedin()) {
                            $this->db->where('branch_id', get_loggedin_branch_id());
                        }
                        $this->db->where('id', $areaID);
                        $this->db->update('area', $arraySubject);
                    }
                    set_alert('success', translate('information_has_been_updated_successfully'));
                }
                $url = base_url('subject');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }


    public function delete($id = '')
    {
        if (get_permission('subject', 'is_delete')) {
            $this->db->where('id', $id);
            $this->db->delete('temario');
            
        }
    }


    public function tema_dictado()
    {
       
        

        $this->data['title'] = "Subir tema desarrollado";
        $this->data['sub_page'] = 'temarios/tema_dictado';
        $this->data['main_menu'] = 'subject';


        $this->db->select('temas_dictados.id as id ,bimestre.name as bimestre, unidad.name as unidad, subject.name as curso ,branch.name as branch,section.name as section, class.name as grado, temario.name as tema'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('temas_dictados');
        $this->db->join('temario', 'temario.id = temas_dictados.tema_id');

        $this->db->join('bimestre', 'bimestre.id = temas_dictados.bimestre_id');
        $this->db->join('unidad', 'unidad.id = temas_dictados.unidad_id');
        $this->db->join('subject', 'subject.id = temas_dictados.curso_id');


        $this->db->join('class', 'class.id = temas_dictados.class_id');
        $this->db->join('branch', 'branch.id = class.branch_id');

        $this->db->join('section', 'section.id = temas_dictados.section_id');

        $this->db->where('temas_dictados.session_id', get_session_id() );
        $this->db->where('temas_dictados.teacher_id', get_loggedin_user_id() );

        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();
        $this->data['temas'] = $result2;


        $this->load->view('layout/index', $this->data);
    }

    public function delete_tema_dictado($id = '')
    {
         
            $this->db->where('id', $id);
            $this->db->delete('temas_dictados');
          
    }

    public function guardar_tema_dictado() {
		
        $bimestre_id = $this->input->post('bimestre_id');
        $unidad_id = $this->input->post('unidad_id');
        $grado_id = $this->input->post('grado_id');
        $section_id = $this->input->post('section_id');
        $curso_id = $this->input->post('curso_id');
        $tema_id = $this->input->post('tema_id');
        $comentario = $this->input->post('comentario');
        $fecha_clase = $this->input->post('fecha_clase');

        $data = array(
            'bimestre_id' => $bimestre_id,
            'unidad_id' => $unidad_id,
            'class_id' => $grado_id,
            'section_id' => $section_id,
            'curso_id' => $curso_id,
            'tema_id' => $tema_id,
            'teacher_id' => get_loggedin_user_id(),

            'session_id' => get_session_id(),

            'fecha_clase'=> $fecha_clase,
            'comentario'=> $comentario,
        );
    
        // Insertar los datos en la tabla exonerados
        $this->db->insert('temas_dictados', $data);
    
        }
   
}

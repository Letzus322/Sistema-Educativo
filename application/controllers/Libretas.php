<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Libretas extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('fees_model');
    }

   
    

    public function ver_aulas()
    {
       
      
       
        
       // Realizar la consulta con join
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion, staff.name as profesorNombre,
        section.id as section_id'); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');
        $this->db->join('teacher_allocation ', 'section.id = teacher_allocation.section_id AND  class.id = teacher_allocation.class_id');
        $this->db->join('staff', 'staff.id = teacher_allocation.teacher_id');
        $this->db->where('teacher_allocation.session_id', get_session_id());

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'libretas/ver_aulas';
        $this->data['main_menu'] = 'subject/class_assign';
        
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


        $this->db->select('bimestre.*,periodo_bimestre.id as id_bimestre_fecha  ,periodo_bimestre.fecha_inicio as fechaInicio, periodo_bimestre.fecha_fin as fechaFin, fecha_inicio_notas as fechaInicioNotas, fecha_fin_notas as fechaFinNotas')->from('bimestre');
        $this->db->join('periodo_bimestre', 'periodo_bimestre.id_bimestre = bimestre.id');
        $this->db->where('periodo_bimestre.id_session',get_session_id() );
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data['bimestres'] = $result;



        $this->load->view('layout/index', $this->data);


        
    }


    public function ver_alumnos($grado_seccion,$bimestre =null)
    {
       
      
       
        $this->db->select('*, student.first_name as nombre, student.last_name as apellido, student.id as student_id'); // Selecciona todas las columnas
        $this->db->from('enroll');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = enroll.class_id AND sections_allocation.section_id = enroll.section_id');
        $this->db->join('class', 'class.id = sections_allocation.class_id');
        $this->db->join('section', 'section.id = sections_allocation.section_id');
        $this->db->join('student', 'student.id = enroll.student_id');

        $this->db->where('sections_allocation.id', $grado_seccion);
        $this->db->where('enroll.session_id', get_session_id());
        
    
        $query = $this->db->get(); // Ejecuta la consulta
       

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();
        $this->data['bimestre'] = $bimestre;


        $this->db->select('*, student.first_name as nombre, student.last_name as apellido, class.name as class_name, section.name as section, 
        branch.name as branch, schoolyear.school_year as school_year,sections_allocation.id as sections_allocation_id, staff.name as profesor'); // Selecciona todas las columnas
        $this->db->from('enroll');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = enroll.class_id AND sections_allocation.section_id = enroll.section_id');
        $this->db->join('class', 'class.id = sections_allocation.class_id');
        $this->db->join('section', 'section.id = sections_allocation.section_id');
        $this->db->join('student', 'student.id = enroll.student_id');
        $this->db->join('branch', 'branch.id = enroll.branch_id');
        $this->db->join('schoolyear', 'schoolyear.id = enroll.session_id');

        
        $this->db->join('teacher_allocation', 'teacher_allocation.class_id = class.id AND teacher_allocation.section_id = section.id');
        $this->db->join('staff', 'staff.id = teacher_allocation.teacher_id');

        
        $this->db->where('sections_allocation.id', $grado_seccion);
        $this->db->where('enroll.session_id', get_session_id());
        $this->db->limit(1); // Limitar a una sola fila
        
        $query_single_row = $this->db->get(); // Ejecuta la consulta
        
        // Obtener la fila única
        $this->data['informacionSalon'] = $query_single_row->row();



        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'libretas/ver_alumnos';
        $this->data['main_menu'] = 'subject/class_assign';
        
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


        $this->db->select('bimestre.*,periodo_bimestre.id as id_bimestre_fecha  ,periodo_bimestre.fecha_inicio as fechaInicio, periodo_bimestre.fecha_fin as fechaFin, fecha_inicio_notas as fechaInicioNotas, fecha_fin_notas as fechaFinNotas')->from('bimestre');
        $this->db->join('periodo_bimestre', 'periodo_bimestre.id_bimestre = bimestre.id');
        $this->db->where('periodo_bimestre.id_session',get_session_id() );
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data['bimestres'] = $result;



        $this->load->view('layout/index', $this->data);


        
    }

    public function ver_libreta($student_id,$bimestre )
    {
       
      
       
        $this->db->select('*, student.first_name as nombre, student.last_name as apellido, student.id as student_id, area.name as area,subject.name as curso
         , area.id as area_id, student.id as student_id,subject_assign_competencias.name as competencia '); // Selecciona todas las columnas
        $this->db->from('enroll');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = enroll.class_id AND sections_allocation.section_id = enroll.section_id');
        $this->db->join('class', 'class.id = sections_allocation.class_id');
        $this->db->join('section', 'section.id = sections_allocation.section_id');
        $this->db->join('student', 'student.id = enroll.student_id');

        $this->db->join('subject_assign', 'subject_assign.class_id = section.id and  subject_assign.section_id = section.id ');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id');
        $this->db->join('area', 'subject.area_id = area.id');

        $this->db->join('subject_assign_competencias', 'subject_assign_competencias.subject_assign_id = subject_assign.id');

       


        $this->db->where('enroll.student_id', $student_id );
        $this->db->where('subject_assign_competencias.bimestre', $bimestre );

        $this->db->where('subject_assign.session_id', get_session_id());

        $this->db->where('enroll.session_id', get_session_id());
        $this->db->order_by('area.id');

    
        $query = $this->db->get(); // Ejecuta la consulta
       

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();
        $this->data['bimestre'] = $bimestre;


        $this->db->select(' student.first_name as nombre, student.last_name as apellido, class.name as class_name, section.name as section, 
        branch.name as branch, schoolyear.school_year as school_year'); // Selecciona todas las columnas
        $this->db->from('enroll');
        $this->db->join('class', 'class.id = enroll.class_id');
        $this->db->join('section', 'section.id = enroll.section_id');

        $this->db->join('student', 'student.id = enroll.student_id');
        $this->db->join('branch', 'branch.id = enroll.branch_id');
        $this->db->join('schoolyear', 'schoolyear.id = enroll.session_id');




        
        $this->db->where('enroll.session_id', get_session_id());
        $this->db->limit(1);
        
        $query_single_row = $this->db->get(); // Ejecuta la consulta
        
        // Obtener la fila única
        $this->data['informacionSalon'] = $query_single_row->row();



        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'libretas/ver_libreta';
        $this->data['main_menu'] = 'subject/class_assign';
        
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


        $this->db->select('bimestre.*,periodo_bimestre.id as id_bimestre_fecha  ,periodo_bimestre.fecha_inicio as fechaInicio, periodo_bimestre.fecha_fin as fechaFin, fecha_inicio_notas as fechaInicioNotas, fecha_fin_notas as fechaFinNotas')->from('bimestre');
        $this->db->join('periodo_bimestre', 'periodo_bimestre.id_bimestre = bimestre.id');
        $this->db->where('periodo_bimestre.id_session',get_session_id() );
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data['bimestres'] = $result;



        $this->load->view('layout/index', $this->data);


        
    }


}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Fees.php
 * @copyright : Reserved RamomCoder Team
 */

class Notas extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('fees_model');
    }

   

    /* fees type form validation rules */

    public function actualizar_fechas() {
        // Obtén los datos del cuerpo de la solicitud POST
        $postData = $this->input->post();

      
        foreach ($postData['bimestres'] as $bimestre) {
            $data = array(
                'fecha_inicio' => $bimestre['fechaInicio'],
                'fecha_fin' => $bimestre['fechaFin'],
                'fecha_inicio_notas' => $bimestre['fechaInicioNotas'],
                'fecha_fin_notas' => $bimestre['fechaFinNotas']
            );
            
            $this->db->where('id', $bimestre['id']);
            $this->db->update('periodo_bimestre', $data);

        }

        foreach ($postData['unidades'] as $unidad) {
            $data = array(
                'fecha_inicio' => $unidad['fechaInicio'],
                'fecha_fin' => $unidad['fechaFin']
            );
            $this->db->where('id', $unidad['id']);
            $this->db->update('periodo_unidad', $data);      
        }

        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }


    public function bimestre()
    {
       
      
       
        
      

        $this->data['title'] = "Bimestres";
        $this->data['sub_page'] = 'notas/bimestre';
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

    public function subir_notas($curso_asignado,$bimestre)
    {
       
      
        $this->db->select('subject.name as curso, subject_assign.id as id, subject_assign.branch_id as branch_id , subject_assign.id as id_subject_assign');
        $this->db->from('subject_assign');
        $this->db->join('sections_allocation', 'subject_assign.class_id = sections_allocation.class_id AND subject_assign.section_id = sections_allocation.section_id');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id');
        $this->db->join('area', 'subject.area_id = area.id');

        $this->db->where('subject_assign.id', $curso_asignado);
        $query = $this->db->get();
        $this->data['curso_asignado'] = $query->row_array(); // Utiliza row_array() para obtener el primer registro
        $this->data['bimestre'] = $bimestre;


        $this->db->select('student.id as id,student.first_name as nombre, student.last_name as apellido');
        $this->db->from('subject_assign');
        $this->db->join('enroll', 'subject_assign.class_id = enroll.class_id AND subject_assign.section_id = enroll.section_id');
        $this->db->join('student', 'student.id = enroll.student_id');
        $this->db->where('subject_assign.id', $curso_asignado);
        $this->db->where('enroll.session_id',get_session_id() );
        $this->db->order_by('student.last_name', 'ASC'); 
        $this->db->order_by('student.first_name', 'ASC');
        $query = $this->db->get();
        $this->data['alumnos'] = $query->result_array(); // Utiliza row_array() para obtener el primer registro


       // Realizar la consulta con join
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion'); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";

        if(loggedin_role_id()!=1){

        $this->data['sub_page'] = 'notas/subir_notas';}
        if(loggedin_role_id()==1){

            $this->data['sub_page'] = 'notas/subir_notas_admin';}

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

    


    public function evaluacion_curso_crear($curso_asignado,$bimestre)
    {
       
      
        $this->db->select('subject.name as curso, subject_assign.id as id, subject_assign.branch_id as branch_id , subject_assign.id as id_subject_assign');
        $this->db->from('subject_assign');
        $this->db->join('sections_allocation', 'subject_assign.class_id = sections_allocation.class_id AND subject_assign.section_id = sections_allocation.section_id');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id');
        $this->db->join('area', 'subject.area_id = area.id');
        $this->db->where('subject_assign.id', $curso_asignado);
        
        $query = $this->db->get();
        $this->data['curso_asignado'] = $query->row_array(); // Utiliza row_array() para obtener el primer registro
        $this->data['bimestre'] = $bimestre;

        
       // Realizar la consulta con join
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion'); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'notas/evaluacion_curso_competencias';
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


    public function evaluacion_curso_capacidad_crear($competencia_asignado)
    {
       
      
        $this->db->select('*');
        $this->db->from('subject_assign_competencias');

        $this->db->where('subject_assign_competencias.id', $competencia_asignado);
        
        $query = $this->db->get();
        $this->data['competencia'] = $query->row_array(); // Utiliza row_array() para obtener el primer registro

        
       // Realizar la consulta con join
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion'); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'notas/evaluacion_curso_capacidad';
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


    public function evaluacion_curso_capacidades_agregar() {

        $postData = $this->input->post();

      
        foreach ($postData['capacidades'] as $capacidad) {
            $data = array(
                'name'=>$capacidad['name'],
                'color'=>$capacidad['color'],
                'peso'=>$capacidad['peso'],
                'competencia_assign_id'=>$capacidad['subject_assign_id'],

            );
            if($data['name']!=null){
            $this->db->insert('subject_assign_capacidades', $data);
        }

        }

        foreach ($postData['capacidadesActualizar'] as $capacidad) {
            $data = array(
                'name'=>$capacidad['name'],
                'color'=>$capacidad['color'],
                'peso'=>$capacidad['peso'],
               
            );
            $capacidad_id = $capacidad['id'];

            $this->db->where('id', $capacidad_id);

            // Realiza la actualización en la tabla 'subject_assign_competencias'
            $this->db->update('subject_assign_capacidades', $data);

        }
        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }

    public function evaluacion_curso_indicador_crear($capacidad_asignado)
    {
       
      
        $this->db->select('*');
        $this->db->from('subject_assign_capacidades');
        $this->db->where('subject_assign_capacidades.id', $capacidad_asignado);
        
        $query = $this->db->get();
        $this->data['capacidad'] = $query->row_array(); // Utiliza row_array() para obtener el primer registro

        
       // Realizar la consulta con join
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion'); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'notas/evaluacion_curso_indicadores';
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

    public function evaluacion_curso_indicadores_agregar() {

        $postData = $this->input->post();

      
        foreach ($postData['indicadores'] as $indicador) {
            $data = array(
                'name'=>$indicador['name'],
                'color'=>$indicador['color'],
                'peso'=>$indicador['peso'],
                'capacidad_assign_id'=>$indicador['subject_assign_id'],

            );
            if($data['name']!=null){
            $this->db->insert('subject_assign_indicadores', $data);
        }

        }




        foreach ($postData['indicadoresActualizar'] as $indicador) {
            $data = array(
                'name'=>$indicador['name'],
                'color'=>$indicador['color'],
                'peso'=>$indicador['peso'],
               
            );
            $indicador_id = $indicador['id'];

            $this->db->where('id', $indicador_id);

            // Realiza la actualización en la tabla 'subject_assign_competencias'
            $this->db->update('subject_assign_indicadores', $data);

        }
        
    }

    public function evaluacion_curso($id_grado_seccion)
    {
       
      
        $this->db->select('area.*, sections_allocation.id as sections_allocation_id');
        $this->db->from('subject_assign');
        $this->db->join('sections_allocation', 'subject_assign.class_id = sections_allocation.class_id AND subject_assign.section_id = sections_allocation.section_id');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id');
        $this->db->join('area', 'subject.area_id = area.id');
        $this->db->where('sections_allocation.id', $id_grado_seccion);
        $this->db->where('subject_assign.session_id',get_session_id());

        $this->db->group_by('area.id');
        
        $query = $this->db->get();
        $this->data['areas'] = $query->result_array();
        
        
       // Realizar la consulta con join
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion'); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'notas/evaluacion_curso';
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
    




    public function registrar_notas()
    {
       
      
       
        
        $this->db->select('timetable_class.class_id, timetable_class.section_id, timetable_class.subject_id, class.name as name, b.name as branch_name, section.name as sectionName, 
        timetable_class.teacher_id, subject.name as subject_name, area.name as area_name ,subject_assign.id as subject_assign_id');

        $this->db->distinct();
        $this->db->from('timetable_class');
        $this->db->join('class', 'class.id = timetable_class.class_id');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('section', 'section.id = timetable_class.section_id', 'left');
        $this->db->join('subject', 'subject.id = timetable_class.subject_id');
        $this->db->join('area', 'area.id = subject.area_id');
        $this->db->join('subject_assign', 'subject_assign.class_id = class.id AND subject_assign.section_id = section.id AND subject_assign.subject_id = subject.id');

        if(loggedin_role_id()!=1){
        $this->db->where('timetable_class.teacher_id', get_loggedin_user_id());
    }

        $this->db->where('timetable_class.session_id', get_session_id());

        $this->db->where('subject_assign.session_id', get_session_id());

        $query = $this->db->get();
        
        

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'notas/registrar_notas';
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


    public function prorroga() {


        // Obtener los datos enviados por la solicitud AJAX
        $nuevaFechaCierre = $this->input->post('nuevaFechaCierre');
        $bimestre = $this->input->post('bimestre');
        $subjectAssignId = $this->input->post('subjectAssignId');
        
        $data = array(
            'subject_assignId' => $subjectAssignId,
            'bimestre' => $bimestre,
            'nueva_fecha_cierre' => $nuevaFechaCierre
        );
        $this->db->insert('prorroga_fecha', $data);

        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }

    public function obtener_asignaturas_por_estudiante($student_id) {
        $this->db->select('subject_assign.id as id, subject.name as name'); 
        $this->db->from('enroll');
        $this->db->join('subject_assign', 'subject_assign.class_id = enroll.class_id AND subject_assign.section_id = enroll.section_id');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id ');

        $this->db->where('enroll.student_id', $student_id);
        $this->db->where('enroll.session_id', get_session_id());
        $this->db->where('subject_assign.session_id', get_session_id());
        $this->db->where('subject.exonerar', 1);

        $query = $this->db->get(); // Ejecuta la consulta

        // Retorna los resultados como un array
        $result = $query->result_array();
        echo json_encode($result);

    }
    public function exonerar_curso() {


       
       // Realizar la consulta con join
       $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion'); // Selecciona las columnas que necesitas
       $this->db->from('class');
       $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
       $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
       $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

       
       $query = $this->db->get();

       // Obtener los resultados
       $this->data['classlist'] = $query->result_array();


       $this->db->select('*, student.last_name as apellido, student.first_name as nombre, subject.name as curso, 
       section.name as section ,class.name as class, branch.name as branch'); // Selecciona las columnas que necesitas
       $this->db->from('exonerados');
       $this->db->join('student' , 'student.id = exonerados.id_student', 'left');
       $this->db->join('subject_assign' , 'subject_assign.id = exonerados.subject_assign_id', 'left');
       $this->db->join('subject' , 'subject.id = subject_assign.subject_id', 'left');
       $this->db->join('enroll' , 'student.id = enroll.student_id', 'left');

       $this->db->join('section ', 'section.id = enroll.section_id');
       $this->db->join('class ', 'class.id = enroll.section_id');
       $this->db->join('branch ', 'branch.id = enroll.branch_id');


       $this->db->where('enroll.session_id',get_session_id() );
       $this->db->where('exonerados.id_session',get_session_id() );

       
       $query = $this->db->get();

       // Obtener los resultados
       $this->data['classlist_exonerados'] = $query->result_array();

       $this->data['title'] = "Exonerar Curso";
       $this->data['sub_page'] = 'notas/exonerar_curso';
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

    public function guardar_exonerar() {

    $studentId = $this->input->post('student_id');
    $subjectAssignId = $this->input->post('subject_assign_id');
    $data = array(
        'id_student' => $studentId,
        'subject_assign_id' => $subjectAssignId,
        'id_session' => get_session_id() // Obtener el ID de sesión según tu lógica de aplicación
    );

    // Insertar los datos en la tabla exonerados
    $this->db->insert('exonerados', $data);

    }

    
    public function evaluacion()
    {
       
      
       
        
       // Realizar la consulta con join
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion'); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'notas/evaluacion';
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

    public function evaluacion_curso_competencias_agregar() {

        $postData = $this->input->post();

      
        foreach ($postData['competencias'] as $competencia) {
            $data = array(
                'name'=>$competencia['name'],
                'color'=>$competencia['color'],
                'peso'=>$competencia['peso'],
                'subject_assign_id'=>$competencia['subject_assign_id'],
                'bimestre'=>$competencia['bimestre'],

            );
            if($data['name']!=null){
            $this->db->insert('subject_assign_competencias', $data);
        }

        }


        foreach ($postData['compatenciasActualizar'] as $competencia) {
            $data = array(
                'name'=>$competencia['name'],
                'color'=>$competencia['color'],
                'peso'=>$competencia['peso'],
               
            );
            $competencia_id = $competencia['id'];

            $this->db->where('id', $competencia_id);

            // Realiza la actualización en la tabla 'subject_assign_competencias'
            $this->db->update('subject_assign_competencias', $data);

        }
       
        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }

    public function copiar_entre_bimestres() {

        $postData = $this->input->post();

        // Accede a la variable 'curso' desde $postData
        $cursoId = $postData['curso'];
       
        $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

        $query = $this->db->get_where('subject_assign_competencias', array(
            'subject_assign_id' => $cursoId, 'bimestre' => 1
        ));



        $result = $query->result_array();

        foreach ($result as $row) {

            for ($bimestre = 2; $bimestre <= 4; $bimestre++){


                $data = array(
                    'subject_assign_id' => $row['subject_assign_id'],
                    'name' => $row['name'],
                    'color' => $row['color'],
                    'peso' => $row['peso'],
                    'bimestre' => $bimestre, // Utiliza el valor actual del bimestre en el bucle
                    // Agrega otros campos si es necesario
                );
        
                // Inserta en la misma tabla
                $this->db->insert('subject_assign_competencias', $data);

                $idCompotencia = $this->db->insert_id();


                $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                $query2 = $this->db->get_where('subject_assign_capacidades', array(
                    'competencia_assign_id' => $row['id']
                ));
                $result2 = $query2->result_array();

                foreach ($result2 as $row2) {    

                
                    
                    $data = array(
                        'competencia_assign_id' =>$idCompotencia,
                        'name' => $row2['name'],
                        'color' => $row2['color'],
                        'peso' => $row2['peso'],
                    );

                    $this->db->insert('subject_assign_capacidades', $data);


                    $idCapacidad = $this->db->insert_id();


                    $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia
    
                    $query3 = $this->db->get_where('subject_assign_indicadores', array(
                        'capacidad_assign_id' => $row2['id']
                    ));
                    $result3 = $query3->result_array();
    


                    foreach ($result3 as $row3) {    

                
                    
                        $data = array(
                            'capacidad_assign_id' =>$idCapacidad,
                            'name' => $row3['name'],
                            'color' => $row3['color'],
                            'peso' => $row3['peso'],
                        );
    
                        $this->db->insert('subject_assign_indicadores', $data);
                    }
    

                }


            }
        }


        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }





    public function copiar($curso_asignado)
    {
        $this->db->select('subject.*, subject_assign.id as curso_asignado,class.name as class, section.name as section,class.id as class_id, section.id as section_id ');
        $this->db->from('subject_assign');
        $this->db->join('sections_allocation', 'subject_assign.class_id = sections_allocation.class_id AND subject_assign.section_id = sections_allocation.section_id');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id');
        $this->db->join('area', 'subject.area_id = area.id');
        $this->db->join('class', 'class.id = subject_assign.class_id');
        $this->db->join('section', 'section.id = subject_assign.section_id');

        $this->db->where('subject_assign.id', $curso_asignado);

        $query = $this->db->get();
        $this->data['curso'] = $query->row_array();

        
        $this->db->select('class.*, b.name as branch_name, section.name as sectionName, sections_allocation.id as id_grado_seccion,class.id as class_id, section.id as section_id '); // Selecciona las columnas que necesitas
        $this->db->from('class');
        $this->db->join('branch as b', 'b.id = class.branch_id', 'left');
        $this->db->join('sections_allocation', 'sections_allocation.class_id = class.id', 'left');
        $this->db->join('section ', 'section.id = sections_allocation.section_id', 'left');

        
        $query = $this->db->get();

        // Obtener los resultados
        $this->data['classlist'] = $query->result_array();

        $this->data['title'] = "Evaluacion";
        $this->data['sub_page'] = 'notas/copiarEvaluacion';
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


       
        $this->load->view('layout/index', $this->data);


        
    }




    public function copiar_entre_secciones() {

        $postData = $this->input->post();

        // Accede a la variable 'curso' desde $postData
        $selectedCourses = $postData['selectedCourses'];
        $cursoBase = $postData['cursoBase'];

        foreach ($selectedCourses as $selectedCourse) {


        for ($bimestre = 1; $bimestre <= 4; $bimestre++){


        $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

        $query = $this->db->get_where('subject_assign_competencias', array(
            'subject_assign_id' => $cursoBase, 'bimestre' => $bimestre
        ));



        $result = $query->result_array();

        foreach ($result as $row) {



                $data = array(
                    'subject_assign_id' => $selectedCourse,
                    'name' => $row['name'],
                    'color' => $row['color'],
                    'peso' => $row['peso'],
                    'bimestre' => $bimestre, // Utiliza el valor actual del bimestre en el bucle
                    // Agrega otros campos si es necesario
                );
        
                // Inserta en la misma tabla
                $this->db->insert('subject_assign_competencias', $data);

                $idCompotencia = $this->db->insert_id();


                $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia

                $query2 = $this->db->get_where('subject_assign_capacidades', array(
                    'competencia_assign_id' => $row['id']
                ));
                $result2 = $query2->result_array();

                foreach ($result2 as $row2) {    

                
                    
                    $data = array(
                        'competencia_assign_id' =>$idCompotencia,
                        'name' => $row2['name'],
                        'color' => $row2['color'],
                        'peso' => $row2['peso'],
                    );

                    $this->db->insert('subject_assign_capacidades', $data);


                    $idCapacidad = $this->db->insert_id();


                    $this->db->order_by('id', 'ASC'); // Ajusta 'ASC' o 'DESC' según tu preferencia
    
                    $query3 = $this->db->get_where('subject_assign_indicadores', array(
                        'capacidad_assign_id' => $row2['id']
                    ));
                    $result3 = $query3->result_array();
    


                    foreach ($result3 as $row3) {    

                
                    
                        $data = array(
                            'capacidad_assign_id' =>$idCapacidad,
                            'name' => $row3['name'],
                            'color' => $row3['color'],
                            'peso' => $row3['peso'],
                        );
    
                        $this->db->insert('subject_assign_indicadores', $data);
                    }
    

                }


            
            }
        }
        }

        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }




    public function guardar_notas() {

        $postData = $this->input->post();

      
        foreach ($postData['notasIndicadores'] as $notas) {
            $id_alumno = $notas['idAlumno'];
            $id_indicador = $notas['idIndicador'];
            $nota = $notas['nota'];
            
            $query = $this->db->get_where('notas_alumnos', array('id_alumno' => $id_alumno, 'id_indicador' => $id_indicador));
            $result = $query->row();
            
            if ($result) {
                // Si ya existe un registro, actualiza la nota
                $this->db->where(array('id_alumno' => $id_alumno, 'id_indicador' => $id_indicador));
                $this->db->update('notas_alumnos', array('nota' => $nota));
            } else {
                // Si no existe un registro, inserta uno nuevo
                $this->db->insert('notas_alumnos', array('id_alumno' => $id_alumno, 'id_indicador' => $id_indicador, 'nota' => $nota));
            }


            

        }
        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }


    public function reactivar_envio_nota() {


        $bimestre = $this->input->post('bimestre');
        $id_subject_assign = $this->input->post('id_subject_assign');
        
        $this->db->where('bimestre', $bimestre);
        $this->db->where('id_subject_assign', $id_subject_assign);
        $this->db->delete('ewgcgdaj_instituto.envio_notas_bimestre');
        

        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }

    public function enviar_notas() {


        $bimestre = $this->input->post('bimestre');
        $subject_assign_id = $this->input->post('subject_assign_id');
       
        $data = array(
            'bimestre' => $bimestre, // Suponiendo que $bimestre contiene el valor del bimestre
            'id_subject_assign' => $subject_assign_id // Suponiendo que $subject_assign_id contiene el ID del subject_assign
        );
        
        $this->db->insert('envio_notas_bimestre', $data);
        // Retorna una respuesta (puede ser un mensaje de éxito, error, etc.)
        $response = array('success' => true, 'message' => 'Fechas guardadas correctamente');
        echo json_encode($response);

        
    }



    /* fees type control */
    public function index()
    {
        if (!get_permission('fees_type', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('fees_type', 'is_add')) {
                ajax_access_denied();
            }
            $this->type_validation();
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $this->fees_model->typeSave($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['categorylist'] = $this->app_lib->getTable('fees_type', array('system' => 0));
        $this->data['title'] = translate('fees_type');
        $this->data['sub_page'] = 'fees/type';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function type_edit($id = '')
    {
        if (!get_permission('fees_type', 'is_edit')) {
            access_denied();
        }

        if ($_POST) {
            $this->type_validation();
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $this->fees_model->typeSave($post);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('fees/type');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['category'] = $this->app_lib->getTable('fees_type', array('t.id' => $id), true);
        $this->data['title'] = translate('fees_type');
        $this->data['sub_page'] = 'fees/type_edit';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function type_delete($id = '')
    {
        if (get_permission('fees_type', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('fees_type');
        }
    }

    
}

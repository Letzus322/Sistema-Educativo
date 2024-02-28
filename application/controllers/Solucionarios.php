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

class Solucionarios extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('subject_model');
    }

    public function index()
    {
       

        if (isset($_POST['subirArchivo'])) {
                   
           
            $titulo = $this->input->post('titulo');
            $tipo = $this->input->post('tipo');
            $gradoId = $this->input->post('grado_id');
            $sectionId = $this->input->post('section_id');
            $fecha = $this->input->post('fecha');
        
            $data = array(
                'titulo' => $titulo,
                'tipo' => $tipo,
                'grado_id' => $gradoId,
                'section_id' => $sectionId,
                'fecha' => $fecha,
                'session_id'=>get_session_id(),
            );
        
            // Insertar los datos en la tabla solucionarios
            $this->db->insert('solucionarios', $data);
            $ultimo_id = $this->db->insert_id();





          
    
            $this->db->select('student.id as id , UPPER(student.last_name) as last_name, UPPER(student.first_name) as first_name');
            $this->db->from('enroll');
            $this->db->join('student', 'student.id = enroll.student_id');
            
            $this->db->where('enroll.session_id',get_session_id());
            $this->db->where('enroll.class_id',$gradoId);
            $this->db->where('enroll.section_id',$sectionId);
    
            $query = $this->db->get(); // Ejecuta la consulta
    
            // Retorna los resultados como un array
            $estudiantes = $query->result_array();




            $alumnosSeleccionados = $this->input->post('alumno');

            
            foreach ($estudiantes as $estudiante) {

                if (in_array($estudiante['id'], $alumnosSeleccionados)) {
                   $visibilidad =1;
                }
                else{
                    $visibilidad =0;

                }
                // Realizar el insert en la base de datos para cada alumno
                $data = array(
                    'id_solucionario' => $ultimo_id, // Suponiendo que $ultimo_id es el ID del solucionario recién insertado
                    'id_student' => $estudiante['id'],
                    'visibilidad_alumno'=>$visibilidad,
                );
        
                // Insertar los datos en la tabla de asignación de alumnos
                $this->db->insert('solucionario_alumno', $data);
            }




            set_alert('success', translate('information_has_been_updated_successfully'));
           
            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);
            for ($i = 1; $i <= 1; $i++) {
                if (!empty($_FILES['archivo'.$i]['name'])) {
                    // Lógica para cada archivo
                    
            
                    $file_extension = pathinfo($_FILES['archivo'.$i]['name'], PATHINFO_EXTENSION);
                    
                    // Directorio base para subir los archivos de estudiantes
                    $base_path = 'uploads/solucionario/';
                
                    $student_path = $base_path ;
                
                    // Si la carpeta no existe, la crea
                    if (!file_exists($student_path)) {
                        mkdir($student_path, 0777, true);
                    }
                   
            
                    // Nuevo nombre de archivo

                    $new_filename = 'Solucionario-'.$ultimo_id.'.' . $file_extension;
                    $new_filename = str_replace(' ', '_', $new_filename);
            
                    // Establece el nuevo nombre del archivo
                    $_FILES['archivo'.$i]['name'] = $new_filename;
                
                    // Actualiza la ruta de carga para el archivo
                    $config['upload_path'] = $student_path;
                
                    $this->upload->initialize($config);
                
                    // Continúa con la subida del archivo
                    if ($this->upload->do_upload('archivo'.$i)) {
                        // Archivo subido exitosamente
                        // Puedes realizar operaciones adicionales si es necesario
                    } else {
                        // Error al subir el archivo
                        set_alert('fail', translate('Algo salió mal con Archivo '.$i));
                    }
                }
            }


          

              

            // Enviar la respuesta JSON
            $array = array('status' => 'success');
            redirect(base_url('solucionarios/index'));
            exit();
        }








        $this->data['title'] ="Solucionarios";
        $this->data['sub_page'] = 'solucionarios/index';
        $this->data['main_menu'] = 'solucionarios';


        $this->db->select('solucionarios.id as id ,solucionarios.titulo as titulo, solucionarios.tipo as tipo , solucionarios.fecha as fecha , class.name as grado, section.name as section'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('solucionarios');

       
        $this->db->join('class', 'class.id = solucionarios.grado_id');
        $this->db->join('section', 'section.id = solucionarios.section_id');
        $this->db->where('solucionarios.session_id',get_session_id());

        
        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();
        $this->data['solucionarios'] = $result2;


        $this->load->view('layout/index', $this->data);








    }


    public function vista_estudiante()
    {
       

        







        $this->data['title'] ="Solucionarios";
        $this->data['sub_page'] = 'solucionarios/vista_estudiante';
        $this->data['main_menu'] = 'academic';


        $this->db->select('*');
        $this->db->from('enroll');
        $this->db->where('enroll.student_id',get_loggedin_user_id() );
        $this->db->limit(1); // Limitar la consulta al primer registro

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $class_id = $row->class_id;
            $section_id = $row->section_id;
        
            // Ahora las variables $class_id y $section_id contienen los valores de las columnas
        } else {
            // No se encontraron registros
        }


        $this->db->select('solucionarios.id as id ,solucionarios.titulo as titulo, solucionarios.tipo as tipo , solucionarios.fecha as fecha , class.name as grado, section.name as section'); // Agrega una coma entre 'id' y 'subject.name'
        $this->db->from('solucionarios');

       
        $this->db->join('class', 'class.id = solucionarios.grado_id');
        $this->db->join('section', 'section.id = solucionarios.section_id');
        $this->db->join('solucionario_alumno', 'solucionario_alumno.id_solucionario = solucionarios.id');

        $this->db->where('solucionario_alumno.id_student',get_loggedin_user_id());
        $this->db->where('solucionario_alumno.visibilidad_alumno',1);

        $this->db->where('solucionarios.session_id',get_session_id());
        $this->db->where('solucionarios.fecha <=', date('Y-m-d H:i:s')); // Compara con la fecha y hora actual
        $this->db->where('solucionarios.grado_id',$class_id);
        $this->db->where('solucionarios.section_id',$section_id);

        
        $query2 = $this->db->get(); 
        $result2 = $query2->result_array();
        $this->data['solucionarios'] = $result2;


        $this->load->view('layout/index', $this->data);








    }


    public function verPDF($nombre_archivo) {


      


        $nombre_archivo = urldecode($nombre_archivo);

        $ruta_pdf = 'uploads/solucionario/'.$nombre_archivo;


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
            echo "El archivo no existe.".$ruta_pdf;
        }
    } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
        // Es una imagen
        $ruta_imagen = $ruta_pdf ;

        if (file_exists($ruta_imagen)) {
            // Mostrar la imagen en el navegador
            header('Content-Type: image/' . $extension);
            readfile($ruta_imagen);
        } else {
            echo "La imagen no existe.".$ruta_pdf ;
        }
    } else {
        echo "Tipo de archivo no compatible.";
    }



    }






    public function obtener_alumnos() {
      
        $grado_id = $this->input->post('grado_id');
        $section_id = $this->input->post('section_id');

        $this->db->select('student.id as id , UPPER(student.last_name) as last_name, UPPER(student.first_name) as first_name');
        $this->db->from('enroll');
        $this->db->join('student', 'student.id = enroll.student_id');
        
        $this->db->where('enroll.session_id',get_session_id());
        $this->db->where('enroll.class_id',$grado_id);
        $this->db->where('enroll.section_id',$section_id);

        $query = $this->db->get(); // Ejecuta la consulta

        // Retorna los resultados como un array
        $result = $query->result_array();
        echo json_encode($result);

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

    public function delete_solucionario($id = '')
    {
         
            $this->db->where('id', $id);
            $this->db->delete('solucionarios');
          
    }
   
}

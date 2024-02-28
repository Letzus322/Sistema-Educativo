<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Student.php
 * @copyright : Reserved RamomCoder Team
 */

class Student extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers('download');
        $this->load->helpers('custom_fields');
        $this->load->model('student_model');
        $this->load->model('email_model');
        $this->load->model('sms_model');
        $this->load->model('student_fields_model');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Archivo_model'); 


    }

    public function index()
    {
        redirect(base_url('student/view'));
    }

    /* student form validation rules */
    protected function student_validation()
    {
        $branchID = $this->application_model->get_branch_id();
        $getBranch = $this->getBranchDetails();
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
        }
        $this->form_validation->set_rules('year_id', translate('academic_year'), 'trim|required');
        $this->form_validation->set_rules('first_name', translate('first_name'), 'trim|required');
        $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
        $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
        $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required|callback_unique_registerid');
        // checking profile photo format
        $this->form_validation->set_rules('user_photo', translate('profile_picture'), 'callback_photoHandleUpload[user_photo]');

        // system fields validation rules
        $validArr = array();
        $validationArr = $this->student_fields_model->getStatusArr($branchID);
        foreach ($validationArr as $key => $value) {
            if ($value->status && $value->required) {
                $validArr[$value->prefix] = 1;
            }
        }
        if (isset($validArr['admission_date'])) {
            $this->form_validation->set_rules('admission_date', translate('admission_date'), 'trim|required');
        }
        if (isset($validArr['student_photo'])) {
            if (isset($_FILES["user_photo"]) && empty($_FILES["user_photo"]['name']) && empty($_POST['old_user_photo'])) {
                $this->form_validation->set_rules('user_photo', translate('profile_picture'), 'required');
            }
        }
        if (isset($validArr['roll'])) {
            $this->form_validation->set_rules('roll', translate('roll'), 'trim|numeric|required|callback_unique_roll');
        } else {
            $this->form_validation->set_rules('roll', translate('roll'), 'trim|numeric|callback_unique_roll');
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
        if (isset($validArr['category'])) {
            $this->form_validation->set_rules('category_id', translate('category'), 'trim|required');
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
        if (isset($validArr['present_address'])) {
            $this->form_validation->set_rules('current_address', translate('present_address'), 'trim|required');
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

        if ($getBranch['stu_generate'] == 0 || isset($_POST['student_id'])) {
            $this->form_validation->set_rules('username', translate('username'), 'trim|required|callback_unique_username');
            if (!isset($_POST['student_id'])) {
                $this->form_validation->set_rules('password', translate('password'), 'trim|required|min_length[4]');
                $this->form_validation->set_rules('retype_password', translate('retype_password'), 'trim|required|matches[password]');
            }
        }
        
        // custom fields validation rules
        $class_slug = $this->router->fetch_class();
        $customFields = getCustomFields($class_slug);
        foreach ($customFields as $fields_key => $fields_value) {
            if ($fields_value['required']) {
                $fieldsID   = $fields_value['id'];
                $fieldLabel = $fields_value['field_label'];
                $this->form_validation->set_rules("custom_fields[student][" . $fieldsID . "]", $fieldLabel, 'trim|required');
            }
        }
    }

    /* student admission information are prepared and stored in the database here */
    public function add()
    {
        // check access permission
        if (!get_permission('student', 'is_add')) {
            access_denied();
        }

        $getBranch = $this->getBranchDetails();
        $branchID = $this->application_model->get_branch_id();
        $this->data['getBranch'] = $getBranch;
        $this->data['branch_id'] = $branchID;
        $this->data['sub_page'] = 'student/add';
        $this->data['sub_page_2'] = 'externa';

        $this->data['main_menu'] = 'admission';
        $this->data['register_id'] = $this->student_model->regSerNumber();
        $this->data['title'] = translate('create_admission');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/student.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function save() {
        if ($_POST) {
            // check access permission
            if (!get_permission('student', 'is_add')) {
                ajax_access_denied();
            }

            $getBranch = $this->getBranchDetails();
            $branchID = $this->application_model->get_branch_id();
        
            $this->student_validation();
            if (!isset($_POST['guardian_chk'])) {

                // system fields validation rules
                $validArr = array();
                $validationArr = $this->student_fields_model->getStatusArr($branchID);
                foreach ($validationArr as $key => $value) {
                    if ($value->status && $value->required) {
                        $validArr[$value->prefix] = 1;
                    }
                }

                if (isset($validArr['guardian_name'])) {
                    $this->form_validation->set_rules('grd_name', translate('name'), 'trim|required');
                }
                if (isset($validArr['guardian_relation'])) {
                    $this->form_validation->set_rules('grd_relation', translate('relation'), 'trim|required');
                }
                if (isset($validArr['father_name'])) {
                    $this->form_validation->set_rules('father_name', translate('father_name'), 'trim|required');
                }
                if (isset($validArr['mother_name'])) {
                    $this->form_validation->set_rules('mother_name', translate('mother_name'), 'trim|required');
                }
                if (isset($validArr['guardian_occupation'])) {
                    $this->form_validation->set_rules('grd_occupation', translate('occupation'), 'trim|required');
                }
                if (isset($validArr['guardian_income'])) {
                    $this->form_validation->set_rules('grd_income', translate('occupation'), 'trim|required|numeric');
                }
                if (isset($validArr['guardian_education'])) {
                    $this->form_validation->set_rules('grd_education', translate('education'), 'trim|required');
                }
                if (isset($validArr['guardian_email'])) {
                    $this->form_validation->set_rules('grd_email', translate('email'), 'trim|required');
                }
                if (isset($validArr['guardian_mobile_no'])) {
                    $this->form_validation->set_rules('grd_mobileno', translate('mobile_no'), 'trim|required|numeric');
                }
                if (isset($validArr['guardian_address'])) {
                    $this->form_validation->set_rules('grd_address', translate('address'), 'trim|required');
                }
                if (isset($validArr['guardian_photo'])) {
                    if (isset($_FILES["guardian_photo"]) && empty($_FILES["guardian_photo"]['name'])) {
                        $this->form_validation->set_rules('guardian_photo', translate('guardian_picture'), 'required');
                    }
                }
                if (isset($validArr['guardian_city'])) {
                    $this->form_validation->set_rules('grd_city', translate('city'), 'trim|required');
                }
                if (isset($validArr['guardian_state'])) {
                    $this->form_validation->set_rules('grd_state', translate('state'), 'trim|required');
                }

                if ($getBranch['grd_generate'] == 0) {
                    $this->form_validation->set_rules('grd_username', translate('username'), 'trim|required|callback_get_valid_guardian_username');
                    $this->form_validation->set_rules('grd_password', translate('password'), 'trim|required');
                    $this->form_validation->set_rules('grd_retype_password', translate('retype_password'), 'trim|required|matches[grd_password]');
                }
            } else {
                $this->form_validation->set_rules('parent_id', translate('guardian'), 'required');
            }
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                //save all student information in the database file
                $studentData = $this->student_model->save($post, $getBranch);
                $studentID = $studentData['student_id'];
                //save student enroll information in the database file
                $arrayEnroll = array(
                    'student_id' => $studentID,
                    'class_id' => $post['class_id'],
                    'section_id' => $post['section_id'],
                    'roll' => (isset($post['roll']) ? $post['roll'] : 0),
                    'session_id' => $post['year_id'],
                    'branch_id' => $branchID,
                );
                $this->db->insert('enroll', $arrayEnroll);

                // handle custom fields data
                $class_slug = $this->router->fetch_class();
                $customField = $this->input->post("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFields($customField, $studentID);
                }

                // send student admission email
                $this->email_model->studentAdmission($studentData);
                // send account activate sms
                $this->sms_model->send_sms($arrayEnroll, 1);

                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('student/add');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    /* csv file to import student information  and stored in the database here */
    public function csv_import()
    {
        // check access permission
        if (!get_permission('multiple_import', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            $err_msg = "";
            $i = 0;
            $this->load->library('csvimport');
            // form validation rules
            if (is_superadmin_loggedin() == true) {
                $this->form_validation->set_rules('branch_id', 'Branch', 'trim|required');
            }
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
            $this->form_validation->set_rules('section_id', 'Section', 'trim|required');
            if (isset($_FILES["userfile"]) && empty($_FILES['userfile']['name'])) {
                $this->form_validation->set_rules('userfile', 'CSV File', 'required');
            }
            if ($this->form_validation->run() == true) {
                $classID = $this->input->post('class_id');
                $sectionID = $this->input->post('section_id');
                $csv_array = $this->csvimport->get_array($_FILES["userfile"]["tmp_name"]);
                if ($csv_array) {
                    $columnHeaders = array('FirstName','LastName','BloodGroup','Gender','Birthday','MotherTongue','Religion','Caste','Phone','City','State','PresentAddress','PermanentAddress','CategoryID','Roll','RegisterNo','AdmissionDate','StudentEmail','StudentUsername','StudentPassword','GuardianName','GuardianRelation','FatherName','MotherName','GuardianOccupation','GuardianMobileNo','GuardianAddress','GuardianEmail','GuardianUsername','GuardianPassword');
                    $csvData = array();
                    foreach ($csv_array as $row) {
                        if ($i == 0) {
                            $csvData = array_keys($row);
                        }
                        $csv_chk = array_diff($columnHeaders, $csvData);
                        if (count($csv_chk) <= 0) {
                            $schoolSettings = $this->student_model->get('branch', array('id' => $branchID), true, false, 'unique_roll');
                            $unique_roll = $schoolSettings['unique_roll'];

                            $r = $this->csvCheckExistsData($row['StudentUsername'], $row['Roll'], $row['RegisterNo'], $classID, $sectionID, $branchID, $unique_roll);
                            if ($r['status'] == false) {
                                $err_msg .= $row['FirstName'] . ' ' . $row['LastName'] . " - Imported Failed : " . $r['message'] . "<br>";
                            } else {
                                $this->student_model->csvImport($row, $classID, $sectionID, $branchID);
                                $i++;
                            }
                        } else {
                            set_alert('error', translate('invalid_csv_file'));
                            redirect(base_url("student/csv_import"));
                        }
                    }
                    if ($err_msg != null) {
                        $this->session->set_flashdata('csvimport', $err_msg);
                    }
                    if ($i > 0) {
                        set_alert('success', $i . ' Students Have Been Successfully Added');
                    }
                    redirect(base_url("student/csv_import"));
                } else {
                    set_alert('error', translate('invalid_csv_file'));
                    redirect(base_url("student/csv_import"));
                }
            }
        }
        $this->data['title'] = translate('multiple_import');
        $this->data['branch_id'] = $branchID;
        $this->data['sub_page'] = 'student/multi_add';
        $this->data['sub_page_2'] = 'externa';

        $this->data['main_menu'] = 'admission';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    /* showing disable authentication student list */
    public function disable_authentication()
    {
        // check access permission
        if (!get_permission('student_disable_authentication', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $this->data['students'] = $this->application_model->getStudentListByClassSection($classID, $sectionID, $branchID, true);
        }

        if (isset($_POST['auth'])) {
            if (!get_permission('student_disable_authentication', 'is_add')) {
                access_denied();
            }
            $stafflist = $this->input->post('views_bulk_operations');
            if (isset($stafflist)) {
                foreach ($stafflist as $id) {
                    $this->db->where(array('role' => 7, 'user_id' => $id));
                    $this->db->update('login_credential', array('active' => 1));
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
            } else {
                set_alert('error', 'Please select at least one item');
            }
            redirect(base_url('student/disable_authentication'));
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('deactivate_account');
        $this->data['sub_page'] = 'student/disable_authentication';
        $this->data['main_menu'] = 'student';
        $this->load->view('layout/index', $this->data);
    }

    // add new student category
    public function category()
    {
        if (isset($_POST['category'])) {
            if (!get_permission('student_category', 'is_add')) {
                access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('category_name', translate('category_name'), 'trim|required|callback_unique_category');
            if ($this->form_validation->run() !== false) {
                $arrayData = array(
                    'name' => $this->input->post('category_name'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $this->db->insert('student_category', $arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('student/category'));
            }
        }
        $this->data['title'] = translate('student') . " " . translate('details');
        $this->data['sub_page'] = 'student/category';
        $this->data['sub_page_2'] = 'externa';

        $this->data['main_menu'] = 'admission';
        $this->load->view('layout/index', $this->data);
    }

    // update existing student category
    public function category_edit()
    {
        if (!get_permission('student_category', 'is_edit')) {
            ajax_access_denied();
        }
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('category_name', translate('category_name'), 'trim|required|callback_unique_category');
        if ($this->form_validation->run() !== false) {
            $category_id = $this->input->post('category_id');
            $arrayData = array(
                'name' => $this->input->post('category_name'),
                'branch_id' => $this->application_model->get_branch_id(),
            );
            $this->db->where('id', $category_id);
            $this->db->update('student_category', $arrayData);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array  = array('status' => 'success');
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail','error' => $error);
        }
        echo json_encode($array);
    }

    // delete student category from database
    public function category_delete($id)
    {
        if (get_permission('student_category', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('student_category');
        }
    }

    // student category details send by ajax
    public function categoryDetails()
    {
        if (get_permission('student_category', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $query = $this->db->get('student_category');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    /* validate here, if the check student category name */
    public function unique_category($name)
    {
        $branchID = $this->application_model->get_branch_id();
        $category_id = $this->input->post('category_id');
        if (!empty($category_id)) {
            $this->db->where_not_in('id', $category_id);
        }
        $this->db->where(array('name' => $name, 'branch_id' => $branchID));
        $uniform_row = $this->db->get('student_category')->num_rows();
        if ($uniform_row == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_category", translate('already_taken'));
            return false;
        }
    }

    /* showing student list by class and section */
    public function view()
    {
        // check access permission
        if (!get_permission('student', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $this->data['students'] = $this->application_model->getStudentListByClassSection($classID, $sectionID, $branchID, false, true);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('student_list');
        $this->data['main_menu'] = 'student';
        $this->data['sub_page'] = 'student/view';
        $this->data['headerelements'] = array(
            'js' => array(
                'js/student.js'
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function reserva(){
        // check access permission
      

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $this->data['students'] = $this->application_model->getStudentReservationListByClassSectionAntiguos($classID, $sectionID, $branchID, false, true);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('Reserva de Matricula');
        $this->data['main_menu'] = 'admission';
        $this->data['sub_page'] = 'vacancy_reservation/vistaAdmin';
        $this->data['sub_page_2'] = 'interna';

        $this->data['headerelements'] = array(
            'js' => array(
                'js/student.js'
            ),
        );
        $this->load->view('layout/index', $this->data);

        
        // check access permission
      
      
    }

    /* profile preview and information are updating here */
    public function profile($id = '')
    {
        // check access permission
        if (!get_permission('student', 'is_edit')) {
            access_denied();
        }
        $this->load->model('fees_model');
        $this->load->model('exam_model');
        $getStudent = $this->student_model->getSingleStudent($id);
        if (isset($_POST['update'])) {
            $this->session->set_flashdata('profile_tab', 1);
            $this->data['branch_id'] = $this->application_model->get_branch_id();
            $this->student_validation();
            $this->form_validation->set_rules('parent_id', translate('guardian'), 'required');
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                //save all student information in the database file
                $studentID = $this->student_model->save($post);
                //save student enroll information in the database file
                $arrayEnroll = array(
                    'class_id' => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section_id'),
                    'roll' => $this->input->post('roll'),
                    'session_id' => $this->input->post('year_id'),
                    'branch_id' => $this->data['branch_id'],
                );
                $this->db->where('id', $getStudent['enrollid']);
                $this->db->update('enroll', $arrayEnroll);

                // handle custom fields data
                $class_slug = $this->router->fetch_class();
                $customField = $this->input->post("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFields($customField, $id);
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('student/profile/' . $id));
            }
        }
      



       

        if (isset($_POST['subirArchivo'])) {
                   
            $userID = $id;
            $estudiante = $getStudent;
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





            
            // Enviar la respuesta JSON
            $array = array('status' => 'success');
            redirect(base_url('student/profile/' . $id));
            exit();
        }



        $this->db->select('d.id, d.nombre_document, d.descripcion,s.ruta_archivo');
        $this->db->from('document d');
        $this->db->join('subida s', 'd.id = s.document_id ');
        $this->db->where('s.Student_id',$id );

        $query = $this->db->get();

       

        //MANDA A LA VISTA LA LISTA DE LOS DOCUMENTOS QUE YA SE HAN SUBIDO
        $this->data['documentosSubidos'] = $query->result_array();







        

        $this->db->select('d.id, d.nombre_document, d.descripcion');
        $this->db->from('document d');
        $this->db->join('subida s', 'd.id = s.document_id AND s.student_id = ' . $id, 'left');
        $this->db->where('s.id IS NULL');
        $query = $this->db->get();
        //MANDA A LA VISTA LA LISTA DE LOS DOCUMENTOS QUE FALTAN SUBIR
        $this->data['documentos'] = $query->result_array();


        $this->data['student'] = $getStudent;
        $this->data['title'] = translate('student_profile');
        $this->data['sub_page'] = 'student/profile';
        $this->data['main_menu'] = 'student';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/student.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function verPDF($nombre_archivo) {

        $userID = $this->input->get('parametro');

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




    /* student information delete here */
    public function delete_data($eid = '', $sid = '')
    {
        if (get_permission('student', 'is_delete')) {
            $branchID = get_type_name_by_id('enroll', $eid, 'branch_id');
            // Check student restrictions
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('student_id', $sid)->delete('enroll');
            if ($this->db->affected_rows() > 0) {
                $this->db->where('id', $sid)->delete('student');
                $this->db->where(array('user_id' => $sid, 'role' => 7))->delete('login_credential');

                $r = $this->db->select('id')->where('student_id', $sid)->get('fee_allocation')->result_array();
                $this->db->where_in('student_id', $sid)->delete('fee_allocation');
                $r = array_column($r, 'id');
                if (!empty($r)) {
                    $this->db->where_in('allocation_id', $r)->delete('fee_payment_history');
                }

                $get_field = $this->db->where(array('form_to' => 'student', 'branch_id' => $branchID))->get('custom_field')->result_array();
                $field_id = array_column($get_field, 'id');
                $this->db->where('relid', $sid);
                $this->db->where_in('field_id', $field_id);
                $this->db->delete('custom_fields_values');
            }
        }
    }

    // student document details are create here / ajax
    public function document_create()
    {
        if (!get_permission('student', 'is_edit')) {
            ajax_access_denied();
        }
        $this->form_validation->set_rules('document_title', translate('document_title'), 'trim|required');
        $this->form_validation->set_rules('document_category', translate('document_category'), 'trim|required');
        if (isset($_FILES['document_file']['name']) && empty($_FILES['document_file']['name'])) {
            $this->form_validation->set_rules('document_file', translate('document_file'), 'required');
        }
        if ($this->form_validation->run() !== false) {
            $insert_doc = array(
                'student_id' => $this->input->post('patient_id'),
                'title' => $this->input->post('document_title'),
                'type' => $this->input->post('document_category'),
                'remarks' => $this->input->post('remarks'),
            );

            // uploading file using codeigniter upload library
            $config['upload_path'] = './uploads/attachments/documents/';
            $config['allowed_types'] = 'gif|jpg|png|pdf|docx|csv|txt';
            $config['max_size'] = '2048';
            $config['encrypt_name'] = true;
            $this->upload->initialize($config);
            if ($this->upload->do_upload("document_file")) {
                $insert_doc['file_name'] = $this->upload->data('orig_name');
                $insert_doc['enc_name'] = $this->upload->data('file_name');
                $this->db->insert('student_documents', $insert_doc);
                set_alert('success', translate('information_has_been_saved_successfully'));
            } else {
                set_alert('error', strip_tags($this->upload->display_errors()));
            }
            $this->session->set_flashdata('documents_details', 1);
            echo json_encode(array('status' => 'success'));
        } else {
            $error = $this->form_validation->error_array();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }
        
    }

    // student document details are update here / ajax
    public function document_update()
    {
        if (!get_permission('student', 'is_edit')) {
            ajax_access_denied();
        }
        // validate inputs
        $this->form_validation->set_rules('document_title', translate('document_title'), 'trim|required');
        $this->form_validation->set_rules('document_category', translate('document_category'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $document_id = $this->input->post('document_id');
            $insert_doc = array(
                'title' => $this->input->post('document_title'),
                'type' => $this->input->post('document_category'),
                'remarks' => $this->input->post('remarks'),
            );
            if (isset($_FILES["document_file"]) && !empty($_FILES['document_file']['name'])) {
                $config['upload_path'] = './uploads/attachments/documents/';
                $config['allowed_types'] = 'gif|jpg|png|pdf|docx|csv|txt';
                $config['max_size'] = '2048';
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if ($this->upload->do_upload("document_file")) {
                    $exist_file_name = $this->input->post('exist_file_name');
                    $exist_file_path = FCPATH . 'uploads/attachments/documents/' . $exist_file_name;
                    if (file_exists($exist_file_path)) {
                        unlink($exist_file_path);
                    }
                    $insert_doc['file_name'] = $this->upload->data('orig_name');
                    $insert_doc['enc_name'] = $this->upload->data('file_name');
                    set_alert('success', translate('information_has_been_updated_successfully'));
                } else {
                    set_alert('error', strip_tags($this->upload->display_errors()));
                }
            }
            $this->db->where('id', $document_id);
            $this->db->update('student_documents', $insert_doc);
            echo json_encode(array('status' => 'success'));
            $this->session->set_flashdata('documents_details', 1);
        } else {
            $error = $this->form_validation->error_array();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }
        
    }

    // student document details are delete here
    public function document_delete($id)
    {
        if (get_permission('student', 'is_edit')) {
            $enc_name = $this->db->select('enc_name')->where('id', $id)->get('student_documents')->row()->enc_name;
            $file_name = FCPATH . 'uploads/attachments/documents/' . $enc_name;
            if (file_exists($file_name)) {
                unlink($file_name);
            }
            $this->db->where('id', $id);
            $this->db->delete('student_documents');
            $this->session->set_flashdata('documents_details', 1);
        }
    }

    public function document_details()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $query = $this->db->get('student_documents');
        $result = $query->row_array();
        echo json_encode($result);
    }

    // file downloader
    public function documents_download()
    {
        $encrypt_name = $this->input->get('file');
        $file_name = $this->db->select('file_name')->where('enc_name', $encrypt_name)->get('student_documents')->row()->file_name;
        $this->load->helper('download');
        force_download($file_name, file_get_contents('./uploads/attachments/documents/' . $encrypt_name));
    }

    /* sample csv downloader */
    public function csv_Sampledownloader()
    {
        $this->load->helper('download');
        $data = file_get_contents('uploads/multi_student_sample.csv');
        force_download("multi_student_sample.csv", $data);
    }

    /* validate here, if the check multi admission  email and roll */
    public function csvCheckExistsData($student_username = '', $roll = '', $registerno = '', $class_id = '', $section_id = '', $branchID = '', $unique_roll)
    {
        $array = array();
        if (!empty($roll)) {

            if ($unique_roll != 0) {
                if ($unique_roll == 2) {
                    $this->db->where('section_id', $section_id);
                }
                $this->db->where(array('roll' => $roll, 'class_id' => $class_id, 'branch_id' => $branchID));
                $rollQuery = $this->db->get('enroll');
                if ($rollQuery->num_rows() > 0) {
                    $array['status'] = false;
                    $array['message'] = "Roll Already Exists.";
                    return $array;
                }
            }
        }
        if ($student_username !== '') {
            $this->db->where('username', $student_username);
            $query = $this->db->get_where('login_credential');
            if ($query->num_rows() > 0) {
                $array['status'] = false;
                $array['message'] = "Student Username Already Exists.";
                return $array;
            }
        }
        if ($registerno !== '') {
            $this->db->where('register_no', $registerno);
            $query = $this->db->get_where('student');
            if ($query->num_rows() > 0) {
                $array['status'] = false;
                $array['message'] = "Student Register No Already Exists.";
                return $array;
            }
        } else {
            $array['status'] = false;
            $array['message'] = "Register No Is Required.";
            return $array; 
        }

        $array['status'] = true;
        return $array;
    }

    // unique valid username verification is done here
    public function unique_username($username)
    {
        if ($this->input->post('student_id')) {
            $student_id = $this->input->post('student_id');
            $login_id = $this->app_lib->get_credential_id($student_id, 'student');
            $this->db->where_not_in('id', $login_id);
        }
        $this->db->where('username', $username);
        $query = $this->db->get('login_credential');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_username", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }

    /* unique valid guardian email address verification is done here */
    public function get_valid_guardian_username($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('login_credential');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("get_valid_guardian_username", translate('username_has_already_been_used'));
            return false;
        } else {
            return true;
        }
    }

    /* unique valid student roll verification is done here */
    public function unique_roll($roll)
    {
        if (empty($roll)) {
            return true;
        }
        $branchID = $this->application_model->get_branch_id();
        $schoolSettings = $this->student_model->get('branch', array('id' => $branchID), true, false, 'unique_roll');
        $unique_roll = $schoolSettings['unique_roll'];
        if (empty($unique_roll) && $unique_roll == 0) {
            return true;
        }

        $classID = $this->input->post('class_id');
        $sectionID = $this->input->post('section_id');
        if ($this->uri->segment(3)) {
            $this->db->where_not_in('student_id', $this->uri->segment(3));
        }
        if ($unique_roll == 2) {
            $this->db->where('section_id', $sectionID);
        }
        $this->db->where(array('roll' => $roll, 'class_id' => $classID, 'branch_id' => $branchID));
        $q = $this->db->get('enroll')->num_rows();
        if ($q == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_roll", translate('already_taken'));
            return false;
        }
    }

    /* unique valid register ID verification is done here */
    public function unique_registerid($register)
    {
        $branchID = $this->application_model->get_branch_id();
        if ($this->uri->segment(3)) {
            $this->db->where_not_in('id', $this->uri->segment(3));
        }
        $this->db->where('register_no', $register);
        $query = $this->db->get('student')->num_rows();
        if ($query == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_registerid", translate('already_taken'));
            return false;
        }
    }

    public function search()
    {
        // check access permission
        if (!get_permission('student', 'is_view')) {
            access_denied();
        }

        $search_text = $this->input->post('search_text');
        $this->data['query'] = $this->student_model->getSearchStudentList(trim($search_text));
        $this->data['title'] = translate('searching_results');
        $this->data['sub_page'] = 'student/search';
        $this->data['main_menu'] = '';
        $this->load->view('layout/index', $this->data);
    }

    /* student password change here */
    public function change_password()
    {
        if (get_permission('student', 'is_edit')) {
            if (!isset($_POST['authentication'])) {
                $this->form_validation->set_rules('password', translate('password'), 'trim|required|min_length[4]');
            } else {
                $this->form_validation->set_rules('password', translate('password'), 'trim');
            }
            if ($this->form_validation->run() !== false) {
                $studentID = $this->input->post('student_id');
                $password = $this->input->post('password');
                if (!isset($_POST['authentication'])) {
                    $this->db->where('role', 7);
                    $this->db->where('user_id', $studentID);
                    $this->db->update('login_credential', array('password' => $this->app_lib->pass_hashed($password)));
                }else{
                    $this->db->where('role', 7);
                    $this->db->where('user_id', $studentID);
                    $this->db->update('login_credential', array('active' => 0));
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array  = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        } 
    }

    // student quick details
    public function quickDetails()
    {
        $id = $this->input->post('student_id');
        $this->db->select('student.*,enroll.student_id,enroll.roll,student_category.name as cname');
        $this->db->from('enroll');
        $this->db->join('student', 'student.id = enroll.student_id', 'inner');
        $this->db->join('student_category', 'student_category.id = student.category_id', 'left');
        $this->db->where('enroll.id', $id);
        $row = $this->db->get()->row();
        $data['photo'] = get_image_url('student', $row->photo);
        $data['full_name'] = $row->first_name . " " . $row->last_name;
        $data['student_category'] = $row->cname;
        $data['register_no'] = $row->register_no;
        $data['roll'] = $row->roll;
        $data['admission_date'] = empty($row->admission_date) ? "N/A" : _d($row->admission_date);
        $data['birthday'] = empty($row->birthday) ? "N/A" : _d($row->birthday);
        $data['blood_group'] = empty($row->blood_group) ? "N/A" : $row->blood_group;
        $data['religion'] = empty($row->religion) ? "N/A" : $row->religion;
        $data['email'] = $row->email;
        $data['mobileno'] = empty($row->mobileno) ? "N/A" : $row->mobileno;
        $data['state'] = empty($row->state) ? "N/A" : $row->state;
        $data['address'] = empty($row->current_address) ? "N/A" : $row->current_address;
        echo json_encode($data);
    }

    public function bulk_delete()
    {
        $status = 'success';
        $message = translate('information_deleted');
        if (get_permission('student', 'is_delete')) {
            $arrayID = $this->input->post('array_id');
            foreach ($arrayID as $key => $row) {
                $branchID = get_type_name_by_id('enroll', $row, 'branch_id');
                $get_field = $this->db->where(array('form_to' => 'student', 'branch_id' => $branchID))->get('custom_field')->result_array();
                $field_id = array_column($get_field, 'id');
                $this->db->where('relid', $row);
                $this->db->where_in('field_id', $field_id);
                $this->db->delete('custom_fields_values');
            }

            $this->db->where_in('student_id', $arrayID)->delete('enroll');
            $this->db->where_in('id', $arrayID)->delete('student');
            $this->db->where_in('user_id', $arrayID)->where('role', 7)->delete('login_credential');

            $r = $this->db->select('id')->where_in('student_id', $arrayID)->get('fee_allocation')->result_array();
            $this->db->where_in('student_id', $arrayID)->delete('fee_allocation');
            $r = array_column($r, 'id');
            if (!empty($r)) {
                $this->db->where_in('allocation_id', $r)->delete('fee_payment_history');
            }
        } else {
            $message = translate('access_denied');
            $status = 'error';
        }
        echo json_encode(array('status' => $status, 'message' => $message));
    }

    public function CitaPsicologicaSecretaria()
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



        $this->db->select(' *,student.first_name AS nombreEstudiante, student.last_name AS apellidoEstudiante, staff.name AS psicologoName');
        $this->db->from('reservasPsicologicas');
        $this->db->join('student', 'student.id = reservasPsicologicas.id_student');
        $this->db->join('work_schedules', 'work_schedules.schedule_id = reservasPsicologicas.schedule_id');
        $this->db->join('staff', 'staff.id = work_schedules.staff_id');
        $this->db->where('staff.branch_id' ,$branch_id);
        $query = $this->db->get();
        $horarios = $query->result_array(); // Obtiene los resultados como un array
        $this->data['horarios'] = $horarios ;


        $this->db->select('*');
        $this->db->from('student s');
        $this->db->join('enroll e', 's.id = e.student_id');
        $this->db->join('reservasPsicologicas rp', 's.id = rp.id_student', 'left');
        $this->db->where('e.session_id', get_session_id());
        $this->db->where('e.branch_id', $branch_id);
        $this->db->where('(rp.estado IS NULL OR rp.estado = "Rechazado")');
        $this->db->where('NOT EXISTS (
            SELECT 1
            FROM reservasPsicologicas
            WHERE id_student = s.id
            AND estado IN ("Aceptado", "Pendiente")
        )', NULL, FALSE);
        $this->db->where('s.created_at >', '2023-12-01');

       
        $query = $this->db->get();
        $result = $query->result_array();
       
     

        $this->data['alumnosBranch']= $result;

        $userID = get_loggedin_user_id();

        // Realiza la consulta para verificar si hay registros para el usuario actual
        $this->db->select('*');
        $this->db->from('reservasPsicologicas');
        $this->db->where('id_student', $userID);
        
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data['citaSeparada'] = (count($result) > 0);


        if ($_POST) {
            $student_id = $this->input->post('student_id');
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
                    'id_student' => $student_id,
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
        $this->data['sub_page'] = 'citas/citaPsicologicaSecretaria';
        $this->data['main_menu'] = 'admission';

        
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
    public function CitaAcademicaSecretaria()
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



        $auxiliar_id =get_loggedin_user_id();
        
        $this->db->select(' *,student.first_name AS nombreEstudiante, student.last_name AS apellidoEstudiante');
        $this->db->from('reservasAcademicas');
        $this->db->join('student', 'student.id = reservasAcademicas.id_student');
        $this->db->join('work_schedules', 'work_schedules.schedule_id = reservasAcademicas.schedule_id');
        $this->db->join('staff', 'staff.id = work_schedules.staff_id');
        $this->db->where('staff.branch_id' ,$branch_id);

        $query = $this->db->get();
        $horarios = $query->result_array(); // Obtiene los resultados como un array
        $this->data['horarios'] = $horarios ;





        $this->db->select('*');
        $this->db->from('student s');
        $this->db->join('enroll e', 's.id = e.student_id');
        $this->db->join('reservasAcademicas rp', 's.id = rp.id_student', 'left');
        $this->db->where('e.session_id', get_session_id());
        $this->db->where('e.branch_id', $branch_id);
        $this->db->where('s.created_at >', '2023-12-01');

        $this->db->where('rp.id_student IS NULL'); // Esto filtra los estudiantes sin reserva psicológica
        $query = $this->db->get();
        $result = $query->result_array();
        $this->data['alumnosBranch']= $result;

        if ($_POST) {
            $student_id = $this->input->post('student_id');

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
            
            $data = array(
                'id_student' =>$student_id, // Reemplaza con el ID del estudiante correspondiente
                'schedule_id' => $horariotrabajador, // Reemplaza con el ID del horario del psicólogo
            );
            $this->db->insert('reservasAcademicas', $data);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array = array('status' => 'success');
            echo json_encode($array);
            exit();
           }

        $this->data['title'] = translate('Cita Academica');
        $this->data['sub_page'] = 'citas/citaAcademicaSecretaria';
        $this->data['main_menu'] = 'admission';
        $this->load->view('layout/index', $this->data);
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
    public function enviarDocumentoNiveles() {

        $this->db->select('*');
        $this->db->from('documentos_por_niveles');
        $this->db->where('documentos_por_niveles.branch', get_loggedin_branch_id());        
        $this->db->where('documentos_por_niveles.session', get_session_id());        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->data['niveles'] = $query->result_array();
        } else {
            $this->data['niveles'] = array(); // Array vacío si no hay resultados
        }


        if (isset($_POST['subirArchivo'])) {
                   
           
            set_alert('success', translate('information_has_been_updated_successfully'));
           
            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);
            for ($i = 1; $i <= 1; $i++) {
                if (!empty($_FILES['archivo'.$i]['name'])) {
                    // Lógica para cada archivo
                    $nivel = $this->input->post('id');
                    
            
                    $file_extension = pathinfo($_FILES['archivo'.$i]['name'], PATHINFO_EXTENSION);
                    
                    // Directorio base para subir los archivos de estudiantes
                    $base_path = 'uploads/documentos/';
                
                    $student_path = $base_path ;
                
                    // Si la carpeta no existe, la crea
                    if (!file_exists($student_path)) {
                        mkdir($student_path, 0777, true);
                    }
                   
            
                    // Nuevo nombre de archivo

                    $new_filename = 'Documento_'.$i.'_' .$nivel.'_'.get_loggedin_branch_id().'_'.get_session_id() .'.' . $file_extension;
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


          

                $nivel = $this->input->post('id');

                $data = array(
                        'nivel' => $nivel,
                        'branch' => get_loggedin_branch_id(),
                        'session' => get_session_id());
                
    
    
                $this->db->insert('documentos_por_niveles', $data);

            // Enviar la respuesta JSON
            $array = array('status' => 'success');
            redirect(base_url('student/enviarDocumentoNiveles'));
            exit();
        }


        if (isset($_POST['eliminaArchivo'])) {
                   
           
            set_alert('success', ('El archivo ha sido eliminado'));
           
          
            $nivel = $_POST['eliminaArchivo'];

            $base_path = 'uploads/documentos/';
            $new_filename = 'Documento_1_' .$nivel.'_'.get_loggedin_branch_id().'_'.get_session_id() .'.' . 'pdf';
            $new_filename = str_replace(' ', '_', $new_filename);
            $file_path = $base_path .$new_filename ;
            

            if (file_exists($file_path)) {
                // Intenta eliminar el archivo
                if (unlink($file_path)) {
                    // Archivo eliminado exitosamente
                    $this->db->where('nivel', $nivel);
                    $this->db->where('branch', get_loggedin_branch_id());
                    $this->db->where('session', get_session_id());
                    $this->db->delete('documentos_por_niveles');
        
        
                    redirect(base_url('student/enviarDocumentoNiveles'));               
                 } else {
                    redirect(base_url('student/enviarDocumentoNiveles'));               

                }
            } else {
                redirect(base_url('student/enviarDocumentoNiveles'));               

            }


           
            exit();
        }

        $this->data['title'] = translate('Enviar Contrato por Niveles');
        $this->data['sub_page'] = 'secretaria/enviarDocumentoNiveles';
        $this->data['sub_page_2'] = 'externa';

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

       
       $this->data['main_menu'] = 'admission';
       $this->load->view('layout/index', $this->data);
    }
    public function EnviarContrato() {
        $branchID = $this->application_model->get_branch_id();

        $this->db->select('s.*');
        $this->db->from('student s');
        $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
        $this->db->join('enroll ', 'enroll.student_id = s.id');
        $this->db->where('enroll.session_id', get_session_id());
        $this->db->where('enroll.branch_id',  $branchID );

        $this->db->where('e.subidaArchivos', 1);
        $this->db->where('e.citaAcademica', 1);
        $this->db->where('e.citaPsicologica', 1);
        $this->db->where('e.contratoEnviado', 0);
        $this->db->where('e.contratoFirmado', 0);
        $this->db->where('e.contratoValidado', 0);
        $this->db->where('e.subidaArchivos', 1);


        $query = $this->db->get();
        
        $result = $query->result_array();
        
        $this->data['students'] = $result;

       



        if ($_POST) {
                   
           
            set_alert('success', translate('information_has_been_updated_successfully'));
           
          
           

            // Directorio de destino para subir archivos
           
           // $upload_dir = APPPATH . 'upload/';

            // Configuración de la subida de archivos
            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);

            for ($i = 1; $i <= 4; $i++) {
                if (!empty($_FILES['archivo'.$i]['name'])) {
                    // Lógica para cada archivo
                    $student_id = $this->input->post('id');
                    $this->db->select('*');
                    $this->db->from('student');
                    $this->db->where('id', $student_id);
                    $query = $this->db->get();
                    $estudiante = $query->row_array();
            
                    $file_extension = pathinfo($_FILES['archivo'.$i]['name'], PATHINFO_EXTENSION);
                    
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
                    $new_filename = 'Documento_'.$i.'_' . $estudiante['first_name'] .'_'. $estudiante['last_name'] .'.' . $file_extension;
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


                $student_id = $this->input->post('id');

          
           
                $this->db->where('idEstudiante', $student_id);
                $query = $this->db->get('estadoMatricula');
                
                if ($query->num_rows() > 0) {
                    // Si existe, realizar un update
                    $this->db->set('contratoEnviado', 1);
                    $this->db->where('idEstudiante', $student_id);
                    $this->db->update('estadoMatricula');
                } else {
                    // Si no existe, realizar un insert
                    $data = array(
                        'idEstudiante' => $student_id,
                        'contratoEnviado' => 1
                        // Puedes establecer otros campos en su valor predeterminado aquí si es necesario
                    );
                
    
    
                    $this->db->insert('estadoMatricula', $data);
                }
           




            // Enviar la respuesta JSON
            $array = array('status' => 'success');

            echo json_encode($array);
            exit();
        }

        $this->data['title'] = translate('Enviar Contrato');
        $this->data['sub_page'] = 'secretaria/enviarContrato';
        
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

       
       $this->data['main_menu'] = 'admission';
       $this->load->view('layout/index', $this->data);
    }

    
    public function subirContratoAntiguos() 
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
        $this->db->join('enroll e', 's.id = e.student_id');

        $this->db->where('s.id', $userID);
        $this->db->where('e.session_id', get_session_id());

        $query = $this->db->get();
        $this->data['documentos'] = $query->result_array();


        $this->db->select('*');
        $this->db->from('reservation r');
        $this->db->where('r.student_id', $userID);
        $this->db->where('r.session_id', get_session_id());
        $query = $this->db->get();

        $reservations = $query->result_array();

        // Verifica si existen registros de reservación
        if (!empty($reservations)) {
            $this->data['reservation_exists'] = 1; // Si hay al menos un registro, establece a 1
        } else {
            $this->data['reservation_exists'] = 0; // Si no hay registros, establece a 0
        }


        $this->data['title'] = translate('Expediente de postulante');
        $this->data['sub_page'] = 'citas/subirContratoAntiguos';
        $this->data['main_menu'] = 'matriculas';
        $this->load->view('layout/index', $this->data);
    }

    public function EnviarContratoAntiguos() {
        $branchID = $this->application_model->get_branch_id();

        $this->db->select('s.*');
        $this->db->from('student s');
        $this->db->join('reservation e', 's.id = e.student_id');
        $this->db->join('enroll ', 'enroll.student_id = s.id');
        $this->db->where('enroll.session_id', get_session_id());
        $this->db->where('enroll.branch_id',  $branchID );

        $this->db->where('e.contrato_enviado', 0);

        $query = $this->db->get();
        
        $result = $query->result_array();
        
        $this->data['students'] = $result;

       



        if ($_POST) {
                   
           
            set_alert('success', translate('information_has_been_updated_successfully'));
           
          
           

            // Directorio de destino para subir archivos
           
           // $upload_dir = APPPATH . 'upload/';

            // Configuración de la subida de archivos
            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);

            for ($i = 1; $i <= 4; $i++) {
                if (!empty($_FILES['archivo'.$i]['name'])) {
                    // Lógica para cada archivo
                    $student_id = $this->input->post('id');
                    $this->db->select('*');
                    $this->db->from('student');
                    $this->db->where('id', $student_id);
                    $query = $this->db->get();
                    $estudiante = $query->row_array();
            
                    $this->db->select('*');
                    $this->db->from('reservation r');
                    $this->db->where('student_id', $student_id);
                    $query = $this->db->get();
                    $session = $query->row_array();

                    $file_extension = pathinfo($_FILES['archivo'.$i]['name'], PATHINFO_EXTENSION);
                    
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
                    $new_filename = 'Documento_'.$i.'_' . $estudiante['first_name'] .'_'. $estudiante['last_name'] .'_'.$session['session_id'].'.' . $file_extension;
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


                $student_id = $this->input->post('id');

          
           
                $this->db->where('student_id', $student_id);
                $query = $this->db->get('reservation');
                
                if ($query->num_rows() > 0) {
                    // Si existe, realizar un update
                    $this->db->set('contrato_enviado', 1);
                    $this->db->where('student_id', $student_id);
                    $this->db->update('reservation');
                } else {
                    // Si no existe, realizar un insert
                    $data = array(
                        'idEstudiante' => $student_id,
                        'contrato_enviado' => 1
                        // Puedes establecer otros campos en su valor predeterminado aquí si es necesario
                    );
                
    
    
                    $this->db->insert('reservation', $data);
                }
           




            // Enviar la respuesta JSON
            $array = array('status' => 'success');

            echo json_encode($array);
            exit();
        }

        $this->data['title'] = translate('Enviar Contrato');
        $this->data['sub_page'] = 'secretaria/enviarContratoAntiguos';
        
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

       
       $this->data['main_menu'] = 'admission';
       $this->load->view('layout/index', $this->data);
    }

    public function revisarContrato() {
        $branchID = $this->application_model->get_branch_id();
       
        $this->data['branch_id'] = $branchID;

        if (isset($_POST['search'])  ) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');


            $this->db->select('s.*,class.name AS className,section.name AS sectionName ,e.id AS idEstadoMatricula, e.subidaArchivos, e.citaAcademica, e.citaPsicologica, e.contratoEnviado, e.contratoFirmado,en.contratoValidado, en.estadoDeclaracion1, en.estadoDeclaracion2,en.estadoDeclaracion3');
            $this->db->from('student s');
            $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
            $this->db->join('enroll en', 's.id = en.student_id');
            $this->db->join('class ', 'class.id = en.class_id');
            $this->db->join('section ', 'section.id = en.section_id');
    
            $this->db->where('en.session_id', get_session_id());
            $this->db->where('en.branch_id', $branchID);
            if($sectionID != 'all'){
            $this->db->where( 'section.id',$sectionID);}
            if($classID != 'all'){

            $this->db->where( 'class.id',$classID);}

    
    
            $this->db->where('en.contratoFirmado', 1);
            $query = $this->db->get();
            $result = $query->result_array();

            $this->data['students'] = $result;
        }
     


        if (isset($_POST['accion'])) {
                   
           
           
            $accion = $this->input->post('accion');
            $idStudent = $this->input->post('student_id');
            $declaracion1 = $this->input->post('opcion_declaracion_0');
            $declaracion2 = $this->input->post('opcion_declaracion_1');
            $declaracion3 = $this->input->post('opcion_declaracion_2');

            
            if($accion){
            $data = array(
                'contratoValidado' => $accion // Nuevo valor para contratoValidado
            );
            $data2 = array(
                'contratoValidado' => $accion,
                'estadoDeclaracion1' => $declaracion1,
                'estadoDeclaracion2' => $declaracion2,
                'estadoDeclaracion3' => $declaracion3

            );
            $this->db->where('idEstudiante', $idStudent); // Asegúrate de tener el ID del estudiante correcto
            $this->db->update('estadoMatricula', $data);


            $this->db->where('student_id', $idStudent); // Asegúrate de tener el ID del estudiante correcto
            $this->db->where('session_id', get_session_id()); 
            $this->db->update('enroll', $data2);

            //set_alert('success', translate('information_has_been_updated_successfully'));


            }
            else{

                $data = array(
                    'contratoValidado' => $accion,
                    'contratoFirmado'=> $accion
                );
                
                $this->db->where('idEstudiante', $idStudent); // Asegúrate de tener el ID del estudiante correcto
                $this->db->update('estadoMatricula', $data);
    
                set_alert('error', ('Se rechazó los documentos, el padre puede volver a mandarlos'));


            }
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');


            $this->db->select('s.*,class.name AS className,section.name AS sectionName ,e.id AS idEstadoMatricula, e.subidaArchivos, e.citaAcademica, e.citaPsicologica, e.contratoEnviado, e.contratoFirmado,en.contratoValidado, en.estadoDeclaracion1, en.estadoDeclaracion2,en.estadoDeclaracion3');
            $this->db->from('student s');
            $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
            $this->db->join('enroll en', 's.id = en.student_id');
            $this->db->join('class ', 'class.id = en.class_id');
            $this->db->join('section ', 'section.id = en.section_id');
    
            $this->db->where('en.session_id', get_session_id());
            $this->db->where('en.branch_id', $branchID);
            if($sectionID != 'all'){
            $this->db->where( 'section.id',$sectionID);}
            if($classID != 'all'){

            $this->db->where( 'class.id',$classID);}

    
    
            $this->db->where('en.contratoFirmado', 1);
            $query = $this->db->get();
            $result = $query->result_array();

            $this->data['students'] = $result;
          
        }

        $this->data['title'] = translate('Revisar Contrato');
        $this->data['sub_page'] = 'secretaria/revisarContrato';
        $this->data['sub_page_2'] = 'externa';

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

       
       $this->data['main_menu'] = 'admission';
       $this->load->view('layout/index', $this->data);
    }


    public function verEstadoMatricula() {
        
        $branchID = $this->application_model->get_branch_id();
       
        $this->data['branch_id'] = $branchID;

        if (isset($_POST['search'])  ) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');


            $this->db->select('s.*, class.name AS className,s.id AS idEstudiante ,rp.estado AS estadopsicologica,rp.resultado AS resultadopsicologica,ra.resultado AS resultadoacademica  ,ra.estado AS estadoacademica ,e.id AS idEstadoMatricula, e.subidaArchivos, e.citaAcademica, e.citaPsicologica, e.contratoEnviado, e.contratoFirmado,e.contratoValidado, e.envioDocumento');
            $this->db->from('student s');
            $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
            $this->db->join('reservasPsicologicas rp', 's.id = rp.id_student', 'left');
            $this->db->join('reservasAcademicas ra', 's.id = ra.id_student', 'left');
            $this->db->join('enroll ', 'enroll.student_id = s.id');
            $this->db->join('class ', 'class.id = enroll.class_id');
            $this->db->join('section ', 'section.id = enroll.section_id');

            
            $this->db->where('enroll.session_id', get_session_id());
    
    
            $this->db->where('enroll.branch_id',  $branchID );
    
            if($sectionID != 'all'){
                $this->db->where( 'section.id',$sectionID);}
            if($classID != 'all'){
    
                $this->db->where( 'class.id',$classID);}


            $this->db->where('s.admission_date >', '2023-11-01');
    
    
            $query = $this->db->get();
            
            $result = $query->result_array();
            
            $this->data['students'] = $result;
        }
     



        if (isset($_POST['accion'])) {
                   
           
           
            $idStudent = $this->input->post('student_id');

            

            $config['allowed_types'] = 'pdf|doc|docx|jpeg|jpg|png|gif';
            
            $this->upload->initialize($config);

            if (!empty($_FILES['archivo']['name'])) {
                // Obtiene la extensión del archivo
                $this->db->select('*');
                $this->db->from('student');
                $this->db->where('id', $idStudent);
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
                $new_filename = 'DocumentoEnviadoConfirmar'.'_' . $estudiante['first_name'] .'_'. $estudiante['last_name'] . '.' . $file_extension;
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




            $data = array(
                'envioDocumento' => 1 // Nuevo valor para contratoValidado
            );
            
            $this->db->where('idEstudiante', $idStudent); // Asegúrate de tener el ID del estudiante correcto
            $this->db->update('estadoMatricula', $data);

            set_alert('success', translate('information_has_been_updated_successfully'));


            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');


            $this->db->select('s.*, class.name AS className,s.id AS idEstudiante ,rp.estado AS estadopsicologica ,ra.estado AS estadoacademica ,e.id AS idEstadoMatricula, e.subidaArchivos, e.citaAcademica, e.citaPsicologica, e.contratoEnviado, e.contratoFirmado,e.contratoValidado, e.envioDocumento');
            $this->db->from('student s');
            $this->db->join('estadoMatricula e', 's.id = e.idEstudiante');
            $this->db->join('reservasPsicologicas rp', 's.id = rp.id_student', 'left');
            $this->db->join('reservasAcademicas ra', 's.id = ra.id_student', 'left');
            $this->db->join('enroll ', 'enroll.student_id = s.id');
            $this->db->join('class ', 'class.id = enroll.class_id');
            $this->db->join('section ', 'section.id = enroll.section_id');

            
            $this->db->where('enroll.session_id', get_session_id());
    
    
            $this->db->where('enroll.branch_id',  $branchID );
    
            if($sectionID != 'all'){
                $this->db->where( 'section.id',$sectionID);}
            if($classID != 'all'){
    
                $this->db->where( 'class.id',$classID);}


            $this->db->where('s.admission_date >', '2023-11-01');
    
    
            $query = $this->db->get();
            
            $result = $query->result_array();
            
            $this->data['students'] = $result;

        }



        $this->data['title'] = translate('Ver estado Matricula');
        $this->data['sub_page'] = 'secretaria/verEstadoMatricula';
        
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

       
       $this->data['main_menu'] = 'admission';
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

    public function traslado()
    {
        // check access permission
        if (!get_permission('student_promotion', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post()) {
            $section_id = $this->input->post('section_id');
            $class_id = $this->input->post('class_id');
            $branch_id= $this->input->post('branch_id');
            $data = array(
                'section_id' => $section_id ,
                'class_id' => $class_id,
                'branch_id'=>$branch_id,
                // ... Agrega más columnas según sea necesario
            );
            $student_id = $this->input->post('student_id');


            $this->db->where('student_id', $student_id);
            $this->db->where('session_id', get_session_id());
            $query = $this->db->get('enroll');
            $resultado = $query->row();


            $datos_registro = array(
                'student_id' => $this->input->post('student_id'),
                'nivel_id' => $resultado->nivel_id,
                'class_id' =>   $this->input->post('class_id') ,
                'section_id' => $this->input->post('section_id'),
                'roll' => $resultado->roll,
                'session_id' => $resultado->session_id,
                'branch_id' => $branch_id= $this->input->post('branch_id'),
                
                'class_id_antigua' =>$resultado->class_id,
                'section_id_antigua' =>  $resultado->section_id,
                'branch_id_antigua' => $resultado->branch_id,
                'secretaria_id' => get_loggedin_user_id()
            );
            $this->db->insert('registroTraslados', $datos_registro);







            $this->db->where('student_id', $student_id);
            $this->db->where('session_id', get_session_id());
            $this->db->update('enroll', $data);



           




            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect('student/traslado');

        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('Cambio de sede');
        $this->data['sub_page'] = 'student/traslado';
        $this->data['main_menu'] = 'subject';
        $this->load->view('layout/index', $this->data);
    }


}
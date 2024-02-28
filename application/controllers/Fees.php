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

class Fees extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('fees_model');
    }

    public function index()
    {
        redirect(base_url('fees/type'));
    }

    /* fees type form validation rules */
    protected function type_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('type_name', translate('name'), 'trim|required|callback_unique_type');
    }

    /* fees type control */
    public function type()
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

    public function unique_type($name)
    {
        $branchID = $this->application_model->get_branch_id();
        $typeID = $this->input->post('type_id');
        if (!empty($typeID)) {
            $this->db->where_not_in('id', $typeID);
        }
        $this->db->where(array('name' => $name, 'branch_id' => $branchID));
        $uniform_row = $this->db->get('fees_type')->num_rows();
        if ($uniform_row == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_type", translate('already_taken'));
            return false;
        }
    }

    public function group($branch_id = '')
    {
        if (!get_permission('fees_group', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('fees_group', 'is_add')) {
                ajax_access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('name', translate('group_name'), 'trim|required');
            $elems = $this->input->post('elem');
            $sel = 0;
            if (count($elems)) {
                foreach ($elems as $key => $value) {
                    if (isset($value['fees_type_id'])) {
                        $sel++;
                        $this->form_validation->set_rules('elem[' . $key . '][due_date]', translate('due_date'), 'trim|required');
                        $this->form_validation->set_rules('elem[' . $key . '][amount]', translate('amount'), 'trim|required|greater_than[0]');
                    }
                }
            }
            if ($this->form_validation->run() !== false) {
                if ($sel != 0) {
                    $arrayGroup = array(
                        'name' => $this->input->post('name'),
                        'description' => $this->input->post('description'),
                        'session_id' => get_session_id(),
                        'branch_id' => $this->application_model->get_branch_id(),
                    );
                    $this->db->insert('fee_groups', $arrayGroup);
                    $groupID = $this->db->insert_id();
                    foreach ($elems as $key => $row) {
                        if (isset($row['fees_type_id'])) {
                            $arrayData = array(
                                'fee_groups_id' => $groupID,
                                'fee_type_id' => $row['fees_type_id'],
                                'due_date' => date("Y-m-d", strtotime($row['due_date'])),
                                'amount' => $row['amount'],
                            );
                            $this->db->where(array('fee_groups_id' => $groupID, 'fee_type_id' => $row['fees_type_id']));
                            $query = $this->db->get("fee_groups_details");
                            if ($query->num_rows() == 0) {
                                $this->db->insert('fee_groups_details', $arrayData);
                            }
                        }
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    set_alert('error', 'At least one type has to be selected.');
                }
                $url = base_url('fees/group');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id'] = $branch_id;
        $this->data['categorylist'] = $this->app_lib->getTable('fee_groups', array('t.session_id' => get_session_id(), 't.system' => 0));
        $this->data['title'] = translate('fees_group');
        $this->data['sub_page'] = 'fees/group';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function group_edit($id = '')
    {
        if (!get_permission('fees_group', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->form_validation->set_rules('name', translate('group_name'), 'trim|required');
            $elems = $this->input->post('elem');
            $sel = array();
            if (count($elems)) {
                foreach ($elems as $key => $value) {
                    if (isset($value['fees_type_id'])) {
                        $sel[] = $value['fees_type_id'];
                        $this->form_validation->set_rules('elem[' . $key . '][due_date]', translate('due_date'), 'trim|required');
                        $this->form_validation->set_rules('elem[' . $key . '][amount]', translate('amount'), 'trim|required|greater_than[0]');
                    }
                }
            }
            if ($this->form_validation->run() !== false) {
                if (count($sel)) {
                    $groupID = $this->input->post('group_id');
                    $arrayGroup = array(
                        'name' => $this->input->post('name'),
                        'description' => $this->input->post('description'),
                    );
                    $this->db->where('id', $groupID);
                    $this->db->update('fee_groups', $arrayGroup);
                    foreach ($elems as $key => $row) {
                        if (isset($row['fees_type_id'])) {
                            $arrayData = array(
                                'fee_groups_id' => $groupID,
                                'fee_type_id' => $row['fees_type_id'],
                                'due_date' => date("Y-m-d", strtotime($row['due_date'])),
                                'amount' => $row['amount'],
                            );
                            $this->db->where(array('fee_groups_id' => $groupID, 'fee_type_id' => $row['fees_type_id']));
                            $query = $this->db->get("fee_groups_details");
                            if ($query->num_rows() == 0) {
                                $this->db->insert('fee_groups_details', $arrayData);
                            } else {
                                $this->db->where('id', $query->row()->id);
                                $this->db->update('fee_groups_details', $arrayData);
                            }
                        }
                    }
                    $this->db->where_not_in('fee_type_id', $sel);
                    $this->db->where('fee_groups_id', $groupID);
                    $this->db->delete('fee_groups_details');
                    set_alert('success', translate('information_has_been_updated_successfully'));
                } else {
                    set_alert('error', 'At least one type has to be selected.');
                }
                $url = base_url('fees/group');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['group'] = $this->app_lib->getTable('fee_groups', array('t.id' => $id), true);
        $this->data['title'] = translate('fees_group');
        $this->data['sub_page'] = 'fees/group_edit';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function group_delete($id)
    {
        if (get_permission('fees_group', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('fee_groups');
            if ($this->db->affected_rows() > 0) {
                $this->db->where('fee_groups_id', $id);
                $this->db->delete('fee_groups_details');
            }
        }
    }

    /* fees type form validation rules */
    protected function fine_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('group_id', translate('group_name'), 'trim|required');
        $this->form_validation->set_rules('fine_type_id', translate('fees_type'), 'trim|required|callback_check_feetype');
        $this->form_validation->set_rules('fine_type', translate('fine_type'), 'trim|required');
        $this->form_validation->set_rules('fine_value', translate('fine') . " " . translate('value'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('fee_frequency', translate('late_fee_frequency'), 'trim|required');
    }

    public function fine_setup()
    {
        if (!get_permission('fees_fine_setup', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            if (!get_permission('fees_fine_setup', 'is_add')) {
                ajax_access_denied();
            }
            $this->fine_validation();
            if ($this->form_validation->run() !== false) {
                $insertData = array(
                    'group_id' => $this->input->post('group_id'),
                    'type_id' => $this->input->post('fine_type_id'),
                    'fine_value' => $this->input->post('fine_value'),
                    'fine_type' => $this->input->post('fine_type'),
                    'fee_frequency' => $this->input->post('fee_frequency'),
                    'branch_id' => $branchID,
                    'session_id' => get_session_id(),
                );
                $this->db->insert('fee_fine', $insertData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['finelist'] = $this->app_lib->getTable('fee_fine');
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('fine_setup');
        $this->data['main_menu'] = 'fees';
        $this->data['sub_page'] = 'fees/fine_setup';
        $this->load->view('layout/index', $this->data);
    }

    public function fine_setup_edit($id = '')
    {
        if (!get_permission('fees_fine_setup', 'is_edit')) {
            access_denied();
        }

        if ($_POST) {
            $branchID = $this->application_model->get_branch_id();
            $this->fine_validation();
            if ($this->form_validation->run() !== false) {
                $insertData = array(
                    'group_id' => $this->input->post('group_id'),
                    'type_id' => $this->input->post('fine_type_id'),
                    'fine_value' => $this->input->post('fine_value'),
                    'fine_type' => $this->input->post('fine_type'),
                    'fee_frequency' => $this->input->post('fee_frequency'),
                    'branch_id' => $branchID,
                    'session_id' => get_session_id(),
                );
                $this->db->where('id', $id);
                $this->db->update('fee_fine', $insertData);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('fees/fine_setup');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['fine'] = $this->app_lib->getTable('fee_fine', array('t.id' => $id), true);
        $this->data['title'] = translate('fine_setup');
        $this->data['sub_page'] = 'fees/fine_setup_edit';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function check_feetype($id)
    {
        $groupID = $this->input->post('group_id');
        $fineID = $this->input->post('fine_id');
        if (!empty($fineID)) {
            $this->db->where_not_in('id', $fineID);
        }
        $this->db->where('group_id', $groupID);
        $this->db->where('type_id', $id);
        $query = $this->db->get('fee_fine');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("check_feetype", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }

    public function fine_delete($id)
    {
        if (get_permission('fees_fine_setup', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('fee_fine');
        }
    }

    public function allocation()
    {
        if (!get_permission('fees_allocation', 'is_add')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $this->data['class_id'] = $this->input->post('class_id');
            $this->data['section_id'] = $this->input->post('section_id');
            $this->data['fee_group_id'] = $this->input->post('fee_group_id');
            $this->data['branch_id'] = $branchID;
            $this->data['studentlist'] = $this->fees_model->getStudentAllocationList($this->data['class_id'], $this->data['section_id'], $this->data['fee_group_id'], $branchID);
        }
        if (isset($_POST['save'])) {
            $student_array = $this->input->post('stu_operations');
            $student_ids = $this->input->post('student_ids');
            $student_sel_array = isset($student_array) ? $student_array : array();
            $delStudent = array_diff($student_ids, $student_sel_array);
            $fee_groupID = $this->input->post('fee_group_id');
            foreach ($student_array as $key => $value) {
                $arrayData = array(
                    'student_id' => $value,
                    'group_id' => $fee_groupID,
                    'session_id' => get_session_id(),
                    'branch_id' => $branchID,
                );
                $this->db->where($arrayData);
                $q = $this->db->get('fee_allocation');
                if ($q->num_rows() == 0) {
                    $this->db->insert('fee_allocation', $arrayData);
                }
            }
            if (!empty($delStudent)) {
                $this->db->where_in('student_id', $delStudent);
                $this->db->where('group_id', $fee_groupID);
                $this->db->where('session_id', get_session_id());
                $this->db->delete('fee_allocation');
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('fees/allocation'));
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('fees_allocation');
        $this->data['sub_page'] = 'fees/allocation';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function allocation_save()
    {
        if (!get_permission('fees_allocation', 'is_add')) {
            access_denied();
        }
        if ($_POST) {
            $branchID = $this->application_model->get_branch_id();
            $student_array = $this->input->post('stu_operations');
            $student_ids = $this->input->post('student_ids');
            $student_sel_array = isset($student_array) ? $student_array : array();
            $delStudent = array_diff($student_ids, $student_sel_array);
            $fee_groupID = $this->input->post('fee_group_id');
            if (!empty($student_sel_array)) {
                foreach ($student_array as $key => $value) {
                    $arrayData = array(
                        'student_id' => $value,
                        'group_id' => $fee_groupID,
                        'session_id' => get_session_id(),
                        'branch_id' => $branchID,
                    );
                    $this->db->where($arrayData);
                    $q = $this->db->get('fee_allocation');
                    if ($q->num_rows() == 0) {
                        $this->db->insert('fee_allocation', $arrayData);
                    }
                }
            }
            if (!empty($delStudent)) {
                $this->db->where_in('student_id', $delStudent);
                $this->db->where('group_id', $fee_groupID);
                $this->db->where('session_id', get_session_id());
                $this->db->delete('fee_allocation');
            }

            $message = translate('information_has_been_saved_successfully');
            $array = array('status' => 'success', 'message' => $message);
            echo json_encode($array);
        }
    }

    /* student fees invoice search user interface */
    public function invoice_list()
    {
        if (!get_permission('invoice', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $this->data['class_id'] = $this->input->post('class_id');
            $this->data['section_id'] = $this->input->post('section_id');
            $this->data['invoicelist'] = $this->fees_model->getInvoiceList($this->data['class_id'], $this->data['section_id'], $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('payments_history');
        $this->data['sub_page'] = 'fees/invoice_list';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function verPDF($nombre_archivo) {

        $userID = $this->input->get('parametro');

        $estudiante = $this->student_model->getSingleStudent($userID);
        $student_folder = $estudiante['first_name'] . '_' . $estudiante['last_name'];
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
    
    
    public function ver_pagos()
    {
        if (!get_permission('invoice', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $this->data['class_id'] = $this->input->post('class_id');
            $this->data['section_id'] = $this->input->post('section_id');

            $this->db->select('*')->from('comprobante_pago');
            $this->db->join('fee_allocation', 'comprobante_pago.allocation_id = fee_allocation.id');
            $this->db->join('student', 'fee_allocation.student_id = student.id');
            $this->db->join('fees_type', 'comprobante_pago.type_id = fees_type.id', 'left');

            $query = $this->db->get();
            $result = $query->result_array();
            $this->data['invoicelist'] = $result;

//            $this->data['invoicelist'] = $this->fees_model->getInvoiceList($this->data['class_id'], $this->data['section_id'], $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('payments_history');
        $this->data['sub_page'] = 'fees/ver_pagos';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function reporte_deudas()
    {



        if (!get_permission('invoice', 'is_view')) {
            access_denied();
        }

        $this->db->select('login_credential.username, allocation.id AS allocation_id, fees_type.id AS fee_type_id, student.register_no, student.first_name, student.last_name, enroll.session_id, fee_groups.session_id, class.name AS class_name, branch.name AS branch_name, (fee_groups_details.amount - COALESCE(descuentos.discount, 0)) AS net_amount, fee_groups_details.due_date');
        $this->db->from('fee_allocation AS allocation');
        $this->db->join('fee_groups', 'allocation.group_id = fee_groups.id');
        $this->db->join('fee_groups_details', 'fee_groups_details.fee_groups_id = fee_groups.id');
        $this->db->join('fees_type', 'fees_type.id = fee_groups_details.fee_type_id');
        $this->db->join('student', 'student.id = allocation.student_id');
        $this->db->join('login_credential', 'login_credential.user_id = student.id');
        $this->db->join('enroll', 'student.id = enroll.student_id');
        $this->db->join('class', 'enroll.class_id = class.id');
        $this->db->join('branch', 'enroll.branch_id = branch.id');
        $this->db->join('descuentos', 'descuentos.allocation_id = allocation.id AND descuentos.type_id = fees_type.id', 'left');
        $this->db->where('login_credential.role', 7);
        $this->db->where('enroll.session_id', 'fee_groups.session_id', FALSE); // Agregamos esta línea para comparar las sesiones
        $this->db->where('allocation.id NOT IN (SELECT history.allocation_id FROM fee_payment_history AS history WHERE history.type_id = fees_type.id)', NULL, FALSE);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        
        $this->data['invoicelist'] = $result;


        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('Reporte deudas');
        $this->data['sub_page'] = 'fees/reporte_deudas';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function descuento()
    {
        $this->data['config'] = $this->get_payment_config();
        $this->data['invoice'] = $this->fees_model->getInvoiceStatus(7);
        $this->data['basic'] = $this->fees_model->getInvoiceBasic(7);
        $this->data['title'] = translate('fees_history');
        $this->data['main_menu'] = 'fees';
        $this->data['sub_page'] = 'fees/descuento';
        $this->load->view('layout/index', $this->data);
    }
    public function invoice_delete($student_id)
    {
        if (!get_permission('invoice', 'is_delete')) {
            access_denied();
        }

        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('student_id', $student_id);
        $result = $this->db->get('fee_allocation')->result_array();
        foreach ($result as $key => $value) {
            $this->db->where('allocation_id', $value['id']);
            $this->db->delete('fee_payment_history');
        }

        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('student_id', $student_id);
        $this->db->delete('fee_allocation');
    }

    /* invoice user interface with information are controlled here */
    public function invoice($id = '')
    {
        if (!get_permission('invoice', 'is_view')) {
            access_denied();
        }
        $basic = $this->fees_model->getInvoiceBasic($id);
        if (empty($basic))
            redirect(base_url('dashboard'));
        $this->data['invoice'] = $this->fees_model->getInvoiceStatus($id);
        $this->data['basic'] = $this->fees_model->getInvoiceBasic($id);
        $this->data['title'] = translate('invoice_history');
        $this->data['main_menu'] = 'fees';
        $this->data['sub_page'] = 'fees/collect';
        $this->load->view('layout/index', $this->data);
    }

    public function invoicePrint()
    {
        if (!get_permission('invoice', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $this->data['student_array'] = $this->input->post('student_id');
            echo $this->load->view('fees/invoicePrint', $this->data, true);
        }
    }

    public function due_invoice()
    {
        if (!get_permission('due_invoice', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $this->data['class_id'] = $this->input->post('class_id');
            $this->data['section_id'] = $this->input->post('section_id');
            $feegroup = explode("|", $this->input->post('fees_type'));

            $feegroup_id = $feegroup[0];
            $fee_feetype_id = $feegroup[1];
            $this->data['invoicelist'] = $this->fees_model->getDueInvoiceList($this->data['class_id'], $this->data['section_id'], $feegroup_id, $fee_feetype_id);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('payments_history');
        $this->data['sub_page'] = 'fees/due_invoice';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }


    public function pago_secciones()
    {
        if (!get_permission('due_invoice', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $this->data['class_id'] = $this->input->post('class_id');
            $this->data['section_id'] = $this->input->post('section_id');
            $feegroup = explode("|", $this->input->post('fees_type'));

            $feegroup_id = $feegroup[0];
            $fee_feetype_id = $feegroup[1];
            $this->data['invoicelist'] = $this->fees_model->getDueInvoiceList($this->data['class_id'], $this->data['section_id'], $feegroup_id, $fee_feetype_id);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('payments_history');
        $this->data['sub_page'] = 'fees/pago_secciones';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);
    }

    public function fee_add()
    {
        if (!get_permission('collect_fees', 'is_add')) {
            ajax_access_denied();
        }
        $this->form_validation->set_rules('fees_type', translate('fees_type'), 'trim|required');
        $this->form_validation->set_rules('date', translate('date'), 'trim|required');
        $this->form_validation->set_rules('amount', translate('amount'), array('trim', 'required', 'numeric', 'greater_than[0]', array('deposit_verify', array($this->fees_model, 'depositAmountVerify'))));
        $this->form_validation->set_rules('discount_amount', translate('discount'), array('trim', 'numeric', array('deposit_verify', array($this->fees_model, 'depositAmountVerify'))));
        $this->form_validation->set_rules('pay_via', translate('payment_method'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $feesType = explode("|", $this->input->post('fees_type'));
            $amount = $this->input->post('amount');
            $fineAmount = $this->input->post('fine_amount');
            $discountAmount = $this->input->post('discount_amount');
            $date = $this->input->post('date');
            $payVia = $this->input->post('pay_via');
            $arrayFees = array(
                'allocation_id' => $feesType[0],
                'type_id' => $feesType[1],
                'collect_by' => get_loggedin_user_id(),
                'amount' => ($amount - $discountAmount),
                'discount' => $discountAmount,
                'fine' => $fineAmount,
                'pay_via' => $payVia,
                'remarks' => $this->input->post('remarks'),
                'date' => $date,
            );
            $this->db->insert('fee_payment_history', $arrayFees);

            // transaction voucher save function
            if (isset($_POST['account_id'])) {
                $arrayTransaction = array(
                    'account_id' => $this->input->post('account_id'),
                    'amount' => ($amount + $fineAmount) - $discountAmount,
                    'date' => $date,
                );
                $this->fees_model->saveTransaction($arrayTransaction);
            }

            // send payment confirmation sms
            if (isset($_POST['guardian_sms'])) {
                $arrayData = array(
                    'student_id' => $this->input->post('student_id'),
                    'amount' => ($amount + $fineAmount) - $discountAmount,
                    'paid_date' => _d($date),
                );
                $this->sms_model->send_sms($arrayData, 2);
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            $array = array('status' => 'success');
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'url' => '', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function getBalanceByType()
    {
        $input = $this->input->post('typeID');
        if (empty($input)) {
            $balance = 0;
            $fine = 0;
        } else {
            $feesType = explode("|", $input);
            $fine = $this->fees_model->feeFineCalculation($feesType[0], $feesType[1]);
            $b = $this->fees_model->getBalance($feesType[0], $feesType[1]);
            $balance = $b['balance'];
            $fine = abs($fine - $b['fine']);
        }
        echo json_encode(array('balance' => $balance, 'fine' => $fine));
    }

    public function getTypeByBranch()
    {
        $html = "";
        $branchID = $this->application_model->get_branch_id();
        $typeID = (isset($_POST['type_id']) ? $_POST['type_id'] : 0);
        if (!empty($branchID)) {
            $this->db->where('session_id', get_session_id());
            $this->db->where('branch_id', $branchID);
            $result = $this->db->get('fee_groups')->result_array();
            if (count($result)) {
                $html .= "<option value=''>" . translate('select') . "</option>";
                foreach ($result as $row) {
                    $html .= '<optgroup label="' . $row['name'] . '">';
                    $this->db->where('fee_groups_id', $row['id']);
                    $resultdetails = $this->db->get('fee_groups_details')->result_array();
                    foreach ($resultdetails as $t) {
                        $sel = ($t['fee_groups_id'] . "|" . $t['fee_type_id'] == $typeID ? 'selected' : '');
                        $html .= '<option value="' . $t['fee_groups_id'] . "|" . $t['fee_type_id'] . '"' . $sel . '>' . get_type_name_by_id('fees_type', $t['fee_type_id']) . '</option>';
                    }
                    $html .= '</optgroup>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }

    public function getGroupByBranch()
    {
        $html = "";
        $branch_id = $this->application_model->get_branch_id();
        if (!empty($branch_id)) {
            $result = $this->db->select('id,name')
                ->where(array('branch_id' => $branch_id, 'session_id' => get_session_id(), 'system' => 0))
                ->get('fee_groups')->result_array();
            if (count($result)) {
                $html .= "<option value=''>" . translate('select') . "</option>";
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }

    public function getTypeByGroup()
    {
        $html = "";
        $groupID = $this->input->post('group_id');
        $typeID = (isset($_POST['type_id']) ? $_POST['type_id'] : 0);
        if (!empty($groupID)) {
            $this->db->select('t.id,t.name');
            $this->db->from('fee_groups_details as gd');
            $this->db->join('fees_type as t', 't.id = gd.fee_type_id', 'left');
            $this->db->where('gd.fee_groups_id', $groupID);
            $result = $this->db->get()->result_array();
            if (count($result)) {
                $html .= "<option value=''>" . translate('select') . "</option>";
                foreach ($result as $row) {
                    $sel = ($row['id'] == $typeID ? 'selected' : '');
                    $html .= '<option value="' . $row['id'] . '" ' . $sel . '>' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('first_select_the_group') . '</option>';
        }
        echo $html;
    }

    protected function reminder_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('frequency', translate('frequency'), 'trim|required');
        $this->form_validation->set_rules('days', translate('days'), 'trim|required|numeric');
        $this->form_validation->set_rules('message', translate('message'), 'trim|required');
    }

    public function reminder()
    {
        if (!get_permission('fees_reminder', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            if (!get_permission('fees_reminder', 'is_add')) {
                ajax_access_denied();
            }
            $this->reminder_validation();
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $post['branch_id'] = $branchID;
                $this->fees_model->reminderSave($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id'] = $branchID;
        $this->data['reminderlist'] = $this->app_lib->getTable('fees_reminder');
        $this->data['title'] = translate('fees_reminder');
        $this->data['main_menu'] = 'fees';
        $this->data['sub_page'] = 'fees/reminder';
        $this->load->view('layout/index', $this->data);
    }

    public function edit_reminder($id = '')
    {
        if (!get_permission('fees_reminder', 'is_edit')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $this->reminder_validation();
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $post['branch_id'] = $branchID;
                $this->fees_model->reminderSave($post);
                $url = base_url('fees/reminder');
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['reminder'] = $this->app_lib->getTable('fees_reminder', array('t.id' => $id), true);
        $this->data['title'] = translate('fees_reminder');
        $this->data['main_menu'] = 'fees';
        $this->data['sub_page'] = 'fees/edit_reminder';
        $this->load->view('layout/index', $this->data);
    }

    public function reminder_delete($id = '')
    {
        if (get_permission('fees_reminder', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('fees_reminder');
        }
    }

    public function due_report()
    {
        if (!get_permission('fees_reports', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $this->data['class_id'] = $this->input->post('class_id');
            $this->data['section_id'] = $this->input->post('section_id');
            $this->data['invoicelist'] = $this->fees_model->getDueReport($this->data['class_id'], $this->data['section_id']);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('due_fees_report');
        $this->data['sub_page'] = 'fees/due_report';
        $this->data['main_menu'] = 'fees_repots';
        $this->load->view('layout/index', $this->data);
    }

    public function payment_history()
    {
        if (!get_permission('fees_reports', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $classID = $this->input->post('class_id');
            $paymentVia = $this->input->post('payment_via');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['invoicelist'] = $this->fees_model->getStuPaymentHistory($classID, "", $paymentVia, $start, $end, $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('fees_payment_history');
        $this->data['sub_page'] = 'fees/payment_history';
        $this->data['main_menu'] = 'fees_repots';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/daterangepicker/daterangepicker.css',
            ),
            'js' => array(
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function student_fees_report()
    {
        if (!get_permission('fees_reports', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $studentID = $this->input->post('student_id');
            $typeID = $this->input->post('fees_type');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['invoicelist'] = $this->fees_model->getStuPaymentReport($classID, $sectionID, $studentID, $typeID, $start, $end, $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('student_fees_report');
        $this->data['sub_page'] = 'fees/student_fees_report';
        $this->data['main_menu'] = 'fees_repots';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/daterangepicker/daterangepicker.css',
            ),
            'js' => array(
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function fine_report()
    {
        if (!get_permission('fees_reports', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $paymentVia = $this->input->post('payment_via');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['invoicelist'] = $this->fees_model->getStuPaymentHistory($classID, $sectionID, $paymentVia, $start, $end, $branchID, true);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('fees_fine_reports');
        $this->data['sub_page'] = 'fees/fine_report';
        $this->data['main_menu'] = 'fees_repots';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/daterangepicker/daterangepicker.css',
            ),
            'js' => array(
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function paymentRevert()
    {
        if (!get_permission('fees_revert', 'is_delete')) {
            $array = array('status' => 'error', 'message' => translate('access_denied'));
            echo json_encode($array);
            exit();
        }
        $array = array('status' => 'success', 'message' => translate('information_deleted'));
        $ids = $this->input->post('id');
        foreach ($ids as $key => $value) {
            $this->db->where('id', $value);
            $this->db->delete('fee_payment_history');
        }
        echo json_encode($array);
    }

    public function fee_fully_paid()
    {
        if (!get_permission('collect_fees', 'is_add')) {
            ajax_access_denied();
        }
        $this->form_validation->set_rules('date', translate('date'), 'trim|required');
        $this->form_validation->set_rules('pay_via', translate('payment_method'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $date = $this->input->post('date');
            $payVia = $this->input->post('pay_via');
            $invoiceID = $this->input->post('invoice_id');

            $allocations = $this->fees_model->getInvoiceDetails($invoiceID);
            $totalBalance = 0;
            $totalFine = 0;

            foreach ($allocations as $row) {
                $fine = $this->fees_model->feeFineCalculation($row['allocation_id'], $row['fee_type_id']);
                $b = $this->fees_model->getBalance($row['allocation_id'], $row['fee_type_id']);
                $fine = abs($fine - $b['fine']);
                if ($b['balance'] != 0) {
                    $totalBalance += $b['balance'];
                    $totalFine += $fine;
                    $arrayFees = array(
                        'allocation_id' => $row['allocation_id'],
                        'type_id' => $row['fee_type_id'],
                        'collect_by' => get_loggedin_user_id(),
                        'amount' => $b['balance'],
                        'discount' => 0,
                        'fine' => $fine,
                        'pay_via' => $payVia,
                        'remarks' => $this->input->post('remarks'),
                        'date' => $date,
                    );
                    $this->db->insert('fee_payment_history', $arrayFees);
                }
            }

            // transaction voucher save function
            if (isset($_POST['account_id'])) {
                $arrayTransaction = array(
                    'account_id' => $this->input->post('account_id'),
                    'amount' => ($totalBalance + $totalFine),
                    'date' => $date,
                );
                $this->fees_model->saveTransaction($arrayTransaction);
            }

            // send payment confirmation sms
            if (isset($_POST['guardian_sms'])) {
                $arrayData = array(
                    'student_id' => $this->input->post('student_id'),
                    'amount' => ($totalBalance + $totalFine),
                    'paid_date' => $date,
                );
                $this->sms_model->send_sms($arrayData, 2);
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            $array = array('status' => 'success');
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'url' => '', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function printFeesPaymentHistory()
    {
        if ($_POST) {
            $record = $this->input->post('data');
            $record_array = json_decode($record, true);
            $this->db->where_in('id', array_column($record_array, 'payment_id'));
            $paymentHistory = $this->db->select("sum(amount) as total_amount,sum(discount) as total_discount,sum(fine) as total_fine")->get('fee_payment_history')->row_array();
            $this->data['total_paid'] = $paymentHistory['total_amount'];
            $this->data['total_discount'] = $paymentHistory['total_discount'];
            $this->data['total_fine'] = $paymentHistory['total_fine'];
            $this->load->view('fees/printFeesPaymentHistory', $this->data);
        }
    }

    public function printFeesInvoice()
    {
        if ($_POST) {
            $record = $this->input->post('data');
            $record_array = json_decode($record);
            $total_fine = 0;
            $total_discount = 0;
            $total_paid = 0;
            $total_balance = 0;
            $total_amount = 0;
            foreach ($record_array as $key => $value) {
                $deposit = $this->fees_model->getStudentFeeDeposit($value->allocationID, $value->feeTypeID);
                $full_amount = $value->feeAmount;
                $type_discount = $deposit['total_discount'];
                $type_fine = $deposit['total_fine'];
                $type_amount = $deposit['total_amount'];
                $balance = $full_amount - ($type_amount + $type_discount);
                $total_discount += $type_discount;
                $total_fine += $type_fine;
                $total_paid += $type_amount;
                $total_balance += $balance;
                $total_amount += $full_amount;
            }
            $this->data['total_amount'] = $total_amount;
            $this->data['total_paid'] = $total_paid;
            $this->data['total_discount'] = $total_discount;
            $this->data['total_fine'] = $total_fine;
            $this->data['total_balance'] = $total_balance;
            $this->load->view('fees/printFeesInvoice', $this->data);
        }
    }

    
    public function ticket($boletaID){

    $this->db->select('boleta.*, staff.name AS nombreSecretaria');
    $this->db->from('boleta');
    $this->db->join('staff', 'staff.id = boleta.id_secretaria');

    $this->db->where('boleta.id', $boletaID);

    $query = $this->db->get();
    $boleta = $query->row_array();
    $tipo = ($boleta['metodo_pago'] == 1) ? "Boleta" : (($boleta['metodo_pago'] == 2) ? "Recibo" : "otro");

    // "otro" es el valor predeterminado si $boleta['metodo_pago'] no es ni 1 ni 2
    
    require_once APPPATH."third_party/code128.php";

    $pdf = new PDF_Code128('P','mm',array(80,258));
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    $pdf->Image('https://ecfmggf.stripocdn.email/content/guids/CABINET_6bb4331f6d2615b961384575ba5ab832b3a72a41035c22d0986924bf622b5711/images/sin_titulo1_mesa_de_trabajo_1.png', 10, 10, 60); // Ajusta las coordenadas y dimensiones según tu necesidad
    $pdf->SetY(35); // Ajusta la posición vertical según tus necesidades

    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper("GRUPO EDUCATIVO AI APAEC S.A.C.
    INICIAL-PRIMARIA-SECUNDARIA")),0,'C',false);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","RUC: 20477480463"),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Direccion  LA LIBERTAD - TRUJILLO - TRUJILLO"),0,'C',false);
   
    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Fecha: ".date("d/m/Y", strtotime($boleta['datePay']))." ".date("h:s A")),0,'C',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper($tipo." Nro: ". $boleta['nro_documento'])),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","ADQUIRIENTE:". $boleta['adquiriente']),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Documento: DNI 00000000"),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","N° OPERACION:: ".$boleta['numeroOperacion']),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);

    # Tabla de productos #
    
    $pdf->Cell(10,5,iconv("UTF-8", "ISO-8859-1","Cant."),0,0,'C');
    $pdf->Cell(19,5,iconv("UTF-8", "ISO-8859-1","Precio"),0,0,'C');
    $pdf->Cell(15,5,iconv("UTF-8", "ISO-8859-1","Desc."),0,0,'C');
    $pdf->Cell(28,5,iconv("UTF-8", "ISO-8859-1","Total"),0,0,'C');

    $pdf->Ln(3);
    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);
////////////////////////////////// 
    $estado =  $boleta['estado']; 

    if($estado =='Cancelado'){

    $this->db->select('fee_payment_history.cantidad AS cantidad, fee_payment_history.amount AS amount , fee_payment_history.fine as fine,
    student.first_name AS nombreAlumno, student.last_name AS apellidoAlumno, fee_payment_history.type_id AS tipoVenta, fees_type.name as nombreProducto');
    $this->db->from('ewgcgdaj_instituto.boleta');
    $this->db->join('ewgcgdaj_instituto.fee_payment_history', 'fee_payment_history.boleta_id = boleta.id');
    $this->db->join('ewgcgdaj_instituto.fee_allocation', 'fee_allocation.id = fee_payment_history.allocation_id', 'left');
    $this->db->join('ewgcgdaj_instituto.fees_type', 'fees_type.id = fee_payment_history.type_id', 'left');
    $this->db->join('ewgcgdaj_instituto.student', 'fee_allocation.student_id = student.id', 'left');
    $this->db->where('boleta.id', $boletaID);
    
}
else{
    $this->db->select('payment_historyAnuladosEliminados.cantidad AS cantidad, payment_historyAnuladosEliminados.amount AS amount , payment_historyAnuladosEliminados.fine as fine,
    student.first_name AS nombreAlumno, student.last_name AS apellidoAlumno, payment_historyAnuladosEliminados.type_id AS tipoVenta, fees_type.name as nombreProducto');
    $this->db->from('ewgcgdaj_instituto.boleta');
    $this->db->join('ewgcgdaj_instituto.payment_historyAnuladosEliminados', 'payment_historyAnuladosEliminados.boleta_id = boleta.id');
    $this->db->join('ewgcgdaj_instituto.fee_allocation', 'fee_allocation.id = payment_historyAnuladosEliminados.allocation_id', 'left');
    $this->db->join('ewgcgdaj_instituto.fees_type', 'fees_type.id = payment_historyAnuladosEliminados.type_id', 'left');
    $this->db->join('ewgcgdaj_instituto.student', 'fee_allocation.student_id = student.id', 'left');
    $this->db->where('boleta.id', $boletaID);
}

    $query = $this->db->get();
    $result = $query->result();
    $total=0;



    foreach ($result as $row) {

    /*----------  Detalles de la tabla  ----------*/

    if($row-> tipoVenta ==0){

        $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1","Pago Personalizado"),0,'C',false);

    }
    else{
    $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1",$row-> nombreProducto."--".$row->nombreAlumno." ".$row->apellidoAlumno ),0,'C',false);}
    $pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1",$row->cantidad),0,0,'C');
    $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1",number_format($row->amount + $row->fine,2)),0,0,'C');
    $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","0.00 "),0,0,'C');
    $numero = $row->cantidad*($row->amount + $row->fine);
    $pdf->Cell(28,4,iconv("UTF-8", "ISO-8859-1",number_format($numero,2)),0,0,'C');
    $pdf->Ln(4);
    $total=$total+ $numero;

}
    $pdf->Ln(7);
    /*----------  Fin Detalles de la tabla  ----------*/



    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

        $pdf->Ln(5);

   

    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL A PAGAR"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",$total),0,0,'C');

    $pdf->Ln(5);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Vendedor: ". $boleta['nombreSecretaria'] ),0,'C',false);

    $pdf->Ln(10);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** ¡GRACIAS POR SU PREFERENCIA!¡EDUCACIÓN INTEGRAL, INNOVADORA Y DE CALIDAD! ***"),0,'C',false);

   
    $pdf->Ln(9);

    # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),$boleta['nro_documento'],70,20);
    $pdf->SetXY(0,$pdf->GetY()+21);
    $pdf->SetFont('Arial','',14);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",$boleta['nro_documento']),0,'C',false);
    
    # Nombre del archivo PDF #
    $pdf->Output("I","Ticket_Nro_1.pdf",true);

}



public function boleta_electronica($boletaID){

    $this->db->select('boleta.*, staff.name AS nombreSecretaria');
    $this->db->from('boleta');
    $this->db->join('staff', 'staff.id = boleta.id_secretaria');

    $this->db->where('boleta.id', $boletaID);

    $query = $this->db->get();
    $boleta = $query->row_array();
    $tipo = ($boleta['metodo_pago'] == 1) ? "Boleta ELECTRÓNICA " : (($boleta['metodo_pago'] == 2) ? "Recibo" : "otro");

    // "otro" es el valor predeterminado si $boleta['metodo_pago'] no es ni 1 ni 2
    
    require_once APPPATH."third_party/code128.php";

    $pdf = new PDF_Code128('P','mm',array(80,258));
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    $pdf->Image('https://ecfmggf.stripocdn.email/content/guids/CABINET_6bb4331f6d2615b961384575ba5ab832b3a72a41035c22d0986924bf622b5711/images/sin_titulo1_mesa_de_trabajo_1.png', 10, 10, 60); // Ajusta las coordenadas y dimensiones según tu necesidad
    $pdf->SetY(35); // Ajusta la posición vertical según tus necesidades

    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper("GRUPO EDUCATIVO AI APAEC S.A.C.
    INICIAL-PRIMARIA-SECUNDARIA")),0,'C',false);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","RUC: 20477480463"),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Direccion  LA LIBERTAD - TRUJILLO - TRUJILLO"),0,'C',false);
   
    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Fecha: ".date("d/m/Y", strtotime($boleta['datePay']))." ".date("h:s A")),0,'C',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper($tipo." Nro: ". $boleta['nro_documento'])),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","ADQUIRIENTE:". $boleta['adquiriente']),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Documento: DNI ".$boleta ['dni_adquiriente'] ),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","N° OPERACION:: ".$boleta['numeroOperacion']),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);

    # Tabla de productos #
    
    $pdf->Cell(10,5,iconv("UTF-8", "ISO-8859-1","Cant."),0,0,'C');
    $pdf->Cell(19,5,iconv("UTF-8", "ISO-8859-1","Precio"),0,0,'C');
    $pdf->Cell(15,5,iconv("UTF-8", "ISO-8859-1","Desc."),0,0,'C');
    $pdf->Cell(28,5,iconv("UTF-8", "ISO-8859-1","Total"),0,0,'C');

    $pdf->Ln(3);
    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);
////////////////////////////////// 
    $estado =  $boleta['estado']; 

    if($estado =='Cancelado'){

    $this->db->select('fee_payment_history.cantidad AS cantidad, fee_payment_history.amount AS amount , fee_payment_history.fine as fine,
    student.first_name AS nombreAlumno, student.last_name AS apellidoAlumno, fee_payment_history.type_id AS tipoVenta, fees_type.name as nombreProducto');
    $this->db->from('ewgcgdaj_instituto.boleta');
    $this->db->join('ewgcgdaj_instituto.fee_payment_history', 'fee_payment_history.boleta_id = boleta.id');
    $this->db->join('ewgcgdaj_instituto.fee_allocation', 'fee_allocation.id = fee_payment_history.allocation_id', 'left');
    $this->db->join('ewgcgdaj_instituto.fees_type', 'fees_type.id = fee_payment_history.type_id', 'left');
    $this->db->join('ewgcgdaj_instituto.student', 'fee_allocation.student_id = student.id', 'left');
    $this->db->where('boleta.id', $boletaID);
    
}
else{
    $this->db->select('payment_historyAnuladosEliminados.cantidad AS cantidad, payment_historyAnuladosEliminados.amount AS amount , payment_historyAnuladosEliminados.fine as fine,
    student.first_name AS nombreAlumno, student.last_name AS apellidoAlumno, payment_historyAnuladosEliminados.type_id AS tipoVenta, fees_type.name as nombreProducto');
    $this->db->from('ewgcgdaj_instituto.boleta');
    $this->db->join('ewgcgdaj_instituto.payment_historyAnuladosEliminados', 'payment_historyAnuladosEliminados.boleta_id = boleta.id');
    $this->db->join('ewgcgdaj_instituto.fee_allocation', 'fee_allocation.id = payment_historyAnuladosEliminados.allocation_id', 'left');
    $this->db->join('ewgcgdaj_instituto.fees_type', 'fees_type.id = payment_historyAnuladosEliminados.type_id', 'left');
    $this->db->join('ewgcgdaj_instituto.student', 'fee_allocation.student_id = student.id', 'left');
    $this->db->where('boleta.id', $boletaID);
}

    $query = $this->db->get();
    $result = $query->result();
    $total=0;



    foreach ($result as $row) {

    /*----------  Detalles de la tabla  ----------*/

    if($row-> tipoVenta ==0){

        $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1","Pago Personalizado"),0,'C',false);

    }
    else{
    $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1",$row-> nombreProducto."--".$row->nombreAlumno." ".$row->apellidoAlumno ),0,'C',false);}
    $pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1",$row->cantidad),0,0,'C');
    $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1",number_format($row->amount + $row->fine,2)),0,0,'C');
    $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","0.00 "),0,0,'C');
    $numero = $row->cantidad*($row->amount + $row->fine);
    $pdf->Cell(28,4,iconv("UTF-8", "ISO-8859-1",number_format($numero,2)),0,0,'C');
    $pdf->Ln(4);
    $total=$total+ $numero;

}
    $pdf->Ln(7);
    /*----------  Fin Detalles de la tabla  ----------*/



    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

        $pdf->Ln(5);

   

    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL A PAGAR"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",$total),0,0,'C');

    $pdf->Ln(5);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Vendedor: ". $boleta['nombreSecretaria'] ),0,'C',false);

    $pdf->Ln(10);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** ¡GRACIAS POR SU PREFERENCIA!¡EDUCACIÓN INTEGRAL, INNOVADORA Y DE CALIDAD! ***"),0,'C',false);

   
    $pdf->Ln(9);

   
   // 20123456789|03|B001|1|36.00|100.00|2024-02-25|1|48285071|
   $numero_documento = explode('-', $boleta['nro_documento']); // Dividir la cadena en partes usando el guion como separador
   $prefijo = $numero_documento[0]; // Obtener el prefijo (por ejemplo, "B001")
   $numero_siguiente = ltrim($numero_documento[1], '0'); // Eliminar los ceros iniciales del número siguiente al guion
   $fecha_convertida = date("d-m-Y", strtotime($boleta['datePay']));

   $texto_qr = ('20477480463|03|' . $prefijo . '|' . $numero_siguiente.'|0'.$total.'|'.$fecha_convertida.'|1|'.$boleta ['dni_adquiriente']);



    $imagen_qr = $this->generar_qr($texto_qr);
    
    file_put_contents('qr_temp.png', $imagen_qr); // Guardar temporalmente la imagen para verificar si se está generando correctamente

    $espacio_disponible = $pdf->GetPageHeight() - $pdf->GetY();
    $alto_qr = 20; // Suponiendo que el QR tenga un alto de 20 mm
    
    if ($espacio_disponible < $alto_qr + 10) {
        // Si no hay suficiente espacio en la página actual, añade una nueva página
        $pdf->AddPage();
        // Coloca el QR al inicio de la página
        $pdf->SetY(10); // Ajusta la posición vertical según sea necesario
    }

    $pdf->Image('qr_temp.png', 30,$pdf->GetY() ,20,20); // Agregar la imagen al PDF


    # Codigo de barras #

    
    # Nombre del archivo PDF #
    $pdf->Output("I","Boleta_Electronica-".$boleta['nro_documento'].".pdf",true);

}

public function generar_qr($texto) {
    // URL base de la API de goQR.me
    $url_base = 'https://api.qrserver.com/v1/create-qr-code/';

    // Parámetros para la solicitud GET
    $params = array(
        'size' => '200x200',  // Tamaño del código QR
        'data' => ($texto)  // Texto o URL que se codificará en el código QR
    );

    // Construir la URL completa con los parámetros
    $url = $url_base . '?' . http_build_query($params);

    // Realizar la solicitud GET
    $respuesta = file_get_contents($url);

    // Retornar la respuesta (la imagen del código QR)
    return $respuesta;
}


    public function ver_documentos(){

      

    

        $branchID = $this->application_model->get_branch_id();
        if ($this->input->post('search')) {
            $this->data['fecha_inicio'] = $this->input->post('fecha_inicio');
            $this->data['fecha_fin'] = $this->input->post('fecha_fin');

            $fechaInicio = $this->input->post('fecha_inicio');

            $fechaFin = $this->input->post('fecha_fin');

           

            $this->db->select('boleta.*,fee_payment_history.fine as mora, boleta.id as boletaID, payment_types.*, SUM(fee_payment_history.amount +  fee_payment_history.fine) as total_amount, staff.name as secretaria, boleta.id as idDocumento, boleta.metodo_pago as metodo_pagoID, branch.name as branchName');
            $this->db->from('boleta');
            $this->db->join('fee_payment_history', 'boleta.id = fee_payment_history.boleta_id', 'left');
            $this->db->join('payment_types', 'boleta.pay_via = payment_types.id', 'left');
            $this->db->join('staff', 'staff.id = boleta.id_secretaria', 'left');
            $this->db->join('branch', 'branch.id = staff.branch_id', 'left');
            


            $this->db->group_by('boleta.id');

            $this->db->where('boleta.datePay >=', $fechaInicio);
            $this->db->where('boleta.datePay <=', $fechaFin);
            
            $query = $this->db->get();
            $resultados = $query->result_array();

            $this->data['invoicelist'] =  $resultados ;
        }


        if ($this->input->post('anular')) {
            $this->data['fecha_inicio'] = $this->input->post('fecha_inicio');
            $this->data['fecha_fin'] = $this->input->post('fecha_fin');

            $fechaInicio = $this->input->post('fecha_inicio');

            $fechaFin = $this->input->post('fecha_fin');
            $idBoleta = $this->input->post('idBoleta');

           
            ////////////////ANULAR
            $this->db->select('fee_payment_history.*'); // Corregido para seleccionar todas las columnas de fee_payment_history
            $this->db->from('boleta');
            $this->db->join('fee_payment_history', 'boleta.id = fee_payment_history.boleta_id');
            $this->db->where('boleta.id =', $idBoleta);
            $query = $this->db->get();
            $resultados = $query->result_array();

            foreach ($resultados as $registro) {
                unset($registro['id']);

                $this->db->insert('payment_historyAnuladosEliminados', $registro);
            }

            $data = array('estado' => 'Anulado');
            $this->db->where('id', $idBoleta);
            $this->db->update('boleta', $data);
            
            $this->db->where('boleta_id', $idBoleta);
            $this->db->delete('fee_payment_history');
            

            


            /////////////////////

            $this->db->select('boleta.*, boleta.id as boletaID, payment_types.*, SUM(fee_payment_history.amount+ fee_payment_history.fine) as total_amount, staff.name as secretaria, boleta.id as idDocumento, boleta.metodo_pago as metodo_pagoID, branch.name as branchName');
            $this->db->from('boleta');
            $this->db->join('fee_payment_history', 'boleta.id = fee_payment_history.boleta_id', 'left');
            $this->db->join('payment_types', 'boleta.pay_via = payment_types.id', 'left');
            $this->db->join('staff', 'staff.id = boleta.id_secretaria', 'left');
            $this->db->join('branch', 'branch.id = staff.branch_id', 'left');

            
            $this->db->group_by('boleta.id');

            $this->db->where('boleta.datePay >=', $fechaInicio);
            $this->db->where('boleta.datePay <=', $fechaFin);
            
            $query = $this->db->get();
            $resultados = $query->result_array();

            $this->data['invoicelist'] =  $resultados ;
        }







        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('Ver Documentos');
        $this->data['sub_page'] = 'fees/ver_documentos';
        $this->data['main_menu'] = 'fees';
        $this->load->view('layout/index', $this->data);



    }
    public function realizar_ventas(){

        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('Ventas');
        $this->data['sub_page'] = 'fees/realizar_ventas';
        $this->data['main_menu'] = 'fees';

     
        $this->load->view('layout/index', $this->data);

    }

    public function actualizar_tabla_pagos() {

        $student_id = $this->input->post('student_id');

        $this->db->select('login_credential.username,student.register_no, student.first_name, student.last_name, 
        class.name as class_name, branch.name as branch_name, (fee_groups_details.amount - COALESCE(descuentos.discount, 0)) AS net_amount, fee_groups_details.due_date,
         fees_type.name AS descripcion, fee_groups_details.fee_type_id AS feeTypeID,  allocation.id AS allocationID ,  fee_groups_details.amount as amount , descuentos.discount as descuento');
        $this->db->from('fee_allocation as allocation');
        $this->db->join('fee_groups', 'allocation.group_id = fee_groups.id');
        $this->db->join('fee_groups_details', 'fee_groups_details.fee_groups_id = fee_groups.id');
        $this->db->join('fees_type', 'fees_type.id = fee_groups_details.fee_type_id');
        $this->db->join('student', 'student.id = allocation.student_id');

        $this->db->join('login_credential', 'login_credential.user_id = student.id');


        $this->db->join('enroll', 'student.id = enroll.student_id');
        $this->db->join('class', 'enroll.class_id = class.id');
        $this->db->join('branch', 'enroll.branch_id = branch.id');
        $this->db->join('descuentos', 'descuentos.allocation_id = allocation.id AND descuentos.type_id = fees_type.id', 'left');
        $this->db->where('enroll.session_id', get_session_id());
        $this->db->where('student.id', $student_id); // Agrega esta línea para filtrar por el ID del estudiante

        $this->db->where('login_credential.role', 7);

        $this->db->where("allocation.id NOT IN (SELECT history.allocation_id FROM fee_payment_history as history WHERE history.type_id = fees_type.id)", NULL, FALSE);
    
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as &$row) {
            $fine = $this->fees_model->feeFineCalculation($row['allocationID'], $row['feeTypeID']);
            $b = $this->fees_model->getBalance($row['allocationID'], $row['feeTypeID']);
            $fine = abs($fine - $b['fine']);
            
            // Sumar 'fine' a 'amount'
            $row['net_amount'] += $fine;

            $row['net_amount'] = number_format($row['net_amount'], 2);

        }




        // Por ejemplo, asumiendo que $students es un array de estudiantes
      
        echo json_encode($result);
    }
    
    public function actualizar_numeros_documentos() {



        $branchId = $this->input->post('branch_id');


        $this->db->select('codigo');
        $this->db->from('branch');
        $this->db->where('branch.id', $branchId);
        $this->db->limit(1); // Limitar a un solo registro
        
        $query = $this->db->get(); // Ejecuta la consulta
        
        if ($query->num_rows() > 0) {
            $row = $query->row(); // Obtiene la primera fila
            $codigo = $row->codigo; // Obtiene el valor de la columna 'codigo'
            // Ahora puedes usar $codigo como necesites
        } else {
            // No se encontraron registros que cumplan con la condición
        }


        $payDocument = $this->input->post('pay_document');
  

        $this->db->from('boleta');
        $this->db->where('boleta.sede', $codigo);
        $this->db->where('metodo_pago', '1');
        



        $boletaNumero = $this->db->count_all_results()+1;
        $formato = 'B001-%06d'; // %06d indica que debe haber 6 dígitos, rellenados con ceros si es necesario
        $branch_id_padded = str_pad($codigo, 3, '0', STR_PAD_LEFT); // Ajusta a una longitud de 3 caracteres
        $formato = 'B' . $branch_id_padded . '-%06d'; // Concatenar $branch_id al inicio del formato
        $boletaFormateado = sprintf($formato, $boletaNumero);
        
        $this->db->from('boleta');
        $this->db->where('boleta.sede', $codigo);
        $this->db->where('metodo_pago', '2');
        $reciboNumero = $this->db->count_all_results()+1;
        $formato2 = 'R' . $branch_id_padded . '-%06d'; // Concatenar $branch_id al inicio del formato
        
        $reciboFormateado = sprintf($formato2, $reciboNumero);



        // Luego, envía la respuesta como JSON
        $response = array(
            'boletaFormatted' => $boletaFormateado,
            'reciboFormatted' => $reciboFormateado
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function generar_boleta() {
           // Obtener datos del formulario
        $branchId = $this->input->post('formData')['branch_id'];
        $payDocument = $this->input->post('formData')['pay_document'];
        $numeroDocumento = $this->input->post('formData')['numeroDocumento'];
        $date = $this->input->post('formData')['date'];
        $payVia = $this->input->post('formData')['pay_via'];
        $datePay = $this->input->post('formData')['datePay'];
        $remarks = $this->input->post('formData')['remarks'];
        $adquiriente = $this->input->post('formData')['adquiriente'];
        $dni_adquiriente = $this->input->post('formData')['dni_adquiriente'];

        // Obtener datos de la tablaSegunda

       

     
        $response = array(
            'success' => true,
            'message' => 'Datos recibidos correctamente'
        );


        $data = array(
            'nro_documento' => $numeroDocumento,
            'pay_via' => $payVia,
            'numeroOperacion' => $remarks,
            'date' => $date,
            'datePay' => $datePay,
            'metodo_pago' => $payDocument,
            'sede' => $branchId,
            'id_secretaria' => get_loggedin_user_id(),
            'adquiriente'=>$adquiriente,
            'dni_adquiriente'=>$dni_adquiriente

        );
    
        // Insertar en la base de datos
        $this->db->insert('boleta', $data);

      

        $tableData = $this->input->post('tableData');
        $this->db->select('id');
        $this->db->from('boleta');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();


       


        if ($query->num_rows() > 0) {
            $row = $query->row();
            $ultimo_id = $row->id ;
            echo "El último ID de la tabla boleta es: " . $ultimo_id;
        } else {
            $ultimo_id = 1;
        }
        $firstIteration = true;



        
        foreach ($tableData as $row) {
            if ($firstIteration) {
                // Ignorar la primera iteración
                $firstIteration = false;
                continue;
            }
            
            $feeType = floatval($row['feeType']); // Convierte a float
            $allocationID = intval($row['allocationID']); // Convierte a entero
            $amount = floatval($row['amount']); // Convierte a float
            $descuento = floatval($row['descuento']); // Convierte a float
            $amountPersonalizado = floatval($row['amountPersonalizado']); // Convierte a float
            
            
            if(  $row['feeType'] != null){
           
            $data = array(
                'allocation_id' => $feeType,    
                'type_id' => $allocationID,
                'amount' => $amount -$descuento,
                'fine' => $amountPersonalizado-($amount -$descuento),
                'discount' => $descuento,
                'boleta_id'=> $ultimo_id,
                'cantidad'=> 1,

            );
        
            // Insertar en la base de datos
            $this->db->insert('fee_payment_history', $data);
        }
        else {
            $amountPersonalizado = intval($row['amountPersonalizado']);
            $cantidad = intval($row['cantidad']);


            $data = array(
                'allocation_id' => 0,
                'type_id' => 0,
                'amount' => $amountPersonalizado,
                'discount' => 0,
                'boleta_id'=> $ultimo_id,
                'cantidad'=> $cantidad,
            );
        
            // Insertar en la base de datos
            $this->db->insert('fee_payment_history', $data);

        }
         
        }


        // Enviar la respuesta en formato JSON
        echo json_encode($response);


    }






    public function selectedFeesPay()
    {
        if (!get_permission('collect_fees', 'is_add')) {
            ajax_access_denied();
        }

        $items = $this->input->post('collect_fees');
        foreach ($items as $key => $value) {
            $this->form_validation->set_rules('collect_fees[' . $key . '][date]', translate('date'), 'trim|required');
            $this->form_validation->set_rules('collect_fees[' . $key . '][pay_via]', translate('payment_method'), 'trim|required');
            $this->form_validation->set_rules('collect_fees[' . $key . '][amount]', translate('amount'), 'trim|required|numeric|greater_than[0]');
            $this->form_validation->set_rules('collect_fees[' . $key . '][discount_amount]', translate('discount'), 'trim|numeric');
            $this->form_validation->set_rules('collect_fees[' . $key . '][fine_amount]', translate('fine'), 'trim|numeric');
            if (isset($value['account_id'])) {
                $this->form_validation->set_rules('collect_fees[' . $key . '][account_id]', translate('account'), 'trim|required');
            }
            $remainAmount = $this->fees_model->getBalance($value['allocation_id'], $value['type_id']);
            if ($remainAmount['balance'] < $value['amount']) {
                $error = array('collect_fees[' . $key . '][amount]' => 'Amount cannot be greater than the remaining.');
                $array = array('status' => 'fail', 'error' => $error);
                echo json_encode($array);
                exit;
            }

            $remainAmount = $this->fees_model->getBalance($value['allocation_id'], $value['type_id']);
            if ($remainAmount['balance'] < $value['discount_amount']) {
                $error = array('collect_fees[' . $key . '][discount_amount]' => 'Amount cannot be greater than the remaining.');
                $array = array('status' => 'fail', 'error' => $error);
                echo json_encode($array);
                exit;
            }
        }

        if ($this->form_validation->run() !== false) {
            $studentID = $this->input->post('student_id');
            foreach ($items as $key => $value) {
                $amount = $value['amount'];
                $fineAmount = $value['fine_amount'];
                $discountAmount = $value['discount_amount'];
                $date = $value['date'];
                $datePay = $value['datePay'];
                $payVia = $value['pay_via'];
                $payViaDocumento = $value['pay_document'];
                $numeroDocumento = $value['numeroDocumento'];

                $arrayFees = array(
                    'allocation_id' => $value['allocation_id'],
                    'type_id' => $value['type_id'],
                    'collect_by' => get_loggedin_user_id(),
                    'amount' => ($amount - $discountAmount),
                    'discount' => $discountAmount,
                    'fine' => $fineAmount,
                    'pay_via' => $payVia,
                    'remarks' => $value['remarks'],
                    'date' => $date,
                    'datePay'=> $datePay,
                    'metodopago'=> $payViaDocumento,
                    'nro_documento'=> $numeroDocumento


                );
                $this->db->insert('fee_payment_history', $arrayFees);

                // transaction voucher save function
                if (isset($value['account_id'])) {
                    $arrayTransaction = array(
                        'account_id' => $value['account_id'],
                        'amount' => ($amount + $fineAmount) - $discountAmount,
                        'date' => $date,
                    );
                    $this->fees_model->saveTransaction($arrayTransaction);
                }
                // send payment confirmation sms
                $arrayData = array(
                    'student_id' => $studentID,
                    'amount' => ($amount + $fineAmount) - $discountAmount,
                    'paid_date' => _d($date),
                );
                $this->sms_model->send_sms($arrayData, 2);
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            $array = array('status' => 'success');
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }



    public function selectedFeesCollect()
    {
        if ($_POST) {
            $record = $this->input->post('data');
            $record_array = json_decode($record);
            $this->data['student_id'] = $this->input->post('student_id');
            $this->data['branch_id'] = $this->application_model->get_branch_id();
            $this->data['record_array'] = $record_array;
            $this->load->view('fees/selectedFeesCollect', $this->data);
        }
    }

    public function descuentos()
    {
        if ($_POST) {
            $record = $this->input->post('data');
            $record_array = json_decode($record);
            $this->data['student_id'] = $this->input->post('student_id');
            $this->data['branch_id'] = $this->application_model->get_branch_id();
            $this->data['record_array'] = $record_array;
            $this->load->view('fees/descuentos', $this->data);
        }
    }
    public function guardarDescuentos()
    {
       

        $items = $this->input->post('collect_fees');
     
       
            $studentID = $this->input->post('student_id');
            foreach ($items as $key => $value) {
                $amount = $value['amount'];
                $fineAmount = $value['fine_amount'];
                $discountAmount = $value['discount_amount'];
                $date = $value['date'];
                $payVia = $value['pay_via'];
                $arrayFees = array(
                    'allocation_id' => $value['allocation_id'],
                    'type_id' => $value['type_id'],
                    'descuento_by' => get_loggedin_user_id(),
                    'discount' => $discountAmount,
                    'date' => $date,
                );
                
                $this->db->where('allocation_id', $value['allocation_id']);
                $this->db->where('type_id', $value['type_id']);
                $query = $this->db->get('descuentos');
                
                if ($query->num_rows() > 0) {
                    // Si existe, realiza un UPDATE en lugar de INSERT
                    $this->db->where('allocation_id', $value['allocation_id']);
                    $this->db->where('type_id', $value['type_id']);
                    $this->db->update('descuentos', $arrayFees);
                } else {
                    // Si no existe, realiza un INSERT
                    $this->db->insert('descuentos', $arrayFees);
                }

                // transaction voucher save function
                if (isset($value['account_id'])) {
                    $arrayTransaction = array(
                        'account_id' => $value['account_id'],
                        'amount' => ($amount + $fineAmount) - $discountAmount,
                        'date' => $date,
                    );
                   // $this->fees_model->saveTransaction($arrayTransaction);
                }
                // send payment confirmation sms
                $arrayData = array(
                    'student_id' => $studentID,
                    'amount' => ($amount + $fineAmount) - $discountAmount,
                    'paid_date' => _d($date),
                );
                //$this->sms_model->send_sms($arrayData, 2);
            }   
            set_alert('success', translate('information_has_been_saved_successfully'));
            $array = array('status' => 'success');
        
        echo json_encode($array);
    }


    public function descargar_boleta_electronica() {
        // Obtener los datos enviados por la solicitud AJAX
        $tipo_archivo = $this->input->post('Detalle');
        $boletaID = $this->input->post('boletaID');
    
        // Consultar la base de datos para obtener la dirección del archivo según el tipo seleccionado
        if ($tipo_archivo == '1') {
            $columna_archivo = 'direccion_xml'; // Nombre de la columna para la dirección del archivo XML en la tabla 'boleta'
        } elseif ($tipo_archivo == '2') {
            $columna_archivo = 'direccion_cdr'; // Nombre de la columna para la dirección del archivo CDR en la tabla 'boleta'
        } else {
            // Si el tipo de archivo no es válido, redirige o muestra un mensaje de error
            redirect(base_url('ruta/a/donde/redirigir')); // Cambia esto por la ruta de redirección deseada
            // o muestra un mensaje de error
            echo "Tipo de archivo no válido";
            return;
        }
    
        // Consulta a la base de datos para obtener la dirección del archivo
        $this->load->database(); // Cargar la base de datos si aún no está cargada
        $this->db->select($columna_archivo);
        $this->db->where('id', $boletaID);
        $query = $this->db->get('boleta');
    

        if ($query->num_rows() > 0) {
            // Obtener el primer registro
            $row = $query->row();
            $direccion_archivo = $row->$columna_archivo;
        
            // Imprimir la dirección del archivo en la consola
            echo "Dirección del archivo: " . $direccion_archivo;
            $this->load->helper('download');
            $ruta_archivo = __DIR__ . '/../..' . $direccion_archivo; // Establece la ruta correcta a tu archivo XML
           
            $nombre_fichero = $ruta_archivo;
            $fichero_texto = fopen($nombre_fichero, "r");
            $contenido_fichero = fread($fichero_texto, filesize($nombre_fichero));

            header('Content-Type: text/xml');
            header("Content-Disposition:attachment ; filename=nombrearchivo.xml");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            echo $contenido_fichero;

        } 


     
    }

    public function downloadXML($boletaID) {
        $this->load->database(); // Cargar la base de datos si aún no está cargada
        $this->db->select('direccion_xml');
        $this->db->where('id', $boletaID);
        $query = $this->db->get('boleta');
        $direccion_archivo="";
        if ($query->num_rows() > 0) {
            // Obtener el primer registro
            $row = $query->row();
            $direccion_archivo = $row->direccion_xml;}

        $data =file_get_contents(__DIR__ .'/../..'.$direccion_archivo);
        force_download('prueba',$data);

    }

    



}
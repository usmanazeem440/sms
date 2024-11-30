<?php

/**
 * 
 */
class Schoolhouse extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("schoolhouse_model");
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'admin/schoolhouse');
        $data['title'] = 'Add School House';
        $data["house_name"] = "";
        $data["description"] = "";
        $houselist = $this->schoolhouse_model->get();
        $data["houselist"] = $houselist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/schoolhouse/houselist', $data);
        $this->load->view('layout/footer', $data);
    }
    function changePassword(){

        $result = $this->schoolhouse_model->getStudents();
        $digi = 123;
        for ($i=0; $i < count($result); $i++){

            $username = $result[$i]['admission_no'];
            $pass = $result[$i]['admission_no'].$digi;

            $data = array(
                'username' => $username,
                'password' => $pass,
            );

            $data1 = array(
                'username' => $result[$i]['guardian_id'],
                'password' => $result[$i]['guardian_id'].$digi,
            );

            $this->db->where('user_id', $result[$i]['id']);
            $this->db->where('role', 'student');
            $this->db->update('users', $data);


            $this->db->where('user_id', $result[$i]['id']);
            $this->db->where('role', 'parent');
            $this->db->update('users', $data1);
        }

        return $this->index();
    }

    function create() {
        if (!$this->rbac->hasPrivilege('student_houses', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add School House';
        $houselist = $this->schoolhouse_model->get();
        $data["houselist"] = $houselist;
        $data["house_name"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules('house_name', 'House Name', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/schoolhouse/houselist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'house_name' => $this->input->post('house_name'),
                'is_active' => 'yes',
                'description' => $this->input->post('description')
            );
            $this->schoolhouse_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">School House added successfully</div>');
            redirect('admin/schoolhouse/index');
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('student_houses', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit School House';
        $houselist = $this->schoolhouse_model->get();
        $data["houselist"] = $houselist;
        $data['id'] = $id;
        $house = $this->schoolhouse_model->get($id);
        $data["house"] = $house;
        $data["house_name"] = $house["house_name"];
        $data["description"] = $house["description"];
        $this->form_validation->set_rules('house_name', 'House Name', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/schoolhouse/houselist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'house_name' => $this->input->post('house_name'),
                'is_active' => 'yes',
                'description' => $this->input->post('description')
            );
            $this->schoolhouse_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">School Houses updated successfully</div>');
            redirect('admin/schoolhouse');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('student_houses', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $this->schoolhouse_model->delete($id);
        }
        redirect('admin/schoolhouse/');
    }

}

?>
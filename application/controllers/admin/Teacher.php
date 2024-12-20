<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Teacher extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('mailsmsconf');
        $this->load->model("classteacher_model");
        $this->role;
    }

    function index() {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'teacher/index');
        $data['title'] = 'Add Teacher';
        $teacher_result = $this->teacher_model->get();
        $data['teacherlist'] = $teacher_result;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/teacherList', $data);
        $this->load->view('layout/footer', $data);
    }

    function getSubjctByClassandSection() {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $data = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
        echo json_encode($data);
    }

    function assignteacher() {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/teacher/viewassignteacher');
        $data['title'] = 'Assign Teacher with Class and Subject wise';
        //$teacher = $this->teacher_model->get();
        $teacher = $this->staff_model->getStaffbyrole(5);
        $data['teacherlist'] = $teacher;
        $subject = $this->subject_model->get();
        $data['subjectlist'] = $subject;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);
        // }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/assignTeacher', $data);
        $this->load->view('layout/footer', $data);
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $loop = $this->input->post('i');
            $array = array();
            foreach ($loop as $key => $value) {
                $s = array();
                $s['session_id'] = $this->setting_model->getCurrentSession();
                $class_id = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $dt = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);

                $s['class_section_id'] = $dt['id'];
                $s['teacher_id'] = $this->input->post('teacher_id_' . $value);
                $s['subject_id'] = $this->input->post('subject_id_' . $value);
                $s['class_id'] = $this->input->post('class_id');
                $row_id = $this->input->post('row_id_' . $value);
                if ($row_id == 0) {
                    $insert_id = $this->teachersubject_model->add($s);
                    $array[] = $insert_id;
                } else {
                    $s['id'] = $row_id;
                    $array[] = $row_id;
                    $this->teachersubject_model->add($s);
                }
            }

            $ids = $array;
            $class_section_id = $dt['id'];
            $this->teachersubject_model->deleteBatch($ids, $class_section_id);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Record updated successfully</div>');
            redirect('admin/teacher/assignteacher');
        }
    }

    function viewassignteacher() {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/teacher/viewassignteacher');
        $data['title'] = 'Assign Teacher with Class and Subject wise';
        //$teacher = $this->teacher_model->get();
        $teacher = $this->staff_model->getStaffbyrole(5);
        $data['teacherlist'] = $teacher;
        $subject = $this->subject_model->get();
        $data['subjectlist'] = $subject;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //   if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        // }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/viewassignTeacher', $data);
        $this->load->view('layout/footer', $data);
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $loop = $this->input->post('i');
            $array = array();
            foreach ($loop as $key => $value) {
                $s = array();
                $s['session_id'] = $this->setting_model->getCurrentSession();
                $class_id = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $dt = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);

                $s['class_section_id'] = $dt['id'];
                $s['teacher_id'] = $this->input->post('teacher_id_' . $value);
                $s['subject_id'] = $this->input->post('subject_id_' . $value);
                $row_id = $this->input->post('row_id_' . $value);
                if ($row_id == 0) {
                    $insert_id = $this->teachersubject_model->add($s);
                    $array[] = $insert_id;
                } else {
                    $s['id'] = $row_id;
                    $array[] = $row_id;
                    $this->teachersubject_model->add($s);
                }
            }

            $ids = $array;
            $class_section_id = $dt['id'];
            $this->teachersubject_model->deleteBatch($ids, $class_section_id);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Record updated successfully</div>');
            redirect('admin/teacher/assignteacher');
        }
    }

    public function getSubjectTeachers() {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run()) {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $dt = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);
            $data = $this->teachersubject_model->getDetailByclassAndSection($dt['id']);
            echo json_encode(array('st' => 0, 'msg' => $data));
        } else {
            $data = array(
                'class_id' => form_error('class_id'),
                'section_id' => form_error('section_id'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    function view($id) {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Teacher List';
        $teacher = $this->teacher_model->get($id);
        $teachersubject = $this->teachersubject_model->getTeacherClassSubjects($id);
        $data['teacher'] = $teacher;
        $data['teachersubject'] = $teachersubject;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/teacherShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Teacher List';
        $this->teacher_model->remove($id);
        redirect('admin/teacher/index');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add teacher';
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->form_validation->set_rules('name', 'Teacher', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $teacher_result = $this->teacher_model->get();
            $data['teacherlist'] = $teacher_result;
            $genderList = $this->customlib->getGender();
            $data['genderList'] = $genderList;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/teacher/teacherCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'sex' => $this->input->post('gender'),
                'dob' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'image' => 'uploads/student_images/no_image.png',
            );
            $insert_id = $this->teacher_model->add($data);
            $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $data_student_login = array(
                'username' => $this->teacher_login_prefix . $insert_id,
                'password' => $user_password,
                'user_id' => $insert_id,
                'role' => 'teacher'
            );
            $this->user_model->add($data_student_login);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/teacher_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'image' => 'uploads/teacher_images/' . $img_name);
                $this->teacher_model->add($data_img);
            }
            $teacher_login_detail = array('id' => $insert_id, 'credential_for' => 'teacher', 'username' => $this->teacher_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('phone'));

            $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Teacher added successfully</div>');
            redirect('admin/teacher/index');
        }
    }

    function handle_upload() {
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png');
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["file"]["type"] != 'image/gif' &&
                    $_FILES["file"]["type"] != 'image/jpeg' &&
                    $_FILES["file"]["type"] != 'image/png') {

                $this->form_validation->set_message('handle_upload', 'File type not allowed');
                return false;
            }
            if (!in_array($extension, $allowedExts)) {

                $this->form_validation->set_message('handle_upload', 'Extension not allowed');
                return false;
            }
            if ($_FILES["file"]["size"] > 10240000) {

                $this->form_validation->set_message('handle_upload', 'File size shoud be less than 100 kB');
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            return true;
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Teacher';
        $data['id'] = $id;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $teacher = $this->teacher_model->get($id);
        $data['teacher'] = $teacher;
        $this->form_validation->set_rules('name', 'Teacher', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $teacher_result = $this->teacher_model->get();
            $data['teacherlist'] = $teacher_result;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/teacher/teacherEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'sex' => $this->input->post('gender'),
                'dob' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone')
            );
            $insert_id = $this->teacher_model->add($data);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/teacher_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => 'uploads/teacher_images/' . $img_name);
                $this->teacher_model->add($data_img);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Teacher updated successfully</div>');
            redirect('admin/teacher/index');
        }
    }

    function getlogindetail() {
        $teacher_id = $this->input->post('teacher_id');
        $examSchedule = $this->user_model->getTeacherLoginDetails($teacher_id);
        echo json_encode($examSchedule);
    }

    //     function assign_class_teacher(){
    // if(!$this->rbac->hasPrivilege('assign_class_teacher','can_view')){
    //        access_denied();
    //        }
    //         $this->session->set_userdata('top_menu', 'Academics');
    //        $this->session->set_userdata('sub_menu', 'classes/index');
    //        $data['title'] = 'Add Class Teacher';
    //        $data['title_list'] = 'Class List';
    //        $this->form_validation->set_rules(
    //                'class', 'Class', array(
    //            'required',
    //            array('class_exists', array($this->class_model, 'class_exists'))
    //                )
    //        );
    //        $this->form_validation->set_rules('sections[]', 'Section', 'trim|required|xss_clean');
    //        if ($this->form_validation->run() == FALSE) {
    //        } else {
    //            $class = $this->input->post("class");
    //            $sections = $this->input->post("sections");
    //            $teachers = $this->input->post("teachers");
    //            $i = 0;
    //            foreach ($teachers as $key => $value) {
    //                 $section = "";
    //                if(array_key_exists($i,$sections)){
    //                    $section = $sections[$i];
    //                }else{
    //                    $section = $sections[0];
    //                }
    //               $classteacherid = $this->input->post("classteacherid");
    //                if(isset($classteacherid)){
    //                 $data = array('id' => $classteacherid,
    //                                'class_id' => $class,
    //                              'section_id' => $section,
    //                              'staff_id' => $teachers[$i],
    //                             );   
    //                }else{
    //                    $data = array('class_id' => $class,
    //                              'section_id' => $section,
    //                              'staff_id' => $teachers[$i],
    //                             );
    //                }
    //                  $i++;
    //             $this->classteacher_model->addClassTeacher($data); 
    //            }
    //             redirect('classes/assign_class_teacher');
    //        }
    //        $classlist = $this->class_model->get();
    //        $data['classlist'] = $classlist;
    //        $sectionlist = $this->section_model->get();
    //        $data['sectionlist'] = $sectionlist;
    //        $assignteacherlist = $this->class_model->getClassTeacher();
    //        $data['assignteacherlist'] = $assignteacherlist;
    //        $teacherlist = $this->staff_model->getStaffbyrole($role=2);
    //        $data['teacherlist'] = $teacherlist;
    //        $this->load->view('layout/header', $data);
    //        $this->load->view('class/classTeacher', $data);
    //        $this->load->view('layout/footer', $data);
    //    }
    function assign_class_teacher() {

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/teacher/assign_class_teacher');
        $data['title'] = 'Add Class Teacher';
        $data['title_list'] = 'Class List';

        $this->form_validation->set_rules(
                'class', 'Class', array(
            'required',
            array('class_exists', array($this->class_model, 'class_exists'))
                )
        );
        $this->form_validation->set_rules('section', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('teachers[]', 'Teacher', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            
        } else {

            $class = $this->input->post("class");
            $section = $this->input->post("section");
            $teachers = $this->input->post("teachers");

            $i = 0;
            foreach ($teachers as $key => $value) {

                $classteacherid = $this->input->post("classteacherid");
                if (isset($classteacherid)) {

                    $data = array('id' => $classteacherid[$i],
                        'class_id' => $class,
                        'section_id' => $section,
                        'staff_id' => $teachers[$i],
                    );
                } else {
                    $data = array('class_id' => $class,
                        'section_id' => $section,
                        'staff_id' => $teachers[$i],
                    );
                }
                $i++;
                $this->classteacher_model->addClassTeacher($data);
            }


            redirect('admin/teacher/assign_class_teacher');
        }
        $classlist = $this->class_model->get();
        $data['classlist'] = $classlist;

        $sectionlist = $this->section_model->get();
        $data['sectionlist'] = $sectionlist;


        $assignteacherlist = $this->class_model->getClassTeacher();
        $data['assignteacherlist'] = $assignteacherlist;
        // echo "<pre>";
        // print_r($assignteacherlist);
        // exit();
        foreach ($assignteacherlist as $key => $value) {
            $class_id = $value["class_id"];
            $section_id = $value["section_id"];

            $tlist[] = $this->classteacher_model->teacherByClassSection($class_id, $section_id);
        }
        $data["tlist"] = $tlist;

        $teacherlist = $this->staff_model->getStaffbyrole($role = 5);

        $data['teacherlist'] = $teacherlist;

        $this->load->view('layout/header', $data);
        $this->load->view('class/classTeacher', $data);
        $this->load->view('layout/footer', $data);
    }

    function classteacheredit($class_id, $section_id) {

        $result = $this->classteacher_model->teacherByClassSection($class_id, $section_id);

        $data["result"] = $result;

        $classlist = $this->class_model->get();
        $data['classlist'] = $classlist;

        $sectionlist = $this->section_model->get();
        $data['sectionlist'] = $sectionlist;

        $assignteacherlist = $this->class_model->getClassTeacher();
        $data['assignteacherlist'] = $assignteacherlist;
        foreach ($assignteacherlist as $key => $value) {
            $classid = $value["class_id"];
            $sectionid = $value["section_id"];

            $tlist[] = $this->classteacher_model->teacherByClassSection($classid, $sectionid);
        }

        $data["tlist"] = $tlist;

        $teacherlist = $this->staff_model->getStaffbyrole($role = 5);

        $data['teacherlist'] = $teacherlist;

        $data['class_id'] = $class_id;
        $data['section_id'] = $section_id;

        $this->load->view('layout/header', $data);
        $this->load->view('class/classTeacherEdit', $data);
        $this->load->view('layout/footer', $data);
    }

    function update_class_teacher() {

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'classes/index');
        $data['title'] = 'Add Class Teacher';
        $data['title_list'] = 'Class List';

        $this->form_validation->set_rules(
                'class', 'Class', array(
            'required',
            array('class_exists', array($this->class_model, 'class_exists'))
                )
        );
        $this->form_validation->set_rules('section', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('teachers[]', 'Teacher', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            
        } else {

            $section = $this->input->post('section');
            $prev_teacher = $this->input->post('classteacherid');
            $staff_id = $this->input->post('teachers');
            $class_id = $this->input->post('class');
            if (!isset($prev_teacher)) {
                $prev_teacher = array();
            }
            $add_result = array_diff($staff_id, $prev_teacher);
            $delete_result = array_diff($prev_teacher, $staff_id);

            if (!empty($add_result)) {
                $teacher_batch_array = array();
                foreach ($add_result as $teacher_add_key => $teacher_add_value) {

                    $teacher_batch_array[] = $teacher_add_value;
                }

                $insert_array = array();
                foreach ($teacher_batch_array as $vec_key => $vec_value) {

                    $vehicle_array = array(
                        'class_id' => $class_id,
                        'section_id' => $section,
                        'staff_id' => $vec_value,
                    );
                    $this->classteacher_model->addClassTeacher($vehicle_array);
                    $insert_array[] = $vehicle_array;
                }
            }


            if (!empty($delete_result)) {
                $classteacher_delete_array = array();
                foreach ($delete_result as $vec_delete_key => $vec_delete_value) {

                    $classteacher_delete_array[] = $vec_delete_value;
                }

                print_r($classteacher_delete_array);
                $this->classteacher_model->delete($class_id, $section, $classteacher_delete_array);
            }

            redirect('admin/teacher/assign_class_teacher');
        }
        $classlist = $this->class_model->get();
        $data['classlist'] = $classlist;

        $sectionlist = $this->section_model->get();
        $data['sectionlist'] = $sectionlist;


        $assignteacherlist = $this->class_model->getClassTeacher();
        $data['assignteacherlist'] = $assignteacherlist;

        foreach ($assignteacherlist as $key => $value) {
            $class_id = $value["class_id"];
            $section_id = $value["section_id"];

            $tlist[] = $this->classteacher_model->teacherByClassSection($class_id, $section_id);
        }
        $data["tlist"] = $tlist;

        $teacherlist = $this->staff_model->getStaffbyrole($role = 5);

        $data['teacherlist'] = $teacherlist;

        $this->load->view('layout/header', $data);
        $this->load->view('class/classTeacher', $data);
        $this->load->view('layout/footer', $data);
    }

    function classteacherdelete($class_id, $section_id) {

        if ((!empty($class_id)) && (!empty($section_id))) {

            $this->classteacher_model->delete($class_id, $section_id, null);
            redirect("admin/teacher/assign_class_teacher");
        }
    }

}

?>
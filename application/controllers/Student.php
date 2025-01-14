<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->model("classteacher_model");
        $this->load->model('studentsession_model');
        $this->load->model("timeline_model");
        $this->blood_group = $this->config->item('bloodgroup');
        $this->role;
    }

    function index()
    {

        $data['title'] = 'Student List';
        $student_result = $this->student_model->get();
        $data['studentlist'] = $student_result;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentList', $data);
        $this->load->view('layout/footer', $data);
    }
    
    
    function classExportFormat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/class_section_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'class_section_sample_file.csv';

        force_download($name, $data);
    }

    function studentreport()
    {
        if (!$this->rbac->hasPrivilege('student_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/studentreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $RTEstatusList = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        $userdata = $this->customlib->getUserData();
        $carray = array();
        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentReport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layout/header', $data);
                $this->load->view('student/studentReport', $data);
                $this->load->view('layout/footer', $data);
            } else {
                $class = $this->input->post('class_id');
                $section = $this->input->post('section_id');
                $category_id = $this->input->post('category_id');
                $gender = $this->input->post('gender');
                $rte = $this->input->post('rte');
                $search = $this->input->post('search');
                if (isset($search)) {
                    if ($search == 'search_filter') {
                        $resultlist = $this->student_model->searchByClassSectionCategoryGenderRte($class, $section, $category_id, $gender, $rte);
                        $data['resultlist'] = $resultlist;
                    }
                    $data['class_id'] = $class;
                    $data['section_id'] = $section;
                    $data['category_id'] = $category_id;
                    $data['gender'] = $gender;
                    $data['rte_status'] = $rte;
                    $this->load->view('layout/header', $data);
                    $this->load->view('student/studentReport', $data);
                    $this->load->view('layout/footer', $data);
                }
            }
        }
    }

    public function exportAllStudent()
    {
        
       // print_r($_POST);exit;
        $this->load->library('excel');
        
        if($this->input->post('downloadStudentsExcelSimple')){
             // Header
         $headings = array(
             'Student Name','Admission Number','Roll Number','Admission Date',
             'Class','Section','Father Name',
             'Gender', 'Mobile Number', 'Email','State','City',
             'Religon', 'DOB','Permanent Address', 'Guardian Id',
             'Guardian name','Guardian relation',
             'Guardian phone','Guardian address', 'Iqama Number',  'Category Id',
             'Category Name','Status',
             
         );
         
        $columns = ' CONCAT(students.firstname," ",students.lastname),  students.admission_no ,'
                        . ' students.roll_no,students.admission_date ,classes.class, sections.section,'
                        . ' ,students.father_name ,students.gender,'
                        . 'students.mobileno, students.email ,students.state ,   students.city ,      students.religion,     '
                        . 'students.dob ,students.current_address,  '
                        . 'students.guardian_id, students.guardian_name , students.guardian_relation,'
                        . 'students.guardian_phone,students.guardian_address, students.iqama_expiry_date, IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.is_active  ';
        $students= $this->student_model->getAllStudents($columns,true);
         $filename='all_students_general_'.time().'.xlsx'; //save our workbook as this file name
        }
        
            if($this->input->post('downloadStudentsExcelDetail')){
              
                  // Header
         $headings = array(
                "Admission No",                "Roll No",                "First Name",                "Last Name",
                "DOB",                "Religion",                "Birth Place",                "Mobile #",
                "Email",                "Admission Date",                "Blood Group",                "Mother Tongue",
                "Address",                "Father Name",                "Father Phone",                "Father Occupation",
                "Mother Name",                "Mother Phone",                "Mother Occupation",                "Guardian Id",
                "Guardian Name",                "Guardian Relation",                "Guardian Email",                "Guardian Phone",
                "Guardian Occupation",                "Guardian Address",                "Current Address",                "Permanent Address",
                "Bank Account No",                "Bank Name",                "IFSC Code",                "Insurance Number",
                "Previous School",                "Note",                "Name Arabic",                "Nationality",
                "Gender",                "Birth Place",                "City",                "Iqama Number",
                "Passport Number",                "Iqama Expiry Date",                "Passport Expiry Date",                "Passport Issued From",
                "School House Id",                "Category Id",                "Father Education",                "Father Iqama Number",
                "Father Passport Number",                "Father Iqama Expiry",                "Father Passport Expiry",                "Father Passport Issued From",
                "Mother Education",                "Insurance Company Name",                "Insurance Policy Number",                "Insurance Contact Number",
                "Allergies",                "Medicine"
             
         );

           $columns = 'admission_no, roll_no,
                        firstname,lastname,dob,religion,birth_place,mobileno,
                        email,admission_date,blood_group,mother_tongue,address,
                        father_name,father_phone,father_occupation,mother_name,
                        mother_phone,mother_occupation,guardian_id,guardian_name,
                        guardian_relation,guardian_email,guardian_phone,guardian_occupation,
                        guardian_address,current_address,permanent_address,bank_account_no,
                        bank_name,ifsc_code,insurance_number,previous_school,note,
                        name_arabic,nationality,gender,birth_place,city,iqama_number,
                        passport_number,iqama_expiry_date,passport_expiry_date,passport_issued_from,
                        school_house_id,category_id,father_education,father_iqama_number,father_passport_number,
                        father_iqama_expiry,father_passport_expiry,father_passport_issued_from,mother_education,
                        insurance_company_name,insurance_policy_number,insurance_contact_number,allergies,medicine' ;
           $students= $this->student_model->getAllStudents($columns); 
                $filename='all_students_details_'.time().'.xlsx'; //save our workbook as this file name
            }
        $this->excel->getActiveSheet()->fromArray($headings, null, 'A1');
                        
                        
            if(count($students)>0)
           $this->excel->getActiveSheet()->fromArray($students,null,'A2');

		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as.XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
              //  ob_end_clean();
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
                exit;
    }
    
    
   
    
      public function download($student_id, $doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/student_documents/$student_id/" . $this->uri->segment(4);
        $data = file_get_contents($filepath);
        $name = $this->uri->segment(6);
        force_download($name, $data);
    }


    function view($id)
    {

        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Student Details';
        $student = $this->student_model->get($id);
        $gradeList = $this->grade_model->get();
        $studentSession = $this->student_model->getStudentSession($id);
        $timeline = $this->timeline_model->getStudentTimeline($id, $status = '');
        $data["timeline_list"] = $timeline;

        $student_session_id = $student["student_session_id"];

        $student_session = $studentSession["session"];
        $data["session"] = $student_session;
        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student_session_id);
        // dd($student_due_fee);
        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student_session_id);
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        $siblings = $this->student_model->getMySiblings($student['parent_id'], $student['id']);

        $examList = $this->examschedule_model->getExamByClassandSection($student['class_id'], $student['section_id']);
        $data['examSchedule'] = array();
        if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $student['id'];
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student['id']);
                foreach ($exam_subjects as $key => $value) {
                    $exam_array = array();
                    $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
                    $exam_array['exam_id'] = $value['exam_id'];
                    $exam_array['full_marks'] = $value['full_marks'];
                    $exam_array['passing_marks'] = $value['passing_marks'];
                    $exam_array['exam_name'] = $value['name'];
                    $exam_array['exam_type'] = $value['type'];
                    $exam_array['attendence'] = $value['attendence'];
                    $exam_array['get_marks'] = $value['get_marks'];
                    $x[] = $exam_array;
                }
                $array['exam_name'] = $ex_value['name'];
                $array['exam_result'] = $x;
                $new_array[] = $array;
            }
            $data['examSchedule'] = $new_array;
        }
        $student_doc = $this->student_model->getstudentdoc($id);
        $data['student_doc'] = $student_doc;
        $data['student_doc_id'] = $id;
        $category_list = $this->category_model->get();
        $data['category_list'] = $category_list;
        $data['gradeList'] = $gradeList;
        $data['student'] = $student;
        $data['siblings'] = $siblings;
        $class_section = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"] = $class_section;
        $studentlistbysection = $this->student_model->getStudentClassSection($student["class_id"]);
        $data["studentlistbysection"] = $studentlistbysection;
        // dd($data['student_due_fee']);
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_student_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'import_student_sample_file.csv';

        force_download($name, $data);
    }

    function updateExportFormat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/update_student_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'update_student_sample_file.csv';

        force_download($name, $data);
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student', 'can_delete')) {
            access_denied();
        }
        $this->student_model->remove($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Record Deleted Successfully');
        redirect('student/search');
    }

    function doc_delete($id, $student_id)
    {
        $this->student_model->doc_delete($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Document Deleted Successfully');
        redirect('student/view/' . $student_id);
    }

    function create()
    {
        if (!$this->rbac->hasPrivilege('student', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/create');
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $data['title'] = 'Add Student';
        $data['title_list'] = 'Recently Added Student';
        $session = $this->setting_model->getCurrentSession();
        $student_result = $this->student_model->getRecentRecord();
        $data['studentlist'] = $student_result;
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();

        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $houses = $this->student_model->gethouselist();
        $data['houses'] = $houses;
        $data["bloodgroup"] = $this->blood_group;
        $hostelList = $this->hostel_model->get();
        $data['hostelList'] = $hostelList;
        $vehroute_result = $this->vehroute_model->get();
        $data['vehroutelist'] = $vehroute_result;
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_is', 'Guardian', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'trim|required|xss_clean');


        $this->form_validation->set_rules('guardian_phone', 'Guardian Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('admission_no', 'Admission No', 'trim|required|xss_clean|is_unique[students.admission_no]');
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_upload');
        $this->form_validation->set_rules(
            'roll_no', 'Roll No.', array('trim',
                array('check_exists', array($this->student_model, 'valid_student_roll'))
            )
        );


        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('student/studentCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $fees_discount = $this->input->post('fees_discount');

            $vehroute_id = $this->input->post('vehroute_id');
            $hostel_room_id = $this->input->post('hostel_room_id');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }
            $data = array(
                'name_arabic' => $this->input->post('name_arabic'),
                'nationality' => $this->input->post('nationality'),
                'birth_place' => $this->input->post('birth_place'),
                'mother_tongue' => $this->input->post('mother_tongue'),
                'iqama_number' => $this->input->post('iqama_number'),
                'passport_number' => $this->input->post('passport_number'),
                'iqama_expiry_date' => $this->input->post('iqama_expiry'),
                'passport_expiry_date' => $this->input->post('passport_expiry'),
                'passport_issued_from' => $this->input->post('passport_issued_from'),
                'father_iqama_number' => $this->input->post('father_iqama_number'),
                'father_passport_number' => $this->input->post('father_passport_number'),
                'father_iqama_expiry' => $this->input->post('father_iqama_expiry'),
                'father_passport_expiry' => $this->input->post('father_passport_expiry'),
                'father_passport_issued_from' => $this->input->post('father_passport_issued_from'),
                'father_education' => $this->input->post('father_education'),
                'mother_education' => $this->input->post('mother_education'),
                'insurance_number' => $this->input->post('insurance_number'),
                'insurance_company_name' => $this->input->post('insurance_company_name'),
                'insurance_policy_number' => $this->input->post('insurance_policy_number'),
                'insurance_contact_number' => $this->input->post('insurance_contact_number'),
                'allergies' => $this->input->post('allergies'),
                'medicine' => $this->input->post('medicine'),
                'address' => $this->input->post('address'),

                'admission_no' => $this->input->post('admission_no'),
                'roll_no' => $this->input->post('roll_no'),
                'admission_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('admission_date'))),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'mobileno' => $this->input->post('mobileno'),

                'email' => $this->input->post('email'),
                'state' => $this->input->post('state'),
                'city' => $this->input->post('city'),
                'guardian_is' => $this->input->post('guardian_is'),
                'pincode' => $this->input->post('pincode'),
                'religion' => $this->input->post('religion'),

                'previous_school' => $this->input->post('previous_school'),
                'dob' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),
                'current_address' => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'image' => 'uploads/student_images/no_image.png',
                'category_id' => $this->input->post('category_id'),
                'adhar_no' => $this->input->post('adhar_no'),
                'samagra_id' => $this->input->post('samagra_id'),
                'bank_account_no' => $this->input->post('bank_account_no'),
                'bank_name' => $this->input->post('bank_name'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'father_name' => $this->input->post('father_name'),
                'father_phone' => $this->input->post('father_phone'),
                'father_occupation' => $this->input->post('father_occupation'),
                'mother_name' => $this->input->post('mother_name'),
                'mother_phone' => $this->input->post('mother_phone'),
                'mother_occupation' => $this->input->post('mother_occupation'),
                'guardian_occupation' => $this->input->post('guardian_occupation'),
                'guardian_id' => $this->input->post('guardian_id'),
                'guardian_email' => $this->input->post('guardian_email'),
                'gender' => $this->input->post('gender'),
                'guardian_name' => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone' => $this->input->post('guardian_phone'),
                'guardian_address' => $this->input->post('guardian_address'),
                'vehroute_id' => $vehroute_id,
                'hostel_room_id' => $hostel_room_id,
                'school_house_id' => $this->input->post('house'),
                'blood_group' => $this->input->post('blood_group'),
                'note' => $this->input->post('note'),
                'is_active' => 'yes',
                'measurement_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('measure_date')))
            );
            $insert_id = $this->student_model->add($data);
            // dd($data);

            $data_new = array(
                'student_id' => $insert_id,
                'class_id' => $class_id,
                'section_id' => $section_id,
                'session_id' => $session,
                'fees_discount' => $fees_discount
            );
            $this->student_model->add_student_session($data_new);
            // $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            // $user_password = $this->input->post('admission_no').'123';
            $user_password = $this->student_login_prefix . "-" . $insert_id;
            $sibling_id = $this->input->post('sibling_id');
            $num = 123;
            $data_student_login = [
                //'username' => $this->student_login_prefix . $insert_id,
                //'password' => $user_password,
                'username' => $this->input->post('admission_no'),
                'password' => $this->input->post('admission_no') . $num,
                'user_id' => $insert_id,
                'role' => 'student'
            ];
            $this->user_model->add($data_student_login);
            if ($sibling_id > 0) {
                $student_sibling = $this->student_model->get($sibling_id);
                $update_student = array(
                    'id' => $insert_id,
                    'parent_id' => $student_sibling['parent_id'],
                );
                $student_sibling = $this->student_model->add($update_student);
            } else {
                //$parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                // $parent_password =$this->input->post('guardian_id').'123';
                $parent_password = "ptr-" . $insert_id;
                $temp = $insert_id;
                $num_2 = 123;
                $data_parent_login = array(
                    //'username' => $this->parent_login_prefix . $insert_id,
                    //'password' => $parent_password,
                    'username' => $this->input->post('guardian_id'),
                    'password' => $this->input->post('guardian_id') . $num_2,
                    'user_id' => $insert_id,
                    'role' => 'parent',
                    'childs' => $temp
                );

                $ins_parent_id = $this->user_model->add($data_parent_login);
                $update_student = array(
                    'id' => $insert_id,
                    'parent_id' => $ins_parent_id
                );
                $this->student_model->add($update_student);
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'image' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                $img_name = $insert_id . "father" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }
            if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                $img_name = $insert_id . "mother" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                $img_name = $insert_id . "guardian" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = $this->input->post('first_title');
                $img_name = $uploaddir . basename($_FILES['first_doc']['name']);
                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $first_title, 'doc' => basename($_FILES['first_doc']['name']));
                $this->student_model->adddoc($data_img);
            }
            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["second_doc"]["name"]);
                $second_title = $this->input->post('second_title');
                $img_name = $uploaddir . basename($_FILES['second_doc']['name']);
                move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $second_title, 'doc' => basename($_FILES['second_doc']['name']));
                $this->student_model->adddoc($data_img);
            }

            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["fourth_doc"]["name"]);
                $fourth_title = $this->input->post('fourth_title');
                $img_name = $uploaddir . basename($_FILES['fourth_doc']['name']);
                move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $fourth_title, 'doc' => basename($_FILES['fourth_doc']['name']));
                $this->student_model->adddoc($data_img);
            }
            if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["fifth_doc"]["name"]);
                $fifth_title = $this->input->post('fifth_title');
                $img_name = $uploaddir . basename($_FILES['fifth_doc']['name']);
                move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $insert_id, 'title' => $fifth_title, 'doc' => basename($_FILES['fifth_doc']['name']));
                $this->student_model->adddoc($data_img);
            }
            $sender_details = array('student_id' => $insert_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
            $this->mailsmsconf->mailsms('student_admission', $sender_details);
            $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
            $this->mailsmsconf->mailsms('login_credential', $student_login_detail);

            if ($sibling_id > 0) {

            } else {
                $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
            }


            $this->session->set_flashdata('msg', '<div class="alert alert-success">Student added Successfully</div>');
            redirect('student/create');
        }
    }

    function create_doc()
    {
        $student_id = $this->input->post('student_id');
        if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
            $uploaddir = './uploads/student_documents/' . $student_id . '/';
            if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                die("Error creating folder $uploaddir");
            }
            $fileInfo = pathinfo($_FILES["first_doc"]["name"]);
            $first_title = $this->input->post('first_title');
            $img_name = $uploaddir . basename($_FILES['first_doc']['name']);
            move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
            $data_img = array('student_id' => $student_id, 'title' => $first_title, 'doc' => basename($_FILES['first_doc']['name']));
            $this->student_model->adddoc($data_img);
        }
        redirect('student/view/' . $student_id);
    }

    function handle_upload()
    {
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
            if ($_FILES["file"]["size"] > 102400) {
                $this->form_validation->set_message('handle_upload', 'File size shoud be less than 100 kB');
                return false;
            }
            return true;
        } else {
            return true;
        }
    }

    function bulkUpdate()
    {

        $fields = array('admission_no', 'roll_no', 'firstname', 'lastname', 'dob', 'religion', 'birth_place', 'mobileno',
            'email', 'admission_date', 'blood_group', 'mother_tongue', 'address', 'father_name', 'father_phone', 'father_occupation',
            'mother_name', 'mother_phone', 'mother_occupation','guardian_id', 'guardian_name', 'guardian_relation', 'guardian_email',
            'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'previous_school', 'note', 'nationality', 'gender', 'city',
            'category_id', 'father_education', 'mother_education',
            'allergies', 'medicine');
        $data["fields"] = $fields;
        $this->load->view('layout/header');
        $this->load->view('student/bulkUpdate', $data);
        $this->load->view('layout/footer');
    }


    function bulkUploadPost()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }


        $fields = array('admission_no', 'roll_no', 'firstname', 'lastname', 'dob', 'religion', 'birth_place', 'mobileno',
            'email', 'admission_date', 'blood_group', 'mother_tongue', 'address', 'father_name', 'father_phone', 'father_occupation',
            'mother_name', 'mother_phone', 'mother_occupation','guardian_id', 'guardian_name', 'guardian_relation', 'guardian_email',
            'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'previous_school', 'note', 'nationality', 'gender', 'city',
            'category_id', 'father_education', 'mother_education',
            'allergies', 'medicine');

        $data["fields"] = $fields;
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_csv_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/bulkUpdate', $data);
            $this->load->view('layout/footer', $data);
        }

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');

                $result = $this->csvreader->parse_file($file);

                $StudentArray = array();

                if (!empty($result)) {
                    if (count($result[1]) == count($fields)){
                        for ($i = 1; $i <= count($result); $i++) {

                            $student_data[$i] = array();
                            $n = 0;

                            foreach ($result[$i] as $key => $value) {

                                $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                                $n++;

                            }

                            $StudentArray[] = $student_data[$i];

                        }

                    if (count($StudentArray) > 0) {

                        if ($this->student_model->updateBlukData($StudentArray)) {
                            $this->session->set_userdata('success', 'Students Updated Successfully');
                            $this->load->view('layout/header');
                            $this->load->view('student/bulkUpdate', $data);
                            $this->load->view('layout/footer');
                        } else {
                            $this->session->set_userdata('error', 'Updation Failed, Review your csv');
                            $this->load->view('layout/header');
                            $this->load->view('student/bulkUpdate', $data);
                            $this->load->view('layout/footer');
                        }

                    }
                } else {
                        $this->session->set_userdata('error', 'Headers error, Please follow given sample pattren');
                        $this->load->view('layout/header');
                        $this->load->view('student/bulkUpdate', $data);
                        $this->load->view('layout/footer');
                    }

                } else {
                    $this->session->set_userdata('error', 'No data was found');
                    $this->load->view('layout/header');
                    $this->load->view('student/bulkUpdate', $data);
                    $this->load->view('layout/footer');
                }
            }
        }
    }
    
    
    public function classSectionImport(){
        $fields = array('admission_no', 'class_name', 'section_name'); 
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');
                $result = $this->csvreader->parse_file($file);
                if (!empty($result)) {
                    $classes = $this->student_model->getClasses();
                    $sections = $this->student_model->getSections();
                    $student_data = array();
                    $recordCount = 0;
                    for ($i = 1; $i <= count($result); $i++) {
                        $student_data[$i] = array();
                        $n = 0;
                        foreach ($result[$i] as $key => $value) {

                            if($fields[$n] == 'admission_no') {

                                $studentId = $this->student_model->getStudentsIdByAdmissionNum($value);

                                if(count($studentId) > 0){
                                    $student_data[$i]['student_id'] = $studentId[0]->id;
                                }else{
                                    $student_data[$i]['student_id'] = -1;
                                }
                            } 

                            if($fields[$n] == 'class_name') {
                                $student_data[$i]['class_id'] = $this->getIdFromArray($classes, $value);
                            } 

                            if($fields[$n] == 'section_name'){
                                $student_data[$i]['section_id'] = $this->getIdFromArray($sections, $value);
                            } 
                            $n++;
                        } 
                    }

                    $updatedRecords = $this->studentsession_model->updateClasses($student_data); 
                    if($updatedRecords > 0){
                        $this->session->set_userdata('success', $updatedRecords. ' Records Updated Successfully!');
                    }else{
                        $this->session->set_userdata('error', 'No New Record Updated!');
                    }

                }
            }else{
                $this->session->set_userdata('error', 'Error Reading csv');
            }

        }else{
            $this->session->set_userdata('error', 'CSV Not found');
        }
return $this->bulkUpdate();
       // redirect('student/bulkUpdate');
}


    function getIdFromArray($array, $val){
        foreach ($array as $arr){

            if(strcasecmp($arr['name'], $val) == 0){
                return $arr['id'];
            }
        }
    }


    function import(){
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Import Student';
        $data['title_list'] = 'Recently Added Student';
        $session = $this->setting_model->getCurrentSession();
        $class = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();

        $category = $this->category_model->get();

        $fields = array('admission_no', 'roll_no', 'firstname', 'lastname', 'dob', 'religion', 'birth_place', 'mobileno',
            'email', 'admission_date', 'blood_group', 'mother_tongue', 'address', 'father_name', 'father_phone', 'father_occupation',
            'mother_name', 'mother_phone', 'mother_occupation','guardian_id', 'guardian_name', 'guardian_relation', 'guardian_email',
            'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'previous_school', 'note', 'nationality', 'gender', 'city',
            'category_id', 'father_education', 'mother_education',
            'allergies', 'medicine');

        $data["fields"] = $fields;
        $data['categorylist'] = $category;
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_csv_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/import', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $existingRecords = 0;
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $session = $this->setting_model->getCurrentSession();

            $this->db->trans_start();
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);

                    $StudentArray = array();
                    $studentSessionArray = array();
                    $studentLoginArray = array();
                    $parentLoginArray = array();
                    $updateArray = array();

                    if (!empty($result)) {
                        $rowcount = 0;
                        $previousInsertedId = $this->student_model->getLastId();
                        $previousInsertedIdCount = (int)$previousInsertedId;
                        for ($i = 1; $i <= count($result); $i++) {

                                $student_data[$i] = array();
                                $n = 0;
                                foreach ($result[$i] as $key => $value) {
                                  
                                           $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                                           $student_data[$i]['is_active'] = 'yes';
                                            $n++;
                                }
    
    
    
                                //if ($this->student_model->check_rollno_exists($student_data[$i]["admission_no"], 0, $class_id)) {
                                if ($this->student_model->check_rollno_exists($student_data[$i]["admission_no"], 0, null)) {
                                    $existingRecords = $existingRecords + 1;
                                   // $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Record already exists.</div>');
                                } else {
                                    $StudentArray[] = $student_data[$i];
                                    $previousInsertedIdCount++;
    
                                    $data['csvData'] = $result;
                                   // $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Students imported successfully</div>');
                                    $rowcount++;
                                   // $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');
                                }

                        }

                        if(count($StudentArray) > 0 && $rowcount > 0) {
                            $this->student_model->addBluckData($StudentArray);
                            $tempPreviousId = (int)($this->student_model->getfirstInsertedId($StudentArray[0]["admission_no"]));

                            $val = $tempPreviousId + count($StudentArray);
                            $admissionInc = 0;

                            for ($tempPreviousId; $tempPreviousId < $val; $tempPreviousId++) {

                                $data_new = array(
                                    'student_id' => $tempPreviousId,
                                    'class_id' => $class_id,
                                    'section_id' => $section_id,
                                    'session_id' => $session
                                );

                                $studentSessionArray[] = $data_new;


                                //Student Login details
                                $user_password =$StudentArray[$admissionInc]["admission_no"]."123";//$this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                $data_student_login = array(
                                    'username' => $StudentArray[$admissionInc]["admission_no"],
                                    'password' => $user_password,
                                    'user_id' => $tempPreviousId,
                                    'role' => 'student'
                                );
                                $studentLoginArray[] = $data_student_login;

                                //Student Parent Login details...
                                $parent_password =  $StudentArray[$admissionInc]["guardian_id"]."123";

                                $data_parent_login = array(
                                    'username' => $StudentArray[$admissionInc]["guardian_id"],
                                    'password' => $parent_password,
                                    'user_id' => $tempPreviousId,
                                    'role' => 'parent',
                                    'childs' => $tempPreviousId
                                );
                                if (trim($data_parent_login['username'] ) == '') {
                                    $data_parent_login['username'] = $this->parent_login_prefix . $data_parent_login['user_id'] . "_1";
                                }
                                $parentLoginArray[] = $data_parent_login;
                                
                                $admissionInc += 1;    


                            }


                            //Outside of loop Single query..
                            $this->student_model->add_student_session_bluk($studentSessionArray);
                            $this->user_model->add_bluk($studentLoginArray);

                            $parentId = ($this->user_model->getLastId());

                            // dd($parentLoginArray);
                            $this->user_model->add_bluk($parentLoginArray);


                            $tempParentId = (int)$parentId;

                            // dd($StudentArray,'StudentArray',0);
                            foreach ($StudentArray as $std) {
                                $tempParentId = $tempParentId + 1;
                                $update_student = array(
                                    'admission_no' => $std['admission_no'],
                                    'parent_id' => $tempParentId,
                                );
                                // dd($update_student,'update_student',0);

                                $this->student_model->add($update_student, true);

                            }
                            // dd('end');
                        }

                        if($existingRecords > 0) {
                            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">'.$existingRecords. 'Existing Records Found. '.$rowcount.' Imported Successfully  </div>');
                        }else{
                            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');
                        }



                    } else {

                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No Data was found.</div>');
                    }
                } else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please upload CSV file only.</div>');
                }
            }
            $this->db->trans_complete();
            redirect('student/import');
        }
    }

    function handle_csv_upload() {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes = array('text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt');
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', 'File type not allowed');
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', 'Extension not allowed');
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', 'Please Select file');
            return false;
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('student', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Student';
        $data['id'] = $id;
        $student = $this->student_model->get($id);
        $genderList = $this->customlib->getGender();
        $data['student'] = $student;
        $data['genderList'] = $genderList;
        $session = $this->setting_model->getCurrentSession();
        $vehroute_result = $this->vehroute_model->get();
        $data['vehroutelist'] = $vehroute_result;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $hostelList = $this->hostel_model->get();
        $data['hostelList'] = $hostelList;
        $houses = $this->student_model->gethouselist();
        $data['houses'] = $houses;
        $data["bloodgroup"] = $this->blood_group;
        $siblings = $this->student_model->getMySiblings($student['parent_id'], $student['id']);
        $data['siblings'] = $siblings;
        $data['siblings_counts'] = count($siblings);
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');

        $this->form_validation->set_rules('guardian_is', 'Guardian', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('rte', 'RTE', 'trim|required|xss_clean');
        $this->form_validation->set_rules('guardian_phone', 'Guardian Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules(
                'roll_no', 'Roll No.', array('trim',
            array('check_exists', array($this->student_model, 'valid_student_roll'))
                )
        );

        $this->form_validation->set_rules('file', 'Image', 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_id = $this->input->post('student_id');
            $student = $this->student_model->get($student_id);
            $sibling_id = $this->input->post('sibling_id');
            $siblings_counts = $this->input->post('siblings_counts');
            $siblings = $this->student_model->getMySiblings($student['parent_id'], $student_id);
            $total_siblings = count($siblings);


            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $hostel_room_id = $this->input->post('hostel_room_id');
            $fees_discount = $this->input->post('fees_discount');
            $vehroute_id = $this->input->post('vehroute_id');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }
            $data = array(
				'id' => $id,
                'name_arabic' => $this->input->post('name_arabic'),
                'nationality' => $this->input->post('nationality'),
                'birth_place' => $this->input->post('birth_place'),
                'mother_tongue' => $this->input->post('mother_tongue'),
                'iqama_number' => $this->input->post('iqama_number'),
                'passport_number' => $this->input->post('passport_number'),
                'iqama_expiry_date' => $this->input->post('iqama_expiry'),
                'passport_expiry_date' => $this->input->post('passport_expiry'),
                'passport_issued_from' => $this->input->post('passport_issued_from'),
                'father_iqama_number' => $this->input->post('father_iqama_number'),
                'father_passport_number' => $this->input->post('father_passport_number'),
                'father_iqama_expiry' => $this->input->post('father_iqama_expiry'),
                'father_passport_expiry' => $this->input->post('father_passport_expiry'),
                'father_passport_issued_from' => $this->input->post('father_passport_issued_from'),
                'father_education' => $this->input->post('father_education'),
                'mother_education' => $this->input->post('mother_education'),
                'insurance_number' => $this->input->post('insurance_number'),
                'insurance_company_name' => $this->input->post('insurance_company_name'),
                'insurance_policy_number' => $this->input->post('insurance_policy_number'),
                'insurance_contact_number' => $this->input->post('insurance_contact_number'),
                'allergies' => $this->input->post('allergies'),
                'medicine' => $this->input->post('medicine'),
                'address' => $this->input->post('address'),

                //'id' => $id,
                'admission_no' => $this->input->post('admission_no'),
                'roll_no' => $this->input->post('roll_no'),
                'admission_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('admission_date'))),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'mobileno' => $this->input->post('mobileno'),
                'email' => $this->input->post('email'),
                'state' => $this->input->post('state'),
                'city' => $this->input->post('city'),
                'previous_school' => $this->input->post('previous_school'),
                'guardian_is' => $this->input->post('guardian_is'),
                'pincode' => $this->input->post('pincode'),
                'religion' => $this->input->post('religion'),
                'dob' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),
                'current_address' => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'category_id' => $this->input->post('category_id'),
                'adhar_no' => $this->input->post('adhar_no'),
                'samagra_id' => $this->input->post('samagra_id'),
                'bank_account_no' => $this->input->post('bank_account_no'),
                'bank_name' => $this->input->post('bank_name'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'father_name' => $this->input->post('father_name'),
                'father_phone' => $this->input->post('father_phone'),
                'father_occupation' => $this->input->post('father_occupation'),
                'mother_name' => $this->input->post('mother_name'),
                'mother_phone' => $this->input->post('mother_phone'),
                'mother_occupation' => $this->input->post('mother_occupation'),
                'guardian_occupation' => $this->input->post('guardian_occupation'),
                'guardian_email' => $this->input->post('guardian_email'),
                'guardian_id' =>  $this->input->post('guardian_id'),
                'gender' => $this->input->post('gender'),
                'guardian_name' => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone' => $this->input->post('guardian_phone'),
                'guardian_address' => $this->input->post('guardian_address'),
                'vehroute_id' => $vehroute_id,
                'hostel_room_id' => $hostel_room_id,
                'school_house_id' => $this->input->post('house'),
                'blood_group' => $this->input->post('blood_group'),

                'note' => $this->input->post('note'),

            );
            $this->student_model->add($data);
            $data_new = array(
                'student_id' => $id,
                'class_id' => $class_id,
                'section_id' => $section_id,
                'session_id' => $session,
                'fees_discount' => $fees_discount
            );
            $insert_id = $this->student_model->add_student_session($data_new);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                $img_name = $id . "father" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'father_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }


            if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                $img_name = $id . "mother" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }


            if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                $img_name = $id . "guardian" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($siblings_counts) && ($total_siblings == $siblings_counts)) {
                //if there is no change in sibling
            } else if (!isset($siblings_counts) && $sibling_id == 0 && $total_siblings > 0) {
                // add for new parent
                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                $data_parent_login = array(
                    'username' => $this->parent_login_prefix . $student_id . "_1",
                    'password' => $parent_password,
                    'user_id' => "",
                    'role' => 'parent',
                );

                $update_student = array(
                    'id' => $student_id,
                    'parent_id' => 0,
                );
                $ins_id = $this->user_model->addNewParent($data_parent_login, $update_student);
            } else if ($sibling_id != 0) {
                //join to student with new parent
                $student_sibling = $this->student_model->get($sibling_id);
                $update_student = array(
                    'id' => $student_id,
                    'parent_id' => $student_sibling['parent_id'],
                );
                $student_sibling = $this->student_model->add($update_student);
            } else {
                
            }


            $this->session->set_flashdata('msg', '<div student="alert alert-success text-left">Student Record Updated successfully</div>');
            redirect('student/search');
        }
    }

    function search() {

        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/search');
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->get('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
           // $this->load->view('layout/header', $data);
           // $this->load->view('student/studentSearch', $data);
           // $this->load->view('layout/footer', $data);
   
            $class = $this->input->get('class_id');
			$_POST['class_id'] = $this->input->get('class_id');
			$_POST['section_id'] = $this->input->get('section_id');
            $section = $this->input->get('section_id');
            $search = $this->input->get('search');
            $search_text = $this->input->get('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
					$this->form_validation->set_data($this->input->get());
                    $this->form_validation->set_rules('class_id', 'class_id', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                      //echo "here333";  
                    } else {
                        $data['searchby'] = "filter";
						//echo "here";
                        $data['class_id'] = $this->input->get('class_id');
                        $data['section_id'] = $this->input->get('section_id');

                        $data['search_text'] = $this->input->get('search_text');
                      //  $resultlist = $this->student_model->searchByClassSection($class, $section);
                       // return print_r($resultlist[0]);
                      //  $data['resultlist'] = $resultlist;
						
						
						///////////kiran code start here/////
						
						$config = array();
				     $config['reuse_query_string'] = true;
					// $config['page_query_string'] = true;
					 $config['use_page_numbers'] = TRUE;
					$config["base_url"] = base_url() . "student/search";
					$config["total_rows"] = $this->student_model->searchByClassSectionTotalRecords($class, $section) ;
					$config ['uri_segment'] = 3;
					$config ['per_page'] = 50;
					$config ['num_links'] = 10;
$config['full_tag_open'] = '<nav aria-label="Page navigation example">
  <ul class="pagination pg-blue">';
    $config['full_tag_close'] = ' </ul>
</nav>';
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] =  '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
	
	
 
					$this->pagination->initialize($config);
 
					$page = ($this->uri->segment(3)) ? $this->uri->segment(3) :1;
					$offset =($page -1) * $config ['per_page'];
					$data["links"] = $this->pagination->create_links();


                    $resultlist = $this->student_model->searchByClassSection($class, $section,$config["per_page"],$offset);
					 $data['resultlist'] = $resultlist;
						///////////end here/////////////////
						
						
						
						
						
						
						
						
						
						
						
                        $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

					$config = array();
				     $config['reuse_query_string'] = true;
					// $config['page_query_string'] = true;
					 $config['use_page_numbers'] = TRUE;
					$config["base_url"] = base_url() . "student/search";
					$config["total_rows"] = $this->student_model->searchFullTextCount($search_text, $carray) ;
					$config ['uri_segment'] = 3;
					$config ['per_page'] = 50;
					$config ['num_links'] = 10;
					$config['full_tag_open'] = '<nav aria-label="Page navigation example">
						<ul class="pagination pg-blue">';
							$config['full_tag_close'] = ' </ul>
						</nav>';
					$config['first_link'] = false;
					$config['last_link'] = false;
					$config['first_tag_open'] = '<li class="page-item">';
					$config['first_tag_close'] = '</li>';
					$config['prev_link'] = 'Previous';
					$config['prev_tag_open'] = '<li class="page-item">';
					$config['prev_tag_close'] = '</li>';
					$config['next_link'] = 'Next';
					$config['next_tag_open'] = '<li class="page-item">';
					$config['next_tag_close'] = '</li>';
					$config['last_tag_open'] = '<li class="page-item">';
					$config['last_tag_close'] = '</li>';
					$config['cur_tag_open'] =  '<li class="active"><a href="#">';
					$config['cur_tag_close'] = '</a></li>';
					$config['num_tag_open'] = '<li class="page-item">';
					$config['num_tag_close'] = '</li>';
					$this->pagination->initialize($config);
 
					$page = ($this->uri->segment(3)) ? $this->uri->segment(3) :1;
					$offset =($page -1) * $config ['per_page'];
					$data["links"] = $this->pagination->create_links();




                    $data['search_text'] = trim($this->input->get('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text, $carray,$config["per_page"],$offset);
                    $data['resultlist'] = $resultlist;
                    $data['title'] = 'Search Details: ' . $data['search_text'];
                }
            }//end serach if
			//print_r($data);
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentSearch', $data);
            $this->load->view('layout/footer', $data);
         
    }
	}
    function getByClassAndSection() {
        $class = $this->input->get('class_id');
        $section = $this->input->get('section_id');
        $resultlist = $this->student_model->searchByClassSection($class, $section);
        echo json_encode($resultlist);
    }

    function getStudentRecordByID() {
        $student_id = $this->input->get('student_id');
        $resultlist = $this->student_model->get($student_id);
        echo json_encode($resultlist);
    }

    function uploadimage($id) {
        $data['title'] = 'Add Image';
        $data['id'] = $id;
        $this->load->view('layout/header', $data);
        $this->load->view('student/uploadimage', $data);
        $this->load->view('layout/footer', $data);
    }

    public function doupload($id) {
        $config = array(
            'upload_path' => "./uploads/student_images/",
            'allowed_types' => "gif|jpg|png|jpeg|df",
            'overwrite' => TRUE,
        );
        $config['file_name'] = $id . ".jpg";
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data = array('upload_data' => $this->upload->data());
            $upload_data = $this->upload->data();
            $data_record = array('id' => $id, 'image' => $upload_data['file_name']);
            $this->setting_model->add($data_record);

            $this->load->view('upload_success', $data);
        } else {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('file_view', $error);
        }
    }

    function getlogindetail() {
        if (!$this->rbac->hasPrivilege('student_parent_login_details', 'can_view')) {
            access_denied();
        }
        $student_id = $this->input->post('student_id');
        $examSchedule = $this->user_model->getStudentLoginDetails($student_id);
        echo json_encode($examSchedule);
    }

    function getUserLoginDetails() {
        $studentid = $this->input->post("student_id");
        $result = $this->user_model->getUserLoginDetails($studentid);
        echo json_encode($result);
    }

    function guardianreport() {
        if (!$this->rbac->hasPrivilege('guardian_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/guardianreport');
        $data['title'] = 'Student Guardian Report';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }
        
        $class_id = $this->input->get("class_id");
        $section_id = $this->input->get("section_id");
		$data['class_id'] = $class_id;
		$data['section_id'] = $section_id;
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');

		
						$config = array();
				     $config['reuse_query_string'] = true;
					 $config['use_page_numbers'] = TRUE;
					$config["base_url"] = base_url() . "student/guardianreport";

					$config ['uri_segment'] = 3;
					$config ['per_page'] = 25;
					$config ['num_links'] = 5;
					$config["total_rows"] = $this->student_model->searchGuardianDetails($class_id, $section_id,TRUE);
					$config['full_tag_open'] = '<nav aria-label="Page navigation example">
					  <ul class="pagination pg-blue">';
						$config['full_tag_close'] = ' </ul>
					</nav>';
						$config['first_link'] = false;
						$config['last_link'] = false;
						$config['first_tag_open'] = '<li class="page-item">';
						$config['first_tag_close'] = '</li>';
						$config['prev_link'] = 'Previous';
						$config['prev_tag_open'] = '<li class="page-item">';
						$config['prev_tag_close'] = '</li>';
						$config['next_link'] = 'Next';
						$config['next_tag_open'] = '<li class="page-item">';
						$config['next_tag_close'] = '</li>';
						$config['last_tag_open'] = '<li class="page-item">';
						$config['last_tag_close'] = '</li>';
						$config['cur_tag_open'] =  '<li class="active"><a href="#">';
						$config['cur_tag_close'] = '</a></li>';
						$config['num_tag_open'] = '<li class="page-item">';
						$config['num_tag_close'] = '</li>';
						$this->pagination->initialize($config);
 
					$page = ($this->uri->segment(3)) ? $this->uri->segment(3) :1;
						$offset =($page -1) * $config ['per_page'];
					//echo $config["per_page"]."-----".$offset;
	
        if ($this->form_validation->run() == FALSE) {

           // $resultlist = $this->student_model->studentGuardianDetails($carray);
 
					
        } else {
				// $resultlist = $this->student_model->searchGuardianDetails($class_id, $section_id,FALSE,$config["per_page"],$offset);
				 //	$config["total_rows"] = $this->student_model->searchGuardianDetails($class_id, $section_id,TRUE);
        }
		 
					 $resultlist = $this->student_model->searchGuardianDetails($class_id, $section_id,FALSE,$config["per_page"],$offset);
					
					//echo  "Total rows:".$config["total_rows"];
					$data["links"] = $this->pagination->create_links();

					
					 $data['resultlist'] = $resultlist;

        $this->load->view("layout/header", $data);
        $this->load->view("student/guardianReport", $data);
        $this->load->view("layout/footer", $data);
    }

    function disablestudentslist() {

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/disablestudentslist');
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $result = $this->student_model->getdisableStudent();
        $data["resultlist"] = $result;

        $userdata = $this->customlib->getUserData();
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
 
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');

                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->disablestudentByClassSection($class, $section);
                        $data['resultlist'] = $resultlist;
                        $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->disablestudentFullText($search_text);
                    $data['resultlist'] = $resultlist;
                    $data['title'] = 'Search Details: ' . $data['search_text'];
                }
            }
        }

        $this->load->view("layout/header", $data);
        $this->load->view("student/disablestudents", $data);
        $this->load->view("layout/footer", $data);
    }

    function disablestudent($id) {

        $data = array('is_active' => "no", 'disable_at' => date("Y-m-d"));
        $this->student_model->disableStudent($id, $data);
        redirect("student/view/" . $id);
    }

    function enablestudent($id) {

        $data = array('is_active' => "yes");
        $this->student_model->disableStudent($id, $data);
        redirect("student/view/" . $id);
    }

}

?>
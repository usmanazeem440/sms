<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Studentfee extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("setting_model");
        $this->load->model("feetype_model");
        $this->load->model("receipts_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/index');
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    function pdf() {
        $this->load->helper('pdf_helper');
    }



    function parentSearch(){
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Student Search By Parent';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->get('search');
        $search_text = "";
        $search = $this->input->get('search');
        $search_text = $this->input->get('search_text');
 
        $data['search_text'] = $search_text;
           if (isset($search)) {
                 $config = array();
                $config['reuse_query_string'] = true;
                // $config['page_query_string'] = true;
                $config['use_page_numbers'] = TRUE;
                $config["base_url"] = base_url() . "/studentfee/parentSearch"; 
                $config["total_rows"] = $this->student_model->searchStudentByParentName($search_text,null,true) ; 
                $config ['uri_segment'] = 3;
                $config ['per_page'] = 50;
                $config ['num_links'] = 10;
                $config['full_tag_open'] = '<nav aria-label="Page navigation example">
                <ul class="pagination pg-blue">';
                $config['full_tag_close'] = ' </ul>
                </nav>';
                $config['first_link'] = 'First';
                $config['last_link'] = 'Last';
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
                $config['cur_tag_open'] = '<li class="active"><a href="#">';
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li class="page-item">';
                $config['num_tag_close'] = '</li>';

                $this->pagination->initialize($config);
                 $page = ($this->uri->segment(3)) ? $this->uri->segment(3) :1; 
                $offset =($page -1) * $config['per_page'];
                $pagination = $this->pagination->create_links();
                $start= ($page - 1)  * $config['per_page']+1;
                $end = (($page - 1) == floor($config['total_rows']/ $config['per_page']))? $config['total_rows'] : (int)($page - 1) * $config['per_page'] + $config['per_page'];
                $data["links"] = "Showing  ".$start." - ".$end." of ".  $config["total_rows"]." total results".$pagination . '</p>';
                
                
                $resultlist = $this->student_model->searchStudentByParentName($search_text,null,false,$config["per_page"],$offset);
                $data['resultlist'] = $resultlist;

                $data['is_parent_search'] = 'true';
                if(count($resultlist) > 0){
                    $data['parent_name'] =$resultlist[0]['parent_id'];
                }


                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/studentfeeSearch', $data);
                $this->load->view('layout/footer', $data);
             }
        }
     









    function search() {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->get('search');
        $class = $this->input->get('class_id');
        $section = $this->input->get('section_id');
        $search = $this->input->get('search');
        $search_text = $this->input->get('search_text');
        $data['class_id'] = $class;
        $data['section_id'] = $section;
        $data['search_text'] = $search_text;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
          
          

            if (isset($search)) {
                
                $config = array();
                $config['reuse_query_string'] = true;
                // $config['page_query_string'] = true;
                $config['use_page_numbers'] = TRUE;
                $config["base_url"] = base_url() . "/studentfee/search"; 

                $config ['uri_segment'] = 3;
                $config ['per_page'] = 50;
                $config ['num_links'] = 10;
                $config['full_tag_open'] = '<nav aria-label="Page navigation example">
                <ul class="pagination pg-blue">';
                $config['full_tag_close'] = ' </ul>
                </nav>';
                $config['first_link'] = 'First';
                $config['last_link'] = 'Last';
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
                $config['cur_tag_open'] = '<li class="active"><a href="#">';
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li class="page-item">';
                $config['num_tag_close'] = '</li>';

               
                
                if ($search == 'search_filter') {
                     $this->form_validation->set_data($_GET);
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                    } else {
                        //echo "234324324";
                        $config["total_rows"] = $this->student_model->searchByClassSectionTotalRecords($class, $section);
                        $this->pagination->initialize($config);
                        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
                        $offset = ($page - 1) * $config['per_page'];
                        $resultlist = $this->student_model->searchByClassSection($class, $section, $config["per_page"], $offset);
                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $config["total_rows"] = $this->student_model->searchFullTextCount($search_text, null, true);
                    $this->pagination->initialize($config);
                    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
                    $offset = ($page - 1) * $config['per_page'];
                    $resultlist = $this->student_model->searchFullText($search_text, null, $config["per_page"], $offset);
                    $data['resultlist'] = $resultlist;
                }

                $pagination = $this->pagination->create_links();
                $start= ($page - 1)  * $config['per_page']+1;
                $end = (($page - 1) == floor($config['total_rows']/ $config['per_page']))? $config['total_rows'] : (int)($page - 1) * $config['per_page'] + $config['per_page'];
                $data["links"] = "Showing  ".$start." - ".$end." of ".  $config["total_rows"]." total results".$pagination . '</p>';
 
                 
                $data['is_parent_search'] = 'false';
                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/studentfeeSearch', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    function feesearch() {
        if (!$this->rbac->hasPrivilege('search_due_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/feesearch');
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feesessiongroup = $this->feesessiongroup_model->getFeesByGroup();

        $data['feesessiongrouplist'] = $feesessiongroup;
        $this->form_validation->set_rules('feegroup_id', 'Fee Group', 'trim|required|xss_clean');

        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student_due_fee'] = array();
            $feegroup_id = $this->input->post('feegroup_id');
            $feegroup = explode("-", $feegroup_id);
            $feegroup_id = $feegroup[0];
            $fee_groups_feetype_id = $feegroup[1];
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_due_fee = $this->studentfee_model->getDueStudentFees($feegroup_id, $fee_groups_feetype_id, $class_id, $section_id);
            if (!empty($student_due_fee)) {
                foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {
                    $amt_due = $student_due_fee_value['amount'];
                    $student_due_fee[$student_due_fee_key]['amount_discount'] = 0;
                    $student_due_fee[$student_due_fee_key]['amount_fine'] = 0;
                    $a = json_decode($student_due_fee_value['amount_detail']);
                    if (!empty($a)) {
                        $amount = 0;
                        $amount_discount = 0;
                        $amount_fine = 0;

                        foreach ($a as $a_key => $a_value) {
                            $amount = $amount + $a_value->amount;
                            $amount_discount = $amount_discount + $a_value->amount_discount;
                            $amount_fine = $amount_fine + $a_value->amount_fine;
                        }
                        if ($amt_due <= $amount) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {

                            $student_due_fee[$student_due_fee_key]['amount_detail'] = $amount;
                            $student_due_fee[$student_due_fee_key]['amount_discount'] = $amount_discount;
                            $student_due_fee[$student_due_fee_key]['amount_fine'] = $amount_fine;
                        }
                    }
                }
            }


            $data['student_due_fee'] = $student_due_fee;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function reportbyname() {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/reportbyname');
        $data['title'] = 'student fees';
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByName', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
            $this->form_validation->set_rules('student_id', 'Student', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/reportByName', $data);
                $this->load->view('layout/footer', $data);
            } else {
                $data['student_due_fee'] = array();
                $class_id = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $student_id = $this->input->post('student_id');
                $student = $this->student_model->get($student_id);
                $data['student'] = $student;
                $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
                $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
                $data['student_discount_fee'] = $student_discount_fee;
                $data['student_due_fee'] = $student_due_fee;
                // dd($student_due_fee);
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                $data['student_id'] = $student_id;
                $category = $this->category_model->get();
                $data['categorylist'] = $category;
                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/reportByName', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    function reportbyclass() {
        $data['title'] = 'student fees';
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_fees_array = array();
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_result = $this->student_model->searchByClassSection($class_id, $section_id);
            $data['student_due_fee'] = array();
            if (!empty($student_result)) {
                foreach ($student_result as $key => $student) {
                    $student_array = array();
                    $student_array['student_detail'] = $student;
                    $student_session_id = $student['student_session_id'];
                    $student_id = $student['id'];
                    $student_due_fee = $this->studentfee_model->getDueFeeBystudentSection($class_id, $section_id, $student_session_id);
                    $student_array['fee_detail'] = $student_due_fee;
                    $student_fees_array[$student['id']] = $student_array;
                }
            }

            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $data['student_fees_array'] = $student_fees_array;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function view($id) {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'studentfee List';
        $studentfee = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function deleteFee() {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
            access_denied();
        }
        $invoice_id = $this->input->post('main_invoice');
        $sub_invoice = $this->input->post('sub_invoice');
        if (!empty($invoice_id)) {
            $this->studentfee_model->remove($invoice_id, $sub_invoice);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    function deleteStudentDiscount() {

        $discount_id = $this->input->post('discount_id');
        if (!empty($discount_id)) {
            $data = array('id' => $discount_id, 'status' => 'assigned', 'payment_id' => "");
            $this->feediscount_model->updateStudentDiscount($data);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    function addfee($id) {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Student Detail';
        $student = $this->student_model->get($id);
        // dd($student);
        $data['student'] = $student;



        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
        // dd($student_due_fee);


        //Discount in percentages
        $student_discount_fee = $this->feediscount_model->getStudentIndiviualDiscounts($student['admission_no']);
       


        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $class_section = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"] = $class_section;
        $session = $this->setting_model->getCurrentSession();


        $studentlistbysection = $this->student_model->getStudentClassSection($student["class_id"],$session);
        $data["studentlistbysection"] = $studentlistbysection;

        $data["fee_tax"] = $this->setting_model->getFeeTax();
		$data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddfee', $data);
        $this->load->view('layout/footer', $data);
    }


    //Add Fee View By Parent

    function addFeeByParent($id) {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_add')) {
            access_denied();
        }

        //List of students
        $data['title'] = 'Student Details';
        $guardianId = $this->student_model->getGuardianId($id);

        $students = $this->student_model->getMySibling($guardianId[0]->guardian_id);
        $data['students'] = $students;
        $session = $this->setting_model->getCurrentSession();
        $student_due_fee= array();
        $student_discount_fee = array();
        $class_section = array();
        $studentlistbysection = array();



        foreach ($students as $student){


            $student_due_fee[] = $this->studentfeemaster_model->getStudentFees($student->student_session_id);
            $student_discount_fee[] = $this->feediscount_model->getStudentIndiviualDiscounts($student->admission_no);
            $class_section[] = $this->student_model->getClassSection($student->student_session_id);
            // $studentlistbysection[] = $this->student_model->getStudentClassSection($student->class_id ,$session);
        }

        $data["fee_tax"] = $this->setting_model->getFeeTax();
	$data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();


        $data['student_discount_fee_array'] = $student_discount_fee;
        $data['student_due_fee_array'] = $student_due_fee;
        $category = $this->category_model->get();
        $data['categorylist_array'] = $category;
        $data["class_section_array"] = $class_section;


        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddfeeByParent', $data);
        $this->load->view('layout/footer', $data);
    }

    //End Add Fee View By Parent

    function deleteTransportFee() {
        $id = $this->input->post('feeid');
        $this->studenttransportfee_model->remove($id);
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    function delete($id) {
        $data['title'] = 'studentfee List';
        $this->studentfee_model->remove($id);
        redirect('studentfee/index');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Add studentfee';
        $this->form_validation->set_rules('category', 'Category', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'category' => $this->input->post('category'),
            );
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">Employee added to ssuccessfully</div>');
            redirect('studentfee/index');
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit studentfees';
        $data['id'] = $id;
        $studentfee = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->form_validation->set_rules('category', 'category', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'category' => $this->input->post('category'),
            );
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">Employee updated successfully</div>');
            redirect('studentfee/index');
        }
    }


    function addByParentfee(){
        $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName();
        $studentIds = $this->input->post('student_ids');
        $student_fees_discount_id = $this->input->post('student_fees_discount_id');
        $amount_fine = $this->input->post('amount_fine');
        $amount_tax = $this->input->post('amount_tax');
        $parent_id = $this->input->post('parent_id'); 

        $student_fees_master_id = $this->input->post('student_fees_master_id');
        $fee_groups_feetype_ids = $this->input->post('fee_groups_feetype_id');

        $amount_discount = $this->input->post('amount_discount');

        $send_to = $this->input->post('guardian_phone');

        $amount = $this->input->post('amount');
        
        
        if(count($studentIds)>0){
           // $dataInvoices = [""];
            $dataReceipts = [
                "receipt_amount"=> array_sum($amount),
                "parent_id"=>$parent_id,
                "status"=>"active"
            ];
            $lastInsertId = $this->receipts_model->add_receipts($dataReceipts);
            $dataReceiptsNumber = [
                "receipt_number"=>$lastInsertId,
            ];
            $this->receipts_model->update_receipts_number($lastInsertId,$dataReceiptsNumber);
        }
        for ($i = 0; $i < count($studentIds); $i++) {
            $tempfee_groups_feetype_id = $student_fees_master_id[$i];
            $tempfee_groups_feetype_ids = $fee_groups_feetype_ids[$i];
            $remain_amount = $this->getStuFeetypeBalance($tempfee_groups_feetype_ids, $tempfee_groups_feetype_id);
            $remain_amount = json_decode($remain_amount)->balance;
            //if ($remain_amount >= floatval($amount[$i])) {
            if(floatval($amount[$i]) > 0){
                $json_array = array(
                    'amount' => floatval($amount[$i]),
                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    'amount_discount' => floatval($amount_discount[$i]),
                    'amount_fine' => $amount_fine[$i],
                    'tax'=> floatval($amount_tax[$i]),
                    'description' => $this->input->post('description') . $collected_by,
                    'payment_mode' => $this->input->post('payment_mode')
                );
                $data = array(
                    'student_fees_master_id' =>  $tempfee_groups_feetype_id ,
                    'fee_groups_feetype_id' => $tempfee_groups_feetype_ids,
                    'amount_detail' => $json_array
                );
                
                $inserted_id = $this->studentfeemaster_model->fee_deposit($data, $send_to, $student_fees_discount_id,$lastInsertId);
            }
            //}
        }


        $array = array('status' => 'success', 'error' => '');
        echo json_encode($array);

    }







    function addByParentfeeAndPrint(){

        $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName();
        $studentIds = $this->input->post('student_ids');
        $student_fees_discount_id = $this->input->post('student_fees_discount_id');
        $amount_fine = $this->input->post('amount_fine');
        $amount_tax = $this->input->post('amount_tax');
        $fee_session_group_ids = $this->input->post('student_fee_session_group_id');

        $student_fees_master_id = $this->input->post('student_fees_master_id');
        $fee_groups_feetype_ids = $this->input->post('fee_groups_feetype_id');

        $amount_discount = $this->input->post('amount_discount');

        $send_to = $this->input->post('guardian_phone');
        $parent_id = $this->input->post('parent_id'); 
        $amount = $this->input->post('amount');
        
        if(count($studentIds)>0){
           // $dataInvoices = [""];
            $dataReceipts = [
                "receipt_amount"=> array_sum($amount),
                "parent_id"=>$parent_id,
                "status"=>"active"
            ];
            $lastInsertId = $this->receipts_model->add_receipts($dataReceipts);
            $dataReceiptsNumber = [
                "receipt_number"=>$lastInsertId,
            ];
            $this->receipts_model->update_receipts_number($lastInsertId,$dataReceiptsNumber);
            

        }
        for ($i = 0; $i < count($studentIds); $i++) {
            $tempfee_groups_feetype_id = $student_fees_master_id[$i];
            $tempfee_groups_feetype_ids = $fee_groups_feetype_ids[$i];

            $remain_amount = $this->getStuFeetypeBalance($tempfee_groups_feetype_ids, $tempfee_groups_feetype_id);
            $remain_amount = json_decode($remain_amount)->balance;



            //if ($remain_amount >= floatval($amount[$i])) {

            if(floatval($amount[$i]) > 0){

                $json_array = array(
                    'amount' => floatval($amount[$i]),
                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    'amount_discount' => floatval($amount_discount[$i]),
                    'amount_fine' => $amount_fine[$i],
                    'tax'=> floatval($amount_tax[$i]),
                    'description' => $this->input->post('description') . $collected_by,
                    'payment_mode' => $this->input->post('payment_mode')
                );
                $data = array(
                    'student_fees_master_id' =>  $tempfee_groups_feetype_id ,
                    'fee_groups_feetype_id' => $tempfee_groups_feetype_ids,
                    'amount_detail' => $json_array
                );

                $inserted_id = $this->studentfeemaster_model->fee_deposit($data, $send_to, $student_fees_discount_id,$lastInsertId);

            }

        }


        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record = $this->input->post('data');
        $record_array = json_decode($record);
        $fees_array = array();


        for($i=0; $i < count($student_fees_master_id); $i++){
            $fee_groups_feetype_id = $fee_groups_feetype_ids[$i];
            $fee_master_id = $student_fees_master_id[$i];
            $fee_session_group_id = $fee_session_group_ids[$i];
            $feeList = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);

            $fees_array[] = $feeList;

            $feeDiscounts =  $this->feediscount_model->getStudentIndiviualDiscounts($fees_array[$i]->admission_no);

        }

        $data['feearray'] = $fees_array;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
	$data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        $data['student_discount_fee'] = $feeDiscounts;
        $data["receipt_number"] = $lastInsertId;
        $this->load->view("print/printFeesByGroupArray", $data);

        //$array = array('status' => 'success', 'error' => '');
        // echo json_encode($array);

    }

















    function addstudentfee() {

        $this->form_validation->set_rules('student_fees_master_id', 'Fee Master', 'required|trim|xss_clean');
        $this->form_validation->set_rules('fee_groups_feetype_id', 'Student', 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'required|trim|xss_clean|callback_check_deposit');
        $this->form_validation->set_rules('amount_discount', 'Discount', 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount_fine', 'Fine', 'required|trim|xss_clean');
        $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'amount' => form_error('amount'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
                'fee_groups_feetype_id' => form_error('fee_groups_feetype_id'),
                'amount_discount' => form_error('amount_discount'),
                'amount_fine' => form_error('amount_fine'),
                'payment_mode' => form_error('payment_mode'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName();
            $student_fees_discount_id = $this->input->post('student_fees_discount_id');
            $json_array = array(
                'amount' => floatval($this->input->post('amount')),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount_discount' => $this->input->post('amount_discount'),
                'amount_fine' => $this->input->post('amount_fine'),
                'description' => $this->input->post('description') . $collected_by,
                'payment_mode' => $this->input->post('payment_mode'),
                'tax' => $this->input->post('tax')
            );
            $data = array(
                'student_fees_master_id' => $this->input->post('student_fees_master_id'),
                'fee_groups_feetype_id' => $this->input->post('fee_groups_feetype_id'),
                'amount_detail' => $json_array
            );

            
           /* 
        */
        
        
            $send_to = $this->input->post('guardian_phone');
            $email = $this->input->post('guardian_email');

            $student_detail = $this->studentfeemaster_model->getStudentDetailByStudentSession($this->input->post('student_fees_master_id'));

            $parent_id = $student_detail->parent_id;
            if($this->input->post('student_fees_master_id')>0){
            $dataReceipts = [
                "receipt_amount"=> floatval($this->input->post('amount')),
                "parent_id"=>$parent_id,
                "status"=>"active"
            ];
            $lastInsertId = $this->receipts_model->add_receipts($dataReceipts);
            $dataReceiptsNumber = [
                "receipt_number"=>$lastInsertId,
            ];
            $this->receipts_model->update_receipts_number($lastInsertId,$dataReceiptsNumber);
            }
            
            $inserted_id = $this->studentfeemaster_model->fee_deposit($data, $send_to, $student_fees_discount_id,$lastInsertId);

            //$sender_details = array('invoice' => $inserted_id, 'contact_no' => $send_to, 'email' => $email,'notification_to'=>array('parent_app_key'=>$student_detail->parent_app_key));
            //$this->mailsmsconf->mailsms('fee_submission', $sender_details);

            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }

    function printFeesByName() {
        $data = array('payment' => "0");

        $record = $this->input->post('data');
        $invoice_id = $this->input->post('main_invoice');
        $sub_invoice_id = $this->input->post('sub_invoice');
        $student_session_id = $this->input->post('student_session_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $student = $this->studentsession_model->searchStudentsBySession($student_session_id);

        $fee_record = $this->studentfeemaster_model->getFeeByInvoice($invoice_id, $sub_invoice_id);
        $data['student'] = $student;
        $data['sub_invoice_id'] = $sub_invoice_id;
        $data['feeList'] = $fee_record;
        $this->load->view('print/printFeesByName', $data);
    }

    function printFeesByGroup() {
        $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
        $fee_master_id = $this->input->post('fee_master_id');
        $fee_session_group_id = $this->input->post('fee_session_group_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $feeList = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
        $data['feeList'] = $feeList;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data['student_discount_fee'] = $this->feediscount_model->getStudentIndiviualDiscounts($feeList->admission_no);
        $this->load->view('print/printFeesByGroup', $data);
    }

    function printFeesByGroupArray() {
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record = $this->input->post('data');
        $record_array = json_decode($record);
        $fees_array = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id = $value->fee_master_id;
            $fee_session_group_id = $value->fee_session_group_id;
            $feeList = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[] = $feeList;
        }
		
        $data['feearray'] = $fees_array;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
		$data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        $data['student_discount_fee'] = $this->feediscount_model->getStudentIndiviualDiscounts($fees_array[0]->admission_no);
        $this->load->view('print/printFeesByGroupArray', $data);
    }

    function searchpayment() {
        if (!$this->rbac->hasPrivilege('search_fees_payment', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/searchpayment');
        $data['title'] = 'Edit studentfees';


        $this->form_validation->set_rules('paymentid', 'Payment ID', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

        } else {
            $paymentid = $this->input->post('paymentid');
            $invoice = explode("/", $paymentid);

            if (array_key_exists(0, $invoice) && array_key_exists(1, $invoice)) {
                $invoice_id = $invoice[0];
                $sub_invoice_id = $invoice[1];
                $feeList = $this->studentfeemaster_model->getFeeByInvoice($invoice_id, $sub_invoice_id);
                $data['feeList'] = $feeList;
                $data['sub_invoice_id'] = $sub_invoice_id;
            } else {
                $data['feeList'] = array();
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/searchpayment', $data);
        $this->load->view('layout/footer', $data);
    }

    function addfeegroup() {
        $this->form_validation->set_rules('fee_session_groups', 'Fee Group', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_session_groups' => form_error('fee_session_groups'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $student_session_id = $this->input->post('student_session_id');
            $fee_session_groups = $this->input->post('fee_session_groups');
            $student_sesssion_array = isset($student_session_id) ? $student_session_id : array();
            $student_ids = $this->input->post('student_ids');
            $delete_student = array_diff($student_ids, $student_sesssion_array);

            $preserve_record = array();
            if (!empty($student_sesssion_array)) {
                foreach ($student_sesssion_array as $key => $value) {

                    $insert_array = array(
                        'student_session_id' => $value,
                        'fee_session_group_id' => $fee_session_groups,
                    );
                    $inserted_id = $this->studentfeemaster_model->add($insert_array);

                    $preserve_record[] = $inserted_id;
                }
            }
            if (!empty($delete_student)) {
                $this->studentfeemaster_model->delete($fee_session_groups, $delete_student);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    function geBalanceFee() {
        $this->form_validation->set_rules('fee_groups_feetype_id', 'fee_groups_feetype_id', 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_fees_master_id', 'student_fees_master_id', 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_session_id', 'student_session_id', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_groups_feetype_id' => form_error('fee_groups_feetype_id'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $data = array();
            $student_session_id = $this->input->post('student_session_id');
            $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
            $student_fees_master_id = $this->input->post('student_fees_master_id');
            $remain_amount = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
            $discount_not_applied = $this->getNotAppliedDiscount($student_session_id);
            $remain_amount = json_decode($remain_amount)->balance;
			
			$feeTypeData = $this->feetype_model->getTaxable($fee_groups_feetype_id);
            
			if($feeTypeData->is_taxable == 'YES')
				$feeTax = $this->setting_model->getFeeTax();
			else
				$feeTax["fee_tax"] = 0;
			
            $array = array('status' => 'success', 'error' => '', 'balance' => $remain_amount, 'discount_not_applied' => $discount_not_applied, "tax" =>$feeTax["fee_tax"]);



            echo json_encode($array);
        }
    }

    function getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id) {
        $data = array();
        $data['fee_groups_feetype_id'] = $fee_groups_feetype_id;
        $data['student_fees_master_id'] = $student_fees_master_id;

        $result = $this->studentfeemaster_model->studentDeposit($data);
        $amount_balance = 0;
        $amount = 0;
        $amount_fine = 0;
        $amount_discount = 0;
        $amount_tax = 0;
        $due_amt = $result->amount;
        if ($result->is_system) {
            $due_amt = $result->student_fees_master_amount;
        }
        $amount_detail = json_decode($result->amount_detail);

        if (is_object($amount_detail)) {

            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                $amount = $amount + $amount_detail_value->amount;
                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                $amount_fine = $amount_fine + $amount_detail_value->amount_fine;
                $amount_tax = $amount_tax + $amount_detail_value->tax;
            }
        }

        $amount_balance = $due_amt + $amount_tax;
        $array = array('status' => 'success', 'error' => '', 'balance' => $amount_balance);
        return json_encode($array);
    }

    function check_deposit($amount) {
        if ($this->input->post('amount') != "" && $this->input->post('amount_discount') != "") {
            if ($this->input->post('amount') < 0) {
                $this->form_validation->set_message('check_deposit', 'Deposit amount can not be less than zero');
                return FALSE;
            } else {
                $student_fees_master_id = $this->input->post('student_fees_master_id');
                $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
                $deposit_amount = $this->input->post('amount');
                $remain_amount = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
                $remain_amount = json_decode($remain_amount)->balance;
                /*if ($remain_amount < $deposit_amount) {
                    $this->form_validation->set_message('check_deposit', 'Deposit amount can not be grater than remaining'. $remain_amount. "  ". $deposit_amount);
                    return FALSE;
                } else {
                    return TRUE;
                }*/
                return true;
            }
            return TRUE;
        }
        return TRUE;
    }

    function getNotAppliedDiscount($student_session_id) {
        return $this->feediscount_model->getDiscountNotApplied($student_session_id);
    }

}

?>
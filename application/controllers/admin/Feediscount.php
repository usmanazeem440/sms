<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feediscount extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("class_model");
        $this->load->model("student_model");
        $this->load->library('encoding_lib');
    }

    function delete($id) {
        $data['title'] = 'feecategory List';
        $this->feediscount_model->remove($id);
        redirect('admin/feediscount/index');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;
        $this->form_validation->set_rules('code', 'Discount Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/feediscountList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'amount' => $this->input->post('amount')
            );
            $this->feediscount_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Fees Discount added succesfully</div>');
            redirect('admin/feediscount');
        }
    }



    function addIndiviualFee(){

        $discountArray = implode(', ', $this->input->post("fee_discount"));
       if($this->feediscount_model->insertStudentDiscount($discountArray, $this->input->post("admission_number")) > 0){
           $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Discount Assigned Successfully');
       }else{
           $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Error In Discount Assign');
       }


       $this->applyStudentDiscountView($this->input->post("student_id"));
    }


    function exportformat(){
        $this->load->helper('download');
        $filepath = "./backend/import/import_discount_format.csv";
        $data = file_get_contents($filepath);
        $name = 'import_discount_format.csv';

        force_download($name, $data);

    }



    function import(){
        $fields = array('admission_no', 'discounts');
        $data["fields"] = $fields;



        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');
                $result = $this->csvreader->parse_file($file);

                if (!empty($result)) {
                    $array = array();
                    $rowcount = 0;
                    for ($i = 1; $i <= count($result); $i++) {

                        $data[$i] = array();
                        $n = 0;
                        foreach ($result[$i] as $key => $value) {
                            if ($fields[$n] == "admission_no") {
                                $data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                            } else {
                                //get Ids of discounts


                                $disNames = explode(',', $this->encoding_lib->toUTF8($result[$i][$key]));

                                $ids = "";
                                foreach ($disNames as $dd) {
                                   // print_r($dd);
                                   // print_r($this->feediscount_model->getIdByName($dd));
                                    $ids .= $this->feediscount_model->getIdByName($dd)[0]["id"] . ",";
                                }

                                $ids = rtrim($ids, ',');
                                $data[$i][$fields[$n]] = $ids;

                            }

                            $n++;
                        }


                        $data_new = array(
                            'student_admission_no' => $data[$i]["admission_no"],
                            'discount_ids' => $data[$i]["discounts"]
                        );

                        $array [] = $data_new;
                        $rowcount++;

                    }


                    if($this->feediscount_model->addBulkImportDiscounts($array) > 0) {
                        $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');
                    } else {
                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No Data was found.</div>');
                    }
                }else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please upload CSV file only.</div>');
                }
            }
        }



        $this->bulkImportDiscounts();
    }




    function bulkImportDiscounts(){

        $fields = array('admission_no', 'discounts');
        $data["fields"] = $fields;


        $this->load->view('layout/header');
        $this->load->view('admin/feediscount/bulk_import', $data);
        $this->load->view('layout/footer');
    }




    function applyStudentDiscountView($id){
       $student = $this->student_model->get($id);

        $previousDiscountIds = $this->feediscount_model->getPreviousDiscountIds($student["admission_no"]);

        $data["student"] = $student;
        if(count($previousDiscountIds) > 0) {
            $data["previousDiscounts"] = explode(',', $previousDiscountIds[0]["discount_ids"]);
        }
        else{
            $data["previousDiscounts"] = array();
        }

        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;



        $this->load->view('layout/header', $data);
        $this->load->view('admin/feediscount/student_view', $data);
        $this->load->view('layout/footer', $data);

    }



    function applyIndiviualDiscount(){
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount/applyIndiviualDiscount');


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

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/assignIndiviuals', $data);
            $this->load->view('layout/footer', $data);
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
                        $resultlist = $this->student_model->searchByClassSection($class, $section);
                        // return print_r($resultlist[0]);
                        $data['resultlist'] = $resultlist;
                        $title = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text, $carray);
                    $data['resultlist'] = $resultlist;
                    $data['title'] = 'Search Details: ' . $data['search_text'];
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/assignIndiviuals', $data);
            $this->load->view('layout/footer', $data);
        }
    }





    function edit($id) {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;
        $data['title'] = 'Edit feecategory';
        $data['id'] = $id;

        $feediscount = $this->feediscount_model->get($id);
        $data['feediscount'] = $feediscount;
        $this->form_validation->set_rules('name', 'category', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/feediscountEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code')
            );
            $this->feediscount_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Fees Discount updated succesfully</div>');
            redirect('admin/feediscount/index');
        }
    }

    function assign($id) {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $data['id'] = $id;
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feediscount_result = $this->feediscount_model->get($id);
        $data['feediscountList'] = $feediscount_result;

        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $RTEstatusList = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender'] = $this->input->post('gender');
            $data['rte_status'] = $this->input->post('rte');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');

            $resultlist = $this->feediscount_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist'] = $resultlist;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feediscount/assign', $data);
        $this->load->view('layout/footer', $data);
    }

    function studentdiscount() {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $this->form_validation->set_rules('feediscount_id', 'Fee Discount', 'required|trim|xss_clean');
        // $this->form_validation->set_rules('student_session_id[]', 'Student', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'feediscount_id' => form_error('feediscount_id'),
                    // 'student_session_id[]' => form_error('student_session_id[]'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $student_list = $this->input->post('student_list');
            $feediscount_id = $this->input->post('feediscount_id');
            $student_sesssion_array = $this->input->post('student_session_id');
            if (!isset($student_sesssion_array)) {
                $student_sesssion_array = array();
            }
            $diff_aray = array_diff($student_list, $student_sesssion_array);
            $preserve_record = array();
            foreach ($student_sesssion_array as $key => $value) {

                $insert_array = array(
                    'student_session_id' => $value,
                    'fees_discount_id' => $feediscount_id,
                );
                $inserted_id = $this->feediscount_model->allotdiscount($insert_array);

                $preserve_record[] = $inserted_id;
            }
            if (!empty($diff_aray)) {
                $this->feediscount_model->deletedisstd($feediscount_id, $diff_aray);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    function applydiscount() {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('discount_payment_id', 'Fees Payment Id', 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_fees_discount_id', 'Fees Discount Id', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'amount' => form_error('amount'),
                'discount_payment_id' => form_error('discount_payment_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $data = array(
                'id' => $this->input->post('student_fees_discount_id'),
                'payment_id' => $this->input->post('discount_payment_id'),
                'description' => $this->input->post('dis_description'),
                'status' => 'applied'
            );

            $this->feediscount_model->updateStudentDiscount($data);
            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }

}

?>
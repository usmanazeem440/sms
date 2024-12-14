<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receipts extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("setting_model");
	$this->load->model("feetype_model");
        $this->load->model("receipts_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('receipts', 'can_view')) {
            access_denied();
        }
        $guardian_id = '';
        $this->session->set_userdata('top_menu', 'receipts');
        $this->session->set_userdata('sub_menu', 'admin/receipts/index');
        $data['title'] = 'student fees';


        if ($this->input->server('REQUEST_METHOD') == "GET")
         {
            $guardian_id = null;
        }
        else
        {
            $search_text = $this->input->post('search_text');
            if (!empty($search_text)) 
            {
                $guardian_id = $search_text;
            }
        
        }
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "/admin/receipts/index";
        $config["total_rows"] = $this->receipts_model->getReceiptsList(null,$guardian_id,true); 
        $config ['uri_segment'] = 4;
        $config ['per_page'] = 25;
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
         $page = ($this->uri->segment(4)) ? $this->uri->segment(4) :1; 
        $offset =($page -1) * $config['per_page'];
        $data["links"] = $this->pagination->create_links();

        $receipts = $this->receipts_model->getReceiptsList(null,$guardian_id,false,$config["per_page"],$offset);
      
        $data['receiptslist'] = $receipts;
        $data['guardian_id'] = $guardian_id;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/receipts/index', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function print_receipts($receipt_id_param = '') {
        if (!$this->rbac->hasPrivilege('receipts', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        if($receipt_id_param != '') {
            $receipt_id = $receipt_id_param;
        } else {
            $receipt_id =  $this->input->post('receipt_id');
        }
        $receiptsList = $this->receipts_model->get($receipt_id);
        $receiptsDetails = $this->receipts_model->getReceiptsDetails($receipt_id);
          //  echo "<pre>";
          // print_r($receiptsDetails);
          // die;
        $data['receiptslist'] = $receiptsList;
        $data['receiptsDetails'] = $receiptsDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/receipts/print_receipts", $data);
    }
    function print_receipts_set_ar(){
        $this->load->helper('lang');
        set_language(5);
    }
    function print_receipts_ar() {
        $this->load->helper('lang');
        if (!$this->rbac->hasPrivilege('receipts', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $receipt_id =  $this->input->post('receipt_id');
        $receiptsList = $this->receipts_model->get($receipt_id);
        $receiptsDetails = $this->receipts_model->getReceiptsDetails($receipt_id);
//            echo "<pre>";
//           print_r($receiptsDetails);
//           die;
        $data['receiptslist'] = $receiptsList;
        $data['receiptsDetails'] = $receiptsDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/receipts/print_receipts_ar", $data);
        set_language(4);
    }

    
    
    function pdf() {
        $this->load->helper('pdf_helper');
    }


    function print_student_paid() {
        if (!$this->rbac->hasPrivilege('receipts', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $receipt_id =  $this->input->post('receipt_id');
        $receiptsList = $this->receipts_model->get($receipt_id);
        $receiptsDetails = $this->receipts_model->getStudentFeeDetails($receipt_id);
//            echo "<pre>";
//           print_r($receiptsDetails);
//           die;
        $data['receiptslist'] = $receiptsList;
        $data['receiptsDetails'] = $receiptsDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/receipts/print_receipts", $data);
    }
}

?>
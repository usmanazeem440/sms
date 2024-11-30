<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("setting_model");
	$this->load->model("feetype_model");
        $this->load->model("invoices_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('invoices', 'can_view')) {
            access_denied();
        }
        $guardian_id = '';
        $this->session->set_userdata('top_menu', 'invoices');
        $this->session->set_userdata('sub_menu', 'admin/invoices/index');
        $data['title'] = 'Invoices';


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
        $config["base_url"] = base_url() . "/admin/invoices/index";
        $config["total_rows"] = $this->invoices_model->getInvoicesList(null,$guardian_id,true); 
        $config ['uri_segment'] = 4;
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
         $page = ($this->uri->segment(4)) ? $this->uri->segment(4) :1; 
        $offset =($page -1) * $config['per_page'];
        $data["links"] = $this->pagination->create_links();

        $invoices = $this->invoices_model->getInvoicesList(null,$guardian_id,false,$config["per_page"],$offset);
      
        $data['invoiceslist'] = $invoices;
        $data['guardian_id'] = $guardian_id;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/invoices/index', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function print_invoices() {
        if (!$this->rbac->hasPrivilege('invoices', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoice_id =  $this->input->post('invoice_id');
        $invoicesList = $this->invoices_model->get($invoice_id);
        $invoicesDetails = $this->invoices_model->getInvoiceDetails($invoice_id);
//            echo "<pre>";
//           print_r($invoicesDetails);
//           die;
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/invoices/print_invoices", $data);
    }
    function print_invoices_set_ar(){
        $this->load->helper('lang');
        set_language(5);
    }
    function print_invoices_ar() {
        $this->load->helper('lang');
        if (!$this->rbac->hasPrivilege('receipts', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoice_id =  $this->input->post('invoice_id');
        $invoicesList = $this->invoices_model->get($invoice_id);
        $invoicesDetails = $this->invoices_model->getInvoiceDetails($invoice_id);
//            echo "<pre>";
//           print_r($invoicesDetails);
//           die;
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/invoices/print_invoices_ar", $data);
        set_language(4);
    }

    
    
    function pdf() {
        $this->load->helper('pdf_helper');
    }

    function print_student_invoices() {
        if (!$this->rbac->hasPrivilege('invoices', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoices_id =  $this->input->post('invoices_id');
        $invoicesList = $this->invoices_model->get($invoices_id);
        $invoicesDetails = $this->invoices_model->getStudentFeeDetails($invoices_id);
            echo "<pre>";
           print_r($receiptsDetails);
           die;
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/invoices/print_invoices", $data);
    }

    public function test($invoice_id) {
        $res = $this->invoices_model->getStudentFeeDetails($invoice_id); 
        dd($res);
    }

}

?>
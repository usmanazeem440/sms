<?php

class Salesreport extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('store_orders');
        $this->load->library('session');
        $this->load->library('encoding_lib');
        $this->load->model('setting_model');
    }


    function dailyReport(){

        $this->session->set_userdata('top_menu', 'sales_reports');
        $this->session->set_userdata('sub_menu', 'admin/Salesreport/dailyReport');
        $user =  $this->customlib->getUserData()['name'];
        $data["orders"] = $this->store_orders->getTodaysReport($user);
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['title'] = "Daily Reports";
        $this->load->view('layout/header');
        $this->load->view('sales-report/daily_report', $data);
        $this->load->view('layout/footer');

    }


    function weeklyReport(){
        $this->session->set_userdata('top_menu', 'sales_reports');
        $this->session->set_userdata('sub_menu', 'admin/Salesreport/weeklyReport');

        $user =  $this->customlib->getUserData()['name'];
        $data["orders"] = $this->store_orders->getWeeklyReport($user);
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['title'] = "Weekly Reports";
        $this->load->view('layout/header');
        $this->load->view('sales-report/daily_report', $data);
        $this->load->view('layout/footer');
    }

    function allReports(){
        $this->session->set_userdata('top_menu', 'sales_reports');
        $this->session->set_userdata('sub_menu', 'admin/Salesreport/allReports');
        $user =  $this->customlib->getUserData()['name'];
        $data["orders"] = $this->store_orders->getAllReports($user);
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['title'] = "Order Reports";
        $this->load->view('layout/header');
        $this->load->view('sales-report/daily_report', $data);
        $this->load->view('layout/footer');
    }

    function allDailyReport(){
        $this->session->set_userdata('top_menu', 'sales_reports');
        $this->session->set_userdata('sub_menu', 'admin/Salesreport/allDailyReport');

        $user =  $this->customlib->getUserData()['name'];
        $data["orders"] = $this->store_orders->getAllTodaysReport($user);
        //echo "<pre>";  print_r(  $data["orders"]);exit;

        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['title'] = "Daily Reports";
        $this->load->view('layout/header');
        $this->load->view('sales-report/admin_report', $data);
        $this->load->view('layout/footer');
    }


    function allWeeklyReport(){
        $this->session->set_userdata('top_menu', 'sales_reports');
        $this->session->set_userdata('sub_menu', 'admin/Salesreport/allWeeklyReport');
        
        $user =  $this->customlib->getUserData()['name'];
     
        $data["orders"] = $this->store_orders->getAllWeeklyReport($user);
        //echo "<pre>";  print_r(  $data["orders"]);exit;

        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['title'] = "Weekly Reports";
        $data['start_date']= $this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $this->load->view('layout/header');
        $this->load->view('sales-report/admin_report', $data);
        //$this->load->view('sales-report/test', $data);
        $this->load->view('layout/footer');
    }

    function allCompleteReports(){
        $this->session->set_userdata('top_menu', 'sales_reports');
        $this->session->set_userdata('sub_menu', 'admin/Salesreport/allCompleteReports');

        $user =  $this->customlib->getUserData()['name'];
        $data["orders"] = $this->store_orders->getAllCompleteReports($user);
         //echo "<pre>";  print_r(  $data["orders"]);exit;

        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['title'] = "Order Reports";
        $data['start_date']= $this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $this->load->view('layout/header');
        $this->load->view('sales-report/admin_report', $data);
        $this->load->view('layout/footer');
    }

    function allCompleteReportses(){
        $user =  $this->customlib->getUserData()['name'];
        $data["orders"] = $this->store_orders->getAllCompleteReportses($user);
         //echo "<pre>";  print_r(  $data["orders"]);exit;

        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['title'] = "Order Reports";
        $data['start_date']= $this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $this->load->view('layout/header');
        $this->load->view('sales-report/admin_report', $data);
        $this->load->view('layout/footer');
    }

}

?>
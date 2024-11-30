<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Order extends Parent_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('store_orders');
        $this->load->library('session');
    }


 public function index(){
  
   $data["orders"] = $this->store_orders->getOrderList($this->session->userdata["student"]["id"]);

     $this->load->view('layout/parent/header');
   $this->load->view('user/bookstore/receipt', $data);
     $this->load->view('layout/parent/footer');

 }

 public function view($id){
    $data["orders"] = $this->store_orders->getDetails($id);


     $this->load->view('layout/parent/header');
    $this->load->view('user/bookstore/receipt_detail', $data);
     $this->load->view('layout/parent/footer');


 }


}
?>
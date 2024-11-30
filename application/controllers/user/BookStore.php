<?php

if (!defined('BASEPATH'))

exit('No direct script access allowed');



class BookStore extends Parent_Controller {



    function __construct() {

        parent::__construct();

        $this->load->model('book_store');

        $this->load->model('store_orders');

        $this->load->library('session');

    }









 public function view_stock(){

  

   $data['books'] = $this->book_store->get_stock();



   $this->load->view('layout/parent/header');

   $this->load->view('user/bookstore/view-stock', $data);

   $this->load->view('layout/parent/footer');



 }



 public function pending_orders(){



     $this->load->view('layout/parent/header');

    $this->load->view('book-store/pending-orders');

     $this->load->view('layout/parent/footer');

 }



 public function completed_orders(){



     $this->load->view('layout/parent/header');

    $this->load->view('book-store/completed-orders');

     $this->load->view('layout/parent/footer');

 }



 public function orderItems(){

    $data['books'] = $this->book_store->get_stock_by_ids($this->input->post('checkedBooks'));



     $this->load->view('layout/parent/header');

   $this->load->view('user/bookstore/order-stock', $data);

     $this->load->view('layout/parent/footer');

 }





 public function placeOrder(){

     $booksId = $this->input->post('id');

     $quantity = $this->input->post('book_quantity');

     $studentId = $this->input->post('studetn_id');

     $price =  $this->input->post('price');

     $user_id = $this->session->userdata["student"]["id"];





    if($this->store_orders->insert( $user_id, $booksId, $quantity, $studentId, $price) > 0){

        $this->session->set_userdata('success', 'Order Placed Successfully!');

    }else{

        $this->session->set_userdata('error', 'Error in Placing Order!');

    }

    return $this->view_stock();

 }





}

?>
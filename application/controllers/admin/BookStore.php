<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class BookStore extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('book_store');
        $this->load->model('store_orders');
        $this->load->model('student_model');
        $this->load->library('session');
        $this->load->library('encoding_lib');
        $this->load->model('setting_model');
        $this->load->model('class_model');
         $this->load->model('studentsession_model');
    }

    public function add_book(){

         $this->session->set_userdata('top_menu', 'Inventory');
         $this->session->set_userdata('sub_menu', 'admin/BookStore/add_book');

         $data['classes'] = $this->class_model->getClassesTag();

        
        $this->load->view('layout/header');
        $this->load->view('book-store/add-book', $data);
        $this->load->view('layout/footer');
    }

    public function post_book(){

        $id = $this->input->post('id');
        $check = $this->book_store->check_book($id);

        $classes = $this->input->post('classs');

        $tags = implode(', ', $classes);


        $data = array(
            'book_id' => $this->input->post('id'),
            'title' => $this->input->post('title'),
            'brand' => $this->input->post('brand'),
            'price' => $this->input->post('price'),
            'author' => $this->input->post('author'),
            'quantity' => $this->input->post('quantity'),
            'class' => $tags
        );

        if($this->book_store->add_book($data) > 0){
            $this->session->set_userdata('book_success', 'Book Added Successfully!');
        }else{
            $this->session->set_userdata('book_error', 'Book Already Exists!');
        }
        return $this->add_book();

    }

    public function update_stock(){

        $this->load->view('layout/header');
        $this->load->view('book-store/update-stock');
        $this->load->view('layout/footer');
    }

    public function view_stock(){
        
          $this->session->set_userdata('top_menu', 'Inventory');
          $this->session->set_userdata('sub_menu', 'admin/BookStore/view_stock');

        $data['books'] = $this->book_store->get_stocks();
        //echo "<pre>";  print_r($data['books']);exit;
        $this->load->view('layout/header');
        $this->load->view('book-store/view-stock',$data);
        $this->load->view('layout/footer');
    }


    public function place_orders(){
        
        $this->session->set_userdata('top_menu', 'store_orders');
        $this->session->set_userdata('sub_menu', 'admin/Store/order');
        
        $data['search_text'] = '';

        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-view', $data);
        $this->load->view('layout/footer');
    }



    public function searchParent(){
        $parentName = $this->input->post('parent_name');
        $data['search_text'] = $parentName;
     //   echo $parentName;
        $data['students'] = $this->student_model->searchStudent($parentName);
     // echo "<pre>";  print_r( $data['students']);exit;
        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-view', $data);
        $this->load->view('layout/footer');

    }


    public function orderItems(){
        $data['books'] = $this->book_store->get_stock_by_ids($this->input->post('checkedBooks'));
        $data["parent_id"] = $this->input->post('parent_id');
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/order-stock', $data);
        $this->load->view('layout/footer');
    }

    public function orderItemss(){
        $data['books'] = $this->book_store->get_stock_by_ids($this->input->post('checkedBooks'));
        //echo "<pre>";  print_r($data['books']);exit;
        $data["id"] = $this->input->post('id');
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/order-stock', $data);
        $this->load->view('layout/footer');
    }


    public function placeOrder(){
        $booksId = $this->input->post('id');
        $quantity = $this->input->post('book_quantity');
        $studentId = $this->input->post('studetn_id');
        $price =  $this->input->post('price');
        $user_id = $this->customlib->getUserData()['name'];


        if($this->store_orders->insert($user_id, $booksId, $quantity, $studentId, $price) > 0){
            $this->session->set_userdata('success', 'Order Placed Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in Placing Order!');
        }
        return redirect(site_url('admin/BookStore/placeOrderByStudent').'/'.$studentId);
    }


    public function editOrder($orderId){
        $data["orders"] = $this->store_orders->getDetails($orderId);
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/edit_order', $data);
        $this->load->view('layout/footer');
    }



    public function updateOrder(){
        $orderId = $this->input->post('order_id');
        $book_id = $this->input->post('books_id');
        $prevOrderQty = $this->input->post('prev_order_quantity');
        $newOrderQty = $this->input->post('new_order_qty');
        $prevOrdersold = $this->input->post('prev_sold_qty');
        $newOrdersold = $this->input->post('new_qty_sold');


        if($this->store_orders->update($orderId, $book_id, $prevOrderQty, $newOrderQty, $prevOrdersold, $newOrdersold) > 0){
            $this->session->set_userdata('success', 'Order updated Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in updating Order!');
        }

        return $this->pending_orders();
    }



    public function placeOrderByParent($parent_id){
        $data["previous_orders"] = $this->store_orders->getOrderList($parent_id);


        $data["stock"] = $this->book_store->get_stock();

      
        $data["parent_id"] = $parent_id;
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data["classes"] = $this->class_model->getClassesTag();
          //$data["classes"]=$this->studentsession_model->getStudentClass();
        //$data["classes"] = $this->book_store->getClasses();
        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-by-parent', $data);
        $this->load->view('layout/footer');

    }



   public function placeOrderByStudent($id){
        
        $data["previous_orders"] = $this->store_orders->getOrderList($id);
        $data["stock"] = $this->book_store->get_stock($id);
        // dd($data['stock']);
        //echo "<pre>";  print_r($data["stock"]);exit;


        $data["id"] = $id;
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data["classes"] = $this->class_model->getClassesTag();
        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-by-parent', $data);
        $this->load->view('layout/footer');


    }





    public function cancelled_orders(){
        $orders = $this->store_orders->getCancelledOrders();
        // return var_dump($this->store_orders->getPendingOrders());

        $cancelledOrders = array();
        foreach ($orders as $order){
            if($order['max_status'] == '-1' && $order['min_status'] == '-1'){
                $cancelledOrders[] = $order;
            }
        }
          $data['orders'] = $cancelledOrders;
        $this->load->view('layout/header');
        $this->load->view('book-store/cancelled-orders', $data);
        $this->load->view('layout/footer');
    }

   public function pending_orders(){

        $orders = $this->store_orders->getPendingOrders();
        // var_dump($orders);die();
        $pendingOrders = array();
        foreach ($orders as $order){
            if($order['max_status'] == '0' && $order['min_status'] == '0'){
                $pendingOrders[] = $order;
            }
        }
        
      

        $data['orders'] = $pendingOrders;


        $this->load->view('layout/header');
        $this->load->view('book-store/pending-orders', $data);
        $this->load->view('layout/footer');
    }


    public function partially_completed_orders(){

        $orders = $this->store_orders->getPartiallyCompletedOrders();
//print_r($orders); die();

       //  var_dump($orders);die();
        $partiallyOrders = array();
        foreach ($orders as $order){
            if($order['max_status'] == '2' || $order['min_status'] == '2'){
                $partiallyOrders[] = $order;
            }elseif($order['max_status'] != $order['min_status']){
                $partiallyOrders[] = $order;
            }
        }

        $data['orders'] = $partiallyOrders;

        $this->load->view('layout/header');
        $this->load->view('book-store/partially_completed_orders', $data);
        $this->load->view('layout/footer');
    }




    public function completed_orders(){

        $orders = $this->store_orders->getCompleteOrders();
     
     //print_r($orders); die();

        // var_dump($orders); die();
        $completedOrders = array();
        foreach ($orders as $order){
            if($order['max_status'] == '1' && $order['min_status'] == '1'){
                $completedOrders[] = $order;
            }
        }

        $data['orders'] = $completedOrders;
        $this->load->view('layout/header');
        $this->load->view('book-store/completed-orders', $data);
        $this->load->view('layout/footer');
    }

    public function edit_book($id){
        $data['book'] = $this->book_store->edit_book($id);
        $data['classes'] = $this->class_model->getClassesTag();
        $this->load->view('layout/header');
        $this->load->view('book-store/edit_book', $data);
        $this->load->view('layout/footer');
    }


    public function update_book(){

        $classes = $this->input->post('classs');

        $tags = implode(', ', $classes);


        $data = array(
            'id' => $this->input->post('id'),
            'title' => $this->input->post('title'),
            'brand' => $this->input->post('brand'),
            'price' => $this->input->post('price'),
            'author' => $this->input->post('author'),
            'quantity' => $this->input->post('quantity'),
            'class' => $tags
        );

        if($this->book_store->update_book($data)){
            $this->load->library('session');
            $this->session->set_userdata('book_success', 'Book Updated Successfully!');
            return $this->view_stock();
        }else{
            $this->load->library('session');
            $this->session->set_userdata('book_error', 'Error in book updation!');
            return $this->edit_book($data['id']);
        }

    }


    public function disable($id){

        $data = array(
            'id' => $id,
            'is_disabled' => 1
        );
        if($this->book_store->disable_book($data)){
            $this->session->set_userdata('book_success', 'Book Deleted Successfully!');
            return $this->view_stock();
        }else{
            $this->session->set_userdata('book_error', 'Error in Book Deletion!');
            return $this->view_stock();
        }
    }


    public function viewCompleteOrder($orderId){
        $data["orders"] = $this->store_orders->getDetails($orderId);
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/complete_order_detail', $data);
        $this->load->view('layout/footer');
    }



    public function viewOrder($orderId){
        $data["orders"] = $this->store_orders->getDetails($orderId);
   //var_dump($data["orders"] ); die();

        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/order_detail', $data);
        $this->load->view('layout/footer');
    }

    public function cancelOrder($orderId){
        if($this->store_orders->cancelOrder($orderId)){
            $this->session->set_userdata('success', 'Order Cancelled Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in Order Cancellation!');
        }
        //;

        redirect_back();
    }



    public function import(){

        $fields = array('bluk_class', 'bluk_author', 'bluk_ISBN', 'bluk_title', 'bluk_price', 'bluk_quantity', 'bluk_brand');
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
                            $data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                            $n++;
                        }



                        $isbn = $data[$i]["bluk_ISBN"];


                        // if ($this->book_store->check_book_exists($isbn)) {
                        //     $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Record already exists.</div>');
                        //   } else {

                        $data_new = array(
                            'book_id' =>  $data[$i]["bluk_ISBN"],
                            'title' =>  $data[$i]["bluk_title"],
                            'author' =>  $data[$i]["bluk_author"],
                            'quantity' =>  $data[$i]["bluk_quantity"],
                            'brand' => $data[$i]['bluk_brand'],
                            'price' => $data[$i]["bluk_price"],
                            'class' => $data[$i]["bluk_class"]
                        );

                        $array []=  $data_new;
                        $rowcount++;

                    }

                    if($this->book_store->add_book_stock($array) > 0){
                        $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');
                    }

                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No Data was found.</div>');
                }
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please upload CSV file only.</div>');
            }
        }



        $this->load->view('layout/header');
        $this->load->view('book-store/import', $data);
        $this->load->view('layout/footer');
    }


    public function exportformat(){
        $this->load->helper('download');
        $filepath = "./backend/import/import_book_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'import_book_sample_file.csv';

        force_download($name, $data);
    }



    public function printReceipt($orderId){
        $orders = $this->store_orders->getReceiptDetails($orderId);

           //echo "<pre>";  print_r( $orders);exit;
        
        $data["parent_details"] = $this->student_model->getStudentDetail($orders[0]["std_id"]);
		
        $data["orders"] = $orders;
        //echo "<pre>";  print_r($data["parent_details"] );exit;
        $data["sales_tax"] = $this->setting_model->getSalesTax();

        $this->load->view('layout/header');
        $this->load->view('book-store/receipt_view', $data);
        $this->load->view('layout/footer');
    }



    public function completeOrder(){
        if(($this->store_orders->markOrderComplete($this->input->post('order_id'), $this->input->post('sold_book_qty'), $this->input->post('books_id'), $this->input->post('ordered_quantity'), $this->input->post('taken_quantity'))> 0)){
            $order = $this->store_orders->getDetails($this->input->post('order_id'));
            $this->book_store->updateStockOnOrderCompltion($order);

            $this->session->set_userdata('success', 'Order Completed Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in order completion!');
        }
        return redirect(site_url('admin/BookStore/printReceipt').'/'.$this->input->post('order_id'));
    }

}
?>
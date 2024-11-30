<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Store_orders extends CI_Model {
    
    function insert($user_id, $ids, $qty, $studentId, $price){

        $this->db->select_max('order_id');
        $orderId = $this->db->get('book_store_orders')->row(); 
        if($orderId->order_id == NULL){
            $orderId = 1;
        }else{
            $orderId = (int)($orderId->order_id) + 1;
        }
        $data = array();
        for($i=0; $i< count($ids); $i++){
            $array = array();
            $array['order_placed_by'] = $user_id;
            $array["std_id"] = $studentId;
            $array["book_id"] = $ids[$i];
            $array["quantity"] = $qty[$i];
            $array["order_id"] = $orderId;
            $array["price"] = floatval( $qty[$i]) * floatval($price[$i]);
            array_push($data, $array);
        }
        $this->db->insert_batch('book_store_orders', $data);   
        return $this->db->affected_rows();

    }



    function update($orderId, $book_id, $prevOrderQty, $newOrderQty, $prevOrdersold, $newOrdersold){

        $rowsEffected = 0;
        for($i = 0; $i< count($book_id); $i++){

            if($newOrderQty[$i] == 0){
                $this->db->where('order_id', $orderId);
                $this->db->where('book_id', $book_id[$i]);
                $this->db->delete('book_store_orders');
            }else {

                $this->db->set('quantity', $newOrderQty[$i], FALSE);
                $this->db->set('sold_quantity', $newOrdersold[$i], FALSE);
                if($newOrdersold[$i] == $newOrderQty[$i]){
                    $this->db->set('status', "1", FALSE);
                }elseif ($newOrdersold[$i] > 0){
                    $this->db->set('status', "2", FALSE);
                }else{
                    $this->db->set('status', "0", FALSE);
                }


                $this->db->where('order_id', $orderId);
                $this->db->where('book_id', $book_id[$i]);
                $this->db->update('book_store_orders');
            }
            $rowsEffected += $this->db->affected_rows();
        }
        if($rowsEffected > 0){
            return true;
        }else{
            return false;
        }


    }


    // function getOrderList($studentId){
       
    //     $this->db->select('id, status, std_id, order_placed_by, MAX(status), order_id, sum(quantity) as qty , sum(price) as price');
    //     $this->db->where('std_id', $studentId);
    //     $this->db->group_by('order_id');
    //     $query = $this->db->get('book_store_orders');
    //     return $query->result_array(); 
    // }


    //     function getOrderList($parent_id){
       
    //     $this->db->select('book_store_orders.id, status, std_id,students.firstname, order_placed_by, MAX(status), order_id, sum(quantity) as qty , sum(price) as price');
    //     $this->db->join('students', 'students.id = book_store_orders.std_id');
        
    //     $this->db->where('std_id', $parent_id);
    //     $this->db->group_by('order_id');
    //     $query = $this->db->get('book_store_orders');
    //     return $query->result_array(); 
    // }


    function getDetails($Orderid){
       
        $this->db->select('book_store_orders.order_id, sold_quantity, book_store_orders.book_id, book_store_orders.status  ,book.title, book.author, book.brand, book.book_id as isbn,  book.price, book_store_orders.quantity, book_store_orders.price as f_price,book_store_orders.std_id');
        $this->db->join('store_books as book', 'book.id = book_store_orders.book_id');
        $this->db->join('students', 'students.id = book_store_orders.std_id');
        $this->db->where('order_id', $Orderid);
        $query = $this->db->get('book_store_orders');
        return $query->result_array(); 

    }

    function cancelOrder($Orderid){

        $this->db->set('status',"-1", FALSE);
        $this->db->where('order_id',$Orderid);
        $this->db->update('book_store_orders');

        if($this->db->affected_rows() > 0) {
            return true;
        }else{
            return false;
        }

    }



    // function getReceiptDetails($Orderid){
       
    //     $this->db->select('book_store_orders.created_at, book_store_orders.std_id, book_store_orders.order_id, book_store_orders.order_placed_by,  sold_quantity, book_store_orders.book_id, book_store_orders.status  ,book.title,book.book_id as isbn, book.author, book.brand, book.price, book_store_orders.quantity, book_store_orders.price as f_price');
    //     $this->db->join('store_books as book', 'book.id = book_store_orders.book_id');
    //     //$this->db->join('users', 'users.id = book_store_orders.std_id');
    //    /* $this->db->join('students', 'students.parent_id = book_store_orders.std_id');*/

    // }

      function getReceiptDetails($Orderid){
       
        $this->db->select('book_store_orders.created_at, book_store_orders.std_id, book_store_orders.order_id, book_store_orders.order_placed_by,  sold_quantity, book_store_orders.book_id, book_store_orders.status  ,book.title,book.book_id as isbn, book.author, book.brand, book.price, book_store_orders.quantity, book_store_orders.price as f_price');
        $this->db->join('store_books as book', 'book.id = book_store_orders.book_id');
        //$this->db->join('users', 'users.id = book_store_orders.std_id');
       /* $this->db->join('students', 'students.parent_id = book_store_orders.std_id');*/
        $this->db->where('order_id', $Orderid);
        $query = $this->db->get('book_store_orders');
        //  echo "<pre>";   print_r( $query->result_array());exit;
		
        return $query->result_array();

    }






   function getPartiallyCompletedOrders(){
        $this->db->select('book_store_orders.id, students.parent_id,students.guardian_id ,students.admission_no , order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at,  students.id,order_id, sum(quantity) as qty , sum(price) as price');
        // $this->db->join('users', 'users.id = book_store_orders.std_id');
        // $this->db->join('students', 'students.id = users.user_id');
        $this->db->join('students', 'students.id = book_store_orders.std_id');
      // $this->db->where('status', "2");
        $this->db->group_by('order_id');
        $this->db->order_by('book_store_orders.created_at ', 'desc');
        $query = $this->db->get('book_store_orders');
        return $query->result_array();
    }





    function getCompleteOrders(){
        $this->db->select('book_store_orders.id,students.parent_id, students.guardian_id ,students.admission_no ,order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at, students.id,order_id, sum(quantity) as qty , sum(price) as price');
        //$this->db->join('users', 'users.id = book_store_orders.std_id');
        // $this->db->join('students', 'students.id = users.user_id');
                 $this->db->join('students', 'students.id = book_store_orders.std_id');
        $this->db->where('status',"1");
        $this->db->group_by('order_id');
        $this->db->order_by('book_store_orders.created_at ', 'desc');
        $query = $this->db->get('book_store_orders');
	//	echo $this->db->last_query();
//die;
        return $query->result_array();
    }

       function getOrderList($parent_id){
       
        $this->db->select('book_store_orders.id, status, std_id,students.admission_no,students.firstname, order_placed_by, MAX(status), order_id, sum(quantity) as qty , sum(price) as price');
        $this->db->join('students', 'students.id = book_store_orders.std_id');
        
        $this->db->where('std_id', $parent_id);
        $this->db->group_by('order_id');
        $query = $this->db->get('book_store_orders');
        return $query->result_array(); 
    }


    function getPendingOrders(){
        $this->db->select('book_store_orders.id, students.parent_id ,students.guardian_id ,students.admission_no ,std_id, status ,order_placed_by, MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at ,students.id,order_id, sum(quantity) as qty , sum(price) as price');
        // $this->db->join('users', 'users.id = book_store_orders.std_id');
        // $this->db->join('students', 'students.id = users.user_id');
         //$this->db->join('users', 'users.id = book_store_orders.std_id');
         $this->db->join('students', 'students.id = book_store_orders.std_id');
         //$this->db->where('std_id', $studentsId);
        //$this->db->where('status',"0");
        $this->db->group_by('order_id');
        $this->db->order_by('book_store_orders.created_at ', 'desc');
        $query = $this->db->get('book_store_orders');
//echo $this->db->last_query();
//die;
        return $query->result_array(); 
    }

    function getCancelledOrders(){
        $this->db->select('book_store_orders.id,students.parent_id, students.guardian_id ,students.admission_no ,order_placed_by, MAX(book_store_orders.status) as max_status, , MIN(book_store_orders.status) as min_status,book_store_orders.created_at, students.id,order_id, sum(quantity) as qty , sum(price) as price');
        // $this->db->join('users', 'users.id = book_store_orders.std_id');
        // $this->db->join('students', 'students.id = users.user_id');
        $this->db->join('students', 'students.id = book_store_orders.std_id');
        $this->db->where('status',"-1");
        $this->db->group_by('order_id');
        $this->db->order_by('book_store_orders.created_at ', 'desc');
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }


    function markOrderComplete($orderId, $sold_books, $books_id, $total_quantity, $taken_quantity){

        $isRoEffected = 0;
         for($i = 0; $i< count($books_id); $i++){
             $status = 0;

             $totalTakenQuantity = number_format($sold_books[$i]) + number_format($taken_quantity[$i]);

             if(number_format($sold_books[$i]) > 0){
                 $status = 0;// Partially Completed
             }

             if(number_format($total_quantity[$i]) == $totalTakenQuantity){
                 $status = 1;
             }


             $this->db->set('status',$status, FALSE);
             $this->db->set('sold_quantity', '`sold_quantity`+ '. $sold_books[$i], FALSE);

             $this->db->where('order_id', $orderId);
             $this->db->where('book_id', $books_id[$i]);
             $this->db->update('book_store_orders');
             if($this->db->affected_rows() > 0){
                 $isRoEffected = $this->db->affected_rows();
             }

         }
        return $isRoEffected;

    }


    //Reports

    function getTodaysReport($userName){

        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');


        $this->db->select('book_store_orders.id, book_store_orders.order_id, ,book_store_orders.order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at ,students.guardian_id, username ,order_id, sum(quantity) as qty , sum(price) as price');
        $this->db->join('users', 'users.id = book_store_orders.std_id');
        $this->db->join('students', 'students.id = users.user_id');
        $this->db->where('order_placed_by', $userName);
        $this->db->where('DATE(book_store_orders.created_at)', $curr_date);
        $this->db->group_by('order_id');
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }


    function getWeeklyReport($userName){
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');


        $this->db->select('book_store_orders.id, book_store_orders.order_id, ,book_store_orders.order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at ,students.guardian_id, username ,order_id, sum(quantity) as qty , sum(price) as price');
        $this->db->join('users', 'users.id = book_store_orders.std_id');
        $this->db->join('students', 'students.id = users.user_id');
        $this->db->where('order_placed_by', $userName);
        $this->db->where('book_store_orders.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()');
        $this->db->group_by('order_id');
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }

    function getAllReports($userName){
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');


        $this->db->select('book_store_orders.id, book_store_orders.order_id, ,book_store_orders.order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at ,students.guardian_id, username ,order_id, sum(quantity) as qty , sum(price) as price');
        $this->db->join('users', 'users.id = book_store_orders.std_id');
        $this->db->join('students', 'students.id = users.user_id');
        $this->db->where('order_placed_by', $userName);
       // $this->db->where('book_store_orders.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()');
        $this->db->group_by('order_id');
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }


    function getAllTodaysReport($userName){

        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');

        $this->db->select('book_store_orders.id, book_store_orders.order_id, ,book_store_orders.order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at,order_id,students.id ,students.guardian_id ,students.admission_no  ,sum(quantity) as qty , sum(price) as price');
        //$this->db->join('users', 'users.id = book_store_orders.std_id');
        //$this->db->join('students', 'students.id = users.user_id');
               $this->db->join('students', 'students.id = book_store_orders.std_id');
        $this->db->where('DATE(book_store_orders.created_at)', $curr_date);
        $this->db->group_by('order_id');
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }


    function getAllWeeklyReport($userName){
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');

      // $data=array();
      //   $start_date= $this->input->post('start_date');
      //   $end_date=$this->input->post('end_date');

        $this->db->select('book_store_orders.id, book_store_orders.order_id, ,book_store_orders.order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at ,students.id,students.guardian_id ,students.admission_no ,order_id, sum(quantity) as qty , sum(price) as price');
        //$this->db->join('users', 'users.id = book_store_orders.std_id');
        $this->db->join('students', 'students.id = book_store_orders.std_id');
        //$this->db->where('order_placed_by', $userName);
        // $this->db->where('book_store_orders.created_at >=', $start_date);
        // $this->db->where('book_store_orders.created_at <=', $end_date);
        $this->db->where('book_store_orders.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()');
        
        $this->db->group_by('order_id');
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }

    function getAllCompleteReports($userName){
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');
        $data=array();
        $start_date= $this->input->post('start_date');
        $end_date=$this->input->post('end_date');

        $this->db->select('book_store_orders.id, book_store_orders.order_id,book_store_orders.order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at ,students.guardian_id ,students.admission_no ,order_id, sum(quantity) as qty , sum(price) as price');
       // $this->db->join('users', 'users.id = book_store_orders.std_id');
          $this->db->join('students', 'students.id = book_store_orders.std_id');
        //$this->db->join('students', 'students.id = users.user_id');
       // $this->db->where('book_store_orders.created_at >=', $start_date);
       //  $this->db->where('book_store_orders.created_at <=', $end_date);

        //$this->db->where('order_placed_by', $userName);
            $this->db->where('book_store_orders.created_at =', $start_date);
        $this->db->where('book_store_orders.created_at =', $end_date);
        // $this->db->where('book_store_orders.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 3 YEAR) AND NOW()');
        $this->db->group_by('book_store_orders.order_id');
        //$this->db->limit(50,1);
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }



    function getAllCompleteReportses($userName){
        // $date = new DateTime("now");
        // $curr_date = $date->format('Y-m-d ');
        $data=array();
        $start_date= $this->input->post('start_date');
        $end_date=$this->input->post('end_date');

        $this->db->select('book_store_orders.id, book_store_orders.order_id, ,book_store_orders.order_placed_by ,MAX(book_store_orders.status) as max_status, MIN(book_store_orders.status) as min_status , book_store_orders.created_at ,students.guardian_id ,order_id, sum(quantity) as qty , sum(price) as price, students.admission_no');
       // $this->db->join('users', 'users.id = book_store_orders.std_id');
          $this->db->join('students', 'students.id = book_store_orders.std_id');
        //$this->db->join('students', 'students.id = users.user_id');
       $this->db->where('book_store_orders.created_at >=', $start_date." 00:00:00");
        $this->db->where('book_store_orders.created_at <=', $end_date." 23:59:59");

        //$this->db->where('order_placed_by', $userName);
        // $this->db->where('book_store_orders.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()');
        $this->db->group_by('book_store_orders.order_id');
        $query = $this->db->get('book_store_orders');

        return $query->result_array();
    }


}
?>
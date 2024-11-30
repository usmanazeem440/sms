<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Book_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null) {
        $this->db->select()->from('books');
        if ($id != null) {
            $this->db->where('books.id', $id);
        } else {
            $this->db->order_by('books.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }


    // public function getBookCheck($booknumber)
    // {
    //     $this->db->select()->from('books');
    //     $this->db->where('books.other', $booknumber);
    //     $query = $this->db->get();
    //     return $query->result_array();
        
    // }

       public function getBookCheck($booknumber)
    {
        $this->db->select()->from('books');
        $this->db->where('books.other', $booknumber);
        $this->db->where('books.available', 'yes');
        $this->db->where('books.is_active', 'yes');
        $query = $this->db->get();
        return $query->result_array();
        
    }
    
       public function getBookChecks($booknumber1)
    {
        $this->db->select()->from('books');
        $this->db->where('books.other', $booknumber1);

        $query = $this->db->get();
        return $query->result_array();
        
    }


//         public function getBookCheck($booknumber){

        
//         $this->db->select()->from('books');
//         $this->db->group_start();
//         $this->db->like('books.other', $booknumber);
//         $this->db->where('books.other', $booknumber);
//          $this->db->group_end();

//         //$this->db->group_by('students.is_active', 'yes');
//         $query = $this->db->get();
//         //echo $this->db->last_query();
// //die;
//      //   echo $searchterm;echo"<pre>";print_r($query->result());exit;
//         return $query->result();

//     }
    
    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('books');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function addbooks($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('books', $data);
        } else {
            $this->db->insert('books', $data);
            return $this->db->insert_id();
        }

    }
    
    public function listbook($getTotalCount = FALSE, $limit = 10, $start = 0) {
        $this->db->select('books.id,books.book_title,books.book_no,books.isbn_no,books.author,books.other,books.subject,books.publish,books.location,books.class,books.tags,books.description,books.available,books.is_active')->from('books');
        // if(trim($title) != '')
        //     $this->db->where('book_title', $title);
        // if(trim($book_no) != '')
        //     $this->db->where('book_no',$book_no);
        // if(trim($isbn) != '')
        //     $this->db->where('isbn_no',$isbn);
        // if(trim($author) != '')
        //     $this->db->where('author',$author);
        // if(trim($barcode) != '')
        //     $this->db->where('other',$barcode);
        // if(trim($subject) != '')
        //     $this->db->where('subject',$subject);
      //  $this->db->limit(10);
        $this->db->order_by("id", "desc");
        if ($getTotalCount) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($limit, $start);
            $query = $this->db->get();
            
            return $query->result_array();
        }
    }

    public function check_Exits_group($data) {
        $this->db->select('*');
        $this->db->from('feemasters');
        $this->db->where('session_id', $this->current_session);
        $this->db->where('feetype_id', $data['feetype_id']);
        $this->db->where('class_id', $data['class_id']);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function getTypeByFeecategory($type, $class_id) {
        $this->db->select('feemasters.id,feemasters.session_id,feemasters.amount,feemasters.description,classes.class,feetype.type')->from('feemasters');
        $this->db->join('classes', 'feemasters.class_id = classes.id');
        $this->db->join('feetype', 'feemasters.feetype_id = feetype.id');
        $this->db->where('feemasters.class_id', $class_id);
        $this->db->where('feemasters.feetype_id', $type);
        $this->db->where('feemasters.session_id', $this->current_session);
        $this->db->order_by('feemasters.id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function add_book_stock($data) {
        $query = $this->db->insert_batch('books', $data);
        return $this->db->affected_rows();
    }
    //UA
       public function update_book_by_id($other,$available){
        $data = array(
            'other' => $other,
            'available' =>$available,
        );
        $this->db->where('other',$other);
        $this->db->update('books',$data);
        return $this->db->affected_rows();

    }
      public function getBookChecksWithAvailable($booknumber)
    {
        $this->db->select()->from('books');
        $this->db->where('books.other', $booknumber);
        $this->db->where('books.available', 'yes');
        $query = $this->db->get();
        return $query->num_rows();
        
    }
        public function getBookCheckssWithIsActive($booknumber)
    {
        $this->db->select()->from('books');
        $this->db->where('books.other', $booknumber);
        $this->db->where('books.is_active', 'yes');
        $query = $this->db->get();
        return $query->num_rows();
        
    }

}

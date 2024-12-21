<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->setting = $this->setting_model->get();

    }

    public function index_old() {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title'] = 'Member';
        $data['title_list'] = 'Members';
        $library_card_no= $this->input ->get('library_card_no');

        // dd($library_card_no);
        /*if($library_card_no==""){
        $memberList = $this->librarymember_model->get();
        $data['memberList'] = $memberList;
        }
        //echo "<pre>"; print_r($library_card_no); exit;
        if($library_card_no!=""){
        $memberList = $this->librarymember_model->get($library_card_no);
        //echo "<pre>"; print_r($memberList); exit;
        $data['memberList'] = $memberList;
        }*/
        
        
        
       // $listbook = $this->book_model->get();


        $config = array();
        $config['reuse_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "admin/member/index";

        $config ['uri_segment'] = 4;
        $config ['per_page'] = 25;
        $config ['num_links'] = 5;
        $config["total_rows"] = $this->librarymember_model->get( true, false, false, $library_card_no);
        $data['library_card_no'] = $library_card_no;
        // dd($config["total_rows"],'test');
        $config['full_tag_open'] = '<nav aria-label="Page navigation example">
                      <ul class="pagination pg-blue">';
        $config['full_tag_close'] = ' </ul>
                    </nav>';
        $config['first_link'] = false;
        $config['last_link'] = false;
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
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
        // dd($page);
        $offset = ($page - 1) * $config ['per_page'];
        //echo $config["per_page"]."-----".$offset;
        
        //echo  "Total rows:".$config["total_rows"];
        $data["links"] = $this->pagination->create_links();
        // dd($data['links']);
        $memberList = $this->librarymember_model->get(FALSE, $config["per_page"], $offset, $library_card_no);
        // dd($this->db->last_query());
        $data['memberList'] = $memberList;

        $this->load->view('layout/header');
        $this->load->view('admin/librarian/index', $data);
        $this->load->view('layout/footer');
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title'] = 'Member';
        $data['title_list'] = 'Members';

        $this->load->view('layout/header');
        $this->load->view('admin/librarian/index', $data);
        $this->load->view('layout/footer');
    }

    public function getMembersList(){
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        

        $totalData = $this->librarymember_model->get(true, false, false);

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value']))
        {
            $members = $this->librarymember_model->get(false);
            // dd($members);
        } else {
            $search = trim($this->input->post('search')['value']); 

            $members =  $this->librarymember_model->get(false, $limit,$start,$search);

            $totalFiltered = $this->librarymember_model->get(true,false,false, $search);
        }

        $data = array();
        if(!empty($members))
        {
            foreach ($members as $member)
              {
                $action = '';
                // if ($this->rbac->hasPrivilege('books', 'can_edit')) {
                // dd($member);
                    $action .= '<a href="'.base_url('admin/member/issue/'.$member->lib_member_id).'" class="btn btn-default btn-xs"  data-toggle="tooltip" title="'.$this->lang->line("issue_return").'">
                                    <i class="fa fa-sign-out"></i>
                                </a>';
                // }

                if ($member->member_type == "student") {
                    $name = $member->firstname . " " . $member->lastname;
                    $class_name = $member->class_name;
                    $section = $member->section;
                } else {
                    // dd($member);
                    $email = $member->teacher_email;
                    $name = $member->teacher_name;
                    $sex = $member->teacher_sex;
                    $class_name = $member->class_name;
                    $section = $member->section;
                }

                $nestedData['member_id'] = $member->lib_member_id;
                $nestedData['library_card_no'] = $member->library_card_no;

                $nestedData['admission_no'] = $member->admission_no;
                $nestedData['name'] = $name;
                $nestedData['member_type'] = $this->lang->line($member->member_type);
                $nestedData['class'] = $class_name." ( ".$section." ) ";
                $nestedData['action'] = "<div class='mailbox-date pull-right'>".$action."</div>";

                $data[] = $nestedData;

              }
        }

        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);

    }
    public function ajax(){
        $config = array();
        $config['reuse_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "admin/book/getall";

        $config ['uri_segment'] = 4;
        $config ['per_page'] = 2;
        $config ['num_links'] = 5;
        $config["total_rows"] = $this->librarymember_model->get( true);
        $config['full_tag_open'] = '<nav aria-label="Page navigation example">
                      <ul class="pagination pg-blue">';
        $config['full_tag_close'] = ' </ul>
                    </nav>';
        $config['first_link'] = false;
        $config['last_link'] = false;
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

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
        $offset = ($page - 1) * $config ['per_page'];
        //echo $config["per_page"]."-----".$offset;
        
        //echo  "Total rows:".$config["total_rows"];
        $data["links"] = $this->pagination->create_links();
        $memberList = $this->librarymember_model->get(FALSE, $config["per_page"], $offset);
        $data['memberList'] = $memberList;
        $this->load->view('admin/librarian/ajax', $data);

    }



    public function issue($id) {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title'] = 'Member';
        $data['title_list'] = 'Members';
        $memberList = $this->librarymember_model->getByMemberID($id);
        $data['memberList'] = $memberList;
        $issued_books = $this->bookissue_model->getMemberBooks($id);
        $data['fineList'] = $this->bookissue_model->getMemberFine($id);
        $data['issued_books'] = $issued_books;
        $bookList = $this->book_model->get();
        $data['bookList'] = $bookList;

        $bookIds = $this->input->post('book_id');
        $data['bok'] = $this->book_model->getBookCheck($bookIds);

        $Availabecheck = $this->book_model->getBookChecksWithAvailable($bookIds);
        $Activecheck = $this->book_model->getBookCheckssWithIsActive($bookIds);
        if ($bookIds!="" && $Availabecheck == "" && $Activecheck== ""){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Book barcode not found, Please try again.</div>');
         }
        // $bookss['bok']= $this->book_model->getBookChecks();
         //var_dump($data['bok']);die();
         if ($bookIds!="" && $Availabecheck == "" && $Activecheck!= ""){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Sorry! Book borrowed.</div>');
         }
         if($bookIds!=""  && $Availabecheck != "" && $Activecheck!= ""){
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book Available! Click save if you want to borrow this.</div>');
        
        $this->form_validation->set_rules('book_id', 'Book', 'trim|required|xss_clean');
        $this->form_validation->set_rules('return_date', 'Return Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $member_id = $this->input->post('member_id');
            $bookId = $this->input->post('book_id');
            $bookValidation= $this->book_model->getBookCheck($bookId);
            //echo '<pre>'; print_r(  $bookValidation); exit;
           // $bookValidation= $this->book_model->getBookCheckss($bookId);
            if(count($bookValidation) > 0)
            {
                $data = array(
                    'book_id' => $bookValidation[0]['id'],
                    'return_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('return_date'))),
                    'issue_date' => date('Y-m-d'),
                    'member_id' => $this->input->post('member_id'),
                    //'is_active' => 'no',
                );

                $barcode = $this->input->post('book_id');
                $available = "no";
                $this->book_model->update_book_by_id($barcode,$available);
                $this->bookissue_model->add($data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book issued successfully.</div>');
            }

            redirect('admin/member/issue/' . $member_id);
        }
    }   
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/issue', $data);
        $this->load->view('layout/footer');
    }


    public function bookreturn($id, $member_id) {
        //get book_issues table data by id
        $book_issue = $this->bookissue_model->get_bookissue_by_id($id);
        $book_issue_id = $book_issue[0]['id'];
        $return_date = $book_issue[0]['return_date'];
        $current_date= date("Y-m-d");
        if($current_date > $return_date) 
         {   
            $symbol = $this->setting[0]['currency_symbol'];
            $a =strtotime(date('Y-m-d'));
            $time = new DateTime($return_date);
            $date = $time->format('Y-m-d');
            $b = strtotime($date);
            $diff = $a -$b;
            $days= floor($diff/(60*60*24));
            $balance=$days*10; // hardcoded 10 rupees fine
            $this->bookissue_model->add_fine($book_issue_id,$days,$balance);
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">You have fine '.$symbol.$balance. '.00/ please pay this. Book Returned Successfully</div>');
         }
         else{
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book returned successfully.</div>');
         }
        //get book_issues table data by id
        $book_issue = $this->bookissue_model->get_book_data_by_bookissue_id($id);
        $other = $book_issue[0]['other'];
        //book table update column available
        $barcode_id = $other;
        $available = "yes";
        $this->book_model->update_book_by_id($barcode_id,$available);
        //book_issues table update
               $data = array(
            'id' => $id,
            'is_returned' => 1,
            'return_date' => date("Y-m-d")
        );
        $this->bookissue_model->update($data);


        redirect('admin/member/issue/' . $member_id);
    }
    public function payfine($fid, $member_id) {
              
              //echo '<pre>'; print_r(  $fid); exit;

               $data = array(
            'id' => $fid,
            'amount_paid' => 'Yes',
            'status'=> 1
           
        );
        $check=$this->bookissue_model->update_fine($data);
        if($check!=""){
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Fine Paid successfully.</div>');
         }
         

        redirect('admin/member/issue/' . $member_id);
    }

    function student() {

        if (!$this->rbac->hasPrivilege('add_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/student');
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
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
                        $resultlist = $this->student_model->searchLibraryStudent($class, $section);
                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text);
                    $data['resultlist'] = $resultlist;
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function add() {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', 'library Card No', 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student = $this->input->post('member_id');
                $data = array(
                    'member_type' => 'student',
                    'member_id' => $student,
                    'library_card_no' => $library_card_no
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student = $this->input->post('member_id');
            $data = array(
                'member_type' => 'student',
                'member_id' => $student,
                'library_card_no' => $library_card_no
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    function check_cardno_exists() {
        $data['library_card_no'] = $this->security->xss_clean($this->input->post('library_card_no'));

        if ($this->librarymanagement_model->check_data_exists($data)) {
            $this->form_validation->set_message('check_cardno_exists', 'Card no already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function teacher() {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/teacher');
        $data['title'] = 'Add Teacher';
        $teacher_result = $this->teacher_model->getLibraryTeacher();
        $data['teacherlist'] = $teacher_result;

        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/member/teacher', $data);
        $this->load->view('layout/footer', $data);
    }

    function addteacher() {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', 'library Card No', 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student = $this->input->post('member_id');
                $data = array(
                    'member_type' => 'teacher',
                    'member_id' => $student,
                    'library_card_no' => $library_card_no
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student = $this->input->post('member_id');
            $data = array(
                'member_type' => 'teacher',
                'member_id' => $student,
                'library_card_no' => $library_card_no
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    public function surrender() {

        $this->form_validation->set_rules('member_id', 'Book', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $member_id = $this->input->post('member_id');
            $this->librarymember_model->surrender($member_id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Membership surrender successfully');
            echo json_encode($array);
        }
    }

}

?>
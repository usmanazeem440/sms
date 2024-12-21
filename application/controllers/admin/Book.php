<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Book extends Admin_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->model('book_model');
        $this->load->library('session');
        $this->load->library('encoding_lib');
        $this->load->model('setting_model');
        
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');
        $data['title'] = 'Add Book';
        $data['title_list'] = 'Book Details';
        
        $config = array();
        $config['reuse_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "admin/book/getall";

        $config ['uri_segment'] = 3;
        $config ['per_page'] = 25;
        $config ['num_links'] = 5;
        $config["total_rows"] = $this->book_model->listbook( true);
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
        
        
        $listbook = $this->book_model->listbook( FALSE, $config["per_page"], $offset);
        $data['listbook'] = $listbook;
        $this->load->view('layout/header');
        $this->load->view('admin/book/createbook', $data);
        $this->load->view('layout/footer');
    }

    public function getall_old() {
        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');
        $data['title'] = 'Add Book';
        $data['title_list'] = 'Book Details';
        
        $config = array();
        $config['reuse_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "admin/book/getall";

        $config ['uri_segment'] = 4;
        $config ['per_page'] = 10;
        $config ['num_links'] = 5;
        $config["total_rows"] = $this->book_model->listbook( true);
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
        
        
       // $listbook = $this->book_model->get();
        $listbook = $this->book_model->listbook( FALSE, $config["per_page"], $offset);
        $data['listbook'] = $listbook;
        $this->load->view('layout/header');
        $this->load->view('admin/book/getall', $data);
        $this->load->view('layout/footer');
    }

    public function getall() {
        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');
        $data['title'] = 'Add Book';
        $data['title_list'] = 'Book Details';
    
        $this->load->view('layout/header');
        $this->load->view('admin/book/getall', $data);
        $this->load->view('layout/footer');
    }

    public function getallList(){
        $limit = $this->input->post('length');
        $start = $this->input->post('start');

        $totalData = $this->book_model->all_books_count();

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value']))
        {
            $books = $this->book_model->all_books($limit,$start);
        }
        else {
            $search = trim($this->input->post('search')['value']); 

            $books =  $this->book_model->books_search($limit,$start,$search);

            $totalFiltered = $this->book_model->books_search_count($search);
        }

        $data = array();
        if(!empty($books))
        {
            foreach ($books as $book)
              {
                $action = '';
                if ($this->rbac->hasPrivilege('books', 'can_edit')) {
                    $action .= '<a href="'.base_url('admin/book/edit/'.$book->id).'" class="btn btn-default btn-xs"  data-toggle="tooltip" title="'.$this->lang->line("edit").'">
                                    <i class="fa fa-pencil"></i>
                                </a>';
                }
                if ($this->rbac->hasPrivilege('books', 'can_delete')) {
                    $action .= '<a href="'.base_url('admin/book/delete/'.$book->id).'" class="btn btn-default btn-xs"  data-toggle="tooltip" title="'.$this->lang->line("delete_confirm").'">
                                    <i class="fa fa-remove"></i>
                                </a>';
                }
                $nestedData['other'] = $book->other;
                $nestedData['book_no'] = $book->book_no;
                $nestedData['book_title'] = '<a href="#" data-toggle="popover" class="detail_popover">'. $book->book_title. '</a>
                    <div class="fee_detail_popover" style="display: none">';
                if ($book->description == "") {
                    $nestedData['book_title'] .= '<p class="text text-danger">'.$this->lang->line('no_description').'</p>';
                } else {
                    $nestedData['book_title'] .= '<p class="text text-info">'.$book->description.'</p>';
                }
                $nestedData['book_title'] .= '</div>';

                // $nestedData['book_title'] = '<a href="#" data-toggle="popover" class="detail_popover" data-original-title="" title="">'. $book->book_title. '</a> <div class="fee_detail_popover" style="display: none">
                //     <p class="text text-info">This is a description for book 200.</p>
                // </div>';
                $nestedData['isbn_no'] = $book->isbn_no;
                $nestedData['publish'] = $book->publish;
                $nestedData['author'] = $book->author;
                $nestedData['subject'] = $book->subject;
                $nestedData['location'] = $book->location;
                $nestedData['class'] = $book->class;
                $nestedData['tags'] = $book->tags;
                $nestedData['is_active'] = $book->is_active;
                $nestedData['available'] = $book->available;
                $nestedData['action'] = "<div class='mailbox-date no-print text text-right'>".$action."</div>";

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

    function create() {
        if (!$this->rbac->hasPrivilege('books', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Book';
        $data['title_list'] = 'Book Details';
        $this->form_validation->set_rules('book_title', 'Book Title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('book_no', 'Book Number', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listbook = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/book/createbook', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'book_title' => $this->input->post('book_title'),
                'book_no' => $this->input->post('book_no'),
                'isbn_no' => $this->input->post('isbn_no'),
                'subject' => $this->input->post('subject'),
                'location' => $this->input->post('location'),
                'publish' => $this->input->post('publish'),
                'author' => $this->input->post('author'),
                'class' => $this->input->post('class'),
                'tags' => $this->input->post('tags'),
                //'postdate' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate'))),
                'other' => $this->input->post('other'),
                'description' => $this->input->post('description')
            );
            $this->book_model->addbooks($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book added successfully</div>');
            redirect('admin/book/index');
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('books', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Book';
        $data['title_list'] = 'Book Details';
        $data['id'] = $id;
        $editbook = $this->book_model->get($id);
        $data['editbook'] = $editbook;
        $this->form_validation->set_rules('book_title', 'Book Title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('book_no', 'Book Number', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listbook = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/book/editbook', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'book_title' => $this->input->post('book_title'),
                'book_no' => $this->input->post('book_no'),
                'isbn_no' => $this->input->post('isbn_no'),
                'subject' => $this->input->post('subject'),
                'location' => $this->input->post('location'),
                'publish' => $this->input->post('publish'),
                'author' => $this->input->post('author'),
                'class' => $this->input->post('class'),
                'tags' => $this->input->post('tags'),
                'other' => $this->input->post('other'),
                'description' => $this->input->post('description')
            );
            $this->book_model->addbooks($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book updated successfully</div>');
            redirect('admin/book/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->book_model->remove($id);
        redirect('admin/book/getall');
    }

        public function import(){

        $fields = array('book_no', 'book_title', 'isbn_no', 'subject', 'location', 'publish', 'author', 'class', 'tags', 'other', 'description');
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

                        $isbn = $data[$i]["book_no"];

                        $data_new = array(
                            'book_no' =>  $data[$i]["book_no"],
                            'book_title' =>  $data[$i]["book_title"],
                            'isbn_no' =>  $data[$i]["isbn_no"],
                            'subject' =>  $data[$i]["subject"],
                            'location' => $data[$i]['location'],
                            'publish' => $data[$i]["publish"],
                            'author' => $data[$i]["author"],
                            'class' =>  $data[$i]["class"],
                            'tags' => $data[$i]['tags'],
                            'other' => $data[$i]["other"],
                            'description' => $data[$i]["description"]
                        );

                        $array []=  $data_new;
                        $rowcount++;

                    }

                    if($this->book_model->add_book_stock($array) > 0){
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
        $this->load->view('admin/book/import', $data);
        $this->load->view('layout/footer');
    }

    
    public function exportformat(){
        $this->load->helper('download');
        $filepath = "./backend/import/import_book_library_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'import_book_library_sample_file.csv';

        force_download($name, $data);
    }
}

?>
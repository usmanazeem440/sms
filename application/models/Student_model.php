<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }
    
     public function getClasses() {
        $this->db->select('id ,class as name');
        $query = $this->db->get('classes');
        return $query->result_array();
    }

    public function getSections() {
        $this->db->select('id ,section as name');
        $query = $this->db->get('sections');
        return $query->result_array();
    }

    public function getStudents() {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.role', 'student');

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }
    
        public function getAllStudents($columns,$joins=false) {
                $this->db->select($columns)->from('students');
                if($joins){
                    $this->db->join('student_session', 'student_session.student_id = students.id','INNER');
                    $this->db->join('classes', 'student_session.class_id = classes.id','INNER');
                    $this->db->join('sections', 'sections.id = student_session.section_id','INNER');
                    $this->db->join('categories', 'students.category_id = categories.id', 'left');
                }
               // $this->db->where('student_session.session_id', $this->current_session);
                $this->db->join('users', 'users.user_id = students.id AND users.role = "student" ','INNER');
               $this->db->where('students.is_active', 'yes');
                $this->db->where('users.role', 'student');
                $this->db->group_by('students.id');
                // $this->db->limit(5);
               // $this->db->order_by('students.id');

                $query = $this->db->get();
                return $query->result_array();
            }
            
          
            
    public function getRecentRecord($id = null) {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            
        }
        $this->db->order_by('students.id', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function get($id = null) {
        $this->db->select('student_session.transport_fees,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,student_session.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city ,students.guardian_id, students.pincode , students.note, students.religion,  school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.birth_place, students.address, students.name_arabic, ,students.iqama_number, students.iqama_expiry_date, students.nationality, students.passport_number, students.passport_issued_from, students.passport_expiry_date, students.father_education, students.father_iqama_number, students.father_iqama_expiry, students.father_passport_number, students.father_passport_issued_from, students.father_passport_expiry, students.insurance_number, students.insurance_company_name, students.insurance_policy_number, students.insurance_contact_number, students.mother_education, students.father_education, students.allergies, students.medicine, students.mother_tongue, students.guardian_name , students.father_pic ,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.guardian_email')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function search_student() {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_id, students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->order_by('students.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getstudentdoc($id) {
        $this->db->select()->from('student_doc');
        $this->db->where('student_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchByClassSection($class_id = null, $section_id = null,$limit=10, $start=0) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.birth_place, students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_id, students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        //$query = $this->db->get();

        $this->db->limit($limit, $start);
        //echo "<pre>";
		//print_r($limit."-".$start);
		//die;
		$query = $this->db->get();
	//	echo $this->db->last_query();
//die;
        return $query->result_array();
    }

    public function searchByClassSectionTotalRecords($class_id = null, $section_id = null) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.birth_place, students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_id, students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

       // $query = $this->db->get();
			return  $this->db->count_all_results();
        //return $query->result_array();
    }

    public function searchByClassSectionCategoryGenderRte($class_id = null, $section_id = null
    , $category = null, $gender = null, $rte = null) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id, categories.category,   students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if ($category != null) {
            $this->db->where('students.category_id', $category);
        }
        if ($gender != null) {
            $this->db->where('students.gender', $gender);
        }
        if ($rte != null) {
            $this->db->where('students.rte', $rte);
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    //SearchParent

    public function searchParents($searchterm){

        $this->db->select('parent_id, father_name, father_phone, father_occupation, count(*) as children')->from('students');
        $this->db->like('students.father_name', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.guardian_id', $searchterm);
        $this->db->group_by('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();

    }
	
	    public function searchStudent($searchterm){

        
		$this->db->select('students.id,admission_no,firstname, lastname, father_name, birth_place, gender, dob,classes.class,sections.section, guardian_id')->from('students');

        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');


		$this->db->group_start();
        $this->db->like('students.father_name', $searchterm);
         // $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);

        $this->db->or_where("CONCAT(students.firstname, ' ', students.lastname) LIKE '%$searchterm%'", NULL, FALSE);

        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->or_like('students.id', $searchterm);
        $this->db->group_end();
        $this->db->where('students.is_active', 'yes');
        //$this->db->group_by('students.is_active', 'yes');
        $query = $this->db->get();
		//echo $this->db->last_query();
//die;
     //   echo $searchterm;echo"<pre>";print_r($query->result());exit;
        return $query->result();

    }
 
    //Search By Parent Name

    public function searchStudentByParentName($searchterm, $carray = null,  $getTotal = false,$limit=10, $start=0) {
        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $this->db->select('classes.id AS `class_id`,students.id, students.guardian_id, classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at, students.parent_id,students.gender,students.rte,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->or_like('students.guardian_id', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.father_name', $searchterm);
         $this->db->or_like('students.guardian_id', $searchterm);
        $this->db->or_like('students.mother_name', $searchterm);


        $this->db->group_end();
        
         if ($getTotal) {
            return  $this->db->count_all_results();
        }
        $this->db->order_by('students.id');
         $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();
    }







    //Search By Parent Name end



public function searchFullTextCount($searchterm, $carray = null) {
        
		//return $this->db->count_all('students','student_session','classes','sections','categories');
       $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $this->db->select('classes.id AS `class_id`,students.id, students.mother_tongue,students.birth_place ,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_id, students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.father_name', $searchterm);
        $this->db->or_like('students.mother_name', $searchterm);

        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('students.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');
		
	return  $this->db->count_all_results();
		
       
    }
	
    public function searchFullText($searchterm, $carray = null,$limit=10, $start=0) {
        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $this->db->select('classes.id AS `class_id`,students.id, students.mother_tongue,students.birth_place ,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_id, students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.father_name', $searchterm);
        $this->db->or_like('students.mother_name', $searchterm);

        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('students.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');
		$this->db->limit($limit, $start);
        //echo "<pre>";
		//print_r($limit."-".$start);
		//die;
		$query = $this->db->get();
	//	echo $this->db->last_query();
//die;
        return $query->result_array();
    }

    public function getStudentListBYStudentsessionID($array) {
        $array = implode(',', $array);
        $sql = ' SELECT students.* FROM students INNER join (SELECT * FROM `student_session` WHERE `student_session`.`id` IN (' . $array . ')) as student_session on students.id=student_session.student_id';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function remove($id) {
        $this->db->trans_start();

        $sql = "SELECT * FROM `users` WHERE childs LIKE '%," . $id . ",%' OR childs LIKE '" . $id . ",%' OR childs LIKE '%," . $id . "' OR childs = " . $id;
        $query = $this->db->query($sql);


        if ($query->num_rows() > 0) {
            $result = $query->row();
            $array_slice = explode(',', $result->childs);
            if (count($array_slice) > 1) {
                $arr = array_diff($array_slice, array($id));
                $update = implode(",", $arr);
                $data = array('childs' => $update);

                $this->db->where('id', $result->id);
                $this->db->update('users', $data);
            } else {
                $this->db->where('id', $result->id);
                $this->db->delete('users');
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('students');

        $this->db->where('student_id', $id);
        $this->db->delete('student_session');



        $this->db->where('user_id', $id);
        $this->db->where('role', 'student');
        $this->db->delete('users');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function doc_delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('student_doc');
    }


    public function getfirstInsertedId($admission_no){
        $row = $this->db->select('id')->where('admission_no', $admission_no)->get('students')->row();
        if($row != null){
            return $row->id;
        }
        return '0';
    }


    function getStudentDetail($userId){
        $this->db->select('parent_id,students.id,classes.Tag,sections.section,admission_no ,firstname ,father_name, guardian_id ,father_phone, father_occupation, count(*) as children')->from('students,student_session');
         $this->db->join('classes', 'classes.id = student_session.class_id');
          $this->db->join('sections', 'sections.id = student_session.section_id');
        //$this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('students.id', $userId);
        //$this->db->where('student_session.student_id', $userId);
        $query = $this->db->get();
        return $query->result();
    }





    public function add($data, $update = false) {


        if($update) { // currently updated code for update by admission no
            if (isset($data['admission_no'])) {
                $this->db->where('admission_no', $data['admission_no']);
                $this->db->update('students', $data);
            }
        } else {
            if (isset($data['id'])) {
                $this->db->where('id', $data['id']);
                $this->db->update('students', $data);
            } else {
                $this->db->insert('students', $data);
            
                return $this->db->insert_id();
            }
        }
        
    }

    public function getLastId(){
        $row = $this->db->select('id')->order_by('id',"desc")->limit(1)->get('students')->row();
        if($row != null){
            return $row->id;
        }
        return '0';
    }


    public function addBluckData($data){
        $this->db->insert_batch('students', $data);
        return  $this->db->insert_id();
    }

    public function updateBlukData($data){

        $rowCount = 0;
        foreach ($data as $d){
            $this->db->where('admission_no', $d['admission_no']);
            $this->db->update('students', $d);
            $rowCount +=$this->db->affected_rows();

        }

        return $rowCount;

    }




    public function add_student_sibling($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_sibling', $data);
        } else {
            $this->db->insert('student_sibling', $data);
            return $this->db->insert_id();
        }
    }



    public function add_student_session_bluk($data) {
        $this->db->insert_batch('student_session', $data);
        return $this->db->insert_id();
    }




    public function add_student_session($data) {
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('student_session');
        if ($q->num_rows() > 0) {
            $rec = $q->row_array();
            $this->db->where('id', $rec['id']);
            $this->db->update('student_session', $data);
        } else {
            $this->db->insert('student_session', $data);
            return $this->db->insert_id();
        }
    }

    public function add_student_session_update($data) {
        $this->db->where('session_id', $data['session_id']);
        $q = $this->db->get('student_session');
        if ($q->num_rows() > 0) {
            $this->db->where('session_id', $student_session);
            $this->db->update('student_session', $data);
        } else {
            $this->db->insert('student_session', $data);
            return $this->db->insert_id();
        }
    }

    public function adddoc($data) {
        $this->db->insert('student_doc', $data);
        return $this->db->insert_id();
    }

    public function read_siblings_students($parent_id) {
        $this->db->select('*')->from('students');
        $this->db->where('parent_id', $parent_id);
        $this->db->where('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
    }




    public function getGuardianId($id){
        $this->db->select("students.guardian_id");
        $this->db->where('students.parent_id', $id);
        $this->db->where('students.guardian_id !=', '');
        $query = $this->db->get('students');
        return $query->result();
    }

    public function getParentId($id){
        $this->db->select("students.parent_id");
        $this->db->where('students.guardian_id', $id);
        $this->db->where('students.parent_id !=', '');
        $this->db->where('students.parent_id >', 0);
        $query = $this->db->get('students');

        $result = ($query->num_rows() > 0) ? $query->row()->parent_id : FALSE;
        return $result;
    }





    public function getMySibling($id) {

        $this->db->select('student_session.transport_fees,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,student_session.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion,  school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.iqama_number, students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->where('students.guardian_id', $id);

        $query = $this->db->get();
        return $query->result();

    }










    public function getMySiblings($parent_id, $student_id) {
        // dd($parent_id);
        if($parent_id != '' && $parent_id > 0) {
            $this->db->select('students.*,classes.id as `class_id`,classes.class,sections.id as `section_id`,sections.section,student_session.session_id as `session_id`')->from('students');
            $this->db->join('student_session', 'student_session.student_id = students.id');
            $this->db->join('classes', 'student_session.class_id = classes.id');
            $this->db->join('sections', 'sections.id = student_session.section_id');
            $this->db->join('categories', 'students.category_id = categories.id', 'left');
            $this->db->where('student_session.session_id', $this->current_session);
            $this->db->where_not_in('students.id', $student_id);
            $this->db->where('students.parent_id', $parent_id);
            $this->db->where('students.is_active', 'yes');
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function getAttedenceByDateandClass($date) {
        $sql = "SELECT IFNULL(student_attendences.id, 0) as attencence FROM `student_session`left JOIN student_attendences on student_attendences.student_session_id=student_session.id and student_attendences.date=" . $this->db->escape($date) . " and student_attendences.attendence_type_id != 2 where student_session.class_id=7 and student_session.session_id=$this->current_session";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchCurrentSessionStudents() {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);

        $this->db->order_by('students.firstname', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchLibraryStudent($class_id = null, $section_id = null) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,
           IFNULL(libarary_members.id,0) as `libarary_member_id`,
           IFNULL(libarary_members.library_card_no,0) as `library_card_no`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('libarary_members', 'libarary_members.member_id = students.id and libarary_members.member_type = "student"', 'left');


        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchNameLike($searchterm) {
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchGuardianNameLike($searchterm) {
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.guardian_email,students.rte,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.guardian_name', $searchterm);

        $this->db->group_end();
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchByClassSectionWithSession($class_id = null, $section_id = null, $session_id = null) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    function getPreviousSessionStudent($previous_session_id, $class_id, $section_id, $student_admission_number = []) {
		$wehreInArray = [];
		if(!empty($student_admission_number))
			$wehreInArray[]	 = " students.admission_no IN ( '".implode("','", $student_admission_number)."') ";
		if(trim($class_id)!='')
			$wehreInArray[]	 = " student_session.class_id=$class_id  ";
		if(trim($section_id)!= '')
			$wehreInArray[]	 = " student_session.section_id=$section_id  ";
        $sql = "SELECT 
					student_session.student_id as student_id, 
					student_session.id as current_student_session_id,
					student_session.class_id as current_session_class_id ,
					previous_session.id as previous_student_session_id,
					students.firstname,
					students.lastname,
					students.admission_no,
					students.roll_no,
					students.father_name,
					students.admission_date 
				FROM 
					`student_session` 
				LEFT JOIN 
					(SELECT * FROM `student_session` where session_id=$previous_session_id) as
						previous_session on student_session.student_id=previous_session.student_id 
				INNER JOIN 
					students on students.id = student_session.student_id 
				WHERE  
					student_session.session_id=$this->current_session 
				AND students.is_active='yes'
				AND ".((!empty($wehreInArray))?implode(' AND ', $wehreInArray): ' 1=1 ' )."
				ORDER BY 
				students.firstname ASC";

        $query = $this->db->query($sql);
        return $query->result();
    }

    function studentGuardianDetails($carray) {
        $userdata = $this->customlib->getUserData();

        $this->db->SELECT("students.admission_no,students.firstname,students.mobileno,students.father_phone,students.mother_phone,students.lastname,students.father_name,students.mother_name,students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id',$this->current_session);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $query = $this->db->get("students");

        return $query->result_array();
    }

    function searchGuardianDetails($class_id, $section_id,$getTotalCount=FALSE,$limit=10, $start=0) {

        $this->db->SELECT("students.admission_no,students.firstname,students.lastname,students.mobileno,students.father_phone,students.mother_phone,students.father_name,students.mother_name,students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id',$this->current_session);
        $this->db->where(array('student_session.class_id' => $class_id, 'student_session.section_id' => $section_id,));
		
		if($getTotalCount){
			return $this->db->count_all_results("students");
		}else{
		 $this->db->order_by('students.firstname');
		$this->db->limit($limit, $start);
        $query = $this->db->get("students");

        return $query->result_array();
		}
    }

    function studentAdmissionDetails($carray = null) {

        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $query = $this->db->SELECT("students.firstname,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, students.guardian_name, students.guardian_relation, students.guardian_phone, classes.class, sessions.id, sections.section")->join("student_session", "students.id = student_session.student_id")->join("classes", "student_session.class_id = classes.id")->join("sections", "student_session.section_id = sections.id")->join("sessions", "student_session.session_id = sessions.id")->group_by("students.id")->get("students");
 
        return $query->result_array();
    }

    function studentSessionDetails($id) {

        $query = $this->db->query("SELECT min(sessions.session) as start , max(sessions.session) as end, min(classes.class) as startclass, max(classes.class) as endclass from sessions join student_session on (sessions.id = student_session.session_id) join classes on (classes.id = student_session.class_id) where student_session.student_id = " . $id);



        return $query->row_array();
    }

    public function searchAdmissionDetails($class_id, $year) {

        if (!empty($year)) {

            $data = array('year(admission_date)' => $year);
        } else {
            $data = array('student_session.class_id' => $class_id);
        }


        $query = $this->db->SELECT("students.firstname,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, students.guardian_name, students.guardian_relation, students.guardian_phone, classes.class, sessions.id, sections.section")->join("student_session", "students.id = student_session.student_id")->join("classes", "student_session.class_id = classes.id")->join("sections", "student_session.section_id = sections.id")->join("sessions", "student_session.session_id = sessions.id")->where($data)->group_by("students.id")->get("students");

        return $query->result_array();
    }

    public function admissionYear() {

        $query = $this->db->SELECT("distinct(year(admission_date)) as year")->get("students");

        return $query->result_array();
    }

    public function getStudentSession($id) {

        $query = $this->db->query("SELECT  max(sessions.id) as student_session_id, max(sessions.session) as session from sessions join student_session on (sessions.id = student_session.session_id)  where student_session.student_id = " . $id);

        return $query->row_array();
    }

    public function valid_student_roll() {
        $roll_no = $this->input->post('roll_no');
        $student_id = $this->input->post('studentid');
        $class = $this->input->post('class_id');

        if ($roll_no != "") {

            if (!isset($student_id)) {
                $student_id = 0;
            }

            if ($this->check_rollno_exists($roll_no, $student_id, $class)) {
                $this->form_validation->set_message('check_exists', 'Roll Number should be unique at Class level');
                return FALSE;
            } else {
                return TRUE;
            }
        }
        return TRUE;
    }

    function check_rollno_exists($roll_no, $student_id, $class) {

        if ($student_id != 0) {
            $data = array('students.id != ' => $student_id, 'student_session.class_id' => $class, 'students.admission_no' => $roll_no);
            $query = $this->db->where($data)->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
         else {
                $whereArray = [];
                $whereArray['admission_no']  = $roll_no;
            if($class != null)
               $whereArray['class_id']  = $class;
                
            $this->db->where($whereArray);
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        /*else {

            $this->db->where(array('class_id' => $class, 'admission_no' => $roll_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }*/

    }

    function import_check_rollno_exists($roll_no) {

            $this->db->where(array('admission_no' => $roll_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
    }

    function gethouselist() {

        $query = $this->db->where("is_active", "yes")->get("school_houses");

        return $query->result_array();
    }

    function disableStudent($id, $data) {

        $this->db->where("id", $id)->update("students", $data);
    }

    function getdisableStudent() {

        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'no');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    function disablestudentByClassSection($class, $section) {

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "no");
        if ($class != null) {
            $this->db->where('student_session.class_id', $class);
        }
        if ($section != null) {
            $this->db->where('student_session.section_id', $section);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    function disablestudentFullText($searchterm) {
        $userdata = $this->customlib->getUserData();
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'no');
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        } else {
            $this->db->group_start();
            $this->db->like('students.firstname', $searchterm);
            $this->db->or_like('students.lastname', $searchterm);
            $this->db->or_like('students.guardian_name', $searchterm);
            $this->db->or_like('students.adhar_no', $searchterm);
            $this->db->or_like('students.samagra_id', $searchterm);
            $this->db->or_like('students.roll_no', $searchterm);
            $this->db->or_like('students.admission_no', $searchterm);
            $this->db->group_end();
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getClassSection($id) {

        $query = $this->db->SELECT("*")->join("sections", "class_sections.section_id = sections.id")->where("class_sections.class_id", $id)->get("class_sections");
        return $query->result_array();
    }

    public function getStudentClassSection($id) {

        $query = $this->db->SELECT("students.firstname,students.id,students.lastname,students.image,student_session.section_id")->join("student_session", "students.id = student_session.student_id")->where("student_session.class_id", $id)->where("students.is_active", "yes")->get("students");

        return $query->result_array();
    }

    public function getStudentsByArray($array) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,students.blood_group ,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.mother_name,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where_in('students.id', $array);
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result();
    }
    
    
     public function getStudentsIdByAdmissionNum($admission_num) {
        $this->db->select('students.id')->from('students');
        $this->db->where('students.admission_no', $admission_num);
        $this->db->where('students.firstname !=',null);
        $query = $this->db->get();
        return $query->result();
    }

}

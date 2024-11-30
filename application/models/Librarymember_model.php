<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Librarymember_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($getTotalCount = FALSE, $limit = 10, $start = 0) {

        $query = ""
                . "SELECT 
    libarary_members.id as `lib_member_id`,
    libarary_members.library_card_no,
    libarary_members.member_type,
    students.admission_no,
    students.firstname,
    students.lastname,
    students.guardian_phone,
    null as `teacher_name`,
    null as `teacher_email`,
    null as `teacher_sex`,
    null as `teacher_phone`,
	classes.class as `class_name`,
        sections.section as `section`
FROM
    `libarary_members`
        INNER JOIN
    students ON libarary_members.member_id = students.id
INNER JOIN student_session ON student_session.student_id = students.id
INNER JOIN classes ON student_session.class_id = classes.id
INNER JOIN sections ON sections.id= student_session.section_id
WHERE
    libarary_members.member_type = 'student'
        
UNION SELECT 
    libarary_members.id as `lib_member_id`,
    libarary_members.library_card_no,
    libarary_members.member_type,
    null,
    null,
    null,
    null,
    staff.name,
    staff.surname,
    staff.email,
    staff.contact_no,
	null as `class_name`,
       null as `section`
FROM
    `libarary_members`
        INNER JOIN
    staff ON libarary_members.member_id = staff.id
WHERE
    libarary_members.member_type = 'teacher'";   
        if ($getTotalCount) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($limit, $start);
            $query = $this->db->query($query);
            
            return $query->result_array();
        }
    
}

    public function checkIsMember($member_type, $id) {
        $this->db->select()->from('libarary_members');

        $this->db->where('libarary_members.member_id', $id);
        $this->db->where('libarary_members.member_type', $member_type);

        $query = $this->db->get();

        $result = $query->num_rows();
        if ($result > 0) {
            $row = $query->row();
            $book_lists = $this->bookissue_model->book_issuedByMemberID($row->id);
            return $book_lists;
        } else {
            return false;
        }
    }

    public function getByMemberID($id = null) {
        $this->db->select()->from('libarary_members');
        if ($id != null) {
            $this->db->where('libarary_members.id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            $result = $query->row();
            if ($result->member_type == "student") {
                $return = $this->getStudentData($result->id);
            } else {
                $return = $this->getTeacherData($result->id);
            }
            return $return;
        }
    }

    function getTeacherData($id) {
        $this->db->select('libarary_members.id as `lib_member_id`,libarary_members.library_card_no,libarary_members.member_type,staff.*');
        $this->db->from('libarary_members');
        $this->db->join('staff', 'libarary_members.member_id = staff.id');        
        $this->db->where('libarary_members.id', $id);

        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    function getStudentData($id) {
        $this->db->select('libarary_members.id as `lib_member_id`,libarary_members.library_card_no,libarary_members.member_type,students.*');
        $this->db->from('libarary_members');
        $this->db->join('students', 'libarary_members.member_id = students.id');
        $this->db->where('libarary_members.id', $id);

        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    function surrender($id) {
        $this->db->where('id', $id);
        $this->db->delete('libarary_members');
        $this->db->where('member_id', $id);
        $this->db->delete('book_issues');
        return true;
    }

}

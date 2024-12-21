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
    public function get_old($getTotalCount = FALSE, $limit = 10, $start = 0, $library_card_no = '') {
        // Define the common query structure
        $base_query = "
            SELECT 
                libarary_members.id AS lib_member_id,
                libarary_members.library_card_no,
                libarary_members.member_type,
                students.admission_no,
                students.firstname,
                students.lastname,
                students.guardian_phone,
                NULL AS teacher_name,
                NULL AS teacher_email,
                NULL AS teacher_sex,
                NULL AS teacher_phone,
                classes.class AS class_name,
                sections.section AS section
            FROM
                libarary_members
            INNER JOIN
                students ON libarary_members.member_id = students.id
            INNER JOIN 
                student_session ON student_session.student_id = students.id
            INNER JOIN 
                classes ON student_session.class_id = classes.id
            INNER JOIN 
                sections ON sections.id = student_session.section_id
            WHERE
                libarary_members.member_type = 'student'
            GROUP BY students.id
        ";

        $teacher_query = "
            SELECT 
                libarary_members.id AS lib_member_id,
                libarary_members.library_card_no,
                libarary_members.member_type,
                NULL,
                NULL,
                NULL,
                NULL,
                staff.name,
                staff.surname,
                staff.email,
                staff.contact_no,
                NULL AS class_name,
                NULL AS section
            FROM
                libarary_members
            INNER JOIN
                staff ON libarary_members.member_id = staff.id
            WHERE
                libarary_members.member_type = 'teacher'
        ";

        // Add library_card_no condition if not empty
        if (!empty($library_card_no)) {
            $base_query .= " AND libarary_members.library_card_no = " . $this->db->escape($library_card_no);
            $teacher_query .= " AND libarary_members.library_card_no = " . $this->db->escape($library_card_no);
        }

        // Combine both queries
        $final_query = $base_query . " UNION " . $teacher_query;

        // If the function is called to fetch the total count
        if ($getTotalCount) {
            $count_query = "SELECT COUNT(*) AS total_count FROM ($final_query) AS combined_query";
            $query = $this->db->query($count_query); // Run the count query
            $result = $query->row();
            return $result->total_count; // Return the total count
        } else {
            // Add the LIMIT clause for pagination
            $paginated_query = $final_query . " LIMIT ?, ?";
            $query = $this->db->query($paginated_query, array((int)$start, (int)$limit)); // Bind parameters
            // dd($this->db->last_query());
            return $query->result_array(); // Return the paginated results
        }
    }   

    public function get($getTotalCount = FALSE, $limit = 10, $start = 0, $search = '')
    {
        // Common query for students
        $this->db->select("
            libarary_members.id AS lib_member_id,
            libarary_members.library_card_no,
            libarary_members.member_type,
            students.admission_no,
            students.firstname,
            students.lastname,
            students.guardian_phone,
            NULL AS teacher_name,
            NULL AS teacher_email,
            NULL AS teacher_sex,
            NULL AS teacher_phone,
            classes.class AS class_name,
            sections.section AS section
        ", FALSE); // The FALSE ensures raw SQL without escaping.

        $this->db->from('libarary_members');
        $this->db->join('students', 'libarary_members.member_id = students.id', 'inner');

        // Subquery to get the latest session along with class_id and section_id
        $latest_session_subquery = "
            SELECT 
                student_id, 
                MAX(id) AS latest_session_id, 
                class_id, 
                section_id
            FROM student_session
            GROUP BY student_id
        ";

        $this->db->join("($latest_session_subquery) latest_session", 'latest_session.student_id = students.id', 'inner');
        $this->db->join('classes', 'latest_session.class_id = classes.id', 'inner');
        $this->db->join('sections', 'latest_session.section_id = sections.id', 'inner');

        // $this->db->join('classes', 'student_session.class_id = classes.id', 'inner');
        // $this->db->join('sections', 'sections.id = student_session.section_id', 'inner');
        $this->db->where('libarary_members.member_type', 'student');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('libarary_members.library_card_no', $search);
            $this->db->or_like('admission_no', $search);
            $this->db->or_like('libarary_members.id', $search);
            $this->db->or_like('libarary_members.member_type', $search);
            $this->db->or_where("CONCAT(classes.class, ' ( ', sections.section, ' )') LIKE '%$search%'", NULL, FALSE);

            // $this->db->or_where("CONCAT(classes.class, ' ', sections.section) LIKE '%$search%'", NULL, FALSE);
            $this->db->or_where("CONCAT(students.firstname, ' ', students.lastname) LIKE '%$search%'", NULL, FALSE);
            $this->db->group_end();
        }

        $this->db->group_by('students.id');
        $student_query = $this->db->get_compiled_select();

        $this->db->select("
            libarary_members.id AS lib_member_id,
            libarary_members.library_card_no,
            libarary_members.member_type,
            NULL AS admission_no,
            NULL AS firstname,
            NULL AS lastname,
            NULL AS guardian_phone,
            staff.name AS teacher_name,
            staff.surname AS teacher_surname,
            staff.email AS teacher_email,
            staff.contact_no AS teacher_phone,
            NULL AS class_name,
            NULL AS section
        ", FALSE);

        $this->db->from('libarary_members');
        $this->db->join('staff', 'libarary_members.member_id = staff.id', 'inner');
        $this->db->where('libarary_members.member_type', 'teacher');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('libarary_members.library_card_no', $search);
            $this->db->or_like('libarary_members.id', $search);
            $this->db->or_like('libarary_members.member_type', $search);
            $this->db->or_like('staff.name', $search);
            $this->db->group_end();
        }

        $teacher_query = $this->db->get_compiled_select();

        // Combine both queries
        $final_query = $student_query . " UNION " . $teacher_query;

        // If the function is called to fetch the total count
        if ($getTotalCount) {
            $count_query = "SELECT COUNT(*) AS total_count FROM ($final_query) AS combined_query";
            $query = $this->db->query($count_query);
            $result = $query->row();
            // dd($this->db->last_query());
            return $result->total_count;
        } else {
            // Add pagination
            $paginated_query = $final_query . " LIMIT ?, ?";
            $query = $this->db->query($paginated_query, array((int)$start, (int)$limit));
            // dd($this->db->last_query());
            return $query->result();

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

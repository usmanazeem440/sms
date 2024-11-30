<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Class_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null, $classteacher = null) {

        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        $carray = array();

        if (isset($role_id) && ($userdata["role_id"] == 5) && ($userdata["class_teacher"] == "yes")) {
            if ($classteacher == 'yes') {

                $classlist = $this->customlib->getclassteacher($userdata["id"]);
            } else {

                $classlist = $this->customlib->getClassbyteacher($userdata["id"]);
            }
        } else {

            $this->db->select()->from('classes');
            if ($id != null) {
                $this->db->where('id', $id);
            } else {
                $this->db->order_by('id');
            }
            $query = $this->db->get();
            if ($id != null) {
                $classlist = $query->row_array();
            } else {
                $classlist = $query->result_array();
            }
        }

        return $classlist;
    }


    public function getClassData($id){
        $this->db->select("*");
        $this->db->where('id', $id);
        $query = $this->db->get('classes');
        return $query->row_array();
    }


    public function getClassesWithSubjects($classteacher = null){
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        $carray = array();
        $finalArray =  array();

            $this->db->select()->from('classes');


            $this->db->order_by('id');
            $query = $this->db->get();

            $classlist = $query->result_array();



            foreach ($classlist as $class){
                $tempArray = array();
                $tempArray['class'] = $class;

                //get Subjects from db;

                $this->db->select("subjects.id, subjects.name")->from("subjects, teacher_subjects, classes");
                $this->db->where("subjects.id", "teacher_subjects.subject_id");
                $this->db->where("classes.id", "teacher_subjects.class_id");
                $this->db->where("classes.id", $class['id']);

                $tempQuery = $this->db->get();

                $tempArray['subjects'] = $tempQuery->result_array();


                $finalArray[] = $tempArray;
            }

            return $finalArray;


    }



    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->delete('classes'); //class record delete.

        $this->db->where('class_id', $id);
        $this->db->delete('class_sections'); //class_sections record delete.

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('classes', $data);
        } else {
            $this->db->insert('classes', $data);
        }
    }



    public function getClassesTag(){
        $this->db->select('Tag');
        $query = $this->db->get('classes');

        return  $query->result_array();
    }

    function check_data_exists($data) {
        $this->db->where('class', $data);

        $query = $this->db->get('classes');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function getStudentByStudentId($id) {
        $this->db->select('classes.Tag')->from('student_session');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_id', $id);
        //$this->db->where('session_id', $this->current_session);
        //$this->db->order_by('id');
        $query = $this->db->get();
            return  $query->result_array();
        //return $query->row_array();
    }



    public function class_exists($str) {

        $class = $this->security->xss_clean($str);
        $res = $this->check_data_exists($class);

        if ($res) {
            $pre_class_id = $this->input->post('pre_class_id');
            if (isset($pre_class_id)) {
                if ($res->id == $pre_class_id) {
                    return TRUE;
                }
            }
            $this->form_validation->set_message('class_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getClassTeacher() {

        $query = $this->db->select('staff.*,class_teacher.id as ctid,classes.class,classes.id as class_id,sections.id as section_id,sections.section')->join("staff", "class_teacher.staff_id = staff.id")->join("classes", "class_teacher.class_id = classes.id")->join("sections", "class_teacher.section_id = sections.id")->group_by("class_teacher.class_id, class_teacher.section_id")->get("class_teacher");

        return $query->result_array();
    }

}

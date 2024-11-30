<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function add_invoices($data) {

        $this->db->insert("invoices", $data);
        return $this->db->insert_id();
    }

    public function update_invoices_number($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('invoices', $data);
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null, $guardian_id = null) {

        $this->db->select("invoices.*, students.parent_id,students.guardian_id")->from('invoices');
        $this->db->join('(select students.parent_id,students.guardian_id  from students group by parent_id) as students', ' invoices.parent_id = students.parent_id');
        if ($guardian_id != '')
            $this->db->where('students.guardian_id', $guardian_id);
        if ($id != null) {
            $this->db->where('invoices.id', $id);
        } else {
            $this->db->order_by('invoices.id');
        }
        // $this->db->group_by('students.parent_id');
        $query = $this->db->get();
        if ($id != null) {
            $invoiceslist = $query->row_array();
        } else {
            $invoiceslist = $query->result_array();
        }


        return $invoiceslist;
    }

    public function getInvoicesList($id = null, $guardian_id = null, $getTotal = false, $limit = 10, $start = 0) {

        $this->db->select("invoices.*, students.parent_id,students.guardian_id")->from('invoices');
        $this->db->join('(select students.parent_id,students.guardian_id  from students group by parent_id) as students', ' invoices.parent_id = students.parent_id');
        if ($guardian_id != '')
            $this->db->where('students.guardian_id', $guardian_id);
        if ($id != null) {
            $this->db->where('invoices.id', $id);
        }
        $this->db->order_by('invoices.invoice_date', 'desc');

        // $this->db->group_by('students.parent_id');
        if ($getTotal) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($limit, $start);
            $query = $this->db->get();
            if ($id != null) {
                $invoiceslist = $query->row_array();
            } else {
                $invoiceslist = $query->result_array();
            }
        }



        return $invoiceslist;
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function getInvoiceDetails($invoiceId = null) {

        $this->db->select(
                "invoices.*,"
                . "invoices_details.student_fees_master_id,"
                . "invoices_details.fee_groups_feetype_id,"
                . "invoices_details.amount,"
                . "invoices_details.discount,"
                . "invoices_details.discount_percent,"
                . "invoices_details.tax,"
                . "invoices_details.tax_percent,"
                . "invoices_details.total_amount,"
                . "invoices_details.date_created,"
                //      . "parents.*,"
        )->from('invoices');
        $this->db->join('invoices_details', ' invoices.id = invoices_details.invoice_id');
      
        if ($invoiceId != null) {
            $this->db->where('invoices.id', $invoiceId);
        } else {
            $this->db->order_by('invoices.id');
        }
        // $this->db->group_by('students.parent_id');
        $query = $this->db->get()->result_array();
        //echo $this->db->last_query();
        // die;
        // Loop through the products array
        foreach ($query as $i => $invoices) {

            $this->db->where('parent_id', $invoices['parent_id']);
            $parent_query = $this->db->get('students')->row_array();
            $query[$i]['parents'] = $parent_query;

            $sql = 'select 
                            students.*, classes.class,sections.section from student_session 
                        INNER JOIN 
                            students ON students.id = student_session.student_id
                        INNER JOIN  
                            student_fees_master ON  student_session.id = student_fees_master.student_session_id  
                        INNER JOIN 
                            classes ON classes.id = student_session.class_id
                        INNER JOIN 
                            sections ON sections.id = student_session.section_id
                            
                        WHERE
                            student_fees_master.id = ' . $invoices['student_fees_master_id'];
            $student_query = $this->db->query($sql);
            $query[$i]['students'] = $student_query->result_array();
            $query[$i]['student_discount_fee'] = $this->feediscount_model->getStudentIndiviualDiscounts($query[$i]['students'][0]['admission_no']);

            $this->db->where('id', $invoices['fee_groups_feetype_id']);
            $fee_groups_feetype_query = $this->db->get('fee_groups_feetype')->result_array();

            foreach ($fee_groups_feetype_query as $j => $fee_groups_feetype_row) {
                $this->db->where('id', $fee_groups_feetype_row['fee_groups_id']);
                $fee_groups_query = $this->db->get('fee_groups')->row_array();
                $fee_groups_feetype_query[$j]['fee_groups'] = $fee_groups_query;

                $this->db->where('id', $fee_groups_feetype_row['feetype_id']);
                $feetype_query = $this->db->get('feetype')->row_array();
                $fee_groups_feetype_query[$j]['feetype'] = $feetype_query;

                $this->db->where('id', $fee_groups_feetype_row['feetype_id']);
                $feetype_query = $this->db->get('feetype')->row_array();
                $fee_groups_feetype_query[$j]['feetype'] = $feetype_query;
            }
            $query[$i]['fee_groups_feetype'] = $fee_groups_feetype_query;
        }
        return $query;
    }

    public function getStudentFeesArray($ids = null, $student_session_id) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.payment_mode,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id where feemasters.id IN (" . $ids . ")";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getTotalCollectionBydate($date) {
        $sql = "SELECT sum(amount) as `amount`, SUM(amount_discount) as `amount_discount` ,SUM(amount_fine) as `amount_fine` FROM `student_fees` where date=" . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getStudentFees($id = null) {
        $this->db->select('feecategory.category,student_fees.id as `invoiceno`,student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->where('student_session.student_id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFeeByInvoice($id = null) {
        $this->db->select('feecategory.category,student_fees.date,student_fees.payment_mode,student_fees.id as `student_fee_id`,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,feetype.type,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.rte')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTodayStudentFees() {
        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,students.firstname,students.lastname,students.admission_no,students.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date', $this->current_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function remove($id, $sub_invoice) {
        $this->db->where('id', $id);
        $q = $this->db->get('student_fees_deposite');
        if ($q->num_rows() > 0) {
            $result = $q->row();
            $a = json_decode($result->amount_detail, true);
            unset($a[$sub_invoice]);
            if (!empty($a)) {
                $data['amount_detail'] = json_encode($a);
                $this->db->where('id', $id);
                $this->db->update('student_fees_deposite', $data);
            } else {
                $this->db->where('id', $id);
                $this->db->delete('student_fees_deposite');
            }
        }
    }

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_fees', $data);
        } else {
            $this->db->insert('student_fees', $data);
            return $this->db->insert_id();
        }
    }

    public function getDueStudentFees($feegroup_id = null, $fee_groups_feetype_id = null, $class_id = null, $section_id = null) {

        $query = "SELECT IFNULL(student_fees_deposite.id, 0) as student_fees_deposite_id, IFNULL(student_fees_deposite.fee_groups_feetype_id, 0) as fee_groups_feetype_id, IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail, student_fees_master.id as `fee_master_id`,fee_groups_feetype.feetype_id ,fee_groups_feetype.amount,fee_groups_feetype.due_date, `classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender` FROM `students` JOIN `student_session` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN student_fees_master on student_fees_master.student_session_id=student_session.id and student_fees_master.fee_session_group_id=" . $this->db->escape($feegroup_id) . " LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=" . $this->db->escape($fee_groups_feetype_id) . "  INNER JOIN fee_groups_feetype on fee_groups_feetype.id = " . $this->db->escape($fee_groups_feetype_id) . " WHERE `student_session`.`session_id` = " . $this->current_session . " AND 
            `students`.`is_active` = 'yes'  AND 
            `student_session`.`class_id` = " . $this->db->escape($class_id) . " AND `student_session`.`section_id` = " . $this->db->escape($section_id) . " ORDER BY `students`.`id`";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudent($class_id = null, $section_id = null, $student_id = null) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category,student_fees.description FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.payment_mode,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id,student_fees.description  from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id JOIN feetype ON feemasters.feetype_id = feetype.id JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudentSection($class_id = null, $section_id = null, $student_session_id = null) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . " ) as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeesByClass($class_id = null, $section_id = null, $student_id = null) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeeBetweenDate($start_date, $end_date) {

        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,students.rte,classes.class,sections.section,students.firstname,students.lastname,students.admission_no,students.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date >=', $start_date);
        $this->db->where('student_fees.date <=', $end_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudentTotalFee($class_id, $student_session_id) {
        $query = "SELECT a.totalfee,b.fee_deposit,b.payment_mode  FROM ( SELECT COALESCE(sum(amount),0) as totalfee FROM `feemasters` WHERE session_id =$this->current_session and class_id=" . $this->db->escape($class_id) . ") as a, (select COALESCE(sum(amount),0) as fee_deposit,payment_mode from student_fees WHERE student_session_id =" . $this->db->escape($student_session_id) . ") as b";
        $query = $this->db->query($query);
        return $query->row();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function getStudentFeeDetails($invoiceId = null) {
        ini_set('max_execution_time', 0);
        // phpinfo();
        // die;
        $tax_payable = $this->setting_model->getFeeTax();
        $sql = "SELECT 
                            students.id as student_id,
                            students.parent_id,
                            students.guardian_id,
                            students.admission_no,
                            classes.class,
                            sections.section,
                            student_fees_master.id as student_fees_master_id,
                            fee_groups_feetype.id AS `fee_groups_feetype_id`,
                            fee_groups_feetype.amount,
                            
                            student_fees_master.student_session_id,
                            student_fees_master.fee_session_group_id,
                            
                            
                            fee_groups_feetype.due_date,
                            fee_groups_feetype.fee_groups_id,
                            fee_groups.name,
                            fee_groups_feetype.feetype_id,
                            feetype.code,
                            feetype.is_taxable,
                            feetype.type
                        FROM
                            student_session
                                INNER JOIN
                            students ON students.id = student_session.student_id
                                INNER JOIN
                            student_fees_master ON student_session.id = student_fees_master.student_session_id
                                INNER JOIN
                            classes ON classes.id = student_session.class_id
                                INNER JOIN
                            sections ON sections.id = student_session.section_id
                                INNER JOIN
                            fee_session_groups ON fee_session_groups.id = student_fees_master.fee_session_group_id
                                INNER JOIN
                            fee_groups_feetype ON fee_groups_feetype.fee_session_group_id = fee_session_groups.id
                                INNER JOIN
                            fee_groups ON fee_groups.id = fee_groups_feetype.fee_groups_id
                                INNER JOIN
                            feetype ON feetype.id = fee_groups_feetype.feetype_id
                            WHERE students.admission_no <>  ''
                                AND sections.id NOT IN (28 , 29, 1)
                                AND classes.id IN (8 , 9, 10, 11,  12,  13,  14,  15,  16, 17, 18,  19,  20,  21, 22)
                                AND fee_groups_feetype.due_date  < '".date('Y-m-d')."'
                                AND (student_fees_master.is_invoiced is null or student_fees_master.is_invoiced = 0)
                            ORDER BY fee_groups_feetype.due_date ASC";
                        $student_query = $this->db->query($sql);
                        $query = $student_query->result_array();
                        // dd($this->db->last_query());
                        $studentdata = [];

        // Loop through the products array
        foreach ($query as $i => $StudentData) {
            $studentdata[$StudentData['due_date']][$StudentData['student_id']]['data'][] = [
                'student_fees_master_id' => $StudentData['student_fees_master_id'],
                'fee_groups_feetype_id' => $StudentData['fee_groups_feetype_id'],
                'amount' => $StudentData['amount'],
                'code' => $StudentData['code'],
                'name' => $StudentData['name'],
                'is_taxable' => $StudentData['is_taxable'],
                'type' => $StudentData['type'],
                'due_date' => $StudentData['due_date'],
            ];
            $studentdata[$StudentData['due_date']][$StudentData['student_id']]['parent_id'] = $StudentData['parent_id'];
            $studentdata[$StudentData['due_date']][$StudentData['student_id']]['admission_no'] = $StudentData['admission_no'];
        }
        $count = 1;
        foreach ($studentdata as $dueDate => $dueDateArray) {

            foreach ($dueDateArray as $student_id => $studentdataArray) {

                $parentId = $studentdataArray['parent_id'];
                $admission_no = $studentdataArray['admission_no'];
                $amount = 0.00;
                $date = $dueDate;
                $dataInvoices = [
                    "invoice_amount" => $amount,
                    "parent_id" => $parentId,
                    "admission_no" => $admission_no,
                    "invoice_date" => $date,
                    "status" => "active"
                ];
                $lastInsertId = $this->invoices_model->add_invoices($dataInvoices);
                $invoiceAmount = 0;
                foreach ($studentdataArray['data'] as $dateKeys => $dateArray) {
                    if($dateArray['type'] == "Tuition Fee"  || $dateArray['type'] == "Saudi Student") {
                        $discountPercent = $this->getStudentIndiviualDiscounts($dateArray['admission_no']);
                    } else {
                        $discountPercent = 0.00;
                    }
                    $discountAmoutnt = $discountPercent*$dateArray['amount'];
                    $amountPayable = $dateArray['amount']-$discountAmoutnt;
                   
                    if(trim($dateArray['is_taxable']) == 'YES'){
                        $taxAmount = ((($amountPayable) * $tax_payable['fee_tax']) /100);
                        $taxPayableDb = $tax_payable['fee_tax'];
                    }else{ 
                        $taxAmount = 0;
                        $taxPayableDb = 0;
                    }
                    $dataInvoiceData = [];
                    $dataInvoiceData["invoice_id"] = $lastInsertId;
                    $dataInvoiceData["student_fees_master_id"] = $dateArray['student_fees_master_id'];
                    $dataInvoiceData["fee_groups_feetype_id"] = $dateArray['fee_groups_feetype_id'];
                    $dataInvoiceData["amount"] = $dateArray['amount'];
                    $dataInvoiceData["discount"] = $discountAmoutnt;
                    $dataInvoiceData["discount_percent"] = $discountPercent;
                    $dataInvoiceData["tax"] = $taxAmount;
                    $dataInvoiceData["tax_percent"] = $taxPayableDb;
                    $dataInvoiceData["total_amount"] = ($amountPayable+$taxAmount);
                    $this->db->insert('invoices_details', $dataInvoiceData);
                    $invoiceAmount += $dataInvoiceData["total_amount"];
                            
                    $this->db->where('id', $dateArray['student_fees_master_id']);
                    $this->db->update('student_fees_master', ["is_invoiced"=>1, "invoice_number"=>$lastInsertId]);
//                	echo $this->db->last_query();
//di//	echo $this->db->last_query();
//die;e;
                
                }
                $dataInvoicesNumber = [
                    "invoice_number" => $lastInsertId,
                    "invoice_amount" => $invoiceAmount,
                ];
                $this->invoices_model->update_invoices_number($lastInsertId, $dataInvoicesNumber);
                
                
                echo $count . " -- " . $student_id . " recepit generated successfully <br>";
                if($count>5000)
                    die;
                $count++;
            }
            
        }
        die;
        // return $query;
    }
    
    
    public function getStudentIndiviualDiscounts($admission_no){
        $this->db->select("student_indiviual_discount.discount_ids");
        $this->db->where("student_admission_no", $admission_no);
        $query = $this->db->get("student_indiviual_discount");

        $totatDiscountAmount = 0.0;

        if(count($query->result_array()) > 0){
            $tempQuery =$query->result_array();
            $ids = $tempQuery[0]["discount_ids"];
            $idsArray = explode(',', $ids);

            $amountArray = array();

            foreach ($idsArray as $id){
                   $this->db->select("fees_discounts.amount");
                   $this->db->where("id", $id);
                   $query = $this->db->get("fees_discounts");
                   $res = $query->result_array();
                   if(count($res) > 0){
                      $totatDiscountAmount+=(int)$res[0]["amount"];  
                   }
                    
                   //$totatDiscountAmount+=(int)$query->result_array()[0]["amount"];
            }
        }
        return $totatDiscountAmount;



    }

}

<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-flask"></i> <?php echo $this->lang->line('std_selected_books'); ?>
        </h1>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">                    
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('admin/BookStore/searchParent') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php //echo $this->lang->line('search_by_student_name'); ?>
                                            Search By Student    
                                        </label><small class="req"> *</small>
                                        <input type="text" name="parent_name" value="<?= $search_text ?>" class="form-control" placeholder="Search By Student Name, Father Name, Roll Number, Enroll Number  Etc."/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>
                    </form>
                </div>


                <?php
                if (isset($students)) {
                    ?>

                <div class="box box-info" id="duefee">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label"><?php echo $this->lang->line('parent_name'); ?></div>
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                            <tr>

                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                <th><?php echo $this->lang->line('guardian_id'); ?></th>
                                <th>Birth Place</th>
                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                <th><?php echo $this->lang->line('class'); ?></th>
                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                <th><?php echo $this->lang->line('gender'); ?></th>
                                <th><?php echo $this->lang->line('category'); ?></th>
                                <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                <th class="text text-right"><?php echo $this->lang->line('action'); ?> </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php  foreach($students as $student){ ?>
                                <tr>
                                    <td><?php echo $student['admission_no']; ?></td>
                                     <td><?php echo $student['guardian_id']; ?></td>
                                    <td><?php if($student['birth_place'] != null){ echo $student['birth_place']; } else { echo "N/A";}  ?></td>
                                    <td>
                                        <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $student['firstname'] . " " . $student['lastname']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                    <td><?php echo $student['father_name']; ?></td>
                                    <td><?php  if($student["dob"] != null)
                                    { echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob'])) ; }?></td>
                                    <td><?php echo $student['gender']; ?></td>
                                    <td><?php echo $student['category']; ?></td>
                                    <td class="phone"><?php echo $student['mobileno']; ?></td>
                                    <td class="text text-right">
                                        <a href="<?php echo site_url('admin/BookStore/placeOrderByStudent/') . $student['id']; ?>" class="btn btn-xs btn-info myCollectFeeBtn" title="<?php echo $this->lang->line('std_place_order'); ?>">
                                            <?php echo $this->lang->line('std_place_order'); ?>
                                        </a>
                                    </td>
                                </tr>

                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
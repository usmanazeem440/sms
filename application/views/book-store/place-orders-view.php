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
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('search_by_student'); ?></label><small class="req"> *</small>
                                        <input type="text" name="parent_name" class="form-control" placeholder="Search by student name Or Id"/>
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

                                <th><?php echo $this->lang->line('name'); ?></th>
                                <th><?php echo $this->lang->line('birth_place'); ?></th>
                                   <th><?php echo $this->lang->line('gender'); ?></th>
                                <th><?php echo $this->lang->line('Dob'); ?></th>
                                <th class="text text-right"><?php echo $this->lang->line('action'); ?> </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php  foreach($students as $student){ ?>
                                <tr>
                                    <td class="text">
                                        <?php echo $student->firstname ?>
                                    </td>
                                    <td  class="text">
                                        <?php echo $student->birth_place ?>
                                    </td>
                                    <td  class="text">
                                        <?php echo $student->gender ?>
                                    </td>
                                    <td  class="text">
                                        <?php echo $student->dob ?>
                                    </td>
                                    <td  class="text text-right">
                                        <a href="<?php echo site_url('admin/BookStore/placeOrderByStudent/').$student->id;?>"  class="btn btn-xs btn-info myCollectFeeBtn " title="<?php echo $this->lang->line('std_place_order'); ?>"> <?php echo $this->lang->line('std_place_order'); ?> </a>
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
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">

</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo "Apply Discount On Student" ?> <small><?php echo $this->lang->line('student1'); ?></small></h1>
        <?php if ($this->session->flashdata('msg')) { ?>
                <?php echo $this->session->flashdata('msg') ?>
        <?php } ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Student Details"; ?></h3>
                    </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <div class="pull-left">
                                    <label for="exampleInputEmail1"><?php echo "First Name" ?>: </label>
                                    <span><?php echo $student['firstname'] ?></span>
                                </div>

                                <div class="pull-right">
                                    <label for="exampleInputEmail1"><?php echo "Last Name" ?>: </label>
                                    <span><?php echo $student['lastname'] ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <br/>
                                <label for="exampleInputEmail1"><?php echo "Admission No: " ?></label>
                                <span><?php echo $student["admission_no"] ?></span>
                            </div>
                            <div class="form-group">
                                <br/>
                                <label for="exampleInputEmail1"><?php echo "Father Name: " ?></label>
                                <span><?php echo $student["father_name"] ?></span>
                            </div>

                            <div class="form-group">
                                <br/>
                                <label for="exampleInputEmail1"><?php echo "Father Phone No: " ?></label>
                                <span><?php echo $student["father_phone"] ?></span>
                            </div>
                        </div>
                </div>

            </div>
            <div class="col-md-8">

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_discount_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <form action="<?php echo site_url('admin/feediscount/addIndiviualFee') ?>"  method="post">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="admission_number" value="<?php echo $student["admission_no"] ?>"/>
                            <input type="hidden" name="student_id" value="<?php echo $student["id"] ?>"/>
                            <div class="table-responsive mailbox-messages">
                                <div class="download_label"><?php echo $this->lang->line('fees_discount_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('name'); ?>
                                        <th><?php echo $this->lang->line('discount_code'); ?>

                                        <th><?php echo $this->lang->line('amount'); ?>(%)
                                        </th>

                                        <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($feediscountList as $feediscount) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $feediscount['name'] ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $feediscount['code'] ?>

                                            </td>

                                            <td class="mailbox-name">
                                                <?php echo $feediscount['amount'] ?>
                                            </td>


                                            <td class="mailbox-date pull-right"">

                                            
                                                <input type="checkbox"

                                                       <?php

                                                        foreach ($previousDiscounts as $dd){
                                                            if($dd == $feediscount["id"]){
                                                                echo "checked";
                                                            }
                                                        }

                                                       ?>

                                                       name="fee_discount[]" value="<?php echo $feediscount["id"]; ?>"/>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    </tbody>
                                </table><!-- /.table -->



                            </div><!-- /.mail-box-messages -->
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
</div>

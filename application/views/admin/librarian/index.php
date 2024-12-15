<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('library'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">  
        <div class="col-md-3">
                               
                                    <form role="form" action="<?php echo site_url('admin/member') ?>" method="get" class="">
                                        <?php //echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="library_card_no" class="form-control"   placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>" value="<?= $library_card_no ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                            </div>     

            <div class="col-md-12">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">

                        <h3 class="box-title titlefix"><?php echo $this->lang->line('members'); ?></h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('members'); ?></div>
                            <table  class="table table-striped table-bordered table-hover example" id="members">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('member_id'); ?></th>
                                        <th><?php echo $this->lang->line('library_card_no'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('member_type'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>


                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="getdetails">
                                    <?php
                                        if (!empty($memberList)) {
                                            $count = 1;
                                            foreach ($memberList as $member) {

                                                if ($member['member_type'] == "student") {
                                                    $name = $member['firstname'] . " " . $member['lastname'];
                                                    $class_name = $member['class_name'];
                                                    $section = $member['section'];
                                                } else {
                                                    $email = $member['teacher_email'];
                                                    $name = $member['teacher_name'];
                                                    $sex = $member['teacher_sex'];
                                                    $class_name = $member['class_name'];
                                                    $section = $member['section'];
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $member['lib_member_id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $member['library_card_no']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $member['admission_no']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $name; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->lang->line($member['member_type']); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $class_name." ( ".$section." ) "; ?>
                                                    </td>
                                                    
                                                    <td class="mailbox-date pull-right">
                                                        <a href="<?php echo base_url(); ?>admin/member/issue/<?php echo $member['lib_member_id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('issue_return'); ?>">
                                                            <i class="fa fa-sign-out"></i>
                                                        </a>


                                                    </td>

                                                </tr>
                                                <?php
                                            }
                                            $count++;
                                        }
                                    ?>
                                                                        
                                </tbody>
                            </table>
                            <?php echo $links;?>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
</div>
<script type="text/javascript">
    
    $(document).ready(function(){
     
     // $.ajax({

     //        url: '<?php echo base_url(); ?>admin/member/ajax/',

     //        success: function (result) {

     //            //alert(result);

     //            $('#getdetails').html(result);

     //        }





     //    });
     });
</script>
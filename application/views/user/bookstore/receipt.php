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
                <input type="hidden" name="studetn_id" value="<?php echo $this->session->userdata["student"]["id"] ?>"/>
                <div class="box box-info">
                    <div class="box-body table-responsive">
                        <div >
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('std_order_id') ?></th>
                                        <th><?php echo $this->lang->line('std_no_books') ?></th>
                                        <th><?php echo $this->lang->line('std_total_price'); ?></th>
                                        <th><?php echo $this->lang->line('std_status'); ?></th>
                                        <th><?php echo $this->lang->line('action') ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($orders as $p) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $p['order_id'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['qty'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['price'] ?></td>
                                            <td class="mailbox-name">
                                            <?php if($p['status'] == '0'){ 
                                                echo "<p style='color:red'>Pending</p>";
                                            }
                                                 else{ 
                                                     echo "<p style='color:green'>Completed</p>"; 
                                                     } ?>
                                                 </td>
                                            <td>
                                            <a href="<?php echo base_url(); ?>user/order/view/<?php echo $p['order_id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                            </a>
                                                    
                                            </td>
                                        </tr>
                                   <?php } 
                                 ?>

                                </tbody>      
                            </table> 

                        </div>           
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>


<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-flask"></i> Pending Books Order<?php //echo $this->lang->line('std_selected_books'); ?>
        </h1>
    </section>   
    <section class="content">

        <div class="row">
            <div class="col-md-12">
            <?php if ($this->session->has_userdata('success')) { ?>
                                <div class="alert alert-success" role="alert">
                                    <?php 
                                        echo $this->session->userdata('success');
                                        $this->session->unset_userdata('success');
                                    ?>
                                </div>
                                
                            <?php } ?>

                            <?php if ($this->session->has_userdata('error')) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php 
                                        echo $this->session->userdata('error');
                                        $this->session->unset_userdata('error');
                                    ?>
                                </div>
                                
                            <?php } ?>
            </div>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body table-responsive">
                        <div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('std_order_id') ?></th>
                                         <th><?php echo $this->lang->line('admission_no') ?></th> 
                                         <th><?php echo $this->lang->line('guardian_id') ?></th> 
                                        <th><?php echo $this->lang->line('order_placed_by') ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        
                                        <th><?php echo $this->lang->line('std_no_books') ?></th>
                                        <th><?php echo $this->lang->line('std_total_price'); ?></th>
                                        <th><?php echo $this->lang->line('action') ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=0; foreach ($orders as $p) { ?>

                                        <tr>
                                            <td class="mailbox-name"><?php echo $p['order_id'] ?></td>
                                                  <td class="mailbox-name"><?php echo $p['admission_no'] ?></td> 
                                           <td class="mailbox-name"><?php echo $p['guardian_id'] ?></td> 
                                            <td class="mailbox-name"><?php echo $p['order_placed_by'] ?></td>
                                            <td class="mailbox-name"><?php echo ($p['created_at']); ?></td>
                                      
                                           
                                            <td class="mailbox-name"><?php echo $p['qty'] ?></td>
                                            <td class="mailbox-name"><?php echo number_format($p['price'],2); ?></td>
                                            <td>
                                            <a href="<?php echo base_url(); ?>admin/BookStore/viewOrder/<?php echo $p['order_id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                            </a>
                                            <input type="hidden" value="<?php echo $p['order_id'] ?>" id="order_id_<?php echo $i; ?>"/>
                                            <?php if($this->rbac->hasPrivilege('order','can_delete')) {?>
                                                <a href="#" class="btn btn-default btn-xs" data-toggle="modal" onclick="updateUrl(<?php echo $i; ?>)"  data-target="#myModal" title="Cancel Order">
                                                    <i class="fa fa-remove" style="color:red"></i>
                                                </a>
                                            <?php } ?>
                                                    
                                            </td>

                                        </tr>
                                   <?php $i++; }
                                 ?>

                                </tbody>      
                            </table> 

                        </div>           
                    </div>
                </form>
            </div>

                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header" style="background:linear-gradient(to right,#9e0303,#fd3618 100%);">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Alert</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure to cancel this order!</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                <a id="cancel_url" href="<?php echo base_url(); ?>admin/BookStore/cancelOrder/<?php echo $p['order_id'] ?>" type="button" class="btn btn-danger">Yes</a>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    updateUrl = function(id){
        document.getElementById("cancel_url").href="<?php echo base_url(); ?>admin/BookStore/cancelOrder/"+document.getElementById("order_id_"+id).value;
        $("#myModal").show();
    }
</script>


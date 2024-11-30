<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-flask"></i> <?php echo $this->lang->line('std_book_store'); ?>
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

            <form action="<?php echo base_url(); ?>user/BookStore/orderItems" method="POST">
                <div id="finalItems">

                </div>
                <div class="box box-info">
                    <div class="box-body table-responsive">
                        <div >
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('book_title') ?></th>
                                        <th><?php echo $this->lang->line('store_book_author') ?></th>
                                        <th><?php echo $this->lang->line('store_book_price'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($books as $p) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $p['title'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['author'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['price'] ?></td>
                                             <td  class="mailbox-date pull-right">
                                                    <input type="checkbox" onclick="updateBookValue(this)"  value="<?php echo $p['id'] ?>"/>
                                            </td>
                                        </tr>
                                   <?php } 
                                 ?>

                                </tbody>      
                            </table> 

                        </div>           
                    </div>
                    <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('std_proceed'); ?></button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    updateBookValue = function(cbk){
        if(cbk.checked){
            $('#finalItems').append('<input class="'+cbk.value+'" type="hidden"  name="checkedBooks[]" value="'+cbk.value+'" />');

        }else{
            $( "."+cbk.value ).remove();
        }


    }
</script>

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
            <form action="<?php echo base_url(); ?>user/BookStore/placeOrder" method="POST">
                <input type="hidden" name="studetn_id" value="<?php echo $this->session->userdata["student"]["id"] ?>"/>
                <div class="box box-info">
                    <div class="box-body table-responsive">
                        <div >
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('store_book_name') ?></th>
                                        <th><?php echo $this->lang->line('store_book_author') ?></th>
                                        <th><?php echo $this->lang->line('std_order_qty') ?> </th>
                                        <th><?php echo $this->lang->line('std_total_price') ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($orders as $p) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $p['title'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['author'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['quantity'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['price'] ?></td>
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


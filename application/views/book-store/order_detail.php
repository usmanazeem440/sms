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

                <form action="<?php echo base_url(); ?>admin/BookStore/completeOrder" method="POST">

                    <div class="box box-info">
                        <div class="box-body table-responsive">
                            <div >
                                <table class="table table-hover table-striped table-bordered example">
                                    <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('isbn'); ?></th>
                                        
                                        <th><?php echo $this->lang->line('store_book_name') ?></th>
                                        <th><?php echo $this->lang->line('store_book_author') ?></th>
                                        
                                        <th><?php echo $this->lang->line('std_order_qty') ?> </th>
                                        <th><?php echo $this->lang->line('order_sold_qty') ?> </th>
                                        <th><?php echo $this->lang->line('order_sold_qty1') ?> </th>
                                        <th><?php echo $this->lang->line('std_total_price') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $totalRemainingAmount = 0; $totalAmount = 0; ?>
                                    <?php foreach ($orders as $p) {  $remainingBooks = (int)($p['quantity']) - (int)($p['sold_quantity']);   ?>
                                        <tr>
                                            <input type="hidden" name="order_id" value="<?php echo $p['order_id'] ?>"/>
                                             <td class="mailbox-name"><?php echo $p['isbn'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['title'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['author'] ?></td>
                                           
                                            <td class="mailbox-name"><?php echo $p['quantity'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['sold_quantity'] ?></td>
                                            <td class="mailbox-name">
                                                <input type="hidden" name="books_id[]" value="<?php echo $p['book_id'] ?>" />
                                                <input type="hidden" name="ordered_quantity[]" value="<?php echo $p['quantity'] ?>" />

                                                <input type="hidden" name="taken_quantity[]" value="<?php echo $p['sold_quantity'] ?>" />

                                                <input type="number" max="<?php echo $remainingBooks ?>" min="0" <?php if($p['status'] == '1') {echo 'readonly';  } ?> name="sold_book_qty[]" value="<?php echo $remainingBooks; ?>"  class="form-control"/>
                                            </td>
                                            <td class="mailbox-name"><?php echo number_format(($p['f_price']), 2, '.', ''); ?></td>
                                        </tr>
                                        <?php
                                        $totalRemainingAmount += ((int)($p['price']) * $remainingBooks);
                                        $totalAmount += floatval($p['f_price']);
                                    }
                                    ?>

                                    </tbody>
                                    <tfoot style="display: table-footer-group;">
                                        <tr>
                                           <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">Sub Total</th>
                                            <th> <?php echo number_format(($totalAmount), 2, '.', '');
                                                
                                            ?></th>
                                        </tr>
                                        <tr>

                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">VAT</th>
                                            <th>
                                                <?php
                                                $tax = (int)$sales_tax["sales_tax"];
                                                echo number_format(($tax * $totalAmount /100), 2, '.', '');
                                                
                                                ?>
                                            </th>
                                        </tr>
                                        <tr>
                                           <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">Total Price</th>
                                            <th>
                                                <?php
                                                $tax = (int)$sales_tax["sales_tax"];
                                                echo number_format(($tax * $totalAmount /100) + $totalAmount, 2, '.', '');
                                                
                                                ?>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="box-footer text-right">
                                 <?php if( $this->rbac->hasPrivilege('order', 'can_edit')){ ?>
                                <button type="submit" class="btn btn-info btn-sm"><?php echo $this->lang->line('std_mark_order_complete'); ?></button> <?php } ?>
                                <a href="<?php echo site_url('admin/BookStore/printReceipt/').$orders[0]['order_id'] ?>" class="btn btn-sm btn-success">Generate Receipt</a>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </section>
</div>


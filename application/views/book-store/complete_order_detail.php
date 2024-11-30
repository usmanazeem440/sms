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

                <div class="box box-info">
                    <div class="box-body table-responsive">
                        <div >
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('isbn') ?></th>
                                    <th><?php echo $this->lang->line('store_book_name') ?></th>
                                    <th><?php echo $this->lang->line('store_book_author') ?></th>
                                    <th><?php echo $this->lang->line('std_order_qty') ?> </th>
                                    <th><?php echo $this->lang->line('std_total_price') ?> </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $totalAmount = 0; ?>
                                <?php foreach ($orders as $p) { ?>
                                    <tr>
                                        <input type="hidden" name="order_id" value="<?php echo $p['order_id'] ?>"/>

                                        <td class="mailbox-name"><?php echo $p['isbn'] ?></td>
                                        <td class="mailbox-name"><?php echo $p['title'] ?></td>
                                        <td class="mailbox-name"><?php echo $p['author'] ?></td>
                                        <td class="mailbox-name"><?php echo $p['quantity'] ?></td>
                                        <td class="mailbox-name">

                                            <?php echo floatval($p['price']) * (int)$p['quantity'] ; ?>

                                        </td>
                                    </tr>
                                    <?php
                                    $totalAmount += floatval($p['price']) * (int)$p['quantity'];
                                }
                                ?>

                                </tbody>
                                <tfoot style="display: table-footer-group;">
                                <tr>
                                   <th></th>
                                    <th></th>
                                    <th></th>

                                    <th class="text-right">Sub Total</th>
                                    <th> <?php echo number_format(($totalAmount), 2, '.', ''); ?>

                                    </th>
                                </tr>
                                <tr>

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

                                    <th class="text-right">Total Price</th>
                                    <th>
                                        <?php
                                        $tax = (int)$sales_tax["sales_tax"];
                                        echo (number_format(($tax * $totalAmount /100) + $totalAmount, 2, '.', ''));

                                        ?>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <a href="<?php echo site_url('admin/BookStore/printReceipt/').$orders[0]['order_id'] ?>" class="btn btn-sm btn-success">Generate Receipt</a>
                    </div>
                </div>
            </div>
    </section>
</div>


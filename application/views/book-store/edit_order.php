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





                <form action="<?php echo base_url(); ?>admin/BookStore/updateOrder" method="POST">

                    <div class="box box-info">
                        <div class="box-body table-responsive">
                            <div >
                                <table class="table table-hover table-striped table-bordered example">
                                    <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('std_name') ?></th>
                                        <th><?php echo $this->lang->line('isbn'); ?></th>

                                        <th><?php echo $this->lang->line('store_book_name') ?></th>
                                        <th><?php echo $this->lang->line('store_book_author') ?></th>

                                        <th><?php echo $this->lang->line('std_order_qty') ?> </th>
                                        <th><?php echo $this->lang->line('order_sold_qty') ?> </th>
                                        <th><?php echo $this->lang->line('std_total_price') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $totalRemainingAmount = 0; $totalAmount = 0; ?>
                                    <?php foreach ($orders as $p) {  $remainingBooks = (int)($p['quantity']) - (int)($p['sold_quantity']);   ?>
                                        <tr>
                                            <input type="hidden" name="order_id" value="<?php echo $p['order_id'] ?>"/>
                                            <td class="mailbox-name"><?php echo $p['username'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['isbn'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['title'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['author'] ?></td>

                                            <td class="mailbox-name">
                                                <input type="number" value="<?php echo $p['quantity'] ?>" onblur="changePrice(<?php echo $p['price'] ?> , this.value, <?php echo $p['isbn'] ?>)" name="new_order_qty[]" />
                                            </td>
                                            <td class="mailbox-name">
                                                <input type="number" value="<?php echo $p['sold_quantity'] ?>" name="new_qty_sold[]" />

                                                <input type="hidden" name="prev_sold_qty[]" value="<?php echo $p['book_id'] ?>" />
                                                <input type="hidden" name="books_id[]" value="<?php echo $p['book_id'] ?>" />
                                                <input type="hidden" name="prev_order_quantity[]" value="<?php echo $p['quantity'] ?>" />

                                                <input type="hidden" name="taken_quantity[]" value="<?php echo $p['sold_quantity'] ?>" />
                                            </td>

                                            <td class="priceSum" id="<?php echo $p['isbn']; ?>"><?php echo number_format(($p['f_price']), 2, '.', ''); ?></td>
                                        </tr>
                                        <?php
                                        $totalRemainingAmount += ((int)($p['price']) * $remainingBooks);
                                        $totalAmount += floatval($p['f_price']);
                                    }
                                    ?>

                                    </tbody>
                                    <tfoot style="display: table-footer-group;">
                                    <tr>
                                        <th></th><th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th class="text-right">Sub Total</th>
                                        <th id="sub_total"> <?php echo number_format(($totalAmount), 2, '.', '');

                                            ?></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th class="text-right">VAT</th>
                                        <th id="vat">
                                            <?php
                                            $tax = (int)$sales_tax["sales_tax"];
                                            echo number_format(($tax * $totalAmount /100), 2, '.', '');

                                            ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th></th><th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">Total Price</th>
                                        <th id="calc_price">
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
                                    <button type="submit" class="btn btn-info btn-sm"><?php echo $this->lang->line('std_update_order'); ?></button> <?php } ?>
                                <a href="<?php echo site_url('admin/BookStore/printReceipt/').$orders[0]['order_id'] ?>" class="btn btn-sm btn-success">Generate Receipt</a>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">

    updateTotalPrice = function(){
        var totalPrice = 0;
        for(var i=0; i < document.getElementsByClassName("priceSum").length; i++){
           // alert(document.getElementsByClassName("priceSum")[i].innerText);
            totalPrice += parseFloat(document.getElementsByClassName("priceSum")[i].innerText);
        }

        tax= <?php echo $sales_tax["sales_tax"];?>;
        document.getElementById("sub_total").innerText=(totalPrice).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

        document.getElementById("vat").innerText=(tax * totalPrice / 100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

        document.getElementById("calc_price").innerText=((tax * totalPrice / 100) + totalPrice).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');


    }

    changePrice = function(price, value, pId){
        var fPrice = parseFloat(price) * parseInt(value);
        document.getElementById(pId).innerText=(fPrice).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

        updateTotalPrice();
    }



</script>


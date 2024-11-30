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
                <form action="<?php echo base_url(); ?>admin/BookStore/placeOrder" method="POST">
                    <input type="hidden" name="studetn_id" value="<?php echo $id ?>"/>
                    <div class="box box-info">
                        <div class="box-body table-responsive">
                            <div >
                                <table class="table table-hover table-striped table-bordered example">
                                    <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('book_title') ?></th>
                                        <th><?php echo $this->lang->line('store_book_author') ?></th>
                                        <th><?php echo $this->lang->line('store_book_price'); ?></th>
                                        <th><?php echo $this->lang->line('store_book_quantity') ?> </th>
                                        <th><?php echo $this->lang->line('bluk_price') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($books as $p) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $p['title'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['author'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['price'] ?></td>
                                            <td>
                                                <input type="hidden" name="price[]" value="<?php echo $p['price'] ?>"/>
                                                <input type="hidden" name="id[]" value="<?php echo $p['id'] ?>"/>
                                                <input type="number" id="quantity_<?php echo $p['id'] ?>" <?php if((int)$p['quantity'] > 0){ echo 'value="1"'; }else{echo 'value="1"'; }?> name="book_quantity[]"  min="0"  onfocusout="updateRowPrice(<?php echo $p['id'] ?>, <?php echo $p['price'] ?>)"/>
                                            </td>
                                            <td>
                                                <p class="price_list" id="price_<?php echo $p['id'] ?>"><?php echo $p['price'] ?></p>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>

                                    </tbody>
                                    <tfoot style="display: table-footer-group;">



                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right" > Sub Total </th>
                                        <th id="calculated_price"></th>
                                    </tr>

                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right" > Total Price</th>
                                        <th ><span id="total_price">0</span> <span class="pull-right"><small>(<?php echo "VAT" ?> <?php echo $sales_tax["sales_tax"]; ?> %) </small> </span> </th>
                                    </tr>


                                    </tfoot>
                                </table>

                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('std_place_order'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">


    updatePriceList(<?php echo (int)$sales_tax["sales_tax"]; ?>);
    function updatePriceList(tax){
        var totalPrice = 0;
        for (let index = 0; index < document.getElementsByClassName('price_list').length; index++) {
            totalPrice += parseFloat(document.getElementsByClassName('price_list')[index].innerHTML);
        }
        document.getElementById("calculated_price").innerHTML = totalPrice;


        var salesPrice = (totalPrice * tax / 100 ) + totalPrice;

        document.getElementById("total_price").innerHTML = salesPrice;

    }


    function updateRowPrice(id, price){
        var quantity = document.getElementById('quantity_'+id).value;
        var price = parseInt(quantity) * parseFloat(price);
        document.getElementById("price_"+id).innerHTML  = price;

        updatePriceList(<?php echo (int)$sales_tax["sales_tax"]; ?>);
    }


</script>

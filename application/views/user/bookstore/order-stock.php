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
                                        <th><?php echo $this->lang->line('book_title') ?></th>
                                        <th><?php echo $this->lang->line('store_book_author') ?></th>
                                        <th><?php echo $this->lang->line('store_book_price'); ?></th>
                                        <th><?php echo $this->lang->line('store_book_quantity') ?> </th>
                                        <th><?php echo $this->lang->line('std_price') ?> </th>
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
                                                <input type="number" id="quantity_<?php echo $p['id'] ?>" value="1" name="book_quantity[]" max="<?php echo  $p['quantity'] ?>" min="1"  onfocusout="updateRowPrice(<?php echo $p['id'] ?>, <?php echo $p['price'] ?>)"/> 
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
                                        <th> </th>
                                        <th><p id="calculated_price">0</p> </th>
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


updatePriceList();
function updatePriceList(){
    var totalPrice = 0;
    for (let index = 0; index < document.getElementsByClassName('price_list').length; index++) {
           totalPrice += parseInt(document.getElementsByClassName('price_list')[index].innerHTML);
        }
        document.getElementById("calculated_price").innerHTML = totalPrice;
    }


    function updateRowPrice(id, price){
        var quantity = document.getElementById('quantity_'+id).value;
        var price = parseInt(quantity) * parseFloat(price);
        document.getElementById("price_"+id).innerHTML  = price;

        updatePriceList();
    }

    
</script>

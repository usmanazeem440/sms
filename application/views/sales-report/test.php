<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $title ?> <small><?php echo $this->lang->line('class1'); ?></small>
        </h1>
    </section>
    <?php
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    ?>
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header hidden-print">
                        <div class="col-lg-12" style="margin-bottom: 1%;">
                            <div class="form-group col-lg-2">
                                <label>Start Date</label>
                                <input type="date" id="start_date" class="form-control" placeholder="Search by Date"/>
                            </div>
                            <div class="form-group col-lg-2">
                                <label>End Date</label>
                                <input type="date" id="end_date" onchange="" class="form-control" placeholder="Search by Date"/>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div >
                            <table class="table table-hover table-striped table-bordered example" id="myTable">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('std_order_id') ?></th>
                                    <th><?php echo "Order By" ?></th>
                                    <th><?php echo $this->lang->line('guardian_id') ?></th>
                                    <th><?php echo $this->lang->line('date') ?> </th>
                                    <th><?php echo $this->lang->line('sales_book_count') ?> </th>
                                    <th><?php echo $this->lang->line('status') ?> </th>
                                    <th><?php echo $currency_symbol.' '.$this->lang->line('sales_total_price') ?> </th>
                                    <th>Payment Method</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php $priceSum = 0; foreach ($orders as $order){ ?>
                                    <tr>
                                        <td class="mailbox-name">
                                            <a href="<?php echo base_url(); ?>admin/BookStore/viewOrder/<?php echo $order['order_id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="View Details">
                                                <?php echo $order['order_id']; ?>
                                            </a>
                                        </td>
                                        <td class="mailbox-name"><?php echo $order['order_placed_by']; ?></td>
                                        <td class="mailbox-name"><?php echo $order['guardian_id']; ?></td>
                                        <td class="mailbox-name"><?php echo $order['created_at'] ?></td>

                                        <td class="mailbox-name"><?php echo $order['qty']; ?></td>
                                        <td class="mailbox-name">
                                            <?php
                                            if($order['max_status'] == '2' || $order['min_status'] == '2'){
                                                echo "Partially Completed";
                                            }elseif ($order['max_status'] != $order['min_status']){
                                                echo "Partially Completed";
                                            }elseif ($order['max_status'] == '0' && $order['min_status'] == '0'){
                                                echo "Pending";
                                            }elseif($order['max_status'] == '1' && $order['min_status'] == '1'){
                                                echo "Completed";
                                            }else{
                                                echo "Cancelled";
                                            }

                                            $priceSum += floatval($order['price']);
                                            ?>
                                        </td>
                                        <td class="mailbox-name"><?php echo $order['price']; ?></td>
                                    </tr>
                                <?php } ?>

                                <tr class="hidden-print">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Sub Total</th>
                                    <th id="newSubTotal"><?php echo $priceSum; ?></th>
                                </tr>

                                <tr class="hidden-print">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>VAT (<?php echo $sales_tax["sales_tax"]; ?>%)</th>
                                    <th id="newTax"><?php
                                        $tax = (int)$sales_tax["sales_tax"];
                                        echo number_format(($tax * $priceSum /100), 2, '.', '');
                                        ?>
                                    </th>
                                </tr>

                                <tr class="hidden-print">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total Amount</th>
                                    <th id="finalAmount"><?php
                                        $tax = (int)$sales_tax["sales_tax"];
                                        echo number_format(($tax * $priceSum /100) + $priceSum, 2, '.', '');
                                        ?></th>
                                </tr>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <h4 id="finalAmount" style="text-align: right; margin-top: 2%;">
                                    Total Amount
                                    <span style="margin-left: 6%;">
                                        <?php
                                            $tax = (int)$sales_tax["sales_tax"];
                                            echo number_format(($tax * $priceSum /100) + $priceSum, 2, '.', '');
                                        ?>
                                    </span>
                                </h4>

                                <h4 id="newTax" style="text-align: right; margin-top: 1%;">
                                    VAT (<?php echo $sales_tax["sales_tax"]; ?>%)
                                    <span style="margin-left: 6%;">
                                        <?php
                                        $tax = (int)$sales_tax["sales_tax"];
                                        echo number_format(($tax * $priceSum /100), 2, '.', '');
                                        ?>
                                    </span>
                                </h4>

                                <h4 id="newSubTotal" style="text-align: right; margin-top: 1%; margin-bottom: 3%;">
                                    Sub Total
                                    <span style="margin-left: 6%;">
                                        <?php echo $priceSum; ?>
                                    </span>
                                </h4>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="button" class="btn btn-sm btn-success hidden-print"  onclick="printDiv()">Print Receipt</button>
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
                                <a id="cancel_url" href="" type="button" class="btn btn-danger">Yes</a>
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

<script type="text/javascript">
    $('#end_date').change('draw.dt', function() {
        var input, table, tr, txtValue, td, filter, end_filter, total, tax, subTotal;
        total = 0;
        filter = $('#start_date').val;

        end_filter = $('#end_date').val;

         table = $('#myTable:');
         tr = table:$("tr");
         for (i = 0; i < tr.length; i++) {
             td = tr[i].$("td")[3];

             if (td) {
                 txtValue = td.text();
                 txtValue = txtValue.split(' ')[0];

                if (txtValue >= filter && txtValue <= end_filter) {

                    total = total + parseFloat(tr[i].$("td")[6].text());
                    tr[i].css("display: block");
                } else {
                    tr[i].css("display: none");
                }
            }
        }

        //document.getElementById('newSubTotal').innerHTML = total.toFixed(3);
        //tax = <?php //echo $sales_tax["sales_tax"]; ?>//;
        //tax = parseInt(tax)*total/100;
        //document.getElementById('newTax').innerHTML = tax.toFixed(3);
        //document.getElementById('finalAmount').innerHTML = (total + tax).toFixed(3);
    });

    function searchDate(){

    }
</script>


<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $title ?> <small><?php echo $this->lang->line('class1'); ?></small></h1>
    </section>
    <?php
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    ?>


    <!-- Main content -->
    <section class="content">
        <div class="row" id="printArea" >
                <div class="col-sm-12">
                    <div class="col-sm-12 visible-print">
                        <img src="<?php echo base_url(); ?>backend/images/s_logo.png" style="height: 80px;">
                    </div>
                    <div class="col-xs-4">
                        <div class="box box-default">
                            <div class="box-body">
                                <b> <label><?php echo $this->setting_model->getCurrentSchoolName() ?></label></b><br/>
                                <span ><?php echo $this->setting_model->getSchoolDetail()->dise_code ?></span><br/>
                                <span ><?php echo $this->setting_model->getSchoolDetail()->email ?></span><br/>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">

                    </div>
                    <div class="col-xs-4">
                        <div class="box box-default">
                            <div class="box-body">
                                <b> <label><?php echo "Report Details" ?></label></b><br/>
                                <span><b>Sales Person: </b> <?php if(count($orders) > 0){echo $orders[0]['order_placed_by'];}else{echo $this->customlib->getUserData()['name'];} ?></span><br/>
                                <span><b>Total Orders: </b> <?php echo count($orders); ?></span><br/>
                                <span><b>Date: </b><?php  echo date("Y-m-d"); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="box box-default">
                        <div class="box-header hidden-print">
                            <div class="col-lg-12" style="margin-bottom: 1%;">
                                <div class="form-group col-lg-2">
                                    <label>Start Date</label>
                                    <input type="date" id="start_date" class="form-control" placeholder="Search by Date"/>
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>End Date</label>
                                    <input type="date" id="end_date" onchange="searchDate()" class="form-control" placeholder="Search by Date"/>
                                </div>
                            </div>
                        </div>
                       <!--  <div class="box-body"> -->
                            <div class="box box-info">
                    <div class="box-body table-responsive">
                            <div class="box-body table-responsive">
                                <div >

                                    <table class="table table-hover table-striped table-bordered example" id="myTable">

                                        <?php $i=0;?>
                                        <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('std_order_id') ?></th>
                                            <th><?php echo $this->lang->line('guardian_id') ?></th>
                                            <th><?php echo $this->lang->line('date') ?> </th>
                                            <th><?php echo $this->lang->line('sales_book_count') ?> </th>
                                            <th><?php echo $this->lang->line('status') ?> </th>
                                            <th><?php echo $currency_symbol.' '.$this->lang->line('sales_total_price') ?> </th>
                                            <th class="hidden-print"><?php echo $this->lang->line('action') ?> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php $priceSum = 0; foreach ($orders as $order){ ?>
                                                <tr>
                                                    <td><?php echo $order['order_id']; ?></td>
                                                    <td><?php echo $order['guardian_id']; ?></td>
                                                    <td><?php echo $order['created_at'] ?></td>

                                                    <td><?php echo $order['qty']; ?></td>
                                                    <td>
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
                                                    <td><?php echo $order['price']; ?></td>
                                                    <td class="hidden-print">
                                                        <a href="<?php echo base_url(); ?>admin/BookStore/viewOrder/<?php echo $order['order_id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="View Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php  } ?>
                                            <tr>
                                                <td colspan="6">
                                                    <hr/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="hidden-print"></th>
                                                <th></th>
                                                <th>Sub Total</th>
                                                <th><?php echo $priceSum; ?></th>
                                            </tr>

                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="hidden-print"></th>
                                                <th></th>
                                                <th>VAT (<?php echo $sales_tax["sales_tax"]; ?>%)</th>
                                                <th><?php
                                                    $tax = (int)$sales_tax["sales_tax"];
                                                    echo number_format(($tax * $priceSum /100), 2, '.', '');
                                                    ?></th>
                                            </tr>

                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th class="hidden-print"></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total Amount</th>
                                                <th><?php
                                                    $tax = (int)$sales_tax["sales_tax"];
                                                    echo number_format(($tax * $priceSum /100) + $priceSum, 2, '.', '');
                                                    ?></th>
                                            </tr>
                                   

                                        </tbody>

                                    </table>
                                </div>
                                <div class="box-footer text-right">
                                    <button type="button" class="btn btn-sm btn-success hidden-print"  onclick="printDiv()">Print Receipt</button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    function printDiv() {

        var printContents = document.getElementById('printArea').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;


        window.print();

        document.body.innerHTML = originalContents;
    }
</script>

<script type="text/javascript">

    function searchDate(){
        var input, table, tr, txtValue, td, filter, end_filter;
        filter = document.getElementById('start_date').value;
        end_filter = document.getElementById('end_date').value;

        table = document.getElementById('myTable');
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];

            if (td) {
                txtValue = td.innerText;
                txtValue = txtValue.split(' ')[0];

                if (txtValue >= filter && txtValue <= end_filter) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>






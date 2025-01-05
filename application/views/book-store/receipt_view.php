<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    

    <?php
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    ?>

    <section class="content-header">
        <h1>
            <i class="fa fa-flask"></i> <?php echo $this->lang->line('receipt_book'); ?>
        </h1>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-md-12 printArea" id="printArea">
                
                <div class="col-xs-12 visible-print" style="margin-bottom:30px; margin-top:10px;">
                    <br/>
                    <div class="col-xs-4 text-right">
                        <img src="<?php echo base_url(); ?>backend/images/print_logo.png" style="height: 80px;">    
                    </div>
                    <div class="col-xs-8 text-left">
                        <b><span style="font-size:16px;"><?php echo $this->setting_model->getCurrentSchoolName() ?></span></b><br/>
                        <span>Madina Chok Badami bagh, Lahore</span><br/>
                        <span>Tel: 012-6738670</span><br/>
                        <b>VAT No: 310150218500003</b>
                    </div>
                    
                </div>

                <div class="box box-success">
                    <div class="box-header">
                        <div class="col-sm-10 text-center">
                            <h4>Book Receipt</h4>
                        </div>
                        <div class="col-sm-2 text-right">
                            <h4>Order ID# <?php echo $orders[0]['order_id'] ?></h4>
                        </div>
                        
                        <div class="col-xs-4">
                            <div class="box box-default">
                                <div class="box-header">
                                    <label>Customer Details</label>
                                </div>
                                <div class="box-body">
                                  
                                    <b>Admission No : </b> <span><?php echo $parent_details[0]->admission_no; ?></span><br/>
                                    <b>Student Name : </b> <span><?php echo $parent_details[0]->firstname; ?></span><br/>
                                    <b>Student Class: </b> <span><?php echo $parent_details[0]->Tag; ?></span><br/>
                                      <b>Student Section: </b> <span><?php echo $parent_details[0]->section; ?></span><br/>
                                      <b>Father Name : </b> <span><?php echo $parent_details[0]->father_name; ?></span><br/>
                                    <b>Father ID : </b> <span><?php echo $parent_details[0]->guardian_id; ?></span><br/>
                                    <b>Phone No: </b> <span><?php echo $parent_details[0]->father_phone; ?></span><br/>
                           
                                    <!-- <b>Customer Name : </b> <span><?php echo $parent_details[0]->father_name; ?></span><br/> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            
                        </div>

                        <div class="col-xs-4">
                            <div class="box box-default">
                                <div class="box-header">
                                    <label>Order Details</label>
                                </div>
                                <div class="box-body">
                                    <b>Order Status :</b> <span id="order_status"><?php echo $parent_details[0]->father_name; ?></span><br/>
                                    <span ><b>Ordered Books: </b> <span><?php echo count($orders); ?></span></span><br/>
                                    <b>Order Taken By: </b> <span><?php echo $orders[0]['order_placed_by'] ?></span><br/>
                                    <b>Date: </b> <span id="vat_price"><?php $date = explode(" ", $orders[0]['created_at'])[0]; echo  $date; ?></span><br/>
                                </div>
                            </div>
                        </div>



                        <div class=col-xs-12>
                            <div class="box box-default">
                                <div class="box-header">
                                    <label>Order Details</label>
                                </div>
                                <div class="box-body">
                                    <div class="box-body table-responsive">
                                        <div >
                                            <table class="table table-hover table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('isbn') ?></th>
                                                    <th><?php echo $this->lang->line('store_book_name') ?></th>

                                                    <th><?php echo $this->lang->line('std_order_qty') ?> </th>
                                                    <th><?php echo $this->lang->line('order_sold_qty') ?> </th>
                                                    <th><?php echo $this->lang->line('order_sold_qty1') ?> </th>
                                                    <th style="display:none"><?php echo $this->lang->line('remaining_amount') ?> </th>
                                                    <th><?php  echo $currency_symbol ." ". $this->lang->line('std_total_price') ?> </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $totalRemainingAmount = 0; $totalAmount = 0; $ordStatus = "Pending"; $isPending = false; $iscompleted = false; $isPartially = false; ?>
                                                <?php foreach ($orders as $p) {  $remainingBooks = (int)($p['quantity']) - (int)($p['sold_quantity']);   ?>
                                                    <tr>
                                                        <input type="hidden" name="order_id" value="<?php echo $p['order_id'] ?>"/>
                                                        <td class="mailbox-name"><?php echo $p["isbn"]; ?></td>
                                                        <td class="mailbox-name"><?php echo $p['title'] ?></td>

                                                        <td class="mailbox-name"><?php echo $p['quantity'] ?></td>
                                                        <td class="mailbox-name"><?php echo $p['sold_quantity'] ?></td>
                                                        <td class="mailbox-name">
                                                            <?php if($p['status'] == '1'){ ?>
                                                                <?php $iscompleted = true; $ordStatus = "Completed"; ?>
                                                                <span class="text-success"> <?php echo ((int)$p['quantity'] - (int)$p['sold_quantity']) ?></span>
                                                            <?php }elseif($p['status'] == '2'){ ?>
                                                                <?php $ordStatus = "Partially Completed"; $isPartially = true; ?>
                                                                <span class="text-warning"><?php echo $remainingBooks; ?></span>
                                                            <?php }else{?>
                                                                <span class="text-danger"><?php echo ((int)$p['quantity'] - (int)$p['sold_quantity']) ?></span>
                                                            <?php  $isPending = true;   }?>

                                                        </td>
                                                        <td style="display:none" class="mailbox-name">

                                                            <?php echo ((int)($p['price']) * $remainingBooks); ?>

                                                        </td>
                                                        <td class="mailbox-name"><?php echo(number_format($p['f_price'], 2, '.', '')); ?>

                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $totalRemainingAmount += ((int)($p['price']) * $remainingBooks);
                                                    $totalAmount += floatval($p['f_price']);
                                                }

                                                if($isPartially){
                                                    $ordStatus = "Partially Completed";
                                                }
                                                elseif($isPending && $iscompleted){
                                                    $ordStatus = "Partially Completed";
                                                }elseif($iscompleted){
                                                    $ordStatus = "Completed";
                                                }elseif ($isPending){
                                                    $ordStatus = "Pending";
                                                }else{
                                                    $ordStatus = "Pending";
                                                }




                                                ?>

                                                <tr>
                                                    <th colspan="7">
                                                        <hr/>
                                                    </th>
                                                </tr>
                                                


                                                </tbody>

                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer text-right">
                                    <div class="col-xs-12 text-left">
                                        <div class="col-xs-3 col-xs-offset-9">
                                            <table>
                                                <tr>
                                                    <th>
                                                        Sub Total:
                                                    </th>
                                                    <th>
                                                        &nbsp;&nbsp;&nbsp;
                                                    </th>
                                                    <th>
                                                        <?php echo ' '.$currency_symbol.' '.$totalAmount;  ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        VAT<?php $tax = (int)$sales_tax["sales_tax"]; echo "<small> (".$tax."%) </small>";?>: 
                                                    </th>
                                                    <th>
                                                        &nbsp;&nbsp;&nbsp;
                                                    </th>
                                                    <th>
                                                        <?php
                                                            echo  ' '.$currency_symbol.' '.(number_format($tax * $totalAmount /100, 2, '.', ''));
                                                            ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Total Price: 
                                                    </th>
                                                    <th>
                                                        &nbsp;&nbsp;&nbsp;
                                                    </th>
                                                    <th>
                                                          <?php
                                                            $tax = (int)$sales_tax["sales_tax"];
                                                            echo ' '.$currency_symbol.' '.(number_format(($tax * $totalAmount /100) + $totalAmount, 2, '.', ''));
                                                            ?>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                
                                 
                                <button type="button" class="btn btn-sm btn-success hidden-print"  onclick="printDiv( '<?php echo $ordStatus ?>' )">Print Receipt</button>

                            </div>
                            <br/>
                            <br/>
                            
                            <div class="visible-print">
                                <div class=col-xs-6>
                                    <span><b>Stamp</b><hr/></span>
                                </div>
                                <div class="col-xs-6">
                                    <span><b>Signature</b><hr/></span>
                                </div>
                                <br/>
                                <br/>
                                 <footer>
                                    <small>
                                        <hr/>
                                        Kindly check and confirm books quantity before leaving Book Bank, as Books once bought will not be returned or changed.
                                    </small>
                                </footer>
                                

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

    orderStatus();
    function orderStatus(){
        document.getElementById("order_status").innerText = '<?php echo $ordStatus; ?>';
    }


    function printDiv(status) {

        document.getElementById("order_status").innerText = status;

        var printContents = document.getElementById('printArea').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>


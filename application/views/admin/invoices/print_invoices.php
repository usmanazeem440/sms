<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<style type="text/css">
    @media print {
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
        .col-sm-pull-12 {
            right: 100%;
        }
        .col-sm-pull-11 {
            right: 91.66666667%;
        }
        .col-sm-pull-10 {
            right: 83.33333333%;
        }
        .col-sm-pull-9 {
            right: 75%;
        }
        .col-sm-pull-8 {
            right: 66.66666667%;
        }
        .col-sm-pull-7 {
            right: 58.33333333%;
        }
        .col-sm-pull-6 {
            right: 50%;
        }
        .col-sm-pull-5 {
            right: 41.66666667%;
        }
        .col-sm-pull-4 {
            right: 33.33333333%;
        }
        .col-sm-pull-3 {
            right: 25%;
        }
        .col-sm-pull-2 {
            right: 16.66666667%;
        }
        .col-sm-pull-1 {
            right: 8.33333333%;
        }
        .col-sm-pull-0 {
            right: auto;
        }
        .col-sm-push-12 {
            left: 100%;
        }
        .col-sm-push-11 {
            left: 91.66666667%;
        }
        .col-sm-push-10 {
            left: 83.33333333%;
        }
        .col-sm-push-9 {
            left: 75%;
        }
        .col-sm-push-8 {
            left: 66.66666667%;
        }
        .col-sm-push-7 {
            left: 58.33333333%;
        }
        .col-sm-push-6 {
            left: 50%;
        }
        .col-sm-push-5 {
            left: 41.66666667%;
        }
        .col-sm-push-4 {
            left: 33.33333333%;
        }
        .col-sm-push-3 {
            left: 25%;
        }
        .col-sm-push-2 {
            left: 16.66666667%;
        }
        .col-sm-push-1 {
            left: 8.33333333%;
        }
        .col-sm-push-0 {
            left: auto;
        }
        .col-sm-offset-12 {
            margin-left: 100%;
        }
        .col-sm-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-sm-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-sm-offset-9 {
            margin-left: 75%;
        }
        .col-sm-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-sm-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-sm-offset-6 {
            margin-left: 50%;
        }
        .col-sm-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-sm-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-sm-offset-3 {
            margin-left: 25%;
        }
        .col-sm-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-sm-offset-0 {
            margin-left: 0%;
        }
        .visible-xs {
            display: none !important;
        }
        .hidden-xs {
            display: block !important;
        }
        table.hidden-xs {
            display: table;
        }
        tr.hidden-xs {
            display: table-row !important;
        }
        th.hidden-xs,
        td.hidden-xs {
            display: table-cell !important;
        }
        .hidden-xs.hidden-print {
            display: none !important;
        }
        .hidden-sm {
            display: none !important;
        }
        .visible-sm {
            display: block !important;
        }
        table.visible-sm {
            display: table;
        }
        tr.visible-sm {
            display: table-row !important;
        }
        th.visible-sm,
        td.visible-sm {
            display: table-cell !important;
        }
		.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: red;
  color: white;
  text-align: center;
}
    }
</style>

<html lang="en">
<head>
    <title><?php echo $this->lang->line('fees_receipt'); ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div id="content" class="col-lg-12 col-sm-12 ">
            <div class="invoice">
                <div class="row header text-center">
                    <div class="col-sm-12">
                        <img src="<?php echo base_url(); ?>/uploads/school_content/logo/pdf_header.jpg">
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <br/>
                        <address>
                            <strong><?php echo $this->lang->line('father_name'); ?>:</strong> <?php echo $invoicesDetails[0]['parents']['father_name']; ?><br>
                            <span><strong>Parent ID:</strong> <?php echo $invoicesDetails[0]['parents']['guardian_id']; ?> </span><br/>
                        </address>
                    </div>
                    <div class="col-xs-4 col-sm-offset-2 text-right">
                        <br/>
                        <address class="text-left">
                            <strong>Date: <?php
                                                            $date = substr($invoiceslist['invoice_date'], 0,10);
                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));
                                ?></strong><br/>
                            <span><strong><?php echo $this->lang->line('invoice_no'); ?>:</strong> <?php echo "INV-".str_pad($invoiceslist['invoice_number'], 6, "0", STR_PAD_LEFT);  ?> </span><br/>
                            
                            
                        </address>
                    </div>
                </div>
                <hr style="margin-top: 0px;margin-bottom: 0px;" />
                <div class="row">
                    <?php
                    if (!empty($invoicesDetails)) {
                        ?>

                        <table class="table table-striped table-responsive" style="font-size: 8pt;">
                            <thead>
                            <th><?php echo "Student Name"; ?></th>
                            
                            <th><?php echo "Admission No."; ?></th>
                            <th  class="text text-center"><?php echo $this->lang->line('class_section'); ?></th>
                            <th><?php echo "Fee Description"; ?></th>
                            
                            <th  class=""><?php echo $this->lang->line('due_date'); ?></th>
                            <th  class="text text-right"><?php echo $this->lang->line('amount'); ?></th>

                            <th class="text text-right" ><?php echo $this->lang->line('discount'); ?></th>
                            <th  class="text text-left"><?php echo "Tax"; ?></th>
                            <th  class="text text-right"><?php echo "Total Amount"; ?></th>

                            </thead>
                            <tbody>
                            <?php
                            $total_amount = 0;
                            $total_deposite_amount = 0;
                            $total_fine_amount = 0;
                            $total_discount_amount = 0;
                            $total_balance_amount = 0;
                            $alot_fee_discount = 0;
                            $total_paid_amount = 0;
                            $total_paid_tax = 0;
                            if (empty($invoicesDetails)) {
                                ?>
                                <tr>
                                    <td colspan="11" class="text-danger text-center">
                                        <?php echo $this->lang->line('no_transaction_found'); ?>
                                    </td>
                                </tr>
                                <?php
                            } else {



                                foreach ($invoicesDetails as $fee_key => $feeList) {
                                    $fee_discount = 0;
                                    $fee_paid = 0;
                                    $fee_fine = 0;
                                    if (!empty($feeList['amount_detail'])) {
                                        $fee_deposits = json_decode(($feeList['amount_detail']));

                                        foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                            $fee_paid = $fee_paid + $fee_deposits_value->amount;
                                            $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                            $fee_fine = $fee_fine + $fee_deposits_value->amount_fine;
                                        }
                                    }
                                    $student_discount_fee = $this->feediscount_model->getStudentIndiviualDiscounts($feeList['students'][0]['admission_no']);
                                    if($feeList['fee_groups_feetype'][0]['feetype']['type'] == "Tuition Fee"){
                                        $discount = $feeList['fee_groups_feetype'][0]['amount'] * $student_discount_fee  / 100;
                                    }else{
                                        $discount = 0;
                                    }

                                    //Feelist->amount = 750
                                    //discount = 150
                                    
                                    $total_amount = $total_amount + $fee_paid;
                                    $total_discount_amount = $total_discount_amount + $discount;
                                    $total_fine_amount = $total_fine_amount + $fee_fine;
                                    $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                    
                                    if(trim($feeList['fee_groups_feetype'][0]['feetype']['code']) == "Previous Session Balance" && $previous_session_balance_tax["previous_session_balance_tax"] == "disabled")
                                            $tax_amount = 0;	
                                    else if($feeList['fee_groups_feetype'][0]['feetype']['is_taxable'] == 'YES')
                                    {
                                        $tax_amount         = (((float)$fee_tax['fee_tax'] * ((float)$feeList['fee_groups_feetype'][0]['amount'] - $discount)) / 100);
                                        //$total_paid_tax += (($fee_paid * (float)$fee_tax['fee_tax'])/100);
                                        $total_paid_tax += ((($fee_paid /  (1+((float)$fee_tax['fee_tax']/100)))*(float)$fee_tax['fee_tax'])/100);
                                        
                                    }
                                    else 
                                            $tax_amount = 0;
                                   

                                    $feetype_balance = $feeList['fee_groups_feetype'][0]['amount'] - ($discount + $fee_paid) + $tax_amount;
                                    $total_balance_amount = $total_balance_amount + $feetype_balance;
                                    ?>
                                    <tr  class="dark-gray">

                                        <td><?php
                                        
                                            echo $feeList['students'][0]['firstname'].' '.$feeList['students'][0]['lastname'];
                                            ?></td>
                                        <td><?php echo $feeList['students'][0]['admission_no']; ?></td>
                                        <td ><?php echo $feeList['students'][0]['class']." (".$feeList['students'][0]['section']." )"; ?></td>
                                            <td><?php
                                        
                                            echo $feeList['fee_groups_feetype'][0]['fee_groups']['name'];
                                            ?></td>
                                        
                                        <td class="">

                                            <?php
                                            if ($feeList['fee_groups_feetype'][0]['due_date'] == "0000-00-00") {

                                            } else {

                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($feeList['fee_groups_feetype'][0]['due_date']));
                                            }
                                            ?>
                                        </td>

                                        <td class="text text-right"><?php echo $currency_symbol . $feeList['fee_groups_feetype'][0]['amount']; ?></td>
                                        <td class="text text-right"><?php
                                            echo ($currency_symbol . number_format($discount, 2, '.', ''));
                                            ?></td>

                                        <td>
                                            <?php echo $currency_symbol . $tax_amount;
                                            $total_paid_tax +=  $tax_amount; ?>
                                        </td>


                                        <td class="text text-right"><?php
                                            echo ($currency_symbol . number_format($feeList['fee_groups_feetype'][0]['amount'] - $discount + $tax_amount, 2, '.', ''));
                                            $total_amount += $feeList['fee_groups_feetype'][0]['amount'] - $discount + $tax_amount;
                                            ?></td>

                                    </tr>

                                    <?php
                              
                                }
                            }
                            ?>
                            <tr class="success">
                                <td colspan="5" ></td>
                                <td align="left" class="text text-left" >
                                    <b>    <?php echo $this->lang->line('total_tax'); ?></b>
                                </td>
                                <td colspan="2">
                                </td>
                                <td class="text text-right">
                                    <b>    <?php
                                        echo $currency_symbol . number_format($total_paid_tax, 2, '.', '');
                                        ?></b>
                                </td>
                                <td class="text text-right"></td>
                            </tr>
                            <tr class="success">
                                <td colspan="5" ></td>
                                <td align="left" class="text text-left" >
                                    <b>    <?php echo $this->lang->line('total_amount'); ?></b>
                                </td>
                                <td colspan="2">
                                </td>
                                <td class="text text-right">
                                    <b>    <?php
                                        echo ($currency_symbol . number_format(($total_amount-$total_paid_tax), 2, '.', ''));
                                        ?></b>
                                </td>
                                <td class="text text-right"></td>
                            </tr>
                            <tr class="success">
                                <td colspan="5" ></td>
                                <td align="left" class="text text-left" >
                                    <b>    <?php echo $this->lang->line('grand_total'); ?></b>
                                </td>
                                <td colspan="2">
                                </td>
                                <td class="text text-right">
                                    <b>    <?php
                                        echo ($currency_symbol . number_format($total_amount, 2, '.', ''));
                                        ?></b>
                                </td>
                                <td class="text text-right"></td>
                            </tr>
                            
                            </tbody>
                        </table>
                        <?php
                    }
                    ?>
                    <!--<div class="row footer text-center">
                    <div class="col-sm-12">
                        <img src="<?php echo base_url(); ?>/uploads/school_content/logo/invoice_footer.jpg">
                    </div>
                    
                </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<footer>
</footer>
</body>
</html>

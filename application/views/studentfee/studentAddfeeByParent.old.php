<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1>
                    <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?><small><?php echo $this->lang->line('student_fee'); ?></small></h1>
            </section>
        </div>
    </div>
    <!-- /.control-sidebar -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line('student_fees'); ?></h3>
                            </div>
                            <div class="col-md-8 ">
                                <div class="btn-group pull-right">
                                    <div class="btn-group pull-right">
                                        <button type="button" onclick="updateAmount()" class="btn btn-xs btn-success myCollectFeeBtn " title="<?php echo $this->lang->line('admin_pay_all_dues'); ?>" data-toggle="modal" data-target="#myFeesModal"> <?php echo $this->lang->line('admin_pay_all_dues'); ?> </button>
                                    </div>
                                </div>
                            </div>
                        </div><!--./box-header-->
                        <div class="box-body" style="padding-top:0;">
                            <div class="row no-print">
                                <div class="col-md-12 mDMb10">
                                    <span class="pull-right"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="download_label"><?php echo $this->lang->line('student_fees') ?> </div>
                                <table class="table table-striped table-bordered table-hover example table-fixed-header">
                                    <thead class="header">
                                    <tr>

                                        <th align="left"><?php echo $this->lang->line('ptr_student_reg'); ?></th>
                                        <th align="left"><?php echo $this->lang->line('ptr_student_name'); ?></th>
                                        <th align="left"><?php echo $this->lang->line('ptr_student_class'); ?></th>
                                        <th align="left"><?php echo $this->lang->line('ptr_student_class_section'); ?></th>

                                        <th align="left"><?php echo $this->lang->line('fees_code'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('due_date'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                        <th  class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text text-right" ><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $total_amount = 0;
                                    $total_deposite_amount = 0;
                                    $total_fine_amount = 0;
                                    $total_discount_amount = 0;
                                    $total_balance_amount = 0;
                                    $alot_fee_discount = 0;


                                    for($i = 0; $i < count($students); $i++) {

                                        $student_due_fee = $student_due_fee_array[$i];

                                        foreach ($student_due_fee as $key => $fee) {

                                            foreach ($fee->fees as $fee_key => $fee_value) {
                                                $fee_paid = 0;
                                                $fee_discount = 0;
                                                $fee_fine = 0;


                                                if (!empty($fee_value->amount_detail)) {
                                                    $fee_deposits = json_decode(($fee_value->amount_detail));

                                                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                        $fee_paid = $fee_paid + $fee_deposits_value->amount;
                                                        $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                        $fee_fine = $fee_fine + $fee_deposits_value->amount_fine;
                                                    }
                                                }
                                                $total_amount = $total_amount + $fee_value->amount;
                                                $total_discount_amount = $total_discount_amount + $fee_discount;
                                                $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                                $total_fine_amount = $total_fine_amount + $fee_fine;
                                                $feetype_balance = $fee_value->amount - ($fee_paid + $fee_discount);
                                                $total_balance_amount = $total_balance_amount + $feetype_balance;
                                                ?>
                                                <?php
                                                if ($feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                                    ?>
                                                    <tr class="danger font12">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <tr class="dark-gray">
                                                    <?php
                                                }
                                                ?>

                                                <td>

                                                    <input type="hidden" name="student_id[]" class="student_id" value="<?php echo $students[$i]->id ?>"/>
                                                    <?php
                                                    echo $students[$i]->admission_no;
                                                    ?>
                                                </td>


                                                <td> <?php
                                                    echo $students[$i]->firstname;
                                                    ?>
                                                </td>


                                                <td><?php
                                                    echo $students[$i]->class;
                                                    ?>
                                                </td>

                                                <td><?php
                                                    echo $students[$i]->section;
                                                    ?>
                                                </td>

                                                <td align="left"><?php echo $fee_value->code; ?></td>
                                                <td align="left" class="text text-left">

                                                    <?php
                                                    if ($fee_value->due_date == "0000-00-00") {

                                                    } else {

                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                    }
                                                    ?>
                                                </td>
                                                <td align="left" class="text text-left width85">
                                                    <?php
                                                    if ($feetype_balance == 0) {
                                                        ?>
                                                        <span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                    } else if (!empty($fee_value->amount_detail)) {
                                                        ?>
                                                        <span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                    } else {
                                                        ?>
                                                        <span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                    }
                                                    ?>

                                                </td>
                                                <td class="text text-right"><?php echo $fee_value->amount; ?>

                                                </td>

                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-right"><?php
                                                    echo(number_format($fee_discount, 2, '.', ''));
                                                    ?>
                                                    <input type="hidden" name="fee_discount[]" value="<?php echo $fee_discount; ?>" class="fee_discount"/>
                                                    <input type="hidden" name="fee_groups_feetype_id[]" value="<?php echo $fee_value->fee_groups_feetype_id; ?>" class="fee_groups_feetype_id"/>
                                                    <input type="hidden" name="fee_id[]" value="<?php echo $fee->id; ?>" class="fee_id"/>
                                                    <input type="hidden" name="student_section_id[]" value="<?php echo $fee->student_session_id; ?>" class="student_section_id"/>


                                                </td>
                                                <td class="text text-right"><?php
                                                    echo(number_format($fee_fine, 2, '.', ''));
                                                    ?>
                                                    <input type="hidden" name="fee_fine[]" value="<?php echo $fee_fine; ?>" class="fee_fine"/>
                                                </td>
                                                <td class="text text-right"><?php
                                                    echo(number_format($fee_paid, 2, '.', ''));
                                                    ?>
                                                </td>
                                                <td class="text text-right"><?php
                                                    $display_none = "ss-none";
                                                    if ($feetype_balance > 0) {
                                                        $display_none = "";
                                                        echo(number_format($feetype_balance, 2, '.', '')); ?>
                                                        <input type="hidden" name="fee_value[]" value="<?php echo $feetype_balance; ?>" class="fee_value"/>

                                                    <?php }else{ ?>
                                                        <input type="hidden" name="fee_value[]" value="0" class="fee_value"/>
                                                    <?php }?>
                                                </td>

                                                </tr>

                                                <?php
                                                if (!empty($fee_value->amount_detail)) {

                                                    $fee_deposits = json_decode(($fee_value->amount_detail));

                                                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                        ?>
                                                        <tr class="white-td">
                                                            <td align="left"></td>

                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td class="text-right"><img
                                                                        src="<?php echo base_url(); ?>backend/images/table-arrow.png"
                                                                        alt=""/></td>


                                                            <td class="text text-left">


                                                                <a href="#" data-toggle="popover"
                                                                   class="detail_popover"> <?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                                <div class="fee_detail_popover" style="display: none">
                                                                    <?php
                                                                    if ($fee_deposits_value->description == "") {
                                                                        ?>
                                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>


                                                            </td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td class="text text-left"><?php echo $fee_deposits_value->payment_mode; ?></td>
                                                            <td class="text text-left">

                                                                <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                            </td>
                                                            <td class="text text-right"><?php echo(number_format($fee_deposits_value->amount_discount, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo(number_format($fee_deposits_value->amount_fine, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo(number_format($fee_deposits_value->amount, 2, '.', '')); ?></td>
                                                            <td></td>

                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <?php
                                    if(count($student_discount_fee_array) > $i){
                                        $student_discount_fee = $student_discount_fee_array[$i];
                                        if (!empty($student_discount_fee)) {

                                            foreach ($student_discount_fee as $discount_key => $discount_value) {
                                                ?>
                                                <tr class="dark-light">
                                                    <td></td>
                                                    <td align="left"> <?php echo $this->lang->line('discount'); ?> </td>
                                                    <td align="left">
                                                        <?php echo $discount_value['code']; ?>
                                                    </td>
                                                    <td align="left"></td>
                                                    <td align="left" class="text text-left">
                                                        <?php
                                                        if ($discount_value['status'] == "applied") {
                                                            ?>
                                                            <a href="#" data-toggle="popover" class="detail_popover" >

                                                                <?php echo $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id']; ?>

                                                            </a>
                                                            <div class="fee_detail_popover" style="display: none">
                                                                <?php
                                                                if ($discount_value['student_fees_discount_description'] == "") {
                                                                    ?>
                                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <p class="text text-danger"><?php echo $discount_value['student_fees_discount_description'] ?></p>
                                                                    <?php
                                                                }
                                                                ?>

                                                            </div>
                                                            <?php
                                                        } else {
                                                            echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']);
                                                        }
                                                        ?>

                                                    </td>
                                                    <td></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td  class="text text-right">
                                                        <?php
                                                        $alot_fee_discount = $alot_fee_discount;
                                                        ?>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="btn-group pull-right">
                                                            <?php
                                                            if ($discount_value['status'] == "applied") {
                                                                ?>

                                                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) { ?>
                                                                    <button class="btn btn-default btn-xs" data-discounttitle="<?php echo $discount_value['code']; ?>" data-discountid="<?php echo $discount_value['id']; ?>" data-toggle="modal" data-target="#confirm-discountdelete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                        <i class="fa fa-undo"> </i>
                                                                    </button>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>

                                                            <button type="button" data-modal_title="<?php echo $this->lang->line('discount') . " : " . $discount_value['code']; ?>" data-student_fees_discount_id="<?php echo $discount_value['id']; ?>"
                                                                    class="btn btn-xs btn-default applydiscount"
                                                                    title="<?php echo $this->lang->line('apply_discount'); ?>"
                                                            ><i class="fa fa-check"></i>
                                                            </button>

                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }

                                    ?>


                                    <tr class="box box-solid total-bg">

                                        <td align="left" ></td>
                                        <td align="left" ></td>
                                        <td align="left" ></td>
                                        <td align="left" ></td>

                                        <td align="left" ></td>
                                        <td align="left" class="text text-left" ><?php echo $this->lang->line('grand_total'); ?></td>
                                        <td align="left" ></td>
                                        <td class="text text-right"><?php
                                            echo ($currency_symbol . number_format($total_amount, 2, '.', ''));
                                            ?></td>
                                        <td class="text text-left"></td>
                                        <td class="text text-left"></td>
                                        <td class="text text-left"></td>

                                        <td class="text text-right">
                                            <?php
                                            echo ($currency_symbol . number_format($total_discount_amount + $alot_fee_discount, 2, '.', ''));
                                            ?></td>
                                        <td class="text text-right">
                                            <?php
                                            echo ($currency_symbol . number_format($total_fine_amount, 2, '.', ''));
                                            ?></td>
                                        <td class="text text-right" >
                                            <?php
                                            echo ($currency_symbol . number_format($total_deposite_amount, 2, '.', ''));
                                            ?></td>

                                        <td class="text text-right" id="total_cal_amount"><?php
                                            echo ($currency_symbol . number_format($total_balance_amount - $alot_fee_discount, 2, '.', ''));
                                            ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>


                </div>
                <!--/.col (left) -->

            </div>

    </section>

</div>


<div class="modal fade" id="myFeesModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center fees_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input  type="hidden" class="form-control" id="parent_id" value="<?php echo $students[0]->parent_id ?>" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="guardian_phone" value="<?php echo $students[0]->guardian_phone ?>" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="guardian_email" value="<?php echo $students[0]->guardian_email ?>" readonly="readonly"/>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('date'); ?></label>
                            <div class="col-sm-9">
                                <input  id="date" name="admission_date" placeholder="" type="text" class="form-control date"  value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('amount'); ?> </label><small class="req"> *</small>
                            <div class="col-sm-9">

                                <input type="text" disabled  value="" id="final_amount" autofocus="" class="form-control modal_amount" id="amount" >

                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>




                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('mode'); ?></label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cash" checked="checked"><?php echo $this->lang->line('cash'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cheque"><?php echo $this->lang->line('cheque'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="DD"><?php echo $this->lang->line('dd'); ?>
                                </label>
                                <span class="text-danger" id="payment_mode_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('note'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="box-body">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="button" class="btn cfees save_button" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_fees'); ?> </button>
                </div>
            </div>
        </div>

    </div>
</div>



<div class="modal fade" id="myDisApplyModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center discount_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input  type="hidden" class="form-control" id="student_fees_discount_id"  value=""/>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment_id'); ?> </label><small class="req">*</small>
                            <div class="col-sm-9">

                                <input type="text" class="form-control" id="discount_payment_id" >

                                <span class="text-danger" id="discount_payment_id_error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('description'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="dis_description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="box-body">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="button" class="btn cfees dis_apply_button" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $this->lang->line('apply_discount'); ?></button>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="delmodal modal fade" id="confirm-discountdelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>

            <div class="modal-body">

                <p>Are you sure want to revert <b class="discount_title"></b> discount, this action is irreversible.</p>
                <p>Do you want to proceed?</p>
                <p class="debug-url"></p>
                <input type="hidden" name="discount_id"  id="discount_id" value="">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-discountdel"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>


<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>

            <div class="modal-body">

                <p>Are you sure want to delete <b class="invoice_no"></b> invoice, this action is irreversible.</p>
                <p>Do you want to proceed?</p>
                <p class="debug-url"></p>
                <input type="hidden" name="main_invoice"  id="main_invoice" value="">
                <input type="hidden" name="sub_invoice" id="sub_invoice"  value="">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">


    $(document).ready(function () {
        $(document).on('click', '.printDoc', function () {
            var main_invoice = $(this).data('main_invoice');
            var sub_invoice = $(this).data('sub_invoice');
            var student_session_id = '<?php echo $student['student_session_id'] ?>';
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByName") ?>',
                type: 'post',
                data: {'student_session_id': student_session_id, 'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (response) {
                    Popup(response);
                }
            });
        });
        $(document).on('click', '.printInv', function () {
            var fee_master_id = $(this).data('fee_master_id');
            var fee_session_group_id = $(this).data('fee_session_group_id');
            var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByGroup") ?>',
                type: 'post',
                data: {'fee_groups_feetype_id': fee_groups_feetype_id, 'fee_master_id': fee_master_id, 'fee_session_group_id': fee_session_group_id},
                success: function (response) {
                    Popup(response);
                }
            });
        });
    });
</script>


<script type="text/javascript">

    updateAmount = function(){
        $('#final_amount').val($('#total_cal_amount').text());
    }
</script>



<script type="text/javascript">
    $(document).on('click', '.save_button', function (e) {
        var $this = $(this);
        $this.button('loading');
        var form = $(this).attr('frm');
        var feetype = $('#feetype_').val();
        var date = $('#date').val();

        var parentId = $('#parent_id').val();


        var description = $('#description').val();
        var guardian_phone = $('#guardian_phone').val();
        var guardian_email = $('#guardian_email').val();
        var payment_mode = $('input[name="payment_mode_fee"]:checked').val();
        var student_fees_discount_id = '0';


        var student_ids = new Array();
        $('.student_id').each(function(){
            student_ids.push($(this).val());
        });

        var amount = new Array();
        $('.fee_value').each(function(){
            amount.push($(this).val());
        });

        var amount_fine = new Array();
        $('.fee_fine').each(function(){
            amount_fine.push($(this).val());
        });



        var amount_discount = new Array();
        $('.fee_discount').each(function(){
            amount_discount.push($(this).val());
        });

        var student_fees_master_id = new Array();
        $('.fee_id').each(function(){
            student_fees_master_id.push($(this).val());
        });

        var fee_groups_feetype_id = new Array();
        $('.fee_groups_feetype_id').each(function(){
            fee_groups_feetype_id.push($(this).val());
        });




        $.ajax({
            url: '<?php echo site_url("studentfee/addByParentfee") ?>',
            type: 'post',
            data: {date: date,
                type: feetype,
                amount: amount,
                parent_id: parentId,
                description: description,
                payment_mode: payment_mode,
                guardian_phone: guardian_phone,
                guardian_email: guardian_email,
                student_ids: student_ids,
                amount_discount: amount_discount,
                amount_fine: amount_fine,
                fee_groups_feetype_id: fee_groups_feetype_id,
                student_fees_discount_id: student_fees_discount_id,
                student_fees_master_id: student_fees_master_id
            },
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                $this.button('reset');
                if (response.status == "success") {
                    location.reload(true);
                } else if (response.status == "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>


<script>

    var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }
    $(document).ready(function () {
        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
        $('#confirm-delete').on('show.bs.modal', function (e) {
            $('.invoice_no', this).text("");
            $('#main_invoice', this).val("");
            $('#sub_invoice', this).val("");

            $('.invoice_no', this).text($(e.relatedTarget).data('invoiceno'));
            $('#main_invoice', this).val($(e.relatedTarget).data('main_invoice'));
            $('#sub_invoice', this).val($(e.relatedTarget).data('sub_invoice'));


        });

        $('#confirm-discountdelete').on('show.bs.modal', function (e) {
            $('.discount_title', this).text("");
            $('#discount_id', this).val("");
            $('.discount_title', this).text($(e.relatedTarget).data('discounttitle'));
            $('#discount_id', this).val($(e.relatedTarget).data('discountid'));
        });

        $('#confirm-delete').on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });


        });

        $('#confirm-discountdelete').on('click', '.btn-discountdel', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var discount_id = $('#discount_id').val();


            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteStudentDiscount") ?>',
                dataType: 'JSON',
                data: {'discount_id': discount_id},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });


        });


        $(document).on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });


        });


        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });



</script>


<script type="text/javascript">
    var fee_amount = 0;

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

    $(document).ready(function () {
        $(".date").datepicker({
            format: date_format,
            autoclose: true,
            endDate: '+0d',
            todayHighlight: true
        });
    });
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    })
    $(document).ready(function () {
        $('.table-fixed-header').fixedHeader();
    });

    //  $(window).on('resize', function () {
    //    $('.header-copy').width($('.table-fixed-header').width())
    //});

    (function ($) {

        $.fn.fixedHeader = function (options) {
            var config = {
                topOffset: 50
                //bgColor: 'white'
            };
            if (options) {
                $.extend(config, options);
            }

            return this.each(function () {
                var o = $(this);

                var $win = $(window);
                var $head = $('thead.header', o);
                var isFixed = 0;
                var headTop = $head.length && $head.offset().top - config.topOffset;

                function processScroll() {
                    if (!o.is(':visible')) {
                        return;
                    }
                    if ($('thead.header-copy').size()) {
                        $('thead.header-copy').width($('thead.header').width());
                    }
                    var i;
                    var scrollTop = $win.scrollTop();
                    var t = $head.length && $head.offset().top - config.topOffset;
                    if (!isFixed && headTop !== t) {
                        headTop = t;
                    }
                    if (scrollTop >= headTop && !isFixed) {
                        isFixed = 1;
                    } else if (scrollTop <= headTop && isFixed) {
                        isFixed = 0;
                    }
                    isFixed ? $('thead.header-copy', o).offset({
                        left: $head.offset().left
                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                }
                $win.on('scroll', processScroll);

                // hack sad times - holdover until rewrite for 2.1
                $head.on('click', function () {
                    if (!isFixed) {
                        setTimeout(function () {
                            $win.scrollTop($win.scrollTop() - 47);
                        }, 10);
                    }
                });

                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                var header_width = $head.width();
                o.find('thead.header-copy').width(header_width);
                o.find('thead.header > tr:first > th').each(function (i, h) {
                    var w = $(h).width();
                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                });
                $head.css({
                    margin: '0 auto',
                    width: o.width(),
                    'background-color': config.bgColor
                });
                processScroll();
            });
        };

    })(jQuery);


    $(".applydiscount").click(function () {
        $("span[id$='_error']").html("");
        $('.discount_title').html("");
        $('#student_fees_discount_id').val("");
        var student_fees_discount_id = $(this).data("student_fees_discount_id");
        var modal_title = $(this).data("modal_title");
        student_fees_discount_id

        $('.discount_title').html("<b>" + modal_title + "</b>");

        $('#student_fees_discount_id').val(student_fees_discount_id);
        $('#myDisApplyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });



    });




    $(document).on('click', '.dis_apply_button', function (e) {
        var $this = $(this);
        $this.button('loading');

        var discount_payment_id = $('#discount_payment_id').val();
        var student_fees_discount_id = $('#student_fees_discount_id').val();
        var dis_description = $('#dis_description').val();

        $.ajax({
            url: '<?php echo site_url("admin/feediscount/applydiscount") ?>',
            type: 'post',
            data: {
                discount_payment_id: discount_payment_id,
                student_fees_discount_id: student_fees_discount_id,
                dis_description: dis_description
            },
            dataType: 'json',
            success: function (response) {
                $this.button('reset');
                if (response.status == "success") {
                    location.reload(true);
                } else if (response.status == "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });

</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.printSelected', function () {
            var array_to_print = [];
            $.each($("input[name='fee_checkbox']:checked"), function () {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {}
                item ["fee_session_group_id"] = fee_session_group_id;
                item ["fee_master_id"] = fee_master_id;
                item ["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_print.push(item);
            });
            if (array_to_print.length == 0) {
                alert("no record selected");
            } else {
                $.ajax({
                    url: '<?php echo site_url("studentfee/printFeesByGroupArray") ?>',
                    type: 'post',
                    data: {'data': JSON.stringify(array_to_print)},
                    success: function (response) {
                        Popup(response);
                    }
                });
            }
        });
    });


    $(function () {
        $(document).on('change', "#discount_group", function () {
            var amount = $('option:selected', this).data('disamount');

            var balance_amount = (parseFloat(fee_amount) - parseFloat(amount)).toFixed(2);
            if (typeof amount !== typeof undefined && amount !== false) {
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', true).val(amount);
                $('div#myFeesModal').find('input#amount').val(balance_amount);

            } else {
                $('div#myFeesModal').find('input#amount').val(fee_amount);
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', false).val(0);
            }

        });
    });
</script>

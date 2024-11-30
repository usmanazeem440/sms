<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">   
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('studen_fee_collection'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" id="action_form" action="<?php echo site_url('admin/feemaster/studentfees') ?>" method="post" class="form-horizontal">
                            <?php echo $this->customlib->getCSRF(); ?>
							<?php if(isset($error) && trim($error) != ''){
								?>
								<div class="alert alert-danger"><?php echo $error; ?></div>
								<?php
							}?>
							<div class="row">
							</div>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                                <div class="col-sm-4">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">

                                                <label><?php echo $this->lang->line('ptr_student_reg'); ?></label>

                                                <input type="text" name="search_text"  id="search_text" class="form-control" value="<?php echo isset($search_text)? $search_text : '';?>" placeholder="<?php echo $this->lang->line('ptr_student_reg'); ?>">

                                            </div>
                                </div>
                                <div class="col-sm-2">
                                    
                                </div>
                                <div class="col-sm-2">
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input type="hidden" name="action" id="action" value="">
                                    <button type="button" name="search" id="search"  value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="button" name="export" id="export"  value="export_filter"  class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-export"></i> <?php echo $this->lang->line('export'); ?></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <form method="post" action="<?php echo site_url('studentfee/addfeegroup') ?>" id="assign_form">


                    <?php
                    if (isset($resultlist)) {
                        ?>
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_fees_group'); ?>
                                    </i> <?php echo form_error('student'); ?></h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <div class="row">
                                    <div class="col-md-12">
                                        
                                        <div class="col-md-12">
                                            <div class=" table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <!--<th><input type="checkbox" id="select_all"/> <?php echo $this->lang->line('all'); ?></th>
-->
                                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                            <th><?php echo $this->lang->line('student_name'); ?></th>

                                                            <th><?php echo $this->lang->line('class'); ?></th>
                                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                                            <!--<th><?php echo $this->lang->line('category'); ?></th>
                                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                                                --> <th><?php echo $this->lang->line('fee_group'); ?></th>
																<th><?php echo $this->lang->line('amount'); ?></th>
                                                        </tr>
                                                        <?php
                                                        if (empty($resultlist)) {
                                                            ?>
                                                            <tr>
                                                                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            $count = 1;
                                                            foreach ($resultlist as $student) {
                                                                ?>
                                                                <tr>

                                                                   <!-- <td> 
                                                                        <?php
                                                                        if ($student['student_fees_master_id'] != 0) {
                                                                            $sel = "checked='checked'";
                                                                        } else {
                                                                            $sel = "";
                                                                        }
                                                                        ?>
                                                                        <input class="checkbox" type="checkbox" name="student_session_id[]"  value="<?php echo $student['student_session_id']; ?>" <?php echo $sel; ?>/>
                                                                        <input type="hidden" name="student_fees_master_id_<?php echo $student['student_session_id']; ?>" value="<?php echo $student['student_fees_master_id']; ?>">
                                                                        <input type="hidden" name="student_ids[]" value="<?php echo $student['student_session_id']; ?>">
                                                                    </td>-->

                                                                    <td><?php echo $student['admission_no']; ?></td>
                                                                    <td><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                                    <td><?php echo $student['father_name']; ?></td>
                                                                    <!--<td><?php echo $student['category']; ?></td>
                                                                    <td><?php echo $student['gender']; ?></td> -->
																	<td><?php echo $student['group_name']; ?></td>
																	<td><?php echo $student['actual_amount']; ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            $count++;
                                                        }
                                                        ?>
                                                    </tbody></table>

                                            </div>
                                            <!--<button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                            </button>-->

                                            <br/>
                                            <br/>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>

        </div> 

    </section>
</div>

<script type="text/javascript">

//select all checkboxes
    $("#select_all").change(function () {  //"select all" change 
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

//".checkbox" change 
    $('.checkbox').change(function () {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });

    $("#assign_form").submit(function (e) {
        if (confirm('Are you sure?')) {
            var $this = $('.allot-fees');
            $this.button('loading');
            $.ajax({
                type: "POST",
                dataType: 'Json',
                url: $("#assign_form").attr('action'),
                data: $("#assign_form").serialize(), // serializes the form's elements.
                success: function (data)
                {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                    }

                    $this.button('reset');
                }
            });

        }
        e.preventDefault();

    });
    
    $("#search").click(function (e) {
        $("#action").val('search');
        $("#action_form").submit();
    });
    
    $("#export").click(function (e) {
        $("#action").val('export');
        $("#action_form").submit();
    });


</script>
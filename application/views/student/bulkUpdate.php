<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> Update Students <small><?php echo $this->lang->line('student1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="pull-right box-tools">
                            <!-- <a href="<?php //echo base_url(); ?>admin/schoolhouse/changePassword"> <button class="btn btn-sm btn-danger">Run Query To Update Passwords</button> </a> -->
                            <a href="<?php echo site_url('student/updateExportFormat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('dl_sample_import'); ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if($this->session->flashdata('msg')) { ?> <div>  <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>
                        <?php if ($this->session->has_userdata('success')) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php
                                echo $this->session->userdata('success');
                                $this->session->unset_userdata('success');
                                ?>
                            </div>

                        <?php } ?>
                        <?php if ($this->session->has_userdata('error')) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php
                                echo $this->session->userdata('error');
                                $this->session->unset_userdata('error');
                                ?>
                            </div>

                        <?php } ?>

                        <br/>
                        1. Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.<br/>
                        2. If the column you are trying to import is date make sure that is formatted in format Y-m-d (2018-06-06).<br/>
                        3. Record will be updated on basis of admission number<br/>
                        4. For student Gender use Male, Female value.<br/>
                        5. For student Blood Group use O+, A+, B+, AB+, O-, A-, B-, AB- value.<br/>

                        <hr/></div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                            <tr>
                                <?php foreach ($fields as $key => $value) {

                                    if($value == 'adhar_no'){
                                        $value = "national_identification_no";
                                    }

                                    if($value == 'samagra_id'){
                                        $value = "local_identification_no";
                                    }
                                    if($value == 'firstname'){
                                        $value = "first_name";
                                    }
                                    if($value == 'lastname'){
                                        $value = "last_name";
                                    }
                                    if($value == 'dob'){
                                        $value = "date_of_birth";
                                    }
                                    if($value == 'mobileno'){
                                        $value = "mobile_no";
                                    }
                                    if($value == 'previous_school'){
                                        $value = "previous_school_details";
                                    }
                                    $add = "";
                                    if(($value == "admission_no")  ||($value == "firstname") ||($value == "dob")){
                                        $add = "<span class=text-red>*</span>";
                                    }
                                    ?>
                                    <th><?php echo $add."<span>".$this->lang->line($value)."</span>"; ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <?php foreach ($fields as $key => $value) {
                                    ?>
                                    <td><?php echo "Sample Data" ?></td>
                                <?php } ?>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <hr/>
                    <form action="<?php echo site_url('student/bulkUploadPost') ?>"  id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div>
                                            <input class="filestyle form-control" type='file' name='file' id="file" size='20' accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div>
                                </div>
                                <div class="col-md pt20" style="margin-right: 2%;">
                                    <button type="submit" class="btn btn-info pull-right">Update Students</button>
                                </div>

                            </div>
                        </div>


                    </form>

                    <div>



                    </div>
                </div>
                </div>
    </section>
    
    <div class="" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> Update Class and Section <small><?php echo $this->lang->line('student1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
                        <div class="pull-right box-tools">
                            <a href="<?php echo site_url('student/classExportFormat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('dl_sample_import'); ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                         <br/>
                        1. Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.<br/>
                        <hr/></div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                            <tr>

                                <th><span class="text-red" style="float: left; padding-right: 1%;">*</span> <span>Admission Number</span></th>
                                <th><span class="text-red" style="float: left; padding-right: 1%;">*</span> <span>Class Name</span></th>
                                <th><span class="text-red" style="float: left; padding-right: 1%;">*</span> <span>Section Name</span></th>


                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><p style="padding-left: 1%;"><?php echo "Sample Data" ?></p></td>
                                <td><p style="padding-left: 1%;"><?php echo "Sample Data" ?></p></td>
                                <td><p style="padding-left: 1%;"><?php echo "Sample Data" ?></p></td>

                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <hr/>
                    <form action="<?php echo site_url('student/classSectionImport') ?>"  id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div>
                                            <input class="filestyle form-control" type='file' name='file' id="file" size='20' accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div></div>
                                <div class="col-md-6 pt20">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('import_student'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div>
                    </div>
                    
                        
                    
        </div>
    </section>
</div>

<script type="text/javascript">
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
        $("#sampledata").DataTable({
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false,});

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
</script>
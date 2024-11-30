<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('bulk_import_books'); ?> <small><?php echo $this->lang->line('student1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="pull-right box-tools">
                            <a href="<?php echo site_url('admin/BookStore/exportformat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('dl_sample_import'); ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if($this->session->flashdata('msg')) { ?> <div>  <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>
                        <br/>
                        1. Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.<br/>
                        2. If the column you are trying to import is date make sure that is formatted in format Y-m-d (2018-06-06).<br/>
                        3. Duplicate ISBN (unique) rows will not be imported.<br/>
                        <hr/></div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                            <tr>
                                <?php foreach ($fields as $key => $value) {

                                    if($value != "bluk_brand") {
                                        $add = "<span class=text-red>*</span>";
                                    }else{
                                        $add="<br/>";
                                    }
                                        ?>
                                    <th class="text-center"><?php echo $add."<span>".$this->lang->line($value)."</span>"; ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <?php foreach ($fields as $key => $value) {
                                    ?>
                                    <td class="text-center"><?php echo "Sample Data" ?></td>
                                <?php } ?>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <hr/>
                    <form action="<?php echo site_url('admin/BookStore/import') ?>"  id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div></div>
                                <div class="col-md-6 pt20">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('bulk_import_books'); ?></button>
                                </div>

                            </div>
                        </div>


                    </form>

                    <div>



                    </div>
                </div>
    </section>
</div>

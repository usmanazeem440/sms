<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('add_store_book'); ?> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add_manager'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <form id="form1" action="<?php echo site_url('admin/BookStore/update_book') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->has_userdata('book_success')) { ?>
                                <div class="alert alert-success" role="alert">
                                    <?php 
                                        echo $this->session->userdata('book_success');
                                        $this->session->unset_userdata('book_success');
                                    ?>
                                </div>
                                
                            <?php } ?>

                            <?php if ($this->session->has_userdata('book_error')) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php 
                                        echo $this->session->userdata('book_error');
                                        $this->session->unset_userdata('book_error');
                                    ?>
                                </div>
                                
                            <?php } ?>

                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF();   ?>  
                            
                            <div class="row"> 
                                <div class="col-lg-6">    
                                <input type="hidden" value="<?php echo $book->id; ?>" name="id"/>              
                                    <div class="form-group col-lg-6">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('store_book_id'); ?></label><small class="req"> *</small>
                                        <input autofocus=""  id="book_title" placeholder="" disabled type="text" class="form-control"  value="<?php echo $book->book_id; ?>" required />
                                        <span class="text-danger"><?php echo form_error('store_book_id'); ?></span>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('store_book_name'); ?></label><small class="req"> *</small>
                                        <input id="book_no" name="title" placeholder="" type="text" class="form-control"  value="<?php echo $book->title; ?>" required />
                                        <span class="text-danger"><?php echo form_error('store_book_name'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-lg-6">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('store_book_brand'); ?></label>
                                        <input id="book_no" name="brand" placeholder="" type="text" class="form-control"  value="<?php echo $book->brand; ?>"  />
                                        <span class="text-danger"><?php echo form_error('store_book_brand'); ?></span>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('store_book_price'); ?></label><small class="req"> *</small>
                                        <input id="book_no" name="price" step="any" placeholder="" type="number" class="form-control"  value="<?php echo $book->price; ?>" required />
                                        <span class="text-danger"><?php echo form_error('store_book_price'); ?></span>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('store_book_author'); ?></label><small class="req"> *</small>
                                        <input id="book_no" name="author" placeholder="" type="text" class="form-control"  value="<?php echo $book->author; ?>" required />
                                        <span class="text-danger"><?php echo form_error('store_book_author'); ?></span>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('store_book_quantity'); ?></label><small class="req"> *</small>
                                        <input id="book_no" name="quantity" placeholder="" type="number" class="form-control"  value="<?php echo $book->quantity; ?>" required />
                                        <span class="text-danger"><?php echo form_error('store_book_quantity'); ?></span>
                                    </div>


                                    <div class="col-lg-6">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class_list'); ?></label>
                                        <br/>
                                        <div class="dropdown cq-dropdown" data-name='statuses'>
                                            <button class="btn btn-info btn-sm dropdown-toggle form-control" type="button" id="btndropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Select Class
                                                <span class="caret"></span>
                                            </button>
                                            <?php

                                            $classArray = explode(',', $book->class);

                                            ?>

                                            <ul class="dropdown-menu -expand" aria-labelledby="btndropdown" style="overflow-y: scroll; height: 250px;
                                                        width: 250px;">
                                                <?php foreach($classes as $classs){ ?>
                                                    <li>
                                                        <label class="radio-btn">
                                                            <input type="checkbox"
                                                                   <?php foreach($classArray as $clsAray){
                                                                       $clsAray = trim($clsAray);
                                                                       if(strcasecmp($clsAray, $classs['Tag']) == 0){
                                                                           echo 'checked';
                                                                       }

                                                                   }?>

                                                                   name="classs[]" value='<?php echo  $classs['Tag']; ?>'>
                                                            <?php echo $classs['Tag']; ?>
                                                        </label>
                                                    </li>
                                                <?php }?>
                                            </ul>
                                        </div>

                                    </div>


                                </div>  <!-- /col-lg-6 -->
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">

                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>

            </div><!--/.col (right) -->

        </div>
        <div class="row">

            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {



        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#postdate').datepicker({
            //   format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true
        });

    });

</script>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>
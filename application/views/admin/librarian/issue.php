
<div class="content-wrapper" style="min-height: 946px;">   
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i>  <?php echo $this->lang->line('library'); ?>
        </h1>
    </section>  
    <section class="content">
        <div class="row">         
            <div class="col-md-3">
                <?php
                if ($memberList->member_type == "student") {
                    $this->load->view('admin/librarian/_student');
                } else {
                    $this->load->view('admin/librarian/_teacher');
                }
                ?>       
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('issue_book'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->


                  <div class="box-body">                            
                        
                    <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id); ?>" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <label for="exampleInputEmail1">
                                <?php echo $this->lang->line('book_barcode'); ?>
                            </label>
                            <small class="req"> *</small>
                            <input type="text" name="book_id" class="form-control scan-barcode" placeholder="Search Barcode" />
                        </div>

                        <div class="form-group">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-sm btn-primary pull-right search">
                                    <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id); ?>" method="post" accept-charset="utf-8">
                         <div class="box-body">
                             <?php
                             if ($this->session->flashdata('msg')) {
                                 echo $this->session->flashdata('msg');
                             }
                             ?>

                             <?php echo $this->customlib->getCSRF(); ?>

                             <input id="member_id" name="member_id" type="hidden" class="form-control date" value="<?php echo $memberList->lib_member_id; ?>" />
                            <div class="row">
                                <!-- Book Barcode -->
                                <div class="form-group col-md-4">
                                 <label><?php echo $this->lang->line('book_barcode'); ?></label>
                                 <?php if (isset($bok)) { ?>
                                     <input type="text" class="form-control scanned-barcode" name="book_id" readonly
                                     <?php foreach ($bok as $student) { ?>
                                         value="<?php echo $student['other']; ?>"
                                     <?php } ?> />
                                 <?php } ?>
                                </div>

                                <!-- Book Title -->
                                <div class="form-group col-md-4">
                                 <label><?php echo $this->lang->line('book_title'); ?></label>
                                 <?php if (isset($bok)) { ?>
                                     <input type="text" class="form-control" readonly
                                     <?php foreach ($bok as $student) { ?>
                                         value="<?php echo $student['book_title']; ?>"
                                     <?php } ?> />
                                 <?php } ?>
                                </div>

                                <!-- Due Date -->
                                <div class="form-group col-md-4">
                                 <label><?php echo $this->lang->line('due_date'); ?></label>
                                 <input id="dateto" name="return_date" type="text" class="form-control date"
                                        value="<?php echo set_value('return_date', date($this->customlib->getSchoolDateFormat(), strtotime("+6days"))); ?>" />
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right issue-book"><?php echo $this->lang->line('issue_book'); ?> </button>
                        </div>
                    </form>

                </div> 
                <div class="box box-primary">
                    <div class="nav-tabs-custom">
                       <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?>  <?php echo $this->lang->line('view'); ?></a></li>
                            <li class=""><a href="#tab_2" class="tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('fine'); ?> <?php echo $this->lang->line('details'); ?> <?php echo $this->lang->line('view'); ?></a></li>
                        </ul>   
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('book_issued'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <div class="box-body">                            
                        <div class="table-responsive mailbox-messages">
                        <div class="tab-content">    
                         <div class="download_label"><?php echo $this->lang->line('book_issued'); ?></div>
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('book_no'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date'); ?></th>
                                        <th><?php echo $this->lang->line('due_date'); ?></th>
                                        <th><?php echo $this->lang->line('return_date'); ?></th>

                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo 'Overdue '. $this->lang->line('days'); ?></th>
                                        <th><?php echo$this->lang->line('fine'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($issued_books)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        $fine_pending = false;
                                        foreach ($issued_books as $book) {
                                                $current_date= date("Y-m-d");
                                                $return_date= $book['return_date'];
                                                if ($book['is_returned'] == 0) {
                                                    if (  $current_date>= $return_date) {
                                                        $status =  "Overdue";
                                                    } else{
                                                        $status =  "Due";
                                                    }
                                                } else {
                                                    $status =  "Returned";
                                                }
                                                $tr_class = ($status == 'Overdue') ? 'bg-danger' : '';
                                                if ($book['is_returned'] == 1 && $book['fine'] > 0 && $book['fine_status'] == '') {
                                                    $tr_class = 'bg-danger';
                                                    $fine_pending = true;
                                                }
                                            ?>
                                            <tr class="<?= $tr_class ?>">
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_title'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_no'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['issue_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['return_date'])) ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['returned_at'])) ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $status; ?>      
                                                </td>  
                                                <td class="mailbox-name">
                                                    <?php 
                                                    if ($book['is_returned'] == 0) {        
                                                        $a = $book['return_date'];
                                                        $b = date("Y-m-d");
                                                       
                                                    } else {
                                                        $a = $book['return_date'];
                                                        $b = $book['returned_at'];
                                                    }

                                                    $date1 =date_create("$a");
                                                    $date2 =date_create("$b");
                                                    $diff = date_diff($date1,$date2);
                                                    if ($b > $a) {
                                                        echo $diff->format("%a days");
                                                    } else { echo "0"; }
                                                    ?> 
                                                </td>   
                                                <td>
                                                    <?php 
                                                    if ($book['is_returned'] == 1 && $book['fine'] > 0) {
                                                        echo ($book['fine_status'] == '') ? 'Un-paid' : 'Paid';
                                                    } else {
                                                        echo 'N/A';
                                                    } ?>
                                                </td>         
                                                <td class="mailbox-date pull-right">
                                                    <?php if ($book['is_returned'] == 0) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/member/bookreturn/<?php echo $book['id'] . "/" . $memberList->lib_member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Return" onclick="return confirm('Are you sure you want to return this book?')">
                                                            <i class="fa fa-mail-reply"></i>
                                                        </a>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table-1 -->
                         </div>
                         <div class="tab-pane" id="tab_2">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('book_no'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date'); ?></th>
                                        <th><?php echo $this->lang->line('return_date'); ?></th>
                                        <th><?php echo $this->lang->line('days'); ?></th>
                                        <th><?php echo $this->lang->line('fine'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($fineList)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($fineList as $fine) {
                                            ?>
                                            <tr class="<?= ($fine['status'] == 0) ?  'bg-danger' : 'bg-success' ?>">
                                                <td class="mailbox-name">
                                                    <?php echo $fine['book_no'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['issue_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['return_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['days'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $currency_symbol . " " . $fine['balance'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($fine['status'] == 0) {
                                                        echo 'Un-paid';
                                                    } else {
                                                        echo 'paid';
                                                    }?>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                  <?php if ($fine['status'] == 0) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/member/payfine/<?php echo $fine['fid'] . "/" . $memberList->lib_member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Pay Fine" onclick="return confirm('Are you sure you want to pay fine?')">
                                                            <i class="fa fa-plus"></i>
                                                        </a>

                                                        <?php
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>  
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table2 -->
                         </div>   

                        </div>
                        </div><!-- /.mail-box-messages -->

                    </div><!-- /.box-body -->

                    </form>
                 </div> 
                  </div> 
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    var fine_pending = false;
</script>
<?php if($fine_pending) { ?>
    <script type="text/javascript">
        var fine_pending = true;
    </script>
<?php }?>

<script type="text/javascript">

    $(document).ready(function () {
        if (fine_pending) {
            $('.tab_2').css('color', 'red');
        }
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $(".date").datepicker({
            // format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true,
            todayHighlight: true,
            startDate: new Date(),
            endDate: "+6d"

        });
    });
    $(document).ready(function () {
        setTimeout(function() {$('.alert').fadeOut(3000);}, 3100);

        $(document).on('click', '.search', function(e) {
            const barcode = $('.scan-barcode').val();

            // Check if the barcode field is empty
            if (barcode == '') {
                e.preventDefault(); // Prevent form submission
                $('.scan-barcode').addClass('has-error');
                alert('Please scan the barcode first');
                return false; // Stop further actions
            } else {
                // Remove the error class if the field is not empty
                $('.scan-barcode').removeClass('has-error');
                // Allow form submission
                return true;
            }
        });

        $(document).on('click', '.issue-book', function(e) {
            const barcode = $('.scanned-barcode').val();

            // Check if the barcode field is empty
            if (barcode == '') {
                e.preventDefault(); // Prevent form submission
                $('.scan-barcode').addClass('has-error');
                alert('Please scan the barcode first');
                return false; // Stop further actions
            } else {
                // Remove the error class if the field is not empty
                $('.scan-barcode').removeClass('has-error');
                // Allow form submission
                return true;
            }
        });

    });
</script>

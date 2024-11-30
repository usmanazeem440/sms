
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
                        
 <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            
     <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('book_barcode'); ?></label><small class="req"> *</small>
                                        <input type="text" name="book_id" class="form-control" placeholder="Search Barcode"/>
                                    </div>

                        <div class="form-group">
              <div class="box-footer">
                            <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>

                            </div>


                        </div><!-- /.box-body -->
</form>



         <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">

                        <div class="box-body"> 
                                                  
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                               
                            }
                            ?>  
                            

                            <?php echo $this->customlib->getCSRF(); ?>

                            <input id="member_id" name="member_id"  type="hidden" class="form-control date"  value="<?php echo $memberList->lib_member_id; ?>" />

                         <!--    <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_barcode'); ?></label>
                                <input id="book_id" name="book_id"  type="text" class="form-control"  value="" placeholder="<?php echo $this->lang->line('book_barcode'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_id'); ?></span>
                            </div> -->

                   <div class="form-group">
                                <label><?php echo $this->lang->line('book_barcode'); ?></label>
                               
                                    <?php
                if (isset($bok)) {
                    ?>
        
                                
                           
                        
                        
                           <input  type="text" class="form-control" name="book_id" readonly 
                        <?php foreach($bok as $student){ ?>
                            value="<?php echo $student['other'];?>"/>           
                        <?php }?>
                           
                  
                <?php } ?>

                            </div>


                        <div class="form-group">
                                <label><?php echo $this->lang->line('book_title'); ?></label>
                               
                                    <?php
                if (isset($bok)) {
                    ?>
        
                                
                           
                        
                       
                        <input  type="text" class="form-control" readonly 
                        <?php foreach($bok as $student){ ?>
                        value="<?php echo $student['book_title'];?>"/>          
                        <?php }?>
                           
                  
                <?php } ?>

                            </div>


                            <div class="form-group">
                                <label><?php echo $this->lang->line('due_date'); ?></label>
                                <input id="dateto" name="return_date"  type="text" class="form-control date"  value="<?php echo set_value('return_date', date($this->customlib->getSchoolDateFormat(), strtotime("+6days")) ); ?>"/>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div> 
                <div class="box box-primary">
                    <div class="nav-tabs-custom">
                       <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?>  <?php echo $this->lang->line('view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('fine'); ?> <?php echo $this->lang->line('details'); ?> <?php echo $this->lang->line('view'); ?></a></li>
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
                                        <th><?php echo $this->lang->line('return_date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('days'); ?></th>
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
                                        foreach ($issued_books as $book) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_title'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_no'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['issue_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['return_date'])) ?></td>
                                                <td class="mailbox-name">
                                                <?php if ($book['is_returned'] == 0) {
                                                        ?>
                                                <?php 
                                                   $a= date("Y-m-d");
                                                   $b= $book['return_date'];
                                                   if (  $a>=$b) {
                                                     echo "Overdue";
                                                  }
                                                  else{
                                                    echo "Due";
                                                  }
                                                   ?>
                                                 <?php
                                                    }
                                                    ?>      
                                                   </td>  
                                                   <td class="mailbox-name">
                                                 <?php if ($book['is_returned'] == 0) {
                                                        ?>   
                                                   <?php                                                 
                                                   $a= $book['return_date'];
                                                   $b= date("Y-m-d");
                                                   $date1=date_create("$a");
                                                   $date2=date_create("$b");
                                                   $diff=date_diff($date1,$date2);
                                                   if ($b > $a) {
                                                   echo $diff->format("%a days");
                                                  }
                                                  else{
                                                    echo "0";
                                                  }
                                                   ?>
                                                  <?php
                                                    }
                                                    ?> 
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
                                        <th><?php echo $this->lang->line('amount'); ?> <?php echo $this->lang->line('paid'); ?></th>
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
                                            <tr>
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
                                                    <?php echo "SR ". $fine['balance'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo  $fine['amount_paid'] ?> 
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                  <?php if ($fine['status'] == 0) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/member/payfine/<?php echo $fine['fid'] . "/" . $memberList->lib_member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Pay Fine" onclick="return confirm('Are you sure you want to pay fine?')">
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

    $(document).ready(function () {
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
    });
</script>

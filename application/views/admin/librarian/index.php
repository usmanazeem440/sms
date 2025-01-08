<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
    .submit-btn {
        margin-top: 23px;
    }
    @media screen and (max-width: 767px) {
        .submit-btn {
            margin-top: 5px;
            margin-bottom: 10px;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header" style="display: flex; justify-content: space-between;">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('library'); ?></h1> 
            <div>
              <code style="font-size: 15px;" >Fine Per Day = <?= $currency_symbol ?><span class="fine_text"><?= $fine_amount?></span></code>
              <i class="fa fa-edit edit-fine" 

                data-toggle="modal" data-target="#fine_amount_modal"
                data-fine_amount = "<?= $fine_amount; ?>"
              style=" font-size:16px; color: #112954; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Edit Fine"></i>
            </div>
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="row">  
            <div class="col-md-5 col-sm-12">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">

                        <h3 class="box-title titlefix"><?php echo $this->lang->line('members'); ?></h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('members'); ?></div>
                            <table  class="table table-striped table-bordered table-hover members" id="members">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('member_id'); ?></th>
                                        <th><?php echo $this->lang->line('library_card_no'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('member_type'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>


                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="getdetails">
                                                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-md-7 col-sm-12">              
                <div class="box box-primary">
                    <div class="nav-tabs-custom">
                       <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?>  <?php echo $this->lang->line('view'); ?></a></li>
                            <li class=""><a href="#tab_2" class="tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('fine'); ?> <?php echo $this->lang->line('details'); ?> <?php echo $this->lang->line('view'); ?></a></li>
                        </ul>   
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('book_issued'); ?></h3>
                            <div class="alert alert-success text-left" style="display: none;"></div>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">                            
                            <div class="table-responsive mailbox-messages">
                            <div class="tab-content">    
                             <div class="download_label"><?php echo $this->lang->line('book_issued'); ?></div>
                                <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                <table id="issued-table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th><?php echo $this->lang->line('book_title'); ?></th>
                                            <th><?php echo $this->lang->line('book_no'); ?></th>
                                            <th><?php echo ('issued'); ?></th>
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

                                    </tbody>
                                </table><!-- /.table-1 -->
                             </div>
                             <div class="tab-pane" id="tab_2">
                                <table class="table table-striped table-bordered table-hover fine-table">
                                    <thead>
                                        <tr>

                                            <th><?php echo $this->lang->line('book_no'); ?></th>
                                            <th>Member</th>
                                            <th><?php echo $this->lang->line('issue_date'); ?></th>
                                            <th><?php echo $this->lang->line('due_date'); ?></th>
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

                    </div> 
                </div> 
            </div> 
        </div>
    </section>
</div>

<div class="modal fade" id="fine_amount_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center"> Update Fine Amount</h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Fine Amount </label><small class="req">*</small>
                            <div class="col-sm-9">

                                <input type="text" class="form-control twodecimel" id="fine" >

                                <span class="text-danger" id="fine_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="box-body">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="button" class="btn cfees update_fine" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    
    $(document).ready(function () {

        $("#fine_amount_modal").on('shown.bs.modal', function (e) {
            var fine_amount = $('.edit-fine').data('fine_amount');
            $('#fine').val(fine_amount);
        })

        $(document).on('click', '.update_fine', function (e) {
            var $this = $(this);

            var fine = $('#fine').val();
            if (parseFloat(fine) == 0 || fine == '') {
                alert('Please add fine amount');
                $('#fine').addClass('has-error');
                return false;
            } else {
                $('#fine').removeClass('has-error');
            }
            // if(confirm('Are you sure you want to update fine?')) {
                $this.button('loading');
                $.ajax({
                    url: '<?php echo site_url("admin/member/updatefine") ?>',
                    type: 'post',
                    data: {
                        fine: fine,
                    },
                    dataType: 'json',
                    success: function (response) {
                        $this.button('reset');
                        if (response.status == "success") {
                            alert('fine updated successfully');
                            $('.fine_text').text(fine);
                            $("#fine_amount_modal").modal('hide');
                        }
                    }
                });
            // }
        });

        // Datatable Initialize End
         var table = $('#members').DataTable({
            "drawCallback": function(result) {
                // $('.detail_popover').popover({
                //     placement: 'right',
                //     trigger: 'hover',
                //     container: 'body',
                //     html: true,
                //     content: function () {
                //         return $(this).closest('td').find('.fee_detail_popover').html();
                //     }
                // });
            },
            "serverSide": true,
            "pagingType": 'first_last_numbers',
            //"stateSave": true,
            "paging": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[ 0, "desc" ]],
            // "dom": '<"top"f>rt<"main"<"bottom main-info"i><"main-lenght"l><"main-pagination"p>><"clear">',
            "pageLength": 25,   
            "ordering"   :false,
            "processing": true,
            "ajax":{
             "url": base_url +"admin/member/getMembersList",
             "dataType": "json",
             "type": "POST",
                           },
        "columns": [
                  { "data": "member_id" },
                  { "data": "library_card_no" },
                  { "data": "admission_no" },
                  { "data": "name" },
                  { "data": "member_type" },
                  { "data": "class" },
                  { "data": "action" },
               ]     

        });// Datatable Initialize End


        var table_2 = $('#issued-table').DataTable({
            "rowCallback": function( row, data ) {
                if (data.row_status == true) {
                    $( row).addClass('bg-danger');
                }
            },
             "serverSide": true,
             "pagingType": 'first_last_numbers',
             //"stateSave": true,
             "paging": true,
             "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
             "order": [[ 0, "desc" ]],
             // "dom": '<"top"f>rt<"main"<"bottom main-info"i><"main-lenght"l><"main-pagination"p>><"clear">',
             "pageLength": 25,   
             "ordering"   :false,
             "processing": true,
             "ajax":{
              "url": base_url +"admin/member/getIssuedList",
              "dataType": "json",
              "type": "POST",
                            },
         "columns": [
                   { "data": "book_title" },
                   { "data": "book_no" },
                   { "data": "issued" },
                   { "data": "issue_date" },
                   { "data": "return_date" },
                   { "data": "returned_at" },
                   { "data": "status" },
                   { "data": "overdue_days" },
                   { "data": "fine" },
                   { "data": "action" },
                ]     

        });// Datatable Initialize End

        var table_3 = $('.fine-table').DataTable({
            "rowCallback": function( row, data ) {
                if (data.row_status == true) {
                    $( row).addClass('bg-danger');
                }
            },
             "serverSide": true,
             "pagingType": 'first_last_numbers',
             //"stateSave": true,
             "paging": true,
             "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
             "order": [[ 0, "desc" ]],
             // "dom": '<"top"f>rt<"main"<"bottom main-info"i><"main-lenght"l><"main-pagination"p>><"clear">',
             "pageLength": 25,   
             "ordering"   :false,
             "processing": true,
             "ajax":{
              "url": base_url +"admin/member/getFineList",
              "dataType": "json",
              "type": "POST",
                            },
         "columns": [
                   { "data": "book_no" },
                   { "data": "member" },
                   { "data": "issue_date" },
                   { "data": "return_date" },
                   { "data": "returned_at" },
                   { "data": "days" },
                   { "data": "balance" },
                   { "data": "status" },
                   { "data": "action" },
                ]     

        });// Datatable Initialize End

        $(document).on('click', '.return', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to return this book?')) {
                var issue_id = $(this).data('issue_id');
                var member_id = $(this).data('member_id');

                $.ajax({
                    url: base_url +"admin/member/bookreturn_ajax",
                    type: "POST",
                    data: {issue_id: issue_id, member_id: member_id},        
                    success: function(data) {
                        if (data.success) {
                            $('.alert').html(data.msg);
                            $('.alert').addClass('alert-success').removeClass('alert-danger');
                            if(data.fine) {
                                $('.alert').addClass('alert-danger').removeClass('alert-success');
                            }
                            $('.alert').show();
                            table_2.ajax.reload();
                            table_3.ajax.reload();

                            setTimeout(() => {
                                $('.alert').hide();
                            }, 20000);
                        }
                    }
                })
            }
        })

        $(document).on('click', '.payfine', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to pay fine?')) {
                var fid = $(this).data('fid');
                var member_id = $(this).data('member_id');
                var fine = $(this).data('fine');

                $.ajax({
                    url: base_url +"admin/member/payfine_ajax",
                    type: "POST",
                    data: {fid: fid, member_id: member_id, fine: fine},        
                    success: function(data) {
                        if (data.success) {
                            $('.alert').html(data.msg);
                            $('.alert').addClass('alert-success').removeClass('alert-danger');

                        } else {
                            $('.alert').html(data.msg);
                            $('.alert').addClass('alert-danger').removeClass('alert-success');
                        }

                        $('.alert').show();
                        // table_2.ajax.reload();
                        table_3.ajax.reload();

                        setTimeout(() => {
                            $('.alert').hide();
                        }, 20000);
                    }
                })
            }
        })
    });
</script>
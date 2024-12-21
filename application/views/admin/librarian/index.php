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
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('library'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="row">  
            <div class="col-md-12">              
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
        </div>
    </section>
</div>
<script type="text/javascript">
    
    $(document).ready(function () {

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
    });
</script>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('invoices'); ?> </h1>
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
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/invoices/index') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_parent'); ?></label>
                                                <input type="text" name="search_text" value="<?php echo $guardian_id;?>" class="form-control" placeholder="<?php echo $this->lang->line('search_by_parent'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <?php
                if (isset($invoiceslist)) {
                    ?>
                    <div class="box box-info">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('invoices'); ?> <?php echo $this->lang->line('list'); ?>
                                </i> <?php echo form_error('student'); ?></h3>

                            <?php if(isset($parent_name) && $is_parent_search == "true" && $this->rbac->hasPrivilege('collect_fees', 'can_add')){ ?>
                                <div class="box-tools pull-right">
                                    <a  href="<?php echo base_url(); ?>studentfee/addFeeByParent/<?php echo $parent_name; ?>" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="">
                                        <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_fees'); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="box-body table-responsive">

                            <div class="download_label"><?php echo $this->lang->line('invoices'); ?> <?php echo $this->lang->line('list'); ?></div>
                            <table class="table table-striped table-bordered table-hover withoutPagination ">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                    <th><?php echo $this->lang->line('guardian_id'); ?></th>
                                    <th><?php echo $this->lang->line('admission_no'); ?></th>
                                    <th><?php echo $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('amount'); ?></th>
                                    <th><?php echo $this->lang->line('status'); ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1;
                                foreach ($invoiceslist as $invoice) {
                                    ?>
                                    <tr>
                                        <td><?php echo $invoice['invoice_number']; ?></td>
                                        <td><?php echo $invoice['guardian_id']; ?></td>
                                        <td><?php echo $invoice['admission_no']; ?></td>
                                        <td><?php echo $invoice['invoice_date']; ?></td>
                                        <td><?php echo $currency_symbol." ".$invoice['invoice_amount']; ?></td>
                                        <td><?php echo $invoice['status']; ?></td>
                                        <td>
                                            <button type="button"
                                                        data-invoice_id="<?php echo $invoice['id']; ?>"
                                                       
                                                        class="btn btn-xs btn-default invoices_details"
                                                        title="<?php echo $this->lang->line('invoices_details'); ?>"
                                                ><i class="fa fa-eye"></i> En</button>
                                            <button type="button"
                                                        data-invoice_id="<?php echo $invoice['id']; ?>"
                                                       
                                                        class="btn btn-xs btn-default invoices_details_ar"
                                                        title="<?php echo $this->lang->line('invoices_details'); ?>"
                                                ><i class="fa fa-eye"></i> Ar</button>
                                                
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $count++;
                                ?>
                                </tbody></table>
                            <?php echo $links;?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>

    </section>
</div>

<script type="text/javascript">
    $(".phone").text(function(i, text) {
        text = text.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        return text;
    });
</script>


<script type="text/javascript">
   
var base_url = '<?php echo base_url() ?>';
function Popup(data) {
    // Create the iframe and set attributes
    var frame1 = $('<iframe />');
    frame1[0].name = "frame1";
    frame1.css({ "position": "absolute", "top": "-1000000px" });
    $("body").append(frame1);

    // Reference the iframe's document
    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;

    // Ensure data is present before proceeding
    if (!data) {
        console.error("No data provided to print.");
        return;
    }

    // Write the HTML structure into the iframe document
    frameDoc.document.open();
    frameDoc.document.write('<html><head><title>Print</title>');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    // Add other styles as needed...
    frameDoc.document.write('</head><body>');
    frameDoc.document.write(data);
    frameDoc.document.write('</body></html>');
    frameDoc.document.close();

    // Print the content after the iframe is fully loaded
    frame1[0].onload = function () {
        setTimeout(function () {
            try {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
            } catch (e) {
                console.error("Error printing: ", e);
            } finally {
                frame1.remove(); // Clean up after printing
            }
        }, 1000);
    };
    return true;
}
$(document).ready(function () {
        $(document).on('click', '.invoices_details', function () {
            var array_to_print = [];
            var invoice_id = $(this).data('invoice_id');
            
            
                $.ajax({
                    url: '<?php echo site_url("admin/invoices/print_invoices") ?>',
                    type: 'post',
                    data: {'invoice_id': invoice_id},
                    success: function (response) {
                        Popup(response);
                    }
                });
        });
        
        
        $(document).on('click', '.invoices_details_ar', function () {
            var array_to_print = [];
            var invoice_id = $(this).data('invoice_id');
            $.ajax({
                    url: '<?php echo site_url("admin/invoices/print_invoices_set_ar") ?>',
                    type: 'post',
                    async: false,
                    data: {'invoice_id': invoice_id},
                    success: function (response) {
                        
                    }
                });
            
                $.ajax({
                    url: '<?php echo site_url("admin/invoices/print_invoices_ar") ?>',
                    type: 'post',
                    async: false,
                    data: {'invoice_id': invoice_id},
                    success: function (response) {
                        Popup(response);
                    }
                });
                
        });
    });

</script>

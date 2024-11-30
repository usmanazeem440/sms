<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('library'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/book/getall') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_title'); ?></label>
                                                <input type="text" name="search_title" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_title'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_no'); ?></label>
                                                <input type="text" name="search_book_no" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_no'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_isbn'); ?></label>
                                                <input type="text" name="search_isbn" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_isbn'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_author'); ?></label>
                                                <input type="text" name="search_author" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_author'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_barcode'); ?></label>
                                                <input type="text" name="search_barcode" class="form-control" placeholder="<?php echo $this->lang->line('search_barcode'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_subject'); ?></label>
                                                <input type="text" name="search_subject" class="form-control" placeholder="<?php echo $this->lang->line('search_subject'); ?>">
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
                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('book_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">


                            <div class="download_label"><?php echo $this->lang->line('book_list'); ?></div>
                            <table id="" class="table table-striped table-bordered table-hover withoutPagination" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('barcode'); ?></th>
                                        <th><?php echo $this->lang->line('book_no'); ?></th>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        
                                        <th><?php echo $this->lang->line('isbn_no'); ?></th>
                                        <th><?php echo $this->lang->line('publisher'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('author'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('location'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('tags'); ?></th>
                                        <th><?php echo $this->lang->line('active'); ?></th>
                                        <th><?php echo $this->lang->line('available'); ?></th>
                                        <th class="no-print text text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($listbook as $book) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $book['other'] ?></td>
                                            <td class="mailbox-name"> <?php echo $book['book_no'] ?></td>
                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover" class="detail_popover"><?php echo $book['book_title'] ?></a>
                                                <div class="fee_detail_popover" style="display: none">
                                                    <?php
                                                    if ($book['description'] == "") {
                                                        ?>
                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <p class="text text-info"><?php echo $book['description']; ?></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            
                                            <td class="mailbox-name"> <?php echo $book['isbn_no'] ?></td>
                                            <td class="mailbox-name"> <?php echo $book['publish'] ?></td>
                                            <td class="mailbox-name"> <?php echo $book['author'] ?></td>
                                            <td class="mailbox-name"><?php echo $book['subject'] ?></td>
                                            <td class="mailbox-name"><?php echo $book['location'] ?></td>
                                            <td class="mailbox-name"> <?php echo $book['class'] ?></td>
                                            <td class="mailbox-name"> <?php echo $book['tags'] ?></td>
                                            <td class="mailbox-name"><?php echo $book['is_active'] ?></td>
                                            <td class="mailbox-name"> <?php echo $book['available'] ?></td>
                                            
                                            <td class="mailbox-date no-print text text-right">
                                                <?php if ($this->rbac->hasPrivilege('books', 'can_edit')) { ?> 
                                                    <a href="<?php echo base_url(); ?>admin/book/edit/<?php echo $book['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php }if ($this->rbac->hasPrivilege('books', 'can_delete')) { ?> 
                                                    <a href="<?php echo base_url(); ?>admin/book/delete/<?php echo $book['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                            <?php echo $links;?>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                    </div>
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <!-- general form elements disabled -->
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#postdate').datepicker({
            // format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true
        });
        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });
</script>



<script type="text/javascript">
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


    $("#print_div").click(function () {
        Popup($('#bklist').html());
    });


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
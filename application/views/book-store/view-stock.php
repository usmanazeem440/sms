<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('store_view_stock'); ?> <small><?php echo $this->lang->line('class1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
           
            <div class="col-md-12">   
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
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('store_list_stock'); ?></h3>                   
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('store_list_stock'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('store_book_id'); ?></th>
                                        <th class=""><?php echo $this->lang->line('store_book_name'); ?></th>
                                        <th class=""><?php echo $this->lang->line('store_book_author'); ?></th>
                                        <th class=""><?php echo $this->lang->line('store_book_price'); ?></th>
                                        <th class=""><?php echo $this->lang->line('store_book_quantity'); ?></th>
                                         <?php if($this->rbac->hasPrivilege('book_store', 'can_edit')){ ?>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                         <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php
                                   if (isset($books)) {
                                     
                                    foreach ($books as $p) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $p['book_id'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['title'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['author'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['price'] ?></td>
                                            <td class="mailbox-name"><?php echo $p['quantity'] ?></td>
                                             <?php if($this->rbac->hasPrivilege('book_store', 'can_edit')){ ?>
                                            <td  class="mailbox-date pull-right">
                                                    <a href="<?php echo base_url(); ?>admin/BookStore/edit_book/<?php echo $p['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="title">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>admin/BookStore/disable/<?php echo $p['id'] ?>" onclick="return confirm('Are you sure to delete this book?')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="title" onclick="">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                            </td> <?php } ?>
                                        </tr>
                                   <?php } 
                                } ?>
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
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>
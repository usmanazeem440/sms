<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-caret-right"></i> <?php echo $this->lang->line('std_place_order'); ?>
        </h1>
    </section>
    <section class="content">

        <div class="row">
            <?php if($this->rbac->hasPrivilege('order','can_add')){ ?>
            <div class="col-md-6">
                <form action="<?php echo base_url(); ?>admin/BookStore/orderItems" method="POST">
                    <input type="hidden" name="parent_id" value="<?php echo $parent_id ?>">
                    <div id="finalItems">

                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <b class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('lpc_place_order'); ?></b>

                            <span class="pull-right">
                                 <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Select Books By Class</button>
                            </span>


                        </div>
                        <div class="box-body">
                            <table id="stock-table"  class="table table-striped table-bordered table-hover example">
                                <thead>
                                <tr>

                                    <th><?php echo $this->lang->line('book_title'); ?></th>
                                    <th><?php echo $this->lang->line('class'); ?></th>
                                    <th><?php echo $this->lang->line('store_book_brand'); ?></th>
                                    <th><?php echo $this->lang->line('store_book_author'); ?></th>
                                    <th><?php echo $this->lang->line('store_stock'); ?></th>
                                    <th><?php echo $this->lang->line('store_book_price'); ?></th>
                                    <th class="text text-right"><?php echo $this->lang->line('action'); ?> </th>
                                </tr>
                                </thead>
                                <tbody id="stock-body">

                                </tbody>
                            </table>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('std_proceed'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
             <?php } ?>


            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('store_orders'); ?></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                            <tr>

                                <th><?php echo $this->lang->line('std_order_id'); ?></th>
                                <th><?php echo $this->lang->line('order_placed_by'); ?></th>
                                <th><?php echo $this->lang->line('std_no_books'); ?></th>
                                <th><?php echo $this->lang->line('std_total_price'); ?></th>
                                <th><?php echo $this->lang->line('std_status'); ?></th>
                                <th class="text text-right"><?php echo $this->lang->line('action'); ?> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($previous_orders as $order){ ?>
                                <tr>
                                    <td class="text">
                                        <?php echo $order['order_id'] ?>
                                    </td>

                                    <td class="text">
                                        <?php echo $order['order_placed_by'] ?>
                                    </td>

                                    <td  class="text">
                                        <?php echo $order['qty'] ?>
                                    </td>
                                    <td  class="text">
                                        <?php  
                                        
                                        
                                        $price = (int)$order['price'];
                                        $tax = (int)$sales_tax["sales_tax"];

                                        echo number_format(($tax * $price /100) + $price, 2, '.', '')
                                        
                                        
                                        ?>
                                    </td>
                                    <td  class="text">
                                        <?php if($order['status'] == '0'){ ?>
                                            <span class="text-danger">Pending</span>
                                        <?php }elseif($order['status'] == '1') {?>
                                            <span class="text-success">Completed</span>
                                        <?php }else{?>
                                            <span class="text-warning">Partially Completed</span>
                                        <?php } ?>

                                    </td>
                                    <td  class="text text-right">
                                        <a href="<?php echo site_url('admin/BookStore/printReceipt/'). $order['order_id'] ?>" class="btn btn-xs btn-success">Print Receipt</a>
                                        <?php if($order['status'] == '1'){ ?>
                                            <a href="<?php echo site_url('admin/BookStore/viewCompleteOrder/'). $order['order_id'] ?>"  class="btn btn-xs btn-info myCollectFeeBtn " title="<?php echo $this->lang->line('details'); ?>"> <?php echo $this->lang->line('details'); ?> </a>
                                        <?php }else {?>
                                            <a href="<?php echo site_url('admin/BookStore/viewOrder/'). $order['order_id'] ?>"  class="btn btn-xs btn-info myCollectFeeBtn " title="<?php echo $this->lang->line('details'); ?>"> <?php echo $this->lang->line('details'); ?> </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>



<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Class</h4>
            </div>
            <div class="modal-body">
                <select id="selected-books" class="form-control">
                    <option>Select Books</option>
                    <?php foreach ($classes as $class){ ?>
                        <option><?php echo $class['Tag']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="selectBooksByClass()">Select Books</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">

    var stockTableArray;
    var checkBoxVals = new Array();


    updateBookValue = function(){

        if(this.checked){
            $('#finalItems').append('<input class="'+this.value+'" type="hidden"  name="checkedBooks[]" value="'+this.value+'" />');
            checkBoxVals[this.id] = true;

        }else{
            $( "."+this.value ).remove();
            checkBoxVals[this.id] = false;
        }
    }




    reEnterVals = function(){
        $("#stock-body").empty();

        for(var i=0; i < stockTableArray.length; i++){
            var tr = document.createElement("tr");


            var tdBookTitle = document.createElement("td");
            tdBookTitle.innerText = stockTableArray[i].title;
            tr.appendChild(tdBookTitle);

            var tdBookClass = document.createElement("td");
            tdBookClass.innerText = stockTableArray[i].class;
            tdBookClass.classList.add("td-class");
            tr.appendChild(tdBookClass);


            var tdBookSuplier = document.createElement("td");
            tdBookSuplier.innerText = stockTableArray[i].brand;
            tr.appendChild(tdBookSuplier);

            var tdBookAuthor = document.createElement("td");
            tdBookAuthor.innerText = stockTableArray[i].author;
            tr.appendChild(tdBookAuthor);

            var tdBookStock = document.createElement("td");
            tdBookStock.innerText = stockTableArray[i].quantity;
            tr.appendChild(tdBookStock);


            var tdBookPrice = document.createElement("td");
            tdBookPrice.innerText = stockTableArray[i].price;
            tr.appendChild(tdBookPrice);


            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.classList.add("check-box");
            checkbox.id = i;
            checkbox.value = stockTableArray[i].id;
            checkbox.onclick = updateBookValue;

            if(checkBoxVals[i]){
                checkbox.checked = true;
            }




            var tdcbk = document.createElement("td");
            tdcbk.classList.add("sort-order");
            tdcbk.classList.add("text-right");
            tdcbk.appendChild(checkbox);
            tr.appendChild(tdcbk);



            if(parseInt(stockTableArray[i].quantity) <= 0){
                tr.classList.add("bg-danger");
            }


            $("#stock-body").append(tr);
        }

    }



    $('#stock-table').on('draw.dt', function() {
        var selectedBookName = $("#selected-books").val();

        for (var i = 0; i < (stockTableArray.length); i++) {

            if(stockTableArray[i].class.match(selectedBookName.trim())){
                if(document.getElementById(i) != null &&  (parseInt(stockTableArray[i].quantity) > 0)){
                    document.getElementById(i).checked = true;
                }

            }
        }
    });





    updateRecord = function(check, vals, num){
        if(check){
            $('#finalItems').append('<input class="'+vals+'" type="hidden"  name="checkedBooks[]" value="'+vals+'" />');
            checkBoxVals[num] = true;

        }else{
            $( "."+vals ).remove();
            checkBoxVals[num] = false;
        }
    }





    selectBooksByClass = function(){
        var selectedBookName = $("#selected-books").val();

        var i;

        var isChanged = false;


        for (i = 0; i < (stockTableArray.length); i++) {
            var arr = stockTableArray[i].class;
            var newArr = arr.split(",");

                for (j = 0; j < (newArr.length); j++){

                  if(newArr[j].trim() == selectedBookName.trim()){
                        updateRecord(true, stockTableArray[i].id, i);
                        isChanged = true;
                    }
                }
            // if(stockTableArray[i].class.match(selectedBookName.trim())){
            //         updateRecord(true, stockTableArray[i].id, i);
            //     isChanged = true;
            // }
        }

        if(isChanged){
            reEnterVals();
            isChanged = false;
        }


        $('#myModal').modal('hide');

    }





    insertValuesInStock = function(stockData){

        stockTableArray = stockData;
        for(var i=0; i < stockTableArray.length; i++){
            checkBoxVals.push(false);
            var tr = document.createElement("tr");


            var tdBookTitle = document.createElement("td");
            tdBookTitle.innerText = stockTableArray[i].title;
            tr.appendChild(tdBookTitle);

            var tdBookClass = document.createElement("td");
            tdBookClass.innerText = stockTableArray[i].class;
            tdBookClass.classList.add("td-class");
            tr.appendChild(tdBookClass);


            var tdBookSuplier = document.createElement("td");
            tdBookSuplier.innerText = stockTableArray[i].brand;
            tr.appendChild(tdBookSuplier);

            var tdBookAuthor = document.createElement("td");
            tdBookAuthor.innerText = stockTableArray[i].author;
            tr.appendChild(tdBookAuthor);

            var tdBookStock = document.createElement("td");
            tdBookStock.innerText = stockTableArray[i].quantity;
            tr.appendChild(tdBookStock);


            var tdBookPrice = document.createElement("td");
            tdBookPrice.innerText = stockTableArray[i].price;
            tr.appendChild(tdBookPrice);


            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.classList.add("check-box");
            checkbox.id = i;
            checkbox.value = stockTableArray[i].id;
            checkbox.onclick = updateBookValue;

            var tdcbk = document.createElement("td");
            tdcbk.classList.add("sort-order");
            tdcbk.classList.add("text-right");
            tdcbk.appendChild(checkbox);
            tr.appendChild(tdcbk);



            if(parseInt(stockTableArray[i].quantity) <= 0){
                tr.classList.add("bg-danger");
                //checkbox.disabled = true;
            }




            $("#stock-body").append(tr);
        }

    }

    insertValuesInStock(<?php echo json_encode($stock); ?>);





</script>
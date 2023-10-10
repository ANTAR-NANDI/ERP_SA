<!-- Stock List Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Crm</h1>
            <small>Burning Setting</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Crm</a></li>
                <li class="active">Burning Setting</li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
        ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>
            </div>
        <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
        ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error_message ?>
            </div>
        <?php
            $this->session->unset_userdata('error_message');
        }
        ?>



        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                 
                    <div class="panel-body">
                        <div class="col-sm-6">

                          <div class="form-group row">
                                <label for="card_name" class="col-sm-4 col-form-label"><?php echo "Eligible Points for Burn" ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" value="<?php echo $eligible_points ?>" name="eligible_points" id="eligible_points" type="text" placeholder="<?php echo "Enter Burning Eligible Points" ?>" required="" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card_name" class="col-sm-4 col-form-label"><?php echo "Points for 1 TK" ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="point" id="point" type="text" placeholder="<?php echo "Enter Points" ?>" required="" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card_name" class="col-sm-4 col-form-label"><?php echo "Maximum Burn Percentage (%)" ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="percentage" id="percentage" type="text" placeholder="<?php echo "Enter Burn Percentage" ?>" required="" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row">
                            <label for="card_name" class="col-sm-4 col-form-label"><?php echo "Card Types" ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <select class="form-control" multiple="" id="card_id" name="card_id[]" tabindex="3">
                                        <option value=""></option>
                                        {card_list}
                                        <option value="{id}">{name}</option>
                                        {/card_list}
                                    </select>
                                </div>
                            </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                            <button class="btn btn-success" onclick="Burning_Setting()">Save</button>

                            </div>
                        </div>
                    </div>
                    
                                            <div class="col-sm-6">
                                                <div class="form-group row">
                                                    <label for="category_id" class="col-sm-2 col-form-label"><?php echo display('category') ?></label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" id="category_id" multiple="" name="category_id" tabindex="3">
                                                            <option value=""></option>
                                                            {category_list}
                                                            <option value="{id}">{name_bn}-{name}</option>
                                                            {/category_list}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                        <div class="panel-title text-right">

                        </div>
                    </div>
                    <div class="panel-body">
                        
                        <div>
                            
                            <div class="table-responsive" id="printableArea">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="fetch_product">
                                    <thead>
                                        <tr>
                                        <th class="table-checkbox"><input id="selectAll" type="checkbox" class="group-checkable"></th>
                                            <th class="text-center"><?php echo display('sl') ?></th>
                                            <th class="text-center">Product Name</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>

                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="currency" value="{currency}" name="">
                       
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
<script type="text/javascript">
    
    // Crm Product Fetch 
    
    var checkboxStates = {};
    let category_id = $("#category_id").val();
    let card_id = $("#card_id").val();
     let category_ids = [];
     let card_ids = [];
  $(document).ready(function () {
    $("#category_id").on("change", function (e) { 
         category_id = $("#category_id").val();
         category_ids.push(category_id);
         console.log(category_ids);
        table.ajax.reload();
      });
      $("#card_id").on("change", function (e) { 
        card_id = $("#card_id").val();
        card_ids.push(card_id);
        table.ajax.reload();
      });
    "use strict";
     console.log($("#category_ids").val())
    var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
    var base_url = $("#base_url").val();
    var card_id = $("#card_id").val();
    var table = $("#fetch_product").DataTable({
        "drawCallback": function () {
            applyCheckboxStates(); // Reapply checkbox states after table redraw
        },
      responsive: true,
      "columnDefs": [
      { "orderable": false, "targets": [0, 1, 2] } // Replace [0, 1, 2] with the column indices where you want to disable sorting
    ],

      aaSorting: [[2, "asc"]],
  
      processing: true,
      serverSide: true,
  
      lengthMenu: [
        [10, 25, 50, 100, 250, 500, 1000],
        [10, 25, 50, 100, 250, 500, "All"],
      ],
  
   
  
      serverMethod: "post",
      ajax: {
        url: base_url + "crm/getBurningProductList",
        data: function (d) {
          d.csrf_test_name = CSRF_TOKEN;
          d.category_id = category_ids;
          d.card_id = card_ids;
        },
        
      },
      columns: [
        { data: "chk_product" },
        { data: "sl" },
        { data: "product_name" }
      ]
    });
    
  })

    $("#selectAll").click(function(){
        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));

     });
     function Burning_Setting(e)
    {
        var product_ids = [];
        $(".data-check:checked").each(function () {
            var product_id = $(this).parents('tr').find('input[name=product_id]').val();
            product_ids.push(product_id);
        });
        var card_id = $("#card_id").val();
        var point = $("#point").val();
        var percentage = $("#percentage").val();
        var eligible_points = $("#eligible_points").val();
        let csrf_test_name = $('[name="csrf_test_name"]').val();
        $(document).ready(function() {
            csrf_test_name = $('[name="csrf_test_name"]').val();
        })
        if(product_ids.length >0)
        {
            console.log(product_ids);
            
            $.ajax({
                url: '<?= base_url() ?>' + 'crm/storeBurningSetting',
                dataType: "json",
                type: 'POST',
                data:  {
                    csrf_test_name: csrf_test_name,
                    card_id: card_id,
                    point: point,
                    percentage: percentage,
                    product_id: product_ids,
                    eligible_points : eligible_points
                    },
                
                success: function (data) {
                    if(data)
                    {
                        location.reload();
                    }
                }
                       
            });
        }
            else{
                alert("At least select One !");
            }
        
    }
$(document).on('click', '.data-check', function () {
    var product_id = $(this).parents('tr').find('input[name=product_id]').val();
    checkboxStates[product_id] = $(this).prop('checked');
    });
function applyCheckboxStates() {
    $(".data-check").each(function () {
        var product_id = $(this).parents('tr').find('input[name=product_id]').val();
        $(this).prop('checked', checkboxStates[product_id] || false);
    });
}





     
</script>
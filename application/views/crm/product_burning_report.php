<!-- Stock List Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Crm</h1>
            <small>Product Burning Report</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Crm</a></li>
                <li class="active">Product Burning Report</li>
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
                    <div class="col-sm-6">
                        <div class="form-group row">
                        <label for="start_date" class="col-sm-2 col-form-label"><?php echo display('start_date') ?></label>
                        <div class="col-sm-6">
                        <input type="text" name="from_date" class="form-control datepicker" id="from_date"  value="" autocomplete="off">
                        </div>    
                    </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                       
                        <label for="end_date" class="col-sm-2 col-form-label"><?php echo display('end_date') ?></label>
                        <div class="col-sm-6">
                        <input type="text" name="to_date" class="form-control datepicker" id="to_date"  value="" autocomplete="off">
                        </div>    
                    </div>
                    </div>
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-body">
                          
                            <div>  
                                <div class="table-responsive" id="printableArea">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="product_burning_report">
                                        <thead>
                                            <tr>
                                                <th class="text-center"><?php echo display('sl') ?></th>
                                                <th class="text-center">Product Name</th> 
                                                <th class="text-center">Card Type</th>   
                                                <th class="text-center">Eligible Points</th>
                                                <th class="text-center">Points</th>  
                                                <th class="text-center">Percentage</th> 
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
    $(document).ready(function () {
    var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
    var base_url = $("#base_url").val();
    var currency = "TK ";
    var table = $("#product_burning_report").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2,3,4] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, 1000],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        title: "Product Burning Report",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Product Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Product Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Product Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        title: "Product Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4], //Your Colume value those you want
        },
        title: "<center>Product Burning Report</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "crm/getProductBurningReport",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.from_date = $("#from_date").val();
        d.to_date = $("#to_date").val();
      },
    },
    columns: [ { data: "sl" },
            { data: "product_name" },
            { data: "card_name" },
            { data: "eligible_points" },
            { data: "points" },
            { data: "percentage" }
    ],
  });
  // Add change event handler for filter inputs
  $("#from_date, #to_date").on("change", function (e) {
        // Reload the DataTable when filter inputs change
        table.ajax.reload();
    });
}); 
</script>
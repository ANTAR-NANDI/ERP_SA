<!-- Stock List Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Crm</h1>
            <small>Earning Burning Report</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Crm</a></li>
                <li class="active">Earning Burning Report</li>
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
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="category_id" class="col-sm-2 col-form-label"><?php echo display('customer') ?></label>
                            <div class="col-sm-6">
                                <select class="form-control" id="customer_id" name="customer_id" tabindex="3">
                                    <option value=""></option>
                                    {customer_list}
                                    <option value="{customer_id}">{customer_name}</option>
                                    {/customer_list}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group row">
                        <label for="start_date" class="col-sm-2 col-form-label"><?php echo display('start_date') ?></label>
                        <div class="col-sm-6">
                        <input type="text" name="from_date" class="form-control datepicker" id="from_date"  value="" autocomplete="off">
                        </div>    
                    </div>
                    </div>
                    <div class="col-sm-3">
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
                                <div class="table-responsive" style="overflow-x: auto !important;">
                                    <table class="table table-striped table-bordered" cellspacing="0" id="crm_report" width="100%" style="overflow-x: auto !important;">
                                                
                                            <thead>
                                                    <tr>
                                                        <th><?php echo display('sl') ?></th>
                                                        <th>Customer Name</th> 
                                                        <th>Customer Mobile</th>   
                                                        <th>Card Type</th>  
                                                        <th>Earning Point</th>   
                                                        <th>Burning Point</th>  
                                                        <th>Remaining Point</th>   
                                                        <th>Burned Taka</th>  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                <th colspan="4" class="text-right"><?php echo display('total') ?>:</th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
            </tr>
                                            </tfoot>
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
    var table = $("#crm_report").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2,3,4,5,6,7] }],
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
        title: "Earning Burning Report",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Earning Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4,5,6,7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Earning Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4,5,6,7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Earning Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4,5,6,7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        title: "Earning Burning Report",
        exportOptions: {
          columns: [0, 1, 2,3,4,5,6,7], //Your Colume value those you want
        },
        title: "<center>Earning Burning Report</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "crm/getCrmReport",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.customer_id = $("#customer_id").val();
        d.from_date = $("#from_date").val();
        d.to_date = $("#to_date").val();
      },
    },
    columns: [{ data: "sl" }, { data: "customer_name" }, { data: "customer_mobile" },
     { 
        data: "card_name"
     },
     { data: "earning",
        class: "earning"
     },
      { data: "burning",
        class: "burning"
      },
       {
         data: "remaining",
         class: "burning"
       },
        { data: "burned_taka",
          class: "burned_taka"
         }
    ],

     footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".earning", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
           
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
        api
        .columns(".burning", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
           
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
        api
        .columns(".remailing", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
        api
        .columns(".burned_taka", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(
            currency +
              " " +
              sum.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
          );
        });
    },
  });
  // Add change event handler for filter inputs
  $("#customer_id, #from_date, #to_date").on("change", function (e) {
        // Reload the DataTable when filter inputs change
        table.ajax.reload();
    });
}); 
</script>
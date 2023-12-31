$(function ($) {
  "use strict";
  //tooltips
  $('[data-toggle="tooltip"]').tooltip();
  //datatable
  $(".datatable").DataTable({
    responsive: true,
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    buttons: [
      { extend: "copy", className: "btn-sm prints" },
      { extend: "csv", title: "ExampleFile", className: "btn-sm prints" },
      {
        extend: "excel",
        title: "ExampleFile",
        className: "btn-sm prints",
        title: "exportTitle",
      },
      { extend: "pdf", title: "ExampleFile", className: "btn-sm prints" },
      { extend: "print", className: "btn-sm prints" },
    ],
  });

  //datatable
  $(".datatable2").DataTable({
    responsive: true,
    paging: false,
    dom: "<'row'<'col-sm-4'B><'col-sm-4'l><'col-sm-4'f>>tp",
    buttons: [
      { extend: "copy", className: "btn-sm prints" },
      { extend: "csv", title: "ExampleFile", className: "btn-sm prints" },
      {
        extend: "excel",
        title: "ExampleFile",
        className: "btn-sm prints",
        title: "exportTitle",
      },
      { extend: "pdf", title: "ExampleFile", className: "btn-sm prints" },
      { extend: "print", className: "btn-sm prints" },
    ],
  });

  //timepicker
  $(".timepicker").timepicker({
    timeFormat: "HH:mm:ss",
    stepMinute: 5,
    stepSecond: 15,
  });

  //timepicker
  $(".timepicker-hour-min-only").timepicker({
    timeFormat: "HH:mm:00",
    stepHour: 1,
    stepMinute: 5,
  });

  // semantic button
  $(".ui.selection.dropdown").dropdown();
  $(".ui.menu .ui.dropdown").dropdown({
    on: "hover",
  });

  // select 2 dropdown
  $("select.form-control:not(.dont-select-me)").select2({
    placeholder: "Select option",
    allowClear: true,
  });

  var twelveHour = $(".timepicker-12-hr").wickedpicker();
  $(".time").text("//JS Console: " + twelveHour.wickedpicker("time"));
  $(".timepicker-24-hr").wickedpicker({ twentyFour: true });
  $(".timepicker-12-hr-clearable").wickedpicker({ clearable: true });

  //preloader
  $(window).on("load", function () {
    $(".se-pre-con").fadeOut("slow");
  });

  // fixed table head
  $("#fixTable").tableHeadFixer();

  //print a div
  ("use strict");
  function printContent(el) {
    var restorepage = $("body").html();
    var printcontent = $("#" + el).clone();
    $("body").empty().html(printcontent);
    window.print();
    $("body").html(restorepage);
    location.reload();
  }

  //Copy text
  ("use strict");
  function myFunction() {
    var copyText = document.getElementById("copyed");
    copyText.select();
    document.execCommand("Copy");
  }

  ("use strict");
  function myFunction1() {
    var copyText = document.getElementById("copyed1");
    copyText.select();
    document.execCommand("Copy");
  }

  function myFunction2() {
    var copyText = document.getElementById("copyed2");
    copyText.select();
    document.execCommand("Copy");
  }

  $('input[type="checkbox"]').each(function () {
    $(this).on("change", function () {
      $(this).val() == 1 ? $(this).val(0) : $(this).val(1);
    });
  });
});

("use strict");
function printDiv(divName) {
  var printContents = document.getElementById(divName).innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  document.body.style.marginTop = "0px";
  window.print();
  document.body.innerHTML = originalContents;
}

/*Customer Part*/
$(document).ready(function () {
  var outlet_id = $("#outlet_id").val();

  ("use strict");
  /*customer list part*/
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var total_customer = $("#total_customer").val();
  var total_card = $("#total_card").val();
  var currency = $("#currency").val();
  var customer_table = $("#customerLIst").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2, 3, 4, 5, 6] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_customer],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        title: "CustomerList",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], //Your Colume value those you want
        },
        title: "<center>CustomerList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Ccustomer/CheckCustomerList",
      data: function (d) {
        (d.csrf_test_name = CSRF_TOKEN), (d.outlet_id = outlet_id);
      },
    },
    columns: [
      { data: "sl" },
      { data: "customer_id_two" },
      // { data: "friend_card" },
      { data: "customer_name_bn" },
      { data: "customer_name" },
      { data: "card_name" },
      { data: "card_number" },
      { data: "shop_name" },
      { data: "address" },
      { data: "address2" },
      { data: "mobile" },
      //    { data: 'phone'},
      { data: "email" },
      {
        data: "balance",
        class: "balance",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
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
  $("#outlet_id").on("change", function (e) {
    console.log($("#outlet_id").val());
    outlet_id = $("#outlet_id").val();
    customer_table.ajax.reload();
  });
  //console.log(customerList);
  // Card List AJax Data//////////////////////////////////////////////////////////////////////////////////////////////////////
  var card_data = $("#cardlist").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_card],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        title: "Card List",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        title: "<center>Card List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Crm/CardList",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
      },
    },
    columns: [{ data: "sl" }, { data: "name" }, { data: "button" }],
  });
  var earning_list = $("#earninglist").DataTable({
    // responsive: true,

    aaSorting: [[2, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2] }],
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
        title: "Card List",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        title: "<center>Card List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Crm/Earning_list",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN
      },
    },
    columns: [
      { data: "sl" },
      { data: "card_name" },
      { data: "points" },
      { data: "button" },
    ],

  });
  var earning_list = $("#burninglist").DataTable({
    // responsive: true,

    aaSorting: [[2, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2] }],
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
        title: "Card List",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Card List",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2], //Your Colume value those you want
        },
        title: "<center>Card List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Crm/Burning_list",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN
      },
    },
    columns: [
      { data: "sl" },
      { data: "card_name" },
      { data: "points" },
      { data: "percentage" },
      { data: "button" },
    ],

  });


  ////////////////////////////////////////////////////////////////
  /*credit customer part*/
  var total_credit_customer = $("#total_credit_customer").val();
  $("#CreditCustomerList").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6, 7] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_credit_customer],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Credit CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Credit CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Credit CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        title: "<center> Credit CustomerList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Ccustomer/CheckCreditCustomerList",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "customer_name" },
      { data: "address" },
      { data: "address2" },
      { data: "mobile" },
      { data: "phone" },
      { data: "email" },
      {
        data: "balance",
        class: "balance",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
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

  /*paid customer part*/
  var total_paid_customer = $("#total_paid_customer").val();
  $("#PaidCustomerList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 3, 4, 5, 6, 7, 8] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_paid_customer],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Paid CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "Paid CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Paid CustomerList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7], //Your Colume value those you want
        },
        title: "<center>Paid CustomerList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Ccustomer/CheckPaidCustomerList",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "customer_name" },
      { data: "address" },
      { data: "address2" },
      { data: "mobile" },
      { data: "phone" },
      { data: "email" },
      {
        data: "balance",
        class: "balance",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
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

  /*supplier part*/
  var total_supplier = $("#total_supplier").val();
  var currency = $("#currency").val();
  $("#supplierList").DataTable({
    responsive: true,

    aaSorting: [[0, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [1, 2, 3, 4, 5, 6, 7] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_supplier],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "SupplierList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "SupplierList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "SupplierList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        title: "<center> SupplierList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Csupplier/CheckSupplierList",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "supplier_name" },
      { data: "address" },
      { data: "mobile" },
      { data: "phone" },
      { data: "emailnumber" },
      { data: "city" },
      { data: "country" },
      {
        data: "balance",
        class: "balance",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".balance", {
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

  /*product part*/
  var total_product = $("#total_product").val();
  var total_vat_item = $("#total_product").val();
  var api_url = $("#api_url").val();

  $("#productList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 3, 6, 7] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_product],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "ProductList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductList",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "<center>ProductList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Cproduct/CheckProductList",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name_bn" },
      { data: "product_name" },
      { data: "created_date" },
      { data: "product_status" },
      { data: "sku" },
      // { data: "product_category" },

      // { data: "supplier_name" },
      { data: "price" },
      // { data: "purchase_p" },
      { data: "image" },
      { data: "button" },
    ],
  });

  $("#vat_item").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 3, 6, 7] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_vat_item],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "ProductList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductList",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "<center>ProductList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Cvat/CheckVatProductList",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name_bn" },
      { data: "product_name" },
      { data: "created_date" },
      { data: "product_status" },
      { data: "sku" },

      { data: "vat" },
      { data: "tax" },
      // { data: "purchase_p" },

      { data: "button" },
    ],
  });
  $("#finished_productList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2, 3, 4, 5] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_product],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "ProductList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductList",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "<center>ProductList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Cproduct/CheckFinishedProductList",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "image" },
      { data: "product_name" },

      { data: "sku" },

      { data: "price" },

      { data: "button" },
    ],
  });
  $("#all_customer").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2, 3, 4] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [25, 50, 100, 250, 500, total_product],
      [25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        title: "Customer List",
        exportOptions: {
          columns: [0, 1, 2, 3, 4], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Customer List",
        exportOptions: {
          columns: [0, 1, 2, 3, 4], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4], //Your Colume value those you want print
        },
        title: "Customer List",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4], //Your Colume value those you want print
        },
        title: "Customer List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4], //Your Colume value those you want print
        },
        title: "<center>Customer List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Ccustomer/Check_all_customer",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "customer_name" },
      { data: "email" },

      { data: "phone" },

      {
        data: "balance",
        class: "balance",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
    ],
  });

  var order_table = $("#order_table").DataTable({
    responsive: true,
    // bPaginate: false,

    aaSorting: [[1, "asc"]],
    columnDefs: [
      { bSortable: false, aTargets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] },
    ],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [30, 50, 100, 250, 500, total_product],
      [30, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        title: "Order List",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "Order List",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "Order List",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "Order List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "<center>Order List</center>",
        className: "btn-sm prints",
      },
    ],
    // paging: false,
    serverMethod: "post",
    ajax: {
      url: base_url + "Corder/CheckOrderList",

      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.status = $("#delivery_status").val();
        // data.last_order =this.row( ':last-child' ).data()[0];
        data.csrf_test_name = CSRF_TOKEN;
      },
    },

    columns: [
      { data: "check" },
      { data: "sl" },
      { data: "order_code" },
      { data: "date" },
      { data: "num_of_product" },
      { data: "customer_name" },
      { data: "customer_number" },
      { data: "amount" },
      { data: "delivery_status" },
      { data: "payment_method" },
      { data: "payment_status" },
      { data: "refund" },
      { data: "button" },
    ],
  });

  $("#btn-filter").click(function () {
    order_table.ajax.reload();
  });
});
$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var currency = $("#currency").val();
  var total_stock = $("#total_stock").val();
  var cat_id = $("#cat_list").val();
  var outlet_id = $("#outlet").val();
  var value = $("#value").val();

  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();

  $("#cat_list,#from_date,#to_date,#value").on("change", function (e) {
    var valueSelected = this.value;
    cat_id = $("#cat_list").val();
    from_date = $("#from_date").val();
    to_date = $("#to_date").val();
    value = $("#value").val();
    table.ajax.reload();
  });

  $("#outlet").on("change", function (e) {
    var outVal = this.value;
    outlet_id = outVal;

    table.ajax.reload();
  });

  $("#product_sku").on("change", function (e) {
    table.ajax.reload();
  });

  var table = $("#checkListStockOutlet").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 4, 5, 6, 7, 8, 9, 10] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_stock],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Crqsn/outlet_stock",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.cat_id = cat_id;
        d.product_sku = $(".product_sku").val();
        d.outlet_id = outlet_id;
        d.from_date = from_date;
        d.to_date = to_date;
        d.value = value;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "category", class: "text-center" },
      { data: "sku", class: "text-center" },
      // { data: "product_model", class: "text-center" },

      {
        data: "sales_price",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      // {
      //   data: "purchase_p",
      //   class: "text-right",
      //   render: $.fn.dataTable.render.number(",", ".", 2, currency),
      // },
      // {
      //   data: "production_cost",
      //   class: "text-right",
      //   render: $.fn.dataTable.render.number(",", ".", 2, currency),
      // },
      { data: "totalPurchaseQnty", class: "text-right" },
      { data: "damagedQnty", class: "text-right" },
      { data: "totalSalesQnty", class: "text-right" },
      { data: "return_given", class: " stock text-right" },
      //   { data: 'warrenty_stock' ,class:"stock text-right" },
      { data: "opening_stock", class: "stock text-right" },
      { data: "closing_stock", class: "stock text-right" },

      {
        data: "total_sale_price",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      // {
      //   data: "purchase_total",
      //   class: "total_purchase text-right",
      //   render: $.fn.dataTable.render.number(",", ".", 2, currency),
      // },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".stock", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".total_sale", {
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

      // api
      //   .columns(".total_purchase", {
      //     page: "current",
      //   })
      //   .every(function () {
      //     var sum = this.data().reduce(function (a, b) {
      //       var x = parseFloat(a) || 0;
      //       var y = parseFloat(b) || 0;
      //       return x + y;
      //     }, 0);
      //     $(this.footer()).html(
      //       currency +
      //       " " +
      //       sum.toLocaleString(undefined, {
      //         minimumFractionDigits: 2,
      //         maximumFractionDigits: 2,
      //       })
      //     );
      //   });
    },
  });
});

$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var currency = $("#currency").val();
  var total_stock = $("#total_stock").val();
  var cat_id = $("#cat_list").val();
  var outlet_id = $("#outlet").val();

  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();

  var purchase_id = $("#purchase_id").val();
  var is_exp = $("#is_exp").val();

  $("#cat_list,#from_date,#to_date,#purchase_id,#is_exp").on(
    "change",
    function (e) {
      var valueSelected = this.value;
      cat_id = $("#cat_list").val();
      from_date = $("#from_date").val();
      to_date = $("#to_date").val();
      purchase_id = $("#purchase_id").val();
      is_exp = $("#is_exp").val();
      table.ajax.reload();
    }
  );

  $("#outlet").on("change", function (e) {
    var outVal = this.value;
    outlet_id = outVal;

    table.ajax.reload();
  });

  $("#product_sku").on("change", function (e) {
    table.ajax.reload();
  });

  var table = $("#checkExpiryStockOutlet").DataTable({
    responsive: true,

    aaSorting: [[3, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 4, 5, 6, 7, 8, 9] }],
    processing: true,
    serverSide: true,
    //paging: false,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_stock],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    // lengthMenu: [
    //   [10, 25, 50, -1],
    //   [10, 25, 50, 'All'],
    // ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Crqsn/expiry_outlet_stock",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.cat_id = cat_id;
        d.product_sku = $(".product_sku").val();
        d.outlet_id = outlet_id;
        d.from_date = from_date;
        d.to_date = to_date;
        d.purchase_id = purchase_id;
        d.is_exp = is_exp;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "category" },
      { data: "sku" },
      { data: "purchase_id", class: "text-center" },
      { data: "expiry_date", class: "text-center" },
      { data: "totalPurchaseQnty", class: "stock text-right" },
      // { data: "damagedQnty", class: "stock text-right" },
      { data: "totalSalesQnty", class: " stock text-right" },
      // { data: "opening_stock", class: "stock text-right" },
      { data: "stok_quantity", class: "stock text-right" },
      { data: "expiry_status", class: " text-right" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".stock", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".total_sale", {
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

      api
        .columns(".total_purchase", {
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
});

$(document).ready(function () {
  "use strict";
  var csrf_test_name = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var total_invoice = $("#total_invoice").val();
  var currency = $("#currency").val();

  var invoicedatatable = $("#InvList").DataTable({
    // responsive: true,

    aaSorting: [[1, "desc"]],
    columnDefs: [
      { bSortable: false, aTargets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] },
    ],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_invoice],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "InvoiceList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], //Your Colume value those you want print
        },
        title: "InvoiceList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], //Your Colume value those you want print
        },
        title: "Invoice List",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], //Your Colume value those you want print
        },
        title: "<center> Invoice List</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Cinvoice/CheckInvoiceList",
      data: function (data) {
        data.fromdate = $("#from_date").val();
        data.todate = $("#to_date").val();
        data.csrf_test_name = csrf_test_name;
      },
    },
    columns: [
      { data: "sl" },
      // { data: "invoice_id" },
      { data: "invoice" },
      { data: "customer_name" },
      { data: "final_date" },
      { data: "outlet_name" },
      { data: "salesman" },

      // { data: "delivery_type" },
      // { data: "sale_type" },
      // { data: "courier_status" },
      {
        data: "sales_status",
        class: "text-center",
      },
      {
        data: "payment_status",
        class: "text-center",
      },
      {
        data: "paid_amount",
        class: "total_paid text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },

      {
        data: "due_amount",
        class: "total_due text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },

      {
        data: "total_amount",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "button" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".total_paid", {
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

      api
        .columns(".total_due", {
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
      api
        .columns(".total_sale", {
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

  $("#btn-filter").click(function () {
    invoicedatatable.ajax.reload();
  });
});

/*CALCULATOR PART*/
var number = "",
  total = 0,
  regexp = /[0-9]/,
  mainScreen = document.getElementById("mainScreen");
("use strict");
function InputSymbol(num) {
  var cur = document.getElementById(num).value;
  var prev = number.slice(-1);
  // Do not allow 2 math operators in row
  if (!regexp.test(prev) && !regexp.test(cur)) {
    console.log("Two math operators not allowed after each other ;)");
    return;
  }
  number = number.concat(cur);
  mainScreen.innerHTML = number;
}

("use strict");
function CalculateTotal() {
  // Time for some EVAL magic
  total = Math.round(eval(number) * 100) / 100;
  mainScreen.innerHTML = total;
}

("use strict");
function DeleteLastSymbol() {
  if (number) {
    number = number.slice(0, -1);
    mainScreen.innerHTML = number;
  }
  if (number.length === 0) {
    mainScreen.innerHTML = "0";
  }
}

("use strict");
function ClearScreen() {
  number = "";
  mainScreen.innerHTML = 0;
}

//security page js end
/*stock list js*/
$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var currency = $("#currency").val();
  var total_stock = $("#total_stock").val();
  var product_status = $("#product_status").val();
  var product_sku = $("#product_sku").val();
  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();
  var pr_status = $("#pr_status").val();
  var outlet_id = $("#outlet_id").val();
  var cat_id = $("#cat_list").val();
  var value = $("#value").val();

  $("#product_status,#from_date, #to_date,#outlet_id,#cat_list,#value").on(
    "change",
    function (e) {
      //var valueSelected = this.value;
      product_status = $("#product_status").val();
      cat_id = $("#cat_list").val();
      from_date = $("#from_date").val();
      to_date = $("#to_date").val();
      outlet_id = $("#outlet_id").val();
      value = $("#value").val();
      table.ajax.reload();
    }
  );

  $("#product_sku").on("change", function (e) {
    table.ajax.reload();
  });

  var table = $("#checkListStockList").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [
      { bSortable: false, aTargets: [0, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12] },
    ],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_stock],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
        footer: true,
        orientation: "landscape",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
        footer: true,
        customize: function (win) {
          var last = null;
          var current = null;
          var bod = [];

          var css = "@page { size: landscape; }",
            head =
              win.document.head || win.document.getElementsByTagName("head")[0],
            style = win.document.createElement("style");

          style.type = "text/css";
          style.media = "print";

          if (style.styleSheet) {
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(win.document.createTextNode(css));
          }

          head.appendChild(style);
        },
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Creport/CheckList",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.outlet_id = outlet_id;
        d.pr_status = pr_status;
        d.cat_id = cat_id;
        d.product_status = product_status;
        d.product_sku = $(".product_sku").val();
        d.from_date = from_date;
        d.to_date = to_date;
        d.value = value;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "category" },
      { data: "sku" },
      // { data: "product_model", class: "text-center" },

      {
        data: "sales_price",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "purchase_p",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      // {
      //   data: "production_cost",
      //   class: "text-right",
      //   render: $.fn.dataTable.render.number(",", ".", 2, currency),
      // },
      { data: "totalPurchaseQnty", class: "text-right" },
      { data: "damagedQnty", class: "text-right" },
      { data: "returnQnty", class: "text-right" },
      { data: "total_sale", class: "text-right" },
      { data: "total_transfer", class: "text-right" },
      { data: "opening_stock", class: "stock text-right" },
      { data: "stok_quantity", class: "stock text-right" },
      {
        data: "total_sale_price",
        class: "total_sale text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "purchase_total",
        class: "total_purchase text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "sold_value",
        class: "sold_value text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".stock", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".total_sale", {
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

      api
        .columns(".total_purchase", {
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

      api
        .columns(".sold_value", {
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
});
$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var currency = $("#currency").val();
  var total_stock = $("#total_stock").val();
  var product_status = $("#product_status").val();
  var product_sku = $("#product_sku").val();
  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();
  var pr_status = $("#pr_status").val();
  var outlet_id = $("#outlet_id").val();
  var purchase_id = $("#purchase_id").val();
  var is_exp = $("#is_exp").val();

  $("#product_status,#from_date, #to_date,#outlet_id,#purchase_id,#is_exp").on(
    "change",
    function (e) {
      //var valueSelected = this.value;
      product_status = $("#product_status").val();
      from_date = $("#from_date").val();
      to_date = $("#to_date").val();
      outlet_id = $("#outlet_id").val();
      purchase_id = $("#purchase_id").val();
      is_exp = $("#is_exp").val();
      table.ajax.reload();
    }
  );

  $("#product_sku").on("change", function (e) {
    table.ajax.reload();
  });

  var table = $("#checkExpiryStockList").DataTable({
    responsive: true,
    aaSorting: [[3, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 4, 5, 6, 7, 8, 9] }],
    processing: true,
    serverSide: true,
    //paging: false,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_stock],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
        footer: true,
        orientation: "landscape",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
        footer: true,
        customize: function (win) {
          var last = null;
          var current = null;
          var bod = [];

          var css = "@page { size: landscape; }",
            head =
              win.document.head || win.document.getElementsByTagName("head")[0],
            style = win.document.createElement("style");

          style.type = "text/css";
          style.media = "print";

          if (style.styleSheet) {
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(win.document.createTextNode(css));
          }

          head.appendChild(style);
        },
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Creport/expiryCheckList",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.pr_status = pr_status;
        d.product_status = product_status;
        d.product_sku = $(".product_sku").val();
        d.outlet_id = outlet_id;
        d.purchase_id = purchase_id;
        d.is_exp = is_exp;

        d.from_date = from_date;
        d.to_date = to_date;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "category" },
      { data: "sku" },
      { data: "purchase_id", class: "text-center" },
      { data: "expiry_date", class: "text-center" },
      {
        data: "purchase_p",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "totalPurchaseQnty", class: "stock text-right" },
      // { data: "damagedQnty", class: "stock text-right" },
      { data: "totalSalesQnty", class: " stock text-right" },
      // { data: "opening_stock", class: "stock text-right" },
      { data: "stok_quantity", class: "stock text-right" },
      {
        data: "purchase_total",
        class: "total_purchase text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "expiry_status", class: " text-right" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      api
        .columns(".stock", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".total_sale", {
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

      api
        .columns(".total_purchase", {
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
});

$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var currency = $("#currency").val();
  var total_stock = $("#total_stock").val();
  var product_status = $("#product_status").val();
  var supplier_id = $("#supplier_id").val();
  var product_sku = $("#product_sku").val();
  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();
  var pr_status = $("#pr_status").val();
  var outlet_id = $("#outlet_id").val();
  var purchase_id = $("#purchase_id").val();
  var is_exp = $("#is_exp").val();

  $("#product_status,#from_date, #to_date,#outlet_id,#purchase_id,#is_exp").on(
    "change",
    function (e) {
      //var valueSelected = this.value;
      product_status = $("#product_status").val();
      from_date = $("#from_date").val();
      to_date = $("#to_date").val();
      outlet_id = $("#outlet_id").val();
      purchase_id = $("#purchase_id").val();
      is_exp = $("#is_exp").val();
      table.ajax.reload();
    }
  );

  $("#supplier_id").on("change", function (e) {
    table.ajax.reload();
  });

  $("#product_sku").on("change", function (e) {
    table.ajax.reload();
  });

  var table = $("#checkSupplierStockList").DataTable({
    responsive: true,
    aaSorting: [[3, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 2, 4, 5, 6, 7, 8, 9] }],
    processing: true,
    serverSide: true,
    //paging: false,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_stock],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
        footer: true,
        orientation: "landscape",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
        footer: true,
        customize: function (win) {
          var last = null;
          var current = null;
          var bod = [];

          var css = "@page { size: landscape; }",
            head =
              win.document.head || win.document.getElementsByTagName("head")[0],
            style = win.document.createElement("style");

          style.type = "text/css";
          style.media = "print";

          if (style.styleSheet) {
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(win.document.createTextNode(css));
          }

          head.appendChild(style);
        },
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Creport/supplierStockCheckList",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.pr_status = pr_status;
        d.product_status = product_status;
        d.supplier_id = $(".supplier_id").val();
        d.product_sku = $(".product_sku").val();
        d.outlet_id = outlet_id;
        d.purchase_id = purchase_id;
        d.is_exp = is_exp;

        d.from_date = from_date;
        d.to_date = to_date;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "category" },
      { data: "sku" },
      { data: "purchase_id", class: "text-center" },
      { data: "supplier_name", class: "text-center" },
      { data: "expiry_date", class: "text-center" },
      {
        data: "sales_price",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "purchase_p",
        class: "text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "totalPurchaseQnty", class: "pur_qnty text-right" },
      // { data: "damagedQnty", class: "stock text-right" },
      { data: "sold_qnty", class: "total_sale text-right" },
      { data: "total_transfer", class: "total_transfer text-right" },
      // { data: "opening_stock", class: "stock text-right" },
      { data: "stok_quantity", class: "stock text-right" },
      {
        data: "purchase_total",
        class: "total_purchase text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "sold_value",
        class: "sold_value text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      { data: "expiry_status", class: " text-right" },
    ],

    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      api
        .columns(".pur_qnty", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".total_sale", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".total_transfer", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".stock", {
          page: "current",
        })
        .every(function () {
          var sum = this.data().reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b) || 0;
            return x + y;
          }, 0);
          $(this.footer()).html(sum.toLocaleString());
        });

      api
        .columns(".total_purchase", {
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
      api
        .columns(".sold_value", {
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
});

/*Cash Calculator*/
("use strict");
function cashCalculator() {
  var mul0 = $(".text_0").val();
  var text_0_bal = mul0 * 200;
  $(".text_0_bal").val(text_0_bal);

  var mul1 = $(".text_1").val();
  var text_1_bal = mul1 * 1000;
  $(".text_1_bal").val(text_1_bal);

  var mul2 = $(".text_2").val();
  var text_2_bal = mul2 * 500;
  $(".text_2_bal").val(text_2_bal);

  var mul3 = $(".text_3").val();
  var text_3_bal = mul3 * 100;
  $(".text_3_bal").val(text_3_bal);

  var mul4 = $(".text_4").val();
  var text_4_bal = mul4 * 50;
  $(".text_4_bal").val(text_4_bal);

  var mul5 = $(".text_5").val();
  var text_5_bal = mul5 * 20;
  $(".text_5_bal").val(text_5_bal);

  var mul6 = $(".text_6").val();
  var text_6_bal = mul6 * 10;
  $(".text_6_bal").val(text_6_bal);

  var mul7 = $(".text_7").val();
  var text_7_bal = mul7 * 5;
  $(".text_7_bal").val(text_7_bal);

  var mul8 = $(".text_8").val();
  var text_8_bal = mul8 * 2;
  $(".text_8_bal").val(text_8_bal);

  var mul9 = $(".text_9").val();
  var text_9_bal = mul9 * 1;
  $(".text_9_bal").val(text_9_bal);

  var total_money =
    text_0_bal +
    text_1_bal +
    text_2_bal +
    text_3_bal +
    text_4_bal +
    text_5_bal +
    text_6_bal +
    text_7_bal +
    text_8_bal +
    text_9_bal;

  $(".total_money").val(total_money);
}

function checkTime(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}
$(document).ready(function () {
  "use strict";
  function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById("time").innerHTML = h + ":" + m + ":" + s;
    t = setTimeout(function () {
      startTime();
    }, 500);
  }
});
/*Account part start*/

$(document).ready(function () {
  $("#jstree1").jstree({
    core: {
      check_callback: true,
    },
    plugins: ["types", "dnd"],
    types: {
      default: {
        icon: "fa fa-folder",
      },
      html: {
        icon: "fa fa-file-code-o",
      },
      svg: {
        icon: "fa fa-file-picture-o",
      },
      css: {
        icon: "fa fa-file-code-o",
      },
      img: {
        icon: "fa fa-file-image-o",
      },
      js: {
        icon: "fa fa-file-text-o",
      },
      attr: {
        class: "panel-heading",
      },
    },
  });
});

("use strict");
function loadCoaData(id) {
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "accounts/selectedform/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#newform").html(data);
      $("#btnSave").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error get data from ajax");
    },
  });
}

("use strict");
function newHeaddata(id) {
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "accounts/newform/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      console.log(data.rowdata);
      var headlabel = data.headlabel;
      $("#txtHeadCode").val(data.headcode);
      document.getElementById("txtHeadName").value = "";
      $("#txtPHead").val(data.rowdata.HeadName);
      $("#txtHeadLevel").val(headlabel);
      $("#btnSave").prop("disabled", false);
      $("#btnSave").show();
      $("#btnUpdate").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error get data from ajax");
    },
  });
}

/*TAX SETTING*/
("use strict");
function add_columnTaxsettings(sl) {
  var text = "";
  var i;
  for (i = 0; i < sl; i++) {
    var f = i + 1;
    text +=
      '<div class="form-group row"><label for="fieldname" class="col-sm-1 col-form-label">Tax Name' +
      f +
      '*</label><div class="col-sm-2"><input type="text" placeholder="Tax Name" class="form-control" required autocomplete="off" name="taxfield[]"></div><label for="default_value" class="col-sm-1 col-form-label">Default Value<i class="text-danger">(%)</i></label><div class="col-sm-2"><input type="text" class="form-control" name="default_value[]" id="default_value"  placeholder="Default Value" /></div><label for="reg_no" class="col-sm-1 col-form-label">Reg No</label><div class="col-sm-2"><input type="text" class="form-control" name="reg_no[]" id="reg_no"  placeholder="Reg No" /></div><div class="col-sm-1"><input type="checkbox" name="is_show" class="form-control" value="1"></div><label for="isshow" class="col-sm-1 col-form-label">Is Show</label></div>';
  }
  document.getElementById("taxfield").innerHTML = text;
}

("use strict");
function deleteTaxRow(row) {
  var i = row.parentNode.parentNode.rowIndex;
  document.getElementById("POITable").deleteRow(i);
}

("use strict");
function TaxinsRow() {
  console.log("hi");
  var x = document.getElementById("POITable");
  var new_row = x.rows[1].cloneNode(true);
  var len = x.rows.length;
  new_row.cells[0].innerHTML = len;

  var inp1 = new_row.cells[1].getElementsByTagName("input")[0];
  inp1.id += len;
  inp1.value = "";
  var inp2 = new_row.cells[2].getElementsByTagName("input")[0];
  inp2.id += len;
  inp2.value = "";
  x.appendChild(new_row);
}

$(document).ready(function () {
  var taxn = $("#taxnumber").val();
  for (var i = 0; i < taxn; i++) {
    var sum = 0;
    $(".rpttax" + i).each(function () {
      sum += parseFloat($(this).text());
    });

    $("#rpttax" + i).html(
      sum.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })
    );
  }
});

("use strict");
function bank_paymetExpense(val) {
  if (val == 2) {
    var style = "block";
  } else {
    var style = "none";
  }

  document.getElementById("bank_div").style.display = style;
}

$("body").on("change", "#nameofficeloanperson", function (event) {
  event.preventDefault();
  var person_id = $("#nameofficeloanperson").val();
  var csrf_test_name = $("[name=csrf_test_name]").val();
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "Csettings/phone_search_by_name",
    type: "post",
    data: { person_id: person_id, csrf_test_name: csrf_test_name },
    success: function (msg) {
      $(".phone").val(msg);
    },
    error: function (xhr, desc, err) {
      alert("failed");
    },
  });
});

$("body").on("change", "#namepersonloan", function (event) {
  event.preventDefault();
  var person_id = $("#namepersonloan").val();
  var base_url = $("#base_url").val();
  var csrf_test_name = $("[name=csrf_test_name]").val();
  $.ajax({
    url: base_url + "Csettings/loan_phone_search_by_name",
    type: "post",
    data: { person_id: person_id, csrf_test_name: csrf_test_name },
    success: function (msg) {
      $(".phone").val(msg);
    },
    error: function (xhr, desc, err) {
      alert("failed");
    },
  });
});

$(document).ready(function () {
  "use strict";
  $("#customer_nameCommission").change(function (e) {
    var customer_id = $(this).val();
    var csrf_test_name = $("[name=csrf_test_name]").val();
    var base_url = $("#base_url").val();
    $.ajax({
      type: "post",
      async: false,
      url: base_url + "Csettings/retrive_product_info",
      data: { customer_id: customer_id, csrf_test_name: csrf_test_name },
      success: function (data) {
        if (data) {
          $("#product_model").html(data);
        } else {
          $("#product_model").html("Product not found!");
        }
      },
      error: function () {
        alert("Request Failed, Please check your code and try again!");
      },
    });
  });
});

("use strict");
function checkallcreate(sl) {
  $("#checkAllcreate" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".create" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".create" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}
("use strict");
function checkallread(sl) {
  $("#checkAllread" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".read" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".read" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}

("use strict");
function checkalledit(sl) {
  $("#checkAlledit" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".edit" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".edit" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}

("use strict");
function checkalldelete(sl) {
  $("#checkAlldelete" + sl).change(function () {
    var checked = $(this).is(":checked");
    if (checked) {
      $(".delete" + sl).each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(".delete" + sl).each(function () {
        $(this).prop("checked", false);
      });
    }
  });
}

("use strict");
function userRole(id) {
  var base_url = $("#base_url").val();
  $.ajax({
    url: base_url + "permission/select_to_rol/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#existrole").html(data);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#existrole").html("<p style='color:red'>No Role Assigned Yet</p>");
    },
  });
}

//            ========= its for toastr error message =============
$(document).ready(function () {
  $("#submit").click(function () {
    var form = $("#passrecoveryform");
    var base_url = $("#base_url").val();
    var form_url = base_url + "Admin_dashboard/password_recovery/";

    $.ajax({
      url: form_url,
      method: "POST",
      dataType: "json",
      data: form.serialize(),
      success: function (r) {
        if (r.status == 1) {
          toastr.success(r.success);
        }
        if (r.status == 0) {
          toastr.error(r.exception);
        }
      },
      error: function (xhr) {
        alert("failed!");
      },
    });
  });
});
/*dashboarjs*/

$(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });

// select 2 dropdown
$("select.form-control:not(.dont-select-me)").select2({
  placeholder: "Select option",
  allowClear: true,
});

$(window).on("load", function () {
  setTimeout(function () {
    $(".page-loader-wrapper").fadeOut();
  }, 50);
});

//Insert supplier
$("#insert_supplier").validate();
$("#validate").validate();

//Update supplier
$("#supplier_update").validate();

//Update customer
$("#customer_update").validate();

//Insert customer
$("#insert_customer").validate();

//Update product
$("#product_update").validate();

//Insert product
$("#insert_product").validate();

// $("#insert_sale").validate();

//Insert pos invoice
$("#insert_pos_invoice").validate();

//Insert invoice
$("#insert_invoice").validate();

//Update invoice
$("#invoice_update").validate();

//Insert purchase
$("#insert_purchase").validate();

//Update purchase
$("#purchase_update").validate();

//Add category
$("#insert_category").validate();

//Update category
$("#category_update").validate();

//Stock report
$("#stock_report").validate();

//Stock report
$("#stock_report_supplier_wise").validate();
//Stock report
$("#stock_report_product_wise").validate();

//Create account
$("#create_account_data").validate();

//Update account
$("#update_account_data").validate();

$(function () {
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var total_production = $("#total_production").val();
  $("#productionList").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2, 3, 4, 5, 6, 7] }],
    processing: true,
    serverSide: true,

    lengthMenu: [
      [10, 25, 50, 100, 250, 500, total_production],
      [10, 25, 50, 100, 250, 500, "All"],
    ],

    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want
        },
        className: "btn-sm prints",
      },
      {
        extend: "csv",
        title: "ProductionList",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        className: "btn-sm prints",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductionList",
        className: "btn-sm prints",
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "ProductionList",
        className: "btn-sm prints",
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5], //Your Colume value those you want print
        },
        title: "<center>ProductionList</center>",
        className: "btn-sm prints",
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Cproduction/CheckProductionList",
      data: {
        csrf_test_name: CSRF_TOKEN,
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "quantity" },
      { data: "rate" },
      { data: "unit" },
      { data: "total" },
      { data: "grand_total" },
      { data: "button" },
    ],
  });
});

("use strict");

function popOverInit(button, tooltip) {
  // always set tooltip id to tooltip
  const popperInstance = Popper.createPopper(button, tooltip, {
    placement: "left",
    modifiers: [
      {
        name: "offset",
        options: {
          offset: [0, 8],
        },
      },
    ],
  });

  function show() {
    tooltip.setAttribute("data-show", "");

    popperInstance.update();
  }

  function hide() {
    tooltip.removeAttribute("data-show");
  }

  const showEvents = ["mouseenter", "focus"];
  const hideEvents = ["mouseleave", "blur"];

  showEvents.forEach((event) => {
    button.addEventListener(event, show);
  });

  hideEvents.forEach((event) => {
    button.addEventListener(event, hide);
  });
}

function get_text() {
  var text = $("#outlet option:selected").text();
  $("#outlet_text").val(text);
}
//Product report salewise
$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  // var product_id = $("#product_id").val();
  var currency = $("#currency").val();
  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();
  var outlet_id = $("#outlet_id").val();

  $("#product_id,#from_date, #to_date,#outlet_id").on("change", function (e) {
    from_date = $("#from_date").val();
    to_date = $("#to_date").val();
    // product_id =  $("#product_id").val();
    outlet_id = $("#outlet_id").val();
    table.ajax.reload();
  });

  var table = $("#ProductSalesReport").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2, 3, 4, 5, 6] }],
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
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
        footer: true,
        orientation: "landscape",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
        footer: true,
        customize: function (win) {
          var last = null;
          var current = null;
          var bod = [];

          var css = "@page { size: landscape; }",
            head =
              win.document.head || win.document.getElementsByTagName("head")[0],
            style = win.document.createElement("style");

          style.type = "text/css";
          style.media = "print";

          if (style.styleSheet) {
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(win.document.createTextNode(css));
          }

          head.appendChild(style);
        },
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Creport/ProductSalesReport",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.outlet_id = outlet_id;
        // d.product_id = product_id;
        d.from_date = from_date;
        d.to_date = to_date;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "qnty" },
      { data: "total_sales", class: "total_sales text-right" },
      {
        data: "discount",
        class: "discount text-right",
        // render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "vat",
        class: "vat text-right",
      },
      {
        data: "tax",
        class: "tax text-right",
      },
      { data: "net_sales", class: "net_sales text-right" },
      // { data: "dc", class: "text-right" },
      { data: "cost_price", class: "cost_price text-right" },
      { data: "gross_profit", class: "gross_profit text-right" },
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      //total gross profit
      api
        .columns(".gross_profit", {
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
      //total Cost Price
      api
        .columns(".cost_price", {
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
      //total Vat Price
      api
        .columns(".vat", {
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
      //total Vat Price
      api
        .columns(".tax", {
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
      //total Net Sales
      api
        .columns(".net_sales", {
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
      //total Cost Price
      api
        .columns(".total_sales", {
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

      //total Cost Price
      api
        .columns(".discount", {
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
});
//Supplier report salewise
$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  var currency = $("#currency").val();
  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();
  var supplier_id = $("#supplier_id").val();
  var outlet_id = $("#outlet_id").val();

  $("#supplier_id,#from_date,#to_date,#outlet_id").on("change", function (e) {
    from_date = $("#from_date").val();
    to_date = $("#to_date").val();
    supplier_id = $("#supplier_id").val();
    outlet_id = $("#outlet_id").val();
    table.ajax.reload();
  });

  var table = $("#SalesReportSupplierWise").DataTable({
    // responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2, 3, 4, 5, 6] }],
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
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
        footer: true,
        orientation: "landscape",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
        footer: true,
        customize: function (win) {
          var last = null;
          var current = null;
          var bod = [];

          var css = "@page { size: landscape; }",
            head =
              win.document.head || win.document.getElementsByTagName("head")[0],
            style = win.document.createElement("style");

          style.type = "text/css";
          style.media = "print";

          if (style.styleSheet) {
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(win.document.createTextNode(css));
          }

          head.appendChild(style);
        },
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Creport/supplier_wise_sales_report",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.supplier_id = supplier_id;
        d.from_date = from_date;
        d.to_date = to_date;
        d.outlet_id = outlet_id;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "sku" },
      { data: "qnty" },
      { data: "rate" },
      { data: "total_sales", class: "total_sales text-right" },
      { data: "discount", class: "discount text-right" },
      { data: "vat", class: "vat text-right" },
      { data: "tax", class: "tax text-right" },
      { data: "net_sales", class: "net_sales text-right" },
      { data: "cost_price", class: "cost_price text-right" },
      { data: "total_cpu", class: "total_cpu text-right" },
      { data: "gross_profit", class: "gross_profit text-right" },
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      //total cpu
      api
        .columns(".total_cpu", {
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
      //total gross profit
      api
        .columns(".gross_profit", {
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
      //Cost Price
      api
        .columns(".cost_price", {
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
      //total Vat Price
      api
        .columns(".vat", {
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
      //total Vat Price
      api
        .columns(".tax", {
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
      //total Net Sales
      api
        .columns(".net_sales", {
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
      //total Cost Price
      api
        .columns(".total_sales", {
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

      //total Cost Price
      api
        .columns(".discount", {
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
});
//Product report salewise
$(document).ready(function () {
  "use strict";
  var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
  var base_url = $("#base_url").val();
  // var product_id = $("#product_id").val();
  var currency = $("#currency").val();
  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();
  var outlet_id = $("#outlet_id").val();

  $("#product_id,#from_date, #to_date,#outlet_id").on("change", function (e) {
    from_date = $("#from_date").val();
    to_date = $("#to_date").val();
    // product_id =  $("#product_id").val();
    outlet_id = $("#outlet_id").val();
    table.ajax.reload();
  });

  var table = $("#ProductPurchaseReport").DataTable({
    responsive: true,

    aaSorting: [[1, "asc"]],
    columnDefs: [{ bSortable: false, aTargets: [0, 1, 2, 3, 4, 5] }],
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
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "csv",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "excel",
        title: "StockList",
        className: "btn-sm prints",
        footer: true,
      },
      {
        extend: "pdf",
        title: "Stock List",
        className: "btn-sm prints",
        footer: true,
        orientation: "landscape",
      },
      {
        extend: "print",
        title: "<center>Stock List</center>",
        className: "btn-sm prints",
        footer: true,
        customize: function (win) {
          var last = null;
          var current = null;
          var bod = [];

          var css = "@page { size: landscape; }",
            head =
              win.document.head || win.document.getElementsByTagName("head")[0],
            style = win.document.createElement("style");

          style.type = "text/css";
          style.media = "print";

          if (style.styleSheet) {
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(win.document.createTextNode(css));
          }

          head.appendChild(style);
        },
      },
    ],

    serverMethod: "post",
    ajax: {
      url: base_url + "Creport/ProductPurchaseReport",
      data: function (d) {
        d.csrf_test_name = CSRF_TOKEN;
        d.outlet_id = outlet_id;
        // d.product_id = product_id;
        d.from_date = from_date;
        d.to_date = to_date;
      },
    },
    columns: [
      { data: "sl" },
      { data: "product_name" },
      { data: "sku" },
      {
        data: "qnty",
        class: "total_quantity text-right",
      },
      {
        data: "total_purchased_amount",
        class: "total_purchased_amount text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
      {
        data: "weighted_price",
        class: "weighted_price text-right",
        render: $.fn.dataTable.render.number(",", ".", 2, currency),
      },
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      //total purchased amount
      api
        .columns(".total_quantity", {
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
      //total Cost Price
      api
        .columns(".total_purchased_amount", {
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
      //total Vat Price
      api
        .columns(".weighted_price", {
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
});

//Add Input Field Of Row


<?php
$cache_file = "invoice.json";
header('Content-Type: text/javascript; charset=utf8');
?>
"use strict";
function addInputField2(t, product_name, none, vat, tax) {


    var row = $("#addinvoice tbody tr").length;
    var count = row + 1;

    //  alert(count)

    var tab1 = 0;
    var tab2 = 0;
    var tab3 = 0;
    var tab4 = 0;
    var tab5 = 0;
    var tab6 = 0;
    var tab7 = 0;
    var tab8 = 0;
    var tab9 = 0;
    var tab10 = 0;
    var tab11 = 0;
    var tab12 = 0;
    var limits = 500;
    var taxnumber = $("#txfieldnum").val();
    var tbfild = '';
    for (var i = 0; i < taxnumber; i++) {
        var taxincrefield = '<input id="total_tax' + i + '_' + count + '" class="total_tax' + i + '_' + count + '" type="hidden"><input id="all_tax' + i + '_' + count + '" class="total_tax' + i + '" type="hidden" name="tax[]">';
        tbfild += taxincrefield;
    }
    if (count == limits)
        toastr.error("You have reached the limit of adding " + count + " inputs");
    else {
        var permission = $("#item_wise_flat_discount").val();
        var a = "product_name_" + count,
            tabindex = count * 6,
            e = document.createElement("tr");
        tab1 = tabindex + 1;
        tab2 = tabindex + 2;
        tab3 = tabindex + 3;
        tab4 = tabindex + 4;
        tab5 = tabindex + 5;
        tab6 = tabindex + 6;
        tab7 = tabindex + 7;
        tab8 = tabindex + 8;
        tab9 = tabindex + 9;
        tab10 = tabindex + 10;
        tab11 = tabindex + 11;
        tab12 = tabindex + 12;
        e.innerHTML = "<td><input type='text' name='product_name' onkeypress='invoice_productList(" + count + ");' class='form-control productSelection common_product' placeholder='" + product_name + "' id='" + a + "' required tabindex='" + tab1 + "'><input type='hidden' class='common_product autocomplete_hidden_value  product_id_" + count + "' name='product_id[]' id='SchoolHiddenId'/></td>" +
            "<td><input type='number'  class='form-control text-right  available_quantity_" + count + "' value='' min='0' disabled readonly></td>" +
            " <td><input type='hidden' name='available_quantity[]' id='' class='form-control text-right common_avail_qnt available_quantity_" + count + "' value='0' readonly='readonly' /><input class='form-control text-right common_name unit_" + count + " valid' value='' readonly='' aria-invalid='false' type='text'></td>" +
            "<td> <input type='text' name='product_quantity[]' value='0' required='required' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='total_qntt_" + count + "' class='common_qnt total_qntt_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab3 + "'/>" +
             
            "<td  class='text-center'><input type='text' value='0.00' name='product_rate[]' onkeyup='quantity_calculate(" + count + ");' readonly='readonly' onchange='quantity_calculate(" + count + ");' id='price_item_" + count + "' class='common_rate price_item" + count + " form-control text-right' required placeholder='0.00' min='0'  tabindex='" + tab4 + "'/>     </td>" 
            + 
            "<td class='text-right'><input class='total_price_wd  form-control text-right' type='text' name='total_price_wd[]' id='total_price_wd_" + count + "' value='0.00' readonly='readonly'/><input class='total_discounted_price_wd form-control text-right' type='hidden' name='total_discounted_price_wd[]' id='total_discounted_price_wd_" + count + "' value='0.00' readonly='readonly'/><input type='hidden' id='vat_percent_" + count + "' class='form-control text-right vat_percent_" + count + "' value='0' readonly='readonly' /><input readonly type='hidden' class='form-control text-right vat  vat_" + count + "' id='vat_" + count + "' name='vat[]' value='0.00'/><input type='hidden' id='tax_percent_" + count + "' class='form-control text-right tax_percent_" + count + "' value='0' readonly='readonly' /><input readonly tax type='hidden' class='form-control text-right tax  tax_" + count + "' id='tax_" + count + "' name='tax[]' value='0.00'/></td>"
            
            +
            "<td class='text-center'><input type='text' name='discount[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='discount_" + count + "' class='form-control text-right discount_per_item_permission' placeholder='0.00' value='0.00' min='0' tabindex='" + tab5 + "' /><input type='hidden' name='distributed_discount[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='distributed_discount_" + count + "' class='form-control text-right' placeholder='0.00' value='0.00' min='0' readonly tabindex='" + tab5 + "' /><input type='hidden' readonly name='item_total_discount[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='item_total_discount_" + count + "' class='form-control text-right' placeholder='0.00' value='0.00' min='0' tabindex='" + tab5 + "' /><input type='hidden' value='' name='discount_type' id='discount_type_" + count + "'></td>" +

            "<td class='text-right' ><input class='total_price form-control text-right' type='text' name='total_price[]' id='total_price_" + count + "' value='0.00' readonly='readonly'/></td>" +

            "<td>" + tbfild + "<button tabindex='" + tab5 + "' style='text-align: right;' class='btn btn-danger' type='button' value='Delete' onclick='deleteRow(this)'><i class='fa fa-close'></i></button></td>",
            document.getElementById(t).appendChild(e),
            document.getElementById(a).focus(),
            document.getElementById("add_invoice_item").setAttribute("tabindex", tab6);
        document.getElementById("details").setAttribute("tabindex", tab7);
        document.getElementById("invoice_discount").setAttribute("tabindex", tab8);
        document.getElementById("shipping_cost").setAttribute("tabindex", tab9);
        document.getElementById("paidAmount").setAttribute("tabindex", tab10);
        // document.getElementById("full_paid_tab").setAttribute("tabindex", tab11);
        document.getElementById("add_invoice").setAttribute("tabindex", tab12);
       

        count++
    }
}
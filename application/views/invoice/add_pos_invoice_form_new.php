<!-- Customer js php -->
<!-- <link href="<?php echo base_url('assets/css/gui_pos.css') ?>" rel="stylesheet" type="text/css" /> -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>

<!-- <script src="<?php echo base_url() ?>my-assets/js/admin_js/pos_invoice.js.php"></script> -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/pos_invoice_new.js.php"></script>
<!-- <script src="<?php echo base_url() ?>my-assets/js/admin_js/pos_invoice.js" type="text/javascript"></script> -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/pos_invoice_new.js" type="text/javascript"></script>


<?php
$currentURL = $this->uri->uri_string();
$params   = $_SERVER['QUERY_STRING'];
// $fullURL = $currentURL . '?' . $params;
$_SESSION['redirect_uri'] = $currentURL;

?>



<!-- Customer type change by javascript end -->

<!-- Add New Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo pos_display('new_pos_invoice') ?></h1>
            <small><?php echo pos_display('add_new_pos_invoice') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo pos_display('home') ?></a></li>
                <li><a href="#"><?php echo pos_display('invoice') ?></a></li>
                <li class="active"><?php echo pos_display('new_pos_invoice') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <div class="alert alert-danger alert-dismissible fade in altmsg" id="almsg" role="alert"> No Available Qty ..
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
        </div>

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

                <?php if ($this->permission1->method('new_invoice', 'create')->access()) { ?>
                    <a href="<?php echo base_url('Cinvoice') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo pos_display('new_invoice') ?> </a>
                <?php } ?>
                <?php if ($this->permission1->method('manage_invoice', 'read')->access()) { ?>
                    <a href="<?php echo base_url('Cinvoice/manage_invoice') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo pos_display('manage_invoice') ?> </a>
                <?php } ?>

            </div>
        </div>

        <!-- POS Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="text-left"><?php echo pos_display('new_pos_invoice') ?></span>
                        </div>
                    </div>
                    <br>

                    <div class="modal fade modal-warning" id="add_receiver_modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <a href="#" class="close" data-dismiss="modal">&times;</a>
                                    <h3 class="modal-title">Add New Receiver</h3>
                                </div>

                                <?php echo form_open('Cinvoice/add_receiver', array('class' => 'form-vertical', 'id' => 'add_receiver_form')) ?>
                                <div class="modal-body">
                                    <div id="customeMessage_rec" class="alert hide"></div>
                                    <div class="panel-body">
                                        <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                        <div class="form-group row">
                                            <label for="receiver_name" class="col-sm-4 col-form-label">Receiver Name<i class="text-danger">*</i></label>
                                            <div class="col-sm-6">
                                                <input class="form-control" name="receiver_name" id="" type="text" placeholder="Receiver Name" required="" tabindex="1">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="receiver_number" class="col-sm-4 col-form-label">Receiver Mobile No.<i class="text-danger">*</i></label>
                                            <div class="col-sm-6">
                                                <input class="form-control" name="receiver_number" id="receiver_number" type="text" placeholder="Mobile No." required="" tabindex="1">
                                            </div>
                                        </div>


                                    </div>

                                </div>

                                <div class="modal-footer">

                                    <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>

                                    <input type="submit" class="btn btn-success" value="Submit">
                                </div>
                                <?php echo form_close() ?>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                    <?php echo form_open_multipart('Cinvoice/manual_sales_insert', array('class' => 'form-vertical', 'id' => 'pos_sale_insert', 'name' => 'insert_pos_invoice')) ?>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="outlet_name" class="col-sm-3 col-form-label"><?php echo pos_display('outlet_name'); ?>
                                        <i class="text-danger">*</i>
                                    </label>
                                    <!-- <div class="col-sm-6">
                                        <select name="outlet_name" id="outlet_name" class="form-control">
                                            <?php if ($outlet_list) { ?>
                                                {outlet_list}
                                                <option value="{outlet_id}">{outlet_name}</option>
                                                {/outlet_list}
                                            <?php } else { ?>
                                                {cw}
                                                <option value="{warehouse_id}">{central_warehouse}</option>
                                                {/cw}
                                            <?php } ?>
                                        </select>
                                    </div> -->

                                    <div class="col-sm-6">
                                        <select name="outlet_name" id="outlet_name" class="form-control">
                                            <?php if ($outlet_list) {foreach ($outlet_list as $outlet) { ?>
                                                 
                                                    <option value="<?php echo html_escape($outlet['outlet_id']) ?>" selected><?php echo html_escape($outlet['outlet_name']); ?></option>
                                               
                                                <?php }} else { ?>
                                                 <option value="HK7TGDT69VFMXB7" selected>Central Warehouse</option>
                                            <?php
                                                }
                                             ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="add_item_p" class="col-sm-3 col-form-label"><?php echo pos_display('barcode') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input type="text" name="product_name" class="form-control bq" placeholder='<?php echo pos_display('barcode_qrcode_scan_here') ?>' id="add_item_p" autocomplete='off' tabindex="1" value="">
                                        <input type="hidden" id="product_value" name="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="add_item" class="col-sm-4 col-form-label"><?php echo pos_display('product_sku'); ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="product_name" class="form-control bq" placeholder='<?php echo pos_display('product_sku'); ?>' id="add_item_m_p" autocomplete='off' tabindex="1" value="">
                                        <input type="hidden" id="product_value" name="">
                                        <input type="hidden" id="sel_type" name="sel_type" value="2">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="invoice_date" class="col-sm-3 col-form-label"><?php echo pos_display('date') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <?php

                                        $date = date('Y-m-d');
                                        ?>
                                        <input class="form-control" type="text" size="50" readonly id="invoice_date" name="invoice_date" required value="<?php echo html_escape($date); ?>" tabindex="2" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6" id="delivery_from">
                                <div class="form-group row">
                                    <label for="deliver_type" class="col-sm-4 col-form-label">
                                        <?php echo pos_display('delivery_type'); ?><i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select name="deliver_type" class="form-control" onchange="delivery_type(this.value)" tabindex="3">
                                            <option value="1">Pick Up</option>
                                            <option value="2">Courier</option>

                                        </select>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6 " id="payment_from_1">
                                <div class="form-group row">
                                    <label for="customer_name" class="col-sm-3 col-form-label"><?php echo pos_display('customer_name'); ?><i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input autocomplete="off" type="text" size="100" name="customer_name" class=" form-control" placeholder="<?php echo pos_display('customer_name'); ?>" id="customer_name" tabindex="1" onkeyup="customer_autocomplete()" value="Walking Customer" />

                                        <input autocomplete="off" id="autocomplete_customer_id" class="customer_hidden_value abc" type="hidden" name="customer_id" value="1">
                                    </div>
                                    <?php if ($this->permission1->method('add_customer', 'create')->access()) { ?>
                                        <div class=" col-sm-3">
                                            <a href="#" class="client-add-btn btn btn-success" id="add_customer" onclick="add_customer()" aria-hidden="true" data-toggle="modal" data-target="#cust_info"><i class="ti-plus m-r-2"></i></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-sm-6" id="payment_from_2">
                                <div class="form-group row">
                                    <label for="customer_name_others" class="col-sm-3 col-form-label"><?php echo pos_display('customer_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input autofill="off" type="text" size="100" name="customer_name_others" placeholder='<?php echo pos_display('customer_name') ?>' id="customer_name_others" class="form-control" tabindex="5" />
                                    </div>

                                    <div class="col-sm-3">
                                        <input onClick="active_customer('payment_from_2')" type="button" id="myRadioButton_2" class="btn btn-success checkbox_account" name="customer_confirm_others" value="<?php echo pos_display('old_customer') ?> ">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer_name_others_address" class="col-sm-3 col-form-label"><?php echo pos_display('customer_mobile') ?></label>
                                    <div class="col-sm-6">
                                        <input type="text" size="100" name="customer_mobile" class=" form-control" placeholder='<?php echo pos_display('customer_mobile') ?>' id="customer_mobile" tabindex="6" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_name_others_address" class="col-sm-3 col-form-label"><?php echo pos_display('address') ?></label>
                                    <div class="col-sm-6">
                                        <input type="text" size="100" name="customer_name_others_address" class=" form-control" placeholder='<?php echo pos_display('address') ?>' id="customer_name_others_address" tabindex="6" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" id="bank_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-4 col-form-label"><?php
                                                                                        echo pos_display('bank');
                                                                                        ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">


                                        <input type="text" name="bank_id" class="form-control" id="bank_id" placeholder="Bank">

                                    </div>
                                    <label for="bank" class="col-sm-4 col-form-label">Cheque NO:
                                        <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input type="number" name="cheque_no" class=" form-control" placeholder="" />
                                    </div>


                                    <label for="date" class="col-sm-4 col-form-label">Cheque Date <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <?php

                                        $date = date('Y-m-d');
                                        ?>
                                        <input class="datepicker form-control" type="text" size="50" name="cheque_date" id="" value="" tabindex="4" />
                                    </div>

                                </div>
                            </div>


                            <div class="col-sm-6" style="display:none;" id="courier_div">

                                <div class="form-group row">
                                    <label for="bank" class="col-sm-4 col-form-label">Courier Name <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">

                                        <select name="courier_id" class="form-control bankpayment" id="" onchange="get_branch(this.value)">
                                            <option value="">Select Location</option>
                                            <?php foreach ($courier_list as $courier) { ?>
                                                <option value="<?php echo html_escape($courier['courier_id']) ?>"><?php echo html_escape($courier['courier_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group row branch_div" id="branch_div" style="display: none;">
                                    <label for="bank" class="col-sm-4 col-form-label">Branch<i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <select name="branch_id" id="branch_id" class="branch_id form-control text-right" tabindex="1" onchange="get_charge(this.value)">

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row branch_div" id="branch_div" style="display: none;">
                                    <label for="bank" class="col-sm-4 col-form-label">Location<i class="text-danger">*</i></label>
                                    <div class="col-sm-6 ">
                                          <input type="radio" id="inside" name="charge" value="" onchange="put_value(this.value)">
                                          <label for="outside">Inside</label><br>
                                          <input type="radio" id="outside" name="charge" value="" onchange="put_value(this.value)">
                                          <label for="outside">Outside</label><br>
                                          <input type="radio" id="sub" name="charge" value="" onchange="put_value(this.value)">
                                          <label for="sub">Sub</label>
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <label for="bank" class="col-sm-4 col-form-label">Condition<i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <select name="courier_condtion" class="form-control bankpayment" id="" onchange="condition_charge(this.value)">
                                            <option value="">Select Condition</option>
                                            <option value="1">Conditional</option>
                                            <option value="2">Partial</option>
                                            <option value="3">Unconditional</option>

                                        </select>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label for="deli_receiver" class="col-sm-4 col-form-label">Receiver</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="deli_reciever" id="deli_receiver" placeholder="Select option" onchange="receiver_changed(this)">
                                            <option value="">Select Receiver</option>
                                            {receiver_list}
                                            <option value="{id}">{receiver_name}</option>
                                            {/receiver_list}
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-sm btn-success" id="add_rec_btn" aria-hidden="true" data-toggle="modal" data-target="#add_receiver_modal">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group row" id="receiver_num_div" style="display: none;">
                                    <label for="del_rec_num" class="col-sm-4 col-form-label">Receiver Number</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="del_rec_num" name="del_rec_num">
                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-6" style="display: none" id="bkash_div">
                                <div class="form-group row">
                                    <label for="bkash" class="col-sm-4 col-form-label">Bkash Number <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <select name="bkash_id" class="form-control bankpayment" id="bkash_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($bkash_list as $bkash) { ?>
                                                <option value="<?php echo html_escape($bkash['bkash_id']) ?>"><?php echo html_escape($bkash['bkash_no']); ?> (<?php echo html_escape($bkash['ac_name']); ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="addinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field"><?php echo pos_display('item_information') ?> <i class="text-danger">*</i></th>

                                        <th class="text-center"><?php echo pos_display('available_qnty') ?></th>
                                        <th class="text-center"><?php echo pos_display('unit') ?></th>
                                        <th class="text-center"><?php echo pos_display('quantity') ?> <i class="text-danger">*</i></th>
                                        <!--                                        <th class="text-center">Warranty Date</th>-->
                                        <th class="text-center"><?php echo pos_display('rate') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center invoice_fields"><?php echo pos_display('total') ?> </th>

                                        <?php
                                        $colsp = 7;
                                        $change_span = 5;
                                        $due_span = 6;
                                        $subtotal_discount_span = 2;
                                        ?>


                                        <!-- <?php if ($discount_type == 1) { ?>
                                            <th class="text-center invoice_fields"><?php echo pos_display('discount_percentage') ?></th>
                                        <?php } elseif ($discount_type == 2) { ?>
                                            <th class="text-center invoice_fields"><?php echo pos_display('discount') ?> </th>
                                        <?php } elseif ($discount_type == 3) { ?>
                                            <th class="text-center invoice_fields"><?php echo pos_display('fixed_dis') ?> </th>
                                        <?php } ?> -->
                               
                                        <th class="text-center invoice_fields"><?php echo pos_display('discount_percentage') ?>
                                
                                        <th class="text-center invoice_fields"><?php echo pos_display('total_with_discount') ?>
                                        </th>
                                        <th class="text-center invoice_fields"><?php echo pos_display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                    <tr></tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                       
                                        <td>
                                            <input type="button" id="add_invoice_item" class="btn btn-info text-right" name="add-invoice-item" onClick="addInputField2('addinvoiceItem', '<?php echo pos_display('product_name'); ?>', '<?php echo pos_display('none'); ?>','<?php echo $vat == "enable" ? 1 : 0; ?>','<?php echo $tax == "enable" ? 1 : 0; ?>')" value="<?php echo pos_display('add_new_item') ?>" tabindex="12" />
                                        </td>
                                        <td class="text-right" colspan="6"><b><?php echo pos_display('sub_total'); ?>:</b></td>
                                        <td class="text-right">
                                        <input type="hidden" id="item_wise_flat_discount" value="<?php echo $item_wise_flat_discount ?>">
                                            <input type="text" id="sub_total" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" class="form-control text-right" name="sub_total" value="" placeholder="0.00" readonly />
                                        </td>
                                    </tr>
                                   
                                    <tr <?php echo ($sale_discount == "disable") ? 'hidden' : '' ?>>
                                        <td class="text-right" colspan="7"><b><?php echo pos_display('invoice_discount') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" id="invoice_discount" class="form-control text-right total_discount" name="invoice_discount" placeholder="0.00" />
                                            <input type="hidden" id="txfieldnum" value="{taxnumber}">
                                        </td>
                                    </tr>
                                
                                    <tr <?php echo ($sale_discount_percent == "disable") ? 'hidden' : '' ?>>
                                        <td class="text-right" colspan="7"><b><?php echo pos_display('invoice_discount'); ?>(%):</b></td>
                                        <td class="text-right">
                                            <input type="text" id="perc_discount" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" class="form-control text-right" name="perc_discount" value="0.00" placeholder="0.00" />
                                        </td>
                                    </tr>
                                    <tr <?php echo ($sale_discount_percent == "disable" && $sale_discount == "disable") ? 'hidden' : '' ?>>
                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b><?php echo pos_display('total_discount') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_discount_ammount" class="form-control text-right" name="total_discount" value="0.00" readonly="readonly" />
                                            <input type="hidden" id="total_price_with_total_discount" class="form-control text-right" name="total_price_with_total_discount" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b><?php echo pos_display('total_price_with_total_discount') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_price_with_total_discount" class="form-control text-right" name="total_price_with_total_discount" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr> -->
                                    <?php $x = 0;
                                    foreach ($taxes as $taxfldt) { ?>
                                        <tr class="hideableRow hiddenRow">
                                            <td class="text-right" colspan="7"><b><?php echo html_escape($taxfldt['tax_name']) ?></b></td>
                                            <td class="text-right">
                                                <input id="total_tax_ammount<?php echo $x; ?>" tabindex="-1" class="form-control text-right valid totalTax" name="total_tax<?php echo $x; ?>" value="0.00" readonly="readonly" aria-invalid="false" type="text">
                                            </td>
                                        </tr>
                                    <?php $x++;
                                    } ?>

                                    <!-- <tr>
                                        <input type="hidden" id="total_vat_amount" tabindex="-1" class="form-control text-right valid" name="total_vat" value="0.00" readonly="readonly" aria-invalid="false">
                                        <input type="hidden" id="total_tax_amount" tabindex="-1" class="form-control text-right valid" name="total_tax" value="0.00" readonly="readonly" aria-invalid="false">
                                    </tr> -->
                                    
                                    <tr <?php echo ($vat == "disable") ? 'hidden' : '' ?>>
                                    <input type="hidden" id="total_vat_permission" value="<?php echo $vat ?>">
                                        <td colspan="<?php echo $colsp ?>" class="text-right"><b><?php echo pos_display('total_vat') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_vat" class="form-control text-right total_vat" name="total_vat" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                  

                                    <tr <?php echo ($tax == "disable") ? 'hidden' : '' ?>>
                                    <input type="hidden" id="total_tax_permission" value="<?php echo $tax ?>">
                                        <td colspan="<?php echo $colsp ?>" class="text-right"><b><?php echo pos_display('total_tax') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_tax" class="form-control text-right total_tax" name="total_tax" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                   

                                    <tr <?php echo ($delivery_charge == "disable") ? 'hidden' : '' ?>>
                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b>Delivery Charge:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="shipping_cost" class="form-control text-right" name="shipping_cost" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" />
                                        </td>
                                    </tr>
                                    <tr <?php echo ($service_charge == "disable") ? 'hidden' : '' ?>>

                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b>Service Charge:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="service_charge" class="form-control text-right" name="service_charge" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" />
                                        </td>
                                    </tr>

                                    <!-- <tr class="hidden_tr d-none">
                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b>ADC:</b></td>
                                        <td class=" text-right">
                                            <input type="text" id="delivery_ac" class="form-control text-right" name="delivery_ac" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" value="0.00" tabindex="14" />
                                        </td>
                                    </tr> -->
                                    <tr id="condition_tr" class=" d-none">
                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b>Condition Charge:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="condition_cost" class="form-control text-right" name="condition_cost" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" value="0.00" tabindex="14" />
                                        </td>
                                    </tr>
                                    <tr id="commission_tr" class=" d-none">
                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b>Commission:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="commission" class="form-control text-right" name="commission" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" value="0.00" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="<?php echo $colsp ?>" class="text-right"><b><?php echo pos_display('grand_total') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="grandTotal" class="form-control text-right" name="grand_total_price" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?php echo $colsp ?>" class="text-right"><b><?php echo pos_display('previous'); ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="previous" class="form-control text-right" name="previous" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="<?php echo $colsp ?>"><b><?php echo pos_display('rounding'); ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="rounding" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" class="form-control text-right" name="rounding" value="" placeholder="0.00" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?php echo "4" ?> " rowspan="2">
                                            <center><label class="text-center" for="details" class="  col-form-label"><?php echo pos_display('invoice_details') ?></label></center>
                                            <textarea name="inva_details" class="form-control" placeholder="<?php echo pos_display('invoice_details') ?>"></textarea>
                                        </td>
                                        <td colspan="3" class="text-right"><b><?php echo pos_display('net_total'); ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="n_total" class="form-control text-right" name="n_total" value="0" readonly="readonly" placeholder="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="3"><b><?php echo pos_display('paid_ammount') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="paidAmount" onkeyup="invoice_paidamount();" class="form-control text-right" name="paid_amount" placeholder="0.00" tabindex="13" value="" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">

                                            <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" />
                                        </td>

                                        <td class="text-right" colspan="<?php echo $due_span ?>"><b><?php echo pos_display('due') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="dueAmmount" class="form-control text-right" name="due_amount" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">

                                        </td>
                                        <td><a href="#" class="btn btn-info" data-toggle="modal" data-target="#calculator"><i class="fa fa-calculator" aria-hidden="true"></i> Calculator</a></td>

                                        <td class="text-right" colspan="<?php echo $change_span ?>"><b><?php echo pos_display('change') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="change" class="form-control text-right" name="change" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row ">
                            <div class="col-sm-12" id="payment_div">
                                <div class="panel panel-bd lobidrag">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h3><?php echo pos_display('payment'); ?></h3>
                                            <input type="hidden" id="count" value="2">
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                        <div id="pay_div" style="margin: 10px 3px; padding:10px 0">
                                            <div class="row margin-top10">
                                                <div class="col-sm-4">
                                                    <label for="payment_type" class="col-sm-5 col-form-label">
                                                        <?php echo pos_display('payment_type'); ?>
                                                        <i class="text-danger">*</i>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <select name="paytype[]" class="form-control pay_type" required="" onchange="bank_paymet(this.value, 1)" tabindex="3">
                                                            <option value="1"><?php echo pos_display('cash_payment') ?></option>
                                                            <option value="2"><span class="">Cheque Payment</span></option>
                                                            <option value="4"><?php echo pos_display('bank_payment') ?></option>
                                                            <option value="3">Bkash Payment</option>
                                                            <option value="5">Nagad Payment</option>
                                                            <option value="7">Rocket Payment</option>
                                                            <option value="6">Card Payment</option>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="col-sm-4" id="bank_div_1" style="display:none;">
                                                    <div class="form-group row">
                                                        <label for="bank" class="col-sm-3 col-form-label">
                                                            <?php echo pos_display('bank'); ?>
                                                            <i class="text-danger">*</i>
                                                        </label>
                                                        <div class="col-sm-7">

                                                            <input type="text" name="bank_id" class="form-control" id="bank_id_1" placeholder="Bank">

                                                        </div>

                                                        <div class="col-sm-1">
                                                            <a href="#" class="client-add-btn btn btn-sm btn-info" aria-hidden="true" data-toggle="modal" data-target="#cheque_info"><i class="fa fa-file m-r-2"></i></a>
                                                        </div>
                                                    </div>

                                                </div>



                                                <div class="col-sm-4" id="bank_div_m_1" style="display:none;">
                                                    <div class="form-group row">
                                                        <label for="bank" class="col-sm-5 col-form-label">
                                                            <?php echo pos_display('bank'); ?>
                                                            <i class="text-danger">*</i>
                                                        </label>
                                                        <div class="col-sm-7">
                                                            <select name="bank_id_m[]" class="form-control bankpayment" id="bank_id_m_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($bank_list as $bank) { ?>
                                                                    <option value="<?php echo html_escape($bank['bank_id']) ?>"><?php echo html_escape($bank['bank_name']) . '(' . html_escape($bank['ac_number']) . ')'; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="bank_list" value='<option value="">Select One</option>
                                                            <?php foreach ($bank_list as $bank) { ?>
                                                                <option value="<?php echo html_escape($bank['bank_id']) ?>"><?php echo html_escape($bank['bank_name']) . '(' . html_escape($bank['ac_number']) . ')'; ?></option>
                                                            <?php } ?>'>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="bkash_div_1">

                                                    <div class="form-group row">
                                                        <label for="bkash" class="col-sm-5 col-form-label">Bkash Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="bkash_id[]" class="form-control bankpayment" id="bkash_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($bkash_list as $bkash) { ?>
                                                                    <option value="<?php echo html_escape($bkash['bkash_id']) ?>"><?php echo html_escape($bkash['bkash_no']); ?> (<?php echo html_escape($bkash['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>
                                                            <input type="hidden" id="bkash_list" value='<option value="">Select One</option>
                                                            <?php foreach ($bkash_list as $bkash) { ?>
                                                                <option value="<?php echo html_escape($bkash['bkash_id']) ?>"><?php echo html_escape($bkash['bkash_no']); ?> (<?php echo html_escape($bkash['ac_name']); ?>)</option>
                                                            <?php } ?>'>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="nagad_div_1">
                                                    <div class="form-group row">
                                                        <label for="nagad" class="col-sm-5 col-form-label">Nagad Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="nagad_id[]" class="form-control bankpayment" id="nagad_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($nagad_list as $nagad) { ?>
                                                                    <option value="<?php echo html_escape($nagad['nagad_id']) ?>"><?php echo html_escape($nagad['nagad_no']); ?> (<?php echo html_escape($nagad['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="nagad_list" value='<option value="">Select One</option>
                                                            <?php foreach ($nagad_list as $nagad) { ?>
                                                                <option value="<?php echo html_escape($nagad['nagad_id']) ?>"><?php echo html_escape($nagad['nagad_no']); ?> (<?php echo html_escape($nagad['ac_name']); ?>)</option>
                                                            <?php } ?>'>

                                                        </div>


                                                    </div>
                                                </div>

                                                <div class="col-sm-4" style="display: none" id="rocket_div_1">
                                                    <div class="form-group row">
                                                        <label for="rocket" class="col-sm-5 col-form-label">Rocket Number <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="rocket_id[]" class="form-control bankpayment" id="rocket_id_1">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($rocket_list as $rocket) { ?>
                                                                    <option value="<?php echo html_escape($rocket['rocket_id']) ?>"><?php echo html_escape($rocket['rocket_no']); ?> (<?php echo html_escape($rocket['ac_name']); ?>)</option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="rocket_list" value='<option value="">Select One</option>
                                            <?php foreach ($rocket_list as $rocket) { ?>
                                                <option value="<?php echo html_escape($rocket['rocket_id']) ?>"><?php echo html_escape($rocket['rocket_no']); ?> (<?php echo html_escape($rocket['ac_name']); ?>)</option>
                                            <?php } ?>'>

                                                        </div>


                                                    </div>
                                                </div>


                                                <div class="col-sm-4" style="display: none" id="card_div_1">
                                                    <div class="form-group row">
                                                        <label for="card" class="col-sm-5 col-form-label">Card Type <i class="text-danger">*</i></label>
                                                        <div class="col-sm-7">
                                                            <select name="card_id[]" class="form-control bankpayment" id="card_id_1" onchange="">
                                                                <option value="">Select One</option>
                                                                <?php foreach ($card_list as $card) { ?>
                                                                    <option value="<?php echo html_escape($card['card_no_id']) ?>"><?php echo html_escape($card['card_no'] . ' (' . $card['card_name'] . ')'); ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" id="card_list" value='<option value="">Select One</option>
                                                            <?php foreach ($card_list as $card) { ?>
                                                                <option value="<?php echo html_escape($card['card_no_id']) ?>"><?php echo html_escape($card['card_no'] . ' (' . $card['card_name'] . ')'); ?></option>
                                                            <?php } ?>'>

                                                        </div>


                                                    </div>


                                                </div>

                                                <div class="col-sm-3" id="ammnt_1">
                                                    <label for="p_amount" class="col-sm-5 col-form-label"> <?php echo pos_display('amount '); ?> <i class="text-danger">*</i></label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control p_amount" type="text" name="p_amount[]" onchange="calc_paid()" onkeyup="calc_paid()">
                                                    </div>


                                                </div>
                                                <div class="col-sm-1">
                                                    <a id="add_pt_btn" onclick="add_pay_row(1)" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="submit" id="add_invoice" class="btn btn-success" name="add-invoice" value="<?php echo pos_display('submit') ?>" tabindex="17" />
                                    <!-- <input type="submit" value="<?php echo pos_display('submit_and_add_another') ?>" name="add-purchase-another" class="btn btn-large btn-success" id="add_purchase_another" > -->
                                </div>
                            </div>

                            <div class="modal fade modal-success" id="cheque_info" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <a href="#" class="close" data-dismiss="modal">&times;</a>
                                            <h3 class="modal-title">Add Cheque</h3>
                                        </div>

                                        <div class="modal-body">
                                            <div id="customeMessage" class="alert hide"></div>

                                            <div class="panel-body">
                                                <div class="addCheque">
                                                    <div id="cheque" class="cheque">
                                                        <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                                        <label for="bank" class="col-sm-4 col-form-label">Cheque type:
                                                            <i class="text-danger">*</i></label>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="cheque_type[]" class=" form-control" placeholder="" autocomplete="off" />

                                                        </div>

                                                        <label for="bank" class="col-sm-4 col-form-label">Cheque NO:
                                                            <i class="text-danger">*</i></label>
                                                        <div class="col-sm-6">
                                                            <input type="number" name="cheque_no[]" class=" form-control" placeholder="" autocomplete="off" />

                                                        </div>


                                                        <label for="date" class="col-sm-4 col-form-label">Due Date <i class="text-danger">*</i></label>
                                                        <div class="col-sm-6">

                                                            <input class="form-control" type="date" size="50" name="cheque_date[]" id="" value="" tabindex="4" autocomplete="off" placeholder="mm/dd/yyyy" />
                                                        </div>

                                                        <label for="bank" class="col-sm-4 col-form-label">Amount:
                                                            <i class="text-danger">*</i></label>

                                                        <div class="col-sm-6">
                                                            <input type="number" name="amount[]" class=" form-control" placeholder="" autocomplete="off" />

                                                        </div>

                                                        <label for="bank" class="col-sm-4 col-form-label">Image:
                                                            <i class="text-danger">*</i></label>

                                                        <div class="col-sm-6" style="padding-bottom:10px ">
                                                            <input type="file" name="image[]" class="form-control" id="image" tabindex="4">

                                                        </div>




                                                        <div class=" col-sm-1">
                                                            <a href="javascript:" id="Add_cheque" class="client-add-btn btn btn-primary add_cheque"><i class="fa fa-plus-circle m-r-2"></i></a>
                                                        </div>


                                                    </div>
                                                </div>



                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>


                                        </div>

                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->


                        </div>


                    </div>

                    <?php echo form_close() ?>
                </div>
            </div>

            <!-- <div class="modal fade" id="printconfirmodal" tabindex="-1" role="dialog" aria-labelledby="printconfirmodal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                            <h4 class="modal-tit le" id="myModalLabel"><?php echo pos_display('print') ?></h4>
                        </div>
                        <?php echo form_open('Cinvoice/invoice_inserted_data_manual', array('class' => 'form-vertical', 'id' => '', 'name' => '')) ?>

                        <div class="modal-body">
                            <div id="outputs" class="hide alert alert-danger"></div>
                            <h3> <?php echo pos_display('successfully_inserted') ?></h3>
                            <h4><?php echo pos_display('do_you_want_to_print') ?> ??</h4>


                            <input type="hidden" name="invoice_id" id="inv_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="cancelprint()" class="btn btn-default" data-dismiss="modal"><?php echo pos_display('no') ?></button>
                            <button type="submit" class="btn btn-primary" id="yes"><?php echo pos_display('yes') ?></button>

                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div> -->

            <div class="modal fade modal-success" id="cust_info" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <a href="#" class="close" data-dismiss="modal">&times;</a>
                            <h3 class="modal-title"><?php echo pos_display('add_new_customer') ?></h3>
                        </div>

                        <?php echo form_open('Cinvoice/instant_customer', array('class' => 'form-vertical', 'id' => 'newcustomer')) ?>
                        <div class="modal-body">
                            <div id="customeMessage" class="alert hide"></div>
                            <div class="panel-body">
                                <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">


                                <div class="form-group row">
                                    <label for="customer_name" class="col-sm-3 col-form-label"><?php echo pos_display('customer_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="customer_name" id="m_customer_name" type="text" placeholder="<?php echo pos_display('customer_name') ?>" required="" tabindex="1">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_name" class="col-sm-3 col-form-label"><?php echo pos_display('shop_name'); ?></label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="shop_name" id="shop_name" type="text" placeholder="<?php echo pos_display('shop_name'); ?>" tabindex="1">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-form-label"><?php echo pos_display('customer_email') ?></label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="email" id="email" type="email" placeholder="<?php echo pos_display('customer_email') ?>" tabindex="2">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-3 col-form-label"><?php echo pos_display('customer_mobile') ?></label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="mobile" id="mobile" type="number" placeholder="<?php echo pos_display('customer_mobile') ?>" min="0" tabindex="3">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_id_two" class="col-sm-3 col-form-label"><?php echo pos_display('contact_person') ?></label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="contact_person" id="" type="text" placeholder="<?php echo pos_display('contact_person') ?>" tabindex="1">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_id_two" class="col-sm-3 col-form-label"><?php echo pos_display('contact_mobile') ?></label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="contact" id="" type="number" placeholder="<?php echo pos_display('contact_mobile') ?>" tabindex="1">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="address " class="col-sm-3 col-form-label"><?php echo pos_display('customer_address') ?></label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="address" id="address " rows="3" placeholder="<?php echo pos_display('customer_address') ?>" tabindex="4"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="website" class="col-sm-3 col-form-label"><?php echo pos_display('customer_type') ?></label>
                                    <div class="col-sm-6">

                                        <select name="cus_type" class="form-control" tabindex="3">

                                            <option value="2">Retail Customer</option>
                                            <option value="1">WholeSale Customer</option>

                                        </select>

                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <a href="#" class="btn btn-danger" data-dismiss="modal"><?php echo pos_display('close') ?></a>

                            <input type="submit" class="btn btn-success" value="<?php echo pos_display('submit') ?>">
                        </div>
                        <?php echo form_close() ?>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- /.modal -->
        </div>

    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        
        "use strict";
        var barcodeScannerTimerP;
        var barcodeStringPos = '';
        $('#add_item_m_p').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault()
                // var product_id = $(this).val();
                var product_id = $(this).val();
                // var product_id = $("#SchoolHiddenId_" + product_id).val();
                var exist = $("#SchoolHiddenSku_" + product_id).val();

                //
                // return
                var qty = $("#total_qntt_" + product_id).val();
                var add_qty = parseInt(qty) + 1;
                var csrf_test_name = $('[name="csrf_test_name"]').val();
                var base_url = $("#base_url").val();
                if (product_id == exist) {
                    $("#total_qntt_" + product_id).val(add_qty);
                    quantity_calculate(product_id);
                    calculateSum();
                    invoice_paidamount();
                    document.getElementById('add_item_m_p').value = '';
                    document.getElementById('add_item_m_p').focus();
                } else {
                    $.ajax({
                        type: "post",
                        async: false,
                        url: base_url + 'Cinvoice/insert_pos_invoice_new',
                        data: {
                            product_id: product_id,
                            csrf_test_name: csrf_test_name
                        },
                        success: function(data) {
                            if (data == false) {
                                alert('This Product Not Found !');
                                document.getElementById('add_item_m_p').value = '';
                                document.getElementById('add_item_m_p').focus();
                                quantity_calculate(product_id);
                                calculateSum();
                                invoice_paidamount();
                            } else {
                                $("#hidden_tr").css("display", "none");
                                document.getElementById('add_item_m_p').value = '';
                                document.getElementById('add_item_m_p').focus();
                                $('#addinvoice tbody').append(data);
                                quantity_calculate(product_id);
                                calculateSum();
                                invoice_paidamount();
                            }
                        },
                        error: function() {
                            alert('Request Failed, Please check your code and try again!');
                        }
                    });
                }
            }
        });

        // capture barcode scanner input
        $('#add_item_p').on('keypress', function(e) {
           
             if (e.keyCode == 13) {
                e.preventDefault()
                // var product_id = $(this).val();
                var product_id = $(this).val();
                // var product_id = $("#SchoolHiddenId_" + product_id).val();
                var exist = $("#SchoolHiddenSku_" + product_id).val();

                //
                // return
                var qty = $("#total_qntt_" + product_id).val();
                var add_qty = parseInt(qty) + 1;
                var csrf_test_name = $('[name="csrf_test_name"]').val();
                var base_url = $("#base_url").val();
                if (product_id == exist) {
                    $("#total_qntt_" + product_id).val(add_qty);
                    quantity_calculate(product_id);
                    calculateSum();
                    invoice_paidamount();
                    document.getElementById('add_item_m_p').value = '';
                    document.getElementById('add_item_m_p').focus();
                } else {
                    $.ajax({
                        type: "post",
                        async: false,
                        url: base_url + 'Cinvoice/insert_pos_invoice_new',
                        data: {
                            product_id: product_id,
                            csrf_test_name: csrf_test_name
                        },
                        success: function(data) {
                            if (data == false) {
                                alert('This Product Not Found !');
                                document.getElementById('add_item_m_p').value = '';
                                document.getElementById('add_item_m_p').focus();
                                quantity_calculate(product_id);
                                calculateSum();
                                invoice_paidamount();
                            } else {
                                $("#hidden_tr").css("display", "none");
                                document.getElementById('add_item_m_p').value = '';
                                document.getElementById('add_item_m_p').focus();
                                $('#addinvoice tbody').append(data);
                                quantity_calculate(product_id);
                                calculateSum();
                                invoice_paidamount();
                            }
                        },
                        error: function() {
                            alert('Request Failed, Please check your code and try again!');
                        }
                    });
                }
            }
        });


        "use strict";

        function processBarcodePosinvoice() {
              e.preventDefault()
            if (barcodeStringPos != '') {
                var product_id = barcodeStringPos;
                var exist = $("#SchoolHiddenId_" + product_id).val();
                var qty = $(".total_qntt_" + product_id).val();
                var add_qty = parseInt(qty) + 1;
                var csrf_test_name = $('[name="csrf_test_name"]').val();
                var base_url = $("#base_url").val();
                if (product_id == exist) {
                    $(".total_qntt_" + product_id).val(add_qty);
                    quantity_calculate(product_id);
                    calculateSum();
                    invoice_paidamount();
                    document.getElementById('add_item_p').value = '';
                    document.getElementById('add_item_p').focus();
                } else {
                    $.ajax({
                        type: "post",
                        async: false,
                        url: base_url + 'Cinvoice/insert_pos_invoice',
                        data: {
                            product_id: product_id,
                            csrf_test_name: csrf_test_name
                        },
                        success: function(data) {
                            if (data == false) {
                                toastr.error('This Product Not Found !');
                                document.getElementById('add_item_p').value = '';
                                document.getElementById('add_item_p').focus();
                                quantity_calculate(product_id);
                                calculateSum();
                                invoice_paidamount();
                            } else {
                                $("#hidden_tr").css("display", "none");
                                document.getElementById('add_item_p').value = '';
                                document.getElementById('add_item_p').focus();
                                $('#addinvoice tbody').append(data);
                                quantity_calculate(product_id);
                                calculateSum();
                                invoice_paidamount();
                            }
                        },
                        error: function() {
                            toastr.error('Request Failed, Please check your code and try again!');
                        }
                    });
                }
            } else {
                alert('barcode is invalid: ' + barcodeStringPos);
            }

            barcodeStringPos = ''; // reset
        }

    });

    "use strict";

    function get_branch(courier_id) {

        var base_url = "<?= base_url() ?>";
        var csrf_test_name = $('[name="csrf_test_name"]').val();



        $.ajax({
            url: base_url + "Ccourier/branch_by_courier",
            method: 'post',
            data: {
                courier_id: courier_id,
                csrf_test_name: csrf_test_name
            },
            cache: false,
            success: function(data) {
                var obj = jQuery.parseJSON(data);
                $('.branch_id').html(obj.branch);


                $(".branch_div").css("display", "block");
                // if(courier_id == obj.courier_id ){
                //     $("#subCat_div").css("display", "block");
                // }else{
                //     $("#subCat_div").css("display", "none");
                // }
            }
        })

    }

    function get_charge(branch_id) {

        var base_url = "<?= base_url() ?>";
        var csrf_test_name = $('[name="csrf_test_name"]').val();



        $.ajax({
            url: base_url + "Ccourier/charge_by_branch",
            method: 'post',
            data: {
                branch_id: branch_id,
                csrf_test_name: csrf_test_name
            },
            cache: false,
            success: function(data) {
                var obj = jQuery.parseJSON(data);
                //   console.log(obj[0].inside)

                $('#inside').val(obj[0].inside);
                $('#outside').val(obj[0].outside);
                $('#sub').val(obj[0].sub);

            }
        })

    }

    function put_value(val) {

        $('#delivery_ac').val(val);
    }
</script>



<script type="text/javascript">
    $(document).ready(function() {
        $('#pos_sale_insert').one('submit', function() {
            $("#add_invoice").prop('disabled', true);
        });
    });
</script>
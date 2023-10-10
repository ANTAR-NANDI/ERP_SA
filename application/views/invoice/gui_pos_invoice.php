<link href="<?php echo base_url('assets/css/gui_pos.css?ver=') . time() ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/flickity.min.css') ?>" rel="stylesheet" type="text/css" />


<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php"></script>
<!-- Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/pos_invoice.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>my-assets/js/admin_js/guibarcode.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/js/flickity.pkgd.min.js" type="text/javascript"></script>

<style>
    * {
        font-family: Kalpurush;
    }

    th {
        font-family: 'Kalpurush';
        font-style: normal;
        font-weight: 700;
        font-size: 12px;
        /*line-height: 21px;*/
        /* identical to box height */


        color: #000000;
    }
</style>

<div class="content-wrapper">

    <!-- <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('invoice') ?></h1>
            <small><?php echo display('gui_pos') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('gui_pos') ?></li>
            </ol>
        </div>
    </section> -->

    <section class="content main-content">
        <div class="alert alert-danger fade in" id="almsg"> No Available Qty ..
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
            <?php echo form_open_multipart('Cinvoice/manual_sales_insert', array('class' => 'form-vertical', 'id' => 'pos_sale_insert', 'name' => 'insert_pos_invoice')) ?>

            <div class="col-sm-12">
                <div class="panel panel-default panel-content">
                    <div class="panel-body ">

                        <div class="row m-5 bill-section">

                            <!-- <div class="col-sm-3">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active">
                                        <a href="#nsale" role="tab" data-toggle="tab">
                                            <?php echo display('new_invoice'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#saleList" role="tab" data-toggle="tab">
                                            <?php echo display('todays_sale'); ?>
                                        </a>
                                    </li>


                                </ul>
                            </div> -->

                            
                        </div>




                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="nsale">


                                <div class="panel panel-default panel-content">
                                    <div class="panel-body">
                                        <input name="url" type="hidden" id="posurl" value="<?php echo base_url("Cinvoice/getitemlist") ?>" />
<div class="col-sm-12 fixedclasspos1">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="li_p">

                                        <div class="total_item"> মোট পণ্য: <span id="total_item_html">0</span></div>

                                    </li>
                                    <li class="li_p">

                                        <div class="total_qty">মোট পরিমাণ: <span id="total_qty_html">0</span></div>

                                    </li>

                                    <!-- <li class="li_p">

                                            <div class="total_amount">Total Amount : <span id="total_amount_html">0.00</span></div>

                                    </li> -->

                                    <li class="li_p bill">


                                        <div class="bill">
                                            <label for="net_total" class="col-sm-1 title-text_n1  col-form-label">মোটঃ</label>
                                            <div class="col-sm-2 bill-amount">
                                                <input type="text" id="n_total" class="form-control box text-center bill-amount" name="n_total" value="0.00" readonly="readonly" placeholder="" />
                                                <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" id="baseurl" />
                                            </div>


                                            <label for="date" class="col-sm-1 title-text_n1  col-form-label paid-color">পেইডঃ </label>
                                            <div class="col-sm-2 bill-amount paid-color">
                                                <input type="text" id="paidAmount" class="form-control box text-center bill-amount paid-color" onkeyup="invoice_paidamount()" onkeypress="invoice_paidamount()" name="paid_amount" placeholder="0.00" tabindex="13" autocomplete="off" readonly />
                                            </div>

                                            <label for="date" class="col-sm-1 title-text_n1  col-form-label red">বকেয়াঃ</label>
                                            <div class="col-sm-2 bill-amount red">
                                                <input type="text" id="dueAmmount" class="form-control box text-center bill-amount red" name="due_amount" value="0.00" readonly="readonly" />
                                            </div>



                                            <label for="date" class="col-sm-1 title-text_n1  col-form-label change-color">চেঞ্জঃ</label>
                                            <div class="col-sm-2 bill-amount change-color">
                                                <input type="text" id="change" class="form-control box text-center bill-amount change-color" name="change" value="0.00" readonly="readonly" />
                                            </div>
                                        </div>
                                    </li>


                                    <!-- <div class=""  style="position:relative;">
                                              <span class="bill-property">Paid: </span>
                                            <input type="text" id="paidAmount" onkeyup="invoice_paidamount()" onkeypress="invoice_paidamount()" class="  total_amount_2" name="paid_amount" placeholder="0.00" tabindex="13" autocomplete="off" readonly/>
                                        </div> -->




                                    <!-- <li class="li_p">

                                            <div class="total_amount">Due : <span id="dueAmmount">0.00</span></div>

                                    </li>

                                    <li class="li_p">

                                            <div class="total_amount">Change : <span id="change">0.00</span></div>

                                    </li> -->

                                </ul>
                            </div>


                                        <div class="col-sm-5">

                                            <?php echo form_open('ordermanage/order/pos_invoice', 'class="navbar-search" method="get"') ?>

                                            <div class="box" style="margin-top: 15px">
                                                <input type="text" id="product_name" class="form-control search-field " dir="ltr" value="" name="s" placeholder="Search By Product" />

                                            </div>

                                            <?php if ($categorylist) { ?>
                                                <div class="main-carousel" data-flickity='{ "freeScroll": true }'>
                                                    <div class="carousel-cell" onclick="get_product('All')"> সকল পণ্য</div>

                                                    <?php foreach ($categorylist as $cates) { ?>

                                                        <div class="carousel-cell" onclick="get_product(<?= $cates->id ?>)"> <?= $cates->name_bn . "-". $cates->name ?></div>

                                                    <?php } ?>


                                                    <!--                                                     ক্যাটেগরিঃ-->

                                                </div>
                                            <?php } ?>

                                            <?php echo form_close() ?>
                                            <div class="product-grid scrollbar" id="style-3">

                                                <div class="row row-m-3 m-0" id="product_search">
                                                    <?php $i = 0;
                                                    foreach ($itemlist as $item) {

                                                    ?>
                                                        <div class="col-md-4 col-sm-2 col-lg-4  col-xs-12  ">
                                                            <div class="panel panel-bd product-panel select_product">
                                                                <div class="panel-body img-div">
                                                                    <img style="height: 150px;border-radius: 5px" src="<?php echo !empty($item->image) ? $item->image : 'assets/img/icons/default.jpg'; ?>" class="img-responsive pointer" onclick="onselectimage('<?php echo $item->product_id ?>')" alt="<?php echo html_escape($item->product_name); ?>">
                                                                </div>
                                                                <div class="panel-footer d-none" onclick="onselectimage('<?php echo $item->product_id ?>')"><?php
                                                                                                                                                            $text = html_escape($item->product_name);

                                                                                                                                                            $pieces = substr($text, 0, 11);
                                                                                                                                                            $ps = substr($pieces, 0, 10) . "...";
                                                                                                                                                            $cn = strlen($text);
                                                                                                                                                            if ($cn > 11) {
                                                                                                                                                                echo html_escape($ps);
                                                                                                                                                            } else {
                                                                                                                                                                echo html_escape($text);
                                                                                                                                                            }

                                                                                                                                                            ?></div>

                                                                <div class="panel-footer">
                                                                    <span><?= html_escape($item->product_name_bn); ?></span><br>
                                                                    <span><?= html_escape($item->product_name); ?></span><br>
                                                                    <?php if (isset($item->brand_name)) { ?>
                                                                        <span><?= '(' . $item->brand_name . ')'; ?></span>
                                                                    <?php } else { ?>
                                                                        <span>(No Brand)</span>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="stock_<?= $item->product_id ?>" name="" value="<?= $item->stock ?>">
                                                                    <div class="row d-none">

                                                                        <?php $today = date('Y-m-d');

                                                                        $date_now = new DateTime();
                                                                        $expired_date   = new DateTime($item->expired_date);

                                                                        //
                                                                        ?>
                                                                        <span class="product-unit">
                                                                            <nobr>Unit : <?= $item->unit ?></nobr>
                                                                        </span>
                                                                        <span class="product-expire text-danger">
                                                                            <nobr><?= ($date_now > $expired_date) ? 'Expired' : '' ?></nobr>
                                                                        </span>

                                                                    </div>
                                                                    <div class="row">
                                                                        <span class="product-price">
                                                                            <nobr>TK : <?= $item->price ?></nobr>
                                                                        </span>
                                                                        <span class="product-stock">
                                                                            <nobr>Stock: <?= (int) $item->stock ?></nobr>
                                                                        </span>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="col-sm-7">

                                            <div class="form-group row guicustomerpanel">
                                                <!-- <div class="col-sm-5">
                                                    <input type="text" name="product_name" class="form-control col-sm-2" placeholder='<?php echo display('barcode_qrcode_scan_here') ?>' id="add_item" autocomplete='off' tabindex="1" value="">
                                                </div>
                                                <label class="col-sm-2"><?php echo display('or'); ?></label> -->
                                                <div class="col-sm-5">

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!-- <?php echo form_open_multipart('Cinvoice/manual_sales_insert', array('class' => 'form-vertical', 'id' => 'pos_sale_insert', 'name' => 'insert_pos_invoice')) ?> -->
                                                <div class="col-sm-12 p-0 m-0">
                                                    <div class="col-sm-5 ">
                                                        <div class="form-group row ">


                                                            <input type="text" name="product_name_m" class="form-control col-sm-2 box" placeholder='Manual Input barcode' id="add_item_m" autocomplete='off' tabindex="1" value="">
                                                            <input type="hidden" id="product_value" name="">

                                                        </div>

                                                    </div>

                                                    <div class="col-sm-5 " style="margin-left: 5px">
                                                        <div class="form-group row ">

                                                            <?php if ($outlet_list) { ?>
                                                                <input type="text" name="" class="form-control col-sm-2 box" placeholder='' id="add_item_m" autocomplete='off' tabindex="1" value="<?= $outlet_list[0]['outlet_name'] ?>" readonly>
                                                                <input type="hidden" id="outlet_name" name="outlet_name" value="<?= $outlet_list[0]['outlet_id'] ?>">
                                                            <?php } else { ?>
                                                                <input type="text" name="" class="form-control col-sm-2 box" placeholder='' id="add_item_m" autocomplete='off' tabindex="1" value="<?= $cw[0]['central_warehouse'] ?>" readonly>
                                                                <input type="hidden" id="outlet_name" name="outlet_name" value="<?= $cw[0]['warehouse_id'] ?>">
                                                            <?php } ?>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <input type="text" size="100" name="customer_name" class="customerSelection form-control box" placeholder='<?php echo display('customer_name') . '/' . display('phone') ?> *' id="customer_name" value="Walking Customer" tabindex="3" onkeyup="customer_autocomplete()" required />
                                                        <input type="hidden" id="point_eligibility" value="<?php echo $points_eligibility ?>"  />
                                                        <input type="hidden" id="applied_point" value="" name="applied_point"  />
                                                        <input type="hidden" id="applied_amount" value="" name="applied_amount"  />

                                                        <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                        <input id="autocomplete_customer_id" class="customer_hidden_value" type="hidden" name="customer_id" value="1">
                                                        <!--                                                        --><?php
                                                                                                                        //                                                        if (empty($customer_name)) {
                                                                                                                        //                                                        
                                                                                                                        ?>
                                                        <!--                                                            <small id="emailHelp" class="text-danger">--><?php //echo display('please_add_walking_customer_for_default_customer') 
                                                                                                                                                                        ?><!--</small>-->
                                                        <!--                                                        --><?php
                                                                                                                        //                                                        }
                                                                                                                        //                                                        
                                                                                                                        ?>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <a href="#" class="client-add-btn btn btn-success" aria-hidden="true" data-toggle="modal" data-target="#cust_info"><i class="ti-plus m-r-2"></i></a>
                                                    </div>
                                                </div>

                                                <div class="table-responsive guiproductdata">
                                                    <table class="table table-bordered table-hover table-sm nowrap gui-products-table" id="addinvoice">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center product_field">পণ্যের বর্ণনা</th>
                                                                <th class="text-center">পরিমাণ </th>
                                                                <!--                                        <th class="text-center">Warranty Date</th>-->
                                                                <th class="text-center">দাম (ইউনিট)</th>
                                                                <th class="text-center invoice_fields">ডিসকাউন্ট</th>
                                                                <th class="text-center invoice_fields">মোট দাম
                                                                </th>
                                                                
                                                                <th class="text-center invoice_fields">একশন</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="addinvoiceItem">
                                                            <tr></tr>
                                                        </tbody>

                                                    </table>


                                                </div>

                                                <div class="row m-0">
                                                    <div class="col-sm-12 col-md-12 col-md-12 m-0 p-0 ">

                                                        <!-- <div class="col-sm-8 m-0 p-0" > -->
                                                        <div class="col-sm-8 p-0">
                                                            <div class="panel panel-bd lobidrag box">
                                                                <div class="form-group row ">
                                                                    <label for="outlet_name" class="col-sm-2 title-text_n  col-form-label m-2">পেমেন্ট</label>
                                                                    <input type="hidden" id="count" value="2">
                                                                </div>

                                                                <div class="panel-body" style="background: #71458f;">
                                                                    <div id="pay_div" style="margin: 10px 3px; padding:10px 0">
                                                                        <div class="col-sm-12 row_div">
                                                                            <div class="row margin-top10  m-0">

                                                                                <div class="col-sm-3 ">
                                                                                    <input class="form-control  box " value="মেথড" readonly>
                                                                                </div>
                                                                                <div class="col-sm-3 col-md-4 col-lg-4">

                                                                                    <div class="selectWrapper">
                                                                                        <select name="paytype[]" class="form-control selectBox   pay_type " required="" onchange="bank_paymet(this.value, 1)" tabindex="3">
                                                                                            <option value="1">Cash</option>
                                                                                            <option value="2">Cheque</option>
                                                                                            <option value="4">Bank</option>
                                                                                            <option value="3">Bkash</option>
                                                                                            <option value="5">Nagad</option>
                                                                                            <option value="7">Rocket</option>
                                                                                            <option value="6">Card</option>

                                                                                        </select>
                                                                                    </div>


                                                                                </div>

                                                                                <div class="col-sm-3 " id="ammnt_1">
                                                                                    <input class="form-control  box  p_amount" type="text" name="p_amount[]" id='cash_1' placeholder="পরিমাণ" onchange="calc_paid()" onkeyup="calc_paid()">
                                                                                </div>
                                                                                <!-- <div class="col-sm-3 " id="ammnt_1">
                                                                                    <input class="form-control  box  p_amount" type="text" name="p_amount[]" id='cash_2' placeholder="পরিমাণ" onchange="calc_paid()" onkeyup="calc_paid()">
                                                                                </div> -->
                                                                                <div class="col-sm-1">
                                                                                    <a id="add_pt_btn" onclick="add_pay_row(1)" class=" client-add-btn btn btn-success"><i class="fa fa-plus"></i></a>
                                                                                </div>

                                                                            </div>

                                                                        </div>
                                                                        <div class="col-sm-12 row_div" id="bank_div_m_1" style="display: none">
                                                                            <div class="row margin-top10  m-0">


                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group row ">
                                                                                        <div class="col-sm-3 ">
                                                                                            <input class="form-control  box " value="Bank Name" readonly>
                                                                                        </div>
                                                                                        <div class="col-sm-7 ">
                                                                                            <div class="selectWrapper">
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
                                                                                </div>


                                                                            </div>

                                                                        </div>
                                                                        <div class="col-sm-12 row_div" id="bank_div_1" style="display: none">
                                                                            <div class="row margin-top10  m-0">


                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group row ">
                                                                                        <div class="col-sm-3 ">
                                                                                            <input class="form-control  box " value="Bank Name" readonly>
                                                                                        </div>
                                                                                        <div class="col-sm-7">

                                                                                            <input type="text" name="bank_id" class="form-control box" id="bank_id_1" placeholder="Bank">

                                                                                        </div>

                                                                                        <div class="col-sm-1">
                                                                                            <a href="#" class="client-add-btn btn btn-sm btn-info" aria-hidden="true" data-toggle="modal" data-target="#cheque_info"><i class="fa fa-file m-r-2"></i></a>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>


                                                                            </div>

                                                                        </div>
                                                                        <div class="col-sm-12 row_div" id="bkash_div_1" style="display: none">
                                                                            <div class="row margin-top10  m-0">


                                                                                <div class="col-sm-12">

                                                                                    <div class="form-group row  ">
                                                                                        <div class="col-sm-3 ">
                                                                                            <input class="form-control  box " value="Number" readonly>
                                                                                        </div>
                                                                                        <div class="col-sm-7">
                                                                                            <div class="selectWrapper">
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
                                                                                </div>


                                                                            </div>

                                                                        </div>
                                                                        <div class="col-sm-12 row_div" id="nagad_div_1" style="display: none">
                                                                            <div class="row margin-top10  m-0">


                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group row">
                                                                                        <div class="col-sm-3 ">
                                                                                            <input class="form-control  box " value="Number" readonly>
                                                                                        </div>
                                                                                        <div class="col-sm-7">
                                                                                            <div class="selectWrapper">
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
                                                                                </div>


                                                                            </div>

                                                                        </div>
                                                                        <div class="col-sm-12 row_div" id="rocket_div_1" style="display: none;">
                                                                            <div class="row margin-top10  m-0">


                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group row">
                                                                                        <div class="col-sm-3 ">
                                                                                            <input class="form-control  box " value="Number" readonly>
                                                                                        </div>
                                                                                        <div class="col-sm-7">
                                                                                            <div class="selectWrapper">
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
                                                                                </div>


                                                                            </div>

                                                                        </div>
                                                                        <div class="col-sm-12 row_div" id="card_div_1" style="display: none;">
                                                                            <div class="row margin-top10  m-0">


                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group row">
                                                                                        <div class="col-sm-3 ">
                                                                                            <input class="form-control  box " value="Bank Name" readonly>
                                                                                        </div>
                                                                                        <div class="col-sm-7">
                                                                                            <div class="selectWrapper">
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


                                                                                </div>


                                                                            </div>

                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 m-0 p-0  panel-background bill-breakdown">


                                                            <div class="form-group row">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="মোট" readonly>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="sub_total" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" class="form-control box text-right" name="sub_total" value="" placeholder="0.00" readonly />
                                                                   
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                           
                                                            <div style="display: <?php echo ($sale_discount_percent == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="ডিসকাউন্ট" readonly>
                                                                    </div>

                                                                    <div class="col-sm-6">
                                                                        <input type="text" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" value="0.00" id="invoice_discount" class="form-control box text-right total_discount" name="invoice_discount" placeholder="0.00" />
                                                                        <input type="hidden" id="txfieldnum" value="{taxnumber}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                           
                                                       

                                                            <div style="display: <?php echo ($sale_discount_percent == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value=" ডিসকাউন্ট (%)" readonly>
                                                                    </div>

                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="perc_discount" onkeyup="quantity_calculate(1);" value="0.00" onchange="quantity_calculate(1);" class="form-control box text-right" name="perc_discount" value="" placeholder="0.00" />


                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div style="display: <?php echo ($crm == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6" style="float: left;">
                                                                        <input class="form-control box bill-item" style="font-size: 15px;" value="পয়েন্ট ডিসকাউন্ট" readonly>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="total_points_discount_ammount" class="form-control box text-right" name="total_points_discount_ammount" value="0.00" readonly="readonly" />


                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="display: <?php echo ($sale_discount == "disable" || $sale_discount_percent == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="মোট ডিসকাউন্ট" readonly>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="total_discount_ammount" class="form-control box text-right" name="total_discount" value="0.00" readonly="readonly" />


                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" id="membership_id" value="">
                                                                <input type="hidden" id="total_vat_permission" value="<?php echo $vat ?>">
                                                            <div style="display: <?php echo ($vat == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="মোট ভ্যাট" readonly>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="total_vat" class="form-control box text-right total_vat" name="total_vat" value="0.00" readonly="readonly" />


                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                     
                                                                <input type="hidden" id="total_tax_permission" value="<?php echo $tax ?>">
                                                            <div style="display: <?php echo ($tax == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="মোট ট্যাক্স" readonly>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="total_tax" class="form-control box text-right total_tax" name="total_tax" value="0.00" readonly="readonly" />


                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div style="display: <?php echo ($delivery_charge == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="ডেলিভারি চার্জ" readonly>
                                                                    </div>

                                                                    <div class="col-sm-6">


                                                                        <input type="text" id="shipping_cost" class="form-control box text-right" name="shipping_cost" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" />

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div style="display: <?php echo ($service_charge == "disable") ? 'none' : '' ?>;" class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="সার্ভিস চার্জ" readonly>
                                                                    </div>

                                                                    <div class="col-sm-6">


                                                                        <input type="text" id="service_charge" class="form-control box text-right" name="service_charge" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" />

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="মোট টাকা" readonly>
                                                                    </div>

                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="grandTotal" class="form-control box text-right" name="grand_total_price" value="0.00" readonly="readonly" />

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- <div class="form-group row ">
                                                                <div class="col-sm-12  ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="পূর্ববর্তী বকেয়া" readonly>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="previous" class="form-control box text-right" name="previous" value="0.00" readonly="readonly" />


                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <div class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item prev-due" value="পূর্ববর্তী বকেয়া" readonly>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" id="previous" class="form-control box text-right prev-due-amount" name="previous" value="0.00" readonly="readonly" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row  ">
                                                                <div class="col-sm-12 ">
                                                                    <div class="col-sm-6 ">
                                                                        <input class="form-control  box bill-item" value="রাউন্ডিং" readonly>
                                                                    </div>

                                                                    <div class="col-sm-6">

                                                                        <input type="text" id="rounding" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" class="form-control box text-right" name="rounding" value="" placeholder="0.00" readonly />

                                                                    </div>
                                                                </div>
                                                            </div>



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

                                                                    <div class="panel-body panel-background">
                                                                        <div class="addCheque">
                                                                            <div id="cheque" class="cheque">
                                                                                <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                                                                <label for="bank" class="col-sm-4 col-form-label">Cheque type:
                                                                                </label>
                                                                                <div class="col-sm-6">
                                                                                    <input type="text" name="cheque_type[]" class=" form-control" placeholder="" autocomplete="off" />
                                                                                    <!--                                                <input type="number"   name="cheque_id[]" class=" form-control" placeholder="" value="--><?php //echo rand()
                                                                                                                                                                                                                                    ?>
                                                                                    <!--" autocomplete="off"/>-->
                                                                                </div>

                                                                                <label for="bank" class="col-sm-4 col-form-label">Cheque NO:
                                                                                </label>
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" name="cheque_no[]" class=" form-control" placeholder="" autocomplete="off" />
                                                                                    <!--                                                <input type="number"   name="cheque_id[]" class=" form-control" placeholder="" value="--><?php //echo rand()
                                                                                                                                                                                                                                    ?>
                                                                                    <!--" autocomplete="off"/>-->
                                                                                </div>


                                                                                <label for="date" class="col-sm-4 col-form-label">Due Date </label>
                                                                                <div class="col-sm-6">

                                                                                    <input class="form-control" type="date" size="50" name="cheque_date[]" id="" value="" tabindex="4" autocomplete="off" placeholder="mm/dd/yyyy" />
                                                                                </div>

                                                                                <label for="bank" class="col-sm-4 col-form-label">Amount:
                                                                                </label>

                                                                                <div class="col-sm-6">
                                                                                    <input type="number" name="amount[]" class=" form-control" placeholder="" autocomplete="off" />
                                                                                    <!--                                                <input type="number"   name="cheque_id[]" class=" form-control" placeholder="" value="--><?php //echo rand()
                                                                                                                                                                                                                                    ?>
                                                                                    <!--" autocomplete="off"/>-->
                                                                                </div>

                                                                                <label for="bank" class="col-sm-4 col-form-label">Image:
                                                                                </label>

                                                                                <div class="col-sm-6" style="padding-bottom:10px ">
                                                                                    <input type="file" name="image" class="form-control" id="image" tabindex="4">
                                                                                    <!--                                                <input type="number"   name="cheque_id[]" class=" form-control" placeholder="" value="--><?php //echo rand()
                                                                                                                                                                                                                                    ?>
                                                                                    <!--" autocomplete="off"/>-->
                                                                                </div>




                                                                                <div class=" col-sm-1">
                                                                                    <a href="#" id="Add_cheque" class="client-add-btn btn btn-primary add_cheque"><i class="fa fa-plus-circle m-r-2"></i></a>
                                                                                </div>


                                                                            </div>
                                                                        </div>

                                                                        <!---->

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


                                            <input type="hidden" id="sel_type" name="sel_type" value="2">


                                        </div>
                                        <div class="fixedclasspos">
                                            <!-- <div class="paymentpart text-center"><span class="btn btn-warning "><i class="fa fa-angle-double-down"></i></span></div> -->
                                            <div class="bottomarea">
                                                <div class="row">

                                            
                                                    <div class="col-sm-10">
                                                    <input type="hidden" id="crm" class="form-control box text-right guifooterfixedinput" value="<?php echo $crm ?>" name="crm" placeholder="0.00" tabindex="13" autocomplete="off" readonly/>
                                                    <?php if($crm == "enable") { ?>
                                                        <!-- <div class="col-sm-12"> -->
                                    
                                                            <label for="date" class="col-sm-1 title-text_n  col-form-label">Total Points</label>
                                                            <div class="col-sm-1"> <input type="text" id="total_points" class="form-control box text-right guifooterfixedinput" placeholder="0.00" tabindex="13" autocomplete="off" readonly/></div>

                                                            <label for="date" class="col-sm-2 title-text_n  col-form-label">Available Points</label>
                                                            <div class="col-sm-1"> <input type="hidden" id="available_points" class="form-control box text-right guifooterfixedinput" name="available_points" placeholder="0.00" tabindex="13" autocomplete="off" readonly/>
                                                            <input type="text" id="max_available_points" class="form-control box text-right guifooterfixedinput" name="max_available_points" placeholder="0.00" tabindex="13" autocomplete="off" readonly/></div>
                                                            <label for="date" class="col-sm-2 title-text_n  col-form-label">Max Discounted Amount</label>
                                                            <div class="col-sm-1"> <input type="hidden" id="discounted_points_amount" class="form-control box text-right guifooterfixedinput" placeholder="0.00" tabindex="13" autocomplete="off" readonly/>
                                                            <input type="text" id="max_discounted_points_amount" class="form-control box text-right guifooterfixedinput" placeholder="0.00" tabindex="13" autocomplete="off" readonly/></div>

                                                            <label for="date" class="col-sm-2 title-text_n  col-form-label">Max Points Burn</label>
                                                            <div class="col-sm-1"> <input type="hidden" id="points_burn" class="form-control box text-right guifooterfixedinput" name="points_burn" placeholder="0.00" tabindex="13" autocomplete="off" readonly/>
                                                            <input type="text" id="max_points_burn" class="form-control box text-right guifooterfixedinput" name="max_points_burn" placeholder="0.00" tabindex="13" autocomplete="off" readonly/></div>
                                                            <div class="col-sm-1">
                                                                <a href="#" class="client-add-btn btn btn-success" aria-hidden="true" data-toggle="modal" data-target="#apply_points">Apply Points</a>
                                                            </div>
                                                        <!-- </div> -->
                                                        <?php } ?>
                                                    </div>
                                                   

                                                    <div class="col-sm-2">
                                                        <div class="col-sm-12">
                                                        
                                                            <!-- <a href="#" class="btn btn-info btn-lg" data-toggle="modal" data-target="#calculator"><i class="fa fa-calculator" aria-hidden="true"></i> </a> -->
                                                            <!--<input type="button" id="full_paid_tab" class="btn btn-warning btn-lg" value="সম্পূর্ণ পরিশোধ" tabindex="14" onClick="full_paid()" />-->
                                                            <input type="submit" id="add_invoice" class="btn btn-success btn-lg" name="add_invoice" value="প্রিন্ট বিল">

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- <?php echo form_close() ?> -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="saleList">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="table-responsive padding10" id="invoic_list">
                                            <table id="" class="table table-bordered  table-hover datatable ">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center product_field"><?php echo display('item_information') ?> </th>

                                                        <th class="text-center"><?php echo display('available_qnty') ?></th>
                                                        <th class="text-center"><?php echo display('unit') ?></th>
                                                        <th class="text-center"><?php echo display('quantity') ?> </th>
                                                        <th class="text-center">Warrenty Date</th>
                                                        <th class="text-center"><?php echo display('rate') ?> </th>

                                                        <?php if ($discount_type == 1) { ?>
                                                            <th class="text-center invoice_fields"><?php echo display('discount_percentage') ?> %</th>
                                                        <?php } elseif ($discount_type == 2) { ?>
                                                            <th class="text-center invoice_fields"><?php echo display('discount') ?> </th>
                                                        <?php } elseif ($discount_type == 3) { ?>
                                                            <th class="text-center invoice_fields"><?php echo display('fixed_dis') ?> </th>
                                                        <?php } ?>

                                                        <th class="text-center invoice_fields"><?php echo display('total') ?>
                                                        </th>
                                                        <th class="text-center invoice_fields"><?php echo display('action') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $total = '0.00';
                                                    $sl = 1;
                                                    if ($todays_invoice) {
                                                        foreach ($todays_invoice as $invoices_list) {
                                                    ?>

                                                            <tr>
                                                                <td><?php echo $sl; ?></td>
                                                                <td>
                                                                    <a href="<?php echo base_url() . 'Cinvoice/invoice_inserted_data/' . $invoices_list['invoice_id']; ?>">

                                                                        <?php echo html_escape($invoices_list['invoice']); ?>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo base_url() . 'Cinvoice/invoice_inserted_data/' . $invoices_list['invoice_id']; ?>">
                                                                        <?php echo $invoices_list['invoice_id'] ?>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo base_url() . 'Ccustomer/customerledger/' . $invoices_list['invoice_id']; ?>"><?php echo html_escape($invoices_list['customer_name']) ?></a>
                                                                </td>

                                                                <td><?php echo $invoices_list['date'] ?></td>
                                                                <td class="text-right"><?php
                                                                                        if ($position == 0) {
                                                                                            echo $currency . $invoices_list['total_amount'];
                                                                                        } else {
                                                                                            echo $invoices_list['total_amount'] . $currency;
                                                                                        }
                                                                                        $total += $invoices_list['total_amount']; ?></td>
                                                                <td>
                                                                    <center>
                                                                        <?php echo form_open() ?>

                                                                        <a href="<?php echo base_url() . 'Cinvoice/invoice_inserted_data/' . $invoices_list['invoice_id']; ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('invoice') ?>"><i class="fa fa-window-restore" aria-hidden="true"></i></a>
                                                                        <a href="<?php echo base_url() . 'Cinvoice/min_invoice_inserted_data/' . $invoices_list['invoice_id']; ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo 'Mini Invoice' ?>"><i class="fa fa-fax" aria-hidden="true"></i></a>

                                                                        <a href="<?php echo base_url() . 'Cinvoice/pos_invoice_inserted_data/' . $invoices_list['invoice_id']; ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('pos_invoice') ?>"><i class="fa fa-fax" aria-hidden="true"></i></a>
                                                                        <?php if ($this->permission1->method('manage_invoice', 'update')->access()) { ?>

                                                                            <a href="<?php echo base_url() . 'Cinvoice/invoice_update_form/' . $invoices_list['invoice_id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                        <?php } ?>
                                                                        <?php if ($this->permission1->method('manage_invoice', 'delete')->access()) { ?>
                                                                            <a href="" class="deleteInvoice btn btn-danger btn-sm" name="<?php echo $invoices_list['invoice_id'] ?>" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                                        <?php } ?>
                                                                        <?php echo form_close() ?>
                                                                    </center>
                                                                </td>
                                                            </tr>

                                                    <?php
                                                            $sl++;
                                                        }
                                                    }
                                                    ?>
                                                </tbody>

                                            </table>

                                        </div>

                                    </div>
                                </div>

                            </div>


                        </div>





                    </div>

                </div>
            </div>
            <?php echo form_close() ?>
        </div>




        <div class="modal fade modal-success" id="cust_info" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <h3 class="modal-title"><?php echo display('add_new_customer') ?></h3>
                    </div>

                    <div class="modal-body">
                        <div id="customeMessage" class="alert hide"></div>
                        <?php echo form_open('Cinvoice/instant_customer', array('class' => 'form-vertical', 'id' => 'newcustomer')) ?>
                        <div class="panel-body">
                            <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="form-group row">
                                <label for="customer_id_two" class="col-sm-4 col-form-label">Customer ID <sapn class="text-danger">*</sapn></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="customer_id_two" id="" type="text" placeholder="Customer ID" tabindex="1">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('customer_name') ?> <sapn class="text-danger">*</sapn></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="customer_name" id="" type="text" placeholder="<?php echo display('customer_name') ?>" required="" tabindex="1">
                                </div>
                            </div>
                            <?php if(!($outlet_list)){ ?>
                                <div class="form-group row">
                                    <label for="outlet_id" class="col-sm-4 col-form-label">Outlet Name<i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <select name="outlet_id" class="form-control" required="" tabindex="3">
                                            <?php foreach ($cw_list as $cw) { ?>
                                                <option value="<?php echo html_escape($cw['warehouse_id']) ?>" <?php echo html_escape($cw['warehouse_id']) == $outlet_id ? 'disabled' : '' ?>><?php echo html_escape($cw['central_warehouse']); ?></option>
                                            <?php } ?>
                                            <?php foreach ($outlet_list_to as $outlet) { ?>
                                                <option value="<?php echo html_escape($outlet['outlet_id']) ?>" <?php echo html_escape($outlet['outlet_id']) == $outlet_id ? 'disabled' : '' ?>><?php echo html_escape($outlet['outlet_name']); ?></option>
                                            <?php } ?>


                                        </select>

                                    </div>

                                </div>
                                <?php } ?>

                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('customer_name') ?>(In Bangla) <sapn class="text-danger">*</sapn></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="customer_name_bn" id="customer_name_bn" type="text" placeholder="<?php echo display('customer_name') ?>" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card" class="col-sm-4 col-form-label">Membership Type</label>
                                <div class="col-sm-6">

                                    <select name="membership_id" class="form-control" tabindex="3">
                                    <option value="">Select One</option>
                                        <?php foreach ($card_list as $card) { ?>
                                            <option value="<?php echo html_escape($card['id']) ?>"><?php echo $card['name'] ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card_number" class="col-sm-4 col-form-label"><?php echo "Card Number" ?></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="card_number" id="card_number" type="text" placeholder="<?php echo "Card Number" ?>" tabindex="1">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label"><?php echo display('customer_email') ?></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="email" id="email" type="email" placeholder="<?php echo display('customer_email') ?>" tabindex="1">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="mobile" class="col-sm-4 col-form-label"><?php echo display('customer_mobile') ?><sapn class="text-danger">*</sapn></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="mobile" id="mobile" type="number" placeholder="<?php echo display('customer_mobile') ?>" min="0" tabindex="1" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="customer_id_two" class="col-sm-4 col-form-label">Contact Person</label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="contact_person" id="" type="text" placeholder="Contact Person" tabindex="1">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="customer_id_two" class="col-sm-4 col-form-label">Contact Mobile</label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="contact" id="" type="number" placeholder="Contact Mobile" tabindex="1">
                                    <input class="form-control" name="cus_type" id="" type="hidden" placeholder="Contact Mobile" value="2" tabindex="1">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address " class="col-sm-4 col-form-label"><?php echo display('customer_address') ?></label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="address" id="address " rows="3" placeholder="<?php echo display('customer_address') ?>" tabindex="1"></textarea>
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
        </div><!-- /.modal -->
        <div class="modal fade modal-success" id="apply_points" role="dialog">
            <div class="modal-dialog" role="document">
            <form id="myForm">
                <div class="modal-content">
                    <div class="modal-header">

                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <h3 class="modal-title"><?php echo "Apply Your Points" ?></h3>
                    </div>

                    <div class="modal-body">
                        <div id="customeMessage" class="alert hide"></div>
                        <div class="panel-body">
                            <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="form-group row">
                                <label for="customer_burning_points" class="col-sm-4 col-form-label">Enter Points <sapn class="text-danger">*</sapn></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="customer_burning_points" id="customer_burning_points" type="text" placeholder="Enter Points" tabindex="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                        <input type="submit" id="apply_points" class="btn btn-success" value="Apply">
                    </div>
                   
                </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div>


        <div id="detailsmodal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <strong>
                            <center> <?php echo display('product_details') ?></center>
                        </strong>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="panel panel-bd">

                                    <div class="panel-body">
                                        <span id="modalimg"></span><br>
                                        <h4><?php echo display('product_name') ?> :<span id="modal_productname"></span></h4>
                                        <h4><?php echo display('product_model') ?> :<span id="modal_productmodel"></span></h4>
                                        <h4><?php echo display('price') ?> :<span id="modal_productprice"></span></h4>
                                        <h4><?php echo display('unit') ?> :<span id="modal_productunit"></span></h4>
                                        <h4><?php echo display('stock') ?> :<span id="modal_productstock"></span></h4>



                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">

                </div>

            </div>

        </div>


        <!-- <div class="modal fade" id="printconfirmodal" tabindex="-1" role="dialog" aria-labelledby="printconfirmodal" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="" class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                        <h4 class="modal-title" id="myModalLabel"><?php echo display('print') ?></h4>
                    </div>
                    <div class="modal-body">
                        <?php echo form_open('Cinvoice/pos_invoice_inserted_data_manual', array('class' => 'form-vertical', 'id' => '', 'name' => '')) ?>
                        <div id="outputs" class="hide alert alert-danger"></div>
                        <h3> <?php echo display('successfully_inserted') ?> </h3>
                        <h4><?php echo display('do_you_want_to_print') ?> ??</h4>
                        <input type="hidden" name="invoice_id" id="inv_id">
                        <input type="hidden" name="url" value="<?php echo base_url('Cinvoice/gui_pos'); ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="cancelprint()" class="btn btn-default" data-dismiss="modal"><?php echo display('no') ?></button>
                        <button type="submit" class="btn btn-primary" id="yes"><?php echo display('yes') ?></button>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div> -->
    </section>
</div>

<!-- =========================  ajax form submit and print preview =================== -->

<script type="text/javascript">
    const form = document.getElementById('myForm');

// Attach an event listener to the form's submit event
form.addEventListener('submit', function (event) {
  event.preventDefault();
//   customer_burning_points
  let discounted_points_amount = $("#discounted_points_amount").val();
  let points_burn = $("#points_burn").val();
  let max_points_burn = $("#max_points_burn").val();
  let available_points = $("#available_points").val();
  let max_available_points = $("#max_available_points").val()
  let points = $("#customer_burning_points").val();
  let point_eligibility =  $("#point_eligibility").val();
  if(parseFloat(available_points) < parseFloat(point_eligibility))
  {
    alert("You dont have burn eligibility");
  }
  else if(points == '')
  {
       alert("Enter Points");
  }
  else if(parseFloat(available_points) >= parseFloat(point_eligibility))
  {
    if(parseFloat(points) > parseFloat(max_points_burn)){
        $("#applied_point").val(0);
        $("#applied_amount").val(0);
        $("#customer_burning_points").val(0);
        // $('#apply_points').modal('hide');
        alert("You dont enough points to burn !");

    }
    else{
        // alert("jj")
        $("#max_available_points").val(available_points - points)
        $("#applied_point").val(points);
        $("#total_points_discount_ammount").val(parseFloat((discounted_points_amount * points)/points_burn).toFixed(2));
        $("#applied_amount").val(parseFloat((discounted_points_amount * points)/points_burn).toFixed(2));
        
        var total_discount_ammount = parseFloat($("#total_discount_ammount").val());
        // Calculate the result
        var result = total_discount_ammount + ((discounted_points_amount * points) / points_burn);
        result = parseFloat(result.toFixed(2));
         $("#total_discount_ammount").val(result);
        let order_total = $("#n_total").val();
        let grandTotal = $("#grandTotal").val();
        $("#n_total").val(order_total - (discounted_points_amount * points)/points_burn)
        $("#grandTotal").val(grandTotal - (discounted_points_amount * points)/points_burn)
        invoice_paidamount();
        $('#apply_points').modal('hide');
    }
   
  }

});
    // $(document).ready(function() {
    //     var csrf_test_name = $('[name="csrf_test_name"]').val();
    //     var base_url = $("#base_url").val();
    //     $.ajax({
    //         url: base_url + 'Cinvoice/previous',
    //         type: 'post',
    //         data: {
    //             customer_id: 1,
    //             csrf_test_name: csrf_test_name
    //         },
    //         success: function(msg) {

    //             //console.log(msg)

    //             $("#previous").val(msg);
    //             // $("#previous").val(0);
    //         },
    //         error: function(xhr, desc, err) {
    //             alert('failed');
    //         }
    //     });
    // });
    $('.main-carousel').flickity({
        // options
        cellAlign: 'left',
        contain: true
    });
    
    $(document).ready(function() {
        $('#pos_sale_insert').one('submit', function() {
            $("#add_invoice").prop('disabled', true);
        });
    });
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('barcode'); ?></h1>
            <small><?php echo display('barcode'); ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('product'); ?></a></li>
                <li class="active"><?php echo display('barcode'); ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->

    <section class="content">
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
        <!-- Product Barcode and QR code -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-body">
                        <div class="table-responsive">

                            <div id="printableArea" onload="printDiv('printableArea')">

                                <table id="" style="page-break-inside:auto;" class="table-bordered">
                                    <?php

                                    $cqty = 8;
                                    $counter = 0;
                                    foreach ($barcode_details as $bar) {  ?>
                                        <!-- <table id="" style="border-collapse: collapse;" class="table-bordered"> -->
                                        <?php
                                        // $qty = ($bar->quantity ? $bar->quantity : 8);
                                        // $cqty = 8;
                                        // $counter = 0;
                                        for ($i = 0; $i < $bar->quantity; $i++) {
                                        ?>

                                            <?php if ($counter == $cqty) { ?>
                                                <tr>
                                                    <?php $counter = 0; ?>
                                                <?php } ?>

                                                <td class="barcode-toptd" style="">

                                                    <div class="barcode-inner barcode-innerdiv">
                                                        <div class="product-name barcode-productname" style="text-align:center;">
                                                            <nobr><?= $company_name ?></nobr>

                                                        </div>

                                                        <!-- <img src="<?= $bar->barcode_url ?>" class="img-responsive center-block barcode-image" alt=""> -->

                                                        <img src="<?php echo base_url('Cbarcode/barcode_generator/' . $bar->product_id) ?>" class="img-responsive center-block barcode-image" alt="">

                                                        <div class="product-name-details barcode-productdetails" style="text-align:center;"><?= $bar->product_name ?></div>

                                                        <div class="price barcode-price" style="text-align:center;"><b><?php echo (($position == 0) ? "$currency $bar->price" : "$bar->price $currency") ?></b>

                                                        </div>
                                                    </div>

                                                </td>
                                                <?php if ($counter == 7) { ?>
                                                </tr>

                                            <?php } ?>
                                            <?php $counter++; ?>
                                        <?php
                                        }
                                        ?>
                                    <?php } ?>

                                </table>
                            </div>

                            <div class="panel-footer text-left">
                                <input type="hidden" name="" id="url" value="<?php echo base_url('Cproduct/barcode_print'); ?>">
                                <a class="btn btn-danger" href="<?php echo base_url('Cproduct/barcode_print'); ?>"><?php echo display('cancel') ?></a>
                                <!-- <button class="btn btn-info" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button> -->

                                <a class="btn btn-info" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                            </div>

                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div> <!-- /.content-wrapper -->
<script type="text/javascript">
    ("use strict");

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;

        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        document.body.style.marginTop = "0px";
        setTimeout(function() {
            // window.print();

            w = window.open();

            w.document.write(printContents);

            w.print();
            w.close();
            document.body.innerHTML = originalContents;
        }, 1000);

    }
</script>
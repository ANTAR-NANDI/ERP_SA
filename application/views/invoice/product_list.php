<link href="<?php echo base_url('assets/css/gui_pos.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/flickity.min.css') ?>" rel="stylesheet" type="text/css" />


<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php"></script>
<!-- Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/pos_invoice.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>my-assets/js/admin_js/guibarcode.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/js/flickity.pkgd.min.js" type="text/javascript"></script>

<div class="row row-m-3 m-0" id="product_search">
    <?php $i = 0;
    foreach ($itemlist as $item) {

        ?>
        <div class="col-md-4 col-sm-2 col-lg-4  col-xs-12  ">
            <div class="panel panel-bd product-panel select_product">
                <div class="panel-body img-div" >
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
                    <span><?= html_escape($item->product_name_bn);?></span><br>
                    <?php if (isset($item->brand_name )){?>
                        <span><?= '('.$item->brand_name .')';?></span>
                    <?php }else{ ?>
                        <span>(No Brand)</span>
                    <?php } ?>
                    <input type="hidden" id="stock_<?= $item->product_id?>" name="" value="<?= $item->stock ?>">
                    <div class="row d-none">

                        <?php $today=date('Y-m-d');

                        $date_now = new DateTime();
                        $expired_date   = new DateTime( $item->expired_date );

                        //
                        ?>
                        <span class="product-unit"> <nobr>Unit : <?=$item->unit ?></nobr></span>
                        <span class="product-expire text-danger"><nobr><?= ( $date_now > $expired_date )? 'Expired':''?></nobr></span>

                    </div>
                    <div class="row">
                        <span class="product-price"> <nobr>TK : <?=$item->price ?></nobr></span>
                        <span class="product-stock"><nobr>Stock:  <?= (int) $item->stock ?></nobr></span>

                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
</div>

<!-- =========================  ajax form submit and print preview =================== -->


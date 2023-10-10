<script src="<?php echo base_url() ?>my-assets/js/admin_js/invoice.js.php" type="text/javascript"></script>
<style>
    .submit-section {
        text-align: left !important;
        margin-top: 10px !important;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        border: 0;
        border-bottom: 1px solid #ccc;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: 0;
        border-bottom: 1px solid #333;
    }
</style>
<!-- Edit Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Invoice Setting</h1>
            <small>Invoice Setting View</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active">Invoice Setting View</li>
            </ol>
        </div>
    </section>

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
        <!-- Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Invoice Setting View</h4>
                        </div>
                    </div>
                    <input type="hidden" id="base_url" value="<?= base_url() ?>">
                    <!-- <form action="<?php echo base_url(); ?>Cinvoice/setting" method="post" id="SystemOption" name="SystemOption" class="form-horizontal"> -->
                    <?php echo form_open_multipart('Cinvoice/setting', array('class' => 'form-horizontal', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                    <div class="form-body">
                        <?php
                        // print_r($SettingData);
                        // exit();
                        if ($this->session->flashdata('message')) { ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('message'); ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <?php
                            $sl = 1;
                            foreach ($SettingData as $key => $value) {
                            ?>
                                <input type="hidden" class="sl" value="<?php echo $sl; ?>">
                                <div class="form-group col-md-6">
                                    <label class="control-label col-md-4"><?php echo $value->OptionName; ?></label>
                                    <?php if ($value->type == "dropdown") { ?>
                                        <?php if ($value->OptionSlug == "setting_paper_format") { ?>
                                            <div class="col-md-3">
                                                <select name="<?php echo $value->OptionSlug; ?>" class="form-control col-md-3" id="status" required>
                                                    <!-- <?php if ($value->status == "A4") { ?>
                                                        <option class="col-md-3" value="A5" selected>A5</option>
                                                        <option class="col-md-3" value="A4">A4</option>
                                                    <?php } else if (($value->status == "A5")) { ?>
                                                        <option class="col-md-3" value="A5" selected>A5</option>
                                                        <option class="col-md-3" value="A4">A4</option>
                                                    <?php } else { ?>
                                                        <option class="col-md-3" value="A5">A5</option>
                                                        <option class="col-md-3" value="A4">A4</option>
                                                    <?php }
                                                    ?> -->
                                                    <option class="col-md-3" value="" selected disabled>Choose Paper Size</option>
                                                    <option class="col-md-3" value="A5" <?= $value->status == "A5" ? 'selected' : '' ?>>A5</option>
                                                    <option class="col-md-3" value="A4" <?= $value->status == "A4" ? 'selected' : '' ?>>A4</option>
                                                    <option class="col-md-3" value="55" <?= $value->status == "55" ? 'selected' : '' ?>>55 MM</option>
                                                    <option class="col-md-3" value="80" <?= $value->status == "80" ? 'selected' : '' ?>>80 MM</option>
                                                </select>
                                            </div>
                                        <?php } ?>
                                        <?php if ($value->OptionSlug == "language") { ?>
                                            <div class="col-md-3">
                                                <select name="<?php echo $value->OptionSlug; ?>" class="form-control col-md-3" id="status" required>
                                                    <option class="col-md-3" value="" selected disabled>Choose Language</option>
                                                    <?php foreach ($language as $lang) { ?>
                                                        <option class="col-md-3" value="<?= strtolower($lang) ?>" <?= strtolower($lang) == $value->status ? 'selected' : '' ?>><?php echo $lang ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    <?php } else if ($value->type == "checkbox") { ?>
                                        <!-- <div class="col-md-2"> -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="<?php echo $value->OptionSlug; ?>" id="<?php echo $value->OptionName . 'inlineRadio1'; ?>" value="1" <?php echo $value->status == '1' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="<?php echo $value->OptionName . 'inlineRadio1'; ?>">Enable</label>

                                            <input class="form-check-input" type="radio" name="<?php echo $value->OptionSlug; ?>" id="<?php echo $value->OptionName . 'inlineRadio2'; ?>" value="0" <?php echo $value->status == '0' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="<?php echo $value->OptionName . 'inlineRadio2'; ?>">Disable</label>
                                            <!-- </div> -->

                                            <!-- <input type="checkbox" class="check_addons" name="status[]" value="<?php echo (isset($value->status) && $value->status == "1")  ? '1' : '0' ?>" id="myCheckbox_<?php echo $value->id; ?>" <?php echo (isset($value->status) && $value->status == "1")  ? 'checked' : '' ?> /> -->

                                        </div>
                                    <?php } ?>

                                </div>

                            <?php
                                $sl++;
                            } ?>
                        </div>
                        <hr>
                        <input type="submit" name="InvoiceSetting" id="InvoiceSetting" class="btn btn-info" value="Submit">
                        <br>
                        <!-- </form> -->
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
    </section>
</div>
<script>
    $(".check_addons:checkbox").change(function() {
        var ischecked = $(this).is(':checked');
        alert(ischecked);
        if (ischecked == true) {
            $(".check_addons").val(1);
        } else {
            $(".check_addons").val(0);
        }

    });
</script>
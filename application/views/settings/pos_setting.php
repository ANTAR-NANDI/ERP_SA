<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('pos_setting') ?></h1>
            <small><?php echo display('pos_setting') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('pos_setting') ?></li>
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

        <!-- New customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('pos_setting') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Csettings/pos_setting_update', array('class' => 'form-vertical', 'id' => 'insert_customer')) ?>
                    <div class="panel-body">

                        <?php $half_list = ceil($length_of_pos_setting_list / 2); ?>

                        <div class="row">
                            <div class="col-sm-6 table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Feature</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < $half_list; $i++) { ?>
                                            <tr>
                                                <td style="width: 70%;"><?php echo display($pos_setting_list[$i]['field_name']); ?></td>
                                                <td class="text-center">
                                                    <?php if ($pos_setting_list[$i]['field_name'] == 'language') { ?>
                                                        <select name="<?php echo $pos_setting_list[$i]['field_name']; ?>" class="form-control select2">
                                                            <option value="" selected disabled>Choose Language</option>
                                                            <?php foreach ($language as $lang) { ?>
                                                                <option value="<?php echo strtolower($lang) ?>" <?php echo strtolower($lang) == $pos_setting_list[$i]['value'] ? 'selected' : '' ?>><?php echo $lang ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="<?php echo $pos_setting_list[$i]['field_name']; ?>" id="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio1'; ?>" value="enable" <?php echo $pos_setting_list[$i]['value'] == 'enable' ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio1'; ?>">Enable</label>

                                                            <input class="form-check-input" type="radio" name="<?php echo $pos_setting_list[$i]['field_name']; ?>" id="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio2'; ?>" value="disable" <?php echo $pos_setting_list[$i]['value'] == 'disable' ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio2'; ?>">Disable</label>
                                                        </div>
                                                        <!-- <input type="checkbox" class="<?php echo $pos_setting_list[$i]['field_name']; ?>" name="<?php echo $pos_setting_list[$i]['field_name']; ?>" id="<?php echo $pos_setting_list[$i]['field_name']; ?>" <?php echo $pos_setting_list[$i]['value'] == 'enable'  ? 'checked' : '' ?>> -->
                                                    <?php } ?>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-sm-6 table-responsive">

                                <table class="table table-bordered table-striped table-hover">

                                    <thead>
                                        <tr>
                                            <th>Feature</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = $half_list; $i < $length_of_pos_setting_list; $i++) { ?>
                                            <tr>
                                                <td style="width: 70%;"><?php echo display($pos_setting_list[$i]['field_name']); ?></td>
                                                <td class="text-center">

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="<?php echo $pos_setting_list[$i]['field_name']; ?>" id="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio1'; ?>" value="enable" <?php echo $pos_setting_list[$i]['value'] == 'enable' ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio1'; ?>">Enable</label>

                                                        <input class="form-check-input" type="radio" name="<?php echo $pos_setting_list[$i]['field_name']; ?>" id="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio2'; ?>" value="disable" <?php echo $pos_setting_list[$i]['value'] == 'disable' ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="<?php echo $pos_setting_list[$i]['field_name'] . 'inlineRadio2'; ?>">Disable</label>
                                                    </div>

                                                    <!-- <input type="checkbox" class="<?php echo $pos_setting_list[$i]['field_name']; ?>" name="<?php echo $pos_setting_list[$i]['field_name']; ?>" id="<?php echo $pos_setting_list[$i]['field_name']; ?>" <?php echo $pos_setting_list[$i]['value'] == 'enable'  ? 'checked' : '' ?>> -->
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                        <div class="form-group text-right">
                            <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                        </div>

                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add new customer end -->
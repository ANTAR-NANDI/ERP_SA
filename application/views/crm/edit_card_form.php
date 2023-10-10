<!--Edit customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Card" ?></h1>
            <small><?php echo "Card Edit" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Card" ?></a></li>
                <li class="active"><?php echo "Card Edit" ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- alert message -->
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
                            <h4><?php echo "Card Edit" ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('crm/card_update', array('class' => 'form-vertical', 'id' => 'card_update')) ?>
                    <div class="panel-body">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-4 col-form-label"><?php echo "Card Name" ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="name" id="name" type="text" placeholder="<?php echo "Card Name" ?>" required="" value="{name}" tabindex="1">
                                </div>
                           </div>

                            <input type="hidden" value="{id}" name="id">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                                <div class="col-sm-6">
                                    <input type="submit" id="add-card" class="btn btn-success btn-large" name="add-card" value="<?php echo display('save_changes') ?>" tabindex="5" />
                                </div>
                            </div>
                        </div>


                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Edit customer end -->
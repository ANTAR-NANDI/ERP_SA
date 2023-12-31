<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Manage Vat & Tax</h1>
            <small>Manage Vat & Tax</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Vat & Tax</a></li>
                <li class="active">Manage Vat & Tax</li>
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
            <div class="col-sm-12">

                <?php if ($this->permission1->method('create_product', 'create')->access()) { ?>
                    <a href="<?php echo base_url('Cvat') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i>Add Vat & Tax </a>
                <?php } ?>

            </div>
        </div>




        <!-- Manage Product report -->
        <div class="row">

            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Manage Vat & Tax</h4>


                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="vat_item">
                                <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('product_name') ?> (In Bangla)</th>
                                    <th><?php echo display('product_name') ?> (In English)</th>
                                    <th>Created Date</th>
                                    <th>Product Status</th>
                                    <th>SKU</th>
                                    <th>Vat</th>
                                    <th>Tax</th>

                                    <th><?php echo display('action') ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <input type="hidden" id="total_product" value="<?php echo $total_product; ?>" name="">
            </div>
        </div>
    </section>
</div>
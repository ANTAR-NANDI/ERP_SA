<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Earning Setting" ?></h1>
            <small><?php echo "Manage Earning Setting" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Earning Setting" ?></a></li>
                <li class="active"><?php echo "Manage Earning Setting" ?></li>
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


        <!-- Manage Product report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo "Manage Earning Setting" ?></h4>


                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive" style="overflow-x: auto !important;">
                            <table class="table table-striped table-bordered" cellspacing="0" id="earninglist" width="100%" style="overflow-x: auto !important;">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl') ?></th>
                                        <th>Card Types</th>
                                        <th>Points</th>
                                        <th><?php echo display('action') ?> </th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    

                                </tfoot>
                            </table>

                        </div>
                        <input type="hidden" name="" class="" id="total_earning" value="<?php echo html_escape($total_earning); ?>">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
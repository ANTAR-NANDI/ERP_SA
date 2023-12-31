<!--Edit customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Branch Edit</h1>
            <small>Branch Edit</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Courier</a></li>
                <li class="active">Branch Edit</li>
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
                            <h4>Branch Name</h4>
                        </div>
                    </div>
                  <?php echo form_open_multipart('Ccourier/branch_update',array('class' => 'form-vertical', 'id' => 'category_update'))?>
                    <div class="panel-body">

                    	<div class="form-group row">
                            <label for="category_name" class="col-sm-3 col-form-label">Branch Name <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name ="category_name" id="category_name" type="text" placeholder="Branch Name"  required="" value="{branch_name}">
                            </div>
                        </div>
                      
                        <input type="hidden" value="{branch_id}" name="courier_id">
                        <div class="form-group row">

                            <label for="category_name" class="col-sm-3 col-form-label">Location <i class="text-danger">*</i></label>
                            <div class="col-sm-3">
                                <table class="table table-striped table-responsive table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Type</th>


                                        <th>Charge</th>

                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>Inside</td>
                                        <td > <input  class="form-control" name ="inside" id="" type="text" placeholder="Charge"  value="{inside}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Outside</td>
                                        <td > <input  class="form-control" name ="outside" id="" type="text" placeholder="Charge"  value="{outside}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sub</td>
                                        <td > <input  class="form-control" name ="sub" id="" type="text" placeholder="Charge" value="{sub}">
                                        </td>
                                    </tr>



                                    </tbody>


                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-Customer" class="btn btn-success btn-large" name="add-Customer" value="<?php echo display('save_changes') ?>" />
                            </div>
                        </div>


                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Edit customer end -->




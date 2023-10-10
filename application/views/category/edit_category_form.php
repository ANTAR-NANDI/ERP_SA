<!--Edit customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('category_edit') ?></h1>
            <small><?php echo display('category_edit') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('category') ?></a></li>
                <li class="active"><?php echo display('category_edit') ?></li>
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
                            <h4><?php echo display('category_edit') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Ccategory/category_update', array('class' => 'form-vertical', 'id' => 'category_update')) ?>
                    <div class="panel-body">

                        <!-- <div class="form-group row">
                            <label for="category_name" class="col-sm-3 col-form-label"><?php echo display('category_name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="category_name" id="category_name" type="text" placeholder="<?php echo display('category_name') ?>" required="" value="{category_name}">
                            </div>
                        </div> -->

                        <div class="form-group ">
                            <label class="col-sm-2 col-form-label" for="name_bn">Name(In Bangla)</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="ক্যাটেগরি " id="name_bn" name="name_bn" class="form-control" required="" value="{name_bn}">
                            </div>
                        </div>
                        <br>
                        <div class="form-group ">
                            <label class="col-sm-2 col-form-label" for="name">Name(In English)</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control" required="" value="{name}">
                            </div>
                        </div>
                        <br>
                        <input type="hidden" value="{category_id}" name="category_id">


                        <div class="form-group ">
                            <label class="col-sm-2 col-form-label" for="name">Status</label>
                            <div class="col-sm-10">
                                <label><input type="radio" id="status" name="status" value="1" <?php echo $status == "1" ? 'checked' : ''; ?>> Active</label>&nbsp;&nbsp;&nbsp;
                                <label><input type="radio" id="status" name="status" value="0" <?php echo $status == "0" ? 'checked' : ''; ?>> Inactive</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-group ">
                            <label class="col-sm-2 col-form-label" for="name">Parent Category</label>
                            <div class="col-sm-10">
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="0">Select Parent Category</option>
                                    <?php
                                    if ($category_list) {
                                        foreach ($category_list as $category) {
                                    ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $parent_id ? 'selected' : ''  ?> <?php echo $category['id'] == $category_id ? 'disabled' : ''  ?>><?php echo $category['name_bn'] . '-' . $category['name']; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <br>

                        <!-- <div class="form-group ">
                            <label class="col-sm-2 col-form-label" for="name">Parent Category</label>
                            <div class="col-sm-10">
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <?php foreach ($category_list as $value) {
                                        if ($value['id'] == $id) {
                                    ?>
                                            <option value="0"><?php echo "Select Category" ?></option>
                                            <option value="<?php echo $value['id']; ?>" selected><?php echo $value['name']; ?></option>

                                        <?php } else { ?>

                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                    <?php }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" id="add-Customer" class="btn btn-success btn-large" name="add-Customer" value="<?php echo display('save_changes') ?>" />
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
<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Add Card" ?></h1>
            <small><?php echo "Add New Card" ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Cards" ?></a></li>
                <li class="active"><?php echo "Add Card" ?></li>
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
                            <h4><?php echo "Card Name" ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Crm/insert_card', array('class' => 'form-vertical', 'id' => 'insert_card')) ?>
                    <div class="panel-body">
                        <div class="col-sm-6">


                            <div class="form-group row">
                                <label for="card_name" class="col-sm-4 col-form-label"><?php echo "Card Name" ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input oninput="checkCard(this.value)" onblur="checkCard(this.value)" class="form-control" name="name" id="name" type="text" placeholder="<?php echo "Card Name" ?>" required="" tabindex="1">
                                    <h5 hidden id="CardExist"></h5>
                                </div>
                            </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <input type="submit" id="add-card" class="btn btn-primary btn-large" name="add_card" value="<?php echo display('save') ?>" tabindex="7" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add new customer end -->

<script type="text/javascript">
    // Check Card Existence
    function checkCard(name) {
        let csrf_test_name = $('[name="csrf_test_name"]').val();
        $.ajax({
            dataType: "json",
            type: 'POST',
            url: '<?= base_url() ?>' + "crm/checkCard",
            data: {
                    csrf_test_name: csrf_test_name,
                    name: name
                  },
            cache: false,
            success: function(response) {
                console.log(response);
                console.log(response);
                if (response.status == 1) {
                    document.getElementById("CardExist").className = "alert alert-danger";
                    $('#CardExist').show();
                    $('#CardExist').html('Card already Exist');
                    
                    $(':input[name="add_card"]').prop("disabled", true);
                } else {
                    $('#CardExist').html("");
                    $('#CardExist').hide();
                    $(':input[name="add_card"]').prop("disabled", false);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#CardExist').show();
                $('#CardExist').html(errorThrown);
            }
        });
    }
   
</script>

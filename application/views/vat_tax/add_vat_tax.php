<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/multiselect/sumoselect.min.css" />
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Add Vat & Tax</h1>
            <small>Add Vat & Tax</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#">Vat & Tax</a></li>
                <li class="active">Add Vat & Tax</li>
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
        <?php echo form_open('Cvat/insert_vat', array('class' => 'form-vertical', 'id' => 'submit_form', 'name' => '')) ?>
        <input type="hidden" id="base_url" value="<?= base_url() ?>">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                          Add Vat & Tax
                            </h4>
                        </div>

                    </div>


                    <div class="panel-body">

                        <div class="row">


                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label">Vat or Tax:
                                        <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                          <input type="radio" id="vat" name="vat_tax" value="vat" required>
                                          <label for="vat">Vat</label>
                                          <input type="radio" id="tax" name="vat_tax" value="tax" required>
                                          <label for="tax">Tax</label><br>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="add_item" class="col-sm-4 col-form-label">Type: <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                          <input type="radio" id="inclusive" name="vat_tax_type" value="in" required>
                                          <label for="inclusive">Inclusive</label>
                                          <input type="radio" id="exclusive" name="vat_tax_type" value="ex" required>
                                          <label for="exclusive">Exclusive</label><br>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row">



                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label">Percentage: <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="percent" class="form-control" placeholder='Percentage' autocomplete='off' tabindex="1" value="" required>

                                    </div>
                                </div>
                            </div>

                        </div>
                       
                        <div class="row">


                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label">Select Category : <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">

                                    <select name="category_id[]" multiple="" class="form-control dont-select-me" id="category_id">
                                                <?php if (!empty($categories)) {
                                                    foreach ($categories as $key => $value) { ?>
                                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                                <?php }
                                                } ?>
                                  </select>

                                    </div>
                                </div>
                            </div>




                        </div>

                        <div class="row">


                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="" class="col-sm-4 col-form-label">Select Product : <i class="text-danger">*</i></label>
                                        <div class="col-sm-7">
                                            <select name="product_id[]" multiple="" class="form-control dont-select-me" id="product_id" required>
                                                <?php if (!empty($products)) {
                                                    foreach ($products as $key => $value) { ?>
                                                        <option value="<?php echo $value['product_id'] ?>"><?php echo $value['product_name'] ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                            </div>
                                </div>
                            </div>




                        </div>
                        <!-- <div class="row">


                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="add_item" class="col-sm-4 col-form-label">Select All Product :</label>
                                    <div class="col-sm-7">

                                        <input id="chkpyall" type="checkbox" >  &nbsp;

                                    </div>
                                </div>
                            </div>




                        </div> -->






                        <?php if ($access != 'view'){ ?>
                            <button type="submit" id="submit_btn" name="submit_form" class="btn btn-success btn-md submit_btn">Submit</button>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php echo form_close(); ?>
    </section>
</div>
<script src="<?php echo base_url(); ?>assets/plugins/multiselect/jquery.sumoselect.min.js"></script>
<script type="text/javascript">


    $(document).ready(function () {

        //fetch category wise product
        var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
        $('#category_id').change(function(event) {
            var category = [];
            if ($(this).val() != null) {
                category.push($(this).val());
            }
            console.log(category[0].length)
            jQuery.ajax({
                type: "POST",
                dataType: "html",
                url: base +'Cvat/getProductbyCategory',
                data: {
                    cat_id: category,
                    csrf_test_name: CSRF_TOKEN,
                },
                success: function(response) {
                    console.log(response);
                    $('#product_id').empty().append(response);
                    $('#product_id')[0].sumo.reload();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        });
        $('#product_id').SumoSelect({
        selectAll: true,
        search: true
        });
        $('#category_id').SumoSelect({
            selectAll: true,
            search: true
        });
        var base = $('#base_url').val();
        // $('#py_cat').select2();
        $("#chkpyall").click(function(){
            if($("#chkpyall").is(':checked')){
                $("#py_cat > option").prop("selected", "selected");
                 $("#py_cat").trigger("change");
            } else {
                $("#py_cat > option").prop("selected", false);
                $("#py_cat").trigger("change");
            }
        });
        $.ajax( {
            url: base +'Cvat/get_all_product',
            method: 'get',
            cache: false,
            success: function( data ) {
                var obj = jQuery.parseJSON(data);

                console.log(obj)

                // $('#py').removeClass('d-none');
                $('#py_cat').html(obj);

            }
        })

        var table = $('#st_table').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: [1, 2, 3],
                },
            ],
        });

        $('.submit_btn').click(function () {
            // table.clear();
            table.destroy();
        });



        $('#add_item_m_p').keydown(function(e) {
            if (e.keyCode == 13) {
                // e.preventDefault()
                var rowCount = document.getElementById('addinvoice').rows.length;

                // alert(rowCount)
                var product_id = $(this).val();
                var exist = $("#SchoolHiddenId_" + product_id).val();
                var qty = $("#total_qntt_" + product_id).val();
                // var add_qty = parseInt(qty) + 1;
                var csrf_test_name = $('[name="csrf_test_name"]').val();
                var base_url = $("#base_url").val();
                if (product_id == exist) {
                    toastr.error('Already inserted!!')
                    // $("#total_qntt_" + product_id).val(add_qty);
                    document.getElementById('add_item_m_p').value = '';
                    document.getElementById('add_item_m_p').focus();
                } else {
                    $.ajax({
                        type: "post",
                        async: false,
                        url: base_url + 'Creport/append_product',
                        data: {
                            product_id: product_id,
                            rowCount: rowCount,
                            csrf_test_name: csrf_test_name
                        },
                        success: function(data) {
                            if (data == false) {
                                toastr.error('This Product Not Found !');
                                document.getElementById('add_item_m_p').value = '';
                                document.getElementById('add_item_m_p').focus();

                            } else {
                                $("#hidden_tr").css("display", "none");
                                document.getElementById('add_item_m_p').value = '';
                                document.getElementById('add_item_m_p').focus();
                                $('#addinvoice tbody').append(data);

                            }
                        },
                        error: function() {
                            toastr.error('Request Failed, Please check your code and try again!');
                        }
                    });
                }
            }
        });
    });

    function deleteRow(t) {
        var a = $("#addinvoice > tbody > tr").length;
//    alert(a);
        var e = t.parentNode.parentNode;
        e.parentNode.removeChild(e);
    }

    $('#submit_form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });




</script>
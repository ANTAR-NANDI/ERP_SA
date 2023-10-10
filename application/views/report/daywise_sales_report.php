<!-- Stock List Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo "Product Wise Sales Report" ?></h1>
            <small><?= $heading_text ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo "Product Wise Sales Report" ?></a></li>
                <li class="active"><?= $heading_text ?></li>
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
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title text-right">

                        </div>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="" id="pr_status" value="<?= $pr_status ?>">
                        

                        <div class="row">

                            <div class="col-sm-6">
                                <label class="" for="from_date"><?php echo display('start_date') ?></label>
                                <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" value="" autocomplete="off">
                            </div>
                            <div class="col-sm-6" style="margin-bottom: 10px;">
                                <label class="" for="to_date"><?php echo display('end_date') ?></label>
                                <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="" autocomplete="off">
                            </div>
                           

                           

                        </div>

                        <div>

                            <div class="table-responsive" id="printableArea">
                            <table  class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Day 1</th>
                                        <th>Day 2</th>
                                        <th>Day 3</th>
                                        <th>Day 4</th>
                                        <th>Day 5</th>
                                        <th>Day 6</th>
                                        <th>Day 7</th>
                                        <th>Day 8</th>
                                        <th>Day 9</th>
                                        <th>Day 10</th>
                                        <th>Day 5</th>
                                        <th>Day 6</th>
                                        <th>Day 7</th>
                                        <th>Day 8</th>
                                        <th>Day 9</th>
                                        <th>Day 10</th>
                                        <th>Day 1</th>
                                        <th>Day 2</th>
                                        <th>Day 3</th>
                                        <th>Day 4</th>
                                        <th>Day 5</th>
                                        <th>Day 6</th>
                                        <th>Day 7</th>
                                        <th>Day 8</th>
                                        <th>Day 9</th>
                                        <th>Day 10</th>
                                        <th>Day 5</th>
                                        <th>Day 6</th>
                                        <th>Day 7</th>
                                        <th>Day 8</th>
                                        <th>Day 9</th>
                                        <th>Day 10</th>
                                    </tr>
                                    <tr>
                                        <td>Product 1</td>
                                        <td>100</td>
                                        <td>110</td>
                                        <td>120</td>
                                        <td>130</td>
                                        <td>140</td>
                                        <td>150</td>
                                        <td>160</td>
                                        <td>170</td>
                                        <td>180</td>
                                        <td>190</td>
                                        <td>140</td>
                                        <td>150</td>
                                        <td>160</td>
                                        <td>170</td>
                                        <td>180</td>
                                        <td>190</td>
                                        <td>100</td>
                                        <td>110</td>
                                        <td>120</td>
                                        <td>130</td>
                                        <td>140</td>
                                        <td>150</td>
                                        <td>160</td>
                                        <td>170</td>
                                        <td>180</td>
                                        <td>190</td>
                                        <td>140</td>
                                        <td>150</td>
                                        <td>160</td>
                                        <td>170</td>
                                        <td>180</td>
                                        <td>190</td>
                                    </tr>
                                    <tr>
                                        <td>Product 2</td>
                                        <td>75</td>
                                        <td>80</td>
                                        <td>85</td>
                                        <td>90</td>
                                        <td>95</td>
                                        <td>100</td>
                                        <td>105</td>
                                        <td>110</td>
                                        <td>115</td>
                                        <td>120</td>
                                        <td>95</td>
                                        <td>100</td>
                                        <td>105</td>
                                        <td>110</td>
                                        <td>115</td>
                                        <td>120</td>
                                        <td>75</td>
                                        <td>80</td>
                                        <td>85</td>
                                        <td>90</td>
                                        <td>95</td>
                                        <td>100</td>
                                        <td>105</td>
                                        <td>110</td>
                                        <td>115</td>
                                        <td>120</td>
                                        <td>95</td>
                                        <td>100</td>
                                        <td>105</td>
                                        <td>110</td>
                                        <td>115</td>
                                        <td>120</td>
                                    </tr>
        <!-- Add more rows for other products as needed -->
                                  </table>
                            </div>
                        </div>
                         <input type="hidden" id="currency" value="{currency}" name=""> 
                        
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
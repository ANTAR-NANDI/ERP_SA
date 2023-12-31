<!-- Sales Report Start -->

<?php
$currentURL = $this->uri->uri_string();
$params   = $_SERVER['QUERY_STRING'];
$fullURL = $currentURL . '?' . $params;
$_SESSION['redirect_uri'] = $fullURL;

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo  "Product Report" ?></h1>
            <small><?php echo "Product Sales Report" ?></small>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() ?>"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('report') ?></a></li>
                <li class="active"><?php echo "Product Sales Report" ?></li>
            </ol>
        </div>
    </section>

    <section class="content">


        

       
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo "Product Sales Report" ?> </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="purchase_div" class="table-responsive">
                            <table class="print-table" width="100%">

                                <tr>
                                    <td align="left" class="print-table-tr">
                                        <img src="<?php echo $software_info[0]['logo']; ?>" alt="logo">
                                    </td>
                                    <td align="center" class="print-cominfo">
                                        <span class="company-txt">
                                            <?php echo $company[0]['company_name']; ?>

                                        </span><br>
                                        <?php echo $company[0]['address']; ?>
                                        <br>
                                        <?php echo $company[0]['email']; ?>
                                        <br>
                                        <?php echo $company[0]['mobile']; ?>

                                    </td>

                                    <td align="right" class="print-table-tr">
                                        <date>
                                            <?php echo display('date') ?>: <?php
                                                                            echo date('d-M-Y');
                                                                            ?>
                                        </date>
                                    </td>
                                </tr>

                            </table>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="salesReportTable">
                                    <thead>
                                        <tr>
                                        
                                            <th class="text-center"><?php echo display('product_name') ?></th>
                                            <th class="text-center">Supplier Name</th>
                                            <th class="text-center">Purchase ID</th>
                                            <th class="text-center">Purchase Date</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Product Rate</th>
                                            <th class="text-center">Total Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $subtotal = 0;
                                        if ($product_sales_detail) {
                                        ?>

                                            <?php
                                            $subtotal = 0;
                                            $sl = 0;
                                            $total_gross_profit = 0;
                                            $total_qnty = 0;
                                            $total_sales = 0;
                                            $total_discount = 0;
                                            $total_cost_price = 0;
                                            $length = count($product_sales_detail);
                                            foreach ($product_sales_detail as $key => $sales) {
                                                $sl++; ?>
                                                <tr>
                                                  
                                                   
                                                    <?php
                                                    if($key == 0)
                                                    {
                                                        ?>
                                                        <td rowspan="<?php echo $length ?>">
                                                            <?php
                                                        echo $sales['product_name'];
                                                        ?>

                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    <td><?php echo $sales['supplier_name'] ?></td>
                                                   
                                                    <td>
                                                    <?php echo $sales['purchase_id'] ?>
                                                    </td>
                                                    <td><?php echo $sales['purchase_date'] ?></td>
                                                    <td><?php echo (int)$sales['qnty'] ?></td>
                                                    <td><?php echo $sales['rate'] ?></td>
                                                    <td><?php echo $sales['total_amount'] ?></td>
                                                   
                                                    
                                                </tr>
                                            <?php } ?>
                                        <?php } else {
                                        ?>

                                            <tr>
                                                <th class="text-center" colspan="8"><?php echo display('not_found'); ?></th>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <!-- <div class="text-right"><?php echo $links ?></div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Sales Report End -->

<script type="text/javascript">
    $(document).ready(function() {
        $('#salesReportTable').DataTable({
            dom: "Bfrltip",
            // select: true,
            responsive: true,
            processing: true,
            "order": [],
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            pageLength: 10,
            buttons: [
                // 'pageLength',
                {
                    extend: 'copyHtml5',
                    footer: true,
                    className: "btn-sm prints",
                },
                {
                    extend: 'excelHtml5',
                    footer: true,
                    className: "btn-sm prints",
                },
                {
                    extend: 'csvHtml5',
                    footer: true,
                    className: "btn-sm prints",
                },
                {
                    extend: 'pdfHtml5',
                    footer: true,
                    className: "btn-sm prints",
                },
                {
                    extend: 'print',
                    footer: true,
                    className: "btn-sm prints",
                }
            ]
        });
    });
</script>
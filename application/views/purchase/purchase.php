<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1><?php echo display('manage_purchase') ?></h1>
			<small><?php echo display('manage_your_purchase') ?></small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('purchase') ?></a></li>
				<li class="active"><?php echo display('manage_purchase') ?></li>
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


		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-7">

						<?php echo form_open('', 'class="form-inline"') ?>

						<div class="form-group">
							<label class="" for="from_date"><?php echo display('from') ?></label>
							<input type="text" name="from_date" class="form-control datepicker" id="from_date" value="" placeholder="<?php echo display('start_date') ?>">
						</div>

						<div class="form-group">
							<label class="" for="to_date"><?php echo display('to') ?></label>
							<input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="">
						</div>

						<button type="button" id="btn-filter" class="btn btn-success"><?php echo display('find') ?></button>

						<?php echo form_close() ?>
					</div>

				</div>
			</div>


		</div>




		<!-- Manage Purchase report -->
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-bd lobidrag">
					<div class="panel-heading">
						<div class="panel-title">

						</div>

					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="PurList">
								<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<!-- <th>Chalan NO:</th> -->
										<th><?php echo display('purchase_id') ?></th>
										<th>Outlet Name</th>
										<th><?php echo display('supplier_name') ?></th>
										<th><?php echo display('purchase_date') ?></th>
										<th><?php echo display('total_ammount') ?></th>
										<th><?php echo display('paid_ammount') ?></th>
										<th><?php echo "Returned Amount" ?></th>
										<th><?php echo "Due Amount" ?></th>
										<th><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
								<tfoot>
									<th colspan="5" class="text-right"><?php echo display('total') ?>:</th>

									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									
								</tfoot>
							</table>
						</div>

					</div>
				</div>
			</div>
			<input type="hidden" id="total_purchase_no" value="<?php echo $total_purhcase; ?>" name="">
			<input type="hidden" id="currency" value="{currency}" name="">
		</div>
	</section>
	<div class="modal fade modal-success updateModal" id="update_payment" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <a href="#" class="close" data-dismiss="modal">&times;</a>
                <h3 class="modal-title">পেমেন্ট</h3>
            </div>

            <div class="modal-body">
                <div id="customeMessage" class="alert hide"></div>
                <form method="post" id="ProjectEditForm" action="<?php echo base_url('Cpurchase/due_payment/') ?>">
                    <div class="panel-body">
                        <input type="hidden" name="csrf_test_name" id="" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="purchase_id" id="purchase_id" value="">
                        <div class="form-group row">


                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        মোট টাকা </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="total_amount" name="total_amount" value="" placeholder="0.00" readonly>
                                        <input type="hidden" class="form-control" id="totalAmount" name="totalAamount" value="" placeholder="0.00" readonly>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        পূর্ববর্তী প্রাপ্তি </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="paid_amount" name="paid_amount" placeholder="0.00" value="" readonly>
                                        <input type="hidden" class="form-control" id="paidAmount" name="paidAmount" placeholder="0.00" value="" readonly>


                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        বকেয়া </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="due_amount" name="due_amount" value="" placeholder="0.00" readonly>
                                        <input type="hidden" class="form-control" id="dueAmount" name="dueAmount" value="" placeholder="0.00" readonly>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        প্রাপ্তি <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="pay_amount" name="pay_amount" placeholder="0.00" onkeyup="calculation_due()" onkeypress="calculation_due()" onchange="calculation_due()">

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        নোট </label>
                                    <div class="col-sm-6">
                                        <textarea name="notes" class="form-control" placeholder="..."></textarea>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">
                                        পেমেন্ট পদ্ধতি <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <select name="paytype" id="paytype" class="form-control" required="" onchange="bank_paymet(this.value)">
                                            <option value="1">ক্যাশ</option>
                                            <option value="4">ব্যাংক</option>
                                            <option value="3">বিকাশ</option>
                                            <option value="5">নগদ</option>
                                            <option value="7">রকেট</option>

                                        </select>


                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6" id="bank_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">ব্যাংক</label>
                                    <div class="col-sm-8">
                                        <select name="bank_id" class="form-control bankpayment" id="bank_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($bank_list as $bank) { ?>
                                                <option value="<?php echo $bank['bank_id'] ?>"><?php echo $bank['bank_name']; ?> (<?php echo $bank['ac_number']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>
                            <div class="col-sm-6" id="bkash_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">বিকাশ</label>
                                    <div class="col-sm-8">
                                        <select name="bkash_id" class="form-control bankpayment" id="bkash_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($bkash_list as $bkash) { ?>
                                                <option value="<?php echo $bkash['bkash_id'] ?>"><?php echo $bkash['bkash_no']; ?> (<?php echo $bkash['ac_name']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>
                            <div class="col-sm-6" id="nagad_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">নগদ</label>
                                    <div class="col-sm-8">
                                        <select name="nagad_id" class="form-control bankpayment" id="nagad_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($nagad_list as $nagad) { ?>
                                                <option value="<?php echo $nagad['nagad_id'] ?>"><?php echo $nagad['nagad_no']; ?> (<?php echo $nagad['ac_name']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>
                            <div class="col-sm-6" id="rocket_div">
                                <div class="form-group row">
                                    <label for="bank" class="col-sm-3 col-form-label">রকেট</label>
                                    <div class="col-sm-8">
                                        <select name="rocket_id" class="form-control bankpayment" id="rocket_id">
                                            <option value="">Select Location</option>
                                            <?php foreach ($rocket_list as $rocket) { ?>
                                                <option value="<?php echo $rocket['rocket_id'] ?>"><?php echo $rocket['rocket_no']; ?> (<?php echo $rocket['ac_name']; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>




                                </div>
                            </div>




                        </div>





                    </div>

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>
                <button type="submit" id="ProjectUpdateConfirmBtn" class="btn btn-success">Update</button>
            </div>
            <!--                    <div class="modal-footer">-->
            <!---->
            <!--                        <a href="#" class="btn btn-danger" data-dismiss="modal">Close</a>-->
            <!---->
            <!--                        <input type="submit" id="ProjectUpdateConfirmBtn" class="btn btn-success" value="Submit">-->
            <!--                    </div>-->
            <?php echo form_close() ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
</div>
<!-- Manage Purchase End -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/purchase.js.php" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		"use strict";
		var csrf_test_name = $('[name="csrf_test_name"]').val();
		var total_purchase_no = $("#total_purchase_no").val();
		var base_url = $("#base_url").val();
		var currency = $("#currency").val();
		var purchasedatatable = $('#PurList').DataTable({
			responsive: true,

			"aaSorting": [
				[1, "desc"]
			],
			"columnDefs": [{
					"bSortable": false,
					"aTargets": [0, 1, 2, 3]
				},

			],
			'processing': true,
			'serverSide': true,


			'lengthMenu': [
				[10, 25, 50, 100, 250, 500, total_purchase_no],
				[10, 25, 50, 100, 250, 500, "All"]
			],

			dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
			buttons: [{
				extend: "copy",
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want
				},
				className: "btn-sm prints"
			}, {
				extend: "csv",
				title: "PurchaseLIst",
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
				},
				className: "btn-sm prints"
			}, {
				extend: "excel",
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
				},
				title: "PurchaseLIst",
				className: "btn-sm prints"
			}, {
				extend: "pdf",
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
				},
				title: "PurchaseLIst",
				className: "btn-sm prints"
			}, {
				extend: "print",
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
				},
				title: "<center> PurchaseLIst</center>",
				className: "btn-sm prints"
			}],


			'serverMethod': 'post',
			'ajax': {
				'url': base_url + 'Cpurchase/CheckPurchaseList',
				"data": function(data) {
					data.fromdate = $('#from_date').val();
					data.todate = $('#to_date').val();

					data.csrf_test_name = csrf_test_name;


				}
			},
			'columns': [{
					data: 'sl'
				},
				// { data: 'chalan_no'},
				{
					data: 'purchase_id'
				},
				{
					data: 'outlet_name'
				},
				{
					data: 'supplier_name'
				},
				{
					data: 'purchase_date'
				},
				{
					data: 'total_amount',
					class: "total_sale text-right",
					render: $.fn.dataTable.render.number(',', '.', 2, currency)
				},
				{
					data: 'paid_amount',
					class: "paid_amount text-right",
					render: $.fn.dataTable.render.number(',', '.', 2, currency)
				},
				{
					data: 'returned_amount',
					class: "returned_amount text-right",
					render: $.fn.dataTable.render.number(',', '.', 2, currency)
				},
				{
					data: 'due_amount',
					class: "due_amount text-right",
					render: $.fn.dataTable.render.number(',', '.', 2, currency)
				},
				{
					data: 'button'
				},
			],

			"footerCallback": function(row, data, start, end, display) {
				var api = this.api();
				api.columns('.total_sale', {
					page: 'current'
				}).every(function() {
					var sum = this
						.data()
						.reduce(function(a, b) {
							var x = parseFloat(a) || 0;
							var y = parseFloat(b) || 0;
							return x + y;
						}, 0);
					$(this.footer()).html(currency + ' ' + sum.toLocaleString(undefined, {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}));
				});
				api.columns('.paid_amount', {
					page: 'current'
				}).every(function() {
					var sum = this
						.data()
						.reduce(function(a, b) {
							var x = parseFloat(a) || 0;
							var y = parseFloat(b) || 0;
							return x + y;
						}, 0);
					$(this.footer()).html(currency + ' ' + sum.toLocaleString(undefined, {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}));
				});
				api.columns('.returned_amount', {
					page: 'current'
				}).every(function() {
					var sum = this
						.data()
						.reduce(function(a, b) {
							var x = parseFloat(a) || 0;
							var y = parseFloat(b) || 0;
							return x + y;
						}, 0);
					$(this.footer()).html(currency + ' ' + sum.toLocaleString(undefined, {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}));
				});
				api.columns('.due_amount', {
					page: 'current'
				}).every(function() {
					var sum = this
						.data()
						.reduce(function(a, b) {
							var x = parseFloat(a) || 0;
							var y = parseFloat(b) || 0;
							return x + y;
						}, 0);
					$(this.footer()).html(currency + ' ' + sum.toLocaleString(undefined, {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}));
				});
			}


		});


		$('#btn-filter').click(function() {
			purchasedatatable.ajax.reload();
		});

	});
	function payment_modal(id, total_amount, paid_amount, due_amount) {
		$('#update_payment').modal('show');
        $('#purchase_id').val(id)
        $('#total_amount').val(total_amount.toFixed(2, 2))
        $('#totalAmount').val(total_amount.toFixed(2, 2))
        $('#paidAmount').val(paid_amount.toFixed(2, 2))
        $('#paid_amount').val(paid_amount.toFixed(2, 2))
        $('#due_amount').val(due_amount.toFixed(2, 2))
        $('#dueAmount').val(due_amount.toFixed(2, 2))

     }
	 function bank_paymet(val) {
        if (val == 4) {
            var style = 'block';
            document.getElementById('bank_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('bank_id').removeAttribute("required");
        }

        document.getElementById('bank_div').style.display = style;
        if (val == 3) {
            var style = 'block';
            document.getElementById('bkash_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('bkash_id').removeAttribute("required");
        }

        document.getElementById('bkash_div').style.display = style;
        if (val == 5) {
            var style = 'block';
            document.getElementById('nagad_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('nagad_id').removeAttribute("required");
        }

        document.getElementById('nagad_div').style.display = style;

        if (val == 7) {
            var style = 'block';
            document.getElementById('rocket_id').setAttribute("required", true);
        } else {
            var style = 'none';
            document.getElementById('rocket_id').removeAttribute("required");
        }

        document.getElementById('rocket_div').style.display = style;
    }


    $(document).ready(function() {
        var paytype = $("#editpayment_type").val();
        if (paytype == 2) {
            $("#bank_div").css("display", "block");
        } else {
            $("#bank_div").css("display", "none");
        }

        if (paytype == 3) {
            $("#bkash_div").css("display", "block");
        } else {
            $("#bkash_div").css("display", "none");
        }

        if (paytype == 4) {
            $("#nagad_div").css("display", "block");
        } else {
            $("#nagad_div").css("display", "none");
        }

        if (paytype == 7) {
            $("#rocket_div").css("display", "block");
        } else {
            $("#rocket_div").css("display", "none");
        }

        $(".bankpayment").css("width", "100%");
    });
	function calculation_due() {



var p = 0,
	d = 0;

var pay_amount = parseFloat($('#pay_amount').val());
var total_amount = parseFloat($('#totalAmount').val());
var due_amount = parseFloat($('#dueAmount').val());
var paid_amount = parseFloat($('#paidAmount').val());


p = paid_amount + pay_amount;
d = total_amount - p

$('#paid_amount').val(p.toFixed(2, 2))
$('#due_amount').val(d.toFixed(2, 2));

if (due_amount < pay_amount) {
	toastr.error("You can't receive greater than customer due amount!")
	$('#pay_amount').val('')
	$('#due_amount').val(due_amount.toFixed(2, 2))
	$('#paid_amount').val(paid_amount.toFixed(2, 2))
}

}
</script>
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-credit-card"></i>
			<div class="content-header-title">Tagihan</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="content-box">
					<div class="row">
						<div class="col-md-12">
								<div class="form-group row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<form id="form-checkout">
											<table class="table table-striped table-bordered">
												<thead>
													<th style="text-align: center;">No</th>
													<th style="text-align: center;">Pembayaran</th>
													<th style="text-align: center;">Jatuh Tempo</th>
													<th style="text-align: center;">Bayar</th>
												</thead>
												<tbody>
													<?php $no=0; foreach ($bills as $row) { $no++; ?>
													<?php if($row->payment==1){ $ch = "checked"; }else{ $ch = ""; } ?>
													<tr>
														<td style="text-align: center;"><?=$no?></td>
														<td style="text-align: center;"><p><?=$row->bills_category_name?></p><p><?=$row->name?></p></td>
														<td style="text-align: center;"><p><?=number_format($row->amount,0,',','.')?></p><p><?=date('d-m-Y',strtotime($row->duedate))?></p></td>
														<td style="text-align: center;"><input type="checkbox" <?=$ch?> value="" onclick="AksiBayar(this.checked,'<?=$row->id?>');" id="checkID" name="checkID"/></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
											</form>
										</div>
									</div>
								</div>
							<div class="content-box-footer">
								<button class="btn btn-primary" id="check-out" type="button">Checkout</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Form -->
<section class="form-panel"></section>


<script type="text/javascript">

function AksiBayar(status,billsID) {
	if(status==true)
	{
		$.ajax({
			type:"POST",
			url:"<?=base_url()?>payment/checked",
			data:{
				billsID : billsID
			},
		success: function(data) {
		$( "#table-responsive" ).load( "<?=base_url()?>payment");
		}
		});
	}
	else
	{
		$.ajax({
			type:"POST",
			url:"<?=base_url()?>payment/unchecked",
			data:{
				billsID : billsID
			},
		success: function(data) {
		$( "#table-responsive" ).load( "<?=base_url()?>payment");
		}
		});
	}
}

</script>


<script type="text/javascript">
	$('#check-out').on("click", function() {
		login();
	});
	$("#form-checkout").keypress(function(event) {
		if (event.which == 13) {
			login();
		}
	});

	function login() {
		$('#check-out').html("Checkout...").attr('disabled', true);
		var data = $('#form-signin').serialize();
		$.post("<?php echo base_url() . 'payment/validate'; ?>", data).done(function(data) {
			if (data.status == "success") {
				window.location.replace("<?php echo base_url(); ?>snap");
			} else {
				$('#check-out').html("Login").attr('disabled', false);
				$('.validation-message').html('');
				$('.validation-message').each(function() {
					for (var key in data) {
						if ($(this).attr('data-field') == key) {
							$(this).html(data[key]);
						}
					}
				});
			}
		});
	}
</script>
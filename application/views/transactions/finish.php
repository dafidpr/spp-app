<?php

$status_code 			= $result->{'status_code'};

$status_message 		= $result->{'status_message'};

$order_id 				= $result->{'order_id'};

$gross_amount 			= $result->{'gross_amount'};

$payment_type 			= $result->{'payment_type'};

$transaction_time 		= $result->{'transaction_time'};

$transaction_status 	= $result->{'transaction_status'};

if($transaction_status == 'pending'){
	$pdf_url 		= $result->{'pdf_url'} ;
}else{
	$pdf_url		= '';
}



$finish_redirect_url 	= $result->{'finish_redirect_url'};

?>

<div class="content">

	<div class="panel">

		<div class="content-header no-mg-top">

			<i class="fa fa-credit-card"></i>

			<div class="content-header-title">Status Pembayaran</div>

		</div>

		<div class="row">

			<div class="col-md-12">

				<div class="content-box">

					<div class="row">

						<div class="col-md-12">

							<div class="form-group row">

								<div class="col-sm-2"><label for=""> Status Message</label></div>

								<div class="col-sm-10"><label for=""> <?=$status_message?></label></div>

							</div>

							<div class="form-group row">

								<div class="col-sm-2"><label for=""> Order ID</label></div>

								<div class="col-sm-10"><label for=""> <?=$order_id?></label></div>

							</div>

							<div class="form-group row">

								<div class="col-sm-2"><label for=""> Gross Amount</label></div>

								<div class="col-sm-10"><label for=""> <?=$gross_amount?></label></div>

							</div>

							<div class="form-group row">

								<div class="col-sm-2"><label for=""> Payment Type</label></div>

								<div class="col-sm-10"><label for=""> <?=$payment_type?></label></div>

							</div>

							<div class="form-group row">

								<div class="col-sm-2"><label for=""> Transaction Time</label></div>

								<div class="col-sm-10"><label for=""> <?=$transaction_time?></label></div>

							</div>

							<div class="form-group row">

								<div class="col-sm-2"><label for=""> Transaction Status</label></div>

								<div class="col-sm-10"><label for=""> <?=$transaction_status?></label></div>

							</div>
							<?php if($transaction_status == 'pending'){ ?>
							<div class="content-box-footer"><button class="btn btn-primary" onclick="pdf()"><i class="fa fa-file-pdf-o"></i> Download Panduan</button></div>
							<?php } ?>

							

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>





<script type="text/javascript">

	function pdf(){

		window.open('<?=$pdf_url?>',"_Blank");

	}

</script>


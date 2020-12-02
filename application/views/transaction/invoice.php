<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Invoice</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="invoice-wrapper">
					<div class="invoice-info">
						<div class="company-info">
							<img src="<?php echo base_url() . 'assets/images/logo-blue.png'; ?>" style="width: 60px;height: 60px;">
							<div class="company-name"><?php echo $company_info[0]->meta_value; ?></div>
							<div class="company-address"><?php echo $company_info[1]->meta_value; ?></div>
							<div class="company-phone">Telephone: <?php echo $company_info[2]->meta_value; ?></div>
						</div>
						<div class="client-info">
							<div class="client-name"><?php echo $transaction->student_name; ?></div>
							<div class="client-address"><?php echo $transaction->student_nis; ?></div>
							<div class="client-phone"><?php echo $transaction->students_group_name; ?></div>
						</div>
					</div>
					<div class="invoice-date">
						<div class="invoice-date-title">Invoice</div>
						<div class="invoice-date-text"><?php echo date ('j F Y', strtotime($transaction->created_at)); ?></div>
					</div>
					<div class="invoice-body">
						<div class="invoice-code">
							<span class="invoice-code-title">Invoice Number #</span>
							<span class="invoice-code-text"><?php echo $transaction->invoice_number; ?></span>
						</div>
						<table class="table invoice-table">
							<thead>
								<tr>
									<th>Description</th>
									<th class="text-right">Price</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($transaction_details as $transaction_detail) { ?>
									<tr>
										<td><?php echo $transaction_detail->bills_name; ?></td>
										<td class="text-right">Rp. <?php echo $transaction_detail->bills_amount; ?></td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td>Total</td>
									<td class="text-right" colspan="2">Rp. <?php echo $transaction->amount; ?></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="invoice-footer">
						<div class="row">
							<div class="col-md-6">
								<img src="<?php echo base_url() . 'assets/images/logo-blue.png'; ?>">
								<span class="company-name"><?php echo $company_info[0]->meta_value; ?></span>
							</div>
							<div class="col-md-6 text-right">
								<span class="company-email-phone"><?php echo $company_info[3]->meta_value; ?> / <?php echo $company_info[2]->meta_value; ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
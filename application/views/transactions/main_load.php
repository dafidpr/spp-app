<style type="text/css">
	html,
	body {
		height: 100%;
	}
	body {
		font-family: 'Source Sans Pro', sans-serif;
	}
	.form-group label{
		padding-bottom: 0px !important;
	}
	.container {
		width: 100%;
		height: 100%;

		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
	}
	.control-group {
		display: inline-block;
		vertical-align: top;
		background: #fff;
		text-align: left;
		box-shadow: 0 1px 2px rgba(0,0,0,0.1);
		width: 200px;
		height: 100%;
	}
	.control {
		position: relative;
		cursor: pointer;
		font-size: 18px;
	}
	.control input {
		position: absolute;
		z-index: -1;
		opacity: 0;
	}
	.control__indicator {
		position: center;
		top: 2px;
		left: 0;
		height: 20px;
		width: 20px;
		background: #ff9900;
	}
	.control--radio .control__indicator {
		border-radius: 0%;
	}
	.control:hover input ~ .control__indicator,
	.control input:focus ~ .control__indicator {
		background: #ccc;
	}
	.control input:checked ~ .control__indicator {
		background: #2aa1c0;
	}
	.control:hover input:not([disabled]):checked ~ .control__indicator,
	.control input:checked:focus ~ .control__indicator {
		background: #0e647d;
	}
	.control input:disabled ~ .control__indicator {
		background: #ffebcc;
		opacity: 0.6;
		pointer-events: none;
	}
	.control__indicator:after {
		content: '';
		position: absolute;
		display: none;
	}
	.control input:checked ~ .control__indicator:after {
		display: block;
	}
	.control--checkbox .control__indicator:after {
		left: 8px;
		top: 4px;
		width: 5px;
		height: 10px;
		border: solid #fff;
		border-width: 0 2px 2px 0;
		transform: rotate(32deg);
	}
	.control--checkbox input:disabled ~ .control__indicator:after {
		border-color: red;
	}
</style>

<table class="table table-striped table-bordered">
	<thead>
		<th style="text-align: center;">No</th>
		<th style="text-align: center;">Pembayaran</th>
		<th style="text-align: center;">Jatuh Tempo</th>
		<th style="text-align: center;">Bayar</th>
	</thead>
	<tbody>
		<tr>
			<td colspan="4">Tagihan Tetap</td>
		</tr>
		<?php $no=0; foreach ($bills as $row) { $no++; ?>
		<?php if($row->payment==1){ $ch = "checked"; }else{ $ch = ""; } ?>
		<tr>
			<td style="text-align: center;"><?=$no?></td>
			<td style="text-align: center;"><p><?=$row->bills_category_name?></p><p><?=$row->name?></p></td>
			<td style="text-align: center;"><p>Rp. <?=number_format($row->amount,0,',','.')?></p><p><?=date('d-m-Y',strtotime($row->duedate))?></p></td>
			<td style="text-align: center;"><label class="control control--checkbox" style=""><input type="checkbox" <?=$ch?> value="" onclick="AksiBayar(this.checked,'<?=$row->id?>');" id="checkID" name="checkID" <?php if($no>$BayarID){ $lock = 'disabled'; }else { $lock = ''; } echo $lock; ?>/><div class="control__indicator" style=""></div></label></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4">Tagihan Tidak Tetap</td>
		</tr>
		<?php $no=0; foreach ($bills_temporary as $temporary) { $no++; ?>
		<?php if($temporary->payment==1){ $ch = "checked"; }else{ $ch = ""; } ?>
		<tr>
			<td style="text-align: center;"><?=$no?></td>
			<td style="text-align: center;"><p><?=$temporary->bills_category_name?></p><p><?=$temporary->name?></p></td>
			<td style="text-align: center;"><p>Rp. <?=number_format($temporary->amount,0,',','.')?></p><p><?=date('d-m-Y',strtotime($temporary->duedate))?></p></td>
			<td style="text-align: center;"><label class="control control--checkbox" style=""><input type="checkbox" <?=$ch?> value="" onclick="AksiBayar(this.checked,'<?=$temporary->id?>');" id="checkID" name="checkID"/><div class="control__indicator" style=""></div></label></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
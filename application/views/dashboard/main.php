<div class="top-banner">
	<div class="top-banner-title">Dashboard</div>
	<div class="top-banner-subtitle">Welcome back, <?php echo $active_user->name; ?>, <?php echo $active_user->username; ?></div>
</div>
<div class="content with-top-banner">
	<div class="panel">
		<div class="row">
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<div class="clear">
						<div class="card-title"> Rp. 
							<span class="timer" data-from="0" data-to="<?=$TotalVolume?>"><?=$TotalVolume?></span>
						</div>
						<div class="card-subtitle"><p style="color: #0275d8;">Total Volume</p> <p style="font-style: italic;">Month to Date</p></div>
					</div>
				</div>
			</div>
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<div class="clear">
						<div class="card-title"> 
							<span class="timer" data-from="0" data-to="<?=$TotalTransaction?>"><?=$TotalTransaction?></span>
						</div>
						<div class="card-subtitle"><p style="color: #0275d8;">Total Transaction</p> <p style="font-style: italic;">Month to Date</p></div>
					</div>
				</div>
			</div>
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<div class="clear">
						<div class="card-title">  
							<span class="timer" data-from="0" data-to="<?=$TotalUser?>"><?=$TotalUser?></span>
						</div>
						<div class="card-subtitle"><p style="color: #0275d8;">Total User</p> <p style="font-style: italic;">Month to Date</p></div>
					</div>
				</div>
			</div>
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<div class="clear">
						<div class="card-title"> Rp. 
							<span class="timer" data-from="0" data-to="<?=$Lastsettlementamount?>"><?=$Lastsettlementamount?></span>
						</div>
						<div class="card-subtitle"><p style="color: #0275d8;">Last Settlement Amount</p> <p style="font-style: italic;"><?=date('d M Y')?></p></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel">
		<div class="row">
			<div class="col-md-8 sm-max">
				<div class="content-header">
					<i class="fa fa-signal"></i>
					<div class="content-header-title">Transaction Volume</div>
				</div>
				<div class="content-box">
					<div class="line-chart-wrapper">
						<div class="line-chart-label">Last Transaction</div>
						<div class="line-chart-value">
							<span class="timer" data-from="0" data-to="<?=$Lastsettlementamount?>"><?=$Lastsettlementamount?></span>
						</div>
						<canvas id="line-chart"></canvas>
					</div>
				</div>
			</div>
			<div class="col-md-4 sm-max">
				<div class="content-header">
					<i class="fa fa-suitcase"></i>
					<div class="content-header-title">Last Settlement</div>
				</div>
				<div class="content-box slide-item">
				<?php foreach ($Lastsettlement as $key => $value) { ?>
					<div class="product-list">
						<img alt="pongo" class="pull-left" src="<?=base_url()?>foto/<?=$this->picture_m->get_picture($value->students_id)?>">
						<div class="clear">
							<div class="product-list-title"><?=get_field($value->students_id,'students','student_name')?></div>
							<div class="product-list-category"><?=get_field($value->students_id,'students','student_nis')?></div>
						</div>
						<div class="product-list-price">Rp. <?=number_format($value->amount,2,',','.')?></div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
		// Line chart 
	if ($('#line-chart').length) {
		var lineChart = $("#line-chart");
		var lineData = {
			labels: [<?php foreach ($TransactionVolume as $row) { echo '"'; echo $row->DateFormat; echo '",'; } ?>],
			datasets: [{
				label: "Total",
				fill: false,
				lineTension: 0,
				backgroundColor: "#fff",
				borderColor: "#6896f9",
				borderCapStyle: 'butt',
				borderDash: [],
				borderDashOffset: 0.0,
				borderJoinStyle: 'miter',
				pointBorderColor: "#fff",
				pointBackgroundColor: "#2a2f37",
				pointBorderWidth: 3,
				pointHoverRadius: 10,
				pointHoverBackgroundColor: "#FC2055",
				pointHoverBorderColor: "#fff",
				pointHoverBorderWidth: 3,
				pointRadius: 10,
				pointHitRadius: 100,
				data: [<?php foreach ($TransactionVolume as $row) { echo '"'; echo $row->TotalVolume; echo '",'; } ?>],
				spanGaps: false
			}]
		};

		var myLineChart = new Chart(lineChart, {
			type: 'line',
			data: lineData,
			options: {
				legend: {
					display: false
				},
				scales: {
					xAxes: [{
						ticks: {
							fontSize: '11',
							fontColor: '#969da5'
						},
						gridLines: {
							color: 'rgba(0,0,0,0.05)',
							zeroLineColor: 'rgba(0,0,0,0.05)'
						}
					}],
					yAxes: [{
						display: false,
						ticks: {
							beginAtZero: true,
							max: 900000000
						}
					}]
				}
			}
		});
	}
</script>
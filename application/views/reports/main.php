<div class="top-banner">
	<div class="top-banner-title">Reports</div>
	<div class="top-banner-subtitle">Welcome back, <?php echo $active_user->name; ?>, <i class="fa fa-map-marker"></i> New York City</div>
</div>
<div class="content with-top-banner">

	<div class="panel">
		<div class="row">
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<i class="fa fa-money"></i>
					<div class="clear">
						<div class="card-title">
							<span class="timer" data-from="0" data-to="<?=$TotalVolume->Total?>"><?=$TotalVolume->Total?></span>
						</div>
						<div class="card-subtitle">Total Volume <br> Mouth to Date</div>
					</div>
				</div>
				<div class="card-menu">
					<ul>
						<li><a href="">Today</a></li>
						<li><a href="">7 Days</a></li>
						<li><a href="">14 Days</a></li>
						<li><a href="">Last Month</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<i class="fa fa-signal"></i>
					<div class="clear">
						<div class="card-title">
							<span class="timer" data-from="0" data-to="<?=$TotalTransaction->Total?>"><?=$TotalTransaction->Total?></span>
						</div>
						<div class="card-subtitle">Total Transaction <br> Month to Date</div>
					</div>
				</div>
				<div class="card-menu">
					<ul>
						<li><a href="">Today</a></li>
						<li><a href="">7 Days</a></li>
						<li><a href="">14 Days</a></li>
						<li><a href="">Last Month</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<i class="fa fa-user-circle"></i>
					<div class="clear">
						<div class="card-title">
							<span class="timer" data-from="0" data-to="<?=$Totaluser->Total?>"><?=$Totaluser->Total?></span>
						</div>
						<div class="card-subtitle">Total user <br> User to Date</div>
					</div>
				</div>
				<div class="card-menu">
					<ul>
						<li><a href="">Today</a></li>
						<li><a href="">7 Days</a></li>
						<li><a href="">14 Days</a></li>
						<li><a href="">Last Month</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-3 card-wrapper">
				<div class="card">
					<i class="fa fa-money"></i>
					<div class="clear">
						<div class="card-title">
							<span class="timer" data-from="0" data-to="<?=$Lastsettlementamount->Total?>"><?=$Lastsettlementamount->Total?></span>
						</div>
						<div class="card-subtitle">Last settlement amount <br> May 11 2018</div>
					</div>
				</div>
				<div class="card-menu">
					<ul>
						<li><a href="">Today</a></li>
						<li><a href="">7 Days</a></li>
						<li><a href="">14 Days</a></li>
						<li><a href="">Last Month</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Table -->
<section class="datagrid-panel">
	<div class="content">
		<div class="panel">
			<div class="content-header no-mg-top">
				<i class="fa fa-newspaper-o"></i>
				<div class="content-header-title"><?=$title?></div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="content-box">
						<div class="content-box-header">
							<div class="row">
								<div class="col-md-6">
									<button class="btn btn-primary" onclick="pdf()"><i class="fa fa-pdf"></i> Cetak PDF</button>
								</div>
								<div class="col-md-6 form-inline justify-content-end">
									<select class="form-control mb-1 mr-sm-1 mb-sm-0" id="search-option"></select>
									<input class="form-control" id="search" placeholder="Search" type="text">
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="datagrid"></table>
						</div>
						<div class="content-box-footer">
							<div class="row">
								<div class="col-md-2 form-inline">
									<select class="form-control" id="option"></select>
								</div>
								<div class="col-md-4 form-inline" id="info"></div>
								<div class="col-md-6">
									<ul class="pagination pull-right" id="paging"></ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">

	// const formatter = new Intl.NumberFormat('en-IND', {
	// 	 style: 'currency',
	// 	 currency: 'IDR',
	// 	 minimumFractionDigits: 0
	// })

	var datagrid = $("#datagrid").datagrid({
		url						: "<?php echo base_url() . 'reports/data_reports_v6'; ?>",
		primaryField			: 'id', 
		rowNumber				: true,
		searchInputElement 		: '#search', 
		searchFieldElement 		: '#search-option', 
		pagingElement 			: '#paging', 
		optionPagingElement 	: '#option', 
		pageInfoElement 		: '#info',
		columns					: [
		{field: 'student_name', title: 'Nama Peserta Didik', editable: true, sortable: false, width: 250, align: 'left', search: true},
		{field: 'student_nis', title: 'NIS', editable: true, sortable: false, width: 250, align: 'left', search: true},
		{field: 'students_group_name', title: 'Kelas', editable: true, sortable: false, width: 250, align: 'left', search: true},
	    {field: 'total_price_formatted', title: 'Jumlah', sortable: false, width: 250, align: 'right', search: true, 
	  		rowStyler: function(rowData, rowIndex) {
      			return '<span class="badge badge-grey">Rp. ' + new Intl.NumberFormat('in-IN', { maximumSignificantDigits: 3 }).format(rowData.TotalAmount) + '</span>';
	        }
	    }
	],
	rowDetail				: {
		formatter : function(rowData, rowIndex) {
			return row_detail(rowData, rowIndex);
		},
		onExpandRow : function(rowData, rowIndex) {
			var datagrid_detail = $("#datagrid-" + rowIndex).datagrid({
				url						: "<?php echo base_url() . 'reports/detail_reports_v6'; ?>",     
				queryParams 			: { students_id : rowData.students_id },
				primaryField			: 'id',
				rowNumber				: true,
				pagingElement 			: '#paging', 
				optionPagingElement 	: '#option', 
				pageInfoElement 		: '#info',				
				columns					: [
					{field: 'bills_category_name', title: 'Category', editable: true, sortable: false, width: 350, align: 'left', search: true},
					{field: 'name', title: 'Name', editable: true, sortable: false, width: 350, align: 'left', search: true},
	        		{field: 'price_formatted', title: 'Amount', sortable: false, width: 200, align: 'left', search: false, 
	        			rowStyler: function(rowData, rowIndex) {
	        				return '<span class="badge badge-grey">Rp. ' + new Intl.NumberFormat('in-IN', { maximumSignificantDigits: 3 }).format(rowData.amount) + '</span>';
	       				}
	      			},
	      			{field: 'status', title: 'Status', editable: true, sortable: false, width: 350, align: 'left', search: true},
				]
			});

			datagrid_detail.run();
		}
	}
});

	datagrid.run();


	function row_detail(rowData, rowIndex) {
		return  "<div class='table-responsive'>" +
		"<table class='table table-striped' id='datagrid-" + rowIndex + "'></table>" +
		"</div>";
	}

	function pdf(){
		window.open('<?=base_url()?>reports/reports_v6_pdf');
	}


</script>
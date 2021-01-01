<!-- Table -->
<section class="datagrid-panel">
	<div class="content">
		<div class="panel">
			<div class="content-header no-mg-top">
				<i class="fa fa-newspaper-o"></i>
				<div class="content-header-title">Tagihan</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="content-box">
						<div class="content-box-header">
							<div class="row">
								<div class="col-md-12 form-inline justify-content-end">
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

<!-- Form -->
<section class="form-panel"></section>

<script type="text/javascript">

	// const formatter = new Intl.NumberFormat('en-IND', {
	// 	 style: 'currency',
	// 	 currency: 'IDR',
	// 	 minimumFractionDigits: 0
	// })

	var datagrid = $("#datagrid").datagrid({
		url						: "<?php echo base_url() . 'invoices/data'; ?>",
		primaryField			: 'id', 
		rowNumber				: true,
		searchInputElement 		: '#search', 
		searchFieldElement 		: '#search-option', 
		pagingElement 			: '#paging', 
		optionPagingElement 	: '#option', 
		pageInfoElement 		: '#info',
		columns					: [
		{field: 'invoice_number', title: 'Nomor Tagihan', editable: true, sortable: false, width: 180, align: 'left', search: true},
		{field: 'student_name', title: 'Nama Peserta Didik', editable: true, sortable: false, width: 250, align: 'left', search: true},
		{field: 'student_nis', title: 'NIS', editable: true, sortable: false, width: 130, align: 'left', search: true},
		{field: 'transaction', title: 'Status', editable: true, sortable: false, width: 150, align: 'left', search: true},
		{field: 'date', title: 'Tanggal', editable: true, sortable: false, width: 150, align: 'left', search: true},
	    {field: 'total_price_formatted', title: 'Jumlah', sortable: false, width: 150, align: 'right', search: true, 
	  		rowStyler: function(rowData, rowIndex) {

      			return '<span class="badge badge-grey">Rp. ' +  new Intl.NumberFormat('in-IN', { maximumSignificantDigits: 3 }).format(rowData.amount) + '</span>';
	        }
	    },
	    {field: 'menu', title: 'Menu', sortable: false, width: 300, align: 'center', search: false, 
	        rowStyler: function(rowData, rowIndex) {
	       		return menu(rowData, rowIndex);
	      	}
	    }
	],
	rowDetail				: {
		formatter : function(rowData, rowIndex) {
			return row_detail(rowData, rowIndex);
		},
		onExpandRow : function(rowData, rowIndex) {
			var datagrid_detail = $("#datagrid-" + rowIndex).datagrid({
				url						: "<?php echo base_url() . 'invoices/detail'; ?>",     
				queryParams 			: { transaction_id : rowData.id },
				primaryField			: 'id',
				rowNumber				: true,
				columns					: [
					{field: 'bills_name', title: 'Name', editable: true, sortable: false, width: 200, align: 'left', search: true},
	        		{field: 'price_formatted', title: 'Amount', sortable: false, width: 200, align: 'left', search: false, 
	        			rowStyler: function(rowData, rowIndex) {
	        				return '<span class="badge badge-grey">Rp. ' + new Intl.NumberFormat('in-IN', { maximumSignificantDigits: 3 }).format(rowData.bills_amount) + '</span>';
	       				}
	      			}
				]
			});

			datagrid_detail.run();
		}
	}
});

	datagrid.run();

	function menu(rowData, rowIndex) {
		var menu = '<a href="javascript:;" onclick="main_routes(\'update\', ' + rowIndex + ')"><i class="fa fa-pencil"></i> Edit </a>' +
		'<a href="' + '<?php echo base_url() . 'invoices/invoice'; ?>' + '/' + rowData.invoice_number + '"><i class="fa fa-print"></i> Print </a>' +
		'<a href="javascript:;" onclick="delete_action(' + rowIndex + ')"><i class="fa fa-trash-o"></i> Delete </a>'
		return menu
	}

	function row_detail(rowData, rowIndex) {
		return  "<div class='table-responsive'>" +
		"<table class='table table-striped' id='datagrid-" + rowIndex + "'></table>" +
		"</div>";
	}

	function create_update_form(rowIndex) {
		$.post("<?php echo base_url() . 'invoices/form'; ?>", {index : rowIndex}).done(function(data) {
			$('.form-panel').html(data)
		})
	}

	function delete_action(rowIndex) {
		swal({   
			title: "Are you sure want to delete this data?",   
			text: "Deleted data can not be restored!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			cancelButtonText: "Cancel",
			confirmButtonText: "Hapus",
			closeOnConfirm: true 
		}, function() {
			var row = datagrid.getRowData(rowIndex)
			$.post("<?php echo base_url() . 'invoices/delete'; ?>", {id : row.id}).done(function(data) {
				datagrid.reload()
			})
		})
	}

	function main_routes(action, rowIndex) {
		$('.datagrid-panel').fadeOut()
		$('.loading-panel').fadeIn()

		if (action == 'create') {
			create_update_form(rowIndex)
		} else if (action == 'update') {
			create_update_form(rowIndex)
		}
	}

</script>
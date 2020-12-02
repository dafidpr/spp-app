<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Invoice</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="id" class="hidden">
						<div class="form-group">
							<label for=""> Invoice Number</label>
							<input class="form-control" name="invoice_number" placeholder="Invoice Number" type="text" readonly="readonly">
							<div class="validation-message" data-field="invoice_number"></div>
						</div>
						<div class="form-group">
							<label for=""> Student Name</label>
							<input class="form-control" name="student_name" placeholder="Students Name" type="text" readonly="readonly">
							<div class="validation-message" data-field="student_name"></div>
						</div>
						<div class="form-group">
							<label for=""> Student NIS</label>
							<input class="form-control" name="student_nis" placeholder="Students NIS" type="text" readonly="readonly">
							<div class="validation-message" data-field="student_nis"></div>
						</div>
						<div class="form-group">
							<label for=""> Date</label>
							<input class="form-control" name="date" placeholder="date" type="text" readonly="readonly">
							<div class="validation-message" data-field="date"></div>
						</div>
						<div class="form-group">
							<label for=""> Amount</label>
							<input class="form-control" name="amount_indo" placeholder="amount" type="text" readonly="readonly">
							<div class="validation-message" data-field="amount_indo"></div>
						</div>
						<div class="form-group">
							<label for=""> Status</label>
							<select name="transaction" class="form-control">
									<option value="Selesai">Selesai</option>
									<option value="Pending">Pending</option>
									<option value="Deny">Deny</option>
									<option value="Expired">Expired</option>
									<option value="Cancel">Cancel</option>
							</select>
							<div class="validation-message" data-field="transaction"></div>
						</div>
					</form>
					<div class="content-box-footer">
						<button type="button" class="btn btn-primary action" title="cancel" onclick="form_routes('cancel')">Cancel</button>
						<button type="button" class="btn btn-primary action" title="save" onclick="form_routes('save')">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	var onLoad = (function() {
		var index = "<?php echo $index; ?>";
		
		if (index != '') {
			datagrid.formLoad('#form-action', index);
		}

		$('.loading-panel').hide();
		$('.form-panel').show();
	})();

	function validate(formData) {
		var returnData;
		$('#form-action').disable([".action"]);
		$("button[title='save']").html("Validating data, please wait...");
		$.ajax({
			url: "<?php echo base_url() . 'invoices/validate'; ?>", async: false, type: 'POST', data: formData,
			success: function(data, textStatus, jqXHR) {
				returnData = data;
			}
		});

		$('#form-action').enable([".action"]);
		$("button[title='save']").html("Save changes");
		if (returnData != 'success') {
			$('#form-action').enable([".action"]);
			$("button[title='save']").html("Save changes");
			$('.validation-message').html('');
			$('.validation-message').each(function() {
				for (var key in returnData) {
					if ($(this).attr('data-field') == key) {
						$(this).html(returnData[key]);
					}
				}
			});
		} else {
			return 'success';	
		}
	}

	function save(formData) {
		$("button[title='save']").html("Saving data, please wait...");
		$.post("<?php echo base_url() . 'invoices/update'; ?>", formData).done(function(data) {
			$('.datagrid-panel').fadeIn();
			$('.form-panel').fadeOut();
			datagrid.reload();
		});
	}

	function cancel() {
		$('.datagrid-panel').fadeIn();
		$('.form-panel').fadeOut();
	}

	function form_routes(action) {
		if (action == 'save') {
			var formData = $('#form-action').serialize();
			if (validate(formData) == 'success') {
				swal({   
					title: "Please check your data",   
					text: "Saved data can not be restored",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					cancelButtonText: "Cancel",
					confirmButtonText: "Save",
					closeOnConfirm: true 
				}, function() {
					save(formData);
				});
			}
		} else {
			cancel();
		}
	}

</script>
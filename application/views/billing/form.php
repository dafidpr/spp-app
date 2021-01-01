<div class="content">
	<div class="panel">
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="id" class="hidden">
						<input type="text" name="students_id" value="<?=$students_id?>" class="hidden" readonly="readonly">
						<div class="form-group">
							<label for=""> Category</label>
							<select name="bills_category_id" class="form-control">
								<?php foreach ($category as $key => $bills_category) { ?>
									<option value="<?php echo $bills_category->id; ?>"><?php echo $bills_category->bills_category_name; ?></option>
								<?php } ?>
							</select>
							<div class="validation-message" data-field="bills_category_id"></div>
						</div>
						<div class="form-group">
							<label for=""> Name</label>
							<input class="form-control" name="name" placeholder="Name" type="text">
							<div class="validation-message" data-field="name"></div>
						</div>
						<div class="form-group">
							<label for=""> Amount</label>
							<input class="form-control" name="amount" placeholder="Amount" type="text">
							<div class="validation-message" data-field="amount"></div>
						</div>
						<div class="form-group">
							<label for=""> Duedate</label>
							<input class="single-daterange form-control" name="duedate" placeholder="1990-01-01" type="text">
							<div class="validation-message" data-field="duedate"></div>
						</div>
						<div class="form-group">
							<label for=""> Status</label>
							<select name="status" class="form-control">
									<option value="Unpaid">Unpaid</option>
									<option value="Paid">Paid</option>
							</select>
							<div class="validation-message" data-field="status"></div>
						</div>
						<div class="form-group">
							<label for=""> Note</label>
							<input class="form-control" name="note" placeholder="Note" type="text">
							<div class="validation-message" data-field="note"></div>
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
			url: "<?php echo base_url() . 'billing/validate'; ?>", async: false, type: 'POST', data: formData,
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
		$.post("<?php echo base_url() . 'billing/action'; ?>", formData).done(function(data) {
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




<script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/disabler-enabler/disabler.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/disabler-enabler/enabler.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/bootstrap-daterangepicker/daterangepicker.js'; ?>"></script>
<script type="text/javascript">
		// Date picker
	if ($('input.single-daterange').length) {
		$('input.single-daterange').daterangepicker({ "singleDatePicker": true, showDropdowns:true })
	}
</script>
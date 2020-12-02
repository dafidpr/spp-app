<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Kelas</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="id" class="hidden">
						<div class="form-group">
							<label for=""> Nama</label>
							<input class="form-control" name="students_group_name" placeholder="Kelas" type="text">
							<div class="validation-message" data-field="students_group_name"></div>
						</div>
						<div class="form-group">
							<label for=""> Nama Sekolah</label>
							<select name="schools_group_id" class="form-control">
								<?php foreach ($schools as $key => $value) { ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->schools_group_name; ?></option>
								<?php } ?>
							</select>
							<div class="validation-message" data-field="schools_group_id"></div>
						</div>
						<div class="form-group">
							<label for=""> Jenjang</label>
							<select name="students_level_id" class="form-control">
								<?php foreach ($level as $key => $value) { ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->students_level_name; ?></option>
								<?php } ?>
							</select>
							<div class="validation-message" data-field="students_level_id"></div>
						</div>
						<div class="form-group">
							<label for=""> Guru </label>
							<select name="teachers_id" class="form-control">
								<?php foreach ($teachers as $key => $value) { ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->teachers_name; ?></option>
								<?php } ?>
							</select>
							<div class="validation-message" data-field="teachers_id"></div>
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
			url: "<?php echo base_url() . 'students_group/validate'; ?>", async: false, type: 'POST', data: formData,
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
		$.post("<?php echo base_url() . 'students_group/action'; ?>", formData).done(function(data) {
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
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Peserta Didik</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="id" class="hidden">
						<div class="form-group">
							<label for=""> Nama Lengkap</label>
							<input class="form-control" name="student_name" placeholder="Nama Lengkap" type="text">
							<div class="validation-message" data-field="student_name"></div>
						</div>
						<div class="form-group">
							<label for=""> NIS</label>
							<input class="form-control" name="student_nis" placeholder="NIS" type="text">
							<div class="validation-message" data-field="student_nis"></div>
						</div>
						<div class="form-group">
							<label for=""> Kelamin</label>
							<select name="gender" class="form-control">
								<option value="L">Laki-laki</option>
								<option value="P">Perempuan</option>
							</select>
							<div class="validation-message" data-field="gender"></div>
						</div>
						<div class="form-group">
							<label for=""> Tempat Lahir</label>
							<input class="form-control" name="birthplace" placeholder="Tempat Lahir" type="text">
							<div class="validation-message" data-field="birthplace"></div>
						</div>
						<div class="form-group">
							<label for=""> Tanggal Lahir</label>
							<input class="single-daterange form-control" name="birthdate_indo" placeholder="1990-01-01" type="text">
							<div class="validation-message" data-field="birthdate_indo"></div>
						</div>
						<div class="form-group">
							<label for=""> Telp</label>
							<input class="form-control" name="phone_number" placeholder="Telp" type="text">
							<div class="validation-message" data-field="phone_number"></div>
						</div>
						<div class="form-group">
							<label for=""> Email</label>
							<input class="form-control" name="email" placeholder="Email" type="text">
							<div class="validation-message" data-field="email"></div>
						</div>
						<div class="form-group">
							<label for=""> Kelas</label>
							<select name="students_group_id" class="form-control">
							<option value="">- Pilih Kelas -</option>
								<?php foreach ($groups as $key => $group) { ?>
									<option value="<?php echo $group->id; ?>">
										<?php echo get_field($group->schools_group_id,'schools_group','schools_group_name'); ?> | 
										<?php echo get_field($group->students_level_id,'students_level','students_level_name'); ?> | 
										<?php echo $group->students_group_name; ?></option>
								<?php } ?>
							</select>
							<div class="validation-message" data-field="students_group_id"></div>
						</div>
						<div class="form-group">
							<label for=""> Status</label>
							<select name="students_status_id" class="form-control">
								<?php foreach ($status as $key => $row) { ?>
									<option value="<?php echo $row->id; ?>"><?php echo $row->students_status_name; ?></option>
								<?php } ?>
							</select>
							<div class="validation-message" data-field="students_group_id"></div>
						</div>
						<div class="form-group">
							<label for=""> Alamat</label>
							<textarea class="form-control" name="address" placeholder="Alamat"></textarea>
							<div class="validation-message" data-field="address"></div>
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
			url: "<?php echo base_url() . 'students/validate'; ?>", async: false, type: 'POST', data: formData,
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
		$.post("<?php echo base_url() . 'students/action'; ?>", formData).done(function(data) {
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

<script type="text/javascript">
		// Date picker
	if ($('input.single-daterange').length) {
		$('input.single-daterange').daterangepicker({ "singleDatePicker": true, showDropdowns:true })
	}
</script>
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Settings</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<div class="form-group">
							<label for=""> Company Name</label>
							<input class="form-control" name="company_name" placeholder="Company Name" type="text" value="<?php echo $settings[0]->meta_value; ?>">
							<div class="validation-message" data-field="company_name"></div>
						</div>
						<div class="form-group">
							<label for=""> Company Address</label>
							<input class="form-control" name="company_address" placeholder="Company Address" type="text" value="<?php echo $settings[1]->meta_value; ?>">
							<div class="validation-message" data-field="company_address"></div>
						</div>
						<div class="form-group">
							<label for=""> Company Phone Number</label>
							<input class="form-control" name="company_phone_number" placeholder="Company Phone Number" type="text" value="<?php echo $settings[2]->meta_value; ?>">
							<div class="validation-message" data-field="company_phone_number"></div>
						</div>
						<div class="form-group">
							<label for=""> Company Email</label>
							<input class="form-control" name="company_email" placeholder="Company Email" type="text" value="<?php echo $settings[3]->meta_value; ?>">
							<div class="validation-message" data-field="company_email"></div>
						</div>
						<div class="form-group">
							<label for=""> Client Key</label>
							<input class="form-control" name="client_key" placeholder="Client Key" type="text" value="<?php echo $settings[4]->meta_value; ?>">
							<label for=""> Default : VT-client-2SbaV2Iil_nhlYJJ</label>
							<div class="validation-message" data-field="client_key"></div>
						</div>
						<div class="form-group">
							<label for=""> Server Key</label>
							<input class="form-control" name="server_key" placeholder="Server Key" type="text" value="<?php echo $settings[5]->meta_value; ?>">
							<label for=""> Default : VT-server-c8o4oOrv8f9cEI9Mx36qJXiN</label>
							<div class="validation-message" data-field="server_key"></div>
						</div>
					</form>
					<div class="content-box-footer">
						<button type="button" class="btn btn-primary action" title="save" onclick="save()">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	function validate(formData) {
		var returnData;
		$('#form-action').disable([".action"]);
		$("button[title='save']").html("Validating data, please wait...");
		$.ajax({
			url: "<?php echo base_url() . 'settings/validate'; ?>", async: false, type: 'POST', data: formData,
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

	function save() {
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
				$('.validation-message').html('');
				$("button[title='save']").html("Saving data, please wait...");
				$.post("<?php echo base_url() . 'settings/save'; ?>", formData).done(function(data) {
					swal({   
						title: "Success",
						text: "Your profile successfully saved",
						type: "success"
					})

					$('#form-action').enable([".action"]);
					$("button[title='save']").html("Save");
					$('.l-name').html($('[name="name"]').val())
					$('.l-position').html($('[name="group_id"]').find('option:selected').html())
				});
			});
		}
	}

</script>
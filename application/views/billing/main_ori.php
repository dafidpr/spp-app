<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Billings</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">             
						<div class="row">
							<div class="col-md-12">		
								<table class="table">
									<tr>
										<td width="20%" style="color:#763D00;font-weight:bold">Masukan NIS </td>
										<td width="80%">
											<select class="key" id="key" onchange="" style="width: 100%" >
												<option value="" selected="selected">-- Masukan NIS atau Nama --</option>
											</select>
											<input type="hidden" id="iidd" class="iidd" placeholder="Masukan NIS Siswa"></td>
										</tr>
									</table>

									<table class="table">
										<tr>
											<td style="width:20%">
												<b>NIS</b>
											</td>
											<td style="width:20%">
												<b>Nama</b>
											</td>
											<td style="width:20%">
												<b>Kelas</b>
											</td>				

										</tr>							
										<tr>
											<td>
												<p style="color:#763D00;" id="student_nis"><b>..........................</b></p>
											</td>
											<td>
												<p style="color:#763D00;" id="student_name"><b>..........................</b></p>
											</td>
											<td>
												<p style="color:#763D00;" id="students_group_name"><b>..........................</b></p>
											</td>
										</tr>
									</table>
								</div>
							</div> 
						</div>
					</div>
				</div>
			</div> 
		</div>
	</div>


	<div id="konten"></div>			
	<script type="text/javascript">	


		function filter(url) {
			if($("#key").val() == ''){
				alert("Silahkan pilih mahasiswa terlebih dahulu");
			}
			else{
				if(url == null)
					url = "<?=base_url()?>billing/search_bills";

				$.ajax({
					type: "POST",
					url: url,
					data: {
						ID: $("#iidd").val()
					},
					success: function(data) {
						$("#konten").html(data);
					}
				});
				return false;
			}
		}

		function formatMahasiswa (repo) {
			if (repo.loading) return repo.text;

			var markup = '<div class="row"> <div class="col-md-12">' ;
			markup +=  '<span class="thumbnail product hidden-phone pull-left">' +repo.foto +'</span>' ;
			markup += '<span class="s_det_mhs">' ;
			markup += '<h3 style="font-size: 16px; margin: 0px;">' + repo.student_name + '</h3>' ;
			markup += '<p style="margin: 0px; font-size: 14px;">' + repo.student_nis + '</p>' ;
			markup += '<p style="margin: 0px;">' + repo.students_group_name + '</p>' ;
			markup += '</span></div></div>';
			return markup;
		}

		function formatMahasiswaSelection (repo) {
			return repo.text || repo.text;
		}

		$('#key').select2({
			ajax: {
				url: '<?=base_url()?>index.php/students/json_mahasiswa',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
        q: params.term, // search term
        page: params.page
    };
},
processResults: function (data,params) {
	params.page = params.page || 1;
	return {
		results: data.items,
		pagination: {
			more: (params.page * 30) < data.total_count
		}
	};
},
cache: true
},

  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  templateResult: formatMahasiswa, // omitted for brevity, see the source of this page
  templateSelection: formatMahasiswaSelection // omitted for brevity, see the source of this page
}).on("change", function (e) { 
	
	url = "<?=base_url()?>students/biodata";
	$.ajax({
		type: "POST",
		url: url,
		data: {
			key : $(this).val()
		},
		success: function(data) {
			var arrBio = data.split("|");
			$("#iidd").val(arrBio[0]);
			$("#student_nis").html("<p>"+arrBio[1]+"</p>");
			$("#student_name").html("<p>"+arrBio[2]+"</p>");
			$("#students_group_name").html("<p>"+arrBio[3]+"</p>");
			filter();

		}
	});
	return false;
	
	
});
</script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/plugins/jquery/jquery-2.1.1.min.js'; ?>"></script>



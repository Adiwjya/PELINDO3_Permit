<!-- form Uploads -->
<link href="<?php echo base_url(); ?>assets/upload/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

	function save(params) {
        var file_data = $('#data_izin').prop('files')[0];
		var judul = document.getElementById('judul').value;
        var izin = document.getElementById('izin').value;
		
		if (judul == "") {
			Swal.fire(
			'Input tidak Valid!',
			'Masih ada beberapa form yang kosong',
			'error'
			)
		}else{
			var form_data = new FormData();
			form_data.append('file', file_data);
			form_data.append('judul', judul);
			form_data.append('izin', izin);

			$.ajax({
				url: "<?php echo base_url(); ?>i_pengembangan/do_upload/",
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				data: form_data,
				dataType: "JSON",
				success: function(data) {
					// Alert
					if (data.status == "Data Tersimpan") {
						Swal.fire({
						position: 'top-end',
						width: 200,
						icon: 'success',
						title: data.status,
						backdrop: false,
						showConfirmButton: false,
						timer: 2000
						})
						document.getElementById('judul').value = "";
        				// document.getElementById('izin').selectedIndex = 1;
						$('.dropify-clear').click();	
					}else{
						Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: data.status,
						backdrop: false,
						showConfirmButton: false,
						timer: 5000
						})
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert("Error json " + errorThrown);
					Swal.fire(
					'Error json',
					''+errorThrown,
					'question'
					)
				}
			});
		}

        
	}
</script>

<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

<!--begin::Portlet-->
<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Pengajuan Izin Pengembangan
			</h3>
		</div>
	</div>

	<!--begin::Form-->
	<form class="kt-form kt-form--label-right">
		<div class="kt-portlet__body">
			<div class="row">
				<form id="form">
				<div class="col-6">
					<div class="form-group">
						<label>Judul</label>
						<input type="text" class="form-control" name="judul" id="judul" placeholder="Massukan Judul">
						<span class="form-text text-muted">Judul izin yang akan dibuat.</span>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label for="exampleSelect1">Jenis Perizinan</label>
						<select class="form-control" id="izin" name="izin">
							<?php
							foreach ($jenis_perizinan->result() as $row) {
							?>
							<option value="<?php echo $row->ID_PERIZINAN; ?>"><?php echo $row->PERIZINAN; ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
			</div>

			<div class="col-12">
				<div class="card-box">
					<label class="col-form-label">Lampirakan File</label>
					<input type="file" class="dropify" id="data_izin" name="data_izin" data-height="300" />
				</div>
			</div>
			</form>
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<div class="row">
					<div class="col-lg-12 ml-lg-auto">
						<button type="button" onclick="save();" class="btn btn-success">Submit</button>
						<button type="button" class="btn btn-secondary">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	<!--end::Form-->
</div>

<!--end::Portlet-->
</div>
<!-- end:: Content -->

 <!-- jQuery  -->
 <script src="<?php echo base_url(); ?>assets/upload/assets/js/jquery.min.js"></script>

<!-- file uploads js -->
<script src="<?php echo base_url(); ?>assets/upload/plugins/fileuploads/js/dropify.min.js"></script>
<script>
	$('.dropify').dropify({
		messages: {
			'default': 'Drag and drop a file here or click',
			'replace': 'Drag and drop or click to replace',
			'remove': 'Remove',
			'error': 'Ooops, something wrong appended.'
		},
		error: {
			'fileSize': 'The file size is too big (1M max).'
		}
	});
</script>


					

		
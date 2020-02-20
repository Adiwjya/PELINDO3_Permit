<!-- form Uploads -->
<link href="<?php echo base_url(); ?>assets/upload/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

<!--begin::Portlet-->
<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Pengajuan Izin Reklamasi
			</h3>
		</div>
	</div>

	<!--begin::Form-->
	<form class="kt-form kt-form--label-right">
		<div class="kt-portlet__body">
			<div class="row">
				<div class="col-6">
					<div class="form-group">
						<label>Judul</label>
						<input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Massukan Judul">
						<span class="form-text text-muted">Judul izin yang akan dibuat.</span>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label for="exampleSelect1">Jenis Perizinan</label>
						<select class="form-control" id="exampleSelect1">
							<option>Izin 1</option>
							<option>Izin 2</option>
							<option>Izin 3</option>
							<option>Izin 4</option>
							<option>Izin 5</option>
						</select>
					</div>
				</div>
			</div>

			<div class="col-12">
				<div class="card-box">
					<label class="col-form-label">Lampirakan File</label>
					<input type="file" class="dropify" data-height="300" />
				</div>
			</div>
			
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<div class="row">
					<div class="col-lg-12 ml-lg-auto">
						<button type="reset" class="btn btn-success">Submit</button>
						<button type="reset" class="btn btn-secondary">Cancel</button>
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

					

		
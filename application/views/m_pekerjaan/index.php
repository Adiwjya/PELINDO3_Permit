
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

<!--begin::Portlet-->
<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Mengajukan Pekerjaan
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
						<label for="exampleSelect1">Lokasi</label>
						<select class="form-control" id="exampleSelect1">
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

			<!-- <div class="col-12">
			<div class="form-group row">
				<label class="col-form-label">Lampirakan File</label>
				<div class="col-sm-12">
					<div class="kt-dropzone dropzone" action="#" id="m-dropzone-one">
						<div class="kt-dropzone__msg dz-message needsclick">
							<h3 class="kt-dropzone__msg-title">Drop files here or click to upload.</h3>
							<span class="kt-dropzone__msg-desc">This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.</span>
						</div>
					</div>
				</div>
			</div>
			</div> -->
			
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
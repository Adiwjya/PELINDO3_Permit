<!-- form Uploads -->
<link href="<?php echo base_url(); ?>assets/upload/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
var idiz = "<?php echo $id_pengajuan;?>";
	$(document).ready(function() {

		$("#select_page").html("Mengajukan Pekerjaan");
		$("#menu_location").html("General");
		$("#menu_location_detail").html("Mengajukan Pekerjaan");
	});

	function alert_form_validation() {
		Swal.fire(
		'Input tidak Valid!',
		'Masih ada beberapa form yang kosong',
		'error'
		)
	}

	function save(params) {
		var id_pengajuan = document.getElementById('id_pengajuan').value;
		var judul = document.getElementById('judul').value;
        var lokasi = document.getElementById('lokasi').value;
		
		if (judul == "") {
			alert_form_validation();
		}else if (lokasi == ""){
            alert_form_validation();
		}else{
			add();
		}

		function add(){
			var form_data = new FormData();
			form_data.append('id_pengajuan', id_pengajuan);
			form_data.append('judul', judul);
			form_data.append('lokasi', lokasi);

			$.ajax({
				url: "<?php echo base_url(); ?>m_pekerjaan/do_upload/",
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				data: form_data,
				dataType: "JSON",
				success: function(data) {
					if (data.status == "Data Tersimpan") {
						// Alert
						Swal.fire({
						position: 'top-end',
						width: 200,
						padding_top: 300,
						icon: 'success',
						title: data.status,
						backdrop: false,
						showConfirmButton: false,
						timer: 2500
						})
						// Reset Form
						document.getElementById('id_pengajuan').value = "";
						document.getElementById('judul').value = "";
                        document.getElementById('lokasi').value = "";

						$('.dropify-clear').click();
						window.location.href = "<?php echo base_url(); ?>m_pekerjaan";
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
					// alert("Error json " + errorThrown);
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
				Pengajuan Pekerjaan
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
						<input type="hidden" class="form-control" name="id_pengajuan" id="id_pengajuan" value="<?php echo $id_pengajuan; ?>" placeholder="Massukan id_pengajuan">
						<input type="text" class="form-control" name="judul" id="judul" value="<?php echo $judul; ?>" placeholder="Massukan Judul">
						<span class="form-text text-muted">Judul izin yang akan dibuat.</span>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label for="exampleSelect1">Lokasi</label>
						<input type="text" class="form-control" name="lokasi" id="lokasi" value="<?php echo $lokasi; ?>" placeholder="Massukan Lokasi">
					</div>
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


					

		
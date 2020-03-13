<link href="<?php echo base_url(); ?>assets/upload/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
	// DataTable
	var table;
	$(document).ready(function() {
		table = $('#tb').DataTable( {
			ajax: "<?php echo base_url(); ?>pemilik_data/ajax_list",
			aaSorting: [[2, 'desc']]
		});
		$("#select_page").html("Pemilik Data (sementara)");
		$("#menu_location").html("Perencanaan");
		$("#menu_location_detail").html("Pemilik Data");
		// $('#deskripsi').val($('#v_menu1').text());
		$('#count').val(0);
	});

	function reload(){
        table.ajax.reload(null,false); //reload datatable ajax
    }

	function i_data(id, data){
        $('#i_mo').modal('show'); // show bootstrap modal 
		$('#r_d').text('Kebutuhan Data : '+data);
    }

	
</script>
<!-- begin:: Content -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content"  >
	
	<div class="kt-portlet">
		<div class="row">
			<div class="col-12">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title mr">
							Data Request
						</h3>
					</div>
				</div>
			</div>
		</div>

		<a href="#" class="btn btn-outline-light btn-pill btn-sm btn-icon btn-icon-md">
			<i class="flaticon2-lock"></i>
		</a>
		<form id="f_csrf">
		<input type="hidden" class="txt_csrfname_2" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">		
		</form>
		
		<div class="container-fluid" >
			<table id="tb" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<!-- <th>Id Pengajuan</th> -->
						<th>Permintaan Data</th>
						<th>Created At</th>
						<th>Created By</th>
						<th>Status</th>
						<!-- <th>Request Izin</th> -->
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

	</div>
</div>
<!-- end:: Content -->

<!--begin::Modal-->
<div class="modal fade" id="i_mo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="i_mo_tittle">Kirimkan File</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<h4 id="r_d"></h4>
				<label class="col-form-label">Lampirakan File</label>
				<input type="file" class="dropify" id="data_izin" name="data_izin" data-height="100" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" onclick="send_response();" class="btn btn-primary">Send Response</button>
			</div>
		</div>
	</div>
</div>

<!--end::Modal-->

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


					

		
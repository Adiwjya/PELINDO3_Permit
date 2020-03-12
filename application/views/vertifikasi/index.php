<link href="<?php echo base_url(); ?>assets/upload/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
	// DataTable
	var table;
	$(document).ready(function() {
		table = $('#tb').DataTable( {
			ajax: "<?php echo base_url(); ?>vertifikasi_izin/ajax_list",
			aaSorting: [[2, 'desc']]
		});
		$("#select_page").html("Vertivikasi Izin");
		$("#menu_location").html("Perencanaan");
		$("#menu_location_detail").html("Vertifikasi Izin");
		// $('#deskripsi').val($('#v_menu1').text());
		$('#count').val(0);
	});

	function reload(){
        table.ajax.reload(null,false); //reload datatable ajax
    }

    function v_tidak(id, nama){
        $('#v_no').modal('show'); // show bootstrap modal
		$('#v_no_name1').text(nama); 
		$('#v_no_name2').text(nama); 
		$('#v_no_name3').text(nama); 
		$('#v_no_name4').text(nama); 
		$('#v_id').val(id); 
    }

	function v_change(id){ 
		$('#status').val(id); 
		if (id == "1") {
			var desc = $('#v_menu1').text();
			$('#deskripsi').val(desc);
		}else if (id == "2") {
			var desc = $('#v_menu2').text();
			$('#deskripsi').val(desc);
		}else if (id == "3") {
			var desc = $('#v_menu3').text();
			$('#deskripsi').val(desc);
		}else{
			var desc = $('#v_menu4').text();
			$('#deskripsi').val(desc);
		}	
    }

	function view(dataz,id){
		$.ajax({
            url : "<?php echo base_url(); ?>vertifikasi_izin/status_ch/"+id,
            type: "POST",
            dataType: "JSON",
            data: $('#f_csrf').serialize(),
            success: function(data){
                $('.txt_csrfname_2').val(data.status.token);
                $('.txt_csrfname').val(data.status.token);
                window.open('<?php echo base_url(); ?>Data_izin/'+dataz, '_blank');
                // window.location.href = "<?php echo base_url(); ?>Data_izin/"+id;
            },
            error: function (jqXHR, textStatus, errorThrown){
                Swal.fire(
                'Error json',
                ''+errorThrown,
                'question'
                )
            }
        });
    }

	function send_response() {

		var file_data = $('#data_izin').prop('files')[0];
		var csrfName = $('.txt_csrfname').attr('name'); // Value specified in $config['csrf_token_name']
        var csrfHash = $('.txt_csrfname').val(); // CSRF hash
		var v_id = $('#v_id').val(); // CSRF hash
		var deskripsi = $('#deskripsi').val(); 
		var status = $('#status').val();
		
		// Fungsi khusus menu memerlukan data

		var data_mi = $('#data_thread1').val();
		var destination_mi = $('#destination_thread1').val();
		for (let index = 1; index <= parseInt($('#count').val()); index++) {
			 if ( $('#data_thread'+index).val() != "") {
				data_mi = data_mi+$('#data_thread'+index).val()+", ";
				destination_mi = destination_mi+$('#destination_thread'+index).val()+", ";
			 }else{
				data_mi = "";
				destination_mi = "";
			 }
		}
		if (data_mi == null) {
			data_mi = "";
			destination_mi = "";
		}

		if (status == "") {
			Swal.fire(
                'Vartivikasi tidak valid!',
                'Alasan pembatalan izin belum dipilih.',
                'question'
                )
		}else{
			var form_data = new FormData();
			form_data.append(csrfName, csrfHash);
			form_data.append('file', file_data);
			form_data.append('v_id', v_id);
			form_data.append('deskripsi', deskripsi);
			form_data.append('status', status);
			form_data.append('data_mi', data_mi);
			form_data.append('destination_mi', destination_mi);

			// alert("Jalan4");
			$.ajax({
				url: "<?php echo base_url(); ?>vertifikasi_izin/save_vertification",
				cache: false,
				contentType: false,
				processData: false,
				type: "POST",
				data: form_data,
				dataType: "JSON",
				success: function(data) {
					if (data.status.message == "Data Tersimpan") {
						$('#form')[0].reset(); // reset form on modals
						// alert("Jalan5");
						// Update CSRF hash
						$('.txt_csrfname').val(data.status.token);
						Swal.fire(
						'Success',
						'Vertifikasi Izin Berhasil',
						'success'
						)
						$('#v_no').modal('hide');
						reload();
					}else{
						$('#form')[0].reset(); // reset form on modals
						// Update CSRF hash
						$('.txt_csrfname').val(data.status.token);
						Swal.fire({
							position: 'top-end',
							icon: 'error',
							title: data.status.message,
							backdrop: false,
							showConfirmButton: false,
							timer: 5000
							})
					}
					
				},
				error: function(jqXHR, textStatus, errorThrown) {
				// alert("Username atau password anda salah " + errorThrown);
						$('#form')[0].reset(); // reset form on modals
						// Update CSRF hash
						$('.txt_csrfname').val(data.status.token);
					Swal.fire(
					'Error json',
					''+errorThrown,
					'question'
					)
				}
			});
		}
	}

	function new_thread(params) {
		var acc = parseInt($('#count').val()) + 1;
		var oc = "delete_thread('list_thread"+acc+"','data_thread"+acc+"','destination_thread"+acc+"')";
		$('#count').val(acc);
		$('#content_thread').html($('#content_thread').html()+''
			+'<div id="list_thread'+acc+'">'
				+'<div class="tab-pane active" id="kt_widget2_tab1_content">'
					+'<div class="kt-widget2">'
						+'<div class="kt-widget2__item kt-widget2__item--primary">'
							+'<div class="kt-widget2__checkbox"></div>'
							+'<div class="kt-widget2__info">'
								+'<a href="#" class="kt-widget2__title">'
									+$('#new_izin').val() // tulisannya judul
								+'</a>'
								+'<a href="#" class="kt-widget2__username">'
									+'Divisi : '+$('#divisi_tujuan').val() // tulisannya divisi
								+'</a>'
							+'</div>'
							+'<div class="kt-widget2__actions">'
								+'<a href="javascript:void(0)" onclick="'+oc+'" class="btn btn-clean btn-sm btn-icon btn-icon-md">'
									+'<i class="flaticon2-delete"></i>'
								+'</a>'
							+'</div>'
						+'</div>'
					+'</div>'
				+'</div>'
			+'</div>'
		+'<input type="hidden" id="data_thread'+acc+'" name="data_thread'+acc+'" value="'+$('#new_izin').val()+'">'
		+'<input type="hidden" id="destination_thread'+acc+'" name="destination_thread'+acc+'" value="'+$('#divisi_tujuan').val()+'">'
	);

			$('#new_izin').val("")
			$('#divisi_tujuan').val("")
	}

	function delete_thread(id,data,destination) {
		$('#'+id).html("");
		$('#'+data).val("");
		$('#'+destination).val("");
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
							Data Pengajuan Izin Pengembangan
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
						<th>Judul Perizinan</th>
						<th>Jenis Izin</th>
						<th>Created At</th>
						<th>Created By</th>	
						<th>Status</th>	
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
<div class="modal fade" id="v_no" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="v_no_tittle">Pembatalan Pengajuan Izin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<form id="form" >
				<input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
				<!--begin:: Widgets/Finance Stats-->
				<input type="hidden" name="v_id" id="v_id">
				<div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid" style="margin-bottom: unset;">
					<div class="kt-portlet__body" style="margin-top: unset;">
						<div class="kt-widget28">
							<div class="kt-widget28__visual" style=" min-height: 150px; background-image: url('<?php echo base_url(); ?>assets/assets/media//misc/bg-2.jpg')"></div>
							<div class="kt-widget28__wrapper kt-portlet__space-x">

								<!-- begin::Nav pills -->
								<ul class="nav nav-pills nav-fill kt-portlet__space-x" role="tablist">
									<li class="nav-item">
										<a class="nav-link" onclick="v_change('1');" data-toggle="pill" href="#menu1"><span><i class="flaticon2-list-2"></i><br></span><span>Memerlukan Izin</span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="v_change('2');" data-toggle="pill" href="#menu2"><span><i class="flaticon2-information"></i></span><span>Izin Belum Ada</span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="v_change('3');" data-toggle="pill" href="#menu3"><span><i class="flaticon2-shield"></i></span><span>Tidak Memerlukan Izin</span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="v_change('4');" data-toggle="pill" href="#menu4"><span><i class="flaticon2-copy"></i></span><span>Izin Sudah Ada</span></a>
									</li>
								</ul>

								<!-- end::Nav pills -->

								<!-- begin::Tab Content -->
								<div class="tab-content">
									<div id="menu0" class="tab-pane active">
										<div class="kt-widget28__tab-items">
											<div class="kt-widget28__tab-item">
												<span>Description</span>
												<span id="v_menu0">Pilih Alasan Pembatalan.</span>
											</div>
										</div>
									</div>
									<div id="menu1" class="tab-pane fade">
										
										<div class="kt-widget28__tab-items">
											<div class="kt-widget28__tab-item">
												<span>Description</span>
												<span id="v_menu1">Proses pengajuan <strong id="v_no_name1"></strong> dapat diproses tapi memerlukan izin lebih lanjut.</span><br>
												<span>Description</span>
												<div class="row">
													<div class="col-7">
														<input type="text" class="form-control" name="new_izin" id="new_izin" placeholder="Izin yang dibutuhkan">
													</div>
													<div class="col-4">
														<input type="text" class="form-control" name="divisi_tujuan" id="divisi_tujuan" placeholder="Divisi tujuan">
													</div>
													<div class="col-1">
														<button class="btn btn-success" type="button" onclick="new_thread();"><i class="fa fa-check" style="padding-right: unset;" ></i></button>
														<input type="hidden" id="count">
													</div>
												</div>
												
												
												<!--begin:: Widgets/Tasks -->
												<div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
													<div class="">
														
														<div class="tab-content" id="content_thread">

															
															
														</div>
													
													</div>
												</div>

												<!--end:: Widgets/Tasks -->
											</div>
										</div>
										<input type="hidden" id="data_mi_result" name="data_mi_result">
									</div>
									<div id="menu2" class="tab-pane fade">
										<div class="kt-widget28__tab-items">
											<div class="kt-widget28__tab-item">
												<span>Description</span>
												<span id="v_menu2">Proses pengajuan <strong id="v_no_name2"></strong> dapat diproses dikarenakan belum ada izin yang tersedia.</span>
											</div>
										</div>
									</div>
									<div id="menu3" class="tab-pane fade">
										<div class="kt-widget28__tab-items">
											<div class="kt-widget28__tab-item">
												<span>Description</span>
												<span id="v_menu3">Pembatalan pengajuan <strong id="v_no_name3"></strong> dikarenakan pengajuan yang bersangkutan tidak memerlukan izin.</span>
											</div>
										</div>
									</div>
									<div id="menu4" class="tab-pane fade">
										<div class="kt-widget28__tab-items">
											<div class="kt-widget28__tab-item">
												<span>Description</span>
												<span id="v_menu4">Pembatalan pengajuan <strong id="v_no_name4"></strong> dikarenakan pengajuan yang bersangkutan sudah pernah diajukan.</span>
												<label class="col-form-label">Lampirakan File</label>
												<input type="file" class="dropify" id="data_izin" name="data_izin" data-height="100" />
											</div>
										</div>
									</div>
									<input type="hidden" name="deskripsi" id="deskripsi">
									<input type="hidden" name="status" id="status">
								</div>

								<!-- end::Tab Content -->
							</div>
						</div>
					</div>
				</div>
				</form>
				<!--end:: Widgets/Finance Stats-->
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


					

		
<?php
if (get_cookie('hak_akses') == "PIC") {$data_list = "ajax_list_pic";} else {$data_list = "ajax_list";}
?>
<script type="text/javascript">
	// DataTable
	var table;
	$(document).ready(function() {
		table = $('#tb').DataTable( {
			ajax: "<?php echo base_url(); ?>i_pengembangan/<?php echo $data_list; ?>"
		});
		$("#select_page").html("Izin Pengembangan");
		$("#menu_location").html("Perencanaan");
		$("#menu_location_detail").html("Izin Pengembangan");
		$('#deskripsi').val($('#v_menu1').text());
		$('#status').val("1");
	});

	function reload(){
        table.ajax.reload(null,false); //reload datatable ajax
    }

	function tambah(){
        window.location.href = "<?php echo base_url(); ?>i_pengembangan/new_add";
    }
	
	function ganti(id){
        window.location.href = "<?php echo base_url(); ?>i_pengembangan/new_add/"+id;
    }

	function unduh(id){
		window.open('<?php echo base_url(); ?>Data_izin/'+id, '_blank');
		// window.location.href = "<?php echo base_url(); ?>Data_izin/"+id;
    }

	function hapus(id, nama){
        if(confirm("Apakah anda yakin menghapus " + nama + " ?")){
            $.ajax({
                url : "<?php echo base_url(); ?>i_pengembangan/hapus/" + id,
                type: "POST",
                dataType: "JSON",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                success: function(data){
                    reload();
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
    }

	// Vertifikasi Section
	function v_tidak(id, nama){
        $('#v_no').modal('show'); // show bootstrap modal
		$('#v_no_name').text(nama); 
		$('#v_no_name2').text(nama); 
		$('#v_id_pengembangan').val(id); 
    }

	function v_change(id){ 
		$('#status').val(id); 
		if (id == "1") {
			var desc = $('#v_menu1').text();
			$('#deskripsi').val(desc);
		}else{
			var desc = $('#v_menu2').text();
			$('#deskripsi').val(desc);
		}
		
    }

	function send_response() {
		$.ajax({
			url: "<?php echo base_url(); ?>i_pengembangan/save_vertification",
			type: "POST",
			data: $('#form').serialize(),
			dataType: "JSON",
			success: function(data) {
				if (data.status.message == "Data Tersimpan") {
					$('#form')[0].reset(); // reset form on modals
					// Update CSRF hash
					$('.txt_csrfname').val(data.status.token);
					Swal.fire(
					'Error json',
					'asdasdsad',
					'success'
					)
					$('#v_no').modal('hide');
					reload();
					// window.location.href = "<?php echo base_url(); ?>i_pengembangan"
				}else{
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
				Swal.fire(
				'Error json',
				''+errorThrown,
				'question'
				)
			}
		});
	}
	
</script>

<!-- begin:: Content -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	
	<div class="kt-portlet">
		<div class="row">
			<div class="col-12">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title mr">
							Data Pengajuan Izin Pengembangan
						</h3>
					</div>

					<?php
						if (get_cookie('hak_akses') == "Administrator") {
					?>
					<div class="kt-portlet__head-label">
						<div  data-toggle="kt-tooltip" title="Reload Datatable" data-placement="bottom">
							<a style="margin-right: 5px;" onclick="reload();" class="btn btn-outline-secondary waves-effect waves-light" href="javascript:void(0)"><i class="flaticon2-reload" style="padding-right: unset;"></i> &nbsp; Reload</a>
						</div>
						<div  data-toggle="kt-tooltip" onclick="tambah();" title="Ajukan Izin Baru" data-placement="bottom">
							<a   class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"><i class="flaticon2-plus" style="padding-right: unset;"></i>&nbsp; Mengajukan Izin</a>
						</div>
					</div>
					<?php
						}
					?>

				</div>
			</div>
		</div>

		<a href="#" class="btn btn-outline-light btn-pill btn-sm btn-icon btn-icon-md">
			<i class="flaticon2-lock"></i>
		</a>
		
		<div class="container-fluid" >
			<table id="tb" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<!-- <th>Id Pengajuan</th> -->
						<th>Judul Perizinan</th>
						<th>Jenis Izin</th>
						<th>Created At</th>
						<th>Created By</th>
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
	<div class="modal-dialog" role="document">
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
				<input type="hidden" name="v_id_pengembangan" id="v_id_pengembangan">
				<div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">
					<div class="kt-portlet__body" style="margin-top: unset;">
						<div class="kt-widget28">
							<div class="kt-widget28__visual" style=" min-height: 150px; background-image: url('<?php echo base_url(); ?>assets/assets/media//misc/bg-2.jpg')"></div>
							<div class="kt-widget28__wrapper kt-portlet__space-x">

								<!-- begin::Nav pills -->
								<ul class="nav nav-pills nav-fill kt-portlet__space-x" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" onclick="v_change('1');" data-toggle="pill" href="#menu11"><span><i class="flaticon2-shield"></i></span><span>Tidak Memerlukan Izin</span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="v_change('2');" data-toggle="pill" href="#menu21"><span><i class="flaticon2-copy"></i></span><span>Izin Sudah Ada</span></a>
									</li>
								</ul>

								<!-- end::Nav pills -->

								<!-- begin::Tab Content -->
								<div class="tab-content">
									<div id="menu11" class="tab-pane active">
										<div class="kt-widget28__tab-items">
											<div class="kt-widget28__tab-item">
												<span>Description</span>
												<span id="v_menu1">Pembatalan pengajuan <strong id="v_no_name"></strong> dikarenakan pengajuan yang bersangkutan tidak memerlukan izin.</span>
											</div>
										</div>
									</div>
									<div id="menu21" class="tab-pane fade">
										<div class="kt-widget28__tab-items">
											<div class="kt-widget28__tab-item">
												<span>Description</span>
												<span id="v_menu2">Pembatalan pengajuan <strong id="v_no_name2"></strong> dikarenakan pengajuan yang bersangkutan sudah pernah diajukan.</span>
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


					

		
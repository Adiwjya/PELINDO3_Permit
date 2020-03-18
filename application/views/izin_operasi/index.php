<script type="text/javascript">
	// DataTable
	var table;
	$(document).ready(function() {
		table = $('#tb').DataTable( {
			ajax: "<?php echo base_url(); ?>i_operasi/ajax_list",
			aaSorting: [[2, 'desc']]
		});
		$("#select_page").html("Izin Operasi");
		$("#menu_location").html("Perencanaan");
		$("#menu_location_detail").html("Izin Operasi");
	});

	function reload(){
        table.ajax.reload(null,false); //reload datatable ajax
    }

	function tambah(){
        window.location.href = "<?php echo base_url(); ?>i_operasi/new_add";
    }
	
	function ganti(id){
        window.location.href = "<?php echo base_url(); ?>i_operasi/new_add/"+id;
    }

	function unduh(id){
		window.open('<?php echo base_url(); ?>Data_izin/'+id, '_blank');
		// window.location.href = "<?php echo base_url(); ?>Data_izin/"+id;
    }

	function response_file_dl(){
		var dock = $("#dokumen").val();
		window.open('<?php echo base_url(); ?>Data_izin/'+dock, '_blank');
		// window.location.href = "<?php echo base_url(); ?>Data_izin/"+id;
    }

	function response(id) {
		$('#v_no').modal('show'); // show bootstrap modal
		// alert(id);
		$.ajax({
            url : "<?php echo base_url(); ?>i_operasi/load_response/" + id,
            type: "POST",
            dataType: "JSON",
			data: $('#f_csrf').serialize(),
            success: function(data){
				$('.txt_csrfname_2').val(data.status.token);

                $('#desc').text(data.status.dataa.DESKRIPSI);
				$('#stat').val(data.status.dataa.STATUS);
				$('#dokumen').val(data.status.dataa.RESPON_DATA);
				var stat = data.status.dataa.STATUS;
				if (stat == 4) {
					$('#response_file').show();
				}else{
					$('#response_file').hide();
				}
				
            },error: function (jqXHR, textStatus, errorThrown){
                Swal.fire(
					'Error json',
					''+errorThrown,
					'question'
					)
            }
        });
	}

	function hapus(id, nama){
        if(confirm("Apakah anda yakin menghapus " + nama + " ?")){
            $.ajax({
                url : "<?php echo base_url(); ?>i_operasi/hapus/" + id,
                type: "POST",
                dataType: "JSON",
				data: $('#f_csrf').serialize(),
                success: function(data){
					$('.txt_csrfname_2').val(data.status.token);
					
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

</script>

<!-- begin:: Content -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	
	<div class="kt-portlet">
		<div class="row">
			<div class="col-12">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title mr">
							Data Pengajuan Izin Operasi
						</h3>
					</div>
					<div class="kt-portlet__head-label">
						<div  data-toggle="kt-tooltip" title="Reload Datatable" data-placement="bottom">
							<a style="margin-right: 5px;" onclick="reload();" class="btn btn-outline-secondary waves-effect waves-light" href="javascript:void(0)"><i class="flaticon2-reload" style="padding-right: unset;"></i> &nbsp; Reload</a>
						</div>
						<div  data-toggle="kt-tooltip" onclick="tambah();" title="Ajukan Izin Baru" data-placement="bottom">
							<a   class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"><i class="flaticon2-plus" style="padding-right: unset;"></i>&nbsp; Mengajukan Izin</a>
						</div>
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
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="v_no_tittle">Status Izin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<input id="stat" type="hidden">
				<input id="dokumen" type="hidden">
				<span id="desc" name="desc" >Deskripsi</span><br>
				<?php
				?>
				<center style="margin-top:20px;" ><a title="View File" onclick="response_file_dl();" id="response_file" style="width:40%;" class="btn btn-success waves-effect waves-light" href="javascript:void(0)"  ><i class="flaticon2-file" style="padding-right: unset;"></i> Download File</a></center> 
				<?php
				?>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!--end::Modal-->


					

		
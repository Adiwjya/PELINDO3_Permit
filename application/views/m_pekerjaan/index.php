<script type="text/javascript">
	// DataTable
	var table;
	$(document).ready(function() {
		table = $('#tb').DataTable( {
			ajax: "<?php echo base_url(); ?>m_pekerjaan/ajax_list"
		});
		$("#select_page").html("Mengajukan Pekerjaan");
		$("#menu_location").html("General");
		$("#menu_location_detail").html("Mengajukan Pekerjaan");
	});

	function reload(){
        table.ajax.reload(null,false); //reload datatable ajax
    }

	function tambah(){
        window.location.href = "<?php echo base_url(); ?>m_pekerjaan/new_add";
    }
	
	function ganti(id){
        window.location.href = "<?php echo base_url(); ?>m_pekerjaan/new_add/"+id;
    }

	function unduh(id){
        window.location.href = "<?php echo base_url(); ?>Data_izin/"+id;
    }

	function hapus(id, nama){
        if(confirm("Apakah anda yakin menghapus " + nama + " ?")){
            // ajax delete data to database
            $.ajax({
                url : "<?php echo base_url(); ?>m_pekerjaan/hapus/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data){
                   
                    reload();
                },
                error: function (jqXHR, textStatus, errorThrown){
                    alert('Error hapus data');
                }
            });
        }
    }
	
</script>

<!-- begin:: Content -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	
	<div class="kt-portlet">
		<div class="row">
			<div class="col-7">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Data Mengajukan Pekerjaan
						</h3>
					</div>
				</div>
			</div>
			<div class="col-5">
				<div class="kt-portlet__head" style="margin-left: 70px;">
					<div class="kt-portlet__head-label">
						<div  data-toggle="kt-tooltip" title="Reload Datatable" data-placement="bottom">
							<a style="margin-right: 5px;" onclick="reload();" class="btn btn-outline-secondary waves-effect waves-light" href="javascript:void(0)"><i class="flaticon2-reload" style="padding-right: unset;"></i> &nbsp; Reload</a>
						</div>
						<div  data-toggle="kt-tooltip" onclick="tambah();" title="Ajukan Pekerjaan Baru" data-placement="bottom">
							<a   class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"><i class="flaticon2-plus" style="padding-right: unset;"></i>&nbsp; Mengajukan Pekerjaan</a>
						</div>
					</div>
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
						<th>Lokasi</th>
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


					

		
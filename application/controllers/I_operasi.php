<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class I_operasi extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
        parent::__construct();
        $this->load->library('Modul');
		$this->load->model('Mglobals');
		$this->load->helper('cookie');
		
	}
	
	public function index(){
		if (get_cookie('status') == "login") {
			
			$this->load->view('head');
			$this->load->view('menu');
			$this->load->view('izin_operasi/index');
			$this->load->view('fitur');
			$this->load->view('footer');
		}else{
			$this->modul->halaman('login');
		}
	}


	// Bagian Pengajuan 

	public function ajax_list() {
        if (get_cookie('status') == "login") {
			$data = array();
            $list = $this->Mglobals->getAllQ("select * from PENGAJUAN_IZIN_OPERASI where DELETE_STATUS = '0'");
            foreach ($list->result() as $row) {
                $val = array();
                // $val[] = $row->ID_PENGAJUAN;
                $val[] = $row->JUDUL_PERIZINAN;
				$jenis_izin = $this->Mglobals->getAllQR("select PERIZINAN from JENIS_IZIN WHERE id_perizinan = '".$row->ID_PERIZINAN."'");
                $val[] = $jenis_izin->PERIZINAN;
                $val[] = $row->CREATED_AT;
				$val[] = $row->CREATED_NAME;
				if ($row->PROGRES_STATUS == 0) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--dark kt-badge--inline kt-badge--pill kt-badge--rounded">pending</span>'
						. '</div>';

					$val[] = '<div style="text-align: center;">'
					. '<a  title="Download File" class="btn btn-outline-success waves-effect waves-light" href="javascript:void(0)" onclick="unduh('."'".$row->DATA_PERIZINAN."'".')" ><i class="flaticon2-download" style="padding-right: unset;"></i></a>&nbsp;'
					. '<a  title="Edit" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="ganti('."'".$this->modul->enkrip_url($row->ID_PENGAJUAN)."'".')"><i class="flaticon2-edit" style="padding-right: unset;"></i></a>&nbsp;'
					. '<a  title="Delete" class="btn btn-outline-danger waves-effect waves-light" href="javascript:void(0)" onclick="hapus('."'".$row->ID_PENGAJUAN."'".','."'".$row->JUDUL_PERIZINAN."'".')"><i class="flaticon2-trash" style="padding-right: unset;"></i></a>'
					. '</div>';
				}else if ($row->PROGRES_STATUS == 1){
					$new_request_data = $this->Mglobals->getAllQR("select COUNT(REQUEST_DATA) as REQUEST_DATA, COUNT(RESPONSE_REQUEST_DATA) as RESPONSE_REQUEST_DATA from VERTIFIKASI_DETAIL where VERTIFIKASI_ID = '".$row->VERTIFIKASI_ID."'");
					if ($new_request_data -> REQUEST_DATA > 0 and $new_request_data -> RESPONSE_REQUEST_DATA < $new_request_data -> REQUEST_DATA) {
						$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">in process</span><br>'
						. '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">new request data</span>'
						. '</div>';

						$val[] = '<div style="text-align: center;">'
						. '<a  title="Download File" class="btn btn-outline-success waves-effect waves-light" href="javascript:void(0)" onclick="unduh('."'".$row->DATA_PERIZINAN."'".')" ><i class="flaticon2-download" style="padding-right: unset;"></i></a>&nbsp;'
						. '<a  title="Edit" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="ganti('."'".$this->modul->enkrip_url($row->ID_PENGAJUAN)."'".')"><i class="flaticon2-edit" style="padding-right: unset;"></i></a>&nbsp;'
						. '<a  title="Upload Request Data" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="on_process('."'".$row->VERTIFIKASI_ID."'".')"><i class="flaticon2-paper-plane" style="padding-right: unset;"></i></a>&nbsp;'
						. '</div>';
					}else{
						$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">in process</span><br>'
						. '</div>';
						
						$val[] = '<div style="text-align: center;">'
						. '<a  title="Download File" class="btn btn-outline-success waves-effect waves-light" href="javascript:void(0)" onclick="unduh('."'".$row->DATA_PERIZINAN."'".')" ><i class="flaticon2-download" style="padding-right: unset;"></i></a>&nbsp;'
						. '<a  title="Edit" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="ganti('."'".$this->modul->enkrip_url($row->ID_PENGAJUAN)."'".')"><i class="flaticon2-edit" style="padding-right: unset;"></i></a>&nbsp;'
						. '</div>';
					}
					
				}else{
					$val[] = '<div style="text-align: center;">'
						. '<button onclick="response('."'".$row->VERTIFIKASI_ID."'".');" class="btn kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">completed</button>'
						. '</div>';

					$val[] = '<div style="text-align: center;">'
					. '<a  title="Download File" class="btn btn-outline-success waves-effect waves-light" href="javascript:void(0)" onclick="unduh('."'".$row->DATA_PERIZINAN."'".')" ><i class="flaticon2-download" style="padding-right: unset;"></i></a>&nbsp;'
					. '<a  title="Delete" class="btn btn-outline-danger waves-effect waves-light" href="javascript:void(0)" onclick="hapus('."'".$row->ID_PENGAJUAN."'".','."'".$row->JUDUL_PERIZINAN."'".')"><i class="flaticon2-trash" style="padding-right: unset;"></i></a>'
					. '</div>';
				}
                $data[] = $val;
            }
            $output = array("data" => $data);
			echo json_encode($output);
			unset($data, $list, $val, $jenis_izin,$output);
		}else{
			$this->modul->halaman('login');
		}
	}
	
	public function new_add()
	{
		if (get_cookie('status') == "login") {
			$kond = $this->modul->dekrip_url($this->uri->segment(3));
			if ($kond == null) {
				$data['jenis_perizinan'] = $this->Mglobals->getAll("JENIS_IZIN");
				$data['id_izin'] = "";
				$data['judul'] = "";
				$data['izin'] = "";
				$data['data_izin'] = "";
				$this->load->view('head',$data);
				$this->load->view('menu');
				$this->load->view('izin_operasi/add');
				$this->load->view('fitur');
				$this->load->view('footer');
			}else {
				$data_edit = $this->Mglobals->getAllQR("select * from PENGAJUAN_IZIN_OPERASI where ID_PENGAJUAN ='".$kond."' ");
				$data['jenis_perizinan'] = $this->Mglobals->getAll("JENIS_IZIN");
				$data['id_izin'] = $data_edit->ID_PENGAJUAN;
				$data['judul'] = $data_edit->JUDUL_PERIZINAN;
				$data['izin'] = $data_edit->ID_PERIZINAN;
				$data['data_izin'] = $data_edit->DATA_PERIZINAN;
				$this->load->view('head',$data);
				$this->load->view('menu');
				$this->load->view('izin_operasi/add');
				$this->load->view('fitur');
				$this->load->view('footer');
			}
			unset($kond, $data, $data_edit);
		}else{
			$this->modul->halaman('login');
		}
	}

	public function do_upload()
        {
			$config['upload_path'] = './Data_izin/';
			$config['allowed_types'] = 'pdf';
			$config['max_filename'] = '255';
			$config['encrypt_name'] = FALSE;
			$config['max_size'] = '10000'; //2 MB
	
			if (isset($_FILES['file']['name'])) {
				if (0 < $_FILES['file']['error']) {
					$status['message'] = "Error during file upload " . $_FILES['file']['error'];
					// $status['message'] = "Gagal! Ukuran file maksimal 2 MB";
				} else {
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('file')) {
						$datafile = $this->upload->data();

						if ($this->input->post('id_izin') == "") {
							// Syarat Autokode OCI_8
							$q_data = $this->Mglobals->getAllQR("select NVL(MAX(substr(ID_PENGAJUAN,'4','7')),0) + 1 as jml from PENGAJUAN_IZIN_OPERASI ");
							// var_dump($nilai);
							$data_input = array(
								'ID_PENGAJUAN' => $this->modul->autokode_oci('IOR','4','7',$q_data->JML), //Auto kode OCI
								'JUDUL_PERIZINAN' => $this->input->post('judul'),
								'ID_PERIZINAN' => $this->input->post('izin'),
								'DATA_PERIZINAN' => $datafile['file_name'],
								'CREATED_AT' => $this->modul->TanggalWaktu(),
								'CREATED_BY' => get_cookie('username'),
								'CREATED_NAME' => get_cookie('nama'),
								'DELETE_STATUS' => 0,
								'PROGRES_STATUS' => 0
							);
							$simpan  = $this->Mglobals->add('PENGAJUAN_IZIN_OPERASI', $data_input);
						}else{
							// Update data
							$data_input = array(
								'JUDUL_PERIZINAN' => $this->input->post('judul'),
								'ID_PERIZINAN' => $this->input->post('izin'),
								'DATA_PERIZINAN' => $datafile['file_name'],
								'UPDATED_AT' => $this->modul->TanggalWaktu(),
								'UPDATED_BY' => get_cookie('username'),
								'UPDATED_NAME' => get_cookie('nama')
							);
							$condition['ID_PENGAJUAN'] = $this->input->post('id_izin');
							$simpan  = $this->Mglobals->update("PENGAJUAN_IZIN_OPERASI", $data_input, $condition);
						}
			
						if ($simpan > 0) {
							$status['message'] = "Data Tersimpan";
						}else{
							$status['message'] = "Data Gagal Tersimpan";
						}
	//                    
					} else {
						$status['message'] = $this->upload->display_errors();
					}
				}
			} else {
				if ($this->input->post('id_izin') != "") {
					$data_input = array(
						'JUDUL_PERIZINAN' => $this->input->post('judul'),
						'ID_PERIZINAN' => $this->input->post('izin'),
						'UPDATED_AT' => $this->modul->TanggalWaktu(),
						'UPDATED_BY' => get_cookie('username'),
						'UPDATED_NAME' => get_cookie('nama')
					);
					$condition['ID_PENGAJUAN'] = $this->input->post('id_izin');
					$simpan  = $this->Mglobals->update("PENGAJUAN_IZIN_OPERASI", $data_input, $condition);
					if ($simpan > 0) {
							$status['message'] = "Data Tersimpan";
						}else{
							$status['message'] = "Data Gagal Tersimpan";
						}
				}else{
					$status['message'] = "File not exits";
				}
				
			}	

			$status['token'] = $this->security->get_csrf_hash();
			echo json_encode(array("status" => $status));
			unset($config, $status, $simpan, $condition, $data_input, $q_data, $datafile);
		}
		
		public function load_response(){
			if (get_cookie('status') == "login") {
				$kond['VERTIFIKASI_ID'] = $this->uri->segment(3);
				$status['dataa'] = $this->Mglobals->get_by_id("VERTIFIKASI_IZIN", $kond);

				$status['token'] = $this->security->get_csrf_hash();
				echo json_encode(array("status" => $status));
				// var_dump($kond);
			}else{
				$this->modul->halaman('login');
			}
		}

		public function hapus() {
			if (get_cookie('status') == "login") {
				// Kode untuk hapus yang asli
				$kond['ID_PENGAJUAN'] = $this->uri->segment(3);
				// Delete Versi Update
				$data = array(
					'UPDATED_AT' => $this->modul->TanggalWaktu(),
					'UPDATED_BY' => get_cookie('username'),
					'UPDATED_NAME' => get_cookie('nama'),
					'DELETE_STATUS' => 1
					
				);
				$hapus = $this->Mglobals->update("PENGAJUAN_IZIN_OPERASI",$data,$kond);
				if($hapus == 1){
					$status['message'] = "Data terhapus";
				}else{
					$status['message'] = "Data gagal terhapus";
				}
				$status['token'] = $this->security->get_csrf_hash();
				echo json_encode(array("status" => $status));
				unset($kond, $status, $simpan, $hapus, $data);
			}else{
				$this->modul->halaman('login');
			}
		}

		public function on_proses() {
			if (get_cookie('status') == "login") {
				$data = array();
				$list = $this->Mglobals->getAllQ("select * from VERTIFIKASI_DETAIL where VERTIFIKASI_ID = '".$this->uri->segment(3)."' and REQUEST_DATA IS NOT null AND RESPONSE_REQUEST_DATA IS null ");
				foreach ($list->result() as $row) {
					$val = array();
					$val[] = $row->REQUEST_DATA;
					$val[] = '<div style="text-align: center;">'
						. '<a  title="Upload Request Data" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="upload_r_data('."'".$row->VERTIFIKASI_ID_DETAIL."'".','."'".$row->REQUEST_DATA."'".','."'".$row->VERTIFIKASI_ID."'".')"><i class="flaticon2-paper-plane" style="padding-right: unset;"></i></a>&nbsp;'
						. '</div>';
					
					$data[] = $val;
				}
				$output = array("data" => $data);
				echo json_encode($output);
				unset($data, $list, $val, $jenis_izin,$output);
			}else{
				$this->modul->halaman('login');
			}
		}
		
		public function do_upload2()
        {
			$config['upload_path'] = './Data_izin/';
			$config['allowed_types'] = 'pdf';
			$config['max_filename'] = '255';
			$config['encrypt_name'] = FALSE;
			$config['max_size'] = '10000'; //2 MB
	
			if (isset($_FILES['file']['name'])) {
				if (0 < $_FILES['file']['error']) {
					$status['message'] = "Error during file upload " . $_FILES['file']['error'];
					// $status['message'] = "Gagal! Ukuran file maksimal 2 MB";
				} else {
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('file')) {
						$datafile = $this->upload->data();
							// Update data
							$data_input = array(
								'PROGRES_STATUS' => 6,
								'RESPONSE_REQUEST_DATA' => $datafile['file_name']
							);
							$condition['VERTIFIKASI_ID_DETAIL'] = $this->input->post('i_id');
							$simpan  = $this->Mglobals->update("VERTIFIKASI_DETAIL", $data_input, $condition);
						if ($simpan > 0) {
							$status['message'] = "Data Tersimpan";		
						}else{
							$status['message'] = "Data Gagal Tersimpan";
						}
	                   
					} else {
						$status['message'] = $this->upload->display_errors();
					}
				}
			} else {
				$status['message'] = "File not exits";
			}	

			$status['token'] = $this->security->get_csrf_hash();
			echo json_encode(array("status" => $status));
			unset($config, $status, $simpan, $condition, $data_input, $q_data, $datafile, $id_ver, $status_izin_v, $status_izin_vakhir,$data_input2, $q_data2);
		}

}


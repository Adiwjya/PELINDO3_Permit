<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemilik_data extends CI_Controller {

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
			$this->load->view('pemilik_data/index');
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
            $list = $this->Mglobals->getAllQ("select * from VERTIFIKASI_DETAIL");
            foreach ($list->result() as $row) {
                $val = array();
                $val[] = $row->VERTIFIKASI_ID;
                $val[] = $row->DATA;
				$tgl_buat = $this->Mglobals->getAllQR("select CREATED_AT, CREATED_NAME from VERTIFIKASI_IZIN WHERE VERTIFIKASI_ID = '".$row->VERTIFIKASI_ID."'");
                $val[] = $tgl_buat->CREATED_AT;
				$val[] = $tgl_buat->CREATED_NAME;
				if ($row->PROGRES_STATUS == 0) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">new</span>'
						. '</div>';
				// }else if ($row->PROGRES_STATUS == 1){
				// 	$val[] = '<div style="text-align: center;">'
				// 		. '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">in process</span>'
				// 		. '</div>';
				}else if ($row->PROGRES_STATUS == 2) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">proses studi</span>'
						. '</div>';
				}else{
					$val[] = '<div style="text-align: center;">'
						. '<button onclick="response('."'".$row->VERTIFIKASI_ID."'".');" class="btn kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">completed</button>'
						. '</div>';
				}
				
				if ($row->PROGRES_STATUS == 0) {
					$val[] = '<div style="text-align: center;">'
							. '<a  title="Memerlukan Studi" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="i_studi('."'".$row->VERTIFIKASI_ID_DETAIL."'".','."'".$row->DATA."'".')"><i class="fa fa-user-graduate" style="padding-right: unset;"></i></a>&nbsp;'
							. '<a  title="Kirim Data" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="i_data('."'".$row->VERTIFIKASI_ID_DETAIL."'".','."'".$row->DATA."'".','."'".$row->VERTIFIKASI_ID."'".')"><i class="flaticon2-paper-plane" style="padding-right: unset;"></i></a>&nbsp;'
							. '</div>';
					}else if ($row->PROGRES_STATUS == 2) {
						$val[] = '<div style="text-align: center;">'
							. '<a  title="Kirim Hasil Studi" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="i_data('."'".$row->VERTIFIKASI_ID_DETAIL."'".','."'".$row->DATA."'".','."'".$row->VERTIFIKASI_ID."'".')"><i class="flaticon2-paper-plane" style="padding-right: unset;"></i></a>&nbsp;'
							. '</div>';
					}else{
						$val[] = '<div style="text-align: center;">'
							. '<button onclick="response('."'".$row->VERTIFIKASI_ID."'".');" class="btn kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">completed</button>'
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
				$this->load->view('izin_pengembangan/add');
				$this->load->view('fitur');
				$this->load->view('footer');
			}else {
				$data_edit = $this->Mglobals->getAllQR("select * from PENGAJUAN_IZIN_PENGEMBANGAN where ID_PENGAJUAN ='".$kond."' ");
				$data['jenis_perizinan'] = $this->Mglobals->getAll("JENIS_IZIN");
				$data['id_izin'] = $data_edit->ID_PENGAJUAN;
				$data['judul'] = $data_edit->JUDUL_PERIZINAN;
				$data['izin'] = $data_edit->ID_PERIZINAN;
				$data['data_izin'] = $data_edit->DATA_PERIZINAN;
				$this->load->view('head',$data);
				$this->load->view('menu');
				$this->load->view('izin_pengembangan/add');
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
							// Update data
							$data_input = array(
								'UPDATE_AT' => $this->modul->TanggalWaktu(),
								'UPDATE_BY' => get_cookie('username'),
								'UPDATE_NAME' => get_cookie('nama'),
								'PROGRES_STATUS' => 1,
								'FILE_DATA' => $datafile['file_name']
							);
							$condition['VERTIFIKASI_ID_DETAIL'] = $this->input->post('i_id');
							$simpan  = $this->Mglobals->update("VERTIFIKASI_DETAIL", $data_input, $condition);
						if ($simpan > 0) {
							$id_ver = $this->input->post('ver_id');
							$status_izin_v = $this->Mglobals->getAllQR("select count(VERTIFIKASI_ID) as jml from VERTIFIKASI_DETAIL where VERTIFIKASI_ID = '".$id_ver."'");
							$status_izin_vakhir = $this->Mglobals->getAllQR("select SUM(PROGRES_STATUS) as jml from VERTIFIKASI_DETAIL where VERTIFIKASI_ID = '".$id_ver."'");

							if ($status_izin_vakhir -> JML == $status_izin_v -> JML) {
								$data_input2 = array(
									'PROGRES_STATUS' => 2
								);
								$condition2['VERTIFIKASI_ID'] = $this->input->post('ver_id');
								$simpan2  = $this->Mglobals->update("VERTIFIKASI_IZIN", $data_input2, $condition2);
								if ($simpan2 > 0) {
									$status['message'] = "Data Tersimpan";
								}else{
									$status['message'] = "Data Gagal Tersimpan";
								}
							}else{
								$status['message'] = "Data Tersimpan";
							}
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
				$hapus = $this->Mglobals->update("PENGAJUAN_IZIN_PENGEMBANGAN",$data,$kond);
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

		public function perlu_studi(){
			if (get_cookie('status') == "login") {
				// proses penentuan table
					// Update data Status
					$data_input = array(
						'PROGRES_STATUS' => 2  //2 = Proses Studi
					);
					$condition['VERTIFIKASI_ID_DETAIL'] = $this->uri->segment(3);
					$update  = $this->Mglobals->update( 'VERTIFIKASI_DETAIL', $data_input, $condition);
					if ($update > 0) {
						$status['message'] = "Data Tersimpan";
					}else{
						$status['message'] = "Data Gagal Tersimpan";
					}
				$status['token'] = $this->security->get_csrf_hash();
				echo json_encode(array("status" => $status));
				unset($update, $status, $condition, $data_input);
			}else{
				$this->modul->halaman('login');
			}
		}


}


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pekerjaan extends CI_Controller {

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
	
	public function index()
	{
		if (get_cookie('status') == "login") {
			
			$this->load->view('head');
			$this->load->view('menu');
			$this->load->view('m_pekerjaan/index');
			$this->load->view('fitur');
			$this->load->view('footer');
		}else{
			$this->modul->halaman('login');
		}
	}

	public function ajax_list() {
        if (get_cookie('status') == "login") {
			$data = array();
            $list = $this->Mglobals->getAllQ("select * from PENGAJUAN_PEKERJAAN where DELETE_STATUS = '0'");
            foreach ($list->result() as $row) {
                $val = array();
                // $val[] = $row->ID_PENGAJUAN;
                $val[] = $row->JUDUL;
                $val[] = $row->LOKASI;
                $val[] = $row->CREATED_AT;
                $val[] = $row->CREATED_NAME;
				$val[] = '<div style="text-align: center;">'
                        . '<a  title="Edit" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="ganti('."'".$this->modul->enkrip_url($row->ID_PENGAJUAN)."'".')"><i class="flaticon2-edit" style="padding-right: unset;"></i></a>&nbsp;'
                        . '<a  title="Delete" class="btn btn-outline-danger waves-effect waves-light" href="javascript:void(0)" onclick="hapus('."'".$row->ID_PENGAJUAN."'".','."'".$row->JUDUL."'".')"><i class="flaticon2-delete" style="padding-right: unset;"></i></a>'
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
	
	public function new_add()
	{
		if (get_cookie('status') == "login") {
			$kond = $this->modul->dekrip_url($this->uri->segment(3));
			if ($kond == null) {
				$data['id_pengajuan'] = "";
				$data['judul'] = "";
				$data['lokasi'] = "";
				$this->load->view('head',$data);
				$this->load->view('menu');
				$this->load->view('m_pekerjaan/add');
				$this->load->view('fitur');
				$this->load->view('footer');
			}else {
				$data_edit = $this->Mglobals->getAllQR("select * from PENGAJUAN_PEKERJAAN where ID_PENGAJUAN ='".$kond."' ");
				$data['id_pengajuan'] = $data_edit->ID_PENGAJUAN;
				$data['judul'] = $data_edit->JUDUL;
				$data['lokasi'] = $data_edit->LOKASI;
				$this->load->view('head',$data);
				$this->load->view('menu');
				$this->load->view('m_pekerjaan/add');
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

			if ($this->input->post('id_pengajuan') == "") {
				// Syarat Autokode OCI_8
				$q_data = $this->Mglobals->getAllQR("select NVL(MAX(substr(ID_PENGAJUAN,'3','7')),0) + 1 as jml from PENGAJUAN_PEKERJAAN ");
				// var_dump($nilai);
				$data_input = array(
					'ID_PENGAJUAN' => $this->modul->autokode_oci('PK','3','7',$q_data->JML), //Auto kode OCI
					'JUDUL' => $this->input->post('judul'),
					'LOKASI' => $this->input->post('lokasi'),
					'CREATED_AT' => $this->modul->TanggalWaktu(),
					'CREATED_BY' => get_cookie('username'),
					'CREATED_NAME' => get_cookie('nama'),
					'DELETE_STATUS' => 0
				);
				$simpan  = $this->Mglobals->add('PENGAJUAN_PEKERJAAN', $data_input);
			}else{
				// Update data
				$data_input = array(
					'JUDUL' => $this->input->post('judul'),
					'LOKASI' => $this->input->post('lokasi'),
					'UPDATED_AT' => $this->modul->TanggalWaktu(),
					'UPDATED_BY' => get_cookie('username'),
					'UPDATED_NAME' => get_cookie('nama')
				);
				$condition['ID_PENGAJUAN'] = $this->input->post('id_pengajuan');
				$simpan  = $this->Mglobals->update("PENGAJUAN_PEKERJAAN", $data_input, $condition);
			}
			
				if ($simpan > 0) {
					$status['message'] = "Data Tersimpan";
				}else{
					$status['message'] = "Data Gagal Tersimpan";
				}	
			$status['token'] = $this->security->get_csrf_hash();
			echo json_encode(array("status" => $status));
			unset($status, $simpan, $condition, $data_input, $q_data);
		}
		
		public function hapus() {
			if (get_cookie('status') == "login") {

				// Kode untuk hapus yang asli
				$kond['ID_PENGAJUAN'] = $this->uri->segment(3);
				// $hapus = $this->Mglobals->delete("PENGAJUAN_PEKERJAAN", $kond);
				// if($hapus == 1){
				// 	$status['message'] = "Data terhapus";
				// }else{
				// 	$status['message'] = "Data gagal terhapus";
				// }
				// echo json_encode(array("status" => $status));

				// Delete Versi Update
				$data = array(
					'UPDATED_AT' => $this->modul->TanggalWaktu(),
					'UPDATED_BY' => get_cookie('username'),
					'UPDATED_NAME' => get_cookie('nama'),
					'DELETE_STATUS' => 1
					
				);
				$hapus = $this->Mglobals->update("PENGAJUAN_PEKERJAAN",$data,$kond);
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

}


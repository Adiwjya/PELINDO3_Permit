<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class I_pengembangan extends CI_Controller {

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
			$this->load->view('izin_pengembangan/index');
			$this->load->view('fitur');
			$this->load->view('footer');
		}else{
			$this->modul->halaman('login');
		}
	}

	public function ajax_list() {
        if (get_cookie('status') == "login") {
			$data = array();
            $list = $this->Mglobals->getAll("PENGAJUAN_IZIN");
            foreach ($list->result() as $row) {
                $val = array();
                $val[] = $row->ID_PENGAJUAN;
                $val[] = $row->JUDUL_PERIZINAN;
				$jenis_izin = $this->Mglobals->getAllQR("select PERIZINAN from JENIS_IZIN WHERE id_perizinan = '".$row->ID_PERIZINAN."'");
                $val[] = $jenis_izin->PERIZINAN;
                $val[] = $row->CREATED_AT;
                $val[] = $row->CREATED_NAME;
				$val[] = '<div style="text-align: center;">'
						. '<a  title="Download File" class="btn btn-outline-success waves-effect waves-light" href="javascript:void(0)"  onclick="ganti('."'".$row->ID_PENGAJUAN."'".')"><i class="flaticon2-download" style="padding-right: unset;"></i></a>&nbsp;'
                        . '<a  title="Edit" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)"  onclick="ganti('."'".$row->ID_PENGAJUAN."'".')"><i class="flaticon2-edit" style="padding-right: unset;"></i></a>&nbsp;'
                        . '<a  title="Delete" class="btn btn-outline-danger waves-effect waves-light" href="javascript:void(0)" onclick="hapus('."'".$row->ID_PENGAJUAN."'".','."'".$row->JUDUL_PERIZINAN."'".')"><i class="flaticon2-delete" style="padding-right: unset;"></i></a>'
                        . '</div>';
                $data[] = $val;
            }
            $output = array("data" => $data);
            echo json_encode($output);
		}else{
			$this->modul->halaman('login');
		}
	}
	
	public function new_add()
	{
		if (get_cookie('status') == "login") {
			$data['jenis_perizinan'] = $this->Mglobals->getAll("JENIS_IZIN");
			
			$this->load->view('head',$data);
			$this->load->view('menu');
			$this->load->view('izin_pengembangan/add');
			$this->load->view('fitur');
			$this->load->view('footer');
		}else{
			$this->modul->halaman('login');
		}
	}

	public function do_upload()
        {
			$config['upload_path'] = './Data_izin/';
			$config['allowed_types'] = 'pdf';
			$config['max_filename'] = '255';
			$config['encrypt_name'] = TRUE;
			$config['max_size'] = '2048'; //2 MB
	
			if (isset($_FILES['file']['name'])) {
				if (0 < $_FILES['file']['error']) {
					$status = "Error during file upload " . $_FILES['file']['error'];
				} else {
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('file')) {
						$datafile = $this->upload->data();
						
						// Syarat Autokode OCI_8
						$q_data = $this->Mglobals->getAllQR("select NVL(MAX(substr(ID_PENGAJUAN,'3','7')),0) + 1 as jml from PENGAJUAN_IZIN ");
						// var_dump($nilai);
						 $data_input = array(
							'ID_PENGAJUAN' => $this->modul->autokode_oci('PI','3','7',$q_data->JML), //Auto kode OCI
							'JUDUL_PERIZINAN' => $this->input->post('judul'),
							'ID_PERIZINAN' => $this->input->post('izin'),
							'DATA_PERIZINAN' => $datafile['file_name'],
							'CREATED_AT' => $this->modul->TanggalWaktu(),
							'CREATED_BY' => get_cookie('username'),
							'CREATED_NAME' => get_cookie('nama'),
							
						);
						$simpan  = $this->Mglobals->add('PENGAJUAN_IZIN', $data_input);
						if ($simpan > 0) {
							$status = "Data Tersimpan";
						}else{
							$status = "Data Gagal Tersimpan";
						}
	//                    
					} else {
						$status = $this->upload->display_errors();
					}
				}
			} else {
				$status = "File not exits";
			}
			echo json_encode(array("status" => $status));
        }

}


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vertifikasi_izin extends CI_Controller {

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
            $this->load->view('vertifikasi/index');
            $this->load->view('fitur');
            $this->load->view('footer');
        }else{
            $this->modul->halaman('login');
        }
    }

    public function ajax_list() {
        if (get_cookie('status') == "login") {
            $data = array();
            $list = $this->Mglobals->getAllQ("
            SELECT * FROM PENGAJUAN_IZIN_IPR where DELETE_STATUS = '0'
                UNION
            SELECT * FROM PENGAJUAN_IZIN_LINGKUNGAN where DELETE_STATUS = '0'
                UNION
            SELECT * FROM PENGAJUAN_IZIN_OPERASI where DELETE_STATUS = '0'
                UNION
            SELECT * FROM PENGAJUAN_IZIN_PENGEMBANGAN where DELETE_STATUS = '0'
                UNION
            SELECT * FROM PENGAJUAN_IZIN_PENGERUKAN where DELETE_STATUS = '0'
                UNION
            SELECT * FROM PENGAJUAN_IZIN_REKLAMASI where DELETE_STATUS = '0'
                UNION
            SELECT * FROM PENGAJUAN_IZIN_RIP where DELETE_STATUS = '0'
                UNION
            SELECT * FROM REKOM_ANDALALIN where DELETE_STATUS = '0'
            ");
            foreach ($list->result() as $row) {
                $val = array();
                // $val[] = $row->ID_PENGAJUAN;
                $val[] = $row->JUDUL_PERIZINAN;
                $jenis_izin = $this->Mglobals->getAllQR("select PERIZINAN from JENIS_IZIN WHERE id_perizinan = '".$row->ID_PERIZINAN."'");
                $val[] = $jenis_izin->PERIZINAN;
                $val[] = $row->CREATED_AT;
                $val[] = $row->CREATED_NAME;
                
                $status_izin_pemilik = $this->Mglobals->getAllQR("select NVL(PROGRES_STATUS,0) as PROGRES_STATUS from VERTIFIKASI_IZIN WHERE VERTIFIKASI_ID = '".$row->VERTIFIKASI_ID."'");  
                // if ($row->PROGRES_STATUS == 0 || $row->PROGRES_STATUS == 1) {
                if ($row->PROGRES_STATUS == 0 ) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">new</span>'
						. '</div>';
				}else if ($status_izin_pemilik->PROGRES_STATUS == 1){
					$val[] = '<div style="text-align: center;">'
                            . '<button onclick="on_process('."'".$row->VERTIFIKASI_ID."'".');" class="btn kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">in process</button>'
                            . '</div>';
				}else{
					$val[] = '<div style="text-align: center;">'
						. '<button onclick="on_process('."'".$row->VERTIFIKASI_ID."'".');" class="btn kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">completed</button>'
						. '</div>';
				}
                
                $status_izin = $this->Mglobals->getAllQR("select STATUS from VERTIFIKASI_IZIN WHERE VERTIFIKASI_ID = '".$row->VERTIFIKASI_ID."'");
                if ($row->VERTIFIKASI_ID == "" || $status_izin->STATUS == "1" || $status_izin->STATUS == "2") {
                    if ($row->VERTIFIKASI_ID != "" && $status_izin_pemilik->PROGRES_STATUS == 2) {
                        $val[] = '<div style="text-align: center;">'
                        . '<a  title="Terbitkan Izin" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)" onclick="terbit('."'".$row->ID_PENGAJUAN."'".','."'".$row->JUDUL_PERIZINAN."'".')"><i class="flaticon2-rocket-1" style="padding-right: unset;"></i></a>'
                        . '</div>';
                   }else{
                        $val[] = '<div style="text-align: center;">'
                        . '<a  title="View File" class="btn btn-outline-success waves-effect waves-light" href="javascript:void(0)" onclick="view('."'".$row->DATA_PERIZINAN."'".','."'".$row->ID_PENGAJUAN."'".')" ><i class="fa fa-file-pdf" style="padding-right: unset;"></i></a>&nbsp;'
                        . '<a  title="Perizinan" class="btn btn-outline-primary waves-effect waves-light" href="javascript:void(0)" onclick="v_tidak('."'".$row->ID_PENGAJUAN."'".','."'".$row->JUDUL_PERIZINAN."'".')"><i class="fa fa-file-signature" style="padding-right: unset;"></i></a>'
                        . '</div>';
                   }
                }else{
                    $val[] = '<div style="text-align: center;">'
                    . '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">completed</span>'
                    . '</div>';
                }
                $data[] = $val;
            }
            $output = array("data" => $data);
            echo json_encode($output);
            unset($data, $list, $val, $jenis_izin,$output,$status_izin_pemilik,$status_izin);
        }else{
            $this->modul->halaman('login');
        }
    }


    public function save_vertification(){
        if (get_cookie('status') == "login") {
            // proses penentuan table
            $destination_table = "";
            if (substr($this->input->post('v_id'),0,3) == "IPG") {
                $destination_table = "PENGAJUAN_IZIN_PENGEMBANGAN";
            }else if (substr($this->input->post('v_id'),0,3) == "ILK") {
                $destination_table = "PENGAJUAN_IZIN_LINGKUNGAN";
            }else if (substr($this->input->post('v_id'),0,3) == "IOR") {
                $destination_table = "PENGAJUAN_IZIN_OPERASI";
            }else if (substr($this->input->post('v_id'),0,3) == "IPR") {
                $destination_table = "PENGAJUAN_IZIN_PENGERUKAN";
            }else if (substr($this->input->post('v_id'),0,3) == "IRL") {
                $destination_table = "PENGAJUAN_IZIN_REKLAMASI";
            }else if (substr($this->input->post('v_id'),0,3) == "RAL") {
                $destination_table = "REKOM_ANDALALIN";
            }else if (substr($this->input->post('v_id'),0,3) == "IRR") {
                $destination_table = "PENGAJUAN_IZIN_IPR";
            }else if (substr($this->input->post('v_id'),0,3) == "RIP") {
                $destination_table = "PENGAJUAN_IZIN_RIP";
            }
            
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
                        // Syarat Autokode OCI_8
                        $q_data = $this->Mglobals->getAllQR("select NVL(MAX(substr(VERTIFIKASI_ID,'4','7')),0) + 1 as jml from VERTIFIKASI_IZIN ");
                        // var_dump($nilai);
                        $id_ver = $this->modul->autokode_oci('VRT','4','7',$q_data->JML);
                        $data_input = array(
                            'VERTIFIKASI_ID' => $id_ver, //Auto kode OCI
                            'CREATED_AT' => $this->modul->TanggalWaktu(),
                            'CREATED_BY' => get_cookie('username'),
                            'CREATED_NAME' => get_cookie('nama'),
                            'DESKRIPSI' => $this->input->post('deskripsi'),
                            'STATUS' => $this->input->post('status'),
                            'RESPON_DATA' => $datafile['file_name'],
                            'PROGRES_STATUS' => 2
                        );
                        $simpan  = $this->Mglobals->add('VERTIFIKASI_IZIN', $data_input);
                        if ($simpan > 0) {
                            // Update data izin pengembangan
                                $data_input = array(
                                    'VERTIFIKASI_ID' => $id_ver,
                                    'PROGRES_STATUS' => 2
                                );
                            $condition['ID_PENGAJUAN'] = $this->input->post('v_id');
                            $update  = $this->Mglobals->update($destination_table, $data_input, $condition);
                            if ($update > 0) {
                                $status['message'] = "Data Tersimpan";
                            }else{
                                $status['message'] = "Data Gagal Tersimpan";
                            }
                        }else{
                            $status['message'] = "Data Gagal Tersimpan";
                        }
                    } else {
                        $status['message'] = $this->upload->display_errors();
                    }
                }
            } else {
                // Syarat Autokode OCI_8
                $q_data = $this->Mglobals->getAllQR("select NVL(MAX(substr(VERTIFIKASI_ID,'4','7')),0) + 1 as jml from VERTIFIKASI_IZIN ");
                // var_dump($nilai);
                $id_ver = $this->modul->autokode_oci('VRT','4','7',$q_data->JML);
                $data_input = array(
                    'VERTIFIKASI_ID' => $id_ver, //Auto kode OCI
                    'CREATED_AT' => $this->modul->TanggalWaktu(),
                    'CREATED_BY' => get_cookie('username'),
                    'CREATED_NAME' => get_cookie('nama'),
                    'DESKRIPSI' => $this->input->post('deskripsi'),
                    'STATUS' => $this->input->post('status'),
                    'PROGRES_STATUS' => 2
                    // 'RESPON_DATA' => $datafile['file_name']
                );
                $simpan  = $this->Mglobals->add('VERTIFIKASI_IZIN', $data_input);
                if ($simpan > 0) {
                    // Update data izin pengembangan
                        $data_input = array(
                            'VERTIFIKASI_ID' => $id_ver,
                            'PROGRES_STATUS' => 2
                        );
                    
                    $condition['ID_PENGAJUAN'] = $this->input->post('v_id');
                    $update  = $this->Mglobals->update($destination_table, $data_input, $condition);
                    if ($update > 0) {
                        $status['message'] = "Data Tersimpan";
                    }else{
                        $status['message'] = "Data Gagal Tersimpan";
                    }
                }else{
                    $status['message'] = "Data Gagal Tersimpan";
                }
                // $status['message'] = "File not exits";
            }	
            
            $status['token'] = $this->security->get_csrf_hash();
            echo json_encode(array("status" => $status));
            unset($update, $status, $simpan, $condition, $data_input, $q_data, $datafile);
        }else{
            $this->modul->halaman('login');
        }
    }

    public function save_memerlukan_izin(){
        if (get_cookie('status') == "login") {
            // proses penentuan table
            $destination_table = "";
            if (substr($this->input->post('v_id'),0,3) == "IPG") {
                $destination_table = "PENGAJUAN_IZIN_PENGEMBANGAN";
            }else if (substr($this->input->post('v_id'),0,3) == "ILK") {
                $destination_table = "PENGAJUAN_IZIN_LINGKUNGAN";
            }else if (substr($this->input->post('v_id'),0,3) == "IOR") {
                $destination_table = "PENGAJUAN_IZIN_OPERASI";
            }else if (substr($this->input->post('v_id'),0,3) == "IPR") {
                $destination_table = "PENGAJUAN_IZIN_PENGERUKAN";
            }else if (substr($this->input->post('v_id'),0,3) == "IRL") {
                $destination_table = "PENGAJUAN_IZIN_REKLAMASI";
            }else if (substr($this->input->post('v_id'),0,3) == "RAL") {
                $destination_table = "REKOM_ANDALALIN";
            }else if (substr($this->input->post('v_id'),0,3) == "IRR") {
                $destination_table = "PENGAJUAN_IZIN_IPR";
            }else if (substr($this->input->post('v_id'),0,3) == "RIP") {
                $destination_table = "PENGAJUAN_IZIN_RIP";
            }

             // Syarat Autokode OCI_8
             $q_data = $this->Mglobals->getAllQR("select NVL(MAX(substr(VERTIFIKASI_ID,'4','7')),0) + 1 as jml from VERTIFIKASI_IZIN ");
             // var_dump($nilai);
             $id_ver = $this->modul->autokode_oci('VRT','4','7',$q_data->JML);
             $data_input = array(
                 'VERTIFIKASI_ID' => $id_ver, //Auto kode OCI
                 'CREATED_AT' => $this->modul->TanggalWaktu(),
                 'CREATED_BY' => get_cookie('username'),
                 'CREATED_NAME' => get_cookie('nama'),
                 'DESKRIPSI' => $this->input->post('deskripsi'),
                 'STATUS' => $this->input->post('status'),
                 'PROGRES_STATUS' => 1
                 // 'RESPON_DATA' => $datafile['file_name']
             );
             $simpan  = $this->Mglobals->add('VERTIFIKASI_IZIN', $data_input);
             if ($simpan > 0) {
                // Update data izin pengembangan
                    $data_input = array(
                        'VERTIFIKASI_ID' => $id_ver,
                        'PROGRES_STATUS' => 1
                    );
                
                $condition['ID_PENGAJUAN'] = $this->input->post('v_id');
                $update  = $this->Mglobals->update($destination_table, $data_input, $condition);
                if ($update > 0) {
                    $data_awal = $this->input->post('data_mi');
                    $data_akhir =  explode (", ", $data_awal);
                    $destination_awal = $this->input->post('destination_mi');
                    $destination_akhir =  explode (", ", $destination_awal);

                    for ($i=0; $i < count($data_akhir) ; $i++) { 
                        $q_data2 = $this->Mglobals->getAllQR("select NVL(MAX(substr(VERTIFIKASI_ID_DETAIL,'4','7')),0) + 1 as jml from VERTIFIKASI_DETAIL");
                        $id_ver2 = $this->modul->autokode_oci('VRD','4','7',$q_data2->JML);
                        $data_input = array(
                            'VERTIFIKASI_ID_DETAIL' => $id_ver2 , //Auto kode OCI
                            'DATA' => $data_akhir[$i],
                            'TUJUAN' => $destination_akhir[$i],
                            'PROGRES_STATUS' => 0,
                            'VERTIFIKASI_ID' => $id_ver
                        );
                        $simpan  = $this->Mglobals->add('VERTIFIKASI_DETAIL', $data_input);
                    }
                    if ($simpan > 0) {
                        $status['message'] = "Data Tersimpan";
                    }else{
                        $status['message'] = "Data Gagal Tersimpan";
                    }
                }else{
                    $status['message'] = "Data Gagal Tersimpan";
                }
            }else{
                $status['message'] = "Data Gagal Tersimpan";
            }

            $status['token'] = $this->security->get_csrf_hash();
            echo json_encode(array("status" => $status));
            unset($update, $status, $simpan, $condition, $data_input, $q_data, $datafile);

        }else{
            $this->modul->halaman('login');
        }
    }

    public function on_proses() {
        if (get_cookie('status') == "login") {
			$data = array();
            $list = $this->Mglobals->getAllQ("select * from VERTIFIKASI_DETAIL where VERTIFIKASI_ID = '".$this->uri->segment(3)."'");
            foreach ($list->result() as $row) {
                $val = array();
                $val[] = $row->DATA;
				if ($row->PROGRES_STATUS == 0) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--dark kt-badge--inline kt-badge--pill kt-badge--rounded">pending</span>'
						. '</div>';
				}else if ($row->PROGRES_STATUS == 4) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">proses studi</span>'
						. '</div>';
				}else if ($row->PROGRES_STATUS == 5) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">New Request Data</span>'
						. '</div>';
				}else if ($row->PROGRES_STATUS == 6) {
					$val[] = '<div style="text-align: center;">'
						. '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Data Arrived</span>'
						. '</div>';
				}else{
					$val[] = '<div style="text-align: center;">'
						. '<button onclick="response('."'".$row->VERTIFIKASI_ID."'".');" class="btn kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">completed</button>'
						. '</div>';
				}
                
                if ($row->REQUEST_DATA != null) {
                    $val[] = $row->REQUEST_DATA;
                }else{
                    $val[] = "-";
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

}
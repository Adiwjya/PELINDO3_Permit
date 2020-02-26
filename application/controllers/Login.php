<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
			$this->modul->halaman('home');
		}else{
			$this->load->view('login/login');
		}
		
	}

	public function akses()
	{
		if (get_cookie('status') == "login") {
			$this->load->view('akses/index');
		}else{
			$this->load->view('login/login');
		}
	}

	public function set_akses()
	{
		if (get_cookie('status') == "login") {
			$status_akses = $this->uri->segment(3);
			if (get_cookie('remember') == '1') {
				set_cookie('hak_akses',$status_akses,'259200');
			}else{
				set_cookie('hak_akses',$status_akses,'0');
			}

			$this->modul->halaman('home');
		}else{
			$this->load->view('login/login');
		}
	}


	public function p_login()
	{
		$postData = array(
			'application_id' => '1922',
			'user_name' => $this->input->post('user_email'),
			'user_password' => $this->input->post('user_password')
		);

		// $postData = array(
		// 	'application_id' => '1922',
		// 	'user_name' => '910205618',
		// 	'user_password' => 'Pelindo34d'
		// );

		// Setup cURL
		$ch = curl_init('http://eap-prsi.pelindo.co.id/portalsi-ws/portalsi/loginVal');
		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => json_encode($postData)
		));
		// Send the request
		$response = curl_exec($ch);
		// Check for errors
		if($response === FALSE){
			die(curl_error($ch));
		}
		// Decode the response
		$responseData = json_decode($response, TRUE);
		// var_dump($responseData);
		if ($responseData['pesan'] == "AKSES LOGIN DIIJINKAN") {
			$status = 'ok';
			set_cookie('remember',$this->input->post('chek'),'0');
			if ($this->input->post('chek') == "1") {
				set_cookie('status','login','259200');
				set_cookie('username',$responseData['USERNAME'],'259200');
				set_cookie('nama',$responseData['NAMA'],'259200');
				set_cookie('jabatan',$responseData['NAMA_JABATAN'],'259200');
				set_cookie('email',$responseData['EMAIL'],'259200');
				set_cookie('idakses',$responseData['HAKAKSES'],'259200');
				set_cookie('akses',$responseData['HAKAKSES_DESC'],'259200');
			}else{
				set_cookie('status','login','0');
				set_cookie('username',$responseData['USERNAME'],'0');
				set_cookie('nama',$responseData['NAMA'],'0');
				set_cookie('jabatan',$responseData['NAMA_JABATAN'],'0');
				set_cookie('email',$responseData['EMAIL'],'0');
				set_cookie('idakses',$responseData['HAKAKSES'],'0');
				set_cookie('akses',$responseData['HAKAKSES_DESC'],'0');
			}
		}else{
			$status = 'no';
		}
		echo json_encode(array("status" => $status));



		// Versi Kalu tidak bisa connect
		// $username = $this->input->post('user_email') ;
		// $password = $this->input->post('user_password') ;

		// if ($username == "admin" && $password == "123") {
		// 	$status = 'ok';
		// 		if ($this->input->post('chek') == "1") {
		// 			set_cookie('status','login','3600');
		// 			set_cookie('datanya','hey','3600');
		// 		}else{
		// 			set_cookie('status','login','0');
		// 			set_cookie('datanya','hey','0');
		// 		}
		// }else{
		// 	$status = 'no';
		// }
		// echo json_encode(array("status" => $status));

	}

	public function logout(){
		delete_cookie('status');
		delete_cookie('nama');
		delete_cookie('jabatan');
		delete_cookie('email');
		delete_cookie('idakses');
		delete_cookie('akses');
		delete_cookie('hak_akses');
		
		$this->modul->halaman('login');
	}

}

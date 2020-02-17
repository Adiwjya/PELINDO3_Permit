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
		// $this->load->model('Mglobals');
		$this->load->helper('cookie');
    }

	public function index()
	{
		if (get_cookie('status') == "login") {
			$this->load->view('head');
			$this->load->view('menu');
			$this->load->view('content');
			$this->load->view('fitur');
			$this->load->view('footer');
		}else{
			$this->load->view('login/login');
		}
		
	}

	public function p_login()
	{
		// $postData = array(
		// 	'application_id' => '1922',
		// 	'user_name' => $this->input->post('user_email'),
		// 	'user_password' => $this->input->post('user_password')
		// );
		// // Setup cURL
		// $ch = curl_init('http://eap-prsi.pelindo.co.id/portalsi-ws/portalsi/loginVal');
		// curl_setopt_array($ch, array(
		// 	CURLOPT_POST => TRUE,
		// 	CURLOPT_RETURNTRANSFER => TRUE,
		// 	CURLOPT_HTTPHEADER => array(
		// 	'Content-Type: application/json'
		// 	),
		// 	CURLOPT_POSTFIELDS => json_encode($postData)
		// ));
		// // Send the request
		// $response = curl_exec($ch);
		// // Check for errors
		// if($response === FALSE){
		// 	die(curl_error($ch));
		// }
		// // Decode the response
		// $responseData = json_decode($response, TRUE);
		// // var_dump($responseData);
		// if ($responseData['pesan'] == "AKSES LOGIN DIIJINKAN") {
		// 	$status = 'ok';
		// }else{
		// 	$status = 'no';
		// }
		// echo json_encode(array("status" => $status));

		$username = $this->input->post('user_email') ;
		$password = $this->input->post('user_password') ;

		if ($username == "admin" && $password == "123") {
			$status = 'ok';
			$sess_array = array(
                'datanya' => "hAY"
            );
            $this->session->set_userdata('logged_in', $sess_array);
		}else{
			$status = 'no';
		}
		echo json_encode(array("status" => $status));

	}
	
	public function p_login2()
	{
		$username = $this->input->post('user_email') ;
		$password = $this->input->post('user_password') ;
		// $username = "admin" ;
		// $password = "123" ;

		if ($username == "admin" && $password == "123") {
			$status = 'ok';
			set_cookie('status','login','3600');
			set_cookie('datanya','hey','3600');
		}else{
			$status = 'no';
		}
		echo json_encode(array("status" => $status));
		echo get_cookie('status');
		echo get_cookie('datanya');
	}

	public function logout(){
		delete_cookie('status');
		delete_cookie('datanya');
		$this->modul->halaman('login');
	}

}

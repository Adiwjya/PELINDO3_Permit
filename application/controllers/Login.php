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
	public function index()
	{
		$this->load->view('login/login');
	}

	public function p_login()
	{
		// $postData = array(
		// 	'application_id' => '1922',
		// 	'user_name' => $this->input->post('user_email'),
		// 	'user_password' => $this->input->post('password')
		// );
		$postData = array(
			'application_id' => '1922',
			'user_name' => "910205618",
			'user_password' => "Pelido34d" 
		);
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

		}else 
		// Decode the response
		$responseData = json_decode($response, TRUE);
		if ($responseData['pesan'] == "AKSES LOGIN DIIJINKAN") {
			$status = 'ok';
		}else{
			$status = 'no';
		}
		echo json_encode(array("status" => $status));
	}

}

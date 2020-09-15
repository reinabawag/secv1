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
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->model(array('user_model'));
		$this->load->helper(array('url'));

		if ($this->session->logged_in == TRUE) {
			redirect('index');
		}
	}

	public function index()
	{
		$this->load->helper(['url']);
		$this->load->view('login');
	}

	public function get_user()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed you IP is '.$this->input->ip_address());
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('inputUsername', 'Username', 'required');
		$this->form_validation->set_rules('inputPassword', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array('error' => validation_errors()));
		} else {
			$username = $this->input->post('inputUsername');
			$Password = $this->input->post('inputPassword');

			// $data = $this->user_model->get_login($username, $Password);

			if (($username == 'admin') && ($Password == 'mis')) {
				$this->session->set_userdata(array('logged_in' => TRUE));

				echo json_encode(array('status' => TRUE, 'msg' => 'Login Successful', 'url' => site_url('index')));
			} else {
				echo json_encode(array('status' => FALSE, 'msg' => 'Invalid Username or Password'));
			}
		}
	}
}

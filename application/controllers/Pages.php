<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

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
		$this->load->model(['user_model']);
		$this->load->helper(['url', 'form']);
		$this->load->library(array('session'));

		if ($this->session->has_userdata('logged_in') == FALSE) {
			redirect('login');
		}
	}

	public function view($page = 'index')
	{
		if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php')) 
		{
			show_404();
		}

		$data['title'] = ucfirst($page);

		$this->load->view('template/header', $data);
		$this->load->view('template/navbar', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('template/footer', $data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}

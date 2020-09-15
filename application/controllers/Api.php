<?php
/**
* 
*/
class Api extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function ()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die();
		}

		$username = $this->input->post('username');
		$password = $this->input->post('password');


		$data = $this->user_model->get_login($username, $password);

		echo json_encode($data);
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
		$this->load->helper(['url', 'form', 'html']);
		$this->load->library(['form_validation', 'session']);

		if ( ! isset($this->session->logged_in)) {
			redirect('login');
		}
	}

	public function insert()
	{
		if ( ! $this->input->is_ajax_request()) {
			die('Hacking is not allowed your IP is '.$this->input->ip_address());
		}

		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]|min_length[4]|max_length[65]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[65]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');

		if ($this->form_validation->run() == FALSE) 
		{
			echo json_encode(['error' => validation_errors()]);
		} 
		else 
		{	
			$username = $this->input->post('username');
			$password = $this->input->post('cpassword');

			if ($this->user_model->insert_user($username, $password))
			{
				echo json_encode(['status' => TRUE, 'msg' => 'User added successfully']);
			}
			else
			{
				echo json_encode(['status' => FALSE, 'msg' => 'Cannot add new user']);
			}
		}
	}

	public function get_users()
	{
		if ( ! $this->input->is_ajax_request()) {
			die('Hacking is not allowed your IP is '.$this->input->ip_address());
		}
		
		$data = array();
		$json = array();
		$count = 0;
		$draw = intval($this->input->post('draw'));
		$start = $this->input->post('start');
		$length = $this->input->post('length');
		$q = $this->input->post('search[value]');
		$order_by = $this->input->post('order[0][column]');
		$order_type = $this->input->post('order[0][dir]');

		if ($q != '') {
			$data = $this->user_model->search_users($length, $start, $q, $order_by, $order_type);
		} else {
			$data = $this->user_model->get_users($length, $start, $order_by, $order_type);
		}		
		
		foreach ($data as $key => $value) {
			$count++;
			$json[] = [$value->username, $value->lastName, $value->firstName, $value->middleName, '<a href="'.site_url('view/'.$value->username).'">Goto</a>'];
		}

		echo json_encode(['draw' =>  $draw, 'recordsTotal' => $this->user_model->count_all_users(), 'recordsFiltered' => $this->user_model->count_all_users_q($q), 'data' => $json]);
	}

	public function view($username)
	{
		$data['username'] = $username;
		$data['user'] = $this->user_model->get_user_by_username($username);
		$data['system'] = $this->user_model->get_systems();
		$data['selected'] = array();

		foreach ($this->user_model->get_systems_by_user($username) as $key => $value) {
			$data['selected'][$value['systemId']] = $value['systemId'];
		}

		if (count($data['user']) == 0) 
		{
			redirect('', 'location');
		}

		$this->load->view('template/header');
		$this->load->view('template/navbar');
		$this->load->view('pages/view', $data);
		$this->load->view('template/footer');
	}

	public function get_systems()
	{
		if ($this->input->is_ajax_request() === FALSE) {
			die('Hacking is not allowed you IP is '.$this->input->ip_address());
		}

		$data = $this->user_model->get_systems();

		echo json_encode($data);
	}

	public function add_system()
	{
		if ($this->input->is_ajax_request() === FALSE) {
			die('Hacking is not allowed you IP is '.$this->input->ip_address());
		}

		$this->form_validation->set_rules('system', 'System', 'required|is_unique[systems.name]|max_length[65]');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode(['error' => validation_errors()]);
		} else {
			$system = $this->input->post('system');

			$status = $this->user_model->post_system($system);

			if ($status) {
				echo json_encode(['status' => TRUE, 'message' => 'System added successfully']);
			} else {
				echo json_encode(['status' => FALSE, 'message' => $status]);
			}
		}
	}

	public function system_user()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed your IP is '.$this->input->ip_address());
		}

		$systemId = $this->input->post('system');
		$userId = $this->input->post('userId');

		if ($this->user_model->add_system_user($userId, intval($systemId[0]))) {
			echo json_encode(array('status' => TRUE));
		} else {
			echo json_encode(array('status' => FALSE));
		}
	}

	public function remove_system_user()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed your IP is '.$this->input->ip_address());
		}

		$systemId = $this->input->post('system');
		$userId = $this->input->post('userId');

		if ($this->user_model->remove_system_user($userId, intval($systemId[0]))) {
			echo json_encode(array('status' => TRUE));
		} else {
			echo json_encode(array('status' => FALSE));
		}
	}

	public function get_system_by_user()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed you IP is '.$this->input->ip_address());
		}

		$userId = $this->input->post('userId');

		$data = $this->user_model->get_systems_by_user($userId);
		$tmp = array();

		foreach ($data as $key => $value) {
			$tmp[] = array($value->systemId);
		}

		echo json_encode($tmp);
	}

	public function user_update($username)
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed your IP is '.$this->input->ip_address());
		}

		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		$this->form_validation->set_rules('firstname', 'First Name', 'required');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array('error' => validation_errors()));
		} else {
			$username = $this->input->post('username');
			$lastname = $this->input->post('lastname');
			$firstname = $this->input->post('firstname');
			$middlename = $this->input->post('middlename');
			$systems = is_null($this->input->post('select-systems')) ? array() : $this->input->post('select-systems');
			$admin = ! is_null($this->input->post('admin')) ? TRUE : FALSE;
			$supervisor = ! is_null($this->input->post('supervisor')) ? TRUE : FALSE;

			if ($this->user_model->set_emp_info($username, $lastname, $firstname, $middlename, $systems, $admin, $supervisor)) {
				echo json_encode(array('status' => TRUE, 'msg' => 'User information successfully updated'));
			} else {
				echo json_encode(array('status' => FALSE, 'msg' => 'Failed to update user information'));
			}
		}
	}

	public function get_info()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed your IP is '.$this->input->ip_address());
		}

		$username = $this->input->post('username');
		$data = $this->user_model->get_user_by_username($username);
		echo json_encode($data);
	}

	public function update_password()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed your IP is '.$this->input->ip_address());
		}

		$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[65]');
		$this->form_validation->set_rules('cpassword', 'Password Confirmation', 'required|matches[password]|min_length[4]|max_length[65]');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode(['val' => FALSE, 'msg' => validation_errors()]);
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('cpassword');
			$password = password_hash($password, PASSWORD_BCRYPT);

			if ($this->user_model->change_password($username, $password)) {
				$this->user_model->change_password_log($username, 'Changed Password');
				echo json_encode(['status' => TRUE, 'msg' => 'Successfully changed password']);	
			} else {
				echo json_encode(['status' => FALSE, 'msg' => 'Error in changing password']);	
			}
		}
	}

	public function updateUsername()
	{
		if ($this->input->is_ajax_request() == FALSE) {
			die('Hacking is not allowed');
		}

		$this->form_validation->set_rules('u_username', 'Username', 'required|min_length[4]|max_length[65]|is_unique[user.username]');
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(['status' => FALSE, 'msg' => form_error('u_username')]);
		} else {
			$username = $this->input->post('username');
			$u_username = $this->input->post('u_username');

			$bool = $this->user_model->updateUsername($username, $u_username);
			if ($bool) {
				echo json_encode(['status' => TRUE, 'msg' => 'Username updated successfully']);
			} else {
				echo json_encode(['status' => FALSE, 'msg' => 'Error updating username']);
			}
		}
	}
}
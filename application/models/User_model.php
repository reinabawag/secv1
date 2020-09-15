<?php 

class User_model extends CI_Model
{
	
	function __construct()
	{
		$this->load->database();
	}

	function insert_user($username, $password)
	{
		$data = array('username' => $username, 'password' => password_hash($password, PASSWORD_BCRYPT), 'status' => 1);

		return $this->db->insert('user', $data);
	}

	public function get_login($username, $password)
	{
		$user = $this->db->get_where('user', array('username' => $username))->row();
		$h_password = $user->password;
		
		if (password_verify($password, $h_password)) {
			return $user;
		}

		return FALSE;
	}

	function count_all_users()
	{
		$this->db->join('empinfo', 'empinfo.userId=user.userId', 'left');
		$this->db->from('user');
		return $this->db->count_all_results();
	}

	function count_all_users_q($q)
	{
		$this->db->join('empinfo', 'empinfo.userId=user.userId', 'left');
		$this->db->like('username', $q);
		$this->db->or_like('lastName', $q);
		$this->db->or_like('firstName', $q);
		$this->db->or_like('middleName', $q);
		$this->db->from('user');
		return $this->db->count_all_results();
	}

	function get_users($limit, $offset, $order_by, $order_type)
	{
		switch ($order_by) {
			case '0':
				$order_by = 'username';
				break;
			case '1':
				$order_by = 'lastName';
				break;
			case '2':
				$order_by = 'firstName';
				break;
			case '3':
				$order_by = 'username';
				break;
			default:
				$order_by = 'username';
				break;
		}
		$this->db->limit($limit, $offset);
		$this->db->join('empinfo', 'empinfo.userId=user.userId', 'left');
		$this->db->order_by($order_by, $order_type);
		$query = $this->db->get('user');
		
		return $query->result();
	}

	function search_users($limit, $offset, $q, $order_by, $order_type)
	{
		switch ($order_by) {
			case 0:
				$order_by = 'username';
				break;
			case 1:
				$order_by = 'lastName';
				break;
			case 2:
				$order_by = 'firstName';
				break;
			case 3:
				$order_by = 'username';
				break;
			default:
				$order_by = 'username';
				break;
		}
		$this->db->limit($limit, $offset);
		$this->db->join('empinfo', 'empinfo.userId=user.userId', 'left');
		$this->db->or_like('username', $q);
		$this->db->or_like('lastName', $q);
		$this->db->or_like('firstName', $q);
		$this->db->or_like('middleName', $q);
		$this->db->order_by($order_by, $order_type);
		$query = $this->db->get('user');
		return $query->result();
	}

	public function get_user_by_username($username)
	{
		$this->db->join('empinfo', 'empinfo.userId = user.userId', 'left');
		$result = $this->db->get_where('user', ['username' => $username]);

		return $result->row();
	}

	public function get_systems()
	{	
		$result = $this->db->get('systems');

		return $result->result();
	}

	public function post_system($system)
	{
		try {
			return $this->db->insert('systems', ['name' => $system, 'status' => 1]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function get_systems_by_user($username)
	{
		$this->db->join('systems', 'systems.systemId=system_user.systemId', 'LEFT');
		$this->db->join('user', 'system_user.userId=user.userId', 'LEFT');
		$this->db->select('systems.systemId');
		$query = $this->db->get_where('system_user', array('user.username' => $username));

		return $query->result_array();
	}

	public function add_system_user($userId, $systemId)
	{
		return $this->db->insert('system_user', array('systemId' => $systemId, 'userId' => $userId));
	}

	public function remove_system_user($userId, $systemId)
	{
		return $this->db->delete('system_user', array('systemId' => $systemId, 'userId' => $userId));
	}

	public function set_emp_info($username, $lastname, $firstname, $middlename, $systems, $admin, $supervisor) 
	{
		try {
			$userId = $this->get_userid_by_username($username)->userId;

			$this->db->where('userId',$userId);
			$this->db->delete('system_user');

			foreach ($systems as $key => $value) {
				$this->db->insert('system_user', array('systemId' => $value, 'userId' => $userId));
			}

			$data = array(
				'userId' => $userId,
				'lastName' => ucfirst($lastname),
				'firstName' => ucfirst($firstname),
				'middleName' => ucfirst($middlename),
				'is_admin' => $admin,
				'is_supervisor' => $supervisor
			);
			
			if (count($this->check_empinfo_exist($userId)) == 0) {
				return $this->db->insert('empinfo', $data);
			} else {
				$this->db->where('userId', $userId);
				return $this->db->update('empinfo', $data);
			}			
		} catch (Exception $e) {
			return FALSE;
		}
	}

	public function get_userid_by_username($username)
	{
		return $this->db->select('userId')->get_where('user', array('username' => $username))->row();
	}

	public function check_empinfo_exist($userId)
	{
		return $this->db->get_where('empinfo', array('userId' => $userId))->result();
	}

	public function change_password($username, $password)
	{	
		$this->db->where(['username' => $username]);
		return $this->db->update('user', ['password' => $password]);
	}

	public function change_password_log($username, $description)
	{
		$data = ['username' => $username, 'description' => $description];
		return $this->db->insert('password_logs', $data);
	}

	public function updateUsername($username, $u_username)
	{
		$this->db->where(['username' => $username]);
		return $this->db->update('user', ['username' => $u_username]);
	}
}
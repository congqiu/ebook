<?php
class User_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

	public function getUserById($id = null)
	{
		if (is_string($id)) {
			$id = (int)$id;
		}
		$query = $this->db->get_where('user', array('ID' => $id));
	    return $query->row();
	}

	public function getUserByUserName($username = null)
	{
		$query = $this->db->get_where('user', array('username' => $username));
	    return $query->row();
	}

	public function getUserByEmail($email = NULL)
	{
		$query = $this->db->get_where('user', array('email' => $email));
		return $query->row();
	}

	public function addUser()
	{
		$date = time();

		$pwd = $this->input->post('password');
		$pwdh = password_hash($pwd, PASSWORD_DEFAULT);
		
	    $data = array(
	        'username' => $this->input->post('username'),
	        'email' => $this->input->post('email'),
	        'password' => $pwdh,
	        'avatar' => 'default.jpg',
	        'status' => 0,
	        'create_time' => $date,
	        'change_time' => $date,
	    );

	    return $this->db->insert('user', $data);
	}

	public function updateUser()
	{
		$date = time();

		$data = array(
	        'username' => $this->input->post('username'),
	        'name' => $this->input->post('name'),
	        'sex' => $this->input->post('sex'),
	        'email' => $this->input->post('email'),
	        'change_time' => $date,
	    );

	    $this->db->where('id', (int)$this->input->post('id'));
	    return $this->db->update('user', $data);
	}

	public function updateAvatar($id, $avatar)
	{
		$date = time();

		$data = array(
	        'avatar' => $avatar,
	        'change_time' => $date,
	    );

	    $this->db->where('id', $id);
	    return $this->db->update('user', $data);
	}

	public function updatePassword()
	{
		$date = time();

		$pwd = $this->input->post('password');
		$pwdh = password_hash($pwd, PASSWORD_DEFAULT);
		
		$data = array(
	        'password' => $pwdh,
	        'change_time' => $date,
	    );

	    $this->db->where('id', (int)$this->input->post('id'));
	    return $this->db->update('user', $data);
	}

	public function updateMd5pwd($id, $pwd = null)
	{
		$date = time();

		$data = array(
	        'md5pwd' => $pwd,
	        'change_time' => $date,
	    );

	    $this->db->where('id', (int)$id);
	    return $this->db->update('user', $data);
	}

	public function updateUserInfo($id, $data = array())
	{
		$date = time();

		$data['change_time'] = $date;

	    $this->db->where('id', (int)$id);
	    return $this->db->update('user', $data);
	}

	public function updateUserByEmail($email, $data = array())
	{
		$date = time();

		$data['change_time'] = $date;

	    $this->db->where('email', $email);
	    return $this->db->update('user', $data);
	}
}
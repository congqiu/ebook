<?php
class Token_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

	public function getByToken($token = "")
	{
		$query = $this->db->get_where('token', array('token' => $token, 'status' => 1));
	    return $query->row();
	}

	public function add($email, $token, $time, $type = 'token')
	{
	    $data = array(
	        'token' => $token,
	        'type' => $type,
	        'status' => 1,
	        'email' => $email,
	        'create_time' => $time,
	        'change_time' => $time
	    );

	    return $this->db->insert('token', $data);
	}

	/*
	表示正常发送了的邮件
	status置为0
	 */
	public function update($token, $type='token')
	{
		$time = time();
		
	    $data = array(
	        'status' => 0,
	        'change_time' => $time
	    );

	    $this->db->where('token', $token);
	    $this->db->where('type', $type);
	    return $this->db->update('token', $data);
	}

	/*
	失效的忘记密码邮件
	status置为-1
	 */
	public function updateByEmail($email, $type='token')
	{
		$time = time();
		
	    $data = array(
	        'status' => -1,
	        'change_time' => $time
	    );

	    $this->db->where('email', $email);
	    $this->db->where('status', 1);
	    $this->db->where('type', $type);
	    return $this->db->update('token', $data);
	}
}
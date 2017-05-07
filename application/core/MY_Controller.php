<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct($type = NULL)
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function is_login()
	{	
		$res = FALSE;
		if ($this->session->userdata('username')) {
			$res = TRUE;
		}

		if (get_cookie('userInfo')) {
			$cuser = NULL;
			$md5pwd = NULL;
			$userInfo = urldecode(get_cookie('userInfo'));
			$arr = explode('&', $userInfo);
			if (explode('=', $arr[0])[0] == 'username') {
				$username = explode('=', $arr[0])[1];
				$cuser = $this->user_model->getUserByUserName($username);
			}
			if (explode('=', $arr[1])[0] == 'md5pwd') {
				$md5pwd = explode('=', $arr[1])[1];
			}
			if ($cuser && $md5pwd && $cuser->md5pwd == $md5pwd && $cuser->is_cookie) {
				$res = TRUE;
			}
		}
		return $res;
	}

	public function get_cur_user()
	{	
		$user = NULL;

		if (get_cookie('userInfo')) {
			$cuser = NULL;
			$md5pwd = NULL;
			$userInfo = urldecode(get_cookie('userInfo'));
			$arr = explode('&', $userInfo);
			if (explode('=', $arr[0])[0] == 'username') {
				$username = explode('=', $arr[0])[1];
				$cuser = $this->user_model->getUserByUserName($username);
			}
			if (explode('=', $arr[1])[0] == 'md5pwd') {
				$md5pwd = explode('=', $arr[1])[1];
			}
			if ($cuser && $md5pwd && $cuser->md5pwd == $md5pwd && $cuser->is_cookie) {
				$res = $cuser;
				return $cuser;
			}
		}

		$username = $this->session->userdata('username');
		if ($username) {
			$user = $this->user_model->getUserByUserName($username);
		}
		return $user;
	}

	public function isAdmin()
	{	
		$res = FALSE;
		$username = $this->session->userdata('username');
		if ($username) {
			$user = $this->user_model->getUserByUserName($username);
			
			if ($user->role == 'admin' && $user->status == 1) {
				$res = TRUE;
			}
		}
		return $res;
	}

	public function getCurAdmin()
	{	
		$res = NULL;
		$username = $this->session->userdata('username');
		if ($username) {
			$user = $this->user_model->getUserByUserName($username);
			
			if ($user->role == 'admin' && $user->status == 1) {
				$res = $user;
			}
		}
		return $res;
	}
	
	public function getRandChar($length){
		$str = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol)-1;

		for($i=0; $i < $length; $i++){
			$str .= $strPol[rand(0,$max)];
		}

		return $str;
	}

	public function getCaptcha()
	{
		$this->load->helper('captcha');

		$vals = array(
            'img_path'  => APPPATH . '../static/captcha/',
            'img_url'   => base_url('/static/captcha/'),
            'word_length' => 4,
            'expiration' => 1800,
        );

        $cap = create_captcha($vals);
        return $cap;
	}

	public function getCaptchaAndSession()
	{
		
        $cap = $this->getCaptcha();

        $cap_data = array(
            'captcha_time'  => $cap['time'],
            'ip_address'    => $this->input->ip_address(),
            'captcha_word'      => strtolower($cap['word'])
        );
        $this->session->set_userdata($cap_data);

        return $cap;
	}
}
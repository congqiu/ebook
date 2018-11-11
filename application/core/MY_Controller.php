<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $need_login = false;

	public function __construct($type = NULL)
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->check_login();//调用判断登录的方法
	}

	private function check_login()
	{
		if($this->need_login && !$this->is_login())
		{
			redirect('/login');
		}
	}

	public function is_login()
	{	
		$res = FALSE;
		if ($this->session->userdata('username')) {
			$res = TRUE;
		}

		if (get_cookie('user') && get_cookie('token')) {
			$this_user = $this->user_model->getUserByUserName(get_cookie('user'));
			if ($this_user) {
	           	$login = $this->user_model->getLoginByTokenAndUser($this_user->username, get_cookie('token'));
	           	$platform = $this->agent->platform();
        		$browser = $this->agent->browser();
        		$logined = $login && $platform == $login->platform && $login->browser == $browser;
		        if ($logined) {
		            $res = TRUE;
		        }
	        }
		}

		return $res;
	}

	public function get_cur_user()
	{	
		$user = NULL;

		if (get_cookie('user') && get_cookie('token')) {
			$this_user = $this->user_model->getUserByUserName(get_cookie('user'));
			if ($this_user) {
	           	$login = $this->user_model->getLoginByTokenAndUser($this_user->username, get_cookie('token'));
	           	$platform = $this->agent->platform();
        		$browser = $this->agent->browser();
        		$logined = $login && $platform == $login->platform && $login->browser == $browser;
		        if ($logined) {
		            return $this_user;
		        }
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
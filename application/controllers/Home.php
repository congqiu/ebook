<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url_helper');
    }

    public function showUserInfo()
    {
        $data['user'] = $this->get_cur_user();
        if (!$data['user']) {
            header('Location: /', TRUE ,307);
            return;
        }

        $data['page'] = 'home-page';

        $this->load->view('layout/header', $data);
        $this->load->view('home/user-info', $data);
        $this->load->view('layout/footer');
    }

    public function login()
    {
        $data['title'] = 'login page';

        if ($this->get_cur_user()) {
            header('Location: /', TRUE ,307);
            return;
        }

        $this->load->library('user_agent');

        if ($this->input->method() == "post") {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
        
            $user = $this->user_model->getUserByUserName($username);
            if ($user != null && password_verify($password, $user->password)) {
                $newdata = array(
                  'username'  =>  $user->username ,
                  'userip'     => $_SERVER['REMOTE_ADDR'],
                  'luptime'   =>time()
                );
                $this->session->set_userdata($newdata);

                if ($user->is_cookie) {
                    $user->md5pwd = md5($this->getRandChar(16) . time());
                    $this->user_model->updateMd5pwd($user->id, $user->md5pwd);
                    $userInfo = 'username=' . $username . '&md5pwd=' . $user->md5pwd;
                    set_cookie("userInfo", urlencode($userInfo), 3600*24*365);
                }

                header('Location: /', TRUE ,307);
            } else {
                $data['message'] = '失败';
                $this->load->view('home/login', $data);
            }
        } else {
            $this->load->view('home/login', $data);
        }

    }

    public function logout() {
        $this->session->unset_userdata('username');
        delete_cookie('userInfo');
        header('Location: /', TRUE ,307);
        return;
    }

    public function register()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('token_model');

        $data['title'] = '注册';

        $this->form_validation->set_rules('username', '用户名', 'trim|required|min_length[1]|max_length[64]|is_unique[user.username])');
        $this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('email', '邮箱', 'trim|required|valid_email|is_unique[user.email])');

        // if ($this->input->method() == "post") {
        //     header('Location: /', TRUE ,307);
        // }
        
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('home/register', $data);

        } else {
            $result = $this->user_model->addUser();
            if ($result) {
                $email = $this->input->post('email');
                $token = md5($this->input->post('username') . $email . $this->getRandChar(16) . time());
                $url = base_url('/register/confirm/' . $token);
                $email_time = time();
                $this->token_model->updateByEmail($email, 'register_confirm');
                $this->token_model->add($email, $token, $email_time, 'register_confirm');
                $mes = '欢迎注册，请在48小时内点击以下链接确认邮箱。<a href="' . $url . '" target="_blank">' . $url . "</a>如果您的浏览器不能直接点击，请复制该链接直接使用。";
               
                if ($this->sendEmail($email, '注册确认邮件', $mes)) {
                    header('Location: /login', TRUE ,307);
                }
            } else {
                $data['message'] = '注册出错了';
            }
            $this->load->view('home/login', $data);
        }
    }

    public function registerConfirm($token)
    {
        $user = $this->get_cur_user();


        $this->load->model('token_model');

        $confirm = $this->token_model->getByToken($token);
       
        if ($confirm && $confirm->create_time > (time() - 3600*48)) {
            $this->token_model->update($token, 'register_confirm');
            $result = $this->user_model->updateUserByEmail($confirm->email, array('status' => 1));
            header('Location: /home', TRUE ,307);
        }
        header('Location: /', TRUE ,307);
    }

    public function updateUserInfo()
    {
        $user = $this->get_cur_user();

        if (! $user) {
            header('Location: /', TRUE ,307);
            return;
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['page'] = 'home-page';

        $data['sex'] = array('男' => '男', '女' => '女', '保密' => '保密');

        $data['user'] = $user;

        $this->form_validation->set_rules('username', 'Username', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('layout/header', $data);
            $this->load->view('home/update-user-info', $data);
            $this->load->view('layout/footer');

        } else {
            if ($data['user']->id == (int)$this->input->post('id')) {
                $this->user_model->updateUser();
                header('Location: /home', TRUE ,307);
            } else {
                $this->load->view('layout/header', $data);
                $this->load->view('home/update-user-info', $data);
                $this->load->view('layout/footer');
            }
        }
    }

    public function updateUserAvatar()
    {
        $user = $this->get_cur_user();

        if (! $user) {
            header('Location: /', TRUE ,307);
            return;
        }
        $this->load->helper(array('form', 'url'));

        $data['user'] = $user;

        $config['upload_path']      = APPPATH . '../static/avatar/';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['file_name']    = $this->getRandChar(32);
        $config['file_ext_tolower']    = TRUE;
        $config['max_size']     = 200;
        $config['max_width']        = 1024;
        $config['max_height']       = 1024;

        $this->load->library('upload', $config);

        $data['page'] = 'home-page';


        if ( ! $this->upload->do_upload('avatar'))
        {
            $error = array('error' => "");

            if ($this->input->method() == "post") {
                $error = array('error' => $this->upload->display_errors());
            }

            $this->load->view('layout/header', $data);
            $this->load->view('home/upload-avatar', $error);
            $this->load->view('layout/footer');
        } else {
            $avatar_arr = $this->upload->data();
            $avatar = $avatar_arr['file_name'];
            $this->user_model->updateAvatar($user->id, $avatar);
            header('Location: /home', TRUE ,307);
        }
    }

    public function updateUserPassword()
    {
        $user = $this->get_cur_user();

        if (! $user) {
            header('Location: /', TRUE ,307);
            return;
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['page'] = 'home-page';

        $data['user'] = $user;

        $this->form_validation->set_rules('old-password', '初始密码', 'required|callback_password_confirm');
        $this->form_validation->set_rules('password', '新密码', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('passconf', '确认密码', 'trim|matches[password]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('layout/header', $data);
            $this->load->view('home/update-user-pwd', $data);
            $this->load->view('layout/footer');

        } else {
            if ($data['user']->id == (int)$this->input->post('id')) {
                $this->user_model->updatePassword();
                header('Location: /home', TRUE ,307);
            } else {
                $this->load->view('layout/header', $data);
                $this->load->view('home/update-user-pwd', $data);
                $this->load->view('layout/footer');
            }
        }
    }

    public function forgotPassword()
    {
        if ($this->get_cur_user()) {
            header('Location: /', TRUE ,307);
        }

        $this->load->model('token_model');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = '找回密码';
        $data['page'] = 'home-page';
        if ($this->input->method() == "post") {

            $this->form_validation->set_rules('username', '用户名', 'required|callback_user_confirm');
            $this->form_validation->set_rules('email', '邮箱', 'trim|required');

            if ($this->form_validation->run() === FALSE)
            {
                $cap = $this->getCaptchaAndSession();
                $data['captcha'] = $cap['filename'];
            } else {
                $expiration = time() - 1800;
                $captcha_time = $this->session->userdata('captcha_time');
                $ip_address = $this->session->userdata('ip_address');
                $captcha_word = $this->session->userdata('captcha_word');

                $email = $this->input->post('email');


                if ($this->input->ip_address() == $ip_address 
                    && $captcha_word == strtolower($this->input->post('captcha'))
                    && $captcha_time > $expiration) {

                    $user = $this->user_model->getUserByEmail($email);
                    if ($user) {
                        $token = md5($user->name . $user->email . $this->getRandChar(16) . time());
                        $url = base_url('/change-password/' . $token);
                        $email_time = time();
                        $this->token_model->updateByEmail($email, 'forgot_pwd');
                        $this->token_model->add($email, $token, $email_time, 'forgot_pwd');
                        $mes = '您在使用找回密码密码功能，请在30分钟内点击以下链接进行重置密码。<a href="' . $url . '" target="_blank">' . $url . "</a>如果您的浏览器不能直接点击，请复制该链接直接使用。";
                       
                        if ($this->sendEmail($email, '找回密码邮件', $mes)) {
                            header('Location: /login', TRUE ,307);
                        }
                    } else {
                        $data['message'] = '邮箱错误';
                    }
                } else {
                    $data['message'] = '验证码错误';
                }
            }
        }

        $cap = $this->getCaptchaAndSession();
        $data['captcha'] = $cap['filename'];
        $this->load->view('layout/header', $data);
        $this->load->view('home/forgot-password', $data);
        $this->load->view('layout/footer');
    }

    public function changePassword($token = '')
    {
        if ($this->get_cur_user()) {
            header('Location: /', TRUE ,307);
        }
        $this->load->model('token_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
     
        $data['title'] = '重置密码';
        $data['page'] = 'home-page';

        $forgot = $this->token_model->getByToken($token);
        $data['token'] = $token;

        if ($forgot && $forgot->create_time > (time() - 1800)) {
            $data['user'] = $this->user_model->getUserByEmail($forgot->email);
            
            if ($this->input->method() == "post") {

                $this->form_validation->set_rules('password', '新密码', 'trim|required|min_length[1]');
                $this->form_validation->set_rules('passconf', '确认密码', 'trim|matches[password]');
                if ($this->form_validation->run() === FALSE)
                {
                } else {
                    if ($data['user']->id == (int)$this->input->post('id')) {
                        $this->user_model->updatePassword();
                        $this->token_model->update($token, 'forgot_pwd');
                        header('Location: /home', TRUE ,307);
                    }
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('home/change-pwd', $data);
            $this->load->view('layout/footer');
        } else {
            header('Location: /', TRUE ,307);
        }
    }

    public function user_confirm($username)
    {
        $user= $this->user_model->getUserByUserName($username);
        if ($user) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function password_confirm($password)
    {
        $user= $this->get_cur_user();
        
        if (password_verify($password, $user->password)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function sendEmail($to, $object = null, $message = null)
    {
        $this->load->library('email');
        
        $this->email->set_newline("\r\n");
        $this->email->from('no-reply@qiucong.xin',$object);
        $this->email->to($to);
        $this->email->subject($object); // 发送标题
        $this->email->message($message);  //  内容
        $this->email->send();
        $status = $this->email->print_debugger();
        return $status;
    }

    public function ajaxAutoLogin($active)
    {
        $user = $this->get_cur_user();

        if (! $user) {
            header('Location: /', TRUE ,307);
            return;
        }

        if (! in_array($active, array(0, 1))) {
            echo "参数错误，请不要修改链接";
            return;
        }

        $result = $this->user_model->updateUserInfo($user->id, array('is_cookie' => (int)$active));
        if ($result) {
            echo "保存成功";
        } else {
            echo "保存失败，请重试！";
        }
    }

    public function ajaxAutoBookmark($active)
    {
        $user = $this->get_cur_user();

        if (! $user) {
            header('Location: /', TRUE ,307);
            return;
        }

        if (! in_array($active, array(0, 1))) {
            echo "参数错误，请不要修改链接";
            return;
        }

        $result = $this->user_model->updateUserInfo($user->id, array('is_auto_bookmark' => (int)$active));
        if ($result) {
            echo "保存成功";
        } else {
            echo "保存失败，请重试！";
        }
    }
}
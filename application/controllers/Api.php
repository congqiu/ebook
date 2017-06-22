<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('user_model');
        $this->load->helper('url_helper');
        $this->load->library('user_agent');
    }

    private function addBookToBookcaseHelp($book, $user)
    {
        $hasOldBookcase = $this->books_model->getBookFromBookcase($book, $user, 0);
        if ($hasOldBookcase) {
            $result = $this->books_model->updateBookcaseStatus($book, $user, 1);
        } else {
            $result = $this->books_model->addBookToBookcase($book, $user);
        }
        return $result;
    }

    private function echoJson($data)
    {
        if (isset($_GET['callback']) && $_GET['callback']) {
            $data = $_GET['callback'] . '(' . json_encode($data) . ')';
            echo $data;
            return;
        }
        echo json_encode($data);
        return;
    }

    private function checkUser()
    {
        $user_id = isset($_GET['user']) ? $_GET['user'] : "";
        $username = isset($_GET['username']) ? $_GET['username'] : "";
        $token = isset($_GET['token']) ? $_GET['token'] : "";
        $login = false;

        if (! $token) {
            return false;
        }

        if ($user_id) {
            $user = $this->user_model->getUserById($user_id);
        } else if ($username) {
            $user = $this->user_model->getUserByUserName($username);
        } else {
            return false;
        }
        if ($user) {
           $login = $this->user_model->getLoginByTokenAndUser($user->username, $token);
        }
        
        $platform = $this->agent->platform();
        $browser = $this->agent->browser();

        $logined = $login && $platform == $login->platform && $login->browser == $browser;
        if ($logined) {
            return true;
        } else {
            return false;
        }
    }

    private function checkToken()
    {
        $token = isset($_GET['token']) ? $_GET['token'] : "";
        $login = false;

        if (! $token) {
            return false;
        }

        $login = $this->user_model->getLoginByToken($token);
        
        $platform = $this->agent->platform();
        $browser = $this->agent->browser();

        $logined = $login && $platform == $login->platform && $login->browser == $browser;
        if ($logined) {
            return true;
        } else {
            return false;
        }
    }

    public function index($page = 0)
    {
        return;
    }

    public function category($category, $page = 0)
    {
        return;
    }

    public function user()
    {
        $username = isset($_GET['username']) ? $_GET['username'] : "";
        $password = isset($_GET['password']) ? $_GET['password'] : "";
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        $token = isset($_GET['token']) ? $_GET['token'] : "";

        $platform = $this->agent->platform();
        $browser = $this->agent->browser();
        $ip = $this->input->ip_address();
        $version = $this->agent->version();
        $robot = $this->agent->robot();
        $mobile = $this->agent->mobile();

        $data = array('data' => array(), 'version' => 1);

        if (! in_array($action, array('login', 'check', 'logout'))) {
            $data['message'] = "请勿修改参数！";
            $this->echoJson($data);
            return;
        }

        if ($action == 'logout' && $username && $token != null) {
            $login = $this->user_model->getLoginByTokenAndUser($username, $token);
            if ($login) {
                $this->user_model->updateUserLoginByToken($token, array('status' => 0));
            }
            $data['status'] = array('success' => 1, 'logout' => 1);
            $data['code'] = 200;
            $data['message'] = "登出成功！";
            $this->echoJson($data);
            return;
        }

        if ($action == 'check') {
            if ($this->checkUser()) {
                $data['status'] = array('success' => 1);
                $data['code'] = 200;
                $data['data']['login'] = 1;
                $data['message'] = "已登录";
            } else {
                $data['status'] = array('success' => 0);
                $data['code'] = 403;
                $data['data']['login'] = 0;
                $data['message'] = "未登录";
            }
            $this->echoJson($data);
            return;
        }

        if ($username == "" || $password == "") {
            $data['status'] = array('success' => 0);
            $data['code'] = 404;
            $data['message'] = "请填写用户名或密码！";
            $this->echoJson($data);
            return;
        }

        $user = $this->user_model->getUserByUserName($username);

        if ($action == 'login' && $user != null && password_verify($password, $user->password)) {
            $logins = $this->user_model->getLoginsByCondition(array('user' => $user->id, 'platform' => $platform, 'browser' => $browser, 'status' => 1));
            if (! $logins) {
                $token = md5($ip . $platform . $username . $this->getRandChar(16) . time() . $browser);
                $this->user_model->addUserLogin($user->id, $username, $token, $ip, $platform, $browser, $version, $robot, $mobile);
            } else {
                $token = $logins[0]->token;
            }
            if ($user->is_cookie) {
                $data['data']['is_cookie'] = 1;
                $data['data']['username'] = $user->username;
            }
            $data['data']['token'] = $token;
            $data['status'] = array('success' => 1, 'login' => 1);
            $data['code'] = 200;
            $data['message'] = "登录成功！";
            $this->echoJson($data);
            return;
        } else if ($action == 'login' && $user != null && $user->md5pwd == md5($username . $password . $user->create_time)) {
            $logins = $this->user_model->getLoginsByCondition(array('user' => $user->id, 'platform' => $platform, 'browser' => $browser, 'status' => 1));
            if (! $logins) {
                $token = md5($ip . $platform . $username . $this->getRandChar(16) . time() . $browser);
                $this->user_model->addUserLogin($user->id, $username, $token, $ip, $platform, $browser, $version, $robot, $mobile);
            } else {
                $token = $logins[0]->token;
            }
            if ($user->is_cookie) {
                $data['data']['is_cookie'] = 1;
                $data['data']['username'] = $user->username;
            }
            $data['data']['token'] = $token;
            $data['status'] = array('success' => 1, 'login' => 1);
            $data['code'] = 200;
            $data['message'] = "登录成功！";
            $this->echoJson($data);
            return;
        } else {
            $data['status'] = array('success' => 1, 'login' => 0);
            $data['code'] = 203;
            $data['message'] = "用户名或密码错误！";
            $this->echoJson($data);
            return;
        }
    }

    public function book()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $bookname = isset($_GET['name']) ? $_GET['name'] : "";
        $message = "";
        $book = "";

        if ($id) {
            $book = $this->books_model->getBookById($id);
        }
        if ($bookname) {
            $books = $this->books_model->getBooksByName($bookname);
            $book = $books[0];
            $id = $books[0]->id;
        }

        $data = array('data' => array(), 'version' => 1);
        if (!$book) {
            $data['status'] = array('success' => 0);
            $data['code'] = 404;
            $data['message'] = "没有找到对应的书！";
            $this->echoJson($data);
            return;
        }

        $latest = $this->books_model->getBookLatestPage($id);

        if (!$latest) {
            $data['message'] = '该书暂无内容，请先加书架！';
            $data['status'] = array('success' => 1, 'latest' => 0);
            $data['data'] = array('book' => $book);
            $data['code'] = 201;
            $this->echoJson($data);
            return;
        }

        $latest_arr = array();
        if ($latest) {
            $latest_arr = array('num' => $latest->num, 'title' => $latest->title, 'time' => date('Y-m-d', $latest->create_time));
        }

        $catalogs = $this->books_model->getBookCatalog($id);
        $catalog_arr = array();
        foreach ($catalogs as $catalog) {
            $catalog_arr[] = array('title' => $catalog->title, 'num' => $catalog->num, 'pre' => $catalog->pre, 'nex' => $catalog->nex);
        }

        $data['message'] = '查询成功！';
        $data['status'] = array('success' => 1, 'latest' => 1);
        $data['data'] = array('book' => $book, 'latest' => $latest_arr, 'catalogs' => $catalog_arr);
        $data['code'] = 200;
        $this->echoJson($data);
        return;
    }

    public function page()
    {
        $book = isset($_GET['book']) ? $_GET['book'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $username = isset($_GET['username']) ? $_GET['username'] : "";
        $message = "";
        $user = "";
        $data = array('data' => array(), 'version' => 1);

        if ($id < 0) {
            $data['status'] = array('success' => 0);
            $data['code'] = 404;
            $data['message'] = "没有该章节！";
            $this->echoJson($data);
            return;
        }

        $bookpage = $this->books_model->getBookPageByNum($book, $id);

        if (!$bookpage) {
            $data['status'] = array('success' => 0);
            $data['code'] = 404;
            $data['message'] = "没有该章节！";
            $this->echoJson($data);
            return;
        }

        if ($username) {
            $user = $this->user_model->getUserByUserName($username);
        }

        if ($this->checkUser()) {
            $data['is_cookie'] = (int)$user->is_cookie;
        }

        $data['status'] = array('success' => 1, 'bookpage' => 1);
        $data['code'] = 200;
        $data['data'] = array('book' => $book, 'bookpage' => $bookpage);
        $data['message'] = "查询成功！";
        $this->echoJson($data);
        return;
    }

    public function search()
    {
        $name = isset($_GET['search']) ? $_GET['search'] : "";
        $type = isset($_GET['type']) ? $_GET['type'] : "";
        $types = isset($_GET['types']) ? $_GET['types'] : "";
        $books = array();
        
        if (! $this->checkUser()) {
            $data['status'] = array('success' => 0);
            $data['code'] = 403;
            $data['data']['login'] = 0;
            $data['message'] = "请登录后在操作！";
            $this->echoJson($data);
            return;
        };

        if ($name) {
            $books = $this->books_model->getBooksByName($name, TRUE);
        }
        if ($type) {
            $books = $this->books_model->getBooksByCondition(array('type' => $type), 10);
        }
        if ($types) {
            foreach ($types as $type) {
                $books[$type] = $this->books_model->getBooksByCondition(array('type' => $type), 10);
            }
        }

        $count = sizeof($books);
        
        $data['status'] = array('success' => 1);
        $data['code'] = 200;
        $data['data'] = array('search' => $name, 'books' => $books, 'count' => $count);
        $data['message'] = "查询成功！";
        $this->echoJson($data);
        return;
    }

    public function bookcase()
    {
        $token = isset($_GET['token']) ? $_GET['token'] : "";

        if (! $this->checkToken()) {
            $data['status'] = array('success' => 0);
            $data['code'] = 403;
            $data['data']['login'] = 0;
            $data['message'] = "请登录后在操作！";
            $this->echoJson($data);
            return;
        };

        $login = $this->user_model->getLoginByToken($token);

        $data = array('data' => array(), 'version' => 1);
      

        $bookcases = $this->books_model->getBooksByUser($login->user);
        $books = array();
        $latests = array();
        $bookmarks = array();
        foreach ($bookcases as $index => $bookcase) {
            $bookid = $bookcase->book;
            $_book = $this->books_model->getBookById($bookid);
            $books[$bookid] = $_book;
            $_latest = $this->books_model->getBookLatestPage($bookid);
            $latest_arr = array();
            if ($_latest) {
                $latest_arr = array('num' => $_latest->num, 'title' => $_latest->title);
            }
            $books[$bookid]->latest = $latest_arr;

            $_bookmark = $this->books_model->getBookPageById($bookcase->bookmark);
            $bookmark_arr = array();
            if ($_bookmark) {
                $bookmark_arr = array('num' => $_bookmark->num, 'title' => $_bookmark->title);
            }
            $books[$bookid]->bookmark = $bookmark_arr;
            $books[$bookid]->order = $index;
        }

        $data['code'] = 200;
        $data['data'] = array('books' => $books);
        $data['status'] = array('success' => 1, 'user' => 1);
        $data['message'] = "查询成功！";
        $this->echoJson($data);
        return;
    }

    public function booktype()
    {
        $types = $this->books_model->getBookTypes();
        
        $data['status'] = array('success' => 1);
        $data['code'] = 200;
        $data['data'] = array('types' => $types);
        $data['message'] = "查询成功！";
        $this->echoJson($data);
        return;
    }

    public function ajaxAddToBookcase()
    {
        $bookid = isset($_GET['bookid']) ? $_GET['bookid'] : "";
        $username = isset($_GET['username']) ? $_GET['username'] : "";

        if (! $this->checkUser()) {
            $data['status'] = array('success' => 0);
            $data['code'] = 403;
            $data['data']['login'] = 0;
            $data['message'] = "请登录后在操作！";
            $this->echoJson($data);
            return;
        };

        if (! $bookid) {
            $data['status'] = array('success' => 0);
            $data['code'] = 400;
            $data['message'] = "缺少参数！";
            $this->echoJson($data);
            return;
        }

        $book = $this->books_model->getBookById($bookid);
        if (! $book) {
            $data['status'] = array('success' => 1, 'book' => 0);
            $data['code'] = 201;
            $data['message'] = "该书不存在！";
            $this->echoJson($data);
            return;
        }

        $user = $this->user_model->getUserByUserName($username);
        

        $check = $this->books_model->getBookFromBookcase($bookid, $user->id, 1);
        if ($check) {
            $data['status'] = array('success' => 1, 'book' => 1, 'bookcase' => 0);
            $data['code'] = 202;
            $data['message'] = "该书已经在书架！";
            $this->echoJson($data);
            return;
        }

        $result = $this->addBookToBookcaseHelp($bookid, $user->id);
        
        if ($result) {
            $data['status'] = array('success' => 1, 'book' => 1, 'bookcase' => 1);
            $data['code'] = 200;
            $data['message'] = "添加成功！";
            $this->echoJson($data);
            return;
        } else {
            $data['status'] = array('success' => 1, 'book' => 1, 'bookcase' => 1);
            $data['code'] = 203;
            $data['message'] = "添加失败！";
            $this->echoJson($data);
            return;
        }
        return;
    }

    public function ajaxRemoveBookcase()
    {
        $bookid = isset($_GET['bookid']) ? $_GET['bookid'] : "";
        $username = isset($_GET['username']) ? $_GET['username'] : "";

        if (! $this->checkUser()) {
            $data['status'] = array('success' => 0);
            $data['code'] = 403;
            $data['data']['login'] = 0;
            $data['message'] = "请登录后在操作！";
            $this->echoJson($data);
            return;
        };

        if (! $bookid) {
            $data['status'] = array('success' => 0);
            $data['code'] = 400;
            $data['message'] = "缺少参数！";
            $this->echoJson($data);
            return;
        }

        $book = $this->books_model->getBookById($bookid);
        if (! $book) {
            $data['status'] = array('success' => 1, 'book' => 0);
            $data['code'] = 201;
            $data['message'] = "该书不存在！";
            $this->echoJson($data);
            return;
        }

        $user = $this->user_model->getUserByUserName($username);

        $check = $this->books_model->getBookFromBookcase($bookid, $user->id);
        if (! $check) {
            $data['status'] = array('success' => 1, 'book' => 1, 'bookcase' => 0);
            $data['code'] = 202;
            $data['message'] = "该书不在书架！";
            $this->echoJson($data);
            return;
        }

        $result = $this->books_model->updateBookcaseStatus($book->id, $user->id, 0);
        if ($result) {
            $data['status'] = array('success' => 1, 'book' => 1, 'bookcase' => 1);
            $data['code'] = 200;
            $data['message'] = "移除成功！";
            $this->echoJson($data);
            return;
        } else {
            $data['status'] = array('success' => 1, 'book' => 1, 'bookcase' => 1);
            $data['code'] = 203;
            $data['message'] = "移除失败！";
            $this->echoJson($data);
            return;
        }
        return;
    }

    public function ajaxAddBookmark()
    {
        $username = isset($_GET['username']) ? $_GET['username'] : "";
        $pageid = isset($_GET['bookpage']) ? $_GET['bookpage'] : "";

        $data = array('data' => array(), 'version' => 1);

        if (!$username || !$pageid) {
            $data['status'] = array('success' => 0);
            $data['code'] = 400;
            $data['message'] = "缺少参数！";
            $this->echoJson($data);
            return;
        }

        if (! $this->checkUser()) {
            $data['status'] = array('success' => 0);
            $data['code'] = 401;
            $data['message'] = "用户不存在";
            $this->echoJson($data);
            return;
        }

        $bookpage = $this->books_model->getBookPageById($pageid);
        if (! $bookpage) {
            $data['status'] = array('success' => 1, 'bookpage' => 0);
            $data['code'] = 201;
            $data['message'] = "该章节不存在！";
            $this->echoJson($data);
            return;
        }

        $bookid = $bookpage->book;

        $user = $this->user_model->getUserByUserName($username);

        $bookcase = $this->books_model->getBookFromBookcase($bookid, $user->id);
        $cresult = TRUE;
        if (! $bookcase) {
            $cresult = $this->addBookToBookcaseHelp($bookid, $user->id);
            $bookcase = $this->books_model->getBookFromBookcase($bookid, $user->id);
        }

        $result = $this->books_model->addBookmark($bookcase->id, $pageid);
        if ($result && $cresult) {
            $data['status'] = array('success' => 1, 'bookpage' => 1, 'bookmark' => 1);
            $data['code'] = 200;
            $data['message'] = "添加成功";
            $this->echoJson($data);
            return;
        } else {
            $data['status'] = array('success' => 1, 'bookpage' => 1, 'bookmark' => 0);
            $data['code'] = 203;
            $data['message'] = "添加失败";
            $this->echoJson($data);
            return;
        }
        return;
    }
}
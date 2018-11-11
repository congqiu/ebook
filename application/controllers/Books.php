<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books extends MY_Controller {

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

    public function index($page = 0)
    {

        if (!$this->is_login()) {
            redirect('/login');
        }
        $books = $this->books_model->getBooks(8);
        $types = $this->books_model->getBookTypes();
        foreach ($types as $type) {
            $type_books[$type->id] = $this->books_model->getBooksByCondition(array('type' => $type->id), 8);
        }
        

        $data = array(
            'page' => 'book-index',
            'user' => $this->get_cur_user(),
            'books' => $books,
            'types' => $types,
            'type_books' => $type_books,
        );

        $this->load->view('layout/header', $data);
        $this->load->view('books/index');
        $this->load->view('layout/footer');
    }

    public function category($category, $page = 0)
    {
        $this->load->library('pagination');

        $category = urldecode($category);
        $type = $this->books_model->getCategoryByName($category);
        if ($type) {
            $books = $this->books_model->getBooksByCondition(array('type' => $type->id), 10, $page);
        } else {
            show_404();
        }

        $all = $this->books_model->getBooksByCondition(array('type' => $type->id));
        
        $config['base_url'] = base_url('/book/category/' . $category);
        $config['total_rows'] = sizeof($all);
        $config['per_page'] = 10;
        $config['use_page_numbers'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最后一页';

        $this->pagination->initialize($config);

        $data = array(
            'page' => 'book-category',
            'pagination' => $this->pagination->create_links(),
            'user' => $this->get_cur_user(),
            'books' => $books,
            'type' => $type,
        );

        $this->load->view('layout/header', $data);
        $this->load->view('books/category');
        $this->load->view('layout/footer');
    }

    public function book($id)
    {
        $message = "";

        $book = $this->books_model->getBookById($id);
        if (!$book) {
            show_404();
        }

        $latest = $this->books_model->getBookLatestPage($id);
        // $latest = NULL;

        if (!$latest) {
            $message = '暂无该书内容，敬请期待！';
        }

        $catalogs = $this->books_model->getBookCatalog($id);

        $data = array(
            'meta_title' => $book->name . '--书友小说',
            'user' => $this->get_cur_user(),
            'page' => 'book-catalog',
            'message' => $message,
            'book' => $book,
            'catalogs' => $catalogs,
            'latest' => $latest
        );

        $this->load->view('layout/header', $data);
        $this->load->view('books/book');
        $this->load->view('layout/footer');
    }

    public function page($book, $id)
    {
        $this->db->cache_off();
        $message = "";

        if ($id < 0) {
            redirect('/book/' . $book);
        }

        $bookpage = $this->books_model->getBookPageByNum($book, $id);

        if (!$bookpage) {
            redirect('/');
        }
        $user = $this->get_cur_user();
        if ($user && $user->is_auto_bookmark) {
            $bookcase = $this->books_model->getBookFromBookcase($book, $user->id);
            if ($bookcase) {
                $result = $this->books_model->addBookmark($bookcase->id, $bookpage->id);
            }
        }

        $data = array(
            'page' => 'book-page',
            'user' => $user,
            'isMobile' => $this->agent->mobile(),
            'meta_title' => $bookpage->title . '--书友小说',
            'message' => $message,
            'book' => $book,
            'bookpage' => $bookpage,
        );

        $this->load->view('layout/header', $data);
        $this->load->view('books/bookpage');
        $this->load->view('layout/footer');
    }

    public function search()
    {
        $this->load->helper('form');

        $data['meta_title'] = '书友小说';
        $message = "";

        $name = $this->input->post('name');

        if ($name) {
            $books = $this->books_model->getBooksByName($name, TRUE);
        } else {
            $books = $this->books_model->getBooks(8);
        }
        
        $data = array(
            'page' => 'book-search',
            'user' => $this->get_cur_user(),
            'message' => $message,
            'books' => $books,
        );

        $this->load->view('layout/header', $data);
        $this->load->view('books/search');
        $this->load->view('layout/footer');
    }

    public function bookcase()
    {

        if (!$this->is_login()) {
            redirect('/login');
        }

        $user = $this->get_cur_user();

        $bookcases = $this->books_model->getBooksByUser($user->id);
        $books = array();
        $latests = array();
        $bookmarks = array();
        // $this->db->cache_on();
        foreach ($bookcases as $bookcase) {
            $bookid = $bookcase->book;
            $books[$bookid] = $this->books_model->getBookById($bookid);
            // $this->db->cache_off();
            $latests[$bookid] = $this->books_model->getBookLatestPage($bookid);
            // $this->db->cache_on();
            $bookmarks[$bookid] = $this->books_model->getBookPageById($bookcase->bookmark);
        }
        // $this->db->cache_off();
        
        $data = array(
            'page' => 'bookcase',
            'user' => $this->get_cur_user(),
            'books' => $books,
            'latests' => $latests,
            'bookmarks' => $bookmarks,
        );

        $this->load->view('layout/header', $data);
        $this->load->view('books/bookcase');
        $this->load->view('layout/footer');
    }

    public function ajaxAddToBookcase()
    {
        if (!$this->is_login()) {
            echo "请先登录再操作！";
            return;
        }

        $bookid = $this->uri->segment(4, 0);
        $cid = $this->uri->segment(5, '0');

        if ($cid == '1') {
            echo "该书已经在书架！";
            return;
        }

        $book = $this->books_model->getBookById($bookid);
        if (! $book) {
            echo "该书不存在！";
            return;
        }

        $user = $this->get_cur_user();

        $check = $this->books_model->getBookFromBookcase($bookid, $user->id, 1);
        if ($check) {
            echo "该书已经在书架！";
            return;
        }

        $result = $this->addBookToBookcaseHelp($bookid, $user->id);

        if ($result) {
            echo "添加成功！";
        } else {
            echo "添加失败！";
        }
        return;
    }

    public function ajaxRemoveBookcase()
    {
        if (!$this->is_login()) {
            echo "请先登录再操作！";
            return;
        }

        $bookid = $this->uri->segment(4, 0);
        $cid = $this->uri->segment(5, '0');

        if ($cid == '1') {
            echo "该书不在书架！";
            return;
        }

        $book = $this->books_model->getBookById($bookid);
        if (! $book) {
            echo "该书不存在！";
            return;
        }

        $user = $this->get_cur_user();

        $check = $this->books_model->getBookFromBookcase($bookid, $user->id);
        if (! $check) {
            echo "该书不在书架！";
            return;
        }

        $result = $this->books_model->updateBookcaseStatus($book->id, $user->id, 0);
        if ($result) {
            echo "移除成功！";
        } else {
            echo "移除失败！";
        }
        return;
    }

    public function ajaxAddBookmark()
    {
        if (!$this->is_login()) {
            echo "请先登录再操作！";
            return;
        }

        $pageid = $this->uri->segment(4, -1);

        $bookpage = $this->books_model->getBookPageById($pageid);
        if (! $bookpage) {
            echo "该页面不存在！";
            return;
        }
        $bookid = $bookpage->book;

        $user = $this->get_cur_user();

        $bookcase = $this->books_model->getBookFromBookcase($bookid, $user->id);
        $cresult = TRUE;
        if (! $bookcase) {
            $cresult = $this->addBookToBookcaseHelp($bookid, $user->id);
            $bookcase = $this->books_model->getBookFromBookcase($bookid, $user->id);
        }

        $result = $this->books_model->addBookmark($bookcase->id, $pageid);
        if ($result && $cresult) {
            echo "添加成功！";
        } else {
            echo "添加失败！";
        }
        return;
    }

    public function addNewBook()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('books_model');

        $user = $this->get_cur_user();
        if (!$user || ($user->type != 'admin')) {
            redirect('/');
        }

        $types = $this->books_model->getBookTypes();

        foreach ($types as $type) {
            $book_types[$type->id] = $type->name;
        }

        $data['title'] = '添加新的书籍来源';
        $data['types'] = $book_types;


        $this->form_validation->set_rules('name', '书名', 'required|min_length[1]');
        $this->form_validation->set_rules('author', '作者', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('source_desc', '介绍', 'trim|required|min_length[1]');
        
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('layout/header', $data);
            $this->load->view('books/add-new-book', $data);
            $this->load->view('layout/footer');

        } else {
            $result = $this->books_model->addNewBook();
            if ($result) {
                redirect('/book/add/source/' . $result);
            } else {
               $this->load->view('layout/header', $data);
                $this->load->view('books/add-new-book', $data);
                $this->load->view('layout/footer'); 
            }
        }
    }

    public function addBookSource($bookid)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('books_model');

        $book = $this->books_model->getBookById($bookid);

        if (! $book) {
            show_404();
        }

        $user = $this->get_cur_user();
        if (!$user || ($user->type != 'admin')) {
            redirect('/');
        }

        $data['title'] = '添加新的书籍来源';
        $data['book'] = $book;

        $this->form_validation->set_rules('source', '来源', 'required|min_length[1]');
        $this->form_validation->set_rules('alias', '网站', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('book', '书ID', 'trim|required|min_length[1]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('layout/header', $data);
            $this->load->view('books/add-book-source', $data);
            $this->load->view('layout/footer');

        } else {
            $this->books_model->addBookSource();
            redirect('/');
        }
    }
}
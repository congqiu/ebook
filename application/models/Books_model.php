<?php
class Books_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getBooks($limit = NULL, $offset = NULL)
	{
	    $query = $this->db->get('book', $limit, $offset);
    	return $query->result();
	}

    public function getBooksByCondition($condition = array(), $limit = NULL, $offset = NULL)
	{
	    $query = $this->db->get_where('book', $condition, $limit, $offset);
    	return $query->result();
	}

    public function getBooksByName($name, $like = FALSE, $limit = NULL, $offset = NULL)
	{
		if ($like) {
			$this->db->like('name', $name);
			$query = $this->db->get('book', $limit, $offset);
		} else {
			$query = $this->db->get_where('book', array('name' => $name), $limit, $offset);
		}
    	return $query->result();
	}

    public function getBookById($id = 1)
	{
	    $query = $this->db->get_where('book', array('id' => (int)$id));
    	return $query->row();
	}

    public function getBookLatestPage($id = 1)
	{
		$this->db->order_by('id', 'DESC');
	    $query = $this->db->get_where('bookpage', array('book' => (int)$id, 'nex' => -2));
    	return $query->row();
	}

    public function getBookCatalog($id = 1)
	{
		$this->db->order_by('num');
	    $query = $this->db->get_where('bookpage', array('book' => (int)$id));
    	return $query->result();
	}

    public function getBookPageById($id = 1)
	{
	    $query = $this->db->get_where('bookpage', array('id' => $id));
    	return $query->row();
	}

    public function getBookPageByNum($book, $num = 1)
	{
	    $query = $this->db->get_where('bookpage', array('book' => $book, 'num' => $num));
    	return $query->row();
	}

    public function getBooksByUser($id)
	{
		$this->db->order_by('change_time', 'DESC');
	    $query = $this->db->get_where('bookcase', array('user' => $id, 'status' => 1));
    	return $query->result();
	}

	public function getBookFromBookcase($book, $user, $status = FALSE)
	{
		$condition = array('book' => $book, 'user' => $user);
		if ($status !== FALSE) {
			$condition['status'] = $status;
		}
		$query = $this->db->get_where('bookcase', $condition);
    	return $query->row();
	}

	public function getAllBooksFromBookcase($condition = array(), $status = FALSE)
	{
		if ($status !== FALSE) {
			$condition['status'] = $status;
		}
		// $this->db->group_by('book');
		// $this->db->select_min('book');
		$query = $this->db->get_where('bookcase', $condition);
    	return $query->result_array();
	}

	public function updateBookcase($bookcase, $data)
	{
		$date = time();

	    $data['change_time'] = $date;

		$this->db->where('id', $bookcase);
	    return $this->db->update('bookcase', $data);
	}

	public function updateBookcaseStatus($book, $user, $status = 0)
	{
		$date = time();

		$data = array(
	        'status' => $status,
	        'change_time' => $date
	    );

		$this->db->where('book', $book);
		$this->db->where('user', $user);
	    return $this->db->update('bookcase', $data);
	}

    public function addBookToBookcase($book, $user)
	{
		$date = time();

		$data = array(
	        'book' => $book,
	        'user' => $user,
	        'status' => 1,
	        'create_time' => $date,
	        'change_time' => $date,
	    );

	    return $this->db->insert('bookcase', $data);
	}

    public function addBookmark($bookcase, $bookpage)
	{
		$date = time();

		$data = array(
	        'bookmark' => $bookpage,
	        'change_time' => $date
	    );

		$this->db->where('id', $bookcase);
	    return $this->db->update('bookcase', $data);
	}

    public function getBookTypes($limit = NULL, $offset = NULL)
	{
	    $query = $this->db->get('booktype', $limit, $offset);
    	return $query->result();
	}

    public function getCategoryByName($name)
	{
	    $query = $this->db->get_where('booktype', array('name' => $name));
    	return $query->row();
	}

    public function addBookSource()
	{
		$date = time();

		$porder = $this->input->post('order');
		$order = $porder ? $porder : 0;

		$data = array(
	        'book' => $this->input->post('book'),
	        'source' => $this->input->post('source'),
	        'order' => $order,
	        'alias' => $this->input->post('alias'),
	        'status' => 1,
	        'create_time' => $date,
	        'change_time' => $date,
	    );

	    return $this->db->insert('book_source', $data);
	}

    public function getBookAllSource($book, $limit = NULL, $offset = NULL)
	{
	    $query = $this->db->get_where('book_source', array('book' => $book), $limit, $offset);
    	return $query->result();
	}

    public function addNewBook()
	{
		$date = time();

		$data = array(
	        'name' => $this->input->post('name'),
	        'author' => $this->input->post('author'),
	        'source_desc' => $this->input->post('source_desc'),
	        'type' => $this->input->post('type'),
	        'source_status' => $this->input->post('source_status'),
	        'status' => $this->input->post('status'),
	        'create_time' => $date,
	        'change_time' => $date,
	    );

	    $this->db->insert('book', $data);
	    return $this->db->insert_id();
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->load->model('book_model');
		$this->load->model('author_model');
		$this->load->helper('string');
		$this->load->library('pagination');
		$this->load->library('cart');
	}
	public function index($offset="0"){
		
		$config['base_url'] = site_url('admin/book/index');
		$config['per_page'] = 500;
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->book_model->count_book();
		$config['num_links'] = 2;

		$config['first_link'] = '&nbsp;First&nbsp;';
		$config['last_link'] = '&nbsp;Last&nbsp;';
		$config['prev_link'] = '&nbsp;Previous&nbsp;';
		$config['next_link'] = '&nbsp;Next&nbsp;';

		$this->pagination->initialize($config);
		$limits = $config['per_page'];
		$data['pageinfo'] = $this->pagination->create_links();
		$data['books'] = $this->book_model->list($limits,$offset);
		$this->load->view('listbook.html',$data);
	}
	public function add(){
		$this->load->view('addbook.html');
	}
	public function insert(){
		$data['Title'] = $this->input->post('title');
		$data['Author'] = $this->input->post('author');
		$data['Cover'] = $this->input->post('cover');
		$data['Publisher'] = $this->input->post('publisher');
		$data['Pages'] = $this->input->post('pages');
		$data['Isbn'] = random_string('numeric', 10);
		$data['Isbn13'] = random_string('numeric', 13);
		if($this->author_model->insert($data['Author'])&$this->book_model->insert($data)){
			$data['message'] = 'Insert Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/book/index');
			$this->load->view('message.html',$data);
		}else{
			$data['message'] = 'Insert Unsuccessfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/book/index');
			$this->load->view('message.html',$data);
		}
	}
	public function edit($Isbn){
		$data['book'] = $this->book_model->get($Isbn);
		$this->load->view('editbook.html',$data);
	}
	public function update(){
		$data['Isbn'] = $this->input->post('Isbn');
		$data['Title'] = $this->input->post('Title');
		if($this->book_model->update($data)){
			$data['message'] = 'Update Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/book/index');
			$this->load->view('message.html',$data);
		}else{
			$data['message'] = 'Update Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/book/index');
			$this->load->view('message.html',$data);
		}
	}
	public function delete($Isbn){
		if($this->book_model->delete($Isbn)){
			
			$data['message'] = "Delete Successfully";
			$data['wait'] = 3;
			$data['url'] = site_url('admin/book/index');
			$this->load->view('message.html',$data);
		}else{
			$data['message'] = "Delete Unsuccessfully";
			$data['wait'] = 3;
			$data['url'] = site_url('admin/book/index');
			$this->load->view('message.html',$data);
		}
	}

	public function find($offset="0"){
		$words = $this->input->post('keywords');
		$config['base_url'] = site_url('admin/book/index');
		$config['per_page'] = 500;
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->book_model->count_search_books($words);
		$config['num_links'] = 2;

		$config['first_link'] = '&nbsp;First&nbsp;';
		$config['last_link'] = '&nbsp;Last&nbsp;';
		$config['prev_link'] = '&nbsp;Previous&nbsp;';
		$config['next_link'] = '&nbsp;Next&nbsp;';

		$this->pagination->initialize($config);
		$limits = $config['per_page'];
		$data['pageinfo'] = $this->pagination->create_links();
		$data['books'] = $this->book_model->search($words,$limits,$offset);
		$this->load->view('listbook.html',$data);
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->load->model('loan_model');
		$this->load->model('book_model');
		$this->load->model('borrower_model');
		$this->load->model('fine_model');
		$this->load->helper('string');
		$this->load->library('pagination');
		$this->load->library('form_validation');
	}
	public function add(){
		$this->load->view('addorder.html');
	}
	public function insert(){
		$data['Isbn'] = $this->input->post('Isbn');
		$data['Card_id'] = $this->input->post('Card_id');
		$data['Loan_id'] = random_string('alnum', 10);
		$this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');
		$this->form_validation->set_rules('Isbn','Isbn','htmlspecialchars|required',
			array(
				'required' => 'Isbn is required'
		));
		$this->form_validation->set_rules('Card_id','Card_id','htmlspecialchars|required',
			array(
				'required' => 'Card_id is required'
		));
		if(!$this->form_validation->run()){
			$this->load->view('addorder.html');
		}else if(is_null($this->loan_model->getByIsbn($data['Isbn']))){
			$data['message'] = 'This Book already been checked out';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/index');
			$this->load->view('message.html',$data);
		}else if(!$this->loan_model->getIsbn($data['Isbn'])){
			$data['message'] = 'This Book does not exist!';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/index');
			$this->load->view('message.html',$data);
		}else if($this->loan_model->getById($data['Card_id'])>2){
			$data['message'] = 'the borrower has already borrow 3 Book, please return them before borrow new books';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/index');
			$this->load->view('message.html',$data);
		}else if($this->addToCart($data)&$this->loan_model->updateAvailability($data['Isbn'])){
			$data['message'] = 'Chechout Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/index');
			$this->load->view('message.html',$data);
		}else{
			$data['message'] = 'Chechout Unsuccessfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/index');
			$this->load->view('message.html',$data);
		}
	}
	public function addToCart($data){
		$loanCart = $this->session->userdata('loanCart');
		$loanCart[$data['Loan_id']] = $data;
		$this->session->set_userdata('loanCart', $loanCart);
		return true;
		/*$this->loan_model->insert($data)&$this->loan_model->updateAvailability($data['Isbn'])*/
	}
	public function listCart(){
		$data['loans'] = $this->session->userdata('loanCart');
		if(is_null($data['loans'])){
			$data['loans']['Loan_id'] = null;
			$data['loans']['Card_id'] = null;
			$data['loans']['Isbn'] = null;
		}
/*		var_dump($data);
		exit();*/
		$this->load->view("listcart.html",$data);
		
		
	}
	public function cartRemove($Isbn){
		$this->loan_model->updateAvailabilityByIsbn($Isbn);
		$loanCart = $this->session->userdata('loanCart');
		foreach ($loanCart as $Loan_id => $value) {
			foreach ($value as $k => $v) {
				if ($k=='Isbn'&&$v==$Isbn) {
					unset($loanCart[$Loan_id]);
				}
			}
		}
		$this->session->set_userdata('loanCart', $loanCart);
		redirect('admin/loan/listCart');
	}
	public function checkOutCart(){
		$loans = $this->input->post();
/*		var_dump(isset($loans));
		exit();*/
		if(empty($loans)){
			$data['message'] = ' Cart Is Empty!!!';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/listCart');
			$this->load->view('message.html',$data);
		}else{
			for( $i = 0; $i<count($loans['card_id']); $i++ ) {
	        	$data['Card_id'] = $loans['card_id'][$i];
	        	$data['Loan_id'] = $loans['loan_id'][$i];
	        	$data['Isbn'] = $loans['isbn'][$i];
	        	if(!$this->loan_model->insert($data)){
		        	$data['message'] = 'Chechout Cart Unsuccessfully';
					$data['wait'] = 3;
					$data['url'] = site_url('admin/loan/listCart');
					$this->load->view('message.html',$data);
	        	}
	        }
	        $this->session->unset_userdata('loanCart');
	        $data['message'] = 'Chechout Cart Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/listCart');
			$this->load->view('message.html',$data);
		}
		
	}
	public function index($offset="0"){
		$config['base_url'] = site_url('admin/borrower/index');
		$config['per_page'] = 100;
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->loan_model->count_loans();
		$config['num_links'] = 2;

		$config['first_link'] = '&nbsp;First&nbsp;';
		$config['last_link'] = '&nbsp;Last&nbsp;';
		$config['prev_link'] = '&nbsp;Previous&nbsp;';
		$config['next_link'] = '&nbsp;Next&nbsp;';

		$this->pagination->initialize($config);
		$limits = $config['per_page'];
		$data['pageinfo'] = $this->pagination->create_links();
		$data['loans'] = $this->loan_model->list($limits,$offset);
		$this->load->view('listorder.html',$data);
	}
	public function find($offset='0'){
		$words = $this->input->post('keywords');
		$config['base_url'] = site_url('admin/loan/index');
		$config['per_page'] = 100;
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->loan_model->count_search_loans($words);
		$config['num_links'] = 2;

		$config['first_link'] = '&nbsp;First&nbsp;';
		$config['last_link'] = '&nbsp;Last&nbsp;';
		$config['prev_link'] = '&nbsp;Previous&nbsp;';
		$config['next_link'] = '&nbsp;Next&nbsp;';

		$this->pagination->initialize($config);
		$limits = $config['per_page'];
		$data['pageinfo'] = $this->pagination->create_links();
		$data['loans'] = $this->loan_model->search($words,$limits,$offset);
		$this->load->view('listorder.html',$data);
	}
	public function checkin($Loan_id){
		if($this->loan_model->checkin($Loan_id)&$this->loan_model->updateInDate($Loan_id)){
			$data['message'] = 'CheckIn Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/index');
			$this->load->view('message.html',$data);
		} else{
			$data['message'] = 'CheckIn Unsuccessfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/loan/index');
			$this->load->view('message.html',$data);
		}
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrower extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->load->model('borrower_model');
		$this->load->helper('string');
		$this->load->library('pagination');
		$this->load->library('cart');
		$this->load->library('form_validation');
	}
	public function add(){
		$this->load->view('adduser.html');
	}
	public function insert(){
		$data['address'] = $this->input->post('address');
		$data['Bname'] = $this->input->post('bname');
		$data['ssn'] = $this->input->post('ssn');
		$data['phone'] = $this->input->post('phone');
		$data['Card_id'] = random_string('alnum', 5).$data['Bname'];
		$this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');
		$this->form_validation->set_rules('address','address','htmlspecialchars|required',
			array(
				'required' => 'address is required'
		));
		$this->form_validation->set_rules('bname','bname','htmlspecialchars|required',
			array(
				'required' => 'bname is required'
		));
		$this->form_validation->set_rules('ssn','ssn','htmlspecialchars|required',
			array(
				'required' => 'ssn is required'
		));
		$this->form_validation->set_rules('phone','PhoneNumber','htmlspecialchars|required',
			array(
				'required' => 'PhoneNumber is required'
		));
		if(!$this->form_validation->run()){
			$this->load->view('adduser.html');
		}else if($this->borrower_model->getBySsn($data['ssn'])){
			$data['message'] = 'This SSN already exists';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/borrower/index');
			$this->load->view('message.html',$data);
		}else if($this->borrower_model->insert($data)){
			$data['message'] = 'Insert Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/borrower/index');
			$this->load->view('message.html',$data);
		}else{
			$data['message'] = 'Insert Unsuccessfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/borrower/index');
			$this->load->view('message.html',$data);
		}
	}
	public function index($offset="0"){
		$config['base_url'] = site_url('admin/borrower/index');
		$config['per_page'] = 500;
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->borrower_model->count_borrower();
		$config['num_links'] = 2;

		$config['first_link'] = '&nbsp;First&nbsp;';
		$config['last_link'] = '&nbsp;Last&nbsp;';
		$config['prev_link'] = '&nbsp;Previous&nbsp;';
		$config['next_link'] = '&nbsp;Next&nbsp;';

		$this->pagination->initialize($config);
		$limits = $config['per_page'];
		$data['pageinfo'] = $this->pagination->create_links();
		$data['borrowers'] =$this->borrower_model->list($limits,$offset);
		$this->load->view('listuser.html',$data);
	}
}
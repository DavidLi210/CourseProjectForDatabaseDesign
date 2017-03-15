<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fine extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->load->model('loan_model');
		$this->load->model('borrower_model');
		$this->load->model('fine_model');
		$this->load->helper('string');
		$this->load->library('pagination');
		$this->load->library('cart');
		$this->load->library('form_validation');
	}
	public function index(){
		$data['fines'] = $this->fine_model->list();
		$this->load->view('listfines.html',$data);
	}
	public function pay($Loan_id){
		if($this->loan_model->checkReturnByLoanid($Loan_id)!=0){
			$data['message'] = 'Please return book before pay for fines';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/fine/index');
			$this->load->view('message.html',$data);
		}else if($this->fine_model->pay($Loan_id)){
			$data['message'] = 'Pay for fines Successfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/fine/index');
			$this->load->view('message.html',$data);
		}else{
			$data['message'] = 'Pay for fines Unsuccessfully';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/fine/index');
			$this->load->view('message.html',$data);
		}
	}
	public function refresh(){
		$data['loans'] = $this->loan_model->getDueLoans();
		if(is_null($data['loans'])){
			$data['message'] = 'There is no one who still doesnt return book or return book after dueday';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/fine/index');
			$this->load->view('message.html',$data);
		}else{
			foreach ($data['loans'] as $loan) {
				if($this->fine_model->exists($loan['Loan_id'])==0){
					$this->fine_model->insert($loan['Loan_id']);
					if(is_null($loan['Date_in'])||$loan['Date_in']=='null'){
						$this->fine_model->updateNotReturned($loan['Loan_id']);
					}else{
						$this->fine_model->updateReturned($loan['Loan_id']);

					}
				}else {
					if(is_null($loan['Date_in'])||$loan['Date_in']=='null'){
						$this->fine_model->updateNotReturned($loan['Loan_id']);
					}else{
						$this->fine_model->updateReturned($loan['Loan_id']);
					}
				}
			}
			/*if($this->fine_model->paid($loan['Loan_id'])=='0')*/
		}
		$data['fines'] = $this->fine_model->list();
		$this->load->view('listfines.html',$data);
	}
}
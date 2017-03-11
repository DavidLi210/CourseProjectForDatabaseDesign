<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends AdminController{
	public function index(){
		$this->load->view('manager.html');
	}
	public function left(){
		$this->load->view('left.html');
	}
	public function head(){
		$this->load->view('head.html');
	}
}
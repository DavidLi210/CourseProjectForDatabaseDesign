<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrower_model extends CI_Model{
	const TL_Borrower ='borrower';
	public function insert($data){
		return $this->db->insert(self::TL_Borrower,$data);
	}
	public function list($limits,$offset){
		$sql = "select * from borrower limit ".$offset.",".$limits;
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function getBySsn($ssn){
		$condition['Ssn'] = $ssn;
		$query = $this->db->where($condition)->get(self::TL_Borrower);
		return $query->row_array();
	}
	public function count_borrower(){
		return $this->db->count_all_results(self::TL_Borrower);
	}
}
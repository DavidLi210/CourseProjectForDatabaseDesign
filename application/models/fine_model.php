<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fine_model extends CI_Model{
	const TL_FINE ='fines';
	public function list(){
		$sql = "select f.*, bl.Card_id from book_loans bl join fines f on bl.Loan_id = f.Loan_id order by bl.Card_id";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function insert($data){
		$sql = "insert into fines values ('".$data."',DEFAULT,DEFAULT)";
		return $this->db->query($sql);
	}
	public function exists($Loan_id){
		$condition = "Loan_id= '".$Loan_id."';";
		$query = $this->db->where($condition)->get(self::TL_FINE);
		return $query->num_rows();
	}
	public function paid($Loan_id){
		$sql = "select paid from fines where Loan_id = '".$Loan_id."'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function pay($Loan_id){
		$sql = "update fines set Paid = 1 where Loan_id ='".$Loan_id."'";
		return $this->db->query($sql);
	}
	public function updateNotReturned($Loan_id){
		$sql = "update fines set Fine_amt = (select TIMESTAMPDIFF(DAY, Due_date,CURRENT_TIMESTAMP )*0.25 from book_loans where Loan_id = '".$Loan_id."') where Loan_id = '".$Loan_id."' and Paid ='0'";
		return $this->db->query($sql);
	}
	public function updateReturned($Loan_id){
		$sql = "update fines set Fine_amt = (select TIMESTAMPDIFF(DAY, Due_date,Date_in )*0.25 from book_loans where Loan_id = '".$Loan_id."') where Loan_id = '".$Loan_id."' and Paid ='0'";
		return $this->db->query($sql);
	}
	 /*select * from book_loans bl where bl.Loan_id in (select Loan_id from fines)*/
}
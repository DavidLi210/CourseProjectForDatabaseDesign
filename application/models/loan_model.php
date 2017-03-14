<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_model extends CI_Model{
	const TL_Book_Loans ='book_loans';
	public function insert($data){
		$sql="insert into book_loans values('".$data['Loan_id']."','".$data['Isbn']."','".$data['Card_id']."',DEFAULT,DATE_ADD(Date_out,INTERVAL 14 DAY),null)";
		return $this->db->query($sql);
	}
	public function list($limits,$offset){
		$sql="select bl.*,b.Bname from book_loans as bl, borrower b where b.Card_id = bl.Card_id limit ".$offset." , ".$limits;
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function count_loans(){
		return $this->db->count_all_results(self::TL_Book_Loans);
	}
	public function getByIsbn($Isbn){
		$condition['Isbn'] = $Isbn;
		$condition['Availability'] = '1';
		$query = $this->db->where($condition)->get('book');
		return $query->row_array();
	}
	public function getById($Card_id){
		$condition = "Card_id= '".$Card_id."' and Date_in is NULL";
		$query = $this->db->where($condition)->get(self::TL_Book_Loans);
		return $query->num_rows();
	}
	public function checkReturnByIsbn($Isbn){
		$condition = "Isbn= '".$Isbn."' and Date_in is NULL";
		$query = $this->db->where($condition)->get(self::TL_Book_Loans);
		return $query->num_rows();
	}
	public function getDueLoans(){
		$sql = "select Loan_id,Date_in from book_loans where (Date_in is null and CURRENT_TIMESTAMP > Due_date) or Date_in > Due_date";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function getIsbn($Isbn){
		$query = $this->db->where('Isbn='.$Isbn)->get('book');
		return $query->row_array();
	}
	public function search($words,$limits,$offset){
		$sql = "select b.Bname,bl.* from book_loans as bl join borrower b on b.Card_id = bl.Card_id where Isbn = '".$words."' or bl.Card_id = '".$words."' or b.Bname like '%".$words."%' limit ".$offset.",".$limits;
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function count_search_loans($words){
		$sql = "select b.Bname,bl.* from book_loans as bl join borrower b on b.Card_id = bl.Card_id where Isbn = '".$words."' or bl.Card_id = '".$words."' or b.Bname like '%".$words."%'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return count($result);
	}
	public function updateAvailability($Isbn){
		$data['Availability'] = '0';
		return $this->db->where("Isbn =".$Isbn)->update('book',$data);
	}
	public function checkin($Loan_id){
		$sql = "update book set Availability = '1' where Isbn = (select Isbn from book_loans where Loan_id = '".$Loan_id."')";
		return $this->db->query($sql);
	}
	public function updateInDate($Loan_id){
		$sql="update book_loans set Date_in = CURRENT_TIMESTAMP where Loan_id = '".$Loan_id."'";
		return $this->db->query($sql);
	}
}
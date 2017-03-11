<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_model extends CI_Model{
	const TL_Book ='book';
	public function insert($data){
		return $this->db->insert(self::TL_Book,$data);
	}
	public function list($limits,$offset){
		$sql = "select Title,Name,availability,b.Isbn from authors as a join BOOK_AUTHORS as ba on (a.Author_id = ba.Author_id) join 
Book b on (ba.Isbn = b.Isbn) limit ".$offset.",".$limits;
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;

	}
	public function delete($book_id){
		return $this->db->where('Isbn=',$book_id)->delete(self::TL_Book);
	}
	public function update($data){
		return $this->db->where('Isbn=',$data['Isbn'])->update(self::TL_Book,$data);
	}
	public function get($book_id){
		$query = $this->db->where("Isbn=",$book_id)->get(self::TL_Book);
		return $query->row_array();
	}
	public function search($words,$limits,$offset){
		$sql = "select Title,Name,availability,b.Isbn from authors as a join BOOK_AUTHORS as ba on (a.Author_id = ba.Author_id) join 
Book b on (ba.Isbn = b.Isbn) where b.Isbn like '%".$words."%' or b.title like '%".$words."%' or Name like '%".$words."%' limit ".$offset.",".$limits;
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function count_book(){
		return $this->db->count_all_results(self::TL_Book);
	}
	public function count_search_books($words){
		$sql = "select Title,Name,availability,b.Isbn from authors as a join BOOK_AUTHORS as ba on (a.Author_id = ba.Author_id) join 
Book b on (ba.Isbn = b.Isbn) where b.Isbn like '%".$words."%' or b.title like '%".$words."%' or Name like '%".$words."%'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return count($result);
	}
	/*public  function admin_index(){
		$query = $this->db->limit($limits,$offset)->get(self::TL_goods);
		return $query->result_array();
	}*/
}


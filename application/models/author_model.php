<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Author_model extends CI_Model{
	const TL_Author ='authors';
	public function insert($name){
		$sql = "insert into authors values(null,'".$name."')";
		return $this->db->query($sql);
	}
}
<?php
require_once 'Model.php';

class User extends Model{

	public $table = 'users';

	function __construct() {
		parent::__construct();
	}

	public function get($id = null){
		if(empty($id)){
			$user = $this->db->exec('SELECT * FROM users');
		}else{
			$user = $this->db->exec('SELECT * FROM users WHERE facebook_id = :id', array('id' => $id));
		}
		return $this->encode('users', $user[0]);
	}

	public function exist($id){
		$user = $this->db->exec('SELECT facebook_id, firstname, lastname, picture, sex
			FROM ' . $this->table . ' WHERE facebook_id = :id',
			array('id' => $id));
		return (count($user) == 1) ? $user[0] : false;
	}

	public function register($user){
		$insert = $this->db->exec('INSERT INTO users(facebook_id, firstname, lastname, picture, sex, created)
			VALUES (:fb_id, :fname, :lname, :picture, :sex, :created)',
				array(
					'fb_id' => $user['facebook_id'],
					'fname' => $user['firstname'],
					'lname' => $user['lastname'],
					'picture' => $user['picture'],
					'sex' => $user['sex'],
					'created' => $this->datetime()
					)
				);
		return ($insert) ? $this->db->lastInsertId() : false;
	}

	public function validate($user){
		$validate = true;

		if(empty($user['facebook_id'])){ return false; }
		if(empty($user['firstname'])){ return false; }
		if(empty($user['lastname'])){ return false; }
		if(empty($user['picture'])){ return false; }
		if(empty($user['sex'])){ return false; }

		return $validate;
	}

}

 ?>
<?php

class User extends AppModel
{
	public $login_verification = true;
	public $validation = array(
			'fname' => array(
				'length' => array(
					'validate_between', 1, 50,
				),
			),

			'lname' => array(
				'length' => array(
					'validate_between', 1, 50,
				),
			),

			'username' => array(
				'length' => array(
					'validate_between', 1, 16,
				),
				'confirmation' => array(
					'username_checker'
				),
			),

			'password' => array(
				'length' => array(
					'validate_between',8, 20,
				),
				'confirmation' => array(
					'password_checker'
				),
			),
		);

	public function password_checker()
	{
		if ($this->password == $this->confirm_password) return true;
	}

	public function username_checker()
	{
		$db = DB::conn();
		$username_not_existing = $db->row('SELECT username FROM user WHERE username = ?', array($this->username));

		if (empty($username_not_existing)) return true;
	}

	public function login()
	{

		$db = DB::conn();
		$row = $db->row('SELECT id, fname FROM user WHERE username = ? AND password = ?', array($this->username, $this->password));

		if (!$row) {
			$this->login_verification =false;
			throw new RecordNotFoundException('no record found');
		}

		return $row;
	}

	public function add()
	{
		$this->validate();
		if ($this->hasError()){
				throw new ValidationException('error in registration');
		}
		
		$db = DB::conn();
		$params = array (
			'fname' => $this->fname, 
			'lname' => $this->lname, 
			'username' => $this->username, 
			'password' => $this->password
		);

		$db->insert('user', $params);
	}

	public function edit()
	{

	}

	public function view()
	{

	}

	
}

?>
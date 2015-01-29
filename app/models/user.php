<?php

class User extends AppModel
{
    const MIN_LENGTH = 1;
    const MIN_LENGTH_PASSWORD = 8;
    const MAX_LENGTH_NAME = 50;
    const MAX_LENGTH_USERNAME = 16;
    const MAX_LENGTH_PASSWORD = 20;

    public $login_verification = true;
    public $validation = array(
            'fname' => array(
                'length' => array(
                    'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_NAME,
                ),
                'confirmation' => array(
                    'name_checker'
                ),
            ),

            'lname' => array(
                'length' => array(
                    'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_NAME,
                ),
                'confirmation' => array(
                    'name_checker'
                ),
            ),

            'username' => array(
                'length' => array(
                    'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_USERNAME,
                ),
                'confirmation' => array(
                    'username_checker'
                ),
            ),

            'password' => array(
                'length' => array(
                    'validate_between', self::MIN_LENGTH_PASSWORD, self::MAX_LENGTH_PASSWORD,
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
        $is_username_existing = $db->row('SELECT username FROM user WHERE username = ?', array($this->username));

        if (!$is_username_existing) return true;
    }

    public function name_checker($name)
    {
        return ctype_alpha($name);
    }

    public function login()
    {

        $db = DB::conn();
        $row = $db->row('SELECT id, fname FROM user WHERE username = ? AND password = ?', 
            array($this->username, $this->password));

        if (!$row) {
            $this->login_verification =false;
            throw new RecordNotFoundException('No Record Found');
        }

        return $row;
    }

    public function add()
    {
        $this->validate();

        if ($this->hasError()) {
                throw new ValidationException('Error in Registration');
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
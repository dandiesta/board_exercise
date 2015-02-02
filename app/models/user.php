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
        $row = $db->row('SELECT id, firstname FROM user WHERE username = ? AND password = ?', 
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
            'firstname' => ucwords($this->firstname), 
            'lastname'  => ucwords($this->lastname), 
            'username'  => $this->username, 
            'password'  => $this->password
        );

        $db->insert('user', $params);
    }

    public function get_from_user()
    {
        $db= DB::conn();
        $row = $db->row('SELECT * FROM user WHERE id=?', array($_SESSION['userid']));
        
        if (!$row) {
            $this->login_verification =false;
            throw new RecordNotFoundException('no record found');
        }

        return $row;
    }

    public function edit()
    {
        $db = DB::conn();
        $params = array(
            'firstname' => ucwords($this->firstname), 
            'lastname'  => ucwords($this->lastname), 
            'username' => $this->username
        );

        $update = $db->update('user', $params, array('id' => $_SESSION['userid']));
    }

    public function member_since()
    {
        $db = DB::conn();

        $row = $db->row('SELECT unix_timestamp(now()) - unix_timestamp(regdate) AS member_since FROM user WHERE id=?', 
            array($_SESSION['userid']));

        $regdate = $row['member_since'];

        if ($regdate < 60) {
            return "$regdate seconds";
        } elseif (60 <= ($regdate < 3600)) {
            $minute = floor($regdate/60);
            return "$minute minutes";
        } elseif (3600 <= ($regdate < 86400)) {
            $hour = floor($regdate/3600);
            return "$hour hours";
        } elseif (86400 <= ($regdate < 2592000)) {
            $day = floor($regdate/86400);
            return "$day days";
        } elseif (2592000 <= ($regdate < 31104000)) {
            $month = floor($regdate/2592000);
            return "$month months";
        } else {
            $year = floor($regdate/31104000);
            return "$year years";
        }
    }
}
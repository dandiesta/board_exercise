<?php

class User extends AppModel
{
    //for validation
    const MIN_LENGTH = 1;
    const MIN_LENGTH_PASSWORD = 8;
    const MAX_LENGTH_NAME = 50;
    const MAX_LENGTH_USERNAME = 16;
    const MAX_LENGTH_PASSWORD = 20;
    //for computation of date
    const MAX_SECONDS = 60;
    const MAX_SECONDS_PER_MINUTE = 3600;
    const MAX_SECONDS_PER_HOUR = 86400;
    const MAX_SECONDS_PER_DAY = 2592000;
    const MAX_SECONDS_PER_MONTH = 31104000;
        

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

        if ($regdate < self::MAX_SECONDS) {
            $time_label = ($regdate == 1) ? "second" : "seconds";
        } elseif (self::MAX_SECONDS <= ($regdate < self::MAX_SECONDS_PER_MINUTE)) {
            $regdate = floor($regdate/self::MAX_SECONDS);
            $time_label = ($regdate == 1) ? "minute" : "minutes";
        } elseif (self::MAX_SECONDS_PER_MINUTE <= ($regdate < self::MAX_SECONDS_PER_HOUR)) {
            $regdate = floor($regdate/self::MAX_SECONDS_PER_MINUTE);
            $time_label = ($regdate == 1) ? "hour" : "hours";
        } elseif (self::MAX_SECONDS_PER_HOUR <= ($regdate < self::MAX_SECONDS_PER_DAY)) {
            $regdate = floor($regdate/self::MAX_SECONDS_PER_HOUR);
            $time_label = ($regdate == 1) ? "day" : "days";
        } elseif (self::MAX_SECONDS_PER_DAY <= ($regdate < self::MAX_SECONDS_PER_MONTH)) {
            $regdate = floor($regdate/self::MAX_SECONDS_PER_DAY);
            $time_label = ($regdate == 1) ? "month" : "months";
        } else {
            $regdate = floor($regdate/self::MAX_SECONDS_PER_MONTH);
            $time_label = ($regdate == 1) ? "year" : "years";
        }
        return "{$regdate} {$time_label}";
    }
}
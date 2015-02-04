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
                'nameChecker'
            ),
        ),
 
        'lname' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_NAME,
            ),
            'confirmation' => array(
                'nameChecker'
            ),
        ),

        'username' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_USERNAME,
            ),
            'confirmation' => array(
                'usernameChecker'
            ),
        ),

        'password' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH_PASSWORD, self::MAX_LENGTH_PASSWORD,
            ),
            'confirmation' => array(
                'passwordChecker'
            ),
        ),
    );

    public static function get()
    {
        $db= DB::conn();
        $row = $db->row('SELECT * FROM user WHERE id=?', array($_SESSION['userid']));
        
        if (!$row) {
            $this->login_verification =false;
            throw new RecordNotFoundException('no record found');
        }

        return $row;
    }

    public function passwordChecker()
    {
        return ($this->password == $this->confirm_password);
    }

    public function usernameChecker()
    {
        $db = DB::conn();
        $is_username_existing = $db->row('SELECT username FROM user WHERE username = ?', array($this->username));

        return (!$is_username_existing); //return true
    }

    public function nameChecker($name)
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

    public function memberSince()
    {
        $db = DB::conn();

        $row = $db->row('SELECT unix_timestamp(now()) - unix_timestamp(regdate) AS member_since 
            FROM user WHERE id=?', 
            array($_SESSION['userid']));

        $registration_date = $row['member_since'];

        if ($registration_date < self::MAX_SECONDS) {
            $time_label = ($registration_date == 1) ? "second" : "seconds";
        } elseif (self::MAX_SECONDS <= ($registration_date < self::MAX_SECONDS_PER_MINUTE)) {
            $registration_date = floor($registration_date/self::MAX_SECONDS);
            $time_label = ($registration_date == 1) ? "minute" : "minutes";
        } elseif (self::MAX_SECONDS_PER_MINUTE <= ($registration_date < self::MAX_SECONDS_PER_HOUR)) {
            $registration_date = floor($registration_date/self::MAX_SECONDS_PER_MINUTE);
            $time_label = ($registration_date == 1) ? "hour" : "hours";
        } elseif (self::MAX_SECONDS_PER_HOUR <= ($registration_date < self::MAX_SECONDS_PER_DAY)) {
            $registration_date = floor($registration_date/self::MAX_SECONDS_PER_HOUR);
            $time_label = ($registration_date == 1) ? "day" : "days";
        } elseif (self::MAX_SECONDS_PER_DAY <= ($registration_date < self::MAX_SECONDS_PER_MONTH)) {
            $registration_date = floor($registration_date/self::MAX_SECONDS_PER_DAY);
            $time_label = ($registration_date == 1) ? "month" : "months";
        } else {
            $registration_date = floor($registration_date/self::MAX_SECONDS_PER_MONTH);
            $time_label = ($registration_date == 1) ? "year" : "years";
        }
        return "{$registration_date} {$time_label}";
    }
}
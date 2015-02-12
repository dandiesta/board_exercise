<?php

class User extends AppModel
{
    //for validation
    const MIN_LENGTH = 1;
    const MIN_LENGTH_PASSWORD = 8;
    const MAX_LENGTH_NAME = 50;
    const MAX_LENGTH_USERNAME = 20;
    const MAX_LENGTH_PASSWORD = 20;
    const MAX_LENGTH_EMAIL = 254;
    //for computation of date
    const MAX_SECONDS = 60;
    const MAX_SECONDS_PER_MINUTE = 3600;
    const MAX_SECONDS_PER_HOUR = 86400;
    const MAX_SECONDS_PER_DAY = 2592000;
    const MAX_SECONDS_PER_MONTH = 31104000;
        

    public $login_verification = true;
    public $validation = array(
        'firstname' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_NAME,
            ),
            'confirmation' => array(
                'nameChecker'
            ),
        ),
 
        'lastname' => array(
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
                'usernameChecker',
            ),
            'banned_checking' => array(
                'isNotBanned',
            ),
        ),

        'password' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH_PASSWORD, self::MAX_LENGTH_PASSWORD,
            ),
            'confirmation' => array(
                'passwordChecker',
            ),
        ),

        'email' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_EMAIL,
            ),
            'confirmation' => array(
                'emailChecker',
            ),
            'banned_checking' => array(
                'isNotBanned',
            ),
        ),

        'old_password' => array(
            'password_check' => array(
                'isPasswordMatched',
            ),
        )
    );

    public static function getAll()
    {
        $db= DB::conn();
        $rows = $db->rows('SELECT * FROM user WHERE usertype != 1');
        
        if (!$rows) {
            $this->login_verification =false;
            throw new RecordNotFoundException('no record found');
        }

        return $rows;   
    }

    public static function get($user_id = null)
    {
        $db= DB::conn();
        if ($user_id == null) {
            $row = $db->row('SELECT * FROM user WHERE id=?', array($_SESSION['userid']));
        } else {
            $row = $db->row('SELECT * FROM user WHERE id=?', array($user_id));
        }
        
        if (!$row) {
            $this->login_verification =false;
            throw new RecordNotFoundException('no record found');
        }

        return $row;
    }

    /* CHECKERS FOR REGISTRATION */

    //checks if firstname and lastname contain letters only
    public function nameChecker($name)
    {
        return ctype_alpha($name);
    }

    //checks if password is same as password for confirmation
    public function passwordChecker()
    {
        return ($this->password == $this->confirm_password);
    }

    //checks if username is not yet in use
    public function usernameChecker()
    {
        $db = DB::conn();
        $is_username_existing = $db->row('SELECT username FROM user WHERE BINARY username = ? AND status=1', 
            array($this->username));

        return (!$is_username_existing); //return true
    }

    //checks if email is not yet in use
    public function emailChecker()
    {
        $db = DB::conn();
        $is_email_existing = $db->row('SELECT email FROM user WHERE BINARY email = ? AND status=1', array($this->email));

        return (!$is_email_existing); //return true
    }

    public function isNotBanned()
    {
        $db = DB::conn();

        $params = array(
            'username' => $this->username, 
            'email'    => $this->email
        );

        $is_banned = $db->value('SELECT id FROM user WHERE username = :username AND status = 2 || 
            email = :email AND status = 2', $params);

        return (!$is_banned); //return true
    }

    /* CHECKER FOR CHANGE PASSWORD */

    //checks if password typed for old password is correct
    public function isPasswordMatched()
    {
        $db = DB::conn();
        $original_password = $db->value('SELECT password FROM user WHERE id = ?', array($_SESSION['userid']));

        return ($this->old_password == $original_password);
    }

    public function login()
    {
        $db = DB::conn();

        $params = array(
            'username' => $this->username, 
            'password' => $this->password
        );

        $row = $db->row('SELECT id, firstname, usertype FROM user 
            WHERE BINARY username = :username AND BINARY password = :password AND status = 1 || 
            BINARY email = :username AND BINARY password = :password AND status = 1', $params);

        if (!$row) {
            $this->login_verification =false;
            throw new RecordNotFoundException('No Record Found');
        }

        return $row;
    }

    public function add()
    {
        $this->validate();
        $current_time = date("Y-m-d H:i:s");

        if ($this->hasError()) {
                throw new ValidationException('Error in Registration');
        }
        
        try {
            $db = DB::conn();
            $db->begin();

            $params = array (
                'firstname' => ucwords($this->firstname), 
                'lastname'  => ucwords($this->lastname), 
                'username'  => $this->username, 
                'password'  => $this->password,
                'email'     => $this->email,
                'usertype'  => 2, //2 for user, 1 for admin
                'status'    => 1, //1 for active, 2 for banned
                'registration_date' => $current_time
            );

            $db->insert('user', $params);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function edit()
    {
        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'firstname' => ucwords($this->firstname), 
                'lastname'  => ucwords($this->lastname), 
                'username' => $this->username
            );

            $update = $db->update('user', $params, array('id' => $_SESSION['userid']));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function memberSince()
    {
        $db = DB::conn();

        $row = $db->row('SELECT unix_timestamp(now()) - unix_timestamp(registration_date) AS member_since 
            FROM user WHERE id= :id', 
            array('id' => $_SESSION['userid']));

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

    public function editStatus()
    {
        try {
            $db = DB::conn();
            $db->begin();

            if ($this->current_status == 1) {
                $update = $db->query('UPDATE user SET status= 2 WHERE id=:id', array('id' => $this->user_id));
            } else {
                $update = $db->query('UPDATE user SET status= 1 WHERE id=:id', array('id' => $this->user_id));
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function changePassword()
    {
        $this->validate();

        if ($this->hasError()) {
            throw new ValidationException('Error in Changing Password');
        }

        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'password' => $this->password, 
                'id'       => $_SESSION['userid']);

            $update = $db->query('UPDATE user SET password = :password WHERE id = :id', $params);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //gets the top likers of the user
    public function topLikers()
    {
        $users = array();
        $db = DB::conn();

        $rows = $db->rows('SELECT u.username AS Liker, COUNT(l.user_id) as Number_of_likes 
            FROM like_monitor l 
            INNER JOIN comment c on c.id = l.comment_id 
            INNER JOIN user u on l.user_id = u.id 
            WHERE c.user_id=? GROUP BY Liker ORDER BY Number_of_likes DESC LIMIT 5', 
            array($_SESSION['userid']));

        foreach ($rows as $row) {
             $users[] = new User($row);
        }
    
        return $users;
    }

    //gets top commentors of the user
    public function topCommentors()
    {
        $users = array();
        $db = DB::conn();

        $rows = $db->rows('SELECT a.username AS Commentor, COUNT(c.id) AS Number_of_comments FROM comment c 
            INNER join thread t on t.id=c.thread_id 
            INNER join user u on u.id=t.user_id 
            INNER JOIN user a on a.id=c.user_id
            WHERE u.id = ? GROUP BY c.user_id ORDER BY Number_of_comments DESC LIMIT 5',
            array($_SESSION['userid']));

        foreach ($rows as $row) {
             $users[] = new User($row);
        }

        return $users;
    }
}
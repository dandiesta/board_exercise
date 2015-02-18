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
                'usernameIsNotBanned',
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
                'emailIsNotBanned',
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
        $rows = $db->rows('SELECT * FROM user');
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
        return preg_match('/^[a-z\s]*$/i', $name);
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

        $params = array(
            'username' => $this->username, 
            'status'   => ACTIVE
        );

        $is_username_existing = $db->row('SELECT username FROM user WHERE BINARY username = :username AND status= :status',
                                 $params);

        return (!$is_username_existing); //return true if the query does not return anything
    }

    //checks if email is not yet in use
    public function emailChecker()
    {
        $db = DB::conn();

        $params = array(
            'email'  => $this->email, 
            'status' => ACTIVE
        );

        $is_email_existing = $db->row('SELECT email FROM user WHERE BINARY email = :email AND status = :status', $params);

        return (!$is_email_existing); //return true if the query does not return anything
    }

    public function usernameIsNotBanned()
    {
        $db = DB::conn();

        $params = array(
            'username' => $this->username,
            'status'   => BANNED
        );

        $is_banned = $db->value('SELECT id FROM user WHERE username = :username AND status = :status', $params);

        return (!$is_banned); //return true if the query does not return anything
    }

    public function emailIsNotBanned()
    {
        $db = DB::conn();   

        $params = array(
            'email' => $this->email,
            'status'   => BANNED
        );

        $is_banned = $db->value('SELECT id FROM user WHERE email = :email AND status = :status', $params);

        return (!$is_banned); //return true if the query does not return anything
    }

    /* CHECKER FOR CHANGE PASSWORD */

    //checks if password typed for old password is correct
    public function isPasswordMatched()
    {
        $db = DB::conn();

        $original_password = $db->value('SELECT password FROM user WHERE id = ?', array($_SESSION['userid']));

        $decrypted_password = $this->mc_decrypt($original_password, ENCRYPTION_KEY);

        return ($this->old_password == $decrypted_password);
    }

    public function login()
    {
        $this->validate();
        $db = DB::conn();

        $params = array(
            'username' => $this->username,
            'status'   => ACTIVE
        );

        $row = $db->row('SELECT id, firstname, usertype FROM user WHERE BINARY username = :username AND status = :status || 
            BINARY email = :username AND status = :status', $params);

        if (!$row) {
            $this->login_verification =false;
            throw new RecordNotFoundException('No Record Found');
        }

        return $row;
    }

    public function checkPassword($password)
    {
        $db = DB::conn();

        $encrypted_password = $db->value('SELECT password FROM user 
            WHERE BINARY username = :username || 
            BINARY email = :username', array('username' => $this->username));

        
        $decrypted_password = $this->mc_decrypt($encrypted_password, ENCRYPTION_KEY);
        
        return ($decrypted_password == $password)? true : $this->login_verification =false;
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
                'password'  => $this->mc_encrypt($this->password, ENCRYPTION_KEY),
                'email'     => $this->email,
                'usertype'  => USER, 
                'status'    => ACTIVE, 
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
        $this->validate();

        if ($this->hasError()) {
                throw new ValidationException('Error in Edit Profile');
        }
        
        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'firstname' => ucwords($this->firstname), 
                'lastname'  => ucwords($this->lastname)
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

            $params = array(
                'status' => BANNED, 
                'id' => $this->user_id
            );

            $param = array(
                'status' => ACTIVE, 
                'id' => $this->user_id
            );
            
            if ($this->current_status == ACTIVE) {
                $update = $db->query('UPDATE user SET status= :status WHERE id=:id', $params);
            } else {
                $update = $db->query('UPDATE user SET status= :status WHERE id=:id', $param);
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
                'password' => $this->mc_encrypt($this->password, ENCRYPTION_KEY),
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

    // Encrypt Function
    public function mc_encrypt($encrypt, $key)
    {
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', str_replace(' ', '', $key[0]));
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
        
        return $encoded;
    }

    // Decrypt Function
    public function mc_decrypt($decrypt, $key)
    {
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        
        if (strlen($iv) !== mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
            return false;
        }
        
        $key = pack('H*', str_replace(' ', '', $key[0]));
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        
        if ($calcmac !== $mac) {
            return false;
        }
        
        $decrypted = unserialize($decrypted);
        return $decrypted;
    } 
}
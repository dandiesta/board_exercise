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

    public $login_verification = true;
    public $validation = array(
        'firstname' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_NAME,
            ),
            'confirmation' => array(
                'isNameValid'
            ),
        ),
 
        'lastname' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_NAME,
            ),
            'confirmation' => array(
                'isNameValid'
            ),
        ),

        'username' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_USERNAME,
            ),
            'confirmation' => array(
                'isUsernameExisting',
            ),
            'banned_checking' => array(
                'isUsernameNotBanned',
            ),
        ),

        'password' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH_PASSWORD, self::MAX_LENGTH_PASSWORD,
            ),
            'confirmation' => array(
                'isConfirmedPasswordMatch',
            ),
        ),

        'email' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH_EMAIL,
            ),
            'confirmation' => array(
                'isEmailExisting',
            ),
            'banned_checking' => array(
                'isEmailNotBanned',
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

    public static function get($user_id)
    {
        $db= DB::conn();
        
        $row = $db->row('SELECT * FROM user WHERE id=?', array($user_id));
    
        if (!$row) {
            $this->login_verification =false;
            throw new RecordNotFoundException('no record found');
        }

        return $row;
    }

    /* CHECKERS FOR REGISTRATION */

    //checks if password is same as password for confirmation
    public function isConfirmedPasswordMatch()
    {
        return ($this->password == $this->confirm_password);
    }
    
    //checks if username is not yet in use
    public function isUsernameExisting()
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
    public function isEmailExisting()
    {
        $db = DB::conn();

        $params = array(
            'email'  => $this->email, 
            'status' => ACTIVE
        );

        $is_email_existing = $db->row('SELECT email FROM user WHERE BINARY email = :email AND status = :status', $params);

        return (!$is_email_existing); //return true if the query does not return anything
    }

    public function isUsernameNotBanned()
    {
        $db = DB::conn();

        $params = array(
            'username' => $this->username,
            'status'   => BANNED
        );

        $is_banned = $db->value('SELECT id FROM user WHERE username = :username AND status = :status', $params);

        return (!$is_banned); //return true if the query does not return anything
    }

    public function isEmailNotBanned()
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

        $decrypted_password = mc_decrypt($original_password, ENCRYPTION_KEY);

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

    public function isPasswordCorrect($password)
    {
        $db = DB::conn();

        $encrypted_password = $db->value('SELECT password FROM user 
            WHERE BINARY username = :username || 
            BINARY email = :username', array('username' => $this->username));

        $decrypted_password = mc_decrypt($encrypted_password, ENCRYPTION_KEY);
        
        return ($decrypted_password == $password)? true : $this->login_verification =false;
    }

    public function add()
    {
        $this->validate();

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
                'password'  => mc_encrypt($this->password, ENCRYPTION_KEY),
                'email'     => $this->email,
                'usertype'  => USER, 
                'status'    => ACTIVE, 
                'registration_date' => NOW
            );

            $db->insert('user', $params);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function edit($user_id)
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

            $update = $db->update('user', $params, array('id' => $user_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function editStatus()
    {
        try {
            $db = DB::conn();
            $db->begin();

            $params['id'] = $this->user_id;
            $params['status'] = ($this->current_status == ACTIVE) ? BANNED : ACTIVE;
            $db->query('UPDATE user SET status= :status WHERE id=:id', $params);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function changePassword($user_id)
    {
        $this->validate();

        if ($this->hasError()) {
            throw new ValidationException('Error in Changing Password');
        }

        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'password' => mc_encrypt($this->password, ENCRYPTION_KEY),
                'id'       => $user_id);

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
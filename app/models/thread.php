<?php

class Thread extends AppModel
{
    const MIN_LENGTH = 1;
    const MAX_LENGTH = 50;

    public $validation = array(
        'title' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH,
            ),
        ),
    );

    public static function getAll()
    {
        $threads = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT t.id, t.title, t.created, u.username FROM thread t 
            INNER JOIN user u ON t.user_id=u.id 
            ORDER BY t.latest DESC');
            
        foreach ($rows as $row) {
            $threads[] = new Thread($row);
        }
    
        return $threads;
    }

    public static function getMyThreads($user_id)
    {
        $threads = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT t.id, t.title, t.created, u.username FROM thread t 
            INNER JOIN user u ON t.user_id=u.id 
            WHERE user_id=? 
            ORDER BY created DESC', 
            array($user_id));

        foreach ($rows as $row) {
            $threads[] = new Thread($row);
        }

        return $threads;
    }

    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));
        
        if (!$row) {
            throw new RecordNotFoundException('No Record Found');
        }

        return new self($row);
    }

    public function getLatestThread()
    {
        $db= DB::conn();
        $row = $db->row('SELECT latest FROM thread ORDER BY latest DESC');

        return $row['latest'];

    }

    public function create(Comment $comment, $user_id)
    {
        $this->validate();
        $comment->validate();

        if ($this->hasError() || $comment->hasError()){
            throw new ValidationException('Invalid Thread or Comment');
        }

        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'title'   => $this->title,
                'user_id' => $user_id,
                'latest'  => $this->getLatestThread()+1 
            );

            $db->insert('thread', $params);
            $this->id = $db->lastInsertId();

            $comments = new Comment;
            $comments->write($comment, $this->id, $_SESSION['userid']);
                
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function updateLatestThread($thread_id)
    {
        //get value of latest then add 1 so that it will be on top
        try {
            $db = DB::conn();
            #$update = $db->query('UPDATE thread SET latest=? WHERE id=?', array(($this->getLatestThread() + 1), $thread_id));
            $update = $db->update('thread', array('latest' => $this->getLatestThread() + 1), array('id' => $thread_id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }
}
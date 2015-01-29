<?php

class Thread extends AppModel
{
    public $validation = array(
        'title' => array(
            'length' => array(
                'validate_between', 1,50,
            ),
        ),
    );

    public static function getAll()
    {
        $threads = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT t.id, t.title, t.created, u.username FROM thread t 
            INNER JOIN user u ON t.user_id=u.id 
            ORDER BY created DESC');
            
        foreach ($rows as $row) {
            $threads[] = new Thread($row);
        }
    
        return $threads;
    }

    public static function getMyThreads()
    {
        $threads = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT t.id, t.title, t.created, u.username FROM thread t 
            INNER JOIN user u ON t.user_id=u.id 
            WHERE user_id=? 
            ORDER BY created DESC', 
            array($_SESSION['userid']));

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

    public function create(Comment $comment)
    {
        $this->validate();
        $comment->validate();

        if ($this->hasError() || $comment->hasError()){
            throw new ValidationException('Invalid Thread or Comment');
        }

        $db = DB::conn();
        $db->begin();

        $db->query('INSERT INTO thread SET title = ?, user_id = ?, created = NOW()', 
            array($this->title, $_SESSION['userid']));

        $this->id = $db->lastInsertId();

        $comments = new Comment;
        $comments->write($comment, $this->id);
            
        $db->commit();
    }
}
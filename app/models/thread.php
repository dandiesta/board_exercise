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

    public static function getAll($user_id = null)
    {
        $threads = array();
        $db = DB::conn();

        if ($user_id == null) {
            $rows = $db->rows('SELECT * FROM thread ORDER BY last_modified DESC');
        } else {
            $rows = $db->rows('SELECT * FROM thread WHERE user_id = ? ORDER BY last_modified DESC', array($user_id));
        }
            
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

    public function getUser()
    {
       return User::get($this->user_id);
    }

    public function create(Comment $comment)
    {
        $this->validate();
        $comment->validate();
        $current_time = date("Y-m-d H:i:s");

        if ($this->hasError() || $comment->hasError()){
            throw new ValidationException('Invalid Thread or Comment');
        }

        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'title'   => $this->title,
                'user_id' => $_SESSION['userid'],
                'created' => $current_time,
                'last_modified' => $current_time,
            );
            
            $db->insert('thread', $params);
            $this->id = $db->lastInsertId();

            $comments = new Comment;
            $comments->write($comment, $this->id);
                
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function updateLastModifiedThread($thread_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'time'      => NOW,
                'thread_id' => $thread_id
            );

            $db->query('UPDATE thread SET last_modified = :time WHERE id = :thread_id', $params);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function editTitle($thread_id)
    {
        $this->validate();

        if ($this->hasError()) {
            throw new ValidationException('Invalid Title');
        }

        try {
            $db = DB::conn();
            $db->begin();
            
            $db->update('thread', array('title' => $this->title), array('id' => $thread_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function delete($thread_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $db->query('DELETE FROM thread WHERE id = ?', array($thread_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function count($user_id)
    {
        $db = DB::conn();

        return $db->value('SELECT COUNT(id) FROM thread WHERE user_id = ?', array($user_id));
    }
}
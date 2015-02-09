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
            $rows = $db->rows('SELECT t.id, t.title, t.created, u.username, t.user_id, u.usertype FROM thread t 
                INNER JOIN user u ON t.user_id=u.id ORDER BY t.last_modified DESC');
        } else {
            $rows = $db->rows('SELECT t.id, t.title, t.created, u.username, t.user_id, u.usertype FROM thread t 
                INNER JOIN user u ON t.user_id=u.id WHERE u.id = ? ORDER BY t.last_modified DESC', array($user_id));
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
                'title' => $this->title,
                'user_id' => $_SESSION['userid'],
                'created' => $current_time,
                'last_modified' => $current_time,
            );
            
            $insert = $db->insert('thread', $params);
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
        $current_time = date("Y-m-d h:i:s");

        try {
            $db = DB::conn();
            $db->begin();

            $update = $db->query('UPDATE thread SET last_modified=? WHERE id= ?', array($current_time, $thread_id));

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
            
            $update = $db->update('thread', array('title' => $this->title), array('id' => $thread_id));

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

            $delete = $db->query('DELETE FROM thread WHERE id = ?', array($thread_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }
}
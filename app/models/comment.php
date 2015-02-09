<?php

class Comment extends AppModel
{
    const MIN_LENGTH = 1;
    const MAX_LENGTH = 1000;

    public $validation = array(
        'body' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_LENGTH,
            ),
        ),
    );

    public static function get($comment_id)
    {
        $db = DB::conn();

        $row = $db->row('SELECT body FROM comment WHERE id = ?', array($comment_id));

        return $row['body'];
    }

    public function getComments($id)
    {
        $comments = array();
        $db= DB::conn();

        $rows = $db->rows('SELECT c.id, u.username, c.user_id, c.body, c.created FROM comment c 
            INNER JOIN user u ON c.user_id=u.id WHERE c.thread_id = ? ORDER BY c.created DESC', array($id));

        foreach ($rows as $row) {
            $comments[] = new Comment($row);
        }
    
        return $comments;
    }

    public function write(Comment $comment, $thread_id)
    {
        if (!$comment->validate()) {
            throw new ValidationException('Invalid Comment');
        }

        $current_time = date("Y-m-d h:i:s");
        $db = DB::conn();

        $params = array(
            'thread_id' => $thread_id,
            'user_id' => $_SESSION['userid'],
            'body' => $comment->body,
            'created' => $current_time
        );

        $db->insert('comment', $params);
    }

    public function edit($comment_id)
    {
        $this->validate();

        if ($this->hasError()) {
            throw new ValidationException('Invalid Comment');
        }

        try {
            $db = DB::conn();
            $db->begin();
            
            $update = $db->update('comment', array('body' => $this->body), array('id' => $comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function deleteThread($thread_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $delete = $db->query('DELETE FROM comment WHERE thread_id = ?', array($thread_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function deleteComment($comment_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $delete = $db->query('DELETE FROM comment WHERE id = ?', array($comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function getTopThreads()
    {
        $db = DB::conn();

        $rows = $db->rows('SELECT t.id, t.user_id, t.title, u.username, t.created, t.last_modified, u.usertype, 
            COUNT(c.id) AS thread_count FROM comment c 
            INNER JOIN thread t ON c.thread_id=t.id 
            INNER JOIN user u ON t.user_id=u.id 
            GROUP BY t.id ORDER BY COUNT(c.id) DESC');

        foreach ($rows as $row) {
            $threads[] = new Thread($row);
        }

        return $threads;
    }
}
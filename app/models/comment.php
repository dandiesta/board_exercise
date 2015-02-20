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

    public function getAll($id)
    {
        $comments = array();
        $db= DB::conn();

        $rows = $db->rows('SELECT * FROM comment WHERE thread_id = ? ORDER BY created DESC', array($id));

        foreach ($rows as $row) {
            $comments[] = new Comment($row);
        }

        return $comments;
    }

    public static function get($comment_id)
    {
        $db = DB::conn();

        $row = $db->row('SELECT * FROM comment WHERE id = ?', array($comment_id));

        if (!$row) {
            throw new RecordNotFoundException('No Record Found');
        }

        return new self($row);
    }

    //get top threads based on comment count and last modified
    public function getTopThreads()
    {
        $db = DB::conn();
        $threads = array();
        
        $rows = $db->rows('SELECT t.id, t.user_id, t.title, u.username, t.created, t.last_modified, u.usertype, 
            COUNT(c.id) AS thread_count FROM comment c 
            INNER JOIN thread t ON c.thread_id=t.id 
            INNER JOIN user u ON t.user_id=u.id 
            GROUP BY t.id ORDER BY COUNT(c.id) DESC, t.last_modified DESC');

        foreach ($rows as $row) {
            $threads[] = new Thread($row);
        }

        return $threads;
    }

    //get top comments based on number of likes and dislikes
    public function getTopComments()
    {
        $comments = array();
        $db= DB::conn();

        $rows = $db->rows('SELECT * FROM comment WHERE liked != 0 ORDER BY liked DESC, disliked ASC');

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

        $db = DB::conn();

        $params = array(
            'thread_id' => $thread_id,
            'user_id' => $_SESSION['userid'],
            'body' => $comment->body,
            'created' => NOW
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

    //delete all comments that has the given thread_id
    public function deleteAll($thread_id)
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

    //delete one comment
    public function delete($comment_id)
    {
        $like_monitor = new LikeMonitor();

        try {
            $db = DB::conn();
            $db->begin();

            $delete = $db->query('DELETE FROM comment WHERE id = ?', array($comment_id));
            $like_monitor->deleteExisting($comment_id); //deletes records in like_monitor table when a comment is deleted

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //increments liked column in comment table
    public function updateLikedCount($comment_id)
    {
        $comment = new Comment();
       
        try {
            $db = DB::conn();
            $db->begin();

            $update = $db->query('UPDATE comment SET liked = liked + 1 WHERE id = ?', array($comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //increments disliked column in comment table
    public function updateDislikedCount($comment_id)
    {
        $comment = new Comment();

        try {
            $db = DB::conn();
            $db->begin();

            $update = $db->query('UPDATE comment SET disliked = disliked + 1 WHERE id = ?', array($comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //decrements liked column in comment table
    public function subtractLikedCount($comment_id)
    {
        $comment = new Comment();

        try {
            $db = DB::conn();
            $db->begin();
        
            $update = $db->query('UPDATE comment SET liked = liked - 1 WHERE id = ?', array($comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //decrements disliked column in comment table
    public function subtractDislikedCount($comment_id)
    {
        $comment = new Comment();
        
        try {
            $db = DB::conn();
            $db->begin();

            $update = $db->query('UPDATE comment SET disliked = disliked - 1 WHERE id = ?', array($comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function count($user_id)
    {
        $db = DB::conn();

        $count_comments = $db->value('SELECT COUNT(id) FROM comment WHERE user_id = ?', array($user_id));

        return $count_comments;
    }
}
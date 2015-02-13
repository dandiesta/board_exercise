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

    //checks if the user had already liked the comment
    public function hasLiked($comment_id)
    {
        $db = DB::conn();

        $params = array(
            'comment_id' => $comment_id, 
            'user_id'    => $_SESSION['userid']
        );

        $row = $db->row('SELECT id FROM like_monitor
            WHERE comment_id = :comment_id AND user_id = :user_id AND liked = 1', $params);

        return $row;
    }

    //checks if the user had already disliked the comment
    public function hasDisliked($comment_id)
    {
        $db = DB::conn();

        $params = array(
            'comment_id' => $comment_id, 
            'user_id'    => $_SESSION['userid']
        );

        $row = $db->row('SELECT id FROM like_monitor 
            WHERE comment_id = :comment_id AND user_id = :user_id AND disliked = 1', $params);

        return $row;
    }

    public static function get($comment_id)
    {
        $db = DB::conn();

        $row = $db->value('SELECT body FROM comment WHERE id = ?', array($comment_id));

        return $row;
    }

    public function getComments($id)
    {
        $comments = array();
        $db= DB::conn();

        $rows = $db->rows('SELECT c.thread_id, c.id, u.username, c.user_id, c.body, c.created, c.liked, c.disliked
            FROM comment c INNER JOIN user u ON c.user_id=u.id
            WHERE c.thread_id = ? ORDER BY c.created DESC', 
            array($id));

        foreach ($rows as $row) {
            $comments[] = new Comment($row);
        }
    
        return $comments;
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

        $rows = $db->rows('SELECT u.username, c.body, c.created, c.liked, c.disliked FROM comment c 
            INNER JOIN user u ON c.user_id=u.id WHERE c.liked != 0 ORDER BY c.liked DESC, c.disliked ASC');

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

        $current_time = date("Y-m-d H:i:s");
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
        try {
            $db = DB::conn();
            $db->begin();

            $delete = $db->query('DELETE FROM comment WHERE id = ?', array($comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //deletes existing record in like_monitor
    public function deleteExisting($comment_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'comment_id' => $comment_id, 
                'user_id'    => $_SESSION['userid']
            );

            $delete = $db->query('DELETE FROM like_monitor WHERE comment_id = :comment_id AND user_id = :user_id', $params);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //for like_monitor
    public function addLike($comment_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'comment_id' => $comment_id,
                'user_id' => $_SESSION['userid'],
                'liked' => 1,
                'disliked' => 0
            );

            $insert = $db->insert('like_monitor', $params);
            $update = $this->updateLikedCount($comment_id);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    //for like_monitor
    public function addDislike($comment_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $params = array(
                'comment_id' => $comment_id,
                'user_id' => $_SESSION['userid'],
                'liked' => 0,
                'disliked' => 1
            );

            $insert = $db->insert('like_monitor', $params);
            $update = $this->updateDislikedCount($comment_id);

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
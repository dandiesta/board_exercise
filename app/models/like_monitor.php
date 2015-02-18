<?php

class LikeMonitor extends AppModel
{
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

    public function addLike($comment_id)
    {
        $comments = new Comment();

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
            $update = $comments->updateLikedCount($comment_id);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function addDislike($comment_id)
    {
        $comments = new Comment();

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
            $update = $comments->updateDislikedCount($comment_id);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function countLike($user_id)
    {
        $db = DB::conn();

        $count_likes = $db->value('SELECT SUM(liked) FROM like_monitor WHERE user_id = ?', array($user_id));

        return $count_likes;
    }

    public function countDislike($user_id)
    {
        $db = DB::conn();

        $count_likes = $db->value('SELECT SUM(disliked) FROM like_monitor WHERE user_id = ?', array($user_id));

        return $count_likes;
    }

    public function delete($thread_id)
    {
        try {
            $db = DB::conn();
            $db->begin();

            $delete = $db->query('DELETE l FROM like_monitor l 
                        INNER JOIN comment c ON c.id = l.comment_id 
                        INNER JOIN thread t ON t.id = c.thread_id 
                        WHERE t.id=?',
                        array($thread_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

}
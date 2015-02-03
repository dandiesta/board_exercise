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

        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, user_id = ?, body = ?',
            array($thread_id, $_SESSION['userid'], $comment->body));
    }

    public function edit($comment_id)
    {
        $this->validate();

        if ($this->hasError()) {
            throw new ValidationException('Invalid Comment');
        }

        try {
            $db = DB::conn();

            $update = $db->update('comment', array('body' => $this->body), array('id' => $comment_id));

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }
}
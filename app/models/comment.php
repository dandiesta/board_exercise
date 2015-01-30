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

    public function getComments($id)
    {
        $comments = array();

        $db= DB::conn();
        $rows = $db->rows('SELECT u.username, c.body, c.created FROM comment c 
            INNER JOIN user u ON c.user_id=u.id WHERE c.thread_id = ? ORDER BY c.created DESC', array($id));

        foreach ($rows as $row) {
            $comments[] = new Comment($row);
        }
    
        return $comments;
    }

    public function write(Comment $comment, $thread_id, $user_id)
    {
        if (!$comment->validate()) {
            throw new ValidationException('Invalid Comment');
        }

        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, user_id = ?, body = ?',
            array($thread_id, $user_id, $comment->body));
    }
}
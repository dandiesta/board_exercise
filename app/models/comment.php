<?php

class Comment extends AppModel
{
	public $validation = array(
			
			'username' => array(
				'length' => array(
					'validate_between', 1, 16,
				),
			),

			'body' => array(
				'length' => array(
					'validate_between',1, 200,
				),
			),
		);

	public function getComments($id)
		{
			$comments = array();

			$db= DB::conn();

			$rows = $db->rows('SELECT u.username, c.body, c.created FROM comment c INNER JOIN user u ON c.user_id=u.id WHERE c.thread_id = ? ORDER BY c.created ASC', array($id));

			foreach ($rows as $row) {
				$comments[] = new Comment($row);
			}
			return $comments;
		}

		public function write(Comment $comment, $id) //Will enable us to insert data into the comment table
		{
			if (!$comment->validate()) {
				throw new ValidationException('invalid comment');
			}

			$db = DB::conn();
			$db->query('INSERT INTO comment SET thread_id = ?, user_id = ?, body = ?, created = NOW()',
				array($id, $_SESSION['userid'], $comment->body));
		}

}

?>
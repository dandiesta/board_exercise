<?php
	class Thread extends AppModel
	{
		public $validation = array(
			'title' => array(
				'length' => array(
					'validate_between', 1,30,
				),
			),
		);

		public static function getAll()
		{
			$threads = array();
			$db = DB::conn();
			$rows = $db->rows('SELECT t.id, t.title, t.created, u.username FROM thread t INNER JOIN user u ON t.user_id=u.id ORDER BY created DESC');
			
			foreach ($rows as $row) {
				$threads[] = new Thread($row);
			}
		
			return $threads;
		}

		public static function getMyThreads()
		{
			$threads = array();
			$db = DB::conn();
			$rows = $db->rows('SELECT t.id, t.title, t.created, u.username FROM thread t INNER JOIN user u ON t.user_id=u.id WHERE user_id=? ORDER BY created DESC', array($_SESSION['userid']));

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
				throw new RecordNotFoundException('no record found');
			}

			return new self($row);
		}

		public function getComments()
		{
			$comments = array();

			$db= DB::conn();

			$rows = $db->rows('SELECT u.username, c.body, c.created FROM comment c INNER JOIN user u ON c.user_id=u.id WHERE c.thread_id = ? ORDER BY c.created ASC', array($this->id));

			foreach ($rows as $row) {
				$comments[] = new Comment($row);
			}
			return $comments;
		}

		public function write(Comment $comment) //Will enable us to insert data into the comment table
		{
			if (!$comment->validate()) {
				throw new ValidationException('invalid comment');
			}

			$db = DB::conn();
			$db->query('INSERT INTO comment SET thread_id = ?, user_id = ?, body = ?, created = NOW()',
				array($this->id, $_SESSION['userid'], $comment->body));
		}

		public function create(Comment $comment)
		{
			$this->validate();
			$comment->validate();
			if ($this->hasError() || $comment->hasError()){
				throw new ValidationException('invalid thread or comment');
			}
			$db = DB::conn();
			$db->begin();

			//$params = array(
			//	'title' => $this->title,
			//);
			//$db->insert('thread', $params);
			//or
			$db->query('INSERT INTO thread SET title = ?, user_id = ?, created = NOW()', array($this->title, $_SESSION['userid']));

			$this->id = $db->lastInsertId(); //returns the latest inserted id within the function

			//write first comment at the same time
			$this->write($comment);

			$db->commit();
		}

		
		
	}
?>
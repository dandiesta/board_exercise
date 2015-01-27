<?php
class AppModel extends Model
{
	public function get_from_user()
	{
		$db= DB::conn();
		$row = $db->row('SELECT * FROM user WHERE id=?', array($_SESSION['userid']));

		if (!$row) {
			$this->login_verification =false;
			throw new RecordNotFoundException('no record found');
		}

		return $row;
	}
}

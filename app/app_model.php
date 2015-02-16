<?php
class AppModel extends Model
{
	 /* CHECKER FOR CURRENT STATUS OF USER THAT IS LOGGED IN */

    public function currentStatusChecker()
    {
        $db = DB::conn();

        $current_status = $db->value('SELECT status FROM user WHERE id = ?', array($_SESSION['userid']));

        return $current_status;
    }
}

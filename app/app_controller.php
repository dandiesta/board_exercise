<?php
class AppController extends Controller
{
    public $default_view_class = 'AppLayoutView';

    function beforeFilter()
	{
		$exclude = array(
			'user/registration',
			'user/login',
			'user/delete',
			'user/confirmation'
		);

		if (in_array(Param::get(DC_ACTION), $exclude)) return;

		if (!isset($_SESSION['userid'])) {
		    redirect('/user/login');
		}
	}
}

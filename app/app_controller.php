<?php
class AppController extends Controller
{
    public $default_view_class = 'AppLayoutView';

    function beforeFilter()
    {
        $exclude = array(
            'user/registration',
            'user/login',
            'user/banned'
        );

        if (in_array(Param::get(DC_ACTION), $exclude)) return;

        if (!isset($_SESSION['userid'])) {
            redirect('/user/login');
        } else {
            $user = User::get($_SESSION['userid']);

            if ($user['status'] == ACTIVE) {
                return;
            
            } else {
                redirect('/user/banned');
            }
        }
    }

    function banned()
    {
        if ($_SESSION['usertype'] != BANNED) {
            redirect('/user/home');
        } else {
            session_destroy();
        }
    }
}

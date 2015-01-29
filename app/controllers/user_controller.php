<?php

class UserController extends AppController
{
    public function registration()
    {
        $register = new User();
        $page = Param::get('page_next', 'registration');

        switch ($page) {
            case 'registration':
                break;
            case 'success':
                $register->fname = Param::get('fname');
                $register->lname = Param::get('lname');
                $register->username = Param::get('username');
                $register->password = Param::get('password');
                $register->confirm_password = Param::get('repeat_password');
                
                try {
                      $register->add();
                } catch (ValidationException $e) {
                   $page = 'registration';
                }    
                break;
            default:
                throw new NotFoundException("{$page} not found");
                break;
        }

        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function login()
    {
        if (isset($_SESSION['userid'])) {
            redirect('/');
        } else {
            $user = new User();
            $page = Param::get('page_next', 'login');

            switch ($page) {
                case 'login':
                    break;
                case 'home':
                    $user->username = Param::get('username');
                    $user->password = Param::get('password');
                    
                    try {
                        $user = $user->login();
                        $_SESSION['userid'] = $user['id'];
                        $fname = $user['fname'];
                    } catch (ValidationException $e) {
                        $page = 'login';
                    }
                    break;
                default:
                    throw new NotFoundException("{$page} not found");
                    break;
            }

            $this->set(get_defined_vars());
            $this->render($page);
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('login');        
    }

    public function home()
    {
        if (!isset($_SESSION['userid'])) {
            redirect('user/login');
        } else {
            $home = new User();
                
            $user = $home->get_from_user();
            $fname = $user['fname'];
                
            $this->set(get_defined_vars());
        }
    }
}
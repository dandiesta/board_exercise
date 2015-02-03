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
                $register->firstname = Param::get('firstname');
                $register->lastname = Param::get('lastname');
                $register->username = Param::get('username');
                $register->password = Param::get('password');
                $register->confirm_password = Param::get('confirm_password');
                
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
                        $fname = $user['firstname'];
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
        $home = new User();
                
        $user = $home->get_from_user();
        $fname = $user['firstname'];
                
        $this->set(get_defined_vars());
        
    }

    public function profile()
    {
        $user = new User();
        $page = Param::get('page_next', 'profile');

            $profile = $user->get_from_user();
            $regdate = $user->member_since();

            $firstname = $profile['firstname'];
            $lastname = $profile['lastname'];
            $username = $profile['username'];
            $member_since = $regdate;

            switch ($page) {
                case 'profile':
                    break;
                case 'success_update':
                    $user->firstname = Param::get('firstname');
                    $user->lastname = Param::get('lastname');
                    $user->username = Param::get('username');
                    
                    try {
                        $user->edit();
                    } catch (ValidationException $e) {
                       $page = 'profile';
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
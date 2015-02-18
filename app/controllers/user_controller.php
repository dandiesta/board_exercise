<?php

class UserController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 5;
    const MIN_PAGE_NUMBER = 1;
    const ADJACENT_TO_CURRENT = 4;

    public function registration()
    {
        $register = new User();
        $page = Param::get('page_next', 'registration');

        switch ($page) {
        case 'registration':
            break;
        case 'success':
            $register->firstname = trim(Param::get('firstname'));
            $register->lastname = trim(Param::get('lastname'));
            $register->username = trim(Param::get('username'));
            $register->password = Param::get('password');
            $register->confirm_password = Param::get('confirm_password');
            $register->email = trim(Param::get('email'));
                
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
                $password = Param::get('password');

                try {
                    if ($user->checkPassword($password)) {
                        $user = $user->login();
                        $_SESSION['userid'] = $user['id'];
                        $_SESSION['usertype'] = $user['usertype'];

                       redirect('/user/home');
                     }
                } catch (ValidationException $e) {
                    $page = 'login';
                }
                break;
            default:
                throw new NotFoundException("{$page} not found");
                break;
            }

            $this->set(get_defined_vars());
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('login');        
    }

    public function home()
    {
        $user = User::get();
        $comments = new Comment();
        $threads = $comments->getTopThreads();
        $firstname = $user['firstname'];
        

        if ($threads) {
            $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_threads = array_slice($threads, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
            $pagination->checkLastPage($other_threads);
            $page_links = Pagination(count($threads), self::MAX_ITEMS_PER_PAGE, $current_page, self::ADJACENT_TO_CURRENT);
            $threads = array_slice($threads, $pagination->start_index - 1, $pagination->count);
        }

        $count = count($page_links);
        $this->set(get_defined_vars());
    }

    public function profile()
    {
        $user = new User();
        $threads = new Thread();
        $comments = new Comment();
        $profile = User::get();
        $like_monitor = new LikeMonitor();
        $page = Param::get('page_next', 'profile');

        $firstname = $profile['firstname'];
        $lastname = $profile['lastname'];
        $username = $profile['username'];
        $email = $profile['email'];

        $member_since = $user->memberSince();
        $thread_count = $threads->count($_SESSION['userid']);
        $comment_count = $comments->count($_SESSION['userid']);
        $like_count = $like_monitor->countLike($_SESSION['userid']);
        $dislike_count = $like_monitor->countDislike($_SESSION['userid']);

        switch ($page) {
            case 'profile':
                break;
            case 'success_update':
                $user->firstname = Param::get('firstname');
                $user->lastname = Param::get('lastname');
                        
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

    public function status()
    {
        if ($_SESSION['usertype'] == ADMIN) {
            $user = User::getAll();        

            if ($user) {
                $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
                $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
                $other_threads = array_slice($user, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
                $pagination->checkLastPage($other_threads);
                $page_links = Pagination(count($user), self::MAX_ITEMS_PER_PAGE, $current_page, self::ADJACENT_TO_CURRENT);
                $user = array_slice($user, $pagination->start_index - 1, $pagination->count);
            }

            $count = count($page_links);
            $this->set(get_defined_vars());
        } else {
            redirect('/user/home');
        }
    }

    public function edit_status()
    {
        $users = new User();
        $user_id = Param::get('user_id');
        $users->user_id = $user_id;
        $user = User::get($user_id);
        $users->current_status = $user['status'];
        
        $update = $users->editStatus();

        $this->set(get_defined_vars());
        redirect("/user/status?page={$_SESSION['current_page']}");
    }

    public function change_password()
    {
        $user = new User();
        $page = Param::get('page_next', 'change_password');

        switch ($page) {
            case 'change_password':
                break;
            case 'edit_success':
                $user->old_password = Param::get('old_password');
                $user->password = Param::get('password');
                $user->confirm_password = Param::get('confirm_password');
                        
                try {
                    $change_password = $user->changePassword();
                    redirect('/user/profile');
                } catch (ValidationException $e) {
                    $page = 'change_password';
                }    
                break;
            default:
                throw new NotFoundException("{$page} not found");
                break;
        }

        $this->set(get_defined_vars());
    }

    public function top_five()
    {
        $users = new User();

        $user = $users->getAll();
       
        $top_likers = $users->topLikers();
        $top_commentors = $users->topCommentors();
        
        $this->set(get_defined_vars());
    }

    public function delete()
    {
        $threads = new Thread();
        $comments = new Comment();

        $thread_id = Param::get('thread_id');

        $threads->delete($thread_id);
        $comments->deleteComments($thread_id);
        
        redirect("/user/home?page={$_SESSION['current_page']}&");
    }

    public function others()
    {
        $users = new User();
        $threads = new Thread();
        $comments = new Comment();

        $user_id = Param::get('user_id');

        $user = User::get($user_id);
        $thread_count = $threads->count($user_id);
        $comment_count = $comments->count($user_id);
        $member_since = $users->memberSince();

        $this->set(get_defined_vars());
    }
}
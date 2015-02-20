<?php

class CommentController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 4;

    public function view()
    {
        $thread_id = Param::get('thread_id');

        if (!$thread_id) {
            redirect('/thread/index');
        }
        
        $thread = Thread::get($thread_id);
        $user = $thread->getUser();
        $users = new User();
        $comment = new Comment();

        $_SESSION['thread_id'] = $thread->id;
        $comments = $comment->getAll($_SESSION['thread_id']);
        $users = $users->getAll();

        if ($comments) {
            $current_page = max(Param::get('page'), MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_comments = array_slice($comments, $pagination->start_index + MIN_PAGE_NUM);
            $pagination->checkLastPage($other_comments);
            $page_links = Pagination(count($comments), self::MAX_ITEMS_PER_PAGE, $current_page, ADJACENT_TO_CURRENT);
            $comments = array_slice($comments, $pagination->start_index - 1, $pagination->count);

            $count = count($page_links);
           $this->set(get_defined_vars());
        }
        $this->set(get_defined_vars());
    }

    public function write()
    {
        $thread_id = Param::get('thread_id');

        if (!$thread_id) {
            redirect('/thread/index');
        }

        $thread = Thread::get($thread_id);
        $comment = new Comment();
        $page = Param::get('page_next', 'write');

        switch ($page) {
            case 'write':
                break;
            case 'write_end':
                $comment->body = trim(Param::get('body'));

                try {
                    $comment->write($comment, $thread->id, $_SESSION['userid']);
                    $thread->updateLastModifiedThread($thread_id);
                } catch (ValidationException $e) {
                    $page = 'write';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }

        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function edit()
    {
        $thread_id = $_SESSION['thread_id'];
        $thread = Thread::get($thread_id);
        $page = Param::get('page_next', 'edit');
        $comment = new Comment();
        $comment_id = Param::get('comment_id');

        if (!$comment_id) {
            redirect('/thread/index');
        }
        
        $comments = Comment::get($comment_id);

        if (!$comments) {
            redirect('/thread/index');
        }

        if ($comments->user_id == $_SESSION['userid']) {
            $title = $thread->title;
            $body = $comments->body;
        } else { 
            redirect('/thread/index');
        }        

        switch ($page) {
            case 'edit':
                break;
            case 'edit_end':
               $comment->body = Param::get('body');

                try {
                    $comment->edit($comment_id);
                    redirect(url('comment/view', array('thread_id' => $thread_id)));
                } catch (ValidationException $e) {
                    $page = 'edit';
                }
                break;  
            default:
                throw new NotFoundException("{$page} not found");
                break;
        }

        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function delete()
    {
        $comments = new Comment();
        $thread_id = $_SESSION['thread_id'];
        $comment_id = Param::get('comment_id');

        if (!$comment_id) {
            redirect('/thread/index');
        }

        try {
            $comment = Comment::get($comment_id);

            if ($comment->user_id == $_SESSION['userid']) {
               $comments->delete($comment_id);
            }
        } catch (ValidationException $e) {
            throw new  NotFoundException('Comment not found');
        }

        $this->set(get_defined_vars());
        redirect(url('comment/view', array('thread_id' => $thread_id)));
    }

    public function liked()
    {
        $comment = new Comment();
        $like_monitor = new LikeMonitor();
        $page_num = $_SESSION['current_page'];
        $thread_id = $_SESSION['thread_id'];

        $comment_id = Param::get('comment_id');
        $like_checker = $like_monitor->hasLiked($comment_id);
        $dislike_checker = $like_monitor->hasDisliked($comment_id);

        if (!$like_checker) { //comment has not been liked
            if (!$dislike_checker) { //comment has not been disliked
                $like_monitor->addLike($comment_id); //add 1 liked record in like_monitor table then increment liked column in comment table
            } else { //comment has been disliked
                $comment->subtractDislikedCount($comment_id); //subtract 1 from disliked column in comment table
                $like_monitor->deleteExisting($comment_id); //deletes disliked record in like_monitor table
                $like_monitor->addLike($comment_id); //add 1 like record in like_monitor table then increment liked column in comment table
            }
        }

        $this->set(get_defined_vars());
        redirect(url('comment/view', array('page' => $page_num, 'thread_id' => $thread_id)));
    }

    public function disliked()
    {
        $comment = new Comment();
        $like_monitor = new LikeMonitor();
        $page_num = $_SESSION['current_page'];
        $thread_id = $_SESSION['thread_id'];

        $comment_id = Param::get('comment_id');
        $like_checker = $like_monitor->hasLiked($comment_id);
        $dislike_checker = $like_monitor->hasDisliked($comment_id);

        if (!$dislike_checker) { //comment has not been disliked
            if (!$like_checker) { //comment has not been liked
                $like_monitor->addDislike($comment_id); //add 1 disliked record in like_monitor table then increment disliked column in comment table
            } else { //comment has been liked
                $comment->subtractLikedCount($comment_id); //subtract 1 from liked column in comment table
                $like_monitor->deleteExisting($comment_id);//deletes liked record in like_monitor table
                $like_monitor->addDislike($comment_id); //add 1 dislike record in like_monitor table then increment disliked column in comment table
            }
        }

        $this->set(get_defined_vars());
        redirect(url('comment/view', array('page' => $page_num, 'thread_id' => $thread_id)));
    }

    public function most_liked()
    {
        $user = User::getAll();
        $comments = new Comment();
        $comment = $comments->getTopComments();

        if ($comment) {
            $current_page = max(Param::get('page'), MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_comments = array_slice($comment, $pagination->start_index + MIN_PAGE_NUM);
            $pagination->checkLastPage($other_comments);
            $page_links = Pagination(count($comment), self::MAX_ITEMS_PER_PAGE, $current_page, ADJACENT_TO_CURRENT);
            $comment = array_slice($comment, $pagination->start_index - 1, $pagination->count);

            $count = count($page_links);
        }

        $this->set(get_defined_vars());
    }
}
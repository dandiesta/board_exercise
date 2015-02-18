<?php

class CommentController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 4;
    const ADJACENT_TO_CURRENT = 4;

    public function view()
    {
        $thread_id = Param::get('thread_id');

        if (!$thread_id) {
            redirect('/thread/index');
        }
        
        $thread = Thread::get($thread_id);
        $user = User::get($thread->user_id);
        $users = new User();
        $comment = new Comment();

        $_SESSION['thread_id'] = $thread->id;
        $comments = $comment->getAll($_SESSION['thread_id']);
        $users = $users->getAll();

        if ($comments) {
            $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_comments = array_slice($comments, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
            $pagination->checkLastPage($other_comments);
            $page_links = Pagination(count($comments), self::MAX_ITEMS_PER_PAGE, $current_page, self::ADJACENT_TO_CURRENT);
            $comments = array_slice($comments, $pagination->start_index - 1, $pagination->count);
        }

        $count = count($page_links);
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
        $thread = Thread::get($_SESSION['thread_id']);

        $comment = new Comment();
        $comment_id = Param::get('comment_id');
        $body = Comment::get($comment_id);
        
        $title = $thread->title;
        $page = Param::get('page_next', 'edit');

        switch ($page) {
            case 'edit':
                break;
            case 'edit_end':
               $comment->body = Param::get('body');

                try {
                    $comment->edit($comment_id);
                    redirect("/comment/view?thread_id={$_SESSION['thread_id']}");
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

        $comment_id = Param::get('comment_id');

        $comments->delete($comment_id);

        $this->set(get_defined_vars());
        redirect("/comment/view?thread_id={$_SESSION['thread_id']}");
    }

    public function liked()
    {
        $comment = new Comment();
        $like_monitor = new LikeMonitor();

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
        redirect("/comment/view?page={$_SESSION['current_page']}&thread_id={$_SESSION['thread_id']}");
    }

    public function disliked()
    {
        $comment = new Comment();
        $like_monitor = new LikeMonitor();

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
        redirect("/comment/view?page={$_SESSION['current_page']}&thread_id={$_SESSION['thread_id']}");
    }

    public function most_liked()
    {
        $comments = new Comment();
        $users = new User();

        $comment = $comments->getTopComments();
        $user = $users->getAll();

        if ($comment) {
            $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_comments = array_slice($comment, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
            $pagination->checkLastPage($other_comments);
            $page_links = Pagination(count($comment), self::MAX_ITEMS_PER_PAGE, $current_page, self::ADJACENT_TO_CURRENT);
            $comment = array_slice($comment, $pagination->start_index - 1, $pagination->count);
        }

        $count = count($page_links);
        $this->set(get_defined_vars());
    }
}
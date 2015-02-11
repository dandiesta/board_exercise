<?php

class CommentController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 4;
    const MIN_PAGE_NUMBER = 1;

    public function view()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $user = User::get($thread->user_id);
        $comment = new Comment();

        $_SESSION['thread_id'] = $thread->id;
        $comments = $comment->getComments($_SESSION['thread_id']);

        if ($comments) {
            $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_threads = array_slice($comments, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
            $pagination->checkLastPage($other_threads);
            $page_links = createPageLinks(count($comments), $current_page, $pagination->count, 'thread_id='.$thread->id);
            $comments = array_slice($comments, $pagination->start_index - 1, $pagination->count);
        }

        $this->set(get_defined_vars());
    }

    public function write()
    {
        $thread_id = Param::get('thread_id');
        $thread = Thread::get($thread_id);
        $comment = new Comment();
        $page = Param::get('page_next', 'write');

        switch ($page) {
            case 'write':
                break;
            case 'write_end':
                $comment->body = Param::get('body');

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

        $comments->deleteComment($comment_id);
        
        redirect("/comment/view?thread_id={$_SESSION['thread_id']}");

        $this->set(get_defined_vars());
        //$this->render($page);
    }

    public function liked()
    {
        $comment = new Comment();

        $comment_id = Param::get('comment_id');
        $like_checker = $comment->likeChecker($comment_id); //only matches like but not dislike
        $dislike_checker = $comment->dislikeChecker($comment_id);

        if (!$like_checker) {
            if (!$dislike_checker) {
                $comment->addLike($comment_id);
                $comment->updateLikedCount($comment_id);
            } else {
                $comment->subtractDislikedCount($comment_id);
                $comment->deleteExisting($comment_id);
                
                $comment->addLike($comment_id);
                $comment->updateLikedCount($comment_id);
            }
        }
        $this->set(get_defined_vars());
        redirect("/comment/view?page={$_SESSION['current_page']}&thread_id={$_SESSION['thread_id']}");
    }

    public function disliked()
    {
        $comment = new Comment();

        $comment_id = Param::get('comment_id');
        $like_checker = $comment->likeChecker($comment_id);
        $dislike_checker = $comment->dislikeChecker($comment_id);

        if (!$dislike_checker) {
            if (!$like_checker) {
                $comment->addDislike($comment_id);
                $comment->updateDislikedCount($comment_id);
            } else {
                $comment->subtractLikedCount($comment_id);
                $comment->deleteExisting($comment_id);
                
                $comment->addDislike($comment_id);
                $comment->updateDislikedCount($comment_id);
            }
        }

        $this->set(get_defined_vars());
        redirect("/comment/view?page={$_SESSION['current_page']}&thread_id={$_SESSION['thread_id']}");
    }

    public function most_liked()
    {
        $comment = new Comment();

        $comments = $comment->getTopComments();

        if ($comments) {
            $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_threads = array_slice($comments, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
            $pagination->checkLastPage($other_threads);
            $page_links = createPageLinks(count($comments), $current_page, $pagination->count);
            $comments = array_slice($comments, $pagination->start_index - 1, $pagination->count);
        }

        $this->set(get_defined_vars());
    }
}
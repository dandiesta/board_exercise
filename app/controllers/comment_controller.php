<?php

class CommentController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 4;
    const MIN_PAGE_NUMBER = 1;

    public function view()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment();
        $_SESSION['thread_id'] = $thread->id;
        $comments = $comment->getComments($_SESSION['thread_id']);
        
        $current_page = max(Param::get('page'), self::MIN_PAGE_NUMBER);
        $chunk_page = array_chunk($comments, self::MAX_ITEMS_PER_PAGE);
        $count_chunks = count($chunk_page); 

        $pagination = new SimplePagination($current_page);
        $display = $pagination->commentLinks($chunk_page, $current_page);
        $pagination->checkLastPage($count_chunks);

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
        $this->render($page);
    }
}
<?php

class CommentController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 5;
    const MIN_PAGE_NUMBER = 1;

    public function view()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment();
        
        $comments = $comment->getComments($thread->id);
        
        $current = max(Param::get('page'), self::MIN_PAGE_NUMBER);
        $chunk_page = array_chunk($comments, self::MAX_ITEMS_PER_PAGE);
        $count_chunks = count($chunk_page); 

        $pagination = new SimplePagination($current);
        $display = $pagination->commentLinks($chunk_page, $current);
        $pagination->checkLastPage($count_chunks);

        $this->set(get_defined_vars());
    }

    public function write()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment();
        $page = Param::get('page_next', 'write');

        switch ($page) {
            case 'write':
                break;
            case 'write_end':
                $comment->body = Param::get('body');

                try {
                    $comment->write($comment, $thread->id, $_SESSION['userid']);
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
}
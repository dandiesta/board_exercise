<?php

class ThreadController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 6;
    const MIN_PAGE_NUMBER = 1;

    public function index()
    {
        $threads = Thread::getAll();

        $current = max(Param::get('page'), self::MIN_PAGE_NUMBER);
        $chunk_page = array_chunk($threads, self::MAX_ITEMS_PER_PAGE);
        $count_chunks = count($chunk_page);
            
        $pagination = new SimplePagination($current);
        $display = $pagination->threadLinks($chunk_page, $current);
        $pagination->checkLastPage($count_chunks);

        $this->set(get_defined_vars());          
    }

    public function my_thread()
    {
        $myThread = Thread::getMyThreads($_SESSION['userid']);
        
        if ($myThread) {
            $current = max(Param::get('page'), self::MIN_PAGE_NUMBER);
            $chunk_page = array_chunk($myThread, self::MAX_ITEMS_PER_PAGE);
            $count_chunks = count($chunk_page);

            $pagination = new SimplePagination($current);
            $display = $pagination->threadLinks($chunk_page, $current);
            $pagination->checkLastPage($count_chunks);

            $this->set(get_defined_vars());
        }
    }

    public function create()
    {
        $thread = new Thread();
        $comment = new Comment();
        $page = Param::get('page_next', 'create');

        switch ($page) {
            case 'create':
                break;
            case 'create_end':
                $thread->title = Param::get('title');
                $comment->body = Param::get('body');

                try {
                    $thread->create($comment, $_SESSION['userid']);
                } catch (ValidationException $e) {
                    $page = 'create';
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
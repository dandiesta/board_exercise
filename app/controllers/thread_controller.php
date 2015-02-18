<?php

class ThreadController extends AppController
{
    const MAX_ITEMS_PER_PAGE = 5;
    const MIN_PAGE_NUMBER = 1;
    const ADJACENT_TO_CURRENT = 4;

    public function index()
    {
        $threads = Thread::getAll();
        $users = new User();
        $user = $users->getAll();

        if ($threads) {
            $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_threads = array_slice($threads, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
            $pagination->checkLastPage($other_threads);
            $page_links = Pagination(count($threads), self::MAX_ITEMS_PER_PAGE, $current_page, self::ADJACENT_TO_CURRENT);
            $threads = array_slice($threads, $pagination->start_index - 1, $pagination->count);

            $count = count($page_links);
        }

        $this->set(get_defined_vars());
    }

    public function my_thread()
    {
        $my_thread = Thread::getAll($_SESSION['userid']);
        $users = new User();
        $user = $users->getAll();

        if ($my_thread) {
            $current_page = max(Param::get('page'), SimplePagination::MIN_PAGE_NUM);
            $pagination = new SimplePagination($current_page, self::MAX_ITEMS_PER_PAGE);
            $other_my_thread = array_slice($my_thread, $pagination->start_index + SimplePagination::MIN_PAGE_NUM);
            $pagination->checkLastPage($other_my_thread);
            $page_links = Pagination(count($my_thread), self::MAX_ITEMS_PER_PAGE, $current_page, self::ADJACENT_TO_CURRENT);
            $my_thread = array_slice($my_thread, $pagination->start_index - 1, $pagination->count);

            $count = count($page_links);
        }

        $this->set(get_defined_vars());
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
            $thread->title = trim(Param::get('title'));
            $comment->body = trim(Param::get('body'));

            try {
                $thread->create($comment);
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

    public function edit()
    {
        $threads = new Thread();
        $thread_id = Param::get('thread_id');
        $thread = Thread::get($thread_id);

        $page = Param::get('page_next', 'edit');
        $title = $thread->title;

        switch ($page) {
            case 'edit':
                break;
            case 'edit_end':
                $threads->title = Param::get('title');

                try {
                    $threads->editTitle($thread_id);
                    redirect("/comment/view?thread_id={$thread_id}");
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
        $threads = new Thread();
        $comments = new Comment();

        $thread_id = Param::get('thread_id');

        $comments->deleteLike($thread_id);
        $threads->delete($thread_id);
        $comments->deleteAll($thread_id);

        redirect("/thread/index?page={$_SESSION['current_page']}&");
    }
}
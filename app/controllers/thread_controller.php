<?php
    class ThreadController extends AppController
    {
        public function index()
        {
        	//Create an instance of Thread model, and call its static function getAll()
            $threads = Thread::getAll(); 
            //Set all defined vars to its view (views/thread/index.php)
            $this->set(get_defined_vars());
        }

        public function my_thread()
        {
            $myThread = Thread::getMyThreads();
            $this->set(get_defined_vars());
        }

      
        public function create()
        {
            $thread = new Thread;
            $comment = new Comment;
            $page = Param::get('page_next', 'create');

            switch ($page) {
                case 'create':
                    break;
                case 'create_end':
                    $thread->title = Param::get('title');
                    $comment->body = Param::get('body');

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

        
    }
?>
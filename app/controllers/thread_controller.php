<?php
    class ThreadController extends AppController
    {
        #CONST MAX_PAGINATION_COUNT =10; //max display ng pages initially
        CONST MAX_ITEMS_PER_PAGE = 6; //max kung ilan ididisplay per page
        CONST MIN_PAGE_NUMBER = 1;

        public function index()
        {
        	//Create an instance of Thread model, and call its static function getAll()
            $threads = Thread::getAll(); 

            $current = max(Param::get('page'), self::MIN_PAGE_NUMBER);
            $chunk_page = array_chunk($threads, self::MAX_ITEMS_PER_PAGE); //nahati hati na yung array into chunks
            $count_chunks = count($chunk_page); //kung ilang chunks meron
            
            $pagination = new SimplePagination($current);
            $display = $pagination->threadLinks($chunk_page, $current);
            $pagination->checkLastPage($count_chunks);

            //Set all defined vars to its view (views/thread/index.php)
            $this->set(get_defined_vars());

            
            
        }

        public function my_thread()
        {
            
            $myThread = Thread::getMyThreads();
            
            $current = max(Param::get('page'), self::MIN_PAGE_NUMBER);
            $chunk_page = array_chunk($myThread, self::MAX_ITEMS_PER_PAGE);
            $count_chunks = count($chunk_page);
            
            $pagination = new SimplePagination($current);
            $display = $pagination->threadLinks($chunk_page, $current);
            $pagination->checkLastPage($count_chunks);

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

        public function trial()
        {
            $db = array(
                1 => array('id' => '1'),
                2 => array('id' => '2'),
                3 => array('id' => '3'),
                4 => array('id' => '4'),
                5 => array('id' => '5'),
                6 => array('id' => '6'),
                7 => array('id' => '7'),
                8 => array('id' => '8'),
                9 => array('id' => '9'),
            );
            $items = Thread::getAll();
            $current = Param::get('page');
            $count = 3;
            if ($current === 1) {
                $items = array(
                $db[1],
                $db[2],
                $db[3],
                $db[4],
                );
            } else if ($current === 2) {
                $items = array(
                $db[4],
                $db[5],
                $db[6],
                $db[7],
                );
            } else if ($current === 3) {
                $items = array(
                $db[7],
                $db[8],
                $db[9],
                );
            } else {
                $current = 1;
                $items = array(
                $db[1],
                $db[2],
                $db[3],
                $db[4],
                );
            }
            $pagination = new SimplePagination($current, $count);
            $pagination->checkLastPage($items);
            $this->set(get_defined_vars());

        }
        
    }
?>
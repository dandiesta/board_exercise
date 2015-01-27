<?php

class CommentController extends AppController
{
	public function view()
        {
            $thread = Thread::get(Param::get('thread_id'));
            //to contain all the comments returned from the function 
            //getComment() as an array, and pass it to the view
            $comment = new Comment;
            $comments = $comment->getComments($thread->id);

            $this->set(get_defined_vars());
        }

        public function write()
        {
            $thread = Thread::get(Param::get('thread_id'));
            $comment = new Comment;
            $page = Param::get('page_next', 'write');

            switch ($page) {
                case 'write':
                    break;
                case 'write_end':
                    $comment->body = Param::get('body');
                    try {
                       $comment->write($comment, $thread->id);
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

?>
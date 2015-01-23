<?php

class UserController extends AppController
{
	public function registration()
	{
		$register = new User;
		$page = Param::get('page_next', 'registration'); //name of page yung registration

		switch ($page) {
			case 'registration':
				break;
			case 'success':
				$register->fname = Param::get('fname');
				$register->lname = Param::get('lname');
				$register->username = Param::get('username');
				$register->password = Param::get('password');
				$register->confirm_password = Param::get('repeat_password');
			
				try {
              		$register->add();
            	} catch (ValidationException $e) {                    
            	   $page = 'registration';
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
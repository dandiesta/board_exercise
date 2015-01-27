<h2><?php enquote_string($thread->title) ?></h2>

<p class="alert alert-success">
	You have successfully created a thread.
</p>

<a href="<?php enquote_string(url('thread/view', array('thread_id' => $thread->id))) ?>">
	&larr; Go to thread
</a>
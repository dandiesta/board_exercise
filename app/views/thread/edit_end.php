<h2><?php enquote_string($threads->title) ?></h2>

<p class="alert alert-success">
    You have successfully edited the title of this thread.
</p>

<a href="<?php enquote_string(url('comment/view', array('thread_id' => $thread->id))) ?>">
    &larr; Go to thread
</a>
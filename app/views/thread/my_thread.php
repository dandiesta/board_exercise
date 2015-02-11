<h2>My threads</h2>

<?php if (empty($my_thread)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">You haven't posted any threads yet!</h4>
    </div>
<?php else: ?>
    <form method="post" action="<?php enquote_string(url('')) ?>">
        <ul class="nav">
            <?php foreach ($my_thread as $v): ?>
                <li>
                    <div class="well span11">
                        <div class="span10">
                            <a href="<?php enquote_string(url('comment/view', array('thread_id'=>$v->id)))?>" >
                                <strong><?php enquote_string($v->title); ?></strong><br/>
                            </a>
                            <small>
                                Posted by: <?php enquote_string($v->username);?>&nbsp;
                                    <a href="<?php enquote_string(url('thread/edit', array('thread_id'=>$v->id)))?>">
                                        <i class="icon-pencil"></i></a> &nbsp;
                                    <a href="<?php enquote_string(url('thread/delete', array('thread_id'=>$v->id)))?>" 
                                        onclick="return confirm('Are you sure you want to delete this thread?')">
                                            <i class="icon-trash"></i></a>
                                <div style="color:#66CCFF"><?php getElapsedTime($v->created); ?> ago</div>
                            </small>
                        </div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </form>

    <!--pagination-->
    <form class="span12">
        <?php if($pagination->current > 1): ?>
            &nbsp;<a class='btn btn-danger' href='?page=<?php enquote_string($pagination->prev) ?>'>Previous</a>
        <?php endif ?>
        
        &nbsp; <?php echo $page_links; ?> &nbsp;
        
        <?php if(!$pagination->is_last_page): ?>
            <a class='btn btn-danger' href='?page=<?php enquote_string($pagination->next) ?>'>Next</a>
        <?php endif ?>
    </form>
<?php endif ?>
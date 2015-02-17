<h2>My threads</h2>

<?php if (empty($my_thread)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">You haven't posted any threads yet!</h4>
    </div>
<?php else: ?>
    <form method="post" action="<?php enquote_string(url('')) ?>">
        <ul class="nav">
            <?php foreach ($my_thread as $v): ?>
                <?php foreach ($user as $u): ?>
                    <?php if ($v->user_id == $u['id']): ?>
                        <li>
                            <div class="well span11" style="box-shadow: 10px 10px 10px #888888">
                                <div class="span10">
                                    <a href="<?php enquote_string(url('comment/view', array('thread_id'=>$v->id)))?>" >
                                        <strong><?php enquote_string($v->title); ?></strong><br/>
                                    </a>
                                    <small>
                                        Posted by: <a href="<?php enquote_string(url('user/profile'))?>"><?php enquote_string($u['username']);?></a>&nbsp;
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
                    <?php endif ?>
                <?php endforeach ?>
            <?php endforeach ?>
        </ul>
    </form>

    <!--pagination-->
    <form class="span12">
        <?php if($pagination->current > 1): ?>
            &nbsp;<a class='btn btn-danger' href='?page=<?php enquote_string($pagination->prev) ?>'>Previous</a>
        <?php endif ?>
        
        <?php for ($i=0; $i < $count; $i++): ?>
            <?php if ($page_links[$i] == $pagination->current): ?>
                <a class='btn btn-default' disabled><?php echo $page_links[$i]?></a>
            <?php else: ?>
                <a class='btn btn-danger' href='?page=<?php enquote_string($page_links[$i]) ?>'><?php echo $page_links[$i]?></a>
            <?php endif?>
        <?php endfor ?>
        
        <?php if(!$pagination->is_last_page): ?>
            <a class='btn btn-danger' href='?page=<?php enquote_string($pagination->next) ?>'>Next</a>
        <?php endif ?>
    </form>
<?php endif ?>
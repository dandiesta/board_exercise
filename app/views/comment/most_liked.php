<h2>Top comments with most likes</h2>
<hr />
<?php if (empty($comments)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">There are no comments that have been liked!</h4>
    </div>
<?php else: ?>
    <form method="post" action="<?php enquote_string(url('')) ?>">
        <ul class="nav">
            <?php foreach ($comment as $v): ?>
                <?php foreach ($user as $u):?>
                    <?php if ($v->user_id == $u['id']): ?>
                        <div class="well span11" style="box-shadow: 10px 10px 10px #888888">
                            <div class="span9">
                                <div style="font-size:20px;"><?php echo readable_text($v->body) ?></div>
                                
                                <div class="meta">
                                    by: <?php enquote_string($u['username']) ?>
                                    <?php if (($_SESSION['usertype'] == 'admin')):?>
                                        <a href="<?php enquote_string(url('comment/delete', array('comment_id'=>$v->id)))?>" 
                                            onclick="return confirm('Are you sure you want to delete this thread?')">
                                                <i class="icon-trash"></i></a>
                                    <?php endif ?>
                                </div>
                                <div style="color:#FF9999;"><small><?php getElapsedTime($v->created) ?> ago</small></div>
                            </div>
                            <p style="color:#669999">
                                Like: <?php enquote_string($v->liked) ?><br />
                                Dislike: <?php enquote_string($v->disliked) ?>
                            </p>                 
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endforeach ?>
        </ul>
    </form>

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
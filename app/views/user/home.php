<div style="font-size:30px; text-shadow: 2px 2px #66FFFF;">
    <strong><?php echo "Welcome, ". $firstname . "!";  ?></strong>

    <?php if ($_SESSION['usertype'] == 1): ?>
    	<a class="btn btn-danger" href ="<?php enquote_string(url('user/status')) ?>">Edit User Status</a>
    <?php endif ?>
    
    <a class="btn btn-danger" href ="<?php enquote_string(url('user/top_five')) ?>">Your Top 5 Likers and Commentors</a>
</div>
<hr />
<h3>
	Want to know the talk of the town?
	<small>Ordered by comment counts
    <a href="<?php enquote_string(url('comment/most_liked')) ?>">View Top Comments with Most Likes</a></small>
</h3>

<?php if (empty($threads)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">There are no threads yet!</h4>
    </div>
<?php else: ?>
    <form method="post" action="<?php enquote_string(url('')) ?>">
        <ul class="nav">
            <?php foreach ($threads as $v): ?>
                <li>
                    <div class="well span11" style="box-shadow: 10px 10px 10px #888888">
                       <div class="span10">
                            <a href="<?php enquote_string(url('comment/view', array('thread_id'=>$v->id)))?>" >
                                <strong><?php enquote_string($v->title); ?></strong><br/>
                            </a>
                            <small>
                                <?php if ($v->thread_count == 1) :?>
                                    1 comment
                                <?php else : ?>
                                    <?php enquote_string($v->thread_count) ?> comments
                                <?php endif ?>
                                <?php if ($_SESSION['userid'] == $v->user_id):?>
                                    <a href="<?php enquote_string(url('thread/edit', array('thread_id'=>$v->id)))?>">
                                        <i class="icon-pencil"></i></a> &nbsp;
                                <?php endif ?>
                                <?php if (($_SESSION['userid'] == $v->user_id) || ($_SESSION['usertype'] == 1)) :?>
                                    <a href="<?php enquote_string(url('user/delete', array('thread_id'=>$v->id)))?>" 
                                        onclick="return confirm('Are you sure you want to delete this thread?')">
                                            <i class="icon-trash"></i></a>
                                <?php endif ?>
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
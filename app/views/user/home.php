<h2><?php echo "Welcome, ". $firstname . "!";  ?></h2>
<!--<script>alert('Welcome, ". $firstname . "!')</script>";-->
<?php if ($_SESSION['usertype'] == 'admin'): ?>
	<a class="btn btn-danger" href ="<?php enquote_string(url('user/status')) ?>">Edit User Status</a>
<?php endif ?>
<a class="btn btn-danger" href ="<?php enquote_string(url('user/top_five')) ?>">Your Top 5 Likers and Commentors</a>
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
                    <div class="well span11">
                       <div class="span10">
                            <a href="<?php enquote_string(url('comment/view', array('thread_id'=>$v->id)))?>" >
                                <strong><?php enquote_string($v->title); ?></strong><br/>
                            </a>
                            <small>
                                <?php echo $v->thread_count ?> comments
                                <?php if ($_SESSION['userid'] == $v->user_id):?>
                                    <a href="<?php enquote_string(url('thread/edit', array('thread_id'=>$v->id)))?>">
                                        <i class="icon-pencil"></i></a> &nbsp;
                                <?php endif ?>
                                <?php if (($_SESSION['userid'] == $v->user_id) || ($_SESSION['usertype'] == 'admin')) :?>
                                    <a href="<?php enquote_string(url('thread/delete', array('thread_id'=>$v->id)))?>" 
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
        
        &nbsp; <?php echo $page_links; ?> &nbsp;
        
        <?php if(!$pagination->is_last_page): ?>
            <a class='btn btn-danger' href='?page=<?php enquote_string($pagination->next) ?>'>Next</a>
        <?php endif ?>
    </form>
<?php endif ?>
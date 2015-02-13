<h2><?php enquote_string($thread->title) ?><small> By: <?php enquote_string($user['username']) ?></small></h2>
<hr />
<?php if (empty($comments)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">There are no comments yet!</h4>
    </div>
<?php else: ?>
    <?php foreach ($comments as $v): ?>
        <div class="well span11">
            <div class="span10">
                <blockquote><?php enquote_string($v->body) ?></blockquote>
                
                <div class="meta">
                    by: <a href="<?php enquote_string(url('user/others', array('user_id'=>$v->user_id)))?>"><?php enquote_string($v->username);?></a>&nbsp;
                    <?php if ($_SESSION['userid'] == $v->user_id):?>
                        <a href="<?php enquote_string(url('comment/edit', array('comment_id'=>$v->id)))?>">
                            <i class="icon-pencil"></i></a> &nbsp;
                    <?php endif ?>
                    <?php if (($_SESSION['userid'] == $v->user_id) || ($_SESSION['usertype'] == 1)):?>
                        <a href="<?php enquote_string(url('comment/delete', array('comment_id'=>$v->id)))?>" 
                            onclick="return confirm('Are you sure you want to delete this comment?')">
                                <i class="icon-trash"></i></a>
                    <?php endif ?>
                </div>
                <div style="color:#FF9999;"><small><?php getElapsedTime($v->created) ?> ago</small></div>
            </div>
            <a href="<?php enquote_string(url('comment/liked', array('comment_id'=>$v->id)))?>"><i class="icon-thumbs-up"></i></a> &nbsp;
            <a href="<?php enquote_string(url('comment/disliked', array('comment_id'=>$v->id)))?>"><i class="icon-thumbs-down"></i></a><br />
            <?php enquote_string($v->liked) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php enquote_string($v->disliked) ?>
        </div>
    <?php endforeach ?>

    <!--pagination-->
    <form class="span12">
        <?php if($pagination->current > 1): ?>
            &nbsp;<a class='btn btn-danger' href='?page=<?php enquote_string($pagination->prev) ?>&thread_id=<?php enquote_string($thread->id)?>'>Previous</a>
        <?php endif ?>
        
        &nbsp; <?php echo $page_links; ?> &nbsp;
        
        <?php if(!$pagination->is_last_page): ?>
            <a class='btn btn-danger' href='?page=<?php enquote_string($pagination->next) ?>&thread_id=<?php enquote_string($thread->id)?>'>Next</a>
        <?php endif ?>
    </form>
<?php endif ?>

<hr />

<form class="well span11" method="post" action="<?php enquote_string(url('comment/write')) ?>">
    <label>COMMENT</label>
    <textarea name="body" class="span11"><?php enquote_string(Param::get('body'))?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php enquote_string($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-danger"> Submit</button>
</form>

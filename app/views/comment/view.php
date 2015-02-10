<h1><?php enquote_string($thread->title) ?></h1>
<hr />
<?php if (empty($comments)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">There are no comments yet!</h4>
    </div>
<?php else: ?>
    <?php foreach ($display as $v): ?>
        <div class="well span11">
            <div class="span10">
                <div style="font-size:20px;"><?php echo readable_text($v['body']) ?></div>
                
                <div class="meta">
                    by: <?php enquote_string($v['username']) ?>
                    <?php if ($_SESSION['userid'] == $v['user_id']):?>
                        <a href="<?php enquote_string(url('comment/edit', array('comment_id'=>$v['id'])))?>">
                            <i class="icon-pencil"></i></a> &nbsp;
                    <?php endif ?>
                    <?php if (($_SESSION['userid'] == $v['user_id']) || ($_SESSION['usertype'] == 'admin')):?>
                        <a href="<?php enquote_string(url('comment/delete', array('comment_id'=>$v['id'])))?>" 
                            onclick="return confirm('Are you sure you want to delete this thread?')">
                                <i class="icon-trash"></i></a>
                    <?php endif ?>
                </div>

                <div style="color:#FF9999;"><small><?php getElapsedTime($v['created']) ?> ago</small></div>
            </div>
            <a href="<?php enquote_string(url(''))?>"><i class="icon-thumbs-up"></i></a> &nbsp;
            <a href="#"><i class="icon-thumbs-down"></i></a>
        </div>
    <?php endforeach ?>

    <!--pagination-->
    <form class="span12">
    <?php if($pagination->current_page > 1): ?>
        <a class="btn btn-danger" href='?page=<?php echo $pagination->prev ?>&thread_id=<?php enquote_string($thread->id)?>'>
            Previous</a>
    <?php endif ?>

    <?php for ($i=1; $i <= $count_chunks; $i++): ?>
        <?php if ($pagination->current_page == $i):?>
            <a class="btn btn-default disabled"><?php echo $i; ?></a>      
        <?php else:?>
            <a class="btn btn-danger" href="?page=<?php echo $i ?>&thread_id=<?php enquote_string($thread->id)?>">
                <?php echo $i; ?></a>&nbsp;
        <?php endif ?>
    <?php endfor ?>  

    <?php if(!$pagination->is_last_page): ?>
        <a class="btn btn-danger" href='?page=<?php echo $pagination->next ?>&thread_id=<?php enquote_string($thread->id)?>'>
            Next</a>
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

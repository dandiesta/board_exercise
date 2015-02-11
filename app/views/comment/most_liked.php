<h2>Top comments with most likes</h2>
<hr />
<?php if (empty($comments)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">There are no comments that have been liked!</h4>
    </div>
<?php else: ?>
    <form method="post" action="<?php enquote_string(url('')) ?>">
        <ul class="nav">
            <?php foreach ($display as $v): ?>
                <div class="well span11">
                    <div class="span10">
                        <div style="font-size:20px;"><?php echo readable_text($v['body']) ?></div>
                        
                        <div class="meta">
                            by: <?php enquote_string($v['username']) ?>
                            <?php if (($_SESSION['usertype'] == 'admin')):?>
                                <a href="<?php enquote_string(url('comment/delete', array('comment_id'=>$v['id'])))?>" 
                                    onclick="return confirm('Are you sure you want to delete this thread?')">
                                        <i class="icon-trash"></i></a>
                            <?php endif ?>
                        </div>
                        <div style="color:#FF9999;"><small><?php getElapsedTime($v['created']) ?> ago</small></div>
                    </div>
                    <a href="<?php enquote_string(url('comment/liked', array('comment_id'=>$v['id'])))?>"><i class="icon-thumbs-up"></i></a> &nbsp;
                    <a href="<?php enquote_string(url('comment/disliked', array('comment_id'=>$v['id'])))?>"><i class="icon-thumbs-down"></i></a><br />
                    <?php echo $v['liked'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $v['disliked'] ?>
                </div>
            <?php endforeach ?>
        </ul>
    </form>

    <form class="span12">
    <?php if($pagination->current_page > 1): ?>
        <a class="btn btn-danger" href="?page=<?php echo $pagination->prev ?>">
            Previous</a>
    <?php endif ?>

    <?php for ($i=1; $i <= $count_chunks; $i++): ?>
        <?php if ($pagination->current_page == $i):?>
            <a class="btn btn-default disabled"><?php echo $i; ?></a>      
        <?php else:?>
            <a class="btn btn-danger" href="?page=<?php echo $i ?>">
                <?php echo $i; ?></a>&nbsp;
        <?php endif ?>
    <?php endfor ?>  

    <?php if(!$pagination->is_last_page): ?>
        <a class="btn btn-danger" href="?page=<?php echo $pagination->next ?>">
            Next</a>
    <?php endif ?>
    </form>
<?php endif ?>
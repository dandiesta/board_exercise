<h2><?php echo "Welcome, ". $firstname . "!";  ?></h2>
<hr />
<h3>
	Talk of the town
	<small>Ordered by comment counts</small>
</h3>

<?php if (empty($threads)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">There are no threads yet!</h4>
    </div>
<?php else: ?>
    <form method="post" action="<?php enquote_string(url('')) ?>">
        <ul class="nav">
            <?php foreach ($display as $v): ?>
                <li>
                    <div class="well span11">
                       <div class="span10">
                            <a href="<?php enquote_string(url('comment/view', array('thread_id'=>$v['id'])))?>" >
                                <strong><?php enquote_string($v['title']); ?></strong><br/>
                            </a>
                            <small>
                                <!-- Posted by: <?php enquote_string($v['username']);?>&nbsp; -->
                                <?php echo $v['thread_count'] ?> comments
                                <?php if ($_SESSION['userid'] == $v['user_id']):?>
                                    <a href="<?php enquote_string(url('thread/edit', array('thread_id'=>$v['id'])))?>">
                                        <i class="icon-pencil"></i></a> &nbsp;
                                <?php endif ?>
                                <?php if (($_SESSION['userid'] == $v['user_id']) || ($_SESSION['usertype'] == 'admin')) :?>
                                    <a href="<?php enquote_string(url('thread/delete', array('thread_id'=>$v['id'])))?>" 
                                        onclick="return confirm('Are you sure you want to delete this thread?')">
                                            <i class="icon-trash"></i></a>
                                <?php endif ?>
                                <div style="color:#66CCFF"><?php getElapsedTime($v['created']); ?> ago</div>
                            </small>
                        </div>
                        <a href="<?php enquote_string(url(''))?>"><i class="icon-thumbs-up"></i></a> &nbsp;
                        <a href="#"><i class="icon-thumbs-down"></i></a>
                    </div>
                </li>
              <?php endforeach ?>
        </ul>
    </form>

    <!--pagination-->
<?php if($pagination->current_page > 1): ?>
        <a class="btn btn-danger" href='?page=<?php echo $pagination->prev ?>'>Previous</a>
    <?php endif ?>

    <?php for ($i=1; $i <= $count_chunks; $i++): 
        if ($pagination->current_page == $i):?>
            <a class="btn btn-default disabled" href="?page=<?php echo $i ?>"><?php echo $i; ?></a>      
        <?php else:?>
            <a class="btn btn-danger" href="?page=<?php echo $i ?>"><?php echo $i; ?></a>&nbsp;
        <?php endif ?>
    <?php endfor ?>  

    <?php if(!$pagination->is_last_page): ?>
        <a class="btn btn-danger" href='?page=<?php echo $pagination->next ?>'>Next</a>
    <?php endif ?>
<?php endif ?>
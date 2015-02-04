<h2>All threads</h2>

<form method="post" action="<?php enquote_string(url('')) ?>">
    <ul class="nav">
        <?php foreach ($display as $v): ?>
            <li class="well span11">
               <div class="span10">
                    <a href="<?php enquote_string(url('comment/view', array('thread_id'=>$v['id'])))?>" >
                        <strong><?php enquote_string($v['title']); ?></strong><br/>
                        <small>
                            Posted by: <?php enquote_string($v['username']);?>
                            <div style="color:#66CCFF"><?php getElapsedTime($v['created']); ?> ago</div>
                        </small>
                    </a>
                </div>
                <?php if ($_SESSION['userid'] == $v['user_id']):?>
                    <a href="<?php enquote_string(url('thread/edit', array('thread_id'=>$v['id'])))?>"><i class="icon-pencil"></i></a> &nbsp;
                    <a href="<?php enquote_string(url('thread/delete', array('thread_id'=>$v['id'])))?>"><i class="icon-trash"></i></a>
                <?php endif ?>
            </li>
          <?php endforeach ?>
    </ul>
</form>

<!--pagination-->
<form class="span12">
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
</form>
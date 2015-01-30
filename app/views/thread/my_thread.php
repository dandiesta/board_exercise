<h2>My threads</h2>
<?php if (empty($myThread)): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">You haven't posted any threads yet!</h4>
    </div>
<?php else: ?>
<form method="post" action="<?php enquote_string(url('')) ?>">
    <ul class="nav">
        <?php foreach ($display as $v): ?>
            <li class="well">
                <a href="<?php enquote_string(url('comment/view', array('thread_id' => $v['id'])))?>">
                    <?php enquote_string($v['title']); ?><br/>
                    <small>Posted by: <?php enquote_string($v['username']); ?></small>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</form>

<!--pagination-->
<?php if($pagination->current > 1): ?>
    <a class="btn btn-danger" href='?page=<?php echo $pagination->prev ?>'>Previous</a>
<?php endif ?>

<?php for ($i=1; $i <= $count_chunks; $i++):
    if ($pagination->current == $i):?>
        <a class="btn btn-default disabled" href="?page=<?php echo $i ?>"><?php echo $i; ?></a>      
    <?php else:?>
        <a class="btn btn-danger" href="?page=<?php echo $i ?>"><?php echo $i; ?></a>&nbsp;
    <?php endif ?>
<?php endfor ?>  

<?php if(!$pagination->is_last_page): ?>
    <a class="btn btn-danger" href='?page=<?php echo $pagination->next ?>'>Next</a>
<?php endif ?>
<?php endif ?>
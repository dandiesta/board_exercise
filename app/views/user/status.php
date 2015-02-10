<h2>All Users</h2>
<form method="post" action="<?php enquote_string(url('')) ?>">
    <ul class="nav">
        <?php foreach ($user as $v): ?>
            <li>
                <div class="well well-small span11">
                    <div class="span9">
                        <small>
                            <strong><?php echo $v['firstname'] . ' ' . $v['lastname']?></strong><br />
                            Member since: <?php getElapsedTime($v['registration_date']); ?> ago <br />
                           	STATUS: <strong><?php echo $v['status'] ?></strong>
                        </small>
                    </div>
                    <a href ="<?php enquote_string(url('user/edit_status', array('user_id'=>$v['id'])))?>" class="btn btn-default" onclick="return confirm('Are you sure you want to change this user\'s status?')">
                        Change status</a>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</form>

    <!--pagination-->
<!--<?php if($pagination->current_page > 1): ?>
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
    <?php endif ?>-->
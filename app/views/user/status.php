<h2>All Users</h2>

<?php if (!$user) : ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">No other users!</h4>
        <div>
            Admin is the only user.
        </div>
    </div>
<?php else : ?>
    <form method="post" action="<?php enquote_string(url('')) ?>">
        <ul class="nav">
            <?php foreach ($user as $v): ?>
                <li>
                    <div class="well well-small span11" style="box-shadow: 10px 10px 10px #888888">
                        <div class="span9">
                            <small>
                                <strong><?php enquote_string($v['firstname'] . ' ' . $v['lastname'])?></strong><br />
                                Username: <strong><?php enquote_string($v['username']) ?></strong> <br />
                                Member since: <?php getElapsedTime($v['registration_date']); ?> ago <br />
                               	STATUS: <strong><?php if ($v['status'] == 1) : enquote_string('Active'); else : enquote_string('Banned'); endif ?></strong>
                            </small>
                        </div>
                        <a href ="<?php enquote_string(url('user/edit_status', array('user_id'=>$v['id'])))?>" class="btn btn-default" onclick="return confirm('Are you sure you want to change this user\'s status?')">
                            Change status</a>
                    </div>
                </li>
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

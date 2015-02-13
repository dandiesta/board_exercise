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

    &nbsp; <?php echo $page_links; ?> &nbsp;
    
    <?php if(!$pagination->is_last_page): ?>
        <a class='btn btn-danger' href='?page=<?php enquote_string($pagination->next) ?>'>Next</a>
    <?php endif ?>
</form>
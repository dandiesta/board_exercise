<div class="span5">
    <h2>Your top 5 likers</h2>
    <?php if (empty($top_likers)): ?>
        <div class="alert alert-block">
            <h4 class="alert-heading">Your comments haven't been liked.</h4>
        </div>
    <?php else: ?>
        <form method="post" action="<?php enquote_string(url('')) ?>" class="well shadow">
            <ol style="font-size: 18px">
                <?php foreach ($top_likers as $v): ?>
                    <li>
                        <strong><?php enquote_string($v->Liker); ?></strong> with
                        <small>
                            <?php if ($v->Number_of_likes == 1) : ?>
                                1 like
                            <?php else: ?>
                                <?php enquote_string($v->Number_of_likes) ?> likes
                            <?php endif ?>
                        </small>
                    </li>
                <?php endforeach ?>
            </ol>
        </form>
    <?php endif ?>
</div>

<div class="span5">
    <h2>Your top 5 commentors</h2>
    <?php if (empty($top_commentors)): ?>
        <div class="alert alert-block">
            <h4 class="alert-heading">Your threads don't have comments.</h4>
        </div>
    <?php else: ?>
        <form method="post" action="<?php enquote_string(url('')) ?>" class="well shadow">
            <ol style="font-size: 18px">
                <?php foreach ($top_commentors as $v): ?>
                    <li>
                        <strong><?php enquote_string($v->Commentor); ?></strong> with
                        <small>
                            <?php if ($v->Number_of_comments == 1) : ?>
                                1 comment
                            <?php else: ?>
                                <?php enquote_string($v->Number_of_comments) ?> comments
                            <?php endif ?>
                        </small>
                    </li>
                  <?php endforeach ?>
            </ol>
        </form>
    <?php endif ?>
</div>
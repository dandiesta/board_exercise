<center><h3>Create a thread</h3></center>

<?php if ($thread->hasError() || $comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>

        <?php if (($thread->validation_errors['title']['length'])): ?>
            <div>
                <em>Title</em> must be between
                <?php enquote_string($thread->validation['title']['length'][1]) ?> and
                <?php enquote_string($thread->validation['title']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if (($comment->validation_errors['body']['length'])): ?>
            <div>
                <em>Body</em> must be between
                <?php enquote_string($comment->validation['body']['length'][1]) ?> and
                <?php enquote_string($comment->validation['body']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="span8 offset2 well" method="post" action="<?php enquote_string(url('')) ?>" style="box-shadow: 10px 10px 10px #888888">
    <label>Title</label>
        <input type="text" class="span8" name="title" value="<?php enquote_string(Param::get('title')) ?>">
    <label>Comment</label>
        <textarea name="body" class="span8"><?php enquote_string(Param::get('body')) ?></textarea>
    
    <br />
    <input type="hidden" name="page_next" value="create_end">
    <button type="submit" class="btn btn-danger">Submit</button>
</form>

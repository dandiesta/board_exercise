<h2><?php echo $title ?></h2>
<?php if ($comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php if ($comment->validation_errors['body']['length']): ?>
            <div>
                <em>Comment</em> must be between
                <?php enquote_string($comment->validation['body']['length'][1]) ?> and
                <?php enquote_string($comment->validation['body']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="well shadow" method="post" action="<?php enquote_string(url('')) ?>">
    <label>Comment</label>
        <textarea class="span11" name="body"><?php echo $body ?></textarea>
    <br />
    <input type="hidden" name="page_next" value="edit_end">
    <button type="submit" class="btn btn-danger">Submit</button>
</form>
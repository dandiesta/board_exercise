<h2><?php enquote_string($thread->title) ?></h2>

<?php if ($comment->hasError()): ?> <!--checks if the Comment Model hasError()
hasError() is a functin in Model Parent class that checks the value of the array $validation_errors 
(contains errors called from validate() function)-->

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

<form class="well shadow" method="post" action="<?php enquote_string(url('comment/write'))?>" style="box-shadow: 10px 10px 10px #888888">
    <label>Comment</label>
        <textarea class="span11" name="body"><?php enquote_string(Param::get('body')) ?></textarea>
    
    <br />
    
    <input type="hidden" name="thread_id" value="<?php enquote_string($thread->id)?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-danger">Submit</button>
</form>
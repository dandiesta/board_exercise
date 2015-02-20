<center><h3>Edit a thread</h3></center>

<?php if ($threads->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>

        <?php if ($threads->validation_errors['title']['length']): ?>
            <div>
                <em>Title</em> must be between
                <?php enquote_string($threads->validation['title']['length'][1]) ?> and
                <?php enquote_string($threads->validation['title']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="span8 offset2 well shadow" method="post" action="<?php enquote_string(url('')) ?>">
    <label>Title</label>
        <input type="text" class="span8" name="title" value="<?php echo $title ?>">
    <br />
    <input type="hidden" name="page_next" value="edit_end">
    <button type="submit" class="btn btn-danger">Submit</button>
</form>
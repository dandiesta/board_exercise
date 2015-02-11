<center><h3>Change password</h3></center>

<?php if ($users->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>

        <?php if ($users->validation_errors['password']['password_change']): ?>
            <div>
                <em>Old password</em> did not match
            </div>
        <?php endif ?>

        <?php if ($users->validation_errors['password']['confirmation']): ?>
            <div>
                <em>New password</em> did not match
            </div>
        <?php endif ?>

        <?php if ($users->validation_errors['password']['length']): ?>
            <div>
                <em>New password</em> must be between
                <?php enquote_string($users->validation['password']['length'][1]) ?> and
                <?php enquote_string($users->validation['password']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>    
    </div>
<?php endif ?>

<form class="span8 offset2 well" method="post" action="<?php enquote_string(url('')) ?>">
    <input type="password" class="span8" name="old_password" placeholder="Enter old password">
    <input type="password" class="span8" name="password" placeholder="Enter new password">
    <input type="password" class="span8" name="confirm_password" placeholder="Confirm new password">
    <br />
    <input type="hidden" name="page_next" value="edit_success">
    <button type="submit" class="btn btn-danger">Submit</button>
</form>

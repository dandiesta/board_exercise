<h2>My Profile</h2>

<?php if ($user->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Registration failed!</h4>

        <?php if ($user->validation_errors['firstname']['length']): ?>
            <div>
                <em>First name</em> must be between
                <?php enquote_string($user->validation['firstname']['length'][1]) ?> and
                <?php enquote_string($user->validation['firstname']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['firstname']['confirmation']): ?>
            <div>
                <em>First name</em> should contain letters only.
            </div>
        <?php endif ?>
        
        <?php if ($user->validation_errors['lastname']['length']): ?>
            <div>
                <em>Last name</em> must be between
                <?php enquote_string($user->validation['lastname']['length'][1]) ?> and
                <?php enquote_string($user->validation['lastname']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['lastname']['confirmation']): ?>
            <div>
                <em>Last name</em> should contain letters only.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>
<form class="span6 well shadow" method="POST">
    <label>First name</label>
    <input class="span6" type="text" value="<?php echo $firstname ?>" name="firstname" required>
    <label>Last name</label>
    <input class="span6" type="text" value="<?php echo $lastname ?>" name="lastname" required>
    <label>Username</label>
    <input class="span6" type="text" value="<?php echo $username ?>" name="username" disabled   >
    <label>Email</label>
    <input class="span6" type="email" value="<?php echo $email ?>" name="email" disabled>
    <label>Member since</label>
    <input class="span6" type="text" value="<?php echo $member_since ?> ago" name="member_since" disabled>
    <br/>
     <input type="hidden" name="page_next" value="success_update">
    <button type="submit" class="btn btn-danger">Save</button>
    <a href="<?php enquote_string(url('user/change_password'))?>" class="btn btn-danger">Change Password</a>
</form>

<form class="offset1 span3 well shadow">
    <center><p style="font-size:18px; color: #800000;"><strong>HISTORY</strong></p></center>
    <?php if (empty($thread_count)): ?>
        You have not posted any threads.
    <?php elseif ($thread_count == 1): ?>
        You have posted 1 thread.
    <?php else: ?>
        You have posted <?php enquote_string($thread_count)?> threads.
    <?php endif ?>

    <br />

    <?php if (empty($comment_count)): ?>
        You have not commented on any threads.
    <?php elseif ($comment_count == 1): ?>
        You have commented once.
    <?php else: ?>
        You have commented <?php enquote_string($comment_count)?> times.
    <?php endif ?>

    <br />

    <?php if (empty($like_count)): ?>
        You have not liked any comments.
    <?php elseif ($like_count == 1): ?>
        You have liked 1 comment.
    <?php else: ?>
        You have liked <?php enquote_string($like_count)?> comments.
    <?php endif ?>

    <br/>

    <?php if (empty($dislike_count)): ?>
        You have not disliked any comments.
    <?php elseif ($dislike_count == 1): ?>
        You have disliked 1 comment.
    <?php else: ?>
        You have disliked <?php enquote_string($dislike_count)?> comments.
    <?php endif ?>
</form>
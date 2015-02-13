<h2>My Profile</h2>

<form class="span6 well" method="POST" style="box-shadow: 10px 10px 10px #888888">
    <label>First name</label>
    <input class="span6" type="text" value="<?php echo $firstname ?>" name="firstname" required>
    <label>Last name</label>
    <input class="span6" type="text" value="<?php echo $lastname ?>" name="lastname" required>
    <label>Username</label>
    <input class="span6" type="text" value="<?php echo $username ?>" name="username" required>
    <label>Email</label>
    <input class="span6" type="email" value="<?php echo $email ?>" name="email" required>
    <label>Member since</label>
    <input class="span6" type="text" value="<?php echo $member_since ?> ago" name="member_since" disabled>
    <br/>
     <input type="hidden" name="page_next" value="success_update">
    <button type="submit" class="btn btn-danger">Save</button>
    <a href="<?php enquote_string(url('user/change_password'))?>" class="btn btn-danger">Change Password</a>
</form>

<form class="offset1 span3 well" style="box-shadow: 10px 10px 10px #888888">
    <center><p style="font-size:18px; color: #800000;"><strong>HISTORY</strong></p></center>
    <?php if (empty($thread_count)): ?>
        You have not posted any threads.
    <?php elseif ($thread_count == 1): ?>
        You have posted 1 thread.
    <?php else: ?>
        You have posted <?php enquote_string($thread_count)?> threads.
    <?php endif ?><br />
    <?php if (empty($comment_count)): ?>
        You have not commented on any threads.
    <?php elseif ($comment_count == 1): ?>
        You have commented once.
    <?php else: ?>
        You have commented <?php enquote_string($comment_count)?> times.
    <?php endif ?>
</form>
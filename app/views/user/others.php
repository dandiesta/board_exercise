<h2><?php enquote_string($user['username'])?>'s profile</h2>

<form class="span6 well shadow" method="POST">
    <label>First name</label>
    <input class="span6" type="text" value="<?php enquote_string($user['firstname']) ?>" name="firstname" disabled>
    <label>Last name</label>
    <input class="span6" type="text" value="<?php enquote_string($user['lastname']) ?>" name="lastname" disabled>
    <label>Username</label>
    <input class="span6" type="text" value="<?php enquote_string($user['username']) ?>" name="username" disabled>
    <label>Email</label>
    <input class="span6" type="email" value="<?php enquote_string($user['email']) ?>" name="email" disabled>
    <label>Member since</label>
    <input class="span6" type="text" value="<?php getElapsedTime($user['registration_date']) ?> ago" name="member_since" disabled>
    <br/>
    
</form>

<form class="offset1 span3 well shadow">
<center><p style="font-size:18px; color: #800000;"><strong>HISTORY</strong></p></center>
	<?php if (empty($thread_count)): ?>
		User has not posted any threads.
	<?php elseif ($thread_count == 1): ?>
	    User has posted 1 thread.
	<?php else: ?>
		User has posted <?php enquote_string($thread_count)?> threads.
	<?php endif ?><br />
	<?php if (empty($comment_count)): ?>
		User has not commented on any threads.
	<?php elseif ($comment_count == 1): ?>
	    User has commented once.
	<?php else: ?>
		User has commented <?php enquote_string($comment_count)?> times.
	<?php endif ?>
</form>
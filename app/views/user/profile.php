<h2>My Profile</h2>

<form class="span6 well" method="POST">
    <label>First name</label>
    <input class="span6" type="text" value="<?php echo $firstname ?>" name="firstname" required>
    <label>Last name</label>
    <input class="span6" type="text" value="<?php echo $lastname ?>" name="lastname" required>
    <label>Username</label>
    <input class="span6" type="text" value="<?php echo $username ?>" name="username" required>
    <label>Member since</label>
    <input class="span6" type="text" value="<?php echo $member_since ?> ago" name="member_since" disabled>
    <br/>
     <input type="hidden" name="page_next" value="success_update">
    <button type="submit" class="btn btn-danger">Save</button>
</form>

<form class="offset1 span3 well">
    Insert avatar here
</form>
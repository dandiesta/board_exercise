<?php if (!$user->login_verification): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Login failed!</h4>
        <?php if (($user->validation_errors['username']['banned_checking'])): ?>
            <div>
                Unauthorized user. You can no longer login.
            </div>
        <?php else: ?>
            <div>
                Incorrect username/password.
            </div>
        <?php endif ?>
    </div>
<?php endif; ?>


<form method="POST" action="" class="span5 offset3 well">
    <legend>Please Sign In</legend>
    <center>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-user"> </i></span>
        <input type="text" name="username" class="span4" value="<?php enquote_string(Param::get('username')) ?>" 
        placeholder="Username or Email">
    </div>

    <div class="input-prepend">
        <span class="add-on"><i class="icon-lock"> </i></span>
        <input type="password" name="password" class="span4" value="<?php enquote_string(Param::get('password')) ?>" 
        placeholder="Password">
    </div>

    </center>

    <input type="hidden" name="page_next" value="home">

    <div class="col-lg-12">
        <button type="submit" name="submit" class="btn btn-danger btn-block">Sign in</button>
    </div>
    
    <div>
        <center>Don't have an account yet? Click <a href="<?php enquote_string(url('user/registration')) ?>">here</a>.</center>
    </div>
</form>

<br>
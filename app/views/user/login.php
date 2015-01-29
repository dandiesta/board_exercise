<?php if (!$user->login_verification): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Login failed!</h4>
        <div>
            Incorrect username/password.
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="" class="span5 offset3 well">
    <legend>Please Sign In</legend>

    <div>
        <input type="text" name="username" class="span5" value="<?php enquote_string(Param::get('username')) ?>" 
        placeholder="Username">
    </div>

    <div class="col-lg-12">
        <input type="password" name="password" class="span5" value="<?php enquote_string(Param::get('password')) ?>" 
        placeholder="Password">
    </div>

    <br />

    <input type="hidden" name="page_next" value="home">

    <div class="col-lg-12">
        <button type="submit" name="submit" class="btn btn-danger btn-block">Sign in</button>
    </div>
    
    <div>
        <center>Don't have an account yet? Click <a href="<?php enquote_string(url('user/registration')) ?>">here</a>.</center>
    </div>
</form>
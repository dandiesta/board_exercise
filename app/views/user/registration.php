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

        <?php if ($user->validation_errors['password']['length']): ?>
            <div>
                <em>Password</em> must be between
                <?php enquote_string($user->validation['password']['length'][1]) ?> and
                <?php enquote_string($user->validation['password']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['username']['length']): ?>
            <div>
                <em>Username</em> must be between
                <?php enquote_string($user->validation['username']['length'][1]) ?> and
                <?php enquote_string($user->validation['username']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['password']['confirmation']): ?>
            <div>
                <em>Password</em> is not the same.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['username']['confirmation']): ?>
            <div>
                <em>Username</em> already existing. Please choose another.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['email']['confirmation']): ?>
            <div>
                <em>Email</em> already existing. Please choose another.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['username']['banned_checking']): ?>
            <div>
                <em>Username</em> cannot be used.
            </div>
        <?php endif ?>

        <?php if ($user->validation_errors['email']['banned_checking']): ?>
            <div>
                <em>Email</em> cannot be used.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>


<div class="container">
    <div class="row">
        <div class="span5 offset3 well shadow">
            <legend>user Here</legend>

            <form method="POST" action="">
                <div class="col-lg-12">
                    <label>First Name</label>
                    <input type="text" name="firstname" class="span5" value="<?php enquote_string(Param::get('firstname')) ?>">
                </div>

                <div class="col-lg-12">
                    <label>Last Name</label>
                    <input type="text" name="lastname" class="span5" value="<?php enquote_string(Param::get('lastname')) ?>">
                </div>

                <div class="col-lg-12">
                    <label>Username</label>
                    <input type="text" name="username" class="span5" value="<?php enquote_string(Param::get('username')) ?>">
                </div>

                <div class="col-lg-12">
                    <label>Email</label>
                    <input type="email" name="email" class="span5" value="<?php enquote_string(Param::get('email')) ?>">
                </div>

                <div class="col-lg-12">
                    <label>Password</label>
                    <input type="password" name="password" class="span5" 
                    	value="<?php enquote_string(Param::get('password')) ?>">
                </div>

                <div class="col-lg-12">
                    <label>Repeat Password</label>
                    <input type="password" name="confirm_password" class="span5" >
                </div>

                <br />

                <input type="hidden" name="page_next" value="success">

                <div class="col-lg-12">
                    <button type="submit" class="btn btn-danger btn-block">user</button>
                </div>

                <div>
                <center>Already have an account? Click <a href="<?php enquote_string(url('user/login')) ?>">here</a>.</center>
                </div>
            </form>
        </div>
    </div>
</div>
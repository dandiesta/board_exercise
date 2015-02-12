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


<form>
<?php
// echo '<h1>Rijndael 256-bit CBC Encryption Function</h1>';
// $data = 'danica';
// $encrypted_data = $user->mc_encrypt($data, ENCRYPTION_KEY);
// echo '<h2>Example #1: String Data</h2>';
//                     echo 'Data to be Encrypted: ' . $data . '<br/>';
//                     echo 'Encrypted Data: ' . $encrypted_data . '<br/>';
//                     echo 'Decrypted Data: ' . $user->mc_decrypt($encrypted_data, ENCRYPTION_KEY) . '</br><br />';

?>
</form>
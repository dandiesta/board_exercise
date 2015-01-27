<?php if ($register->hasError()): ?>
	<div class="alert alert-block">
		<h4 class="alert-heading">Registration failed!</h4>

		<?php if (!empty($register->validation_errors['fname']['length'])): ?>
			<div>
				<em>First name</em> must be between
				<?php eh($register->validation['fname']['length'][1]) ?> and
				<?php eh($register->validation['fname']['length'][2]) ?> characters in length.
			</div>
		<?php endif ?>
		
		<?php if (!empty($register->validation_errors['lname']['length'])): ?>
			<div>
				<em>Last name</em> must be between
				<?php eh($register->validation['lname']['length'][1]) ?> and
				<?php eh($register->validation['lname']['length'][2]) ?> characters in length.
			</div>
		<?php endif ?>

		<?php if (!empty($register->validation_errors['password']['length'])): ?>
			<div>
				<em>Password</em> must be between
				<?php eh($register->validation['password']['length'][1]) ?> and
				<?php eh($register->validation['password']['length'][2]) ?> characters in length.
			</div>
		<?php endif ?>

		<?php if (!empty($register->validation_errors['username']['length'])): ?>
			<div>
				<em>Username</em> must be between
				<?php eh($register->validation['username']['length'][1]) ?> and
				<?php eh($register->validation['username']['length'][2]) ?> characters in length.
			</div>
		<?php endif ?>

		<?php if (!empty($register->validation_errors['password']['confirmation'])): ?>
			<div>
				<em>Password</em> is not the same.
			</div>
		<?php endif ?>

		<?php if (!empty($register->validation_errors['username']['confirmation'])): ?>
			<div>
				<em>Username</em> already existing. Please choose another.
			</div>
		<?php endif ?>
	</div>
<?php endif ?>


<div class="container">
    <div class="row">
		<div class="span5 offset3 well">
			<legend>Register Here</legend>
          	
			<form method="POST" action="">
				<div class="col-lg-12">
					<label>First Name</label>
					<input type="text" name="fname" class="span5" value="<?php eh(Param::get('fname')) ?>">
				</div>
				
				<div class="col-lg-12">
					<label>Last Name</label>
					<input type="text" name="lname" class="span5" value="<?php eh(Param::get('lname')) ?>">
				</div>	

				<div class="col-lg-12">
					<label>Username</label>
					<input type="text" name="username" class="span5" value="<?php eh(Param::get('username')) ?>">
				</div>
						
				<div class="col-lg-12">
					<label>Password</label>
					<input type="password" name="password" class="span5" value="<?php eh(Param::get('password')) ?>">
				</div>
						
				<div class="col-lg-12">
					<label>Repeat Password</label>
					<input type="password" name="repeat_password" class="span5" >
				</div>
				<br />
				<input type="hidden" name="page_next" value="success">

				<div class="col-lg-12">				
					<button type="submit" class="btn btn-danger btn-block">Register</button>
				</div>

				<div>
				<center>Already have an account? Click <a href="<?php eh(url('user/login')) ?>">here</a>.</center>
				</div>
			</form>    
		</div>
	</div>
</div>
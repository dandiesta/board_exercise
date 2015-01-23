<h1>Registration</h1>
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
<form class="well" method="post" action="<?php eh(url('')) ?>">
	<div class="container">				
				<div class="col-lg-12">
					<div class="form-group col-lg-12">
						<label>First Name</label>
						<input type="text" name="fname" class="form-control" value="<?php eh(Param::get('fname')) ?>">
					</div>
					
					<div class="form-group col-lg-12">
						<label>Last Name</label>
						<input type="text" name="lname" class="form-control" value="<?php eh(Param::get('lname')) ?>">
					</div>	
					<div class="form-group col-lg-12">
						<label>Username</label>
						<input type="text" name="username" class="form-control" value="<?php eh(Param::get('username')) ?>">
					</div>
					
					<div class="form-group col-lg-12">
						<label>Password</label>
						<input type="password" name="password" class="form-control" value="<?php eh(Param::get('password')) ?>">
					</div>
					
					<div class="form-group col-lg-12">
						<label>Repeat Password</label>
						<input type="password" name="repeat_password" class="form-control" >
					</div>
									
							
				</div>
				<br />
				<input type="hidden" name="page_next" value="success">
				<div class="form-horizontal col-md-6">				
					<button type="submit" class="btn btn-primary">Register</button>
				</div>

	</div>
</form>

<nav class="navbar">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="<?php eh(url('thread/index')) ?>">All Threads</a></li>
        <li><a href="<?php eh(url('thread/index')) ?>">My Threads</a></li>
        <li><a href="<?php eh(url('thread/create')) ?>">Create New Thread</a></li>
        
      </ul>
      <ul class="nav navbar-nav pull-right">
        <li><a href="#">My Profile</a></li>
        <li><a href="<?php eh(url('user/login')) ?>">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<center><h3>Create a thread</h3></center>

<?php if ($thread->hasError() || $comment->hasError()): ?>
	<div class="alert alert-block">
		<h4 class="alert-heading">Validation error!</h4>

		<?php if (!empty($thread->validation_errors['title']['length'])): ?>
			<div>
				<em>Title</em> must be between
				<?php eh($thread->validation['title']['length'][1]) ?> and
				<?php eh($thread->validation['title']['length'][2]) ?> characters in length.
			</div>
		<?php endif ?>
	</div>
<?php endif ?>

<form class="span8 offset2 well" method="post" action="<?php eh(url('')) ?>">
	<label>Title</label>
	<input type="text" class="span8" name="title" value="<?php eh(Param::get('title')) ?>">
	<label>Comment</label>
	<textarea name="body" class="span8"><?php eh(Param::get('body')) ?></textarea>
	<br />
	<input type="hidden" name="page_next" value="create_end">
	<button type="submit" class="btn btn-danger	">Submit</button>
</form>

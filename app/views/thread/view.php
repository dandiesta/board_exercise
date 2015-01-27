<nav class="navbar">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="<?php enquote_string(url('thread/index')) ?>">All Threads</a></li>
        <li><a href="<?php enquote_string(url('thread/my_thread')) ?>">My Threads</a></li>
        <li><a href="<?php enquote_string(url('thread/create')) ?>">Create New Thread</a></li>
        
      </ul>
      <ul class="nav navbar-nav pull-right">
        <li><a href="#">My Profile</a></li>
        <li><a href="<?php enquote_string(url('user/login')) ?>">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<h1><?php enquote_string($thread->title) ?></h1>
<hr />
<?php foreach ($comments as $k => $v): ?>
	<div class="well">
		<div style="font-size:23px;"><?php echo readable_text($v->body) ?></div>
		<div><small><?php enquote_string($v->created) ?></small></div>
		<div class="meta">
			by: <?php enquote_string($v->username) ?> 
		</div>
	</div>
<?php endforeach ?>

<hr>

<form class="well" method="post" action="<?php enquote_string(url('thread/write')) ?>">
	<label>COMMENT</label>
	<textarea name="body" class="span11"><?php enquote_string(Param::get('body'))?></textarea>
	<br />
	<input type="hidden" name="thread_id" value="<?php enquote_string($thread->id) ?>">
	<input type="hidden" name="page_next" value="write_end">
	<button type="submit" class="btn btn-danger"> Submit</button>
</form>
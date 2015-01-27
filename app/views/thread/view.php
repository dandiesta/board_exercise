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
<h1><?php eh($thread->title) ?></h1>
<hr />
<?php foreach ($comments as $k => $v): ?>
	<div class="well">
		<div style="font-size:23px;"><?php echo readable_text($v->body) ?></div>
		<div><small><?php eh($v->created) ?></small></div>
		<div class="meta">
			by: <?php eh($v->username) ?> 
		</div>
	</div>
<?php endforeach ?>

<hr>

<form class="well" method="post" action="<?php eh(url('thread/write')) ?>">
	<label>COMMENT</label>
	<textarea name="body" class="span11"><?php eh(Param::get('body'))?></textarea>
	<br />
	<input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
	<input type="hidden" name="page_next" value="write_end">
	<button type="submit" class="btn btn-danger"> Submit</button>
</form>
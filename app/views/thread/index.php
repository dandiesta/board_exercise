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

<h2>All threads</h2>
<form method="post" action="<?php enquote_string(url('')) ?>">
	
	<ul class="nav">
	<?php foreach ($threads as $v): ?>
	<li class="span8 well">
		<a href="<?php enquote_string(url('thread/view', array('thread_id' => $v->id)))?>">
			<?php enquote_string($v->title); ?><br/>
      <small>Posted by: <?php enquote_string($v->username); ?></small>
		</a>	
	</li>
	<?php endforeach ?>
</ul>
</form>

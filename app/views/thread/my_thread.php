<nav class="navbar">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="<?php eh(url('thread/index')) ?>">All Threads</a></li>
        <li><a href="<?php eh(url('thread/my_thread')) ?>">My Threads</a></li>
        <li><a href="<?php eh(url('thread/create')) ?>">Create New Thread</a></li>
        
      </ul>
      <ul class="nav navbar-nav pull-right">
        <li><a href="#">My Profile</a></li>
        <li><a href="<?php eh(url('user/login')) ?>">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!--<a class="btn btn-large btn-primary" href="<?php eh(url('thread/create')) ?>">Create</a>-->
<form class="span8 well" method="post" action="<?php eh(url('')) ?>">
	<h2>My threads</h2>
	<ul>
	<?php foreach ($myThread as $v): ?>
	<li>
		<a href="<?php eh(url('thread/view', array('thread_id' => $v->id)))?>">
			<?php eh($v->title) ?>
		</a>	
	</li>
	<?php endforeach ?>
</ul>
</form>
<form class="span2 well">
put something here!!<br/><br/><br/><br/><br/><br/><br/><br/><br/>
</form>
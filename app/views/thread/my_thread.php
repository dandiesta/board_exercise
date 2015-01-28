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


	<h2>My threads</h2>
<form method="post" action="<?php enquote_string(url('')) ?>">

 
  <ul class="nav">
  <?php foreach ($myThread as $v): ?>
  <li class="span11 well">
    <a href="<?php enquote_string(url('comment/view', array('thread_id' => $v->id)))?>" class="span10">
      <?php enquote_string($v->title); ?><br/>
      <small>Posted by: <?php enquote_string($v->username); ?></small>
    </a>
    <button class="btn btn-danger pull-right" type="submit" >X</button>  
  </li>
  <?php endforeach ?>
</ul>
</form>

<?php if($pagination->current > 1): ?>
<a class="btn btn-danger" href='?page=<?php echo $pagination->prev ?>'>Previous</a>
<?php endif ?>

<?php foreach ($items as $item): ?>

<a href="<?php echo $item['id'] ?>"><?php echo $item['id'] ?></a>&nbsp;
<?php endforeach ?>

<?php if(!$pagination->is_last_page): ?>
<a class="btn btn-danger" href='?page=<?php echo $pagination->next ?>'>Next</a>
<?php endif ?>


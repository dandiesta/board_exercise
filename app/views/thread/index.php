<nav class="navbar">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="<?php enquote_string(url('thread/index?page=1')) ?>">All Threads</a></li>
        <li><a href="<?php enquote_string(url('thread/my_thread?page=1')) ?>">My Threads</a></li>
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
    <?php foreach ($display as $v): ?>
    	<li class="well">
    		<a href="<?php enquote_string(url('comment/view', array('thread_id' => $v['id'])))?>">
    			<?php enquote_string($v['title']); ?><br/>
          <small>Posted by: <?php enquote_string($v['username']); ?></small>
    		</a>	
    	</li>
  	<?php endforeach ?>
</ul>
</form>
</pre>
<!--pagination-->
<?php if($pagination->current > 1): ?>
<a class="btn btn-danger" href='?page=<?php echo $pagination->prev ?>'>Previous</a>
<?php endif ?>

<?php for ($i=1; $i <= $count_chunks; $i++): ?>
  <a class="btn btn-danger" href="?page=<?php echo $i ?>"><?php echo $i; ?></a>&nbsp;
<?php endfor ?>  


<?php if(!$pagination->is_last_page): ?>
<a class="btn btn-danger" href='?page=<?php echo $pagination->next ?>'>Next</a>
<?php endif ?>


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
<h2><?php echo "Welcome, ". $fname . "!";  ?></h2>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Forum</title>
    
    
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
      body {
        padding-top: 60px;
      }
    </style>
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="/">My Forum</a>
        </div>
      </div>
    </div>

    <div class="container">
      <?php if (isset($_SESSION['userid'])):?>
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
                <li><a href="<?php enquote_string(url('user/logout')) ?>">Logout</a></li>
              </ul>
            </div>
          </div>
        </nav>
      <?php endif ?>

      <?php echo $_content_ ?>
    </div>

    <script>
    console.log(<?php enquote_string(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
    </script>

  </body>
</html>

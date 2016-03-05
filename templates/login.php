<!DOCTYPE html>
<html>
  <head>
    <title>Bienal</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO8859-15" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>    
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.5.1.min.js"></script>
  </head>
  <body style="background:url(http://i.imgur.com/mZb1RQZ.jpg) center; background-size:cover;"> 
    <header>
    </header>
    <div class="wrapper2">
      <div class="login-container">
        <i class="fa fa-user"></i>
        <p class="login-title">.bienal</p>
        <?php 
        if (!empty($_SESSION['slim.flash'])){
          foreach($_SESSION['slim.flash'] as $key=>$flash){
            if(strpos($key,'fail-text')===false) {?>
              <div id="feedback" class="<?php echo $key?>">
                <p><?php echo $flash?></p>
              </div>
            <?php 
            } 
          }
        }
        ?>      
        <form method="post">
          <input placeholder="username" class="login-input" type="text" name="username">
          <input placeholder="password" class="login-input" type="password" name="password">
          <input class="login-button" type="submit"> 
        </form>
    </div> 
  </body>
</html>
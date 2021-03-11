<?php 

session_set_cookie_params(31556926,"/");//one year in seconds
session_start();
$errmsg = "";

if(!empty($_POST['send'])):
  if($_POST['username']==="admin" && $_POST['password']==="shoemaker72"):
    $_SESSION['loggedin'] = "admin";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="nate" && $_POST['password']==="L()gm3!n"):
    $_SESSION['loggedin'] = "nate";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="garrett" && $_POST['password']==="2020"):
    $_SESSION['loggedin'] = "garrett";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="user" && $_POST['password']==="corriher"):
    $_SESSION['loggedin'] = "user";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="charlesbob" && $_POST['password']==="attachments123"):
    $_SESSION['loggedin'] = "Charles";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="jerry" && $_POST['password']==="catfish1"):
    $_SESSION['loggedin'] = "user";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="Rick" && $_POST['password']==="tractor1"):
    $_SESSION['loggedin'] = "Rick";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="jeremy" && $_POST['password']==="Tractor-123"):
    $_SESSION['loggedin'] = "Jeremy";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="tam" && $_POST['password']==="tech"):
    $_SESSION['loggedin'] = "Tam";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="rickh" && $_POST['password']==="45*"):
    $_SESSION['loggedin'] = "Rick H";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="mtt" && $_POST['password']==="0420"):
    $_SESSION['loggedin'] = "Travis";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="phil" && $_POST['password']==="Tractor20"):
    $_SESSION['loggedin'] = "Phil";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="wayne" && $_POST['password']==="Tractor40"):
    $_SESSION['loggedin'] = "Wayne";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="admin" && $_POST['password']==="hpot"):
    $_SESSION['loggedin'] = "admin";
    header('Location: index.php'); exit;
  endif;
    header('Location: index.php'); exit;
  endif;
  $errmsg = "<p>Wrong! Try again, or Don't</p>";


?><!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Production Schedule</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style>
      body, html{ 
        background: #efefef; 
        padding: 0;
        margin: 0;
      }
      h2{ margin: 0; }
      #loginform{ 
        display:block;
        margin:300px auto;
        text-align:center;
        width: 300px;
        border: solid 1px #c0c0c0;
        padding: 20px;
        background: #d6d6d6;
      }
      #loginform input[type="text"], #loginform input[type="password"]{
        padding: 3px;
        margin: 5px;
      }
      #loginform input[type="submit"]{
        display: block;
        margin: 10px auto;
        padding: 2px 32px;
      }

    </style>
 </head>
<body>

<div id="loginform">
      <?php echo $errmsg; ?>
      <form method="post"><input type="hidden" name="send" value="1" />
        <input type="text" name="username" placeholder="User Name" value="" /><br />
        <input type="password" name="password" placeholder="Password" value="" /><br />
        <input type="submit" value="Login" />
      </form>
</div>
  </body>
</html>

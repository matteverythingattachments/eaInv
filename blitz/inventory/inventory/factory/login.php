<?php 

ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);
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
  if($_POST['username']==="user" && $_POST['password']==="corriher"):
    $_SESSION['loggedin'] = "user";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="charles" && $_POST['password']==="corriher123"):
    $_SESSION['loggedin'] = "charles";
    header('Location: index.php'); exit;
  endif;
  if($_POST['username']==="jerry" && $_POST['password']==="eafulbright"):
    $_SESSION['loggedin'] = "user";
    header('Location: index.php'); exit;
  endif;
  $errmsg = "<p>Wrong! Try again, or Don't</p>";
endif;

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

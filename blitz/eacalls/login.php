<?php
function redirect($url)
{
  if (!headers_sent()) {
    header('Location: ' . $url);
    exit();
  } else {
    echo '<script type="text/javascript">';
    echo 'window.location.href="' . $url . '";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
    echo '</noscript>';
    exit();
  }
}
?>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .content {
      max-width: 500px;
      margin: auto;
    }
  </style>
  <title>EA Call System - Login</title>
</head>

<body>
  <div class="content">
    <br>
    <p><img src="img/EALogoWB.png" width="212" height="48" alt="" /></p>
    <form method="post">
      <input type="hidden" name="send" value="1">
      <div class="form-group">
        <label for="username">Username</label>
        <input style="width: 50%" type="username" class="form-control" name="userName" placeholder="Enter Username">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input style="width: 50%" type="password" class="form-control" name="passWord" placeholder="Password">
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
  </div>
  <?php
  error_reporting(0);
  include('db.php');

  //username passed from the login menu
  $userName = $_POST['userName'];
  //password passed from the login menu
  $passWord = $_POST['passWord'];

  //username and password from the database
  $auth_query = mysqli_query($mysqli, "SELECT * FROM users WHERE name = '$userName'");

  if (mysqli_num_rows($auth_query) != 1) die('');
  //convert the raw database query to readable text
  $userData = mysqli_fetch_array($auth_query);

  // 31556926 - original number - one year in seconds
  if ($userData['pass'] != $passWord) {
    die('Your Password is incorrect.');
  } else {
    // calcs secs * mins * hours * year * day(s)
    $yearTimer = 60 * 60 * 24 * 365 * 1;
    session_set_cookie_params($yearTimer, "/");
    session_start();
    $_SESSION['userName'] =  $userName;
    $_SESSION['role'] =  $userData['role'];
    redirect('index.php');
  }
  ?>
</body>
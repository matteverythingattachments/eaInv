<?php
session_start();
include('../scripts/authent.php');
include('scripts/access.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Admin Menu</title>
</head>

<body>
<form name="form1" method="post" action="add_user.php">
  <label for="username"></label>
  <label for="password"></label>
  <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th scope="row">New Username</th>
      <td><input type="text" name="username" id="username"></td>
    </tr>
    <tr>
      <th scope="row">Password</th>
      <td><input type="text" name="password" id="password"></td>
    </tr>
    <tr>
      <th colspan="2" scope="row"><input type="submit" name="submit" id="submit" value="Submit"></th>
    </tr>
  </table>
</form>
</body>
</html>
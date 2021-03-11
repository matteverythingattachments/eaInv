<?php
if(isset($_SESSION['user']))	{
	header("location:home3.php");
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Login</title>
</head>

<body>
<form name="form1" method="post" action="scripts/login.php">
  <label for="username"></label>
  <label for="password"></label>
  <table border="0" cellspacing="0" cellpadding="5">
    <tr>
      <th scope="row">Username:</th>
      <td><input type="text" name="username" id="username"></td>
    </tr>
    <tr>
      <th scope="row">Password:</th>
      <td><input type="password" name="password" id="password"></td>
    </tr>
    <tr>
      <th colspan="2" scope="row"><input type="submit" name="button" id="button" value="Submit" ></th>
    </tr>
  </table> 
</form>
</body>
</html>
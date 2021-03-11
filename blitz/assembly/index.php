<?php
session_start();
if(isset($_SESSION['userName']))	{
	header('Location:menu.php');
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>ETA Factory Inventory Control</title>
</head>

<body>
<form action="admin/login.php" method="post">
<table>
	<tr>
    	<td>Username:</td>
        <td><input name="userName"></td>
    </tr>
    <tr>
    	<td>Password:</td>
        <td><input name="passWord" type="password"></td>
    </tr>
    <tr>
    	<td colspan="2"><button type="submit">Login</button></td>
</table>
</form>
</body>
</html>
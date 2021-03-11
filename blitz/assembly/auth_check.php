<?php
$web_address = 'http://localhost/assemblyLocal/admin/login.php';

if(!isset($_SESSION['userName']))	{
	die('You Must Be Logged In to access this site.<a href="'.$web_address.'">Login</a>');
}
$timeCheck = time() - 3600;

if($_SESSION['lastActivity'] < $timeCheck)	{
	session_destroy();
	header('Location:'.$web_address);
}
else	{
	$_SESSION['lastActivity'] = time();
}

?>
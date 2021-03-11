<?php
$web_address = 'http://10.10.1.106/assembly';

if(!isset($_SESSION['userName']))	{
	die('You Must Be Logged In to access this site.<a href="'.$web_address.'">Login</a>');
}
$timeCheck = time() - 28800;

if($_SESSION['lastActivity'] < $timeCheck)	{
	//session_destroy();
	//header('Location:'.$web_address);
}
else	{
	$_SESSION['lastActivity'] = time() + 28800;
}
?>
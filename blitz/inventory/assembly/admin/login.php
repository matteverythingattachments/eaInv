<?php
error_reporting(0);
include('scripts/access.php');

//username passed from the login menu
$userName = $_POST['userName'];
//password passed from the login menu
$passWord = $_POST['passWord'];

//query to pull matching username and password from the database
$auth_query = mysqli_query($mysqli, "SELECT * FROM users WHERE UserName = '$userName'");

if(mysqli_num_rows($auth_query) != 1) die('Your Username and Password Do Not Match 1');
//convert the raw database query to readable text
$userData = mysqli_fetch_array($auth_query);

if($userData['PassWord'] != $passWord)	die('Your Username and Password Do Not Match 2');
else	{
session_start();
$_SESSION['userName'] = $userData['UserName'];
$_SESSION['ROLE'] = $userData['ROLE'];
$_SESSION['lastActivity'] = time();

header('Location:../menu.php');
}
?>

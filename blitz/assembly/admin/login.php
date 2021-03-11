<?php
session_start();
//error_reporting(0);
include('scripts/access.php');

//username passed from the login menu
$userName = $_POST['userName'];
//password passed from the login menu
$passWord = $_POST['passWord'];

//query to pull matching username and password from the database
$auth_query = mysqli_query($mysqli, "SELECT * FROM users WHERE UserName = '$userName'");

if(mysqli_num_rows($auth_query) != 1) die('Your Username and/or Password Do Not Match.');
//convert the raw database query to readable text
$userData = mysqli_fetch_array($auth_query);

if($userData['PassWord'] != $passWord)	die('Your Password is incorrect.');

if($userData['PassWord'] == $passWord)
{
$_SESSION['userName'] = $userData['UserName'];
$_SESSION["role"] = $userData['ROLE'];
$_SESSION["lastActivity"] = time();
//session_set_cookie_params(31556926,"/");//one year in seconds
header('Location:../menu.php');
}

?>

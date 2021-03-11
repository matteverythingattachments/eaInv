<?php
session_start();
include('../scripts/authent.php');
include('scripts/access.php');
$new_user = $_POST['username'];
$new_pass = md5(md5($_POST['password']));
mysql_query("INSERT INTO USERS VALUES(
'$new_user','$new_pass')");
header('Location:.');
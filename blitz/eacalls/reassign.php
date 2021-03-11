<?php
//session_start();
//error_reporting(0);
include('db.php');
include('challenge.php');

$id = $_GET['id'];
$sale = $_GET['sYes'];
$reassign = $_GET['assigned'];

mysqli_query($mysqli, "UPDATE eainternalcalls SET assigned='$reassign' where ID=$id");

header("Location:index.php");
?>


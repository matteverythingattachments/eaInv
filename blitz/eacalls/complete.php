<?php
//session_start();
//error_reporting(0);
include('db.php');
include('challenge.php');

$id = $_GET['id'];
$sale = $_GET['sYes'];
$enteredby = $_SESSION['userName'];
$tcomplete = date("Y-m-d H:i:s");

mysqli_query($mysqli, "UPDATE eainternalcalls SET FollowupBy='$enteredby',complete=1, sale=$sale, completeTime='$tcomplete' where ID=$id");

header("Location:index.php");
?>


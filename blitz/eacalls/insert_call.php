<?php
error_reporting(0);
session_start();
include('db.php');
include('challenge.php');
$name = $mysqli -> real_escape_string($_GET['name']);
$email = $mysqli -> real_escape_string($_GET['email']);
$phone = $mysqli -> real_escape_string($_GET['phone']);
$callReason = $mysqli -> real_escape_string($_GET['rCall']);
$product = $mysqli -> real_escape_string($_GET['product']);
$inherit = $mysqli -> real_escape_string($_GET['inherit']);

if(isset($_GET['part']) && 
   $_GET['part'] == '1') 
{
    $part = 1;
}
else
{
    $part = 0;
}	
$tractor = $mysqli -> real_escape_string($_GET['tractor']);
$dis = " ";
$time=date("Y-m-d H:i:s");
$enteredby = $_SESSION['userName'];

mysqli_query($mysqli, "INSERT INTO eainternalcalls (Name,Email,Phone,callReason,Product,Part,Tractor,Disposition,Timestamp, Enteredby, assigned) VALUES('$name','$email','$phone','$callReason','$product',$part,'$tractor','$dis','$time','$enteredby','$inherit')") or die(mysqli_error($mysqli));

header("Location:index.php");
?>


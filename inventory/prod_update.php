<?php 
error_reporting(0);
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$id = $_GET['ID'];
$Product = $_POST['Product'];
$Black = $_POST['Black'];
$Green = $_POST['Green'];
$Orange = $_POST['Orange'];
$Yellow = $_POST['Yellow'];
$Primer = $_POST['Primer'];
$Price = $_POST['Price'];

mysqli_query($conn,"UPDATE EA_PRODS SET Product='$Product',Black='$Black',Green='$Green',Orange='$Orange',
Yellow=$Yellow,Primer='$Primer',Price='$Price' WHERE ID = '$id'") or die(mysqli_error());

header("Location:home3.php#$_POST[cat_id]");
?>
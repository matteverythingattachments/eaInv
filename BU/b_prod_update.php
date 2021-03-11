<?php 
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$id = $_GET['ID'];
$Product = $_POST['Product'];
$On_hand = $_POST['On_Hand'];
$Ordered = $_POST['Ordered'];
$Est_Delivery = $_POST['Est_Delivery'];
$Price = $_POST['Price'];
$low_limit = $_POST['Low_Limit'];

mysqli_query($conn,"UPDATE BRADCO_PRODS SET Product = '$Product' WHERE ID = '$id'") or die(mysqli_error());
mysqli_query($conn,"UPDATE BRADCO_PRODS SET On_hand = '$On_hand' WHERE ID = '$id'") or die(mysqli_error());
mysqli_query($conn,"UPDATE BRADCO_PRODS SET Ordered = '$Ordered' WHERE ID = '$id'") or die(mysqli_error());
mysqli_query($conn,"UPDATE BRADCO_PRODS SET Est_Delivery = '$Est_Delivery' WHERE ID = '$id'") or die(mysqli_error());
mysqli_query($conn,"UPDATE BRADCO_PRODS SET Price = '$Price' WHERE ID = '$id'") or die(mysqli_error());
mysqli_query($conn,"UPDATE BRADCO_PRODS SET Low_Limit = '$low_limit' WHERE ID = '$id'") or die(mysqli_error());

header("Location:paladin.php");
?>
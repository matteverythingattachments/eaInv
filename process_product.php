<?php
//error_reporting(0);
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$Prod_Name = $_POST['Prod_Name'];
$Black = $_POST['Black'];
$Green = $_POST['Green'];
$Orange = $_POST['Orange'];
$Primer = $_POST['Primer'];
$Yellow = $_POST['Yellow'];
$Price = $_POST['Price'];
$Category = $_POST['Category'];

mysqli_query($conn, "INSERT INTO ea_prods (Cat_ID,Product,Black,Green,Orange,Yellow,Primer,Price)  VALUES('$Category','$Prod_Name','$Black','$Green','$Orange','$Yellow','$Primer','$Price')") or die(mysqli_error($conn));

header('Location:home3.php');
?>
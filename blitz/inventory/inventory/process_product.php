<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$Prod_Name = $_POST['Prod_Name'];
$On_Hand = $_POST['On_Hand'];
$Ordered = $_POST['Ordered'];
$Est_Del = $_POST['Est_Del'];
$Price = $_POST['Price'];
$Low_Lim = $_POST['Low_Lim'];
$Category = $_POST['Category'];

mysqli_query($conn, "INSERT INTO EA_PRODS VALUES(
'','$Category','$Prod_Name','$On_Hand','$Ordered','$Est_Del','$Price','$Low_Lim')") or die(mysqli_error());

header('Location:add_product.php');
?>
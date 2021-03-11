<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$category = $_POST['category'];
mysqli_query($conn, "INSERT INTO CATEGORIES (CATEGORY) VALUES('$category')");
header("Location:home3.php");
?>
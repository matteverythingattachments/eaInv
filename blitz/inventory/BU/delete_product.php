<?php
session_start();
error_reporting(E_ALL);
include('scripts/authent.php');
include('admin/scripts/access.php');

$prod = $_GET['ID'];
$retval = mysqli_query($conn, "DELETE from EA_PRODS WHERE ID = '$prod'");

if(! $retval ) {
      die('Could not delete product: ' . mysqli_error());
}
   echo "Deleted product successfully\n";
   mysqli_close($conn);
   header("Location:home3.php");
?>
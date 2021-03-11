<?php
include('admin/scripts/access.php');
$prod = $_POST['product'];
$qty = $_POST['qty'];
$current_qty = mysqli_fetch_array(mysqli_query($conn,"SELECT On_Hand FROM EA_PRODS WHERE ID = '$prod'"));
$new_qty = $current_qty['On_Hand'] - $qty;
mysqli_query($conn, "UPDATE EA_PRODS SET On_Hand = $new_qty WHERE ID = '$prod'") or die(mysqli_error());
?>
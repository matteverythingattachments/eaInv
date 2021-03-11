<?php
include('admin/scripts/access.php');
$prod = $_GET['ID'];
$query = mysqli_query($conn,"SELECT On_Hand FROM EA_PRODS WHERE ID = $prod");
$curr_num = mysqli_fetch_assoc($query);
$new_num = $curr_num['On_Hand'] - 1;
mysqli_query($conn, "UPDATE EA_PRODS SET On_Hand = $new_num WHERE ID = '$prod'");
header("Location:home3.php");?>
<?php
//error_reporting(0);
session_set_cookie_params(31556926,"/");//one year in seconds
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
//error_reporting(0);
$id = $mysqli -> real_escape_string($_GET['ID']);
$qty = $mysqli -> real_escape_string($_GET['qty']); 
$buildQtyQuery = mysqli_query($mysqli, "select BuildQty from assemblies where ID=$id");
while($buildQuery = mysqli_fetch_array($buildQtyQuery))
{
    $updatedBuildQty = $buildQuery['BuildQty'] - $qty;
}


mysqli_query($mysqli, "UPDATE assemblies set ActualQty=0, BuildQty=$updatedBuildQty where ID=$id") or die(mysqli_error($mysqli));

?>

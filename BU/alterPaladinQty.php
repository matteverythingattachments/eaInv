<?php
session_start();
error_reporting(E_ALL);
include('scripts/authent.php');
include('admin/scripts/access.php');

$fullID = $_GET['identity'];//OHI233
$len = strlen($fullID);
$color = substr($fullID,0,1);
$direction = substr($fullID,2,1);
$prodID = substr($fullID,3,$len);
$existQty = $_GET['current'];//0
$enteredQty = $_GET['entered'];//1
//echo $fullID.":".$color.":".$direction.":".$prodID.":".$existQty.":".$enteredQty;
if ($direction == "I")
{
    $finalQty = $existQty + $enteredQty;
}
else
{
    $finalQty = $existQty - $enteredQty;
}
echo "UPDATE BRADCO_PRODS SET On_Hand=".$finalQty." WHERE ID = $prodID";

$retval = mysqli_query($conn, "UPDATE BRADCO_PRODS SET On_Hand=".$finalQty." WHERE ID = $prodID");

if(!$retval ) {
      die('Damn it Nate, something fucked the hell up.' . mysqli_error($conn));
}
header("Location:paladin.php");
?>
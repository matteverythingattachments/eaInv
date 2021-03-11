<?php
session_start();
error_reporting(E_ALL);
include('scripts/authent.php');
include('admin/scripts/access.php');

$fullID = $_GET['identity'];//YD233
$len = strlen($fullID);
$color = substr($fullID,0,1);
$direction = substr($fullID,1,1);
$prodID = substr($fullID,2,$len);
$existQty = $_GET['current'];//0
$enteredQty = $_GET['entered'];//1
echo $fullID.":".$color.":".$direction.":".$prodID.":".$existQty.":".$enteredQty;
switch ($color) {
    case "Y":
        $color = "Yellow";
        break;
    case "B":
        $color = "Black";
        break;
    case "G":
        $color = "Green";
        break;
    case "O":
        $color = "Orange";
        break;
    case "P":
        $color = "Primer";
        break; 
}
if ($direction == "I")
{
    $finalQty = $existQty + $enteredQty;
}
else
{
    $finalQty = $existQty - $enteredQty;
}
echo "UPDATE EA_PRODS SET ".$color."=".$finalQty." WHERE ID = $prodID";

$retval = mysqli_query($conn, "UPDATE EA_PRODS SET ".$color."=".$finalQty." WHERE ID = $prodID");

if(! $retval ) {
      die('Damn it Nate, something fucked the hell up.' . mysqli_error());
}
header("Location:home3.php");
?>
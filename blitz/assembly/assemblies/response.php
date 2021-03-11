<?php
//error_reporting(0);
session_set_cookie_params(31556926,"/");//one year in seconds
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
//error_reporting(0);
$id = $mysqli -> real_escape_string($_GET['ID']);
//$qty = $mysqli -> real_escape_string($_GET['qty']); 
//$qtyFactor = $mysqli -> real_escape_string($_GET['qtyFactor']);

		$buildListQuery = mysqli_query($mysqli, "SELECT BuildQty FROM assemblies where ID=".$id) or die(mysqli_error($mysqli));
		while($buildList = mysqli_fetch_array($buildListQuery))	{
			echo $buildList['BuildQty'];
		}
        //echo $buildListQuery;

?>

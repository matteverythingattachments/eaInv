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
$qtyFactor = $mysqli -> real_escape_string($_GET['qtyFactor']);


		$multiplier = $qtyFactor;
		//pull list of all parts in assembly for table update.
		$buildListQuery = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE assemblyID = $id") or die(mysqli_error($mysqli));
		//loop through the update process. 
		while($buildList = mysqli_fetch_array($buildListQuery))	{
			mysqli_query($mysqli, "UPDATE parts SET Qty = Qty-($multiplier * $buildList[Qty]) where ID = '$buildList[partID]'");
		}
			mysqli_query($mysqli, "UPDATE assemblies SET Qty = Qty + $multiplier WHERE ID = $id");
            if ($qty < 0) { $qty = 0; }
            mysqli_query($mysqli, "UPDATE assemblies set BuildQty=$qty where ID=$id");

			//getBlaster();

?>

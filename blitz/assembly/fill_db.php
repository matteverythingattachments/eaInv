<?php
session_start();
include('auth_check.php');
include('access.php');
include('components.php');

$x=1;
$part = array(1,2,3);
$p1 = array('BoxBlade', 'ScrapeBlade', 'Grapple', 'Subsoiler', 'Plow');
$p2 = array("MnTube","SidePlate","topLinkBar","MnBar","LgGusset","Gusset","innerBrace","HitchArm","Pin");
$p3 = array(48,60,72,84,96);
$thickness = array(.060, .1875, .25, .3125, .375, .500, .625, .750, 1.0);
$qty = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
$minQty = array(3,5,10);
$maxQty = array(10,20,30,40,50);
$reorderValue = array(3,5,10,20);
$location = array("SF","FO","MR","BS","JA");

while($x <20)	{
	shuffle($p1);
	shuffle($p2);
	shuffle($p3);
	shuffle($thickness);
	shuffle($qty);
	shuffle($minQty);
	shuffle($maxQty);
	shuffle($reorderValue);
	shuffle($location);
	$partNum = $p1[0].'-'.$p2[0].'-'.$p3[0];
	$partDesc = $p1[0].' '.$p2[0].' '.$p3[0];
	$partThickness = $thickness[0];
	$partQty = $qty[0];
	$partMinQty = $minQty[0];
	$partMaxQty = $maxQty[0];
	$partReorderQty = $reorderValue[0];
	$partLocation = $location[0];
	mysqli_query($mysqli,"INSERT INTO subassemblies VALUES(
	'','$partNum','$partDesc','$partThickness','$partQty','$partMinQty','$partMaxQty','$partReorderQty','$partLocation')");
	$x++;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
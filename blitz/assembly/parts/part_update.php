<?php
session_start();
error_reporting(0);
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
include('../admin/resize-class.php');

if(!isset($_POST['ID']) or !isset($_POST['Name'])or !isset($_POST['Description'])or !isset($_POST['Thickness'])or !isset($_POST['Qty'])or !isset($_POST['MinQty'])or !isset($_POST['MaxQty'])or !isset($_POST['ReorderValue']) or !isset($_POST['Location']))	{
	die("Internal Error, could not process");
}
$istube = 'n';
$ispurchased = 'n';

if($_POST['is_tube'] == 'yes') $istube = 'y';
if($_POST['is_purchased'] == 'yes') $ispurchased = 'y';

if($_POST['Name'] == '') die('Part Name Required');
else	{
	mysqli_query($mysqli, "UPDATE parts SET Name='$_POST[Name]', Description='$_POST[Description]', Thickness='$_POST[Thickness]', Qty='$_POST[Qty]', MinQty='$_POST[MinQty]', MaxQty='$_POST[MaxQty]', ReorderValue='$_POST[ReorderValue]', Location='$_POST[Location]', is_tube='$istube', purchased_part='$ispurchased',external_part_num='$_POST[External_Part_Num]',is_bolt='$_POST[is_bolt]' WHERE ID = '$_POST[ID]'") or die(mysqli_error($mysqli));
	$newPartName = $_POST['ID'];
	@move_uploaded_file( $_FILES['part_pic']['tmp_name'], '../img/tmp/'.$newPartName.'.jpg');
	
	$resizeObj = new resize('../img/tmp/'.$newPartName.'.jpg');
	$resizeObj -> resizeImage(800, 450, 'auto');
	$resizeObj -> saveImage('../img/parts/'.$newPartName.'.jpg', 100);
    
    $resizeObj = new resize('../img/tmp/'.$newPartName.'.jpg');
	$resizeObj -> resizeImage(200, 127, 'auto');
	$resizeObj -> saveImage('../img/parts/thumbs/'.$newPartName.'Th.jpg', 100);
    
	unlink('../img/tmp/'.$newPartName.'.jpg');
}
$sendTo = $_POST['referrer'];
header("Location:$sendTo");
?>
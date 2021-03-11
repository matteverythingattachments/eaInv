<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');

$partid = $_GET['partid'];
$assemblyid = $_GET['assemblyid'];
$newposition = $_GET['position'];

$idQuery = mysqli_query($mysqli, "SELECT ID FROM assembly_build WHERE partID = '$partid' AND assemblyID = '$assemblyid'");

$id = mysqli_fetch_array($idQuery);
$lineID = $id['ID'];

mysqli_query($mysqli, "UPDATE assembly_build SET position = '$newposition' WHERE ID = '$lineID'");
//header('Location:build_assembly.php?ID='.$assemblyid);?>

<html> <body onload="window.close()"> </body> </html>
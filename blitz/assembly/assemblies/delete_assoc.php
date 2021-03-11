<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
$partID = $_GET['partid'];
$assemblyID = $_GET['assemblyid'];

mysqli_query($mysqli, "DELETE FROM assembly_build WHERE partID = $partID && assemblyID = $assemblyID") or die(mysqli_error($mysqli));

header("Location:build_assembly.php?ID=$assemblyID");
//	<td><a href="delete_assoc.php?partid='.$buildSheet['ID'].'&assemblyid='.$_GET['ID'].'">Delete</a></td>
?>
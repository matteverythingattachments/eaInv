<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');

$position = $_POST['position'];
$associd = $_POST['associd'];
$assemblyID = $_POST['assemblyID'];
$Qty = $_POST['Qty'];


mysqli_query($mysqli, "UPDATE assembly_build SET position = $position WHERE ID = $associd ") or die(mysqli_error($mysqli));
mysqli_query($mysqli, "UPDATE assembly_build SET Qty = $Qty WHERE ID = $associd");

header("Location: build_assembly.php?ID=$assemblyID");
?>
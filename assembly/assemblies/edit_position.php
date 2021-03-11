<?php

session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
$partID = $_GET['partid'];
$assemblyid = $_GET['assemblyid'];

$partInfo = mysqli_query($mysqli, "SELECT assembly_build.ID as ABID, parts.ID as partid, parts.Name as parts_name, parts.Description, assembly_build.Qty, assembly_build.position, assemblies.Name as ass_name, assemblies.Description FROM parts JOIN assembly_build ON parts.ID = assembly_build.partID JOIN assemblies on assembly_build.assemblyID = assemblies.ID WHERE parts.ID = $partID AND assemblies.ID = $assemblyid") or die(mysqli_error($mysqli));
while($info = mysqli_fetch_array($partInfo)) {
	echo '<table><tr>';
	echo '<td><img style="max-height:600px; width:auto;" src="../img/assemblies/'.$info['ass_name'].'.jpg">
	<h2>'.$info['parts_name'].'</h2></td>';
	echo '<td><img style="max-width:500px; height:auto;" src="../img/parts/'.$info['partid'].'.jpg">
	<h2>'.$info['parts_name'].'</h2></td></tr></table>';
	echo '<form action="update_assembly_build.php" method="post"><input type="hidden" value="'.$info['ABID'].'" name="associd">';
	echo 	'Position: <input type="text" name="position" value="'.$info['position'].'"><br>';
	echo 	'Quantity: <input type="text" name="Qty" value="'.$info['Qty'].'"><br>';
	echo 	'<input type="hidden" value="'.$assemblyid.'" name="assemblyID">';
	echo 	'<button type="submit">Save</button>';
	
}
//mysqli_query($mysqli, "DELETE FROM assembly_build WHERE partID = $partID AND assemblyID = $assemblyid") or die(mysqli_error($mysqli));
?>
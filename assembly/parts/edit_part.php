<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
if(isset($_SERVER['REFERER']))	{
	$referer = $_SERVER['REFERER'];
}
else	{
	$referer = $web_address.'/parts/edit_part.php?ID='.$_GET['ID'];
}
//query the database for the details of the specific part
$partQuery = mysqli_query($mysqli,"SELECT * FROM parts WHERE ID = '$_GET[ID]'");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Part Details</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
<meta http-equiv=”Pragma” content=”no-cache”>
<meta http-equiv=”Expires” content=”-1″>
<meta http-equiv=”CACHE-CONTROL” content=”NO-CACHE”>
</head>

<body>
<?php echo $nav_menu;
$partInfo = mysqli_fetch_array($partQuery);

?>
<table border="0">
	<tr>
    	<td valign="top">
<form action="part_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="ID" value="<?php echo $_GET['ID'];?>">
<input type="hidden" name="referrer" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
<table style="border-collapse:collapse;" border="1" cellpadding="5">
	<tr>
		<th>Name</th>
    	<td><input name="Name" value="<?php echo $partInfo['Name']; ?>"></td>
        <td rowspan="13"><?php echo '<img style="float:right;" src="../img/parts/'.$partInfo['ID'].'.jpg">'; ?></td>
    </tr>
	<tr>
		<th>Description</th>
    	<td><input name="Description" value="<?php echo $partInfo['Description']; ?>"></td>
    </tr>
	<tr>
		<th>Thickness</th>
    	<td><input name="Thickness" value="<?php echo $partInfo['Thickness']; ?>"></td>
    </tr>
	<tr>
		<th>Quantity</th>
    	<td><input name="Qty" value="<?php echo $partInfo['Qty']; ?>"></td>
    </tr>
	<tr>
		<th>Min Qty</th>
    	<td><input name="MinQty" value="<?php echo $partInfo['MinQty']; ?>"></td>
    </tr>
	<tr>
		<th>Max Qty</th>
    	<td><input name="MaxQty" value="<?php echo $partInfo['MaxQty']; ?>"></td>
    </tr>
	<tr>
		<th>Reorder Qty</th>
    	<td><input name="ReorderValue" value="<?php echo $partInfo['ReorderValue']; ?>"></td>
    </tr>
	<tr>
		<th>Location</th>
    	<td><input name="Location" value="<?php echo $partInfo['Location']; ?>"></td>
    </tr>
	<tr>
		<th>Is Tube?</th>
		<td><input name="is_tube" value="yes" type="checkbox" <?php if($partInfo['is_tube'] == 'y') echo 'checked';?>></td></tr>
<th>Is Purchased?</th>
		<td><input name="is_purchased" value="yes" type="checkbox" <?php if($partInfo['purchased_part'] == 'y') echo 'checked';?>></td></tr>
<th>Is Bolt?</th>
		<td><input name="is_bolt" value="y" type="checkbox" <?php if($partInfo['is_bolt'] == 'y') echo 'checked';?>></td></tr>
  <th>External Part Number</th>
    	<td><input name="External_Part_Num" value="<?php echo $partInfo['external_part_num']; ?>"></td>
  </tr>
    	<td colspan="2"><h3>
				Update Picture:</h3><input type="file" accept=".jpg" name="part_pic"></td>
    </tr>
    <tr>
    	<td colspan="2"><button type="submit">Update</button></td>
    </tr>
</table>
</form>
<h3>Assemblies Used:</h3>
<?php
$assemblyLookUp = mysqli_query($mysqli, "SELECT parts.Name AS partName, assemblies.ID as assID, assemblies.Name AS assName, assemblies.Description FROM parts JOIN assembly_build ON parts.ID = assembly_build.partID JOIN assemblies ON assembly_build.assemblyID = assemblies.ID WHERE parts.ID = $_GET[ID]") or die(mysqli_error($mysqli));
if(mysqli_num_rows($assemblyLookUp) > 0)	{
	$countDown = mysqli_num_rows($assemblyLookUp);
	echo '<ul>
	'
	;
}
while($assembliesUsed = mysqli_fetch_array($assemblyLookUp))	{
	echo '<li><a href="../assemblies/build_assembly.php?ID='.$assembliesUsed['assID'].'">'.$assembliesUsed['assName'].'</a></li>
	';
	$countDown--;
	if($countDown == 0)	{
		echo '</ul>
		';
	}
}
?>
</body>
</html>
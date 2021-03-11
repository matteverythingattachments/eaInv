<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

if(isset($_GET['Name'], $_GET['Description'], $_GET['Qty'], $_GET['MinQty'], $_GET['MaxQty'], $_GET['ReorderValue'], $_GET['Location']))	{
	$saTest = mysqli_query($mysqli, "SELECT * FROM subassemblies WHERE Name = '$_GET[Name]'") or die(mysqli_error($mysqli));
	if(mysqli_num_rows($saTest)!= 0)	{
		$warning = 'Duplicate part names are not allowed';
	}
	else	{
		mysqli_query($mysqli,"INSERT INTO subassemblies VALUES (
		'','$_GET[Name]','$_GET[Description]','$_GET[Qty]','$_GET[MinQty]','$_GET[MaxQty]','$_GET[ReorderValue]','$_GET[Location]')");
	}

}
$inventoryQuery = mysqli_query($mysqli,"SELECT ID, Name, Description, Qty, MinQty, MaxQty, ReorderValue, Location, round(MinQty/Qty, 3) AS Sev_In FROM subassemblies ORDER BY Qty, Sev_In");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Inventory Levels</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php echo $nav_menu;
if(isset($warning)) echo $warning;?>
<form enctype="multipart/form-data" method="get" action="index.php">
<table border="0" style="text-align:left;">
	<tr>
    	<th>Name</th>
        <td><input name="Name"></td>
    </tr>
	<tr>
    	<th>Description</th>
        <td><input name="Description"></td>
    </tr>
	<tr>
    	<th>Quantity</th>
        <td><input name="Qty"></td>
    </tr>
	<tr>
    	<th>Minimum Quantity</th>
        <td><input name="MinQty"></td>
    </tr>
	<tr>
    	<th>Maximum Quantity</th>
        <td><input name="MaxQty"></td>
    </tr>
	<tr>
    	<th>Reorder Value</th>
        <td><input name="ReorderValue"></td>
    </tr>
	<tr>
    	<th>Location</th>
        <td><input name="Location"></td>
    </tr>
	<tr>
    	<td></td>
        <td><button type="submit">Enter</button></td>
    </tr>
</table>
</form>
<table border="1" cellpadding="5" class="dataTable">
	<tr>
    	<th>Name</th>
    	<th>Description</th>
    	<th>Quantity</th>
    	<th>Minimum Quantity</th>
    	<th>Maximum Quantity</th>
        <th>Severity Index</th>
    	<th>Reorder Value</th>
    	<th>Location</th>
    </tr>
<?php while($invData = mysqli_fetch_array($inventoryQuery))	{
	if (!isset($invData['Sev_In']))	{
		$invData['Sev_In'] = 100;
	}
	
	if($invData['Sev_In'] < 0)	{
		$invData['Sev_In'] = 100;
	}
	
	if($invData['Sev_In'] > 1){
		$sevin = "severe";		
	}
	
	else	{
		$sevin = "normal";
	}
echo '	<tr class="'.$sevin.'">
	<td><a href="edit_subassembly.php?ID='.$invData['ID'].'">'.$invData['Name'].'</a></td>
	<td>'.$invData['Description'].'</td>
	<td>'.$invData['Qty'].'</td>
	<td>'.$invData['MinQty'].'</td>
	<td>'.$invData['MaxQty'].'</td>
	<td>'.$invData['Sev_In'].'</td>
	<td>'.$invData['ReorderValue'].'</td>
	<td>'.$invData['Location'].'</td>
</tr>		';
}?>
</table>
</body>
</html>
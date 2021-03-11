<?php
error_reporting(0);
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

if(isset($_GET['thickness']))	{
	$inventoryQuery = mysqli_query($mysqli,"SELECT ID, Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, Location, round(MinQty/Qty, 3) AS Sev_In FROM parts WHERE Thickness = $_GET[thickness] AND Qty < MinQty AND is_tube='n'
ORDER BY Name");
}
else	{
	$inventoryQuery = mysqli_query($mysqli,"SELECT ID, Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, Location, round(MinQty/Qty, 3) AS Sev_In FROM parts ORDER BY Qty, Sev_In");
}
$thicknessQuery = mysqli_query($mysqli,"SELECT Thickness FROM parts WHERE is_tube = 'n' GROUP BY Thickness ORDER BY Thickness");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Inventory Levels</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php echo $nav_menu;?>
<div id="sub_menu">
<?php 
$x = mysqli_num_rows($thicknessQuery);
while($thicknessMenu = mysqli_fetch_array($thicknessQuery))	{
	echo '<a href="inventory_levels.php?thickness='.$thicknessMenu['Thickness'].'">'.$thicknessMenu['Thickness'].'</a> ';
	$x--;
	if ($x >= 1) echo '| ';
};
		echo "<br>
		<a href=\"inventory_levels_by_qty.php?thickness=$_GET[thickness]\">View By Qty</a>";

?>
</div>
  <?php 
  include('csvimportto0.php');
  
  ?>
<table border="1" cellpadding="5" class="dataTable">
	<tr>
    	<th>Name</th>
    	<th>Description</th>
    	<th>Thickness</th>
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
	<td><a href="edit_part.php?ID='.$invData['ID'].'">'.$invData['Name'].'</a></td>
	<td>'.$invData['Description'].'</td>
	<td>'.$invData['Thickness'].'</td>
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
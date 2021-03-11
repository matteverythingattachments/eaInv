<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Search For Parts</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php echo $nav_menu;
echo '<form action="part_search.php" method="get">
<input name ="partName">
<button type="submit">Search</button>
</form>
</br>
';
if(isset($_GET['partName']))	{
	$x = 0;
	$string = explode('-',$_GET['partName']);
	$imploded = implode('%\' OR Name LIKE \'%',$string);
	$query = mysqli_query($mysqli,"SELECT ID, Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, Location FROM `parts` WHERE Name LIKE '%$imploded%'") or die(mysqli_error($mysqli));
if(mysqli_num_rows($query) >= 1)	{	
echo '<table border="1" cellpadding="5" class="dataTable">
		<tr>
			<th>Name</th>
			<th>Description</th>
			<th>Thickness</th>
			<th>Quantity</th>
			<th>Minimum Quantity</th>
			<th>Maximum Quantity</th>
			<th>Reorder Value</th>
			<th>Location</th>
		</tr>
			';
}

else	{
	echo '<h2>No Matches</h2>';
}
		while($rows = mysqli_fetch_array($query))	{
echo '<tr>
			<td><a href="edit_part.php?ID='.$rows['ID'].'">'.$rows['Name'].'</a></td>
			<td>'.$rows['Description'].'</td>
			<td>'.$rows['Thickness'].'</td>
			<td>'.$rows['Qty'].'</td>
			<td>'.$rows['MinQty'].'</td>
			<td>'.$rows['MaxQty'].'</td>
			<td>'.$rows['ReorderValue'].'</td>
			<td>'.$rows['Location'].'</td>
		</tr>
		';
			}
			echo '</table>';
}
?>
</body>
</html>
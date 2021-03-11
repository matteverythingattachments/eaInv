<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$assemblyID = time();
if(isset($_GET['assemblyName']))	{
	$preCheckQuery = mysqli_query($mysqli,"SELECT * FROM assemblies WHERE name = '$_GET[assemblyName]'");
	if(mysqli_num_rows($preCheckQuery) > 0)	{
		die('Assembly name is already in the database.');
	}
	
	else	{
		mysqli_query($mysqli, "INSERT INTO assemblies (Name, Description, Qty, MinQty, MaxQty, ReorderValue, Location) VALUES
		('$_GET[assemblyName]','$_GET[assemblyDescription]','$_GET[Qty]','$_GET[MinQty]','$_GET[MaxQty]','$_GET[ReorderValue]','$_GET[Location]')") or die(mysqli_error($mysqli));
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Assembly</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php echo $nav_menu;?>
<form action="add_assembly.php" method="get" enctype="multipart/form-data">
<table>
	<tr>
    	<td>Assembly Name</td>
        <td><input name="assemblyName"></td>
    </tr>
	<tr>
    	<td>Assembly Description</td>
        <td><input name="assemblyDescription"></td>
    </tr>
    <tr>
	<tr>
    	<td>Quantity</td>
        <td><input name="Qty"></td>
    </tr>
	<tr>
    	<td>Minimum Quantity</td>
        <td><input name="MinQty"></td>
    </tr>
 	<tr>
    	<td>Maximum Quantity</td>
        <td><input name="MaxQty"></td>
    </tr>
	<tr>
    	<td>Reorder Value</td>
        <td><input name="ReorderValue"></td>
    </tr>
	<tr>
    	<td>Location</td>
        <td><input name="Location"></td>
    </tr>
   	<td colspan="2"><button type="submit">Enter</button></td>
    </tr>
</form>
</body>
</html>
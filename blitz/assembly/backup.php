<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');


if(isset($_GET['assemblyID']))	{
	$assemblyInfoQuery = mysqli_query($mysqli, "SELECT ID, Name FROM assemblies WHERE ID = '$_GET[assemblyID]'");
		if(mysqli_num_rows($assemblyInfoQuery) != 1)	{
			die('Error - Assembly not found');
		}
}

if(isset($_GET['part_num']))	{		
	$partCheck = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE partID = $_GET[part_num] AND assemblyID = $_GET[assemblyID]");	
	if(mysqli_num_rows($partCheck) > 0)	{
		die('Part already assigned to this assembly');
	}
}

if(isset($_GET['task']))	{
	if($_GET['task'] == 'addPart')	{
	mysqli_query($mysqli,"INSERT INTO assembly_build VALUES(
	'','$_GET[part_num]','$_GET[assemblyID]','$_GET[qty]')");
	}
}

	$assemblyInfoQuery = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE ID = $_GET[assemblyID]");
	$assemblyData = mysqli_fetch_array($assemblyInfoQuery);
	$queryString = "SELECT Name, Description, Thickness, Location, assembly_build.Qty, parts.Qty AS 'onHand' FROM assembly_build JOIN parts ON assembly_build.partID = parts.ID WHERE assembly_build.assemblyID = $_GET[assemblyID]";
	$buildQuery = mysqli_query($mysqli, $queryString) or die(mysqli_error($mysqli));
	
	$partsQuery = mysqli_query($mysqli, "SELECT * FROM parts");		

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Assembly</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php echo $nav_menu;?>
<h1><?php echo $assemblyData['Name'];?></h1>
<p><img src="../img/<?php echo $assemblyData['Name']?>.png"></p>
<table border="0" cellpadding="5">
	<tr>
    	<td colspan="2">Build Subassembly </td>
    </tr>
    <tr>
    	<td>Quantity: <input name="buildQty"></td>
        <td><button type="submit">Build</button></td>
    </tr>
</table>
<table border="1" cellpadding="5" class="dataTable">
<tr>
    	<th>Part Name</th>
        <th>Part Description</th>
    <th>Thickness</th>
        <th>Qty</th>
        <th>On Hand</th>
        <th>Location</th>
    </tr>
<?php
while($buildSheet = mysqli_fetch_array($buildQuery))	{
	if($buildSheet['Qty'] > $buildSheet['onHand'])	{
		$class = 'severe';
	}
	else $class = 'normal';
echo'    <tr class="'.$class.'">
	<td>'.$buildSheet['Name'].'</td>
	<td>'.$buildSheet['Description'].'</td>
	<td>'.$buildSheet['Thickness'].'</td>
	<td>'.$buildSheet['Qty'].'</td>
	<td>'.$buildSheet['onHand'].'</td>
	<td>'.$buildSheet['Location'].'</td>
    </tr>
';
}
?>
</table>

<form enctype="multipart/form-data" action="build_assembly.php" method="get">
<input type="hidden" name="task" value="addPart">
<input type="hidden" name="assemblyID" value="<?php echo $_GET['assemblyID']?>">
	<table border="0">
  <tr>
        	<td>
            	<select name="part_num">
                	<?php while($parts = mysqli_fetch_array($partsQuery))	{
						echo '<option value="'.$parts['ID'].'">'.$parts['Name'].' | '.$parts['Description'].'</option>
						';
					}?>
                </select>
                <input name="qty">
                <button type="submit">Add To Assembly</button>
            </td>
        </table>
</form>
</body>
</html>

<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');


if(isset($_GET['ID']))	{
	$assemblyInfoQuery = mysqli_query($mysqli, "SELECT ID, Name FROM subassemblies WHERE ID = $_GET[ID]");
		if(mysqli_num_rows($assemblyInfoQuery) != 1)	{
			die('Error - Assembly not found');
		}
}

if(isset($_GET['part_num']))	{		
	$partCheck = mysqli_query($mysqli, "SELECT * FROM subassembly_build WHERE partID = $_GET[part_num] AND subassemblyID = $_GET[ID]");	
	if(mysqli_num_rows($partCheck) > 0)	{
		die('Part already assigned to this assembly');
	}
}


	if(!isset($_GET['task']))	{
		$_GET['task'] = '';
	}

	if($_GET['task'] == '')	{
	}

	if($_GET['task'] == 'addPart')	{
	mysqli_query($mysqli,"INSERT INTO subassembly_build VALUES(
	'','$_GET[part_num]','$_GET[ID]','$_GET[qty]')");
	getBlaster();
	
	}
	
	if($_GET['task'] == 'build')	{
		$multiplier = $_GET['buildQty'];
		//pull list of all parts in subassembly for table update.
		$buildListQuery = mysqli_query($mysqli, "SELECT * FROM subassembly_build WHERE subassemblyID = $_GET[ID]") or die(mysqli_error($mysqli));
		//loop through the update process. 
		while($buildList = mysqli_fetch_array($buildListQuery))	{
			mysqli_query($mysqli, "UPDATE parts SET Qty = Qty-($multiplier * $buildList[Qty]) where ID = '$buildList[partID]'");
		}
			mysqli_query($mysqli, "UPDATE subassemblies SET Qty = Qty + $multiplier WHERE ID = $_GET[ID]");
			getBlaster();
	}
	

	$assemblyInfoQuery = mysqli_query($mysqli, "SELECT * FROM subassemblies WHERE ID = '$_GET[ID]'");
	$assemblyData = mysqli_fetch_array($assemblyInfoQuery);
	$queryString = "SELECT subassembly_build.ID, Name, Description, Thickness, Location, subassembly_build.Qty, parts.Qty AS 'onHand', parts.ID as partID FROM subassembly_build JOIN parts ON subassembly_build.partID = parts.ID WHERE subassembly_build.subassemblyID = $_GET[ID]";
	$buildQuery = mysqli_query($mysqli, $queryString) or die(mysqli_error($mysqli));
	
	$partsQuery = mysqli_query($mysqli, "SELECT * FROM parts ORDER BY Name");		

//counter for item number
$x=1;
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
<h2><?php echo $assemblyData['Description']; ?></h2>
<p><a href="print.php?ID=<?php echo $_GET['ID'];?>" target="_blank">Printer Friendly Version</a></p>
<img src="../img/subassemblies/<?php echo $assemblyData['Name']?>.png">
<h2>On Hand: <?php  echo $assemblyData['Qty'];?></h2>
<form action="edit_subassembly.php" method="get">
<input type="hidden" name="ID" value="<?php echo $_GET['ID'];?>">
<input type="hidden" name="task" value="build">
<table border="0" cellpadding="5">
	<tr>
    	<td colspan="2"><h2>Build Subassembly</h2></td>
    </tr>
    <tr>
    	<td>Quantity: <input name="buildQty"></td>
        <td><button type="submit">Build</button></td>
    </tr>
</table>
</form>
<h2>Add Part To Subassembly</h2>
<form enctype="multipart/form-data" action="edit_subassembly.php" method="get">
<input type="hidden" name="task" value="addPart">
<input type="hidden" name="ID" value="<?php echo $_GET['ID']?>">
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


<table border="1" cellpadding="5" class="dataTable">
    <tr>
    	<th>Item No.</th>
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
	<td><a href="delete.php?ID='.$buildSheet['ID'].'" class="blue_link">Del</a> '.$x.' <a href="sa_build_edit.php?ID='.$buildSheet['ID'].'" class="blue_link">Edit</a></td>
	<td><a href="../parts/edit_part.php?ID='.$buildSheet['partID'].'">'.$buildSheet['Name'].'</a></td>
	<td>'.$buildSheet['Description'].'</td>
	<td>'.$buildSheet['Thickness'].'</td>
	<td>'.$buildSheet['Qty'].'</td>
	<td>'.$buildSheet['onHand'].'</td>
	<td>'.$buildSheet['Location'].'</td>
    </tr>
';
$x++;
}
?>
</table>

</body>
</html>

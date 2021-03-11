<?php
session_start();
if(!isset($_GET['subassemblyID']))	{
	die('No Subassembly Has Been Selected');
}
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$saQuery = mysqli_query($mysqli, "SELECT Name, Description, Qty, MinQty, MaxQty, ReorderValue, Location FROM subassemblies WHERE ID = $_GET[subassemblyID]");
$saDetails = mysqli_fetch_array($saQuery);

$partsQuery = mysqli_query($mysqli, "SELECT * FROM parts")
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $saDetails['Name'];?></title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php echo $nav_menu;?>
<form enctype="multipart/form-data" action="subassembly_build.php" method="get">
<table border="0" cellpadding="5" class="dataTable">
	<tr>
    	<th>Name:</th>
        <td><?php echo $saDetails['Name'];?></td>
    </tr>
    	<th>Description:</th>
        <td><?php echo $saDetails['Description'];?></td>
    </tr>
    	<th>Quantity:</th>
        <td><?php echo $saDetails['Qty'];?></td>
    </tr>
    	<th>Minimum Qunatity:</th>
        <td><?php echo $saDetails['MinQty'];?></td>
    </tr>
    	<th>Maximum Quantity:</th>
        <td><?php echo $saDetails['MaxQty'];?></td>
    </tr>
    	<th>Reorder Value:</th>
        <td><?php echo $saDetails['ReorderValue'];?></td>
    </tr>
    	<th>Location:</th>
        <td><?php echo $saDetails['Location'];?></td>
    </tr>
</table>
</form>
<form action="subassembly_build.php" method="get" enctype="multipart/form-data">
	<select name="newPart">
    	<?php 
			while($partsList = mysqli_fetch_array($partsQuery))	{
				echo '<option value="'.$partsList['ID'].'">'.$partsList['Name'].'</option>
				';
			}
		?>
    </select>
    <input name="newPartQty">
    <button type="submit">Enter</button>
</form>
</body>
</html>
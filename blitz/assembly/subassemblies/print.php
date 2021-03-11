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
<h1><?php echo $assemblyData['Name'];?></h1>
<h2><?php echo $assemblyData['Description']; ?></h2>
<img src="../img/subassemblies/<?php echo $assemblyData['Name']?>.png">
<h2>On Hand: <?php  echo $assemblyData['Qty'];?></h2>
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
	<td>'.$x.'</td>
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

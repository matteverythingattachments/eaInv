<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
if(isset($_GET['ID'], $_GET['name'], $_GET['description'], $_GET['qty'], $_GET['minqty'], $_GET['maxqty'], $_GET['reordervalue'], $_GET['location']))	{
	mysqli_query($mysqli, "UPDATE assemblies SET NAME = '$_GET[name]' WHERE ID = $_GET[ID];") or die(mysqli_error($mysqli));
	mysqli_query($mysqli, "UPDATE assemblies SET Description = '$_GET[description]' WHERE ID = $_GET[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET Qty = $_GET[qty] WHERE ID = $_GET[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET MinQty = $_GET[minqty] WHERE ID = $_GET[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET MaxQty = $_GET[maxqty] WHERE ID = $_GET[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET ReorderValue = $_GET[reordervalue] WHERE ID = $_GET[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET Location = '$_GET[location]' WHERE ID = $_GET[ID];;");
}

$assemblyQuery = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE ID = $_GET[ID]") or die(mysqli_error($mysqli));
$assemblyInfo = mysqli_fetch_array($assemblyQuery);
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
<form action="edit_assembly.php" method="get">
<input type="hidden" name="ID" value="<?php echo $_GET['ID']; ?>">
<table style="border-collapse:collapse;" width="200" border="0" cellpadding="5">
  <tr>
    <th scope="row">Name</th>
    <td><label for="textfield"></label>
    <input type="text" name="name" value="<?php echo $assemblyInfo['Name'];?>"></td>
  </tr>
  <tr>
    <th scope="row">Description</th>
    <td><input type="text" name="description" value="<?php echo $assemblyInfo['Description'];?>"></td>
  </tr>
  <tr>
    <th scope="row">Qty</th>
    <td><input type="text" name="qty" value="<?php echo $assemblyInfo['Qty'];?>"></td>
  </tr>
  <tr>
    <th scope="row">Minimum Quantity</th>
    <td><input type="text" name="minqty" value="<?php echo $assemblyInfo['MinQty'];?>"></td>
  </tr>
  <tr>
    <th scope="row">Maximum Quantity</th>
    <td><input type="text" name="maxqty" value="<?php echo $assemblyInfo['MaxQty'];?>"></td>
  </tr>
  <tr>
    <th scope="row">Reorder Value</th>
    <td><input type="text" name="reordervalue" value="<?php echo $assemblyInfo['ReorderValue'];?>"></td>
  </tr>
  <tr>
    <th scope="row">Location</th>
    <td><input type="text" name="location" value="<?php echo $assemblyInfo['Location'];?>"></td>
  </tr>
  <tr>
    <th colspan="2" scope="row"><button type="submit">Enter</button></th>
  </tr>
</table>
</form>
</body>
</html>
<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
include('../admin/resize-class.php');

if($_GET['ID']!='')	{
	$ID = $_GET['ID'];
}

else	{
	$ID = $_POST['ID'];
}

if(isset($_POST['ID'], $_POST['name'], $_POST['description'], $_POST['qty'], $_POST['minqty'], $_POST['maxqty'], $_POST['reordervalue'], $_POST['location']))	{
	mysqli_query($mysqli, "UPDATE assemblies SET NAME = '$_POST[name]' WHERE ID = $_POST[ID];") or die(mysqli_error($mysqli));
	mysqli_query($mysqli, "UPDATE assemblies SET Description = '$_POST[description]' WHERE ID = $_POST[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET Qty = $_POST[qty] WHERE ID = $_POST[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET MinQty = $_POST[minqty] WHERE ID = $_POST[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET MaxQty = $_POST[maxqty] WHERE ID = $_POST[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET ReorderValue = $_POST[reordervalue] WHERE ID = $_POST[ID];;");
	mysqli_query($mysqli, "UPDATE assemblies SET Location = '$_POST[location]' WHERE ID = $_POST[ID];;");
	
	rename("../img/assemblies/$_POST[oldname].jpg", "../img/assemblies/$_POST[name].jpg");
					$newPartName = $_POST['name'];
					
	@move_uploaded_file( $_FILES['new_assembly_pic']['tmp_name'], '../img/tmp/'.$newPartName.'.jpg');
	$resizeObj = new resize('../img/tmp/'.$newPartName.'.jpg') or die('cant find pic');
	$resizeObj -> resizeImage(800, 450, 'auto');
	$resizeObj -> saveImage('../img/assemblies/'.$newPartName.'.jpg', 100);
}

$assemblyQuery = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE ID = $ID") or die(mysqli_error($mysqli));
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
<form action="edit_assembly.php?ID=<?php echo $ID;?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="ID" value="<?php echo $ID; ?>">
<table style="border-collapse:collapse;" width="200" border="0" cellpadding="5">
  <tr>
    <th scope="row">Name</th>
    <td><label for="textfield"></label>
    <input type="text" name="name" value="<?php echo $assemblyInfo['Name'];?>"></td>
 		<input type="hidden" name="oldname" value="<?php echo $assemblyInfo['Name']; ?>">
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
    	<td colspan="2"><input type="file" name="new_assembly_pic"></td>
    </tr>
  <tr>
    <th colspan="2" scope="row"><button type="submit">Enter</button></th>
  </tr>
</table>
</form>
<img src="../img/assemblies/<?php echo $assemblyInfo['Name'];?>.jpg">
</body>
</html>
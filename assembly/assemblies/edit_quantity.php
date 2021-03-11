<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

// make sure variable is set before trying to populate the page
if(!isset($_GET['ID'])) die('Critical Error Please Go Back');

if(isset($_GET['Qty']))	{mysqli_query($mysqli, "UPDATE assembly_build SET Qty = $_GET[Qty] WHERE ID = $_GET[ID]");}

//get information about association
$assocQuery = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE ID = $_GET[ID]") or die(mysqli_error($mysqli));
$assocInfo = mysqli_fetch_array($assocQuery);

if(isset($_GET['Qty'])) 

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
<form action="edit_quantity.php" method="get">
<input name="ID" type="hidden" value="<?php echo $_GET['ID'];?>" >
<input name="Qty" value="<?php echo $assocInfo['Qty'];?>">
<button type="submit">Enter</button>
</form>
</body>
</html>
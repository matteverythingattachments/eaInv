<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/components.php');
include('../admin/scripts/access.php');

if(!isset($_GET['ID']))	{
	header("Location:".$_SERVER['HTTP_REFERER']);
}

$saBuildQuery = mysqli_query($mysqli, "SELECT subassembly_build.ID as buildID, parts.Name AS part_name, parts.Description AS part_description, subassemblies.Name AS subassembly_name,  subassemblies.Description AS subassembly_description, subassembly_build.Qty as build_qty FROM subassembly_build JOIN parts ON partID = parts.ID JOIN subassemblies ON subassemblyID = subassemblies.ID  WHERE subassembly_build.ID = $_GET[ID]") or die(mysqli_error($mysqli));

$saBuildInfo = mysqli_fetch_array($saBuildQuery);
?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
echo $nav_menu;
?>
<table border="0" cellpadding="5">
	<tr>
    	<th>Subassembly Name:</th>
        <td><?php echo $saBuildInfo['subassembly_name'].' | '.$saBuildInfo['subassembly_description'];?></td>
    </tr>
    <tr>
    	<th>Part Name:</th>
        <td><?php echo $saBuildInfo['part_name'].' | '.$saBuildInfo['part_description'];?></td>
    </tr>
    <tr>
    	<th>Quantity:</th>
        <td><?php echo $saBuildInfo['build_qty'];?></td>
    </tr>
</table>
</body>
</html>
<!doctype html>
<html>
<?php
//error_reporting(0);
session_start();
include('admin/scripts/auth_check.php');
include('admin/scripts/components.php');
include('admin/scripts/access.php');
$today = date('Y-m-d');
$auditName = "audit/$today.txt";

/*if(file_exists($auditName))    {
	$auditFile = fopen($auditName, "r") or die('cant open');
	$fileContents = fread($auditFile,"500") or die('cant read');
	$locArray = explode(' ',$fileContents);
	
	$todaysLocsQueries = mysqli_query($mysqli, "SELECT @n := @n + 1 n, Location FROM parts Group By Location Order By n");
}
else	{
	//count locations
	$locCountQuery = mysqli_query($mysqli, "SELECT Location FROM parts GROUP BY Location") or die(mysqli_error($mysqli));
	$locCount = mysqli_num_rows($locCountQuery);

}
*/
include('components/html_header.php');
?>
<head>
<meta charset="utf-8">
<title>EA Assembly</title>
    <link href="css/inv_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/functions.js">
</script>
</head>
<body>
<?php 
	echo "Logged In As: ".$_SESSION["role"]." ".$_SESSION["userName"];
	echo $nav_menu;?>
<ul>
	<li><a href="assemblies/">Assemblies</a></li>
    <li><a href="parts/">Parts</a></li>
</ul>
</body>
</html>
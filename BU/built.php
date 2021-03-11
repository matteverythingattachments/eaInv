<?php
include('admin/scripts/access.php');
$prod_query = mysqli_query($conn, "SELECT Product, On_Hand, Category FROM EA_PRODS JOIN CATEGORIES ON EA_PRODS.Cat_ID = CATEGORIES.ID") or die(mysqli_error());
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
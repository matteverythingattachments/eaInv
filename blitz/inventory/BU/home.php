<?php
session_start();
include('admin/scripts/access.php');
$cat_query = mysqli_query($conn,"SELECT * FROM CATEGORIES")
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Everything Attachments Inventory</title>
</head>

<body>
You are logged in as <?php echo $_SESSION['user'];?>
<ul>
	<?php 
	while($list = mysqli_fetch_array($cat_query))	{
		echo '<li><a href="view_cat.php?CAT_ID='.$list['ID'].'">'.$list['CATEGORY'].'</a></li>
		';
	}
	?>
    
</ul>
  <a href="sold.php">Enter Product Sold </a><br>
<a href="built.php"> Enter Product Built </a>
</body>
</html>
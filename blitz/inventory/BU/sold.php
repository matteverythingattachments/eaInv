<?php
include('admin/scripts/access.php');
$prod_query = mysqli_query($conn, "SELECT EA_PRODS.ID, Product, On_Hand, CATEGORY FROM EA_PRODS JOIN CATEGORIES ON EA_PRODS.Cat_ID = CATEGORIES.ID") or die(mysqli_error());
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Enter Sold Product</title>
</head>

<body>
<form name="form1" method="post" action="update_sold.php">
  <select name="product" id="product">
  <?php while($prods = mysqli_fetch_array($prod_query))	{
	  echo '<option value="'.$prods['ID'].'">'.$prods['CATEGORY'].'-'.$prods['Product'].'</option>
	  ';
  }?>
  </select>
  <input type="text" name="qty" id="qty">
  <input type="submit" name="submit" id="submit" value="Submit">
</form>
</body>
</html>
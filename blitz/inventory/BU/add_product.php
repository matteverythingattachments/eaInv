<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$cat_query = mysqli_query($conn, "SELECT * FROM CATEGORIES ORDER BY ID")
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Product</title>
</head>

<body>
<form name="form1" method="post" action="process_product.php">
  <p>
    <label for="Prod_name"></label>
    <label for="textfield"></label>
    <label for="textfield2"></label>
    <label for="textfield3"></label>
    <label for="textfield4"></label>
    <label for="textfield5"></label>
    <label for="textfield6"></label>
  </p>
  <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th scope="row">Product Name</th>
      <td><input type="text" name="Prod_Name" id="Prod_name"></td>
    </tr>
    <tr>
      <th scope="row">Black Qty</th>
      <td><input type="text" value=0 name="Black" id="textfield"></td>
    </tr>
    <tr>
      <th scope="row">Green Qty</th>
      <td><input type="text" value=0 name="Green" id="textfield2"></td>
    </tr>
    <tr>
      <th scope="row">Orange Qty</th>
      <td><input type="text" value=0 name="Orange" id="textfield3"></td>
    </tr>
    <tr>
      <th scope="row">Yellow Qty</th>
      <td><input type="text" value=0 name="Yellow" id="textfield4"></td>
    </tr>
    <tr>
      <th scope="row">Primer Qty</th>
      <td><input type="text" value=0 name="Primer" id="textfield5"></td>
    </tr>
    <tr>
      <th scope="row">Price</th>
      <td><input type="text" name="Price" id="textfield6"></td>
    </tr>
    <tr>
      <th scope="row">Category</th>
      <td><label for="select"></label>
        <select name="Category" id="select">
        <?php 
		while($list = mysqli_fetch_array($cat_query))	{
			echo '<option value="'.$list['ID'].'">'.$list['CATEGORY'].'</option>
			';
		}
		?>
      </select></td>
    </tr>
    <tr>
      <th colspan="2" scope="row"><input type="submit" name="submit" id="submit" value="Submit"></th>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
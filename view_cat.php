<?php
session_start();
include('admin/scripts/access.php');
$cat_id = $_GET['CAT_ID'];
$cat_name = mysqli_fetch_array(mysqli_query($conn, "SELECT CATEGORY FROM CATEGORIES WHERE ID = '$cat_id'"));
$cat_query = mysqli_query($conn, "SELECT * FROM EA_PRODS WHERE CAT_ID = '$cat_id'")
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Inventory Of <?php echo $cat_name['CATEGORY'];?></title>
</head>

<body>
<?php
echo '<h1>'.$cat_name['CATEGORY'].'</h1>
';
	echo '<table border="1" cellpadding="5" style="border-collapse:collapse;">
		<tr>
			<th>Product</th>
			<th>On Hand</th>
			<th>On Order</th>
			<th>Best Delivery</th>
			<th>Price</th>
		</tr>
		';
while($products = mysqli_fetch_array($cat_query))	{
	echo '<tr>
		<td>'.$products['Product'].'</td>
		<td>'.$products['On_Hand'].'</td>
		<td>'.$products['Ordered'].'</td>
		<td>'.$products['Est_Delivery'].'</td>
		<td>'.$products['Price'].'</td>
	</tr>';
}
echo '</table>';
?>
</body>
</html>
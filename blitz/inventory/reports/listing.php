<?php
include('db_access.php');
$query = mysql_query("SELECT orders.OrderID, OrderDate, OrderStatus, PaymentAmount, ShipState, ProductCode, ProductName, Quantity, ShipDate, Method
FROM orders
JOIN payment_method ON orders.PaymentMethodID = payment_method.ID
JOIN order_details on orders.OrderID = order_details.OrderID
WHERE OrderStatus !='Cancelled'");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
<table border="1" cellpadding="5" style="border-collapse:collapse;">
	<tr>
    	<th>Order ID</th>
    	<th>Order Date</th>
    	<th>Order Status</th>
    	<th>Payment Amount</th>
    	<th>Ship State</th>
    	<th>Product Code</th>
    	<th>Product Name</th>
    	<th>Quantity</th>
    	<th>Ship Date</th>
    	<th>Method</th>
    </tr>
<?php
	while($list = mysql_fetch_array($query))	{
		echo '<tr>
			<td>'.$list['OrderID'].'</td>
			<td>'.$list['OrderDate'].'</td>
			<td>'.$list['OrderStatus'].'</td>
			<td>'.$list['PaymentAmount'].'</td>
			<td>'.$list['ShipState'].'</td>
			<td>'.$list['ProductCode'].'</td>
			<td>'.$list['ProductName'].'</td>
			<td>'.$list['Quantity'].'</td>
			<td>'.$list['ShipDate'].'</td>
			<td>'.$list['Method'].'</td>
			</tr>'
			;
	}
?>
</table>
</body>
</html>
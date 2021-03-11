<?php
include('db_access.php');

//Setting up dates and queries for daily totals
$eff_date = $_GET['year'].'-'.$_GET['month'].'-'.$_GET['day'];
$tdate = date('l F jS o');
$sales_nums = mysql_query("SELECT COUNT(OrderID) as NumOrders, SUM(PaymentAmount) as TotalPayment FROM orders WHERE OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'") or die(mysql_error());
$sales_sum = mysql_fetch_array($sales_nums);

//querying for visa
$visa_query = mysql_query("SELECT SUM(PaymentAmount) AS Visa FROM orders WHERE PaymentMethodID = '5' AND OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'");
$visa_num = mysql_fetch_array($visa_query);

//querying for MasterCard
$MC_query = mysql_query("SELECT SUM(PaymentAmount) AS MC FROM orders WHERE PaymentMethodID = '6' AND OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'");
$MC_num = mysql_fetch_array($MC_query);


//querying for AMEX
$AMEX_query = mysql_query("SELECT SUM(PaymentAmount) AS AMEX FROM orders WHERE PaymentMethodID = '7' AND OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'");
$AMEX_num = mysql_fetch_array($AMEX_query);

//querying for DISC
$DISC_query = mysql_query("SELECT SUM(PaymentAmount) AS DISC FROM orders WHERE PaymentMethodID = '8' AND OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'");
$DISC_num = mysql_fetch_array($DISC_query);

//querying for PAYPAL
$PAYPAL_query = mysql_query("SELECT SUM(PaymentAmount) AS PAYPAL FROM orders WHERE PaymentMethodID = '18' AND OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'");
$PAYPAL_num = mysql_fetch_array($PAYPAL_query);

//querying for CBM
$CBM_query = mysql_query("SELECT SUM(PaymentAmount) AS CBM FROM orders WHERE PaymentMethodID = '2' AND OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'");
$CBM_num = mysql_fetch_array($CBM_query);

//queries for CASH
$CASH_query = mysql_query("SELECT SUM(PaymentAmount) AS CASH FROM orders WHERE PaymentMethodID = '17' AND OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'");
$CASH_num = mysql_fetch_array($CASH_query);

//query for order nums and details
$order_query = mysql_query("SELECT orders.OrderID, ProductPrice, ProductCode, ProductName FROM orders JOIN order_details on orders.OrderID = order_details.OrderID WHERE OrderDate = '$eff_date' AND OrderStatus != 'Cancelled'") or die(mysql_error());
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo 'Report For '.$eff_date;?></title>
</head>

<body style="font-family:Verdana, Geneva, sans-serif; font-size:8pt;">
<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td><?php echo $tdate?></td>
    <td>&nbsp;</td>
    <td>Report For <?php echo $eff_date?></td>
    <th rowspan="5" valign="bottom">Product Name</th>
  </tr>
  <tr>
    <td align="right">No Of Sales: <?php echo $sales_sum['NumOrders']?></td>
    <td align="right">&nbsp;</td>
    <td align="right">Total Sales: $<?php echo $sales_sum['TotalPayment']?></td>
  </tr>
  <tr>
    <td align="right">Visa: $<?php echo $visa_num['Visa'];?></td>
    <td align="right">MasterCard: $<?php echo $MC_num['MC'];?></td>
    <td align="right">American Express: $<?php echo $AMEX_num['AMEX'];?></td>
  </tr>
  <tr>
    <td align="right">Discover: $<?php echo $DISC_num['DISC']?></td>
    <td align="right">PayPal: $<?php echo $PAYPAL_num['PAYPAL'];?></td>
    <td align="right">Check By Mail: $<?php echo $CBM_num['CBM'];?><br>
Cash: $<?php echo $CASH_num['CASH'];?></td>
  </tr>
  <tr>
    <th>Order No.</th>
    <th>Price</th>
    <th>Product Code</th>
  </tr>
  <?php
  while($list = mysql_fetch_array($order_query))	{
	  echo '<tr>
	  	<td>'.$list['OrderID'].'</td>
	  	<td align="right">$'.$list['ProductPrice'].'</td>
	  	<td>'.$list['ProductCode'].'</td>
	  	<td>'.$list['ProductName'].'</td>
		</tr>
	';
  }
  ?>
</table>
</body>
</html>

<?php
include('db_access.php');
$prod_query = mysql_query("SELECT * FROM eta_prods");
$curr_year = 2014;
$x =1;
$start_time = time();
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Products Sold By Month</title>
<style type="text/css">
body {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
</style></head>

<body>

<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <th scope="row">Number of Products Sold By Month <?php echo $curr_year;?></th>
    <th>Jan</th>
    <th>Feb</th>
    <th>Mar</th>
    <th>April</th>
    <th>May</th>
    <th>June</th>
    <th>July</th>
    <th>Aug</th>
    <th>Sept</th>
    <th>Oct</th>
    <th>Nov</th>
    <th>Dec</th>
  </tr>
  <?php
  while($prods = mysql_fetch_array($prod_query))	{
	  $jan_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-01-01' AND '$curr_year-01-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $jan_number = mysql_fetch_array($jan_query);
	  if($jan_number['NUM'] == '')	{
		$jan_number['NUM'] = 0;  
		$x++;
	  }

	  $feb_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-02-01' AND '$curr_year-02-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $feb_number = mysql_fetch_array($feb_query);
	  if($feb_number['NUM'] == '')	{
		$feb_number['NUM'] = 0;  
		$x++;
	  }

	  $mar_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-03-01' AND '$curr_year-03-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $mar_number = mysql_fetch_array($mar_query);
	  if($mar_number['NUM'] == '')	{
		$mar_number['NUM'] = 0;  
		$x++;
	  }

	  $apr_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-04-01' AND '$curr_year-04-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $apr_number = mysql_fetch_array($apr_query);
	  if($apr_number['NUM'] == '')	{
		$apr_number['NUM'] = 0;  
		$x++;
	  }

	  $may_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-05-01' AND '$curr_year-05-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $may_number = mysql_fetch_array($may_query);
	  if($may_number['NUM'] == '')	{
		$may_number['NUM'] = 0;  
		$x++;
	  }

	  $june_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-06-01' AND '$curr_year-06-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $june_number = mysql_fetch_array($june_query);
	  if($june_number['NUM'] == '')	{
		$june_number['NUM'] = 0;  
		$x++;
	  }
	  $july_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-07-01' AND '$curr_year-07-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $july_number = mysql_fetch_array($july_query);
	  if($july_number['NUM'] == '')	{
		$july_number['NUM'] = 0;  
		$x++;
	  }

	  $aug_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-08-01' AND '$curr_year-08-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $aug_number = mysql_fetch_array($aug_query);
	  if($aug_number['NUM'] == '')	{
		$aug_number['NUM'] = 0;  
		$x++;
	  }

	  $sept_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-09-01' AND '$curr_year-09-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $sept_number = mysql_fetch_array($sept_query);
	  if($sept_number['NUM'] == '')	{
		$sept_number['NUM'] = 0;  
		$x++;
	  }

	  $oct_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-10-01' AND '$curr_year-10-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $oct_number = mysql_fetch_array($oct_query);
	  if($oct_number['NUM'] == '')	{
		$oct_number['NUM'] = 0;  
		$x++;
	  }

	  $nov_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-11-01' AND '$curr_year-11-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $nov_number = mysql_fetch_array($nov_query);
	  if($nov_number['NUM'] == '')	{
		$nov_number['NUM'] = 0;  
		$x++;
	  }

	  $dec_query = mysql_query("SELECT SUM(Quantity) as NUM FROM order_details JOIN orders ON order_details.OrderID = orders.OrderID WHERE ProductCode = '$prods[ID]' AND OrderDate Between '$curr_year-12-01' AND '$curr_year-12-31' AND OrderStatus != 'Cancelled'") or die(mysql_error());
	  $dec_number = mysql_fetch_array($dec_query);
	  if($dec_number['NUM'] == '')	{
		$dec_number['NUM'] = 0;  
		$x++;
	  }
	  echo '<tr>
	  	<th align="left">'.$prods['ID'].'<br>'.$prods['Name'].'</th>
		<td>'.$jan_number['NUM'].'</td>
		<td>'.$feb_number['NUM'].'</td>
		<td>'.$mar_number['NUM'].'</td>
		<td>'.$apr_number['NUM'].'</td>
		<td>'.$may_number['NUM'].'</td>
		<td>'.$june_number['NUM'].'</td>
		<td>'.$july_number['NUM'].'</td>
		<td>'.$aug_number['NUM'].'</td>
		<td>'.$sept_number['NUM'].'</td>
		<td>'.$oct_number['NUM'].'</td>
		<td>'.$nov_number['NUM'].'</td>
		<td>'.$dec_number['NUM'].'</td>';
  }
  ?>
</table>
<?php 
$end_time = time();
$tot_time = $end_time - $start_time;
echo "<h3>Calculations were performed in $tot_time seconds with $x queries</h3>"; ?>
</body>
</html>
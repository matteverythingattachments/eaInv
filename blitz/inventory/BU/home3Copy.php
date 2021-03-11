<?php
// Turn off all error reporting
error_reporting(0);
?>
<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$runningTotal = 0;
$cat_query = mysqli_query($conn,"SELECT CATEGORY FROM CATEGORIES ORDER BY ID ASC") or die(mysqli_error());
$num_rows = mysqli_num_rows($cat_query);
$x=0;
while($cat_list = mysqli_fetch_array($cat_query))	{
	$category[$x] = $cat_list['CATEGORY'];
	$x++;
}
$x=0;

$nav_query = mysqli_query($conn,"SELECT * FROM CATEGORIES ORDER BY ID") or die(mysqli_error());
?>
<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript">
function refresher()	{
	window.location.reload();
}
function autoload()	{
	setInterval("refresher()", 120000);
}
</script>
<style>
.row_header	{
	background-color:#FC0;
	color:#A65300
}
.rows	{
	background-color:#FFF;
}
a	{
	color:#000;
}
.alert	{
	color:#F00;
	font-weight:bolder;
	background-color:#000;
}
.alert a	{
	color:#F00;
	font-weight:bolder;
}
#logout {
	position: fixed;
	right: 0px;
	z-index:50;
}

nav	{
	background-color:#F00;
	position:fixed;
	top:0px;
	left:0px;
	z-index:500;
	margin-bottom: 10px;
	width: auto;
}

#nav_list li	{
	display:table;
}

#nav_list a	{
	color:#FFF;
}
	
	#running_total {
		position:fixed;
		left:10px;
		bottom:0;
	}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Everything Attachments Inventory</title>
</head>

<body onLoad="autoload()">
<div id="logout">
<p>You are logged in as <?php echo $_SESSION['user'];?></p>
<p>Last updated at <script type="text/javascript">
<!--
	var currentTime = new Date()
	var hours = currentTime.getHours()
	var minutes = currentTime.getMinutes()

	if (minutes < 10)
	minutes = "0" + minutes

	var suffix = "AM";
	if (hours >= 12) {
	suffix = "PM";
	hours = hours - 12;
	}
	if (hours == 0) {
	hours = 12;
	}

	document.write("<b>" + hours + ":" + minutes + " " + suffix + "</b>")
//-->
</script>

<a href="logout.php">Logout</a><br>

<a href="paladin.php">Paladin Products</a>
</p>


<p><a href="add_category.php">Add Categories</a></p>
<p><a href="add_product.php">Add Product</a></p>
<p>
	<a href="printer_friendly.php" target="_blank">Printer Friendly Version</a>
	</p>
</div>
<nav>
	<ul id="nav_list">
		<?php while($nav_list = mysqli_fetch_array($nav_query))	{
			echo '<li><a href="#'.$nav_list['ID'].'">'.$nav_list['CATEGORY'].'</a></li>
				';
			}?>
	</ul>
</nav>
<table border="1" align="center" cellpadding="5" style="border-collapse:collapse; font-family:Verdana, Geneva, sans-serif; font-size:9pt; z-index:350; position:relative; top:55px;">
	<tr class="rows">
    	<th>Product</th>
        <th>On Hand</th>
        <th>Yellow</th>
        <th>Best Delivery</th>
        <th>Cost</th>
        <th>Threshold</th>
				<th>Total</th>
    </tr>
	<?php 
	while($x < $num_rows)	{
		$y = $x+1;
		echo '<tr>
			<th colspan="7" class="row_header"><a name="'.$y.'">'.$category[$x].'</a></th>
		</tr>
		';
		$x++;
		$prod_query = mysqli_query($conn, "SELECT * FROM EA_PRODS WHERE Cat_ID = '$x' ORDER BY display_order, Product");
		while($prod_list = mysqli_fetch_array($prod_query))	{
			if($prod_list['On_Hand'] > 0 ) {
					$retail_value = $prod_list['On_Hand'] * $prod_list['Price'];
					$wholesale_value = $retail_value * .65;
				$runningTotal = $runningTotal + ($wholesale_value);
				}
			else {
				$wholesale_value = 0;
			}
			if($prod_list['On_Hand'] < $prod_list['Low_Limit'])	{
				
			echo '<tr class="alert">
				<th><a href="prod_details.php?ID='.$prod_list['ID'].'">'.$prod_list['Product'].'</a></th>
				<th>'.$prod_list['On_Hand'].'</th>
				<td>'.$prod_list['Ordered'].'</td>
				<td>'.$prod_list['Est_Delivery'].'</td>
				<td>'.$prod_list['Price'].'</td>
				<td>'.$prod_list['Low_Limit'].'</td>
				<td>'.$wholesale_value.'</td>
			</tr>
			';
			}
			else	{
				echo '<tr class="rows">
				<th><a href="prod_details.php?ID='.$prod_list['ID'].'">'.$prod_list['Product'].'</a></th>
				<th>'.$prod_list['On_Hand'].'</th>
				<td>'.$prod_list['Ordered'].'</td>
				<td>'.$prod_list['Est_Delivery'].'</td>
				<td>'.$prod_list['Price'] * .65.'</td>
				<td>'.$prod_list['Low_Limit'].'</td>
				<td>'.$wholesale_value.'</td>
			</tr>
			';
			}
		}
	}
	?>
</table>
	<h2 id="running_total">
		<?php echo 'Total Inventory Value $'.number_format($runningTotal, 2); ?>
	</h2>
</body>
</html>
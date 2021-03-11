<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$cat_query = mysqli_query($conn,"SELECT CATEGORY FROM CATEGORIES ORDER BY ID ASC") or die(mysqli_error());
$num_rows = mysqli_num_rows($cat_query);
$x=0;
while($cat_list = mysqli_fetch_array($cat_query))	{
	$category[$x] = $cat_list['CATEGORY'];
	$x++;
}
$x=0;
?>
<!DOCTYPE HTML>
<html>
<head>
<style>
.row_header	{
	background-color:#FC0;
	color:#000;
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
	position: absolute;
	left: 0px;
	top: 0px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Everything Attachments Inventory</title>
</head>

<body>
<script type="text/javascript">
setInterval(refresher(), 120000)
</script>
<div id="logout">
<p>You are logged in as <?php echo $_SESSION['user'];?></p>
<p><a href="logout.php">Logout</a>
</p>
<p><a href="paladin.php">Paladin Products</a></p>
</div>
<table border="1" align="center" cellpadding="5" style="border-collapse:collapse; font-family:Verdana, Geneva, sans-serif; font-size:9pt;">
	<tr>
    	<th>Product</th>
        <th>On Hand</th>
        <th>Peanut Needs</th>
        <th>Best Delivery</th>
        <th>Price</th>
        <th>Threshold</th>
    </tr>
	<?php 
	while($x < $num_rows)	{
		echo '<tr>
			<th colspan="6" class="row_header">'.$category[$x].'</th>
		</tr>
		';
		$x++;
		$prod_query = mysqli_query($conn,"SELECT * FROM EA_PRODS WHERE Cat_ID = '$x'");
		while($prod_list = mysqli_fetch_array($prod_query))	{
			if($prod_list['On_Hand'] < $prod_list['Low_Limit'])	{
			echo '<tr class="alert">
				<th><a href="prod_details.php?ID='.$prod_list['ID'].'">'.$prod_list['Product'].'</a></th>
				<th>'.$prod_list['On_Hand'].'</th>
				<td>'.$prod_list['Ordered'].'</td>
				<td>'.$prod_list['Est_Delivery'].'</td>
				<td>'.$prod_list['Price'].'</td>
				<td>'.$prod_list['Low_Limit'].'</td>
			</tr>
			';
			}
			else	{
				echo '<tr>
				<th><a href="prod_details.php?ID='.$prod_list['ID'].'">'.$prod_list['Product'].'</a></th>
				<th>'.$prod_list['On_Hand'].'</th>
				<td>'.$prod_list['Ordered'].'</td>
				<td>'.$prod_list['Est_Delivery'].'</td>
				<td>'.$prod_list['Price'].'</td>
				<td>'.$prod_list['Low_Limit'].'</td>
			</tr>
			';
			}
		}
	}
	?>
</table>
</body>
</html>
<?php
error_reporting(E_ALL);
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$cat_query = mysqli_query($conn, "SELECT NAME FROM bradco_cats ORDER BY ID ASC") or die(mysqli_error($conn));
$num_rows = mysqli_num_rows($cat_query);
$x=0;$runningTotal = 0;
while($cat_list = mysqli_fetch_array($cat_query))	{
	$category[$x] = $cat_list['NAME'];
	$x++;
}
$x=0;

$nav_query = mysqli_query($conn, "SELECT * FROM bradco_cats ORDER BY NAME ASC") or die(mysqli_error($conn));
?>
<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript">
function refresher()	{
	window.location.reload();
}
function autoload()	{
	//setInterval("refresher()", 120000);
}
</script>
<style>
.row_header	{
	background-color:#FC0;
	color:#A65300
}
tr:nth-of-type(odd)
{  
background-color: #fff;
}
tr:nth-of-type(even)
{  
background-color: #eee;
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
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Paladin (Bradco) Inventory</title>
</head>

<body>



<table border="1" align="center" cellpadding="5" style="border-collapse:collapse; font-family:Verdana, Geneva, sans-serif; font-size:9pt; z-index:350; position:relative; top:55px;">
	<tr class="rows">
    	<th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
		<th>Total</th>
    </tr>
	<?php 
	while($x < $num_rows)	{
		$y = $x+1;
		echo '<tr>
			<th colspan="10" class="row_header"><a name="'.$y.'">'.$category[$x].'</a></th>
		</tr>';
		$x++;
		$prod_query = mysqli_query($conn, "SELECT * FROM bradco_prods WHERE Cat_ID = '$x' ORDER BY Cat_ID, Product ASC");
		while($prod_list = mysqli_fetch_array($prod_query))	{
			if ($prod_list['On_Hand'] > 0 )
            {
				$retail_value = ($prod_list['On_Hand']) * $prod_list['Price'];
				$wholesale_value = $retail_value * .65;
				$runningTotal = $runningTotal + ($retail_value);
            }
			else {
				$wholesale_value = 0;
                $retail_value = 0;
			}
                echo '<tr class="rows">
				<th>'.$prod_list['Product'].'</th>
				<th>'.$prod_list['On_Hand'].'</th>
                <td>'.money_format('$%.2n', $prod_list['Price']).'</td>
				<td>'.money_format('$%.2n', $retail_value).'</td>
			</tr>'; 
        }
	}
	?>
</table>
<div style="width:100%; height:5000px;"></div>
</body>
</html>
<?php
// Turn off all error reporting
//error_reporting(0);
?>
<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$runningTotal = 0;
$cat_query = mysqli_query($conn,"SELECT NAME FROM bradco_cats order by ID ASC") or die(mysqli_error($conn));
$num_rows = mysqli_num_rows($cat_query);
$x=0;
while($cat_list = mysqli_fetch_array($cat_query))	{
	$category[$x] = $cat_list['NAME'];
	$x++;
}
$x=0;

$nav_query = mysqli_query($conn,"SELECT * FROM bradco_cats ORDER BY NAME ASC") or die(mysqli_error($conn));
?>
<!DOCTYPE HTML>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
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
	background-color:#2769AB;
	position:fixed;
	top:0px;
	left:0px;
	z-index:500;
	margin-bottom: 10px;
	width: auto;
    padding-right: 10px;
    padding-top: 10px;
}
title	{
	background-color:#F00;
	position:fixed;
	top:0px;
	left:100px;
	z-index:1000;
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
    #scroll {
    position:fixed;
    right:10px;
    bottom:10px;
    cursor:pointer;
    width:50px;
    height:50px;
    background-color:#3498db;
    text-indent:-9999px;
    display:none;
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    border-radius:5px;
}
#scroll span {
    position:absolute;
    top:50%;
    left:50%;
    margin-left:-8px;
    margin-top:-12px;
    height:0;
    width:0;
    border:8px solid transparent;
    border-bottom-color:#ffffff
}
#scroll:hover {
    background-color:#e74c3c;
    opacity:1;
    filter:"alpha(opacity=100)";
    -ms-filter:"alpha(opacity=100)";    
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Paladin (Bradco) Inventory</title>
</head>

<body>
<div id="logout">
<?php 
    $adminTitle = '';
    if ($_SESSION['rights'] == 'admin') { $adminTitle = "Administrator"; } ?>
  <p>Welcome,  <?php echo $_SESSION['user'];echo '<br>'.$adminTitle;?></p>
  <div id="message"></div>
<p><p>
<a href="logout.php" class="btn btn-danger btn-sm">Logout</a><br><br><br>
<a href="home3.php" class="btn btn-success">Home</a>
</p>
</p>
        <p>
	<a href="printer_friendly_paladin.php" target="_blank" class="btn btn-outline-dark">
          <span class="glyphicon glyphicon-print"></span>Print </a>
	</p>
        <?php $date = date('M d, Y ').'<br>@'.date(" h:i a"); ?>
        <br><u>Last updated:</u><br><?php echo $date; ?>
</div>
<nav>
	<ul id="nav_list">
		<?php while($nav_list = mysqli_fetch_array($nav_query))	{
			echo '<li><a href="#'.$nav_list['ID'].'">'.$nav_list['NAME'].'</a></li>
				';
			}?>
	</ul>
</nav>
<table border="1" align="center" cellpadding="5" style="border-collapse:collapse; font-family:Verdana, Geneva, sans-serif; font-size:9pt; z-index:350; position:relative; top:55px;">
	<tr class="rows">
    	<th>Product</th>
        <th style="background:#8CC6FF">Quantity</th>
        <th>Price</th>
		<th>Total</th>
    </tr>
	<?php 
	while($x < $num_rows)	{
		$y = $x+1;
		echo '<tr>
			<th colspan="10" class="row_header"><a name="'.$y.'">'.$category[$x].'</a></th>
		</tr>
		';
		$x++;
		$prod_query = mysqli_query($conn, "SELECT * FROM bradco_prods WHERE Cat_ID = '$x' ORDER BY Cat_ID, Product ASC");
        //DEBUG
        if (!$prod_query) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
        } 
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
	       if($_SESSION['rights'] == 'admin')	{	
            echo '<tr class="rows">
				<th>'.$prod_list['Product'].'</th>
                
				<th style="background:#8CC6FF"><form name="QtyFormOHD'.$prod_list['ID'].'" method="get" action="alterPaladinQty.php">
                <input type="hidden" name="identity" id="identity" value="OHD'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['On_Hand'].'">
                <input type="hidden" name="entered" id="entered" value="1">               
                <input type=submit value="-" id="OHD'.$prod_list['ID'].'" /></form>'.$prod_list['On_Hand'].
                
                '<form name="QtyFormOHI'.$prod_list['ID'].'" method="get" action="alterPaladinQty.php">
                <input type="hidden" name="identity" id="identity" value="OHI'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['On_Hand'].'">
                <input type="hidden" name="entered" id="entered" value="1">
                <input type=submit value="+" id="OHI'.$prod_list['ID'].'" /></form></th>
				
                
				<td>'.$prod_list['Price'].'</td>
				<td>'.$retail_value.'</td>
			</tr>';
           }
            else
            {
                echo '<tr class="rows">
				<th >'.$prod_list['Product'].'</th>'
                .'<th style="background:#8CC6FF">'.$prod_list['On_Hand'].'</th>                                 
				<td>'.$prod_list['Price'].'</td>
				<td>'.$retail_value.'</td>
			</tr>';
            }
		}
	}
	?>
</table>
	<h4 id="running_total">
		<?php if($_SESSION['rights'] == 'admin')	{ ?>
        <?php echo 'Total Inventory Value $'.number_format($runningTotal, 2); ?>
        <?php } ?>
	</h4>
<a href="javascript:void(0);" id="scroll" title="Scroll to Top" style="display: none;">Top<span></span></a>
</body>
<script type="text/javascript">
$(document).ready(function(){
    $(window).scroll(function(){
        if($(this).scrollTop() > 500){
            $('#scroll').fadeIn();
        }else{
            $('#scroll').fadeOut();
        }
    });
    $('#scroll').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 300);
        return false;
    });
});
</script>
</html>

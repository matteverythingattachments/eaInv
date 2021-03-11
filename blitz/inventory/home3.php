<?php
// Turn off all error reporting
error_reporting(0);
?>
<?php
session_set_cookie_params(31556926,"/");//one year in seconds
session_start();
include('admin/scripts/sess.php');
include('admin/scripts/access.php');
$runningTotal = 0;
$cat_query = mysqli_query($conn,"SELECT CATEGORY FROM categories ORDER BY ID ASC") or die(mysqli_error($conn));
$num_rows = mysqli_num_rows($cat_query);
$x=0;
while($cat_list = mysqli_fetch_array($cat_query))	{
	$category[$x] = $cat_list['CATEGORY'];
	$x++;
}
$x=0;

$nav_query = mysqli_query($conn,"SELECT * FROM categories ORDER BY CATEGORY ASC") or die(mysqli_error($conn));
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
	setInterval("refresher()", 120000);
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
#updated {
	position:fixed;
    color:#7C7A7A;
	left:10px;
	bottom:50;
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
<title>EA Inventory</title>
</head>

<body onLoad="autoload()">
    <div id="logout">
<?php 
    $adminTitle = '';
    if ($_SESSION['rights'] == 'admin') { $adminTitle = "Administrator"; } ?>
  <p>Welcome,  <?php echo $_SESSION['user'];echo '<br>'.$adminTitle;?></p>
  <div id="message"></div>
<p>
<a href="logout.php" class="btn btn-danger btn-sm">Logout</a><br><br><br>
<a href="paladin.php" class="btn btn-outline-secondary">Paladin Products</a>
</p>
<?php
	if($_SESSION['rights'] == 'admin')	{
        ?>

<p><a href="add_category.php" class="btn btn-outline-success">Add Categories</a></p>
<p><a href="add_product.php" class="btn btn-outline-success">Add Products</a></p>
   <?php } ?> 
        <p>
	<a href="printer_friendly.php" target="_blank" class="btn btn-outline-dark">
          <span class="glyphicon glyphicon-print"></span>Print </a>
	</p>
        <?php
	if($_SESSION['rights'] == 'admin')	{
        ?>
    <p><a href="stockReport.php" class="btn btn-success pull-right">Export OoS CSV</a></p>
    <?php } ?>
    <?php $date = date('M d, Y ').'<br>@'.date(" h:i a"); ?>
        <br><u>Last updated:</u><br><?php echo $date; ?>
</div>
<nav>
	<ul id="nav_list">
		<?php while($nav_list = mysqli_fetch_array($nav_query))	{
			echo '<li><a href="#'.$nav_list['ID'].'">'.$nav_list['CATEGORY'].'</a></li>
				';
			}?>
        
	</ul>
    
</nav>
            
    <p>
<table border="1" align="center" cellpadding="5" style="border-collapse:collapse; font-family:Verdana, Geneva, sans-serif; font-size:9pt; z-index:350; position:relative; top:55px;">
	<tr class="rows">
    	<th>Product</th>
        <th style="background:#FFFF8C">Yellow</th>
        <th style="background:#FFFFFF">Black</th>
        <th style="background:#9BFF9B">Green</th>
        <th style="background:#FFAD5B">Orange</th>
        <th style="background:#C4C4C4">Primer</th>
        <th>Price</th>
		<th>Total</th>
    </tr>
	<?php 
	while($x < $num_rows)	{ //REDO - DB must be perfectly sequential for this to work. If out of sequence, will not work
		$y = $x+1;
		echo '<tr>
			<th colspan="10" class="row_header"><a name="'.$y.'">'.$category[$x].'</a></th>
		</tr>
		';
		$x++;
		$prod_query = mysqli_query($conn, "SELECT * FROM ea_prods WHERE Cat_ID = '$x' ORDER BY Cat_ID, Product ASC");
		/* //DEBUG
        if (!$prod_query) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
        } */
        while($prod_list = mysqli_fetch_array($prod_query))	{ //echo "SELECT * FROM EA_PRODS WHERE Cat_ID = '$x' ORDER BY Cat_ID, Product ASC";
			if (($prod_list['Yellow'] > 0 ) || ($prod_list['Black'] > 0) || ($prod_list['Green'] > 0)
            || ($prod_list['Orange'] > 0) || ($prod_list['Primer'] > 0))
            {
				$retail_value = ($prod_list['Yellow'] + $prod_list['Black'] + $prod_list['Green'] +
                $prod_list['Orange'] + $prod_list['Primer']) * $prod_list['Price'];
				$wholesale_value = $retail_value * .65;
				$runningTotal = $runningTotal + ($retail_value);
            }
			else {
				$wholesale_value = 0;
                $retail_value = 0;
			}
	       if($_SESSION['rights'] == 'admin')	{	
            echo '<tr class="rows">
				<th><a href="prod_details.php?ID='.$prod_list['ID'].'">'.$prod_list['Product'].'</a></th>
                
				<th style="background:#FFFF8C"><form name="QtyFormYD'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="YD'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Yellow'].'">
                <input type="hidden" name="entered" id="entered" value="1">               
                <input type=submit value="-" id="YD'.$prod_list['ID'].'" /></form>'.$prod_list['Yellow'].
                
                '<form name="QtyFormYI'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="YI'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Yellow'].'">
                <input type="hidden" name="entered" id="entered" value="1">
                <input type=submit value="+" id="YI'.$prod_list['ID'].'" /></form></th>
				
				<th style="background:#FFFFFF"><form name="QtyFormBD'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="BD'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Black'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="-" id="BD'.$prod_list['ID'].'" /></form>'.$prod_list['Black'].'
                
                <form name="QtyFormBI'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="BI'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Black'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="+" id="BI'.$prod_list['ID'].'" /></form></th>
				
				<th style="background:#9BFF9B"><form name="QtyFormGD'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="GD'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Green'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="-" id="GD'.$prod_list['ID'].'" /></form>'.$prod_list['Green'].'
                
                <form name="QtyFormGI'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="GI'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Green'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="+" id="GI'.$prod_list['ID'].'" /></form></th>
                
				<th style="background:#FFAD5B"><form name="QtyFormOD'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="OD'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Orange'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="-" id="OD'.$prod_list['ID'].'" /></form>'.$prod_list['Orange'].'
                
                <form name="QtyFormOI'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="OI'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Orange'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="+" id="OI'.$prod_list['ID'].'" /></form></th>
                
				<th style="background:#C4C4C4"><form name="QtyFormPD'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="PD'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Primer'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="-" id="PD'.$prod_list['ID'].'" /></form>'.$prod_list['Primer'].'
                
                <form name="QtyFormPI'.$prod_list['ID'].'" method="get" action="alterQty.php">
                <input type="hidden" name="identity" id="identity" value="PI'.$prod_list['ID'].'">
                <input type="hidden" name="current" id="current" value="'.$prod_list['Primer'].'">
                <input type="hidden" name="entered" id="entered" value="1"> 
                <input type=submit value="+" id="PI'.$prod_list['ID'].'" /></form></th>
                
				<td>'.$prod_list['Price'].'</td>
				<td>'.$retail_value.'</td>
			</tr>';
           }
            else
            {
                echo '<tr class="rows">
				<th >'.$prod_list['Product'].'</th>'
                .'<th style="background:#FFFF8C">'.$prod_list['Yellow'].'</th>'
                .'<th style="background:#FFFFFF">'.$prod_list['Black'].'</th>'
                .'<th style="background:#9BFF9B">'.$prod_list['Green'].'</th>'
                .'<th style="background:#FFAD5B">'.$prod_list['Orange'].'</th>'
                .'<th style="background:#C4C4C4">'.$prod_list['Primer'].'</th>                                 
				<td>'.$prod_list['Price'].'</td>
				<td>'.$retail_value.'</td>
			</tr>';
            }
		}
	}
	?>
</table>
	
<?php if($_SESSION['rights'] == 'admin')	{ ?>
<h4 id="running_total">
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

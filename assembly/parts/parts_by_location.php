<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$locationQuery = mysqli_query($mysqli, "SELECT Location FROM parts GROUP BY Location") or die(mysqli_query($mysqli));

if(isset($_GET['location']))	{
	$inventoryQuery = mysqli_query($mysqli,"SELECT ID, Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, Location, round(MinQty/Qty, 3) AS Sev_In FROM parts WHERE Location = '$_GET[location]' ORDER BY Name") or die(mysqli_error($mysqli));
}
else	{
	$inventoryQuery = mysqli_query($mysqli,"SELECT ID, Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, Location, round(MinQty/Qty, 3) AS Sev_In FROM parts ORDER BY Qty, Sev_In");
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Inventory Levels</title>
<link href="../css/inv_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php echo $nav_menu;?>
<div id="sub_menu">
<form action="parts_by_location.php" method="get">

<select name="location">
<?php 
while($locationList = mysqli_fetch_array($locationQuery))	{
	echo '<option value="'.$locationList['Location'].'">'.$locationList['Location'].'</option>';
}
?>
</select>
<button type="submit" value="submit">Submit</button>
</form>
</div>
<table border="1" cellpadding="5" class="dataTable">
<tr>
    	<th>Name</th>
    	<th>Description</th>
    	<th>Thickness</th>
    	<th>Quantity</th>
    	<th>Minimum Quantity</th>
    	<th>Maximum Quantity</th>
        <th>Severity Index</th>
    	<th>Reorder Value</th>
    	<th>Location</th>
    </tr>
<?php while($invData = mysqli_fetch_array($inventoryQuery))	{
	if (!isset($invData['Sev_In']))	{
		$invData['Sev_In'] = 100;
	}
	
	if($invData['Sev_In'] < 0)	{
		$invData['Sev_In'] = 100;
	}
	
	if($invData['Sev_In'] > 1){
		$sevin = "severe";		
	}
	
	else	{
		$sevin = "normal";
	}
echo '	<tr class="'.$sevin.'">
	<td><a href="edit_part.php?ID='.$invData['ID'].'">'.$invData['Name'].'</a></td>
	<td>'.$invData['Description'].' '.$invData['Sev_In'].'</td>
	<td>'.$invData['Thickness'].'</td>
	<td>'.$invData['Qty'].'</td>
	<td>'.$invData['MinQty'].'</td>
	<td>'.$invData['MaxQty'].'</td>
	<td>'.$invData['Sev_In'].'</td>
	<td>'.$invData['ReorderValue'].'</td>
	<td>'.$invData['Location'].'</td>
</tr>		';
}?>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td valign="top"><h2>A</h2>
    <ul><?php $Aquery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'A%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Aquery);
while($thicknessMenu = mysqli_fetch_array($Aquery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>B</h2>
    <ul><?php $Bquery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'B%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Bquery);
while($thicknessMenu = mysqli_fetch_array($Bquery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>C</h2>
    <ul><?php $Cquery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'C%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Cquery);
while($thicknessMenu = mysqli_fetch_array($Cquery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>D</h2>
    <ul><?php $Dquery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'D%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Dquery);
while($thicknessMenu = mysqli_fetch_array($Dquery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>E</h2>
    <ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'E%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>F</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'F%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
  </tr>
  <tr>
    <td valign="top"><h2>G</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'G%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>H</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'H%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>I</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'I%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>J</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'J%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>K</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'K%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>L</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'L%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
  </tr>
  <tr>
    <td valign="top"><h2>M</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'M%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>N</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'N%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>O</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'O%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>P</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'P%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>Q</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'Q%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>R</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'R%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
  </tr>
  <tr>
    <td valign="top"><h2>S</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'S%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>T</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'T%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>U</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'U%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>V</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'V%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>W</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'W%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>X</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'X%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
  </tr>
  <tr>
    <td valign="top"><h2>Y</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'Y%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>Z</h2><ul><?php $Equery = mysqli_query($mysqli,"SELECT Location FROM parts WHERE Location LIKE 'Z%' GROUP BY Location ORDER BY Location");
	$x = mysqli_num_rows($Equery);
while($thicknessMenu = mysqli_fetch_array($Equery))	{
	echo '<li><a href="parts_by_location.php?location='.$thicknessMenu['Location'].'">'.$thicknessMenu['Location'].'</a> ';
	$x--;
	if ($x >= 1) echo '</li> 
	';
};?></ul></td>
    <td valign="top"><h2>&nbsp;</h2></td>
    <td valign="top"><h2>&nbsp;</h2></td>
    <td valign="top"><h2>&nbsp;</h2></td>
    <td valign="top"><h2>&nbsp;</h2></td>
  </tr>
</table>

</body>
</html>
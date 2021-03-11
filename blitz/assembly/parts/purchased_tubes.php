<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

//set variable for sorting feature
if(isset($_GET['sortOrder']))	{
$sortBy = $_GET['sortOrder'];
}
else $sortBy = 'Qty';

//Query all of the parts in that parts table
$partsQuery = mysqli_query($mysqli, "SELECT ID,Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, ReorderValue - Qty AS Num_To_Order, external_part_num FROM parts WHERE Qty > -100 AND purchased_part = 'y' ORDER BY $sortBy");
include('../components/html_header.php');

?>

<body>
<?php echo $nav_menu;?>
  <a href="tubes_by_qty.php">By Quantity</a> |
  <a href="purchased_tubes.php">Purchased Tubes</a>
<table border="1" cellpadding="5" class="dataTable">
	<tr>
    	<th>Part Name</th>
        <th>Description</th>
        <th>Thickness</th>
        <th>Quantity</th>
        <th>Minimum Quantity</th>
        <th>Maximum Quantity</th>
        <th>Quantity Needed</th>
        <th>External Part Number</th>
    </tr>
<?php
	while($partsArray = mysqli_fetch_array($partsQuery))	{
		echo "<tr>
			<th><a href='edit_part.php?ID=$partsArray[ID]'>$partsArray[Name]</th>
			<td>$partsArray[Description]</td>
			<td>$partsArray[Thickness]</td>
			<td>$partsArray[Qty]</td>
			<td>$partsArray[MinQty]</td>
			<td>$partsArray[MaxQty]</td>
			<td>$partsArray[Num_To_Order]</td>
			<td>$partsArray[external_part_num]</td>";
	}
?>
</table>
</body>
</html>
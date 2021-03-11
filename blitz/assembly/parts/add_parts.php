<?php
session_start();
error_reporting(0);
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
include('../admin/resize-class.php');

$partID = time();

if(isset($_POST['partName']))	{
	//query to make sure same part number is not already in the database.
	if($_POST['partName'] == '')	{
		die('Part Name Cannot Be Blank');
	}
	$preCheckQuery = mysqli_query($mysqli, "SELECT * FROM parts WHERE Name = '$_POST[partName]'");
	if(mysqli_num_rows($preCheckQuery) > 0)	{
		die('<h1>Part Name is already in database.</h1>');
	}
	
	$istube = 'n';
  $ispurchased = 'n';
	
	if($_POST['is_tube'] == "yes") {$istube = 'y';}
	if($_POST['is_purchased'] == "yes") {$ispurchased = 'y';}

		mysqli_query($mysqli, "INSERT INTO parts VALUES
		('$partID','$_POST[partName]','$_POST[partDescription]','$_POST[thickness]','$_POST[Qty]','$_POST[minQty]','$_POST[maxQty]','$_POST[reorderValue]','$_POST[storageLocation]','$istube','$ispurchased','$_POST[external_part_num]','$is_bolt')") or die(mysqli_error($mysqli));
		
		$newPartName = $_POST['partName'];
		echo $newPartName;

	@move_uploaded_file( $_FILES['new_product_image']['tmp_name'], '../img/tmp/'.$partID.'.jpg');
	
	$resizeObj = new resize('../img/tmp/'.$partID.'.jpg');
	$resizeObj -> resizeImage(800, 450, 'auto');
	$resizeObj -> saveImage('../img/parts/'.$partID.'.jpg', 100);
    
    $resizeObj = new resize('../img/tmp/'.$partID.'.jpg');
	$resizeObj -> resizeImage(200, 127, 'auto');
	$resizeObj -> saveImage('../img/parts/thumbs/'.$partID.'Th.jpg', 100);
    
    }


	include('../components/html_header.php');
?>
<body>
<?php echo $nav_menu;
if(isset($warning))	{
	echo $warning;
}?>
<form action="add_parts.php" method="post" enctype="multipart/form-data">
<table>
	<tr>
    	<td>Part Name</td>
    	<td><input name="partName"></td>
    </tr>
    <tr>
    	<td>Part Description</td>
        <td><input name="partDescription"></td>
    </tr>
    <tr>
    	<td>Thickness</td>
        <td><input name="thickness"></td>
    </tr>
    <tr>
    	<td>Quantity</td>
        <td><input name="Qty"></td>
    </tr>
    <tr>
    	<td>Minimum Quantity</td>
        <td><input name="minQty"></td>
    </tr>
    <tr>
    	<td>Maximum Quantity</td>
        <td><input name="maxQty"></td>
    </tr>
    <tr>
    	<td>Reorder Value</td>
        <td><input name="reorderValue"></td>
    </tr>
    <tr>
    	<td>Storage Location</td>
        <td><input name="storageLocation"></td>
    </tr>
   <tr>
    	<td>Product Image</td>
        <td><input type="file" name="new_product_image" id="new_product_image"></td>
    </tr> 
	<tr>
		<td>Tube?</td>
		<td><input type="checkbox" name="is_tube" value="yes"></td>
	</tr>
  <tr>
		<td>Purchased?</td>
		<td><input type="checkbox" name="is_purchased" value="yes"></td>
	</tr>
  <tr>
		<td>Is Bolt?</td>
		<td><input type="checkbox" name="is_bolt" value="y"></td>
	</tr>
  <tr>
    	<td>External Part Number</td>
        <td><input name="external_part_num"></td>
    </tr><tr>
    	<td colspan="2"><button type="submit">Enter</button></td>
    </tr>
</table>
</form>
</body>
</html>
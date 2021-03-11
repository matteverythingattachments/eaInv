<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$assemblyID = time();

if(isset($_POST['assemblyName']))	{
	$preCheckQuery = mysqli_query($mysqli,"SELECT * FROM assemblies WHERE name = '$_POST[assemblyName]'");
	if(mysqli_num_rows($preCheckQuery) > 0)	{
		die('Assembly name is already in the database.');
	}
	
	else	{
		mysqli_query($mysqli, "INSERT INTO assemblies VALUES
		('$assemblyID','$_POST[assemblyName]','$_POST[assemblyDescription]','$_POST[Qty]','$_POST[MinQty]','$_POST[MaxQty]','$_POST[ReorderValue]','$_POST[Location]','$_POST[Category]')") or die(mysqli_error($mysqli));
		
				$newPartName = $_POST['assemblyName'];

	@move_uploaded_file( $_FILES['new_assembly_pic']['tmp_name'], '../img/assemblies/'.$newPartName.'.jpg');
	}

}

$category_query = mysqli_query($mysqli, "SELECT * FROM categories ORDER BY Name") or die(mysqli_error($mysqli));

include('../components/html_header.php');
?>

<body>
		<?php 
echo "Logged In As $_SESSION[ROLE] $_SESSION[userName]";
echo $nav_menu;
	?>
<form action="add_assembly.php" method="post" enctype="multipart/form-data">
<table border="0">
	<tr>
    	<td>Assembly Name</td>
        <td><input name="assemblyName"></td>
    </tr>
	<tr>
    	<td>Assembly Description</td>
        <td><input name="assemblyDescription"></td>
    </tr>
    <tr>
	<tr>
    	<td>Quantity</td>
        <td><input name="Qty"></td>
    </tr>
	<tr>
    	<td>Minimum Quantity</td>
        <td><input name="MinQty"></td>
    </tr>
 	<tr>
    	<td>Maximum Quantity</td>
        <td><input name="MaxQty"></td>
    </tr>
	<tr>
    	<td>Reorder Value</td>
        <td><input name="ReorderValue"></td>
    </tr>
	<tr>
    	<td>Location</td>
        <td><input name="Location"></td>
    </tr>
    <tr>
    	<td><input type="file" name="new_assembly_pic"></td>
    </tr>
	<tr>
		<td>
			<?php
				while($cat_options = mysqli_fetch_array($category_query)) {
		echo '<input type="radio" name="Category" value="'.$cat_options['ID'].'">'.$cat_options['Name'].'<br>';
	}
			?>
		</td>
   	<td colspan="2"><button type="submit">Enter</button></td>
    </tr>
</form>
</body>
</html>
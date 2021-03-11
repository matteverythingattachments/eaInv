<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$currentAssemblyQuery = mysqli_query($mysqli, "SELECT * FROM assemblies ORDER BY Name");
	
$category_query = mysqli_query($mysqli, "SELECT * FROM categories ORDER BY Name");

include('../components/html_header.php');

?>


<body>
<?php echo $nav_menu;?>
<ul>
	<li><a href="add_assembly.php">Add Assembly</a></li>
	<li><a href="clone_assembly.php">Clone Assembly</a></li>
</ul>
	<ul id="assembly_category_list">
		<?php
		while($cat_list = mysqli_fetch_array($category_query)) {
			echo '<li><a href="assembly_category.php?ID=';
			echo $cat_list['ID'];
			echo '">';
			echo $cat_list['Name'];
			echo '</a></li>';
		}
	?>
	</ul>
	
<table border="1" cellpadding="5" class="dataTable">
	<tr>
    	<th>Name</th>
        <th>Description</th>
				<th>&nbsp;</th>
    </tr>
    <?php while($assemblyList = mysqli_fetch_array($currentAssemblyQuery))	{
			echo '<tr>
				<td><a href="build_assembly.php?ID='.$assemblyList['ID'].'">'.$assemblyList['Name'].'</a> <a class="blue_link" href="edit_assembly.php?ID='.$assemblyList['ID'].'">Edit</a></td>
				<td>'.$assemblyList['Description'].'</td>
				<td><a href="clone.php?ID='.$assemblyList['ID'].'">Clone</a></td>
		</tr>
		';
		}?> 
</table>
</body>
</html>
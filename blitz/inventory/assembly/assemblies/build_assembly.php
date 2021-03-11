<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
//pull general information about the assembly
if(isset($_GET['assemblyID']))	{
	$assemblyInfoQuery = mysqli_query($mysqli, "SELECT ID, Name FROM assemblies WHERE ID = $_GET[assemblyID]");
		if(mysqli_num_rows($assemblyInfoQuery) != 1)	{
			die('Error - Assembly not found');
		}
}
//get part id number based on part name.
if(isset($_GET['part_num']))	{
	$partIDQuery = mysqli_query($mysqli,"SELECT * FROM parts WHERE Name = '$_GET[part_num]';") or die(mysqli_error($mysqli));
	$partID = mysqli_fetch_array($partIDQuery);
	if($partID['ID'] == '')	{
		die('Part Does Not Exist');
	}
}

//double check that the part isn't already assigned to the assembly before adding it to the assembly
if(isset($_GET['part_num']))	{		
	$partCheck = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE partID = '$partID[ID]' AND assemblyID = '$_GET[ID]' AND Type = 'P'") or die(mysqli_error($mysqli));	
	if(mysqli_num_rows($partCheck) > 0)	{
		die('Part already assigned to this assembly');
	}
}

if(isset($_GET['subassembly_num']))	{		
	$subassemblyCheck = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE partID = $_GET[subassembly_num] AND assemblyID = $_GET[ID] AND Type = 'SA'") or die(mysql_error($mysqli));	
	if(mysqli_num_rows($subassemblyCheck) > 0)	{
		die('Subassembly already assigned to this assembly');
	}
}

// check the task parameter to make sure the appropriate actions are taken

	if(!isset($_GET['task']))	{
		$_GET['task'] = '';
	}

	if($_GET['task'] == '')	{
	}

	if($_GET['task'] == 'addPart')	{
	mysqli_query($mysqli,"INSERT INTO assembly_build (partID,assemblyID,Qty,Type,position) VALUES(
	'$partID[ID]','$_GET[ID]','$_GET[qty]','P','0')") or die(mysqli_error($mysqli));
	getBlaster();
	
	}
	
		if($_GET['task'] == 'addSubassembly')	{
	mysqli_query($mysqli,"INSERT INTO assembly_build VALUES(
	'','$_GET[subassembly_num]','$_GET[ID]','$_GET[qty]','SA')");
	getBlaster();
	}

	
	if($_GET['task'] == 'build')	{
		$multiplier = $_GET['buildQty'];
		//pull list of all parts in assembly for table update.
		$buildListQuery = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE assemblyID = $_GET[ID]") or die(mysqli_error($mysqli));
		//loop through the update process. 
		while($buildList = mysqli_fetch_array($buildListQuery))	{
			mysqli_query($mysqli, "UPDATE parts SET Qty = Qty-($multiplier * $buildList[Qty]) where ID = '$buildList[partID]'");
		}
			mysqli_query($mysqli, "UPDATE assemblies SET Qty = Qty + $multiplier WHERE ID = $_GET[ID]");
			getBlaster();
	}
	

	$assemblyInfoQuery = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE ID = '$_GET[ID]'");
	$assemblyData = mysqli_fetch_array($assemblyInfoQuery);
	$queryString = "SELECT parts.ID, Name, Description, Thickness, Location, Position, assembly_build.ID AS assemblyBuildID, assembly_build.Qty, parts.Qty AS 'onHand', Type FROM assembly_build JOIN parts ON assembly_build.partID = parts.ID WHERE assembly_build.assemblyID = $_GET[ID] AND Type = 'P' ORDER BY Position";
	$buildQuery = mysqli_query($mysqli, $queryString) or die(mysqli_error($mysqli));


	$saQueryString = "SELECT subassemblies.ID, Name, Description, Location, assembly_build.Qty, subassemblies.Qty AS 'onHand', Type FROM assembly_build JOIN subassemblies ON assembly_build.partID = subassemblies.ID WHERE assembly_build.assemblyID = $_GET[ID] AND Type = 'SA'";
	$saBuildQuery = mysqli_query($mysqli, $saQueryString) or die(mysqli_error($mysqli));

	
	$partsQuery = mysqli_query($mysqli, "SELECT * FROM parts ORDER BY Name");
	$subassemblyQuery = mysqli_query($mysqli,"SELECT * FROM subassemblies ORDER BY Name");

//counter for item number
$x=1;
include('../components/html_header.php');
?>

<body>
<div id="content_wrapper">
<?php 
	echo $nav_menu;
?>
<h1><?php echo $assemblyData['Name'];?></h1>
<p><?php echo $assemblyData['Description']; ?></p>
<p><a onclick="viewImage('assembly_image')">View Assembly</a>
<div id="assembly_image">
<img style="border: solid thin black; margin-top:5px;" src="../img/assemblies/<?php echo $assemblyData['Name']?>.jpg">
<a onClick="javascript:closeImage('assembly_image')">Close Image</a>
</div>

<!-- <h2>On Hand: <?php  echo $assemblyData['Qty'];?></h2>-->
<form action="build_assembly.php" method="get">
<input type="hidden" name="ID" value="<?php echo $_GET['ID'];?>">
<input type="hidden" name="task" value="build">
<table border="0" cellpadding="5">
	<tr>
    	<td colspan="2">Build Assembly </td>
    </tr>
    <tr>
    	<td>Quantity: <input name="buildQty"></td>
        <td><button type="submit">Build</button></td>
    </tr>
</table>
</form>

<table border="1" cellpadding="5" class="dataTable">
    <tr>
    	<th>Item No.</th>
    	<th>Part Name</th>
      <th>Qty</th>
      <th>On Hand</th>
      <th>Part Description</th>
      <th>Thickness</th>
      <th>Location</th>
    </tr>
<?php
while($buildSheet = mysqli_fetch_array($buildQuery))	{
	if($buildSheet['Qty'] > $buildSheet['onHand'])	{
		$class = 'severe';
	}
	else $class = 'normal';
	
	//if($buildSheet['position'] == 0) {
	//	mysqli_query($mysqli, "UPDATE assembly_build SET assembly_build.position = $x WHERE ID = $buildSheet[assemblyBuildID]") or die(mysqli_error($mysqli));
	//}
echo'    <tr class="'.$class.'">
	<td>'.$buildSheet['Position'].' <button onclick="javascript:showPart(\''.$buildSheet['Name'].'\')">View</button></td>
	<td><a href="../parts/edit_part.php?ID='.$buildSheet['ID'].'">'.$buildSheet['Name'].'</a></td>
	<td>'.$buildSheet['Qty'].' <!--<a href="edit_quantity.php?ID='.str_replace(' ','',$buildSheet['assemblyBuildID']).'">Edit</a>--></td>
	<td>'.$buildSheet['onHand'].'</td>
	<td>'.$buildSheet['Description'].'</td>
	<td>'.$buildSheet['Thickness'].'</td>
	<td>'.$buildSheet['Location'].'</td>
	<td><a href="edit_position.php?partid='.$buildSheet['ID'].'&assemblyid='.$_GET['ID'].'">Edit</a></td>
	<td><a href="delete_assoc.php?partid='.$buildSheet['ID'].'&assemblyid='.$_GET['ID'].'">Delete</a></td>
    </tr>
	<div id="'.$buildSheet['Name'].'" style="display:none; position:absolute; left:50%; margin-left:-400px; width:800px; height:auto; background-color:#000;"><img src="../img/parts/'.$buildSheet['ID'].'.jpg"><button style="margin-left:10px; margin-bottom:10px; "onClick="javascript:closePart(\''.$buildSheet['Name'].'\')">Close</button>';
$x++;
}

while($saBuildSheet = mysqli_fetch_array($saBuildQuery))	{
	if($saBuildSheet['Qty'] > $saBuildSheet['onHand'])	{
		$class = 'severe';
	}
	else $class = 'normal';
echo'    <tr class="'.$class.'">
	<td>'.$x.'</td>
	<td><a href="../parts/edit_part.php?ID='.$saBuildSheet['ID'].'">'.$saBuildSheet['Name'].'</a></td>
	<td>'.$saBuildSheet['Description'].'</td>
	<td>N/A</td>
	<td>'.$saBuildSheet['Qty'].'</td>
	<td>'.$saBuildSheet['onHand'].'</td>
	<td>'.$saBuildSheet['Location'].'</td>
	<td>'.$saBuildSheet['Type'].'</td>
    </tr>
';
$x++;
}

?>
</table>
<!--<img style="border: solid thin black; margin-top:5px;" src="../img/assemblies/<?php echo $assemblyData['Name']?>.jpg">-->

<form enctype="multipart/form-data" action="build_assembly.php" method="get">
<input type="hidden" name="task" value="addPart">
<input type="hidden" name="ID" value="<?php echo $_GET['ID']?>">
	<table border="0">
  <tr>
        	<td>
            
Part Number: <input name="part_num" tabindex="1">
            	<!-- <input name="part_num">
                	<?php /* while($parts = mysqli_fetch_array($partsQuery))	{
						echo '<option value="'.$parts['ID'].'">'.$parts['Name'].' | '.$parts['Description'].'</option>
						';
					} */?>
                </select> -->
               Qty: <input name="qty" tabindex="2">
                <button type="submit">Add To Assembly</button>
            </td>
        </table>
</form>

<form enctype="multipart/form-data" action="build_assembly.php" method="get">
<input type="hidden" name="task" value="addSubassembly">
<input type="hidden" name="ID" value="<?php echo $_GET['ID']?>">
	<table border="0">
  <tr>
        	<td>
            	<select name="subassembly_num">
                	<?php while($subassemblies = mysqli_fetch_array($subassemblyQuery))	{
						echo '<option value="'.$subassemblies['ID'].'">'.$subassemblies['Name'].' | '.$subassemblies['Description'].'</option>
						';
					}?>
                </select>
                <input name="qty">
                <button type="submit">Add To Assembly</button>
            </td>
        </table>
</form>
</div>
<div id="shade">
</div>

</body>
</html>

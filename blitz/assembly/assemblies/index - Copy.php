<?php
////session_set_cookie_params(31556926,"/");//one year in seconds
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$currentAssemblyQuery = mysqli_query($mysqli, "SELECT * FROM assemblies ORDER BY Name");
	
$category_query = mysqli_query($mysqli, "SELECT * FROM categories ORDER BY Name");

include('../components/html_header.php');

?>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

    function processChange(id){
        //console.log(itemstring);
        var val = $('#bQty'+id).val();
        var name = $('#bName'+id).val();
        var itemstring = 'ID='+id+'&qty='+val+'&bName='+name;
        //alert(itemstring);
        //return false;
        $.ajax({
            type: "GET",
            url: "update.php",
            data: itemstring,
            complete: function(data) {
                var Resp = data.responseText;
                //console.log(Resp);
            },
            success: function() {
                var newText = name + ' Build Qty Updated to:' + val;
                $('#saved').text(newText);
                $('#saved').css('display', 'block');

                setTimeout(function() {
                    $('#saved').animate({
                        opacity: 0,
                    }, 500, function(){
                        $('#saved').css('display', 'none').css('opacity', '1');
                    });
                }, 2000);
            }
        });
    }
   function processBuild(id){
        //console.log(itemstring);
        var val = $('#aQty'+id).val();
        var name = $('#bName'+id).val();
        var itemstring = 'ID='+id+'&qty='+val+'&bName='+name;
       
        //alert(itemstring);
        return false;
        $.ajax({
            type: "GET",
            url: "updateActual.php",
            data: itemstring,
            complete: function(data) {
                var Resp = data.responseText;
                //console.log(Resp);
            },
            success: function() {
                var newText = name + ' Actual Qty Updated to:' + val;
                $('#savedBuild').text(newText);
                $('#savedBuild').css('display', 'block');

                setTimeout(function() {
                    $('#savedBuild').animate({
                        opacity: 0,
                    }, 1000, function(){
                        $('#savedBuild').css('display', 'none').css('opacity', '1');
                    });
                }, 2000);
            }
        });
    }


    
</script>
</head>
<body>
<div style="color:blue;background:white;padding: 20px;line-height: 24px;font-weight:bold;font-size:16px;position:fixed; top: 20px; left: 50px;" id="saved"></div>
<div style="color:#CD4FB0;background:white;padding: 20px;line-height: 24px;font-weight:bold;font-size:16px;position:fixed; top: 20px; left: 50px;" id="savedBuild"></div>
<div style="color:red;background:white;padding: 20px;line-height: 24px;font-weight:bold;font-size:16px;position:fixed; top: 150px; left: 50px;" id="error"></div>
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
        <th>Actual Qty</th>
        <th>Description</th>
        <th>Build Qty</th>
        <th>Qty</th>
        <th>MinQty</th>
        <th>MaxQty</th>
        <th>Reorder Value</th>
        <th>Location</th>
    </tr>
    <?php
    if ($_SESSION['role'] == 'admin')
    {   
    ?>
    <?php while($assemblyList = mysqli_fetch_array($currentAssemblyQuery))	{
			echo '<tr>
				<input  type="hidden" id="bID" value="'.$assemblyList['ID'].'">
                <input  type="hidden" id="bName'.$assemblyList['ID'].'" value="'.$assemblyList['Name'].'">
                <td><a href="build_assembly.php?ID='.$assemblyList['ID'].'">'.$assemblyList['Name'].'</a> <a class="blue_link" href="edit_assembly.php?ID='.$assemblyList['ID'].'">Edit</a></td>
                
                <td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" id="aQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['ActualQty'].'" onchange="processBuild('.$assemblyList['ID'].')"></td> 
                
				<td>'.$assemblyList['Description'].'</td>
                <td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" id="bQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['BuildQty'].'" onchange="processChange('.$assemblyList['ID'].')"></td>    
				<td>'.$assemblyList['Qty'].'</td>
				<td>'.$assemblyList['MinQty'].'</td>
				<td>'.$assemblyList['MaxQty'].'</td>
				<td>'.$assemblyList['ReorderValue'].'</td>
				<td>'.$assemblyList['Location'].'</td>
                </tr>';
		}
    ?>
    <?php
    }
    else
    {
    ?>    
    <?php
        while($assemblyList = mysqli_fetch_array($currentAssemblyQuery))	{
			echo '<tr>
				<input  type="hidden" id="bID" value="'.$assemblyList['ID'].'">
                <input  type="hidden" id="bName'.$assemblyList['ID'].'" value="'.$assemblyList['Name'].'">
                <td><a href="build_assembly.php?ID='.$assemblyList['ID'].'">'.$assemblyList['Name'].'</a> <a class="blue_link" href="edit_assembly.php?ID='.$assemblyList['ID'].'">Edit</a></td>
                <td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" id="aQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['ActualQty'].'" onchange="processBuild('.$assemblyList['ID'].')"></td> 
				<td>'.$assemblyList['Description'].'</td>';
                if ($assemblyList['BuildQty'] > 0) {
                echo '<td style="font-weight:bold;color:red;">'.$assemblyList['BuildQty'].'</td>';
                }
                else
                {
                     echo '<td>'.$assemblyList['BuildQty'].'</td>';   
                }
				echo '<td>'.$assemblyList['Qty'].'</td>
				<td>'.$assemblyList['MinQty'].'</td>
				<td>'.$assemblyList['MaxQty'].'</td>
				<td>'.$assemblyList['ReorderValue'].'</td>
				<td>'.$assemblyList['Location'].'</td></tr>';
         }
    }
    ?>
</table>
</body>
</html>
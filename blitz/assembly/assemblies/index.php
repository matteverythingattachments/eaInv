<?php
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

    function processChange(id, currentBuildVal){// ActualQty from Chuck*****************
        //console.log(itemstring);
        var val = parseInt($('#aQty'+id).val(),10);
        var name = $('#bName'+id).val();
        var itemstring = 'ID='+id+'&qty='+val+'&bName='+name;
        //alert(itemstring);
        var temp = parseInt($('#bQ'+id).html(),10);
        //alert('temp:' + temp + ' val:' +val);
        if (val <= 0)
        { 
            $('#aQty'+id).val('0');
            return false;       
        }
        if  (val > temp)//6 > 11
        { 
            $('#aQty'+id).val('0');
            return false;       
        }
        //return false;
        $.ajax({
            type: "GET",
            url: "update.php",
            data: itemstring,
            complete: function(data) {
            var Resp = data.responseText;
            //alert(Resp);
            //alert(itemstring);
            },
            success: function() {
                var newText = name + ' Actual Qty Updated to:' + val;
                $('#saved').text(newText);
                $('#saved').css('display', 'block');
                
                var updateBQty = 0;
                var currentOnPage = $('#bQ'+id).html();
                updateBQty = currentOnPage - val;
                //updateBQty = currentBuildVal - val;
                //alert("current: " + currentBuildVal + " AQty:" + updateBQty);
                //if (isNaN(updateBQty)) { updateBQty = 0; }
                $('#bQ'+id).html(updateBQty);
                if (updateBQty <= 0)
                {
                    $('#aQty'+id).css('background-color', '');
                    $('#aQty'+id).prop('disabled', true);
                    $('#bQ'+id).css('color', '');
                    $('#bQ'+id).css({ 'font-weight': 'normal' });
                    
                }
                $('#aQty'+id).val('0');
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

    function updateData(id) {
        var html = $.ajax({
        type: "GET",
        url: "response.php",
        data: {ID: id},
        async: false // <-- heres the key !
        }).responseText;
        return html;
        }
   function processBuild(id,currentBuildVal){ //currentBuildVal is the current DB BuildQty for tracking purposes
        //console.log(itemstring); 
       var val = 0;
       var name = '';
       val = $('#bQty'+id).val();
       
       name = $('#bName'+id).val();
        //This calculation will take the actual difference between numbers instead of changing qty by the entire db number
       var qtyFactor = 0;
              $.ajaxSetup ({
        cache: false
        });
       $previousVal = updateData(id);
       //alert(val + ":" + currentBuildVal + " prev:" + $previousVal); //entered then current
       currentBuildVal = $previousVal;
       if (val >= currentBuildVal)//4 5
       {
           qtyFactor = val - currentBuildVal;
       }
       else if (val < currentBuildVal)//1 2
       {
           qtyFactor = val - currentBuildVal;// 12 - 454
       }
       else if (currentBuildVal == 0) // 5 5
       {
           qtyFactor = val; 
           return;
       }
       //alert(qtyFactor); //DEBUG POINT

        var itemstring = 'ID='+id+'&qty='+val+'&bName='+name+'&qtyFactor='+qtyFactor;
       
        //alert(itemstring);
        $.ajax({
            type: "GET",
            url: "updateActual.php",
            data: itemstring,
            complete: function(data) {
                var Resp = data.responseText;
                //console.log(Resp);
            },
            success: function() {
                var newText = name + ' Build Qty Updated to:' + val;
                $('#savedBuild').text(newText);
                $('#savedBuild').css('display', 'block');
                //$('#bQty'+id).val();
                var q = $('#overAllQty'+id).text(); //q = current db value, val= entered val
                var finalQty = parseInt(q) + parseInt(qtyFactor);  

                $('#overAllQty'+id).text(finalQty);
                //setInterval('location.reload()', 0); 
                setTimeout(function() {
                    $('#savedBuild').animate({
                        opacity: 0,
                    }, 1000, function(){
                        $('#savedBuild').css('display', 'none').css('opacity', '1');
                    });
                }, 2000);
                //location.reload();
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
                <td><a href="build_assembly.php?ID='.$assemblyList['ID'].'">'.$assemblyList['Name'].'</a> <a class="blue_link" href="edit_assembly.php?ID='.$assemblyList['ID'].'">Edit</a></td>';
                
                if ($assemblyList['BuildQty'] <= 0)
                {
                 echo '<td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" id="aQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['ActualQty'].'" disabled )"></td>';
                }
                else
                {
                   echo '<td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" style="background-color:#FFFF8C;" id="aQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['ActualQty'].'" onchange="processChange('.$assemblyList['ID'].','.$assemblyList['BuildQty'].')"></td>';
                }
                
				echo '<td>'.$assemblyList['Description'].'</td>
                <td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" id="bQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['BuildQty'].'" onchange="processBuild('.$assemblyList['ID'].','.$assemblyList['BuildQty'].')"></td>    
				<td id="overAllQty'.$assemblyList['ID'].'">'.$assemblyList['Qty'].'</td>
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
				<input  type="hidden" id="aID" value="'.$assemblyList['ID'].'">
                <input  type="hidden" id="bName'.$assemblyList['ID'].'" value="'.$assemblyList['Name'].'">
                <td><a href="build_assembly.php?ID='.$assemblyList['ID'].'">'.$assemblyList['Name'].'</a> <a class="blue_link" href="edit_assembly.php?ID='.$assemblyList['ID'].'">Edit</a></td>';
                if ($assemblyList['BuildQty'] <= 0)
                {
                 echo '<td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" id="aQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['ActualQty'].'" disabled )"></td>';
                }
                else
                {
                   echo '<td style="text-align:center;font-size:24px;font-style:bold;"><input type="text" style="background-color:#FFFF8C;" id="aQty'.$assemblyList['ID'].'" size="2" value="'.$assemblyList['ActualQty'].'" onchange="processChange('.$assemblyList['ID'].','.$assemblyList['BuildQty'].')"></td>';
                }
            
				echo '<td>'.$assemblyList['Description'].'</td>';
                if ($assemblyList['BuildQty'] > 0) {
                echo '<td id="bQ'.$assemblyList['ID'].'" style="font-weight:bold;color:red;">'.$assemblyList['BuildQty'].'</td>';
                }
                else
                {
                     echo '<td id="bQ'.$assemblyList['ID'].'">'.$assemblyList['BuildQty'].'</td>';   
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
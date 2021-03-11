<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');
include('../components/html_header.php');

$partNames = mysqli_query($mysqli,"SELECT * FROM parts ORDER BY Name") or die(mysqli_error($mysqli));

while($names = mysqli_fetch_array($partNames)) {
	//echo $names['ID'].' ';
	if(file_exists('../img/parts/'.$names['Name'].'.jpg')){
		
	}
	else echo('<a href="edit_part.php?ID='.$names['ID'].'">'.$names['Name']. '</a><br>');
}
?>
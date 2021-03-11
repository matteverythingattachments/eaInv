<?php
session_start();
include('../../admin/scripts/auth_check.php');
include('../../admin/scripts/access.php');

$partNames = mysqli_query($mysqli,"SELECT * FROM parts ORDER BY Name") or die(mysqli_error($mysqli));

while($names = mysqli_fetch_array($partNames)) {
	//echo $names['ID'].' ';
	if(file_exists($names['Name'].'.jpg')){
	rename("$names[Name].jpg", "$names[ID].jpg");
	}
	//else die("$names[Name] doesn't exist");
}

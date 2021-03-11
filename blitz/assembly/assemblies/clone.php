<?php
//error_reporting(0);
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$IDtoClone = $_GET['ID'];

$newAssemblyID = time(); //1594903094 //Auto 1588169552

$clone_query = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE ID = $IDtoClone");

$clone_info = mysqli_fetch_array($clone_query);

$exist_check_query = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE Name = '$clone_info[Name]-clone'");

$exist_returns = mysqli_num_rows($exist_check_query);

if($exist_returns != 0) {
  die('<h1>Part Already Cloned');
}
mysqli_query($mysqli, "INSERT INTO assemblies (Name,Description,Qty,MinQty,MaxQty,ReorderValue, Location, category) VALUES('$clone_info[Name]-clone','$clone_info[Description]',0,0,0,0,'CLONE','$clone_info[category]')") or die(mysqli_error($mysqli));

//Clone fix...query assemblies for last Identity value
$last_id = mysqli_insert_id($mysqli);
$newAssemblyID = $last_id;
//echo 'ID:'.$last_id;
$association_query = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE assemblyID = $IDtoClone");

while($association_add = mysqli_fetch_array($association_query)) {
  //echo $association_add[Qty];
  mysqli_query($mysqli, "INSERT INTO assembly_build (partID,assemblyID,Qty,Type,position) VALUES($association_add[partID],$newAssemblyID,$association_add[Qty],'$association_add[Type]',$association_add[position])") or die(mysqli_error($mysqli)) ;
}

copy("../img/assemblies/$clone_info[Name].jpg", "../img/assemblies/$clone_info[Name]-clone.jpg");

header("Location:build_assembly.php?ID=$newAssemblyID");
?>
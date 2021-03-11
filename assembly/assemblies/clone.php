<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/access.php');
include('../admin/scripts/components.php');

$IDtoClone = $_GET['ID'];

$newAssemblyID = time();

$clone_query = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE ID = $IDtoClone");

$clone_info = mysqli_fetch_array($clone_query);

$exist_check_query = mysqli_query($mysqli, "SELECT * FROM assemblies WHERE Name = '$clone_info[Name]-clone'");

$exist_returns = mysqli_num_rows($exist_check_query);

if($exist_returns != 0) {
  die('<h1>Part Already Cloned');
}

mysqli_query($mysqli, "INSERT INTO assemblies VALUES('$newAssemblyID','$clone_info[Name]-clone','$clone_info[Description]','','','','','CLONE','$clone_info[category]')") or die(mysqli_error($myqli));

$association_query = mysqli_query($mysqli, "SELECT * FROM assembly_build WHERE assemblyID = $IDtoClone");

while($association_add = mysqli_fetch_array($association_query)) {
  //echo $association_add[partID];
  mysqli_query($mysqli, "INSERT INTO assembly_build VALUES('','$association_add[partID]','$newAssemblyID','$association_add[Qty]','$association_add[Type]','$association_add[position]')") or die(mysqli_error($mysqli)) ;
}

copy("../img/assemblies/$clone_info[Name].jpg", "../img/assemblies/$clone_info[Name]-clone.jpg");

header("Location:build_assembly.php?ID=$newAssemblyID");
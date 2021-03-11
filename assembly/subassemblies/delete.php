<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/components.php');
include('../admin/scripts/access.php');


if(isset($_GET['ID']))	{
	mysqli_query($mysqli, "DELETE FROM subassembly_build WHERE ID = $_GET[ID]");
	header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>

<?php
if(!isset($_SESSION['user']))	{
	header("Location:https://groundbraker.com/blitz/inventory/logout.php");
    //die('You Must Be Logged In');
}
?>
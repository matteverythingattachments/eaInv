<?php
if(!isset($_SESSION['user']))	{
	die(header("Location:../../index.php"));
    //die('You Must Be Logged In');
}
?>
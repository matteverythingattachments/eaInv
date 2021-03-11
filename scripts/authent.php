<?php
if(!isset($_SESSION['user']))	{
	header("Location:../");
    //die('You Must Be Logged In');
}
?>
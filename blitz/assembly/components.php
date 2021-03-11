<?php
$web_address = 'https://groundbraker.com/blitz/assembly';
$nav_menu =  '<div id="navi"><a href="'.$web_address.'">Home</a> | <a href="'.$web_address.'/parts">Parts</a> | <a href="'.$web_address.'/assemblies">Assemblies</a> | <a href="'.$web_address.'/subassemblies">Sub-Assemblies</a> | <a href="'.$web_address.'/logout.php">Logout</a><br><a href="./">Category Home</a></div>';




// functions go here
function getBlaster()	{
	$fileName = basename($_SERVER['SCRIPT_FILENAME']);
	header("Location:$fileName?ID=$_GET[ID]");

}?>
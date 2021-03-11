<?php

$web_address = 'http://localhost/groundbraker/blitz/assembly';
// $web_address = 'https://groundbraker.com/blitz/assembly';
$nav_menu =  '<div id="navi">
	<ul>
		<li><a href="' . $web_address . '">Home</a></li>
		<li><a href="' . $web_address . '/parts">Parts</a></li>
		<li><a href="' . $web_address . '/assemblies">Assemblies</a></li>
		<li><a href="' . $web_address . '/subassemblies">Sub-Assemblies</a></li>
		<li><a href="' . $web_address . '/parts/tubes.php">Tubes</a></li>
		<li><a href="' . $web_address . '/logout.php">Logout</a></li>
	</ul>
</div>';



// functions go here

function getBlaster()
{

  $fileName = basename($_SERVER['SCRIPT_FILENAME']);

  header("Location:$fileName?ID=$_GET[ID]");
}

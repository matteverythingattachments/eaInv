<?php 
$host = 'localhost';
$user = 'root';
$pword = '';
mysql_connect($host, $user, $pword);
mysql_select_db('products');
$x = 1;
while($x < 3621)	{
	$pull = mysql_query("SELECT * FROM things WHERE keycode = '$x'") or die(mysql_error());
	$text = mysql_fetch_array($pull);
	$newText = preg_replace('/\'/', "", $text['productname']);
	mysql_query("UPDATE things SET productname = '$newText' WHERE keycode = '$x'") or die(mysql_error());
	$x++;

}
?>
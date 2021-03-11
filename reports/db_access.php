<?php
$host = '10.0.0.19';
$username = 'nate_mc';
$pword = 'L()gm3!n';
mysql_connect($host, $username, $pword) or die('Cant Connect');
mysql_select_db('volusion_orders');
?>
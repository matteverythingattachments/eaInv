<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_MainConnection = "localhost";
$database_MainConnection = "inventory_control";
$username_MainConnection = "root";
$password_MainConnection = "";
$MainConnection = mysql_pconnect($hostname_MainConnection, $username_MainConnection, $password_MainConnection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
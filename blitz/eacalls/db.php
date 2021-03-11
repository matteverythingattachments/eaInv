<?php
//error_reporting(0);
//session_start();
$host = 'localhost';
$dbname = 'eacalls';
$username = 'root';
$pw = '';
$mysqli = mysqli_connect($host, $username, $pw, $dbname) or die(mysqli_error($mysqli));

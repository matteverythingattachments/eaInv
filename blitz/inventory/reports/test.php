<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//https://stackoverflow.com/questions/29849256/volusions-generic-sql-folder-functionality

$thismonth = date("m");
$thisyear = date("Y");


$get_start_month = isset($_GET['startmonth']) ? $_GET['startmonth'] : $thismonth;
$get_start_year = isset($_GET['startyear']) ? $_GET['startyear'] : $thisyear;
$get_end_month = isset($_GET['endmonth']) ? $_GET['endmonth'] : $thismonth;
$get_end_year = isset($_GET['endyear']) ? $_GET['endyear'] : $thisyear;


$asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/customer_list.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year."");

//echo file_get_contents("https://www.everythingattachments.com/v/vspfiles/schema/Generic/customer_list.asp?startmonth=04&startyear=2019&endmonth=04&endyear=2019");



?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Everything Attachments Order Reports Menu</title>
</head>
<body>
  
 <table>
   
<?php
$customers_arr = array();
  
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\customer_list');
foreach($xml->Table as $customer):
  $cid       = (string)$customer->customerid;
  $email     = (string)$customer->emailaddress;
  $firstname = (string)$customer->firstname;
  $lastname  = (string)$customer->lastname;
  
  $valid_email = filter_var($email, FILTER_SANITIZE_EMAIL); 

  // Validate e-mail
  if (filter_var($valid_email, FILTER_VALIDATE_EMAIL)): 
    $customers_arr[$cid]['email'] = strtolower($valid_email);
    $customers_arr[$cid]['fullname'] = ucfirst(strtolower($firstname)).' '.ucfirst(strtolower($lastname));
  endif; 



endforeach;
   
   //print_r($customers_arr);
   
 foreach($customers_arr as $key => $list):  
   echo '<tr><td>'.$list['fullname'].'</td><td>'.$list['email'].'</td></tr>';
 endforeach;
	
?>
  </table>
 </body>
</html>
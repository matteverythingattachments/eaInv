<?php 
error_reporting(0);
// CRON JOB TO CHECK FOR ORDERS THAT ARE MARKED SHIPPED BUT HAVE NOT BEEN COLLECTED
// IF FOUND SEND EMAIL TO JERRY


$format_received_date = date("Y-m-d");



// GET LIST OF ORDER IDS
include ("db_info.php");

  $getquery = "SELECT * FROM jerrys_data";
  $get_data = mysqli_query($conn, $getquery);
 
	while ($order_data = mysqli_fetch_assoc($get_data)):
      $order_id                              = $order_data['order_id'];
      $data_order[$order_id]['order_id']     = $order_data['order_id'];
      $data_order[$order_id]['emailed']      = $order_data['emailed'];
      $data_order[$order_id]['date_emailed'] = $order_data['date_emailed'];

  endwhile;


// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";

$today = date();
$selectyear  = date("Y", strtotime("-7 months"));
$selectmonth = date("m", strtotime("-7 months"));
$thisyear  = date("Y");
$thismonth = date("m");

$get_start_month = $selectmonth;
$get_start_year = $selectyear;
$get_end_month = $thismonth;
$get_end_year = $thisyear;

$asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/jerrys_cron.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year);

//echo file_get_contents("https://www.everythingattachments.com/v/vspfiles/schema/Generic/jerrys_cron.asp?startmonth=01&startyear=2018&endmonth=07&endyear=2019");
//https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\jerrys_cron

// SET VARs
$orders_arr = array();
$total_tax = 0;
$total_pay = 0;
                  
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\jerrys_cron');
   
foreach($xml->Orders as $orders):
  
  $orderid        = (string)$orders->OrderID;
  $orderdate      = date("m/d/Y", strtotime((string)$orders->OrderDate));
  $pay_result     = (string)$orders->pay_result;
  $pay_ammount    = round((string)$orders->pay_amount,2);
  $payment_amount = round((string)$orders->PaymentAmount,2);
  $totalpayment   = round((string)$orders->Total_Payment_Received,2);
  $paymentdate    = date("m/d/Y", strtotime((string)$orders->pay_authdate));
  $shipdate       = date("m/d/Y", strtotime((string)$orders->ShipDate));
                  
  $orders_arr[$orderid]['orderid']        = $orderid;
  $orders_arr[$orderid]['orderdate']      = $orderdate;
  $orders_arr[$orderid]['shipdate']       = $shipdate;

  if($pay_result==="AUTHORIZE"):
    $orders_arr[$orderid]['pay_authorize'] = $pay_ammount;
  elseif($pay_result==="CAPTURE"):
    $orders_arr[$orderid]['pay_capture']   = $pay_ammount;
  elseif($pay_result==="CREDIT"):
    $orders_arr[$orderid]['pay_credit']    = $pay_ammount;
  elseif($pay_result==="DEBIT"):
    $orders_arr[$orderid]['pay_debit']     = $pay_ammount;
  endif;  

  $orders_arr[$orderid]['payment_amount'] = $payment_amount;
  $orders_arr[$orderid]['totalpayment']   = $totalpayment;
  $orders_arr[$orderid]['paymentdate']    = $paymentdate;

endforeach;

$tcount = 0;
$totalshort = 0;
foreach($orders_arr as $neworder):

  if( $neworder['totalpayment'] + $neworder['pay_credit'] <> $neworder['payment_amount'] ):
  
    $amountshort = $neworder['payment_amount'] - $neworder['totalpayment'];

    if($amountshort > 0):

      $get_order_num = $neworder['orderid'];

      if (!array_key_exists($get_order_num, $data_order)):
        // COLLECT ORDERS THAT ARE SHIPPED BUT NOT PAID FOR
        // EMAIL JERRY
        $to_email = 'jerry@everythingattachments.com';
        $subject = 'Missed Order# '.$get_order_num.' - DO NOT SHIP!!!';
        $message = 'Check Order# '.$get_order_num.' as it was marked for SHIPPED and may not be COLLECTED yet. '."\r\n";
        $message .= 'https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=orders&page=1&ID='.$get_order_num." \r\n  \r\n";
        $message .= 'View list of all Questionable Orders '." \r\n";
        $message .= 'https://groundbraker.com/blitz/inventory/factory/jerrys-data.php'." \r\n  \r\n";
        $message = wordwrap($message, 70);
        $headers = "From: tam@everythingattachments.com" . "\r\n" ."CC: tam@everythingattachments.com";
        mail($to_email,$subject,$message,$headers);

        $missed_orders .= $get_order_num.', ';

        // ADD ORDER TO DATABASE
        $addproduct ="INSERT INTO jerrys_data (order_id, emailed, date_emailed) VALUES('$get_order_num',1,'$format_received_date')";    
        $addnewproduct = mysqli_query($conn, $addproduct) or die (mysqli_error());
        if(!$addnewproduct): $error = "Crap something went wrong!! Try again in a few minutes or call Tam"; endif;

      else:
        $email_date = date("Y-m-d", strtotime($data_order[$get_order_num]['date_emailed'].' +1 days')); // strtotime($data_order[$get_order_num]['date_emailed']);
        $this_date = date("Y-m-d");

        // ITS BEEN 24 HOURS RESENT EMAIL
        if($email_date < $this_date):
          $to_email = 'jerry@everythingattachments.com';
          $subject = 'Reminder: Missed Order# '.$get_order_num.' - DO NOT SHIP!!!';
          $message = 'Reminder to check Order# '.$get_order_num.' as it was marked for SHIPPED and may not be COLLECTED yet. '."\r\n";
          $message .= 'https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=orders&page=1&ID='.$get_order_num." \r\n  \r\n";
          $message .= 'View list of all Questionable Orders '." \r\n";
          $message .= 'http://10.10.1.106/inventory/factory/jerrys-data.php'." \r\n  \r\n";
          $message = wordwrap($message, 70);
          $headers = "From: tam@everythingattachments.com" . "\r\n" ."CC: tam@everythingattachments.com";
          mail($to_email,$subject,$message,$headers);

          // UPDATE EMAIL DATE IN DATABASE
          $updatequery_prodcuts ="UPDATE jerrys_data SET date_emailed='$format_received_date' WHERE order_id=$get_order_num";
          $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
          if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Tam"; endif;

        endif;
  

      endif;
      
      
      
      // CREATE A LIST OR ORDERS TO SAVE TO TEXT FILE
      $add_orders .= $get_order_num."\n";

   
      $table_data .= '<tr><td><a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=orders&page=1&ID='.$neworder['orderid'].'" >'.$neworder['orderid'].'</a></td>';
      $table_data .= '<td>'.$neworder['orderdate'].'</td><td>'.$neworder['paymentdate'].'</td><td>'.$neworder['shipdate'].'</td>';
      $table_data .= '<td class="hdata">'.money_format('%.2n', $neworder['pay_authorize']).'</td><td class="hdata">'.money_format('%.2n', $neworder['pay_capture']).'</td>';
      $table_data .= '<td class="hdata">'.money_format('%.2n', $neworder['pay_debit']).'</td><td class="hdata">'.money_format('%.2n', $neworder['pay_credit']).'</td>';
      $table_data .= '<td>'.money_format('%.2n', $neworder['payment_amount']).'</td><td>'.money_format('%.2n', $neworder['totalpayment']).'</td>';
      $table_data .= '<td>'.money_format('%.2n', $amountshort).'</td></tr>';
      $totalshort = $totalshort + $amountshort;
      $tcount ++;
      

    endif;
  endif;

endforeach;




// SAVE ORDERS TO THE DATABASE
// $file =  __DIR__ . '/check_orders.txt';
// file_put_contents($file, $add_orders);

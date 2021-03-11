<?php 
error_reporting(0);
// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";


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



$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


//if(empty($_SESSION['loggedin'])):
//  header('Location: login.php'); exit;
//endif;


// GET DATA ABOUT ORDER ID FROM VOLUSION -----------------------------------------------------------------------------------------------------------------
//if(!empty($_GET['getinfo']) && !empty($_GET['orderid']) && ($_SESSION['loggedin']=="admin")):  

if(!empty($_POST['getinfo'])):
  $choosesearch  = $_POST['choosesearch'];
else:
  $choosesearch = 4;
endif;


$selectyear  = date("Y", strtotime("-".$choosesearch." months"));
$selectmonth = date("m", strtotime("-".$choosesearch." months"));

$thisyear  = date("Y");
$thismonth = date("m");

$get_start_month = $selectmonth;
$get_start_year = $selectyear;
$get_end_month = $thismonth;
$get_end_year = $thisyear;

  $asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/jerrys_data.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year);


//echo file_get_contents("https://www.everythingattachments.com/v/vspfiles/schema/Generic/jerrys_data.asp?startmonth=01&startyear=2018&endmonth=07&endyear=2020");
//https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\jerrys_data

// SET VARs
$orders_arr = array();
$total_tax = 0;
$total_pay = 0;
                  
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\jerrys_data');
   
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
//echo $neworder['totalpayment'] .':'. $neworder['pay_credit'] .':'. $neworder['payment_amount'];
  if( $neworder['totalpayment'] + $neworder['pay_credit'] <> $neworder['payment_amount'] ):
  
    $amountshort = $neworder['payment_amount'] - $neworder['totalpayment'];
    //echo $neworder['orderid'].' at '.$amountshort.' : ';
    if($amountshort > 0)
    {   
$table_data .= '<tr><td><a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=orders&page=1&ID='.$neworder['orderid'].'" >'.$neworder['orderid'].'</a></td>';
      $table_data .= '<td>'.$neworder['orderdate'].'</td><td>'.$neworder['paymentdate'].'</td><td>'.$neworder['shipdate'].'</td>';
      $table_data .= '<td class="hdata">'.$neworder['pay_authorize'].'</td><td class="hdata">'.$neworder['pay_capture'].'</td>';
      $table_data .= '<td class="hdata">'.$neworder['pay_debit'].'</td><td class="hdata">'.$neworder['pay_credit'].'</td>';
      $table_data .= '<td>'.$neworder['payment_amount'].'</td><td>'.$neworder['totalpayment'].'</td>';
      $table_data .= '<td>'.$amountshort.'</td></tr>';
      $totalshort = $totalshort + $amountshort;
      $tcount ++;
    }
        //endif;
  endif;

endforeach;


?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Jerrys Data - Everything Attachments</title>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
  
  <script>
    jQuery(document).ready(function($){
        $(document).on('click', '#unhidedata', function(e){ 
            if($(this).text() == "Unhide Data"){ 
              $(this).text("Hide Data");
              $(".hdata").show();
            }else{
              $(this).text("Unhide Data");
              $(".hdata").hide();
            }
        });
    });
  </script>

  <style>
      body{ background:#f1f1f1;}
      #maindiv{
        text-align:center;
      }
      #content{
        margin: 15px auto;
      }
      #zipform{
        padding: 15px;
      }
      #zipform input[type='text']{
        border: solid 1px #c0c0c0;
        padding: 4px 4px 2px;
        font-weight: bold;
        font-size: 1rem;
      }
      #zipform input[type='submit']{
        border: solid 1px #66a222;
        border-radius: 4px;
        padding: 4px 11px 2px;
        font-weight: bold;
        font-size: 1rem;
        color: #fff;
        margin-left: 10px;
        background: #8BC34A;
        cursor:pointer;
      }
      #zipform input[type='submit']:hover{
        background: #75a939;
        border: solid 1px #63902f;
      }
      #frighttable{ 
        text-align:left; 
        margin: 0 auto;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      }
      #frighttable, #frighttable tr, #frighttable th, #frighttable td{ 
        border-collapse: collapse; border: solid 1px #c0c0c0; 
      }
      #frighttable th, #frighttable td{
        padding: 7px 15px;
        font-weight: bold;
      }
      #frighttable tr:nth-child(2n+3){
        background: #eee;
      }
      #frighttable tr:nth-child(2n+2){
        background: #fff;
      }
      #frighttable tr:first-child{
        background: #666;
        color:#fff;
      }
            
      #choosedata{ 
        padding: 8px;
        margin-top: 20px;
        background: #dcdcdc;
        border: solid 1px #c5c5c5;
        text-align: center;
      }
      #choosedata label{ font-weight:bold; font-size: 1.1rem; }
      #choosedata select{
        padding: 4px;
        font-weight:bold;
        font-size: 1.1rem;
        
      }
      #frighttable td:nth-child(1), #frighttable td:nth-child(2), #frighttable td:nth-child(3), #frighttable td:nth-child(4){ text-align:center; }
      #frighttable td:nth-child(11){ color: #bf0000; }
    .hdata{ display:none; }
    #getinfobutton{
      cursor:pointer;
      border: solid 1px #b6e283;
      color: #000;
      background:#dffbc0;
      font-weight:bold;
      font-size: 18px;
      padding: 2px 10px;
      border-radius: 4px;
      margin-left: 20px;
    }
    #getinfobutton:hover{ background:#f8fff0; }
    #labelgetinfo{ 
      cursor:pointer;
      font-weight:bold;
      border: solid 1px #c3c3c3;
      background: #d8d8d8;
      padding: 2px 10px;
      font-size: 18px;
      border-radius: 4px;
    }
    #labelgetinfo:hover{ background: #f8f8f8; }
    #vtdata{ 
      position:absolute;
      text-decoration:none;
      color:blue;
      font-weight:bold;
      font-size: 18px;
      margin-left: 300px;
    }
    #unhidedata{
      cursor:pointer;
      position:absolute;
      margin: 35px 0 0 -220px;
      text-decoration:underline;
      color:#9a9a9a;
    }
    </style>
  
  
</head>
<body>

  <form method="post">
    <div id="choosedata" style="margin-top:30px;">
      <input type="hidden" name="getinfo" value="1" />
    
      <label for="choosesearch"> Go Back:</label>
      <select id="choosesearch" name="choosesearch">
        <option <?php if($choosesearch==='4'){ echo 'selected'; }?> value='4'>4 Months</option>
        <option <?php if($choosesearch==='5'){ echo 'selected'; }?> value='5'>5 Months</option>
        <option <?php if($choosesearch==='6'){ echo 'selected'; }?> value='6'>6 Months</option>
        <option <?php if($choosesearch==='12'){ echo 'selected'; }?> value='12'>1 Year</option>
       </select> 
      
    
    </div>
    <p style="text-align:center;margin:30px;">
      <span id="unhidedata">Unhide Data</span>
      <input id="getinfobutton" type="submit" value="Get Data" />  
    </p>
  </form>
  
  
 <table id="frighttable" style="margin-top:30px;">
   <tr>
     <th>Order ID</th><th>Order Date</th><th>Pay Date</th><th>Ship Date</th>
     <th class="hdata">Authorized</th><th class="hdata">Captured</th><th class="hdata">Debit</th><th class="hdata">Credit</th>
     <th>Payment Amount</th><th>Total Paid</th><th>Total Short</th>
   </tr>
   
   
   <?php echo $table_data; ?>
   <tr><td></td><td>Total Items:</td><td> <?php echo $tcount; ?></td><td></td><td class="hdata"></td><td class="hdata"></td><td class="hdata"></td><td class="hdata"></td><td></td><td></td><td><?php echo $totalshort; ?></td></tr>
  </table>
  <br /><br />
  
  <br /><br />
 </body>
</html>
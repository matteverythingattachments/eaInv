<?php 
// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";


$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


//if(empty($_SESSION['loggedin'])):
//  header('Location: http://inventory.corimpco.net/factory/login.php'); exit;
//endif;


// GET DATA ABOUT ORDER ID FROM VOLUSION -----------------------------------------------------------------------------------------------------------------
//if(!empty($_GET['getinfo']) && !empty($_GET['orderid']) && ($_SESSION['loggedin']=="admin")):  

$lastyear  = date("Y", strtotime("-1 years"));
$lastmonth = date("m", strtotime("-1 months"));

$thisyear  = date("Y");
$thismonth = date("m");

if(!empty($_POST['getinfo'])):
  $useYear  = $_POST['chooseyear'];
  $useMonth = $_POST['choosemonth'];
  $useState = $_POST['choosestate'];
else:
  $useYear  = $lastyear;
  $useMonth = 'all';
  $useState = 'all';
endif;



$get_start_month = $useMonth =='all' ? '01' : $useMonth;
$get_start_year = $useYear;
$get_end_month = $useMonth =='all' ? '12' : $useMonth;
$get_end_year = $useYear;
$get_state = $useState;


  $asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/state_sales.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year."&thestate=".$get_state);


//echo file_get_contents("https://www.everythingattachments.com/v/vspfiles/schema/Generic/state_sales.asp?startmonth=01&startyear=2018&endmonth=12&endyear=2018&thestate=all");
//https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\state_sales

// SET VARs
$orders_arr = array();
$state_arr = array();
$state_taxx = array();
$total_tax = 0;
$total_pay = 0;
                  
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\state_sales');
   
foreach($xml->Orders as $orders):
  
  $orderid        = (string)$orders->OrderID;
  $orderdate      = date("m/d/Y", strtotime((string)$orders->OrderDate));
  $shipstate      = (string)$orders->ShipState;
  $shippostalcode = (string)$orders->ShipPostalCode;
  $salestax1      = round((string)$orders->salestax1,2);
  $salestaxrate1  = round((string)$orders->salestaxrate1,2);
  $totalpayment   = round((string)$orders->Total_Payment_Received,2);
  $paymentdate    = date("m/d/Y", strtotime((string)$orders->pay_authdate)); 

                  
  //$productcode    = (string)$orders->productcode; 
  //$productname    = (string)$orders->productname;
  //$revenue        = (string)$orders->Revenue;
  //$cleanrevenue   = money_format('%.2n', $revenue); 
 
  $orders_arr[$orderid]['orderid']        = $orderid;
  $orders_arr[$orderid]['orderdate']      = $orderdate;
  $orders_arr[$orderid]['shipstate']      = $shipstate;
  $orders_arr[$orderid]['shippostalcode'] = $shippostalcode;
  $orders_arr[$orderid]['salestax1']      = $salestax1;
  $orders_arr[$orderid]['salestaxrate1']  = $salestaxrate1; 
  $orders_arr[$orderid]['totalpayment']   = $totalpayment;
  $orders_arr[$orderid]['paymentdate']    = $paymentdate;

  $orders_table .= '<tr><td><a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=orders&page=1&ID='.$orderid.'">'.$orderid.'</a></td>';
  $orders_table .= '<td>'.$orderdate.'</td><td>'.$paymentdate.'</td><td>'.money_format('%.2n', $totalpayment).'</td><td>'.money_format('%.2n', $salestax1).'</td></tr>'; 
  
endforeach;


// LOOP THROUGH ORDERS AND GROUP BY STATE AND CACULATE TOTAL PAYMENTS
foreach($orders_arr as $order):
  if($state_arr[$order['shipstate']]):
    $new_total = $state_arr[$order['shipstate']] + $order['totalpayment'];
    $new_taxx = $state_taxx[$order['shipstate']] + $order['salestax1'];
    $state_arr[$order['shipstate']] = $new_total;
    $state_taxx[$order['shipstate']] = $new_taxx;
  else:
    $state_arr[$order['shipstate']] = $order['totalpayment'];
    $state_taxx[$order['shipstate']] = $order['salestax1'];
  endif;
endforeach;


if($useState === 'all'):
  // LOOP THROUGH STATES AND WRITE TABLE
  foreach( $states as $key => $value ):
    $table_data .= '<tr><td>'.$key.'</td><td>'. money_format('%.2n', $state_arr[$key]).'</td><td>'. money_format('%.2n', $state_taxx[$key]).'</td></tr>';
    $total_pay = $total_pay + $state_arr[$key];
    $total_taxx = $total_taxx + $state_taxx[$key];
  endforeach;
else:
  // SINGLE STATE
  $table_data .= '<tr><td>'.$useState.'</td><td>'. money_format('%.2n', $state_arr[$useState]).'</td><td>'. money_format('%.2n', $state_taxx[$useState]).'</td></tr>';


endif;

//foreach($state_arr as $key => $new_state):
//  $table_data .= '<tr><td>'.$key.'</td><td>'.$new_state.'</td></tr>';
//endforeach;


?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Everything Attachments Order Reports Menu</title>
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
      #frighttable td:nth-child(1), #frighttable td:nth-child(2), #frighttable td:nth-child(5), #frighttable td:nth-child(6){ text-align:center; }
    
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
    </style>
</head>
<body>

  <form method="post">
    <div id="choosedata" style="margin-top:30px;">
      <input type="hidden" name="getinfo" value="1" />
      <label for="choosestate"> Select State:</label>
      <select id="choosestate" name="choosestate">
        <option <?php if($useState==='all'){ echo 'selected'; }?> value='all'>All</option>
        <?php 
          foreach( $states as $key => $value ){
            if($key==$useState){ $stateselectclass = "selected"; }else{ $stateselectclass = ""; }
            echo '<option value="'.$key.'" title="'.$value.'" '.$stateselectclass.'>'.$key.'</option>';
          }                    
        ?>
      </select>&nbsp;&nbsp; &nbsp;&nbsp;
      
      <label for="chooseyear"> Select Year:</label>
      <select id="chooseyear" name="chooseyear">
        <?php 
            $maxYear = date("Y");
            for ($x = 2012; $x <= $maxYear; $x++) {
              if($x==$useYear){$yearselectclass = "selected"; }else{ $yearselectclass = ""; }
              echo  '<option value="'.$x.'" '.$yearselectclass.'>'.$x.'</option>';
            } 
        ?>
      </select>&nbsp;&nbsp; &nbsp;&nbsp; 
    
      <label for="choosemonth"> Select Month:</label>
      <select id="choosemonth" name="choosemonth">
        <option <?php if($useMonth==='all'){ echo 'selected'; }?> value='all'>All</option>
        <option <?php if($useMonth==='01'){ echo 'selected'; }?> value='01'>Janaury</option>
        <option <?php if($useMonth==='02'){ echo 'selected'; }?> value='02'>February</option>
        <option <?php if($useMonth==='03'){ echo 'selected'; }?> value='03'>March</option>
        <option <?php if($useMonth==='04'){ echo 'selected'; }?> value='04'>April</option>
        <option <?php if($useMonth==='05'){ echo 'selected'; }?> value='05'>May</option>
        <option <?php if($useMonth==='06'){ echo 'selected'; }?> value='06'>June</option>
        <option <?php if($useMonth==='07'){ echo 'selected'; }?> value='07'>July</option>
        <option <?php if($useMonth==='08'){ echo 'selected'; }?> value='08'>August</option>
        <option <?php if($useMonth==='09'){ echo 'selected'; }?> value='09'>September</option>
        <option <?php if($useMonth==='10'){ echo 'selected'; }?> value='10'>October</option>
        <option <?php if($useMonth==='11'){ echo 'selected'; }?> value='11'>November</option>
        <option <?php if($useMonth==='12'){ echo 'selected'; }?> value='12'>December</option>
       </select> 
     
      
    </div>
    <p style="text-align:center;margin:30px;">
      <input id="getinfobutton" type="submit" value="Get Tax Data" />  
      <a href="http://inventory.corimpco.net/taxes/tax-rates.php" id="vtdata" title="Volusion Tax Data">Tax Rates >></a>
    </p>
  </form>
  
  
 <table id="frighttable" style="margin-top:30px;">
   <tr><th>State</th><th>Total Sales</th><th>Total Taxes</th></tr>
   <?php echo $table_data; ?>
   <tr><td>Totals </td><td><?php echo money_format('%.2n', $total_pay); ?></td><td><?php echo money_format('%.2n', $total_taxx); ?></td></tr>
  </table>
  <br /><br />
  <?php if($useState != 'all'):  ?>
    <table id="frighttable" style="margin-top:30px;">
      <tr><th>Order #</th><th>Order Date</th><th>Payment Date</th><th>Total Payments</th><th>Total Tax</th></tr>
      <?php echo $orders_table; ?>  
    </table>
  <?php endif; ?>
  <br /><br />
 </body>
</html>

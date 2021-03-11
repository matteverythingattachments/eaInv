<?php 
error_reporting(0);
// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";


$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


$SSTstates = array('AR'=>'Arkansas','GA'=>'Georgia','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','MI'=>'Michigan','MN'=>'Minnesota','NE'=>'Nebraska','NV'=>'Nevada','NJ'=>'New Jersey','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','RI'=>'Rhode Island','SD'=>'South Dakota','TN'=>'Tennessee','UT'=>'Utah','VT'=>'Vermont','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


// START THE CSV FILE
$avalara_csv_file = array('ProcessCode,DocCode,DocType,DocDate,CompanyCode,CustomerCode,EntityUseCode,LineNo,TaxCode,TaxDate,ItemCode,Description,Qty,Amount,Discount,Ref1,Ref2,ExemptionNo,RevAcct,DestAddress,DestCity,DestRegion,DestPostalCode,DestCountry,OrigAddress,OrigCity,OrigRegion,OrigPostalCode,OrigCountry,LocationCode,SalesPersonCode,PurchaseOrderNo,CurrencyCode,ExchangeRate,ExchangeRateEffDate,PaymentDate,TaxIncluded,DestTaxRegion,OrigTaxRegion,Taxable,TaxType,TotalTax,CountryName,CountryCode,CountryRate,CountryTax,StateName,StateCode,StateRate,StateTax,CountyName,CountyCode,CountyRate,CountyTax,CityName,CityCode,CityRate,CityTax,Other1Name,Other1Code,Other1Rate,Other1Tax,Other2Name,Other2Code,Other2Rate,Other2Tax,Other3Name,Other3Code,Other3Rate,Other3Tax,Other4Name,Other4Code,Other4Rate,Other4Tax,ReferenceCode,BuyersVATNo,IsSellerImporterOfRecord,BRBuyerType,BRBuyer_IsExemptOrCannotWH_IRRF,BRBuyer_IsExemptOrCannotWH_PISRF,BRBuyer_IsExemptOrCannotWH_COFINSRF,BRBuyer_IsExemptOrCannotWH_CSLLRF,BRBuyer_IsExempt_PIS,BRBuyer_IsExempt_COFINS,BRBuyer_IsExempt_CSLL,Header_Description,Email');



// EA CSV FILE
$csv_file = array('Order ID,Customer ID,Inv Date,Pay Date,State,City,Postal Code,Exempt #,Est Tax,Tax,Rate,Total Payment,Link');

//if(empty($_SESSION['loggedin'])):
//  header('Location: http://inventory.corimpco.net/factory/login.php'); exit;
//endif;


// GET DATA ABOUT ORDER ID FROM VOLUSION -----------------------------------------------------------------------------------------------------------------
//if(!empty($_GET['getinfo']) && !empty($_GET['orderid']) && ($_SESSION['loggedin']=="admin")):  

if(!empty($_POST['getinfo'])):
  $CurrentYear  = $_POST['chooseyear'];
  $CurrentMonth = $_POST['choosemonth'];
  $CurrentState = $_POST['choosestate'];
  $CreateCSV    = $_POST['createcsv'];
  $CSV_STATE    = $_POST['choosestate'];
else:
  $CurrentYear  = date("Y");
  $CurrentMonth = date("m", strtotime("-1 months"));
  $CSV_STATE    = "EA";
endif;

$getdate   = $CurrentMonth.'/01/'.$CurrentYear;

$startmonth = date("m", strtotime($getdate . "-6 months"));
$endmonth   = date("m", strtotime($getdate . "+6 months"));
$startyear  = date("Y", strtotime($getdate . "-6 months"));
$endyear    = date("Y", strtotime($getdate . "+6 months"));

$get_start_month = isset($_GET['startmonth']) ? $_GET['startmonth'] : $startmonth;
$get_start_year = isset($_GET['startyear']) ? $_GET['startyear'] : $startyear;
$get_end_month = isset($_GET['endmonth']) ? $_GET['endmonth'] : $endmonth;
$get_end_year = isset($_GET['endyear']) ? $_GET['endyear'] : $endyear;


$asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/salestax.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year."&thestate=".$CurrentState);

//echo "https://www.everythingattachments.com/v/vspfiles/schema/Generic/salestax.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year."&thestate=".$CurrentState; exit;


//echo file_get_contents("https://www.everythingattachments.com/v/vspfiles/schema/Generic/salestax.asp?startmonth=04&startyear=2019&endmonth=04&endyear=2019&thestate=NC");
//https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\salestax

// SET VARs
$orders_arr = array();
$total_tax = 0;
$total_pay = 0;
$total_taxed = 0;
$total_count = 0;

                  
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\salestax');
   
foreach($xml->Orders as $orders):
  
  $orderstatus    = (string)$orders->OrderStatus;
  $orderid        = (string)$orders->OrderID;
  $customerid     = (string)$orders->customerid;
  $orderdate      = date("m/d/Y", strtotime((string)$orders->OrderDate));
  $shipdate       = date("m/d/Y", strtotime((string)$orders->ShipDate));
  $shipcity       = ucfirst(strtolower((string)$orders->ShipCity));
  $shipstate      = (string)$orders->ShipState;
  $shippostalcode = (string)$orders->ShipPostalCode;
  $salestax1      = round((string)$orders->salestax1,2);
  $salestaxrate1  = (string)$orders->salestaxrate1;
  $totalpayment   = round((string)$orders->Total_Payment_Received,2);
  $paymentdate    = date("m/d/Y", strtotime((string)$orders->pay_authdate)); 
  $exempt_tax_num = (string)$orders->Custom_Field_Custom3;

  $total_wo_tax = $totalpayment - $salestax1;
                  
  //$productcode    = (string)$orders->productcode; 
  //$productname    = (string)$orders->productname;
  //$revenue        = (string)$orders->Revenue;
  //$cleanrevenue   = money_format('%.2n', $revenue); 
 
  $orders_arr[$orderid]['orderstatus']    = $orderstatus;
  $orders_arr[$orderid]['orderid']        = $orderid;
  $orders_arr[$orderid]['customerid']     = $customerid; 
  $orders_arr[$orderid]['orderdate']      = $orderdate;
  $orders_arr[$orderid]['shipdate']       = $shipdate;
  $orders_arr[$orderid]['shipstate']      = $shipstate;
  $orders_arr[$orderid]['shipcity']       = $shipcity;
  $orders_arr[$orderid]['shippostalcode'] = $shippostalcode;
  $orders_arr[$orderid]['salestax1']      = $salestax1;
  $orders_arr[$orderid]['salestaxrate1']  = $salestaxrate1; 
  $orders_arr[$orderid]['totalpayment']   = $totalpayment;
  $orders_arr[$orderid]['paymentdate']    = $paymentdate;
  $orders_arr[$orderid]['exampttaxnum']   = $exempt_tax_num; 
  
endforeach;


  //$exempt_tax_num = (empty($exempt_tax_num)) ? 0 : $exempt_tax_num;




  // ADD ORDER ID TO ARRAY AND PREVENT DUPLICATES -----------------------------------------------------------------------------
 // if(!in_array($orderid, $orders_arr)):
 //   $orders_arr[] = $orderid;

foreach($orders_arr as $seeOrder ):
  $getOrderMonth = date("m", strtotime($seeOrder['paymentdate']));     
  $getOrderYear  = date("Y", strtotime($seeOrder['paymentdate']));

  if($getOrderMonth == $CurrentMonth && $getOrderYear == $CurrentYear):

    // VARIABLES
    $orderstatus    = $seeOrder['orderstatus'];
    $orderid        = $seeOrder['orderid'];
    $customerid     = $seeOrder['customerid'];
    $orderdate      = $seeOrder['orderdate'];
    $shipdate       = $seeOrder['shipdate'];
    $shipcity       = $seeOrder['shipcity'];
    $shipstate      = $seeOrder['shipstate'];
    $shippostalcode = $seeOrder['shippostalcode'];
    $salestax1      = $seeOrder['salestax1'];
    $salestaxrate1  = $seeOrder['salestaxrate1'];
    $totalpayment   = $seeOrder['totalpayment'];
    $paymentdate    = $seeOrder['paymentdate'];
    $exempt_tax_num = $seeOrder['exampttaxnum'];

    $total_wo_tax = $totalpayment - $salestax1;

    $shipzip = substr(trim($shippostalcode),0,5);

    $total_tax = $total_tax + $salestax1;
    $total_pay = $total_pay + $total_wo_tax;
    $Header_Description = "Tax for ".$shipstate;  
    $total_count++;


// GET TAX RATE
    if(!empty($salestaxrate1)):
      $useTaxRate =  ($salestaxrate1 * 100);
    else:
      $useTaxRate = ($cvs_file[$shipzip]['tax_r']) ? $cvs_file[$shipzip]['tax_r'] : 7;
    endif;


  // ESTIMATED TAXES
    $estimated_tax = (($useTaxRate / 100) * $total_wo_tax);
    $totaltaxamount = $totaltaxamount + $estimated_tax;


    // TOTAL FOR SALES THAT HAS TAX
    if($salestax1 > 0):
      $total_taxed = $total_taxed + $total_wo_tax;
    endif;
   
    if(!empty($CreateCSV)):
      //$csv_file[] = $ProcessCode . ',Ord'.$orderid.',1,'.$paymentdate.',,'.$customerid.',,1,,,,,,'.$total_wo_tax.',,,,'.$exempt_tax_num.',,,,'.$shipstate.','.$shippostalcode.',US,,,NC,28613,,,,,,,,,,,,,,'.$show_tax.',,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,FALSE,,,,,,,,,'.$Header_Description.',';   

    $csv_file[] = $orderid.','.$customerid.','.$orderdate.','.$paymentdate.','.$shipstate.','.$shipcity.','.$shipzip.','.$exempt_tax_num.','.$estimated_tax.','.$salestax1.','.$useTaxRate.','.$totalpayment.', https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$orderid;   
    endif;



    $table_data .= '<tr><td>'.$total_count.'</td><td><a target="_blank" href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$orderid.'">'.$orderid.'</a></td>';
    $table_data .= '<td>'.$orderstatus.'</td><td>'.$orderdate.'</td><td>'.$paymentdate.'</td><td>'.$shipstate.'</td><td>'.$shipcity.'</td><td rel="'.$shippostalcode.'">'.$shipzip.'</td><td>'.money_format('%.2n', $estimated_tax).'</td><td>'.money_format('%.2n', $salestax1).'</td><td>'.$useTaxRate.'%</td><td>'.money_format('%.2n', $total_wo_tax).'</td></tr>';


  endif;
endforeach;


 if(!empty($CreateCSV)):
  // OUT PUT THE CVS FILE TO DOWNLOAD --------
    $namefile = $CSV_STATE."_Tax_Record_".date('m')."_".date('d')."_".date('Y').".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$namefile.'"');
    $fp = fopen('php://output', 'wb');
    foreach ( $csv_file as $line ) {
      //$line = str_replace( '""', '"', $line );
      $val = explode(",", $line);
      //print_r( $val );
      //fputcsv($fp, $val);
      fwrite( $fp, $line . "\n" );
    }
    fclose($fp);

    exit;
  endif;



?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Everything Attachments Tax Reporting</title>
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
      #frighttable td:nth-child(1), #frighttable td:nth-child(2), #frighttable td:nth-child(5), #frighttable td:nth-child(7){ text-align:center; }
    
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
    .fl_right{ float:right; }
    
    
    @media print {
      #dataform{ display:none; } 
     
    }

    
    </style>
</head>
<body>

  <form id="dataform" method="post">
    <div id="choosedata" style="margin-top:30px;">
      <input type="hidden" name="getinfo" value="1" />
      <label for="choosestate"> Select State:</label>
      <select id="choosestate" name="choosestate">
        <option <?php if($CurrentState==='all'){ echo 'selected'; }?> value='all'>All</option>
        <?php 
          foreach( $states as $key => $value ){
            if($key==$CurrentState){ $stateselectclass = "selected"; }else{ $stateselectclass = ""; }
            echo '<option value="'.$key.'" title="'.$value.'" '.$stateselectclass.'>'.$key.'</option>';
          }                    
        ?>
      </select>&nbsp;&nbsp; &nbsp;&nbsp;
      
      <label for="chooseyear"> Select Year:</label>
      <select id="chooseyear" name="chooseyear">
        <?php 
            $maxYear = date("Y");
            for ($x = 2012; $x <= $maxYear; $x++) {
              if($x==$CurrentYear){$yearselectclass = "selected"; }else{ $yearselectclass = ""; }
              echo  '<option value="'.$x.'" '.$yearselectclass.'>'.$x.'</option>';
            } 
        ?>
      </select>&nbsp;&nbsp; &nbsp;&nbsp; 
    
      <label for="choosemonth"> Select Month:</label>
      <select id="choosemonth" name="choosemonth">
        <option <?php if($CurrentMonth==='01'){ echo 'selected'; }?> value='01'>Janaury</option>
        <option <?php if($CurrentMonth==='02'){ echo 'selected'; }?> value='02'>February</option>
        <option <?php if($CurrentMonth==='03'){ echo 'selected'; }?> value='03'>March</option>
        <option <?php if($CurrentMonth==='04'){ echo 'selected'; }?> value='04'>April</option>
        <option <?php if($CurrentMonth==='05'){ echo 'selected'; }?> value='05'>May</option>
        <option <?php if($CurrentMonth==='06'){ echo 'selected'; }?> value='06'>June</option>
        <option <?php if($CurrentMonth==='07'){ echo 'selected'; }?> value='07'>July</option>
        <option <?php if($CurrentMonth==='08'){ echo 'selected'; }?> value='08'>August</option>
        <option <?php if($CurrentMonth==='09'){ echo 'selected'; }?> value='09'>September</option>
        <option <?php if($CurrentMonth==='10'){ echo 'selected'; }?> value='10'>October</option>
        <option <?php if($CurrentMonth==='11'){ echo 'selected'; }?> value='11'>November</option>
        <option <?php if($CurrentMonth==='12'){ echo 'selected'; }?> value='12'>December</option>
       </select> 
      
      <a href="https://help.avalara.com/000_Avalara_AvaTax/Add_or_Import_Transactions" target="_blank" title="Avalara Help" style="font-weight:bold;position:absolute;margin: 7px 0 0 100px; color:#66769a;">Avalara Help</a>
      
    </div>
    <p style="text-align:center;margin:30px;">
      <label id="labelgetinfo" for="getinfo">Create CSV File <input id="getinfo" type="checkbox" name="createcsv"></label> 
      <input id="getinfobutton" type="submit" value="Get Tax Data" />  
      <a href="upload-taxes.php" id="vtdata" title="Volusion Tax Data">Volusion Tax Data >></a>
    </p>
  </form>
  
  
 <table id="frighttable" style="margin-top:30px;">
   <tr><th>#</th><th>Order ID</th><th>Status</th><th>Inv Date</th><th>Pay Date</th><th>State</th><th>City</th><th>Postal Code</th><th>Est Tax</th><th>Tax</th><th>Rate</th><th>Gross Payment</th></tr>
   
   <?php echo $table_data; ?>
   <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><?php echo $totaltaxamount; ?></td><td><?php echo $total_tax; ?></td><td></td>
     <td colspan="2">Total: <span class="fl_right"><?php echo $total_pay; ?></span><br />
       Only Taxed:&nbsp;&nbsp;&nbsp;<span class="fl_right"><?php echo $total_taxed; ?></span><br />
     </td></tr>
  </table>
  <br /><br />
  
  <br /><br />
 </body>
</html>







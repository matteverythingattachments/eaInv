<?php 
error_reporting(0);
// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";

$totaltaxamount = 0;
$totalPretaxAmount = 0;

$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


$SSTstates = array('AR'=>'Arkansas','GA'=>'Georgia','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','MI'=>'Michigan','MN'=>'Minnesota','NE'=>'Nebraska','NV'=>'Nevada','NJ'=>'New Jersey','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','RI'=>'Rhode Island','SD'=>'South Dakota','TN'=>'Tennessee','UT'=>'Utah','VT'=>'Vermont','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


// START THE CSV FILE
$csv_file = array('ProcessCode,DocCode,DocType,DocDate,CompanyCode,CustomerCode,EntityUseCode,LineNo,TaxCode,TaxDate,ItemCode,Description,Qty,Amount,Discount,Ref1,Ref2,ExemptionNo,RevAcct,DestAddress,DestCity,DestRegion,DestPostalCode,DestCountry,OrigAddress,OrigCity,OrigRegion,OrigPostalCode,OrigCountry,LocationCode,SalesPersonCode,PurchaseOrderNo,CurrencyCode,ExchangeRate,ExchangeRateEffDate,PaymentDate,TaxIncluded,DestTaxRegion,OrigTaxRegion,Taxable,TaxType,TotalTax,CountryName,CountryCode,CountryRate,CountryTax,StateName,StateCode,StateRate,StateTax,CountyName,CountyCode,CountyRate,CountyTax,CityName,CityCode,CityRate,CityTax,Other1Name,Other1Code,Other1Rate,Other1Tax,Other2Name,Other2Code,Other2Rate,Other2Tax,Other3Name,Other3Code,Other3Rate,Other3Tax,Other4Name,Other4Code,Other4Rate,Other4Tax,ReferenceCode,BuyersVATNo,IsSellerImporterOfRecord,BRBuyerType,BRBuyer_IsExemptOrCannotWH_IRRF,BRBuyer_IsExemptOrCannotWH_PISRF,BRBuyer_IsExemptOrCannotWH_COFINSRF,BRBuyer_IsExemptOrCannotWH_CSLLRF,BRBuyer_IsExempt_PIS,BRBuyer_IsExempt_COFINS,BRBuyer_IsExempt_CSLL,Header_Description,Email');

//if(empty($_SESSION['loggedin'])):
//  header('Location: http://inventory.corimpco.net/factory/login.php'); exit;
//endif;

// GET TAX RATES FROM ZIP FOLDER


  $files = listFolders("zip/");

    foreach($files as $file) {

        if (($handle = fopen($file, "r")) !== FALSE) {
            //echo "<b>Filename: " . basename($file) . "</b><br><br>";
            while (($data = fgetcsv($handle, 4096, ",")) !== FALSE) {
                //echo implode("\t", $data);
                if($data[0] != "State"){
                  $tax_rate_title = 'Tax ('.$data[4]*100 .'%)';
                  $tax_rate       = $data[4]*100;
              
                  $cvs_file[$data[1]]['state']  = $states[$data[0]];
                  $cvs_file[$data[1]]['region'] = ucwords(strtolower($data[2]));
                  $cvs_file[$data[1]]['tax_t']  = $tax_rate_title;
                  $cvs_file[$data[1]]['tax_r']  = $tax_rate;
                  
                }
            }
            //echo "<br /><br />";
            fclose($handle);
        } else {
            echo "Could not open file: " . $file;
        }
    }
  
 
// ----------------------------------------------------------------------------------------------------------------------------------


// FUNCTION TO GET LIST OF ALL CVS FILES INSIDE OF ZIP FOLDER -----------------------------------------------------------------------
function listFolders($dir) {
    $directory = new RecursiveDirectoryIterator($dir);
    $directory->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);

    $files = new RecursiveIteratorIterator(
        $directory,
        RecursiveIteratorIterator::SELF_FIRST
    );

    $list = [];
    foreach ($files as $file) {
        if (
            $file->isDir() == false &&
            $file->getExtension() === 'csv'
        ) {
            $list[] = $dir.'/'.$directory.'/'.$file->getBasename();
        }
    }

    return $list;
}
// ----------------------------------------------------------------------------------------------------------------------------------













// GET DATA ABOUT ORDER ID FROM VOLUSION -----------------------------------------------------------------------------------------------------------------
//if(!empty($_GET['getinfo']) && !empty($_GET['orderid']) && ($_SESSION['loggedin']=="admin")):  

if(!empty($_POST['getinfo'])):
  $StartYear  = $_POST['startyear'];
  $EndYear    = $_POST['endyear'];
  $StartMonth = $_POST['startmonth'];
  $EndMonth   = $_POST['endmonth'];
  $GetState   = $_POST['choosestate'];
  $CreateCSV  = $_POST['createcsv'];
else:
  $StartYear = $EndYear  = date("Y");
  $StartMonth = $EndMonth = date("m", strtotime("-1 months"));
endif;

$thisyear  = date("Y");
$thismonth = date("m");

$get_start_month = isset($_GET['startmonth']) ? $_GET['startmonth'] : $StartMonth;
$get_start_year = isset($_GET['startyear']) ? $_GET['startyear'] : $StartYear;
$get_end_month = isset($_GET['endmonth']) ? $_GET['endmonth'] : $EndMonth;
$get_end_year = isset($_GET['endyear']) ? $_GET['endyear'] : $EndYear;


$asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/salestax.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year."&thestate=".$GetState);
//$asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/salestax.asp?startmonth=1&startyear=".$get_start_year."&endmonth=12&endyear=".$get_end_year."&thestate=".$CurrentState);


//echo file_get_contents("https://www.everythingattachments.com/v/vspfiles/schema/Generic/salestax.asp?startmonth=04&startyear=2019&endmonth=04&endyear=2019&thestate=NC");
//https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\salestax

// SET VARs
$orders_arr = array();
$total_tax = 0;
$total_pay = 0;
$total_count = 0;
$exemptTotal = 0;
$exemptGT = 0;
$showExemptIndicator = false;
                  
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\salestax');
   
foreach($xml->Orders as $orders):
  
  $orderid        = (string)$orders->OrderID;
  $customerid     = (string)$orders->customerid;
  $orderdate      = date("m/d/Y", strtotime((string)$orders->OrderDate));
  $shipdate       = date("m/d/Y", strtotime((string)$orders->ShipDate));
  $shipstate      = (string)$orders->ShipState;
  $shipcity       = $orders->ShipCity;  
  $shippostalcode = (string)$orders->ShipPostalCode;
  $salestax1      = (float)$orders->salestax1;
  $salestaxrate1  = (string)$orders->salestaxrate1;
  $totalpayment   = (float)$orders->Total_Payment_Received;
  $paymentdate    = date("m/d/Y", strtotime((string)$orders->pay_authdate)); 
  $exempt_tax_num = (string)$orders->Custom_Field_Custom3;
  $total_wo_tax   = $totalpayment - $salestax1;

  //$productcode    = (string)$orders->productcode; 
  //$productname    = (string)$orders->productname;
  //$revenue        = (string)$orders->Revenue;
  //$cleanrevenue   = money_format('%.2n', $revenue); 
 /*
  $orders_arr[$orderid]['orderid']        = $orderid;
  $orders_arr[$orderid]['customerid']     = $customerid; 
  $orders_arr[$orderid]['orderdate']      = $orderdate;
  $orders_arr[$orderid]['shipstate']      = $shipstate;
  $orders_arr[$orderid]['shippostalcode'] = $shippostalcode;
  $orders_arr[$orderid]['salestax1']      = $salestax1;
  $orders_arr[$orderid]['salestaxrate1']  = $salestaxrate1; 
  $orders_arr[$orderid]['totalpayment']   = $totalpayment;
  $orders_arr[$orderid]['paymentdate']    = $paymentdate;
  $orders_arr[$orderid]['exampttaxnum']   = $exempt_tax_num; 
  */

  //$exempt_tax_num = (empty($exempt_tax_num)) ? 0 : $exempt_tax_num;

  if(!empty($SSTstates[$shipstate])):
      $ProcessCode = 3;
      $show_tax = "";
  else:
      $ProcessCode = 1;
      $show_tax = $total_tax;
  endif;





  // ADD ORDER ID TO ARRAY AND PREVENT DUPLICATES -----------------------------------------------------------------------------
  if(!in_array($orderid, $orders_arr)):
    $orders_arr[] = $orderid;

    $total_tax = $total_tax + (float)$salestax1;
    $total_pay = $total_pay + (float)$totalpayment;
    $Header_Description = "Tax for ".$shipstate;  
    $total_count++;



    // CREATE CSV FILE
    if(!empty($CreateCSV)):
      $csv_file[] = $ProcessCode . ',Ord'.$orderid.',1,'.$paymentdate.',,'.$customerid.',,1,,,,,,'.$total_wo_tax.',,,,'.$exempt_tax_num.',,,,'.$shipstate.','.$shippostalcode.',US,,,NC,28613,,,,,,,,,,,,,,'.$show_tax.',,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,FALSE,,,,,,,,,'.$Header_Description.',';            
    endif;

      $showExemptIndicator = false;
    
 if ($salestax1 == 0)
 {
    $exemptTotal = $exemptTotal + $total_wo_tax;
    $exemptGT = $exemptGT + $exemptTotal;
    $showExemptIndicator = true;
    //echo $exemptGT."\n";
 }
$exemptTotal = 0;

    // ESTIMATED TAXES
    $shipzip = substr(trim($shippostalcode),0,5);
    $useTaxRate = ($cvs_file[$shipzip]['tax_r']) ? $cvs_file[$shipzip]['tax_r'] : 7;
    $estimated_tax = (($useTaxRate / 100) * $totalpayment);
    $totaltaxamount = $totaltaxamount + $salestax1;
    $totalPretaxAmount = $totalPretaxAmount + $total_wo_tax;
if ($showExemptIndicator) 
{
$table_data .= '<tr><td>'.$total_count.'</td><td><a add target="_blank" href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$orderid.'">'.$orderid.'</a></td>';
    $table_data .= '<td>'.$orderdate.'</td><td>'.$paymentdate.'</td><td>'.$shipstate.'</td><td>'.$shipcity.'</td><td rel="'.$shippostalcode.'"><a add target="_blank" href="http://www.sale-tax.com/'.$shipzip.'">'.$shipzip.'</a></td><td><div class="exempt rounded-circle"></div></td><td>'.money_format('%.2n', $total_wo_tax).'</td><td>'.money_format('%.2n', $salestax1).'</td><td>'.$useTaxRate.'%</td><td>'.money_format('%.2n', $totalpayment).'</td></tr>';
}
else    
{
   $table_data .= '<tr><td>'.$total_count.'</td><td><a add target="_blank" href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$orderid.'">'.$orderid.'</a></td>';
    $table_data .= '<td>'.$orderdate.'</td><td>'.$paymentdate.'</td><td>'.$shipstate.'</td><td>'.$shipcity.'</td><td rel="'.$shippostalcode.'"><a add target="_blank" href="http://www.sale-tax.com/'.$shipzip.'">'.$shipzip.'</a></td><td>&nbsp;</div></td><td>'.money_format('%.2n', $total_wo_tax).'</td><td>'.money_format('%.2n', $salestax1).'</td><td>'.$useTaxRate.'%</td><td>'.money_format('%.2n', $totalpayment).'</td></tr>'; 
}

  endif;
  

endforeach;


 if(!empty($CreateCSV)):
  // OUT PUT THE CVS FILE TO DOWNLOAD --------
    $namefile = "Avalara_tax_upload_".date('m')."_".date('d')."_".date('Y').".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$namefile.'"');
    $fp = fopen('php://output', 'wb');
    foreach ( $csv_file as $line ) {
      $val = explode(",", $line);
      fputcsv($fp, $val);
    }
    fclose($fp);
    exit;
  endif;



?><!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Everything Attachments Order Reports Menu</title>
  <style>
            @media print {
          a[href]:after {
            content: none !important;
            size: auto !important;
          }
        }
      @page { /* Bootstrap 4 prevents Landscape option in Chrome, this fixes that */
  size: auto !important;

}
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
      font-size: 16px;
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
      padding: 2px 5px;
      font-size: 14px;
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
    .exempt {
    width: 25px;
    height: 25px;
    background-color: red;
    text-align: center;
        margin-left: 25px;
  }
}
    </style>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body>

  <form method="post">
    <div id="choosedata" style="margin-top:30px;">
      <input type="hidden" name="getinfo" value="1" />
      <label for="choosestate"> Select State:</label>
      <select id="choosestate" name="choosestate">
        <option <?php if($GetState==='all'){ echo 'selected'; }?> value='all'>All</option>
        <?php 
          foreach( $states as $key => $value ){
            if($key==$GetState){ $stateselectclass = "selected"; }else{ $stateselectclass = ""; }
            echo '<option value="'.$key.'" title="'.$value.'" '.$stateselectclass.'>'.$key.'</option>';
          }                    
        ?>
      </select>&nbsp;&nbsp; &nbsp;&nbsp;
      
      <label for="startyear"> Start Year:</label>
      <select id="startyear" name="startyear">
        <?php 
            $maxYear = date("Y");
            for ($x = 2012; $x <= $maxYear; $x++) {
              if($x==$StartYear){$yearselectclass = "selected"; }else{ $yearselectclass = ""; }
              echo  '<option value="'.$x.'" '.$yearselectclass.'>'.$x.'</option>';
            } 
        ?>
      </select>&nbsp;&nbsp;
      
      <label for="endyear"> End Year:</label>
      <select id="endyear" name="endyear">
        <?php 
            $maxYear = date("Y");
            for ($x = 2012; $x <= $maxYear; $x++) {
              if($x==$EndYear){$yearselectclass = "selected"; }else{ $yearselectclass = ""; }
              echo  '<option value="'.$x.'" '.$yearselectclass.'>'.$x.'</option>';
            } 
        ?>
      </select>&nbsp;&nbsp; 
    
      <label for="startmonth"> Start Month:</label>
      <select id="startmonth" name="startmonth">
        <option <?php if($StartMonth==='01'){ echo 'selected'; }?> value='01'>Janaury</option>
        <option <?php if($StartMonth==='02'){ echo 'selected'; }?> value='02'>February</option>
        <option <?php if($StartMonth==='03'){ echo 'selected'; }?> value='03'>March</option>
        <option <?php if($StartMonth==='04'){ echo 'selected'; }?> value='04'>April</option>
        <option <?php if($StartMonth==='05'){ echo 'selected'; }?> value='05'>May</option>
        <option <?php if($StartMonth==='06'){ echo 'selected'; }?> value='06'>June</option>
        <option <?php if($StartMonth==='07'){ echo 'selected'; }?> value='07'>July</option>
        <option <?php if($StartMonth==='08'){ echo 'selected'; }?> value='08'>August</option>
        <option <?php if($StartMonth==='09'){ echo 'selected'; }?> value='09'>September</option>
        <option <?php if($StartMonth==='10'){ echo 'selected'; }?> value='10'>October</option>
        <option <?php if($StartMonth==='11'){ echo 'selected'; }?> value='11'>November</option>
        <option <?php if($StartMonth==='12'){ echo 'selected'; }?> value='12'>December</option>
       </select> &nbsp;&nbsp; 
      
      
      <label for="endmonth"> End Month:</label>
      <select id="endmonth" name="endmonth">
        <option <?php if($EndMonth==='01'){ echo 'selected'; }?> value='01'>Janaury</option>
        <option <?php if($EndMonth==='02'){ echo 'selected'; }?> value='02'>February</option>
        <option <?php if($EndMonth==='03'){ echo 'selected'; }?> value='03'>March</option>
        <option <?php if($EndMonth==='04'){ echo 'selected'; }?> value='04'>April</option>
        <option <?php if($EndMonth==='05'){ echo 'selected'; }?> value='05'>May</option>
        <option <?php if($EndMonth==='06'){ echo 'selected'; }?> value='06'>June</option>
        <option <?php if($EndMonth==='07'){ echo 'selected'; }?> value='07'>July</option>
        <option <?php if($EndMonth==='08'){ echo 'selected'; }?> value='08'>August</option>
        <option <?php if($EndMonth==='09'){ echo 'selected'; }?> value='09'>September</option>
        <option <?php if($EndMonth==='10'){ echo 'selected'; }?> value='10'>October</option>
        <option <?php if($EndMonth==='11'){ echo 'selected'; }?> value='11'>November</option>
        <option <?php if($EndMonth==='12'){ echo 'selected'; }?> value='12'>December</option>
       </select> 
      
    </div>
            <div class="h4" style="font-size: 21px; text-align:center;margin-top:5px;"><?php echo $total_count ?> Items &nbsp;&nbsp;</div>
      
    <p style="text-align:center;margin:30px;"> 
      <label style="text-align:center;margin-left: -60px;margin-top: 4px;"  for="getinfo">Create CSV File <input id="getinfo" type="checkbox" name="createcsv"></label>
        <input class="btn btn-success" type="submit" value="Get Tax Data" />  
      <a href="upload-taxes.php" id="vtdata" title="Volusion Tax Data"></a>
    </p>
  </form>
  
  
 <table id="frighttable" style="margin-top:30px;">
   <tr><th style="text-align: center;">#</th><th style="text-align: center;">Order ID</th><th style="text-align: center;">Inv Date</th><th style="text-align: center;">Pay Date</th><th style="text-align: center;">State</th><th style="text-align: center;">City</th><th style="text-align: center;">Zip Code</th><th style="text-align: center;">Exempt</th><th style="text-align: center;">Pre-Tax</th><th style="text-align: center;">Tax</th><th style="text-align: center;">Rate</th><th style="text-align: center;">Total Payment</th></tr>
   
   <?php echo $table_data; ?>
   <tr><td></td><td></td><td></td><td></td><td></td><td></td><td><td><?php echo  money_format('%.2n', $exemptGT);?></td><td><?php echo  money_format('%.2n', $totalPretaxAmount); ?></td><td><?php echo  money_format('%.2n', $totaltaxamount); ?></td><td></td><td><?php echo money_format('%.2n', $total_pay); ?></td></tr>
  </table>
  <br /><br />
  
  <br /><br />
 </body>
</html>


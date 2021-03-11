<?php 
// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";

  $CurrentYear  = date("Y");
  $CurrentMonth = date("m", strtotime("-1 months"));

//if(empty($_SESSION['loggedin'])):
//  header('Location: http://inventory.corimpco.net/factory/login.php'); exit;
//endif;


// GET DATA ABOUT ORDER ID FROM VOLUSION -----------------------------------------------------------------------------------------------------------------
//if(!empty($_GET['getinfo']) && !empty($_GET['orderid']) && ($_SESSION['loggedin']=="admin")):  



$thismonth = date("m");
$thisyear = date("Y");


$get_start_month = isset($_GET['startmonth']) ? $_GET['startmonth'] : $thismonth;
$get_start_year = isset($_GET['startyear']) ? $_GET['startyear'] : $thisyear;
$get_end_month = isset($_GET['endmonth']) ? $_GET['endmonth'] : $thismonth;
$get_end_year = isset($_GET['endyear']) ? $_GET['endyear'] : $thisyear;


$asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/productcount.asp?startmonth=".$get_start_month."&startyear=".$get_start_year."&endmonth=".$get_end_month."&endyear=".$get_end_year."");

//echo file_get_contents("https://www.everythingattachments.com/v/vspfiles/schema/Generic/customer_list.asp?startmonth=04&startyear=2019&endmonth=04&endyear=2019");




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
      
    </style>
</head>
<body>
  <br /><br />
 <table id="frighttable">
   <tr><th>Product Name</th><th>Quantity Sold</th><th>Revenue</th></tr>
   
<?php
$products_arr = array();
$remove_arr = array("residential","Delivery","shipipng","farm delivery");   
  //echo 'https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\productcount'; exit;
   
   //https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\productcount
   
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\productcount');
   
foreach($xml->Table as $product):
  $productcode   = (string)$product->productcode;
  $productname   = (string)$product->productname;
  $quantitysold  = (string)$product->QuantitySold;
  $revenue       = (string)$product->Revenue;
  $cleanrevenue  = money_format('%.2n', $revenue); 
 
  $products_arr[$productcode]['name'] = $productname;
  $products_arr[$productcode]['qty']  = $quantitysold;
  $products_arr[$productcode]['rev']  = $revenue;
  $products_arr[$productcode]['crev'] = $cleanrevenue;
   
   //echo '<tr><td>'.$productname.'</td><td>'.$quantitysold.'</td><td>'.$cleanrevenue.'</td></tr>';

endforeach;
   
   
   
   
   $products_by_qty = [];
    foreach($products_arr as $key => $aproduct) {
        if(!isset($products_by_qty[$aproduct['qty']])) {
            $products_by_qty[$aproduct['qty']] = [];
        }
        array_push($products_by_qty[$aproduct['qty']], $aproduct['$productname']);
    }

    var_dump($products_by_qty);
   
   
   foreach ($products_by_qty as $key => &$genre) {
        usort($genre, function($a,$b){
            return ($a["noofpages"] <= $b["noofpages"]) ? -1 : 1;
        });
    }

    var_dump($products_by_qty);
   
   
   
   
   foreach($products_arr as $thisproduct):
      echo '<tr><td>'.$thisproduct['name'].'</td><td>'.$thisproduct['qty'].'</td><td>'.$thisproduct['crev'].'</td></tr>';
   endforeach;

?>
  </table>
 </body>
</html>



























<?



if($dontshow):

// include('product_list.php');  

  $indexPage = curl_get_productinfo($getdata);
  $xml = new SimpleXMLElement($indexPage);


  foreach ($xml->Orders as $element):
    // ASSIGN VALUES
    $OrderID        = $element->OrderID; 
    $OrderDate      = $element->OrderDate;
    $LastModified   = $element->LastModified;
    $OrderStatus    = $element->OrderStatus;
    $ShipState      = $element->ShipState;
    $Order_Comments = $element->Order_Comments;
    $CutomerName    = ucwords($element->BillingFirstName).' '.ucwords($element->BillingLastName);
    
    //$ProductNote    = $element->ProductNote;
    //$ProductOptions = $element->Options;
    //$CutomLineItem  = $element->CustomLineItem;

    $OrderDate      = date("m/d/Y", strtotime($OrderDate));

    foreach ($element->OrderDetails as $products):
      $ProductCode = trim($products->ProductCode);
      $getProductName .= $ProductCode.' - '.$products->ProductName."\r\n";
      $checkProduct = ""; //Initializing variable
      $checkProduct = isset($products_array[$ProductCode]) ? htmlspecialchars($products_array[$ProductCode]) : '';
      if(!empty($checkProduct)):
        $safecode = str_replace('.', '_', $ProductCode);
        $ProductName .= '<label id="l-'.$safecode.'">Product Info:</label>';
        $ProductName .= '<input type="text" class="productdata" name="od_products['.$ProductCode.']" id="v-'.$safecode.'" value="'.$checkProduct.'" />';
        $ProductName .= '<div id="x-'.$safecode.'" class="removecode">X</div><br />';
        $ProductName .= '<div id="d-'.$safecode.'" class="displaycode">'.$ProductCode.' <div class="quickwordlink" data-w="'.$safecode.'">Key Words</div>'; 
        $ProductName .= '<div id="q-'.$safecode.'" class="quickwords" data-w="'.$safecode.'">'.$quickwords.'</div></div>';
      endif;
    endforeach;

      $tabledata .= '<form method="post" id="infoform">';
      $tabledata .= '<div id="orderiddiv">Order ID: <span>'.$OrderID.'</span></div>';
      $tabledata .= '<input type="submit" id="saveinfo" value="Save Info" />';
      $tabledata .= '<input type="hidden" name="savedata" value="'.$OrderID.'" />';
      $tabledata .= '<label>Request Date:</label><input type="text" name="od_date" id="requestdate" style="width:120px;" value="'.$OrderDate.'" />';
      $tabledata .= '<div id="todaysdate" data-date="'.date("m/d/Y", strtotime("now")).'">Todays Date</div>';
      $tabledata .= '<a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$OrderID.'" target="_new" class="vlink">Volusion</a><br />';
      $tabledata .= '<label>Customer Name:</label><input type="text" name="od_customer" id="customername" style="width:250px;" value="'.$CutomerName.'" /><br />';
      if($ProductName):
        $tabledata .= $ProductName;
      else:
      
        $tabledata .= '<label>Product Code:</label>';
        $tabledata .= '<input type="text" class="productdata" name="new_product_code" value="" />';
        $tabledata .= '<label>Product Name:</label>';
        $tabledata .= '<input type="text" class="productdata" name="new_product_name" id="v-newcode" value="" />';
        $tabledata .= '<div id="d-newcode" class="displaycode"> <div class="quickwordlink" data-w="newcode">Key Words</div>'; 
        $tabledata .= '<div id="q-newcode" class="quickwords" data-w="newcode">'.$quickwords.'</div></div>';

      endif;
  //$tabledata .= '<label>Received Date:</label><input type="text" name="od_received" id="receiveddate" style="width:120px;" value="" />';
      $tabledata .= '<br /><hr /><label>Order Info:</label><br /><textarea disabled="disabled">'.$getProductName.'</textarea><br /><br />';
      $tabledata .= '<label>Order Note:</label><br /><textarea disabled="disabled">'.$Order_Comments.'</textarea></form>';

  endforeach;

    echo '<div id="results"><hr />'.$tabledata.$tabledatax.'</div>';
   
//endif;

  // END IF ORDER ID --------------------------------------------------------------------------------------------------------------------------------------
  


//include ("db_info.php");



?><!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Reports</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style>
      <?php include('styles.css'); ?>
    </style>
    <script>
      <?php  //include('javascript.js');  ?>
    </script>
 </head>
<body><div id="overlay"></div>
 

 <div id="maindiv">
      <div id="content">

  
<p><a href="yest_report.php">View Sales Report From Yesterday.</a></p>
<form name="form1" method="get" action="by_date.php">
  <p>
    <select name="month" id="month">
      <option value="01" selected>January</option>
      <option value="02">February</option>
      <option value="03">March</option>
      <option value="04">April</option>
      <option value="05">May</option>
      <option value="06">June</option>
      <option value="07">July</option>
      <option value="08">August</option>
      <option value="09">September</option>
      <option value="10">October</option>
      <option value="11">November</option>
      <option value="12">December</option>
    </select>
    <select name="day" id="day">
      <option value="01">01</option>
      <option value="02">02</option>
      <option value="03">03</option>
      <option value="04">04</option>
      <option value="05">05</option>
      <option value="06">06</option>
      <option value="07">07</option>
      <option value="08">08</option>
      <option value="09">09</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
      <option value="13">13</option>
      <option value="14">14</option>
      <option value="15">15</option>
      <option value="16">16</option>
      <option value="17">17</option>
      <option value="18">18</option>
      <option value="19">19</option>
      <option value="20">20</option>
      <option value="21">21</option>
      <option value="22">22</option>
      <option value="23">23</option>
      <option value="24">24</option>
      <option value="25">25</option>
      <option value="26">26</option>
      <option value="27">27</option>
      <option value="28">28</option>
      <option value="29">29</option>
      <option value="30">30</option>
      <option value="31">31</option>
    </select>
    <select name="year" id="year">
      <option value="2008">2008</option>
      <option value="2009">2009</option>
      <option value="2010">2010</option>
      <option value="2011">2011</option>
      <option value="2012">2012</option>
      <option value="2013" selected>2013</option>
    </select>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Go">
  </p>
</form>
<p></p>
        
        
   </div>
  </div>
  </body>
</html>

<?php endif; ?>
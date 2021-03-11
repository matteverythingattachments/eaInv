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
//  header('Location: login.php'); exit;
//endif;


// GET DATA ABOUT ORDER ID FROM VOLUSION -----------------------------------------------------------------------------------------------------------------
//if(!empty($_GET['getinfo']) && !empty($_GET['orderid']) && ($_SESSION['loggedin']=="admin")):  

  // GET ORDER INFORMATION FROM VOLUSION 
  function curl_get_productinfo($url){
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    $raw=curl_exec($ch);
    //print_r(curl_getinfo($ch));
    curl_close ($ch);
    return $raw;
  }
  

  $getdata = 'https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\Orders&SELECT_Columns=od.OrderID,od.ProductName,od.ProductNote,od.Options,od.CustomLineItem,o.OrderID,o.BillingFirstName,o.BillingLastName,o.LastModified,o.OrderDate,o.OrderStatus,o.PaymentAmount,o.Shipped,o.ShipState,o.OrderNotes,o.Order_Comments,o.Total_Payment_Received,o.Custom_Field_Custom5&WHERE_Column=o.Custom_Field_Custom5&WHERE_Value=null';


echo $getdata; exit;

include('product_list.php');  

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
   

  exit;

  // END IF ORDER ID --------------------------------------------------------------------------------------------------------------------------------------
  


//include ("db_info.php");



?><!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Production Information</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style>
      <?php include('styles.css'); ?>
    </style>
    <script>
      <?php  include('javascript.js');  ?>
    </script>
 </head>
<body><div id="overlay"></div>
 


</body>
</html>
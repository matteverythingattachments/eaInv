<?php 
// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";


if(empty($_SESSION['loggedin'])):
  header('Location: login.php'); exit;
endif;

    //Initializing variables
    $checkProduct = $getProductName = $tabledata = $ProductName = ""; 

    if(!empty($_SESSION['loggedin'])):
      if($_SESSION['loggedin']!=="admin"):
        $disableinput = 'disabled="disabled"';
        $hideelement = 'style="display:none;"';
      endif;
    endif;



  // GET ORDER INFORMATION FROM VOLUSION 
  function curl_get_taxinfo($url){
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
  

  $getdata = 'https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\non-freight-products';

//https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\non-freight-products

$orders_arr    = array();
$product_count = array();

include('product_list.php'); 
include('other_product_list.php');

  $indexPage = curl_get_taxinfo($getdata);
  $xml = new SimpleXMLElement($indexPage);
  $oCount = 1;

  foreach ($xml->Orders as $element):
    // ASSIGN VALUES
    $OrderID        = (string)$element->OrderID; 
    $OrderDate      = $element->OrderDate;
    $OrderStatus    = $element->OrderStatus;
    $CustomerName   = ucwords($element->BillingFirstName).' '.ucwords($element->BillingLastName);
    $ProductCode    = strtoupper((string)$element->productcode);
    $ProductName    = (string)$element->productname;

    $OrderDate      = date("m/d/Y", strtotime($OrderDate));

    $orders_arr[$oCount]['OrderID']      = $OrderID;
    $orders_arr[$oCount]['OrderDate']    = $OrderDate;
    $orders_arr[$oCount]['CustomerName'] = $CustomerName;
    $orders_arr[$oCount]['ProductName']  = $ProductName;
    $orders_arr[$oCount]['ProductCode']  = $ProductCode;

    if (array_key_exists($ProductCode,$products_array2)):
      $ProductCode = $products_array2[$ProductCode];
    endif;

    $product_count[$ProductCode][] = $OrderID;

    $oCount++;
  endforeach;


//print_r($product_count); exit;

$totalpcount = count($orders_arr);  

// LOOP THROUGH ORDERS TO CREATE TABLE LIST
foreach($orders_arr as $key => $order):
    $this_p_code = $order['ProductCode'];

    if (array_key_exists($this_p_code,$products_array2)):
      $UseCode = $products_array2[$this_p_code];
    else:
      $UseCode = $this_p_code;
    endif;


    $datediff  = (strtotime("now") - strtotime($order['OrderDate']));
    $days_waiting = round($datediff / (60 * 60 * 24));

    $pcount1 = array_search($order['OrderID'], $product_count[$UseCode]);
    $pcount1++;
    $pcount2 = count($product_count[$UseCode]);

    $tabledata .= '<tr id="tr_'.$key.'" class="orders"><td>'.$order['OrderDate'].'</td>';
    $tabledata .= '<td><a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$order['OrderID'].'" target="_blank" class="productlink" title="'.$key.' out of '.$totalpcount.' in total">'.$order['OrderID'].'</a></td>';
 
    $tabledata .= '<td>'.$order['CustomerName'].'</td>';
    $tabledata .= '<td><a href="https://www.everythingattachments.com/ProductDetails.asp?ProductCode='.$this_p_code.'" title="Number '.$pcount1.' out of '.$pcount2.' to be built">'.$products_array[$this_p_code].'</a></td>';      
    $tabledata .= '<td class="center">'.$days_waiting.'</td></tr>';
  
endforeach;
//EA-GARDEN-BEDDER-GB60
//print_r($product_count); exit; 

?><!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Production Schedule for Non Freight Products</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style>
      <?php include('styles.css'); ?>
    </style>
    <style>
      #tabledata th:nth-child(4), #tabledata td:nth-child(4){ max-width: 600px; }
      #tabledata th:nth-child(5), #tabledata td:nth-child(5){ max-width: 105px; }
    </style>
    <script>
      <?php  include('javascript.js');  ?>
    </script>
 </head>
<body>

<div id="searchorderbox">
  <form id="formsearchid">
    <input type="text" id="searchoid" placeholder="Order #" /><br /> 
    <input type="submit" class="serchboxbutton" value="FIND" />
  </form>
</div>

<div id="tabledatadiv">
  <table id="tabledata">
    <tr class="fixed">
      <th>Request Date</th>
      <th>Order ID</th>
      <th>Customer</th>
      <th id="productcell">Product&nbsp;&nbsp;(<?php echo $totalpcount; ?> items)<a href="index.php" title="Freight Products" style="padding: 0 15px 0 200px;color:#ffeb3b;">Freight Products</a> </th>
     
      <th class="center topth">Days Waiting</th>
      
      
     </tr>
     <tr><td><div id="divtd"></div></td><td></td><td></td><td class="center" id="cellsize"></td><td></td></tr>
    <?php echo $tabledata; ?>
    <?php for ($x = 0; $x <= 10; $x++): ?>
     <tr><td><div style="height: 15px;width:10px;"></div></td><td></td><td></td><td class="center"></td><td></td></tr>
    <?php endfor; ?>
  </table>
  <br /><br /><br />
</div>
<div id="bottombar">
  <a href="/factory/" <?php if(empty($_GET['complete'])){ echo 'class="selected"';} ?> title="Factory Product Schedule LTL">Factory Product Schedule LTL</a>
  <a href="/factory/?complete=1" <?php if(!empty($_GET['complete'])){ echo 'class="selected"';} ?> title="Complete Factory to Assembly">Complete Factory to Assembly</a>
  
  <div id="loginform">
    <?php if(!empty($_SESSION['loggedin']) ): ?>
    <a href="logout.php" title="Log out" />Log out<a></a>
    <?php else: ?>
    <span id="loginlink">Login</span>
    <div id="login">
      <form method="post"><input type="hidden" name="send" value="1" />
        <input type="text" name="username" placeholder="User Name" value="" /><br />
        <input type="password" name="password" placeholder="Password" value="" /><br />
        <input type="submit" value="Login" />
      </form>
    </div>
  <?php endif; ?>  
  </div>
  
</div>

</body>
</html>
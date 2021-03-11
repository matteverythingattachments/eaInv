<?php 


include('product_list.php');
include ('db_info');
foreach($products_array as $item) {
    echo $item;
    echo '<br />';
}
exit;

// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "965B036361DAAEBAB229F73BAABE29C00E4BDDC1282B4A861B0DDAFB2683C6DE";
  $vUserName = "scott@everythingattachments.com";


if(empty($_SESSION['loggedin'])):
  header('Location: login.php'); exit;
endif;

    //Initializing variables
    $checkProduct = $getProductName = $tabledata = $ProductName = ""; 
    $quickwords = '<div>(Black)</div><div>w/ shielding</div><div>(Green)</div><div>(JD)</div><div>(Orange)</div><div>(Quick Attach)</div><div>(USSQA)</div>';

    if(!empty($_SESSION['loggedin'])):
      if($_SESSION['loggedin']!=="admin"):
        $disableinput = 'disabled="disabled"';
        $hideelement = 'style="display:none;"';
      endif;
    endif;


// GET DATA ABOUT ORDER ID FROM VOLUSION -----------------------------------------------------------------------------------------------------------------
if(!empty($_GET['getinfo']) && !empty($_GET['orderid']) && ($_SESSION['loggedin']=="admin")):  

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
  

  $getdata = 'https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\Orders&SELECT_Columns=od.OrderID,od.ProductName,od.ProductNote,od.Options,od.CustomLineItem,o.OrderID,o.BillingFirstName,o.BillingLastName,o.LastModified,o.OrderDate,o.OrderStatus,o.PaymentAmount,o.Shipped,o.ShipState,o.OrderNotes,o.Order_Comments,o.Total_Payment_Received&WHERE_Column=o.OrderID&WHERE_Value='.$_GET['orderid'];


include('product_list.php');  

  $indexPage = curl_get_taxinfo($getdata);
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

  if(empty($tabledata)):
      $tabledata .= '<form method="post" id="infoform">';
      $tabledata .= '<div id="orderiddiv">Order ID: <span>'.$_GET['orderid'].'</span></div>';
      $tabledata .= '<p>Couldn\'t find Order! Manually enter Info.</p><hr /><br />';
      $tabledata .= '<input type="submit" id="saveinfo" value="Save Info" />';
      $tabledata .= '<input type="hidden" name="savedata" value="'.$_GET['orderid'].'" />';
      $tabledata .= '<label>Request Date:</label><input type="text" name="od_date" id="requestdate" style="width:120px;" value="'.$OrderDate.'" />';
      $tabledata .= '<div id="todaysdate" data-date="'.date("m/d/Y", strtotime("now")).'">Todays Date</div><br />';
      $tabledata .= '<label>Customer Name:</label><input type="text" name="od_customer" id="customername" style="width:250px;" value="'.$CutomerName.'" /><br />';
      

        $ProductName .= '<label id="l-xxx">Product Info:</label>';
        $ProductName .= '<input type="text" class="productdata" name="od_products[Not-Found]" id="v-xxx" value="" /><br />';
        $ProductName .= '<div id="d-xxx" class="displaycode">Not-Found <div class="quickwordlink" data-w="xxx">Key Words</div>'; 
        $ProductName .= '<div id="q-xxx" class="quickwords" data-w="xxx">'.$quickwords.'</div></div>';


      $tabledata .= $ProductName;
      $tabledata .= '<br /><hr /><br /><br /></form>';
  endif; 

    echo '<div id="results"><hr />'.$tabledata.$tabledatax.'</div>';
   

  exit;
endif;
  // END IF ORDER ID --------------------------------------------------------------------------------------------------------------------------------------
  


include ("db_info.php");



// SHOW DATABASE INFO FOR SINGLE POST DATA -----------------------------------------------------------------------------------------------------------------------------------------
if(!empty($_GET['editinfo']) && !empty($_GET['orderid'])): 
  $editinfo = $_GET['editinfo'];
  $getquery = "SELECT * FROM production WHERE id = '$editinfo'";
  $get_data = mysqli_query($conn, $getquery);
 
	while ($data = mysqli_fetch_assoc($get_data)):

      $data_id           = $data['id'];
      $data_order_id     = $data['order_id'];
      $data_request_date = $data['request_date'];
      $data_product      = htmlspecialchars($data['product']);
      $data_product_code = $data['product_code'];
      $data_customername = $data['customer'];
      $data_note         = $data['note'];

      if($data['received']=="0000-00-00"):
        $data_received   = "";
        $remove_complete = "";
        $remove_c_form   = "";
      else:
        $data_received   = date("m/d/Y", strtotime($data['received']));
        $remove_complete = '<div class="remove_complete" '.$hideelement.'>Remove From Completed</div>';
        $remove_c_form   = '<form method="post" id="remove_c_form" style="display:none;"><input type="hidden" name="removecompleted" value="'.$data_id.'" /></form>';
      endif;
      


      $safecode = str_replace('.', '_', $data_product_code);
      $ProductName .= '<label id="l-'.$data_product_code.'">Product Info:</label>';
      $ProductName .= '<input type="text" class="productdata" name="od_products['.$data_product_code.']" id="v-'.$safecode.'" value="'.$data_product.'" '.$disableinput.' />';
      $ProductName .= '<div id="x-'.$safecode.'" class="removecode" '.$hideelement.'>X</div><br />';
      $ProductName .= '<div id="d-'.$safecode.'" class="displaycode">'.$data_product_code.' <div class="quickwordlink" data-w="'.$safecode.'" '.$hideelement.'>Key Words</div>'; 
      $ProductName .= '<div id="q-'.$safecode.'" class="quickwords" data-w="'.$safecode.'">'.$quickwords.'</div></div>';

      $tabledata .= '<form method="post" id="receivedform">';
      $tabledata .= '<input type="hidden" name="data_id" value="'.$data_id.'" />';
      $tabledata .= '<input type="hidden" name="receiveddate" value="'.date("m/d/Y", strtotime("now")).'" id="hidereceiveddate" />';
      $tabledata .= '<input type="submit" id="markreceived" value="Received" '.$hideelement.' /></form>';

      $tabledata .= '<form method="post" id="infoform">';
      $tabledata .= '<div id="orderiddiv">Order ID: <span>'.$data_order_id.'</span></div>';
      $tabledata .= '<input type="submit" id="saveinfo" value="Save Info" />';
      $tabledata .= '<input type="hidden" name="data_id" value="'.$data_id.'" id="data_id" />';
      $tabledata .= '<input type="hidden" name="savedata" value="'.$data_order_id.'" />';
      $tabledata .= '<label>Request Date:</label><input type="text" name="od_date" id="requestdate" style="width:120px;" value="'.date("m/d/Y", strtotime($data_request_date)).'" '.$disableinput.' />';
      $tabledata .= '<div id="todaysdate" data-date="'.date("m/d/Y", strtotime("now")).'"><img src="images/calendar.png" alt="Todays Date" '.$hideelement.' /></div>';
      $tabledata .= '<a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$data_order_id.'" target="_new" class="vlink">Volusion</a><br />';
      $tabledata .= '<label>Customer Name:</label><input type="text" name="od_customer" id="customername" style="width:250px;" value="'.$data_customername.'" '.$disableinput.' /><br />';
      $tabledata .= $ProductName;
      $tabledata .= '<label>Received Date:</label><input type="text" name="od_received" id="receiveddate" style="width:120px;" value="'.$data_received.'" '.$disableinput.' />';
      $tabledata .= '<div id="todaysdate2" data-date="'.date("m/d/Y", strtotime("now")).'"><img src="images/calendar.png" alt="Todays Date" '.$hideelement.' /></div>';
      $tabledata .= $remove_complete.'<br />';
      $tabledata .= '<label>Factory Notes:</label><input type="text" name="factory_notes" value="'.$data_note.'" /><br />';
      $tabledata .= '<br /></form><hr />'.$remove_c_form;

      $tabledatax .= '<form method="post" id="deleteform" '.$hideelement.'>';
      $tabledatax .= '<div id="deleteconfirm"><p>Confirm Delete</p><div id="confirmyes" class="conbutton">Yes</div><div id="confirmno" class="conbutton">No</div></div>';
      $tabledatax .= '<input type="hidden" name="deleteproduct" value="'.$data_id.'" />';
      $tabledatax .= '<input type="submit" id="markdelete" value="Delete" /></form>';

   endwhile;


   // GET ORDER INFORMATION FROM VOLUSION ---------------------------------------------------------------------------------------------------------------
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


  $getdata = 'https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\Orders&SELECT_Columns=od.OrderID,od.ProductName,od.ProductNote,od.Options,od.CustomLineItem,o.OrderID,o.BillingFirstName,o.BillingLastName,o.LastModified,o.OrderDate,o.OrderStatus,o.PaymentAmount,o.Shipped,o.ShipState,o.OrderNotes,o.Order_Comments,o.Total_Payment_Received&WHERE_Column=o.OrderID&WHERE_Value='.$data_order_id;


include('product_list.php');  

  $indexPage = curl_get_taxinfo($getdata);
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
    $OrderDate      = date("m/d/Y", strtotime($OrderDate));

    foreach ($element->OrderDetails as $products):
      $ProductCode = trim($products->ProductCode);
      $getProductName .= $ProductCode.' - '.$products->ProductName."\r\n";
      $checkProduct = ""; //Initializing variable
      $checkProduct = isset($products_array[$ProductCode]) ? htmlspecialchars($products_array[$ProductCode]) : '';
      if(!empty($checkProduct)):
        if($ProductCode !== $data_product_code):
          // CHECK IF PRODUCT IS IN DATABASE
          $query = "SELECT * FROM production WHERE (order_id='$OrderID' AND product_code LIKE '$ProductCode') LIMIT 1";
          $result = mysqli_query($conn, $query);
          $row = mysqli_fetch_assoc($result);
          if(empty($row)):
            $ProductName2 .= '<form method="post">';
            $ProductName2 .= '<input type="submit" id="updatechange" value="Update Change" />';
            $ProductName2 .= '<input type="hidden" name="data_id" value="'.$data_id.'"  />';
            $ProductName2 .= '<input type="hidden" name="changeproduct" value="'.$data_id.'"  />';
            $ProductName2 .= '<label id="l-'.$ProductCode.'">Product Info:</label>';
            $ProductName2 .= '<input type="text" class="productdata" name="od_products['.$ProductCode.']" id="v-'.$ProductCode.'" value="'.$checkProduct.'" />';
            $ProductName2 .= '<div id="d-'.$ProductCode.'" class="displaycode">'.$ProductCode.'</div></form>';
          endif;
        endif;
      endif;
    endforeach;

      if(!empty($ProductName2)):
        $tabledata .= '<div style="font-weight:bold;color:#f00;padding:4px 0;text-align:center;">Products Not Listed In Production Schedule</div>';
        $tabledata .= $ProductName2;
        $tabledata .= "<br /><hr />";
      endif;

      $tabledata .= '<label>Order Info:</label><br /><textarea disabled="disabled">'.$getProductName.'</textarea><br /><br />';
      $tabledata .= '<label>Order Note:</label><br /><textarea disabled="disabled">'.$Order_Comments.'</textarea>';

  endforeach;
  
  if(empty($tabledata)):
    echo '<div id="results"><hr />Nothing Found!</div>';
  else:
    echo '<div id="results"><hr />'.$tabledata.$tabledatax.'</div>';
  endif;
  exit;
endif;


// SAVE NEW POST DATA --------------------------------------------------------------------------------------------------------------------
if(!empty($_POST['savedata']) && ($_SESSION['loggedin']=="admin" || $_SESSION['loggedin']=="nate")):
  $post_orderid        = !empty($_POST['savedata']) ? $_POST['savedata'] : '';
  $post_customername   = !empty($_POST['od_customer']) ? $_POST['od_customer'] : '';
  $post_product_array  = !empty($_POST['od_products']) ? $_POST['od_products'] : '';
  $post_factory_note   = !empty($_POST['factory_notes']) ? $_POST['factory_notes'] : '';

  // IF PRODUCT WASN'T FOUND ADD NEW 
  $post_new_code = $_POST['new_product_code']; 
  $post_new_name = $_POST['new_product_name'];
  
  // IF ADDING NEW PRODUCT ADD IT TO THE PRODUCT ARRAY
  if(!empty($post_new_code)):
    $post_product_array[$post_new_code] = $post_new_name;
  endif;

  // CLEAN VARIABLES
  $post_customername = mysqli_real_escape_string($conn, $post_customername);

  $post_requested_date = !empty($_POST['od_date']) ? $_POST['od_date'] : '';
  if(!empty($post_requested_date)):
    $format_date = date("Y-m-d", strtotime($post_requested_date));
  endif;

  // IF RECEIVED DATE IS FILLED
  $post_received_date   = !empty($_POST['od_received']) ? $_POST['od_received'] : ''; 
  if(!empty($post_received_date)):
    $format_received_date = date("Y-m-d", strtotime($post_received_date));
    $set_received_date = "received='$format_received_date', ";
  endif;

  if(is_array($post_product_array)):
    foreach($post_product_array as $key => $aproduct):
      $aproduct = mysqli_real_escape_string($conn, $aproduct); // CLEAN VARIABLE
      if(!empty($_POST['data_id'])):
         $newid = !empty($_POST['data_id']) ? $_POST['data_id'] : '';
         $updatequery_prodcuts ="UPDATE production SET $set_received_date request_date='$format_date', customer='$post_customername', product='$aproduct', note='$post_factory_note' WHERE id=$newid";
         $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
         if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;

      else:
        // CHECK IF ORDERID AND PRODUCT ID IS IN DATABASE ALREADY
        $query = "SELECT * FROM production WHERE (order_id='$post_orderid' AND product_code LIKE '$key') LIMIT 1";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if($row && $key !== "Not-Found"):
          // UPDATE
          $newid = $row['id'];
          $updatequery_prodcuts ="UPDATE production SET $set_received_date request_date='$format_date', customer='$post_customername', product='$aproduct' WHERE id=$newid";
          $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
          if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;

        else:

          if(!empty($set_received_date)):
            $addproduct ="INSERT INTO production (request_date, order_id, product_code, customer, product, received) VALUES('$format_date','$post_orderid','$key','$post_customername','$aproduct','$format_received_date')";    
            $addnewproduct = mysqli_query($conn, $addproduct) or die (mysqli_error());
            if(!$addnewproduct): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;
          else:
            // ADD NEW
            $addproduct ="INSERT INTO production (request_date, order_id, product_code, customer, product) VALUES('$format_date','$post_orderid','$key','$post_customername','$aproduct')";    
            $addnewproduct = mysqli_query($conn, $addproduct) or die (mysqli_error());
            if(!$addnewproduct): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;
          endif;

        endif;
      endif;

    endforeach;
  endif;

   // SAVE FACTOR NOTES POSTED BY NATE
  if($_SESSION['loggedin']=="nate" && isset($_POST['data_id'])):
    $newid = !empty($_POST['data_id']) ? $_POST['data_id'] : '';
    $updatequery_prodcuts ="UPDATE production SET note='$post_factory_note' WHERE id=$newid";
    $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
    if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;
  else:
    $saved = "yes";
  endif;

endif;  
// END SAVE NEW POST DATA ----------------------------------------------------------------------------------------------------------------


// CHANGE PRODUCT DATA --------------------------------------------------------------------------------------------------------------------
if(!empty($_POST['changeproduct']) && ($_SESSION['loggedin']=="admin")):
  $get_dataid         = !empty($_POST['data_id']) ? $_POST['data_id'] : '';
  $post_product_array = !empty($_POST['od_products']) ? $_POST['od_products'] : '';

  if(is_array($post_product_array)):
    foreach($post_product_array as $key => $aproduct):
      $aproduct = mysqli_real_escape_string($conn, $aproduct);
      if(!empty($get_dataid)):
         $updatequery_prodcuts ="UPDATE production SET product_code='$key', product='$aproduct' WHERE id=$get_dataid";
         $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
         if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;
      endif;
    endforeach;
  endif;
 $saved = "yes";
endif;  
// END CHANGE PRODUCT DATA ----------------------------------------------------------------------------------------------------------------


// MARK AS BEING RECEIVED --------------------------------------------------------------------------------------------------------------------
if(!empty($_POST['receiveddate']) && !empty($_POST['data_id']) && ($_SESSION['loggedin']=="admin")):
  $get_dataid        = !empty($_POST['data_id']) ? $_POST['data_id'] : '';
  $get_received_date = !empty($_POST['receiveddate']) ? $_POST['receiveddate'] : '';
  $format_date       = date("Y-m-d", strtotime($get_received_date));

  if(!empty($get_received_date) && !empty($get_dataid)):
     $updatequery_prodcuts ="UPDATE production SET received='$format_date' WHERE id=$get_dataid";
     $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
     if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;
  endif;
endif;  
// END MARK AS BEING RECEIVED ----------------------------------------------------------------------------------------------------------------


// REMOVE RECEIVED DATE & COMPLETED SIDE -----------------------------------------------------------------------------------------------------
if(!empty($_POST['removecompleted']) && ($_SESSION['loggedin']=="admin")):
  $get_dataid = !empty($_POST['removecompleted']) ? $_POST['removecompleted'] : '';
   $updatequery_prodcuts ="UPDATE production SET received='0000-00-00' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;
  $saved = "yes";
endif;  
// END REMOVE RECEIVED DATE & COMPLETED SIDE ------------------------------------------------------------------------------------------------

// DELETE FROM DATABASE ---------------------------------------------------------------------------------------------------------------------
if(!empty($_POST['deleteproduct']) && ($_SESSION['loggedin']=="admin")):
  $get_dataid = !empty($_POST['deleteproduct']) ? $_POST['deleteproduct'] : '';
   $updatequery_prodcuts ="DELETE FROM production WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): $error = "Crap something went wrong!! Try again in a few minutes or call Scott"; endif;
  $saved = "yes";
endif;  
// END DELETE FROM DATABASE -----------------------------------------------------------------------------------------------------------------

$OrderID = !empty($_GET['orderid']) ? $_GET['orderid'] : '';
//$OrderID = "76770";

$CurrentYear  = date("Y");
$CurrentMonth = date("m", strtotime("-1 months"));

$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


?><!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Production Schedule</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style>
      <?php include('styles.css'); ?>
    </style>
    <script>
      <?php  include('javascript.js');  ?>
    </script>
    <?php if(isset($saved)): ?>
    <script>
        jQuery(document).ready(function($){
          $(window).scrollTop($(document).height());
        });
    </script>
    <?php endif; ?>
 </head>
<body><div id="overlay"></div>
  <div id="getinfoform">
    <div id="choosedata">
      <div id="closedata">X</div>
        <br /><Br />
      <div id="loadinfo"></div>
    </div>
  </div>
  
  
<?php

 // GET PRODUCTION INFORMATION FROM THE DATABASE 
  //$getquery = "SELECT * FROM production WHERE hide = 0 ";
  if(!empty($_GET['complete'])):
     $getquery = "SELECT * FROM production WHERE received != '0000-00-00' ORDER BY received";
  else:
     $getquery = "SELECT * FROM production WHERE received = '0000-00-00' ORDER BY request_date";
  endif;
  
	$get_data = mysqli_query($conn, $getquery);
 
	while ($data = mysqli_fetch_assoc($get_data)):
      $newid        = $data['order_id'];
      $product_code = $data['product_code'];
  
      $data_id           = $data['id'];
      $data_order_id     = $data['order_id'];
      $data_request_date = $data['request_date'];
      $data_product      = $data['product'];
      $data_product_code = $data['product_code'];
      $data_customername = $data['customer'];
      $data_received     = $data['received'];
      $data_note         = $data['note'];
  
      if($data_received === "0000-00-00"):
        $datediff  = (strtotime("now") - strtotime($data_request_date));
        $days_waiting = round($datediff / (60 * 60 * 24));
      else:
        $datediff = (strtotime($data_received) - strtotime($data_request_date));
        $days_waiting = round($datediff / (60 * 60 * 24));
      endif;

      $formated_request_date = date("m/d/Y", strtotime($data_request_date));
      $formated_received     = date("m/d/Y", strtotime($data_received));
  
    if ($test_result1 = $conn->query("SELECT * FROM production WHERE received = '0000-00-00' AND product_code = '".$data_product_code."' ORDER BY received")) {
      $row_cnt1 = $test_result1->num_rows;
      $test_result1->close();
    } 
  
   if ($test_result2 = $conn->query("SELECT * FROM production WHERE received = '0000-00-00' AND product_code = '".$data_product_code."' AND request_date <= '".$data_request_date."' ORDER BY received")) {
      $row_cnt2 = $test_result2->num_rows;
      $test_result2->close();
    }
  
  
      if($data_product_code !== "Not-Found"):
        $product_data = '<a href="https://www.everythingattachments.com/ProductDetails.asp?ProductCode='.$data_product_code.'" class="productlink" target="_new" title="Number '.$row_cnt2.' out of '.$row_cnt1.' to be built">'.$data_product.'</a> '; 
      else:
        $product_data = $data_product;
      endif;
  
      $tabledata .= '<tr id="tr_'.$data_id.'" class="orders">';
      $tabledata .= '<td>'.$formated_request_date.'</td>';
  
      if(!empty($_SESSION['loggedin']) && ($_SESSION['loggedin']=='admin' || $_SESSION['loggedin']=='nate')):
        $tabledata .= '<td><div class="editdata" data-id="'.$data_order_id.'" id="td_'.$data_id.'">'.$data_order_id.'</div></td>';
      else:
        $tabledata .= '<td><a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$data_order_id.'" target="_new" class="productlink">'.$data_order_id.'</a></td>';
      endif;
  
      $tabledata .= '<td>'.$data_customername.'</td><td>'.$product_data.'</td>';
      
      $tabledata .= '<td>'.$data_note.'</td>';
  
      if(!empty($_GET['complete'])):
        $tabledata .= '<td class="center">'.$formated_received.'</td>';
      endif;
  
      $tabledata .= '<td class="center">'.$days_waiting.'</td></tr>';
  
      endwhile;
 
  
  mysqli_close($conn);
  
?>



<div id="tabledatadiv">
  <table id="tabledata">
    <tr class="fixed">
      <th>Request Date</th>
      <th>Order ID</th>
      <th>Customer</th>
      <th id="productcell">Product <?php if(!empty($_SESSION['loggedin']) && $_SESSION['loggedin']=='admin'): ?>
        <form method="post" id="getorderinfoform">
          <input type="hidden" id="data_id" name="data_id" placeholder="Order ID#" value="" />
          <input type="text" id="orderid" name="orderid" style="width:100px;margin-left:8px;" value="" />
          <input type="submit" id="getorderinfo" value="Get Info" />
        </form>
        <?php endif; ?></th>
      <th> Notes</th>
      <?php if(!empty($_GET['complete'])): ?>
      <th class="center topth">Received</th>
      <th class="center topth">Days Waited</th>
      <?php else: ?>
      <th class="center topth">Days Waiting</th>
      <?php endif; ?>
      
     </tr>
     <tr><td><div id="divtd"></div></td><td></td><td></td><td class="center" id="cellsize"></td><td></td>
       <?php if(!empty($_GET['complete'])): ?><td></td><td></td><?php else: ?><td></td><?php endif; ?>
     </tr>
    <?php echo $tabledata; ?>
    <?php for ($x = 0; $x <= 10; $x++): ?>
     <tr><td><div style="height: 15px;width:10px;"></div></td><td></td><td></td><td class="center"></td><td></td>
        <?php if(!empty($_GET['complete'])): ?><td></td><td></td><?php else: ?><td></td><?php endif; ?>
     </tr>
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
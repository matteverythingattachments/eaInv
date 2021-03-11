<?php 
error_reporting(0);
session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";

if($_POST['send']):
  if(($_POST['username']==="GMiller" && $_POST['password']==="corriher") || ($_POST['username']==="user" && $_POST['password']==="corriher")):
    $_SESSION['loggedin'] = 1;
  endif;
endif;


if(!empty($_SESSION['loggedin'])):
   header('Location: https://groundbraker.com/blitz/inventory/taxes/tax-data.php');
endif;


$CurrentYear  = date("Y", strtotime("-1 months"));
$CurrentMonth = date("m", strtotime("-1 months"));
$CurrentState = "NC";

if($_POST['chooseyear']){ $CurrentYear = $_POST['chooseyear']; }
if($_POST['choosemonth']){ $CurrentMonth = $_POST['choosemonth']; }
if($_POST['choosestate']){ $CurrentState = $_POST['choosestate']; }


$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');


?><!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Tax Data</title>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
    <style>
      <?php include('styles.css'); ?>
    </style>
    <script>
      <?php include('javascript.js'); ?>
    </script>
 </head>
<body>

<?php if(!$_SESSION['loggedin']){ ?>
  <div id="login">
    <form method="post"><input type="hidden" name="send" value="1" />
      <input type="text" name="username" placeholder="User Name" value="" /><br />
      <input type="password" name="password" placeholder="Password" value="" /><br />
      <input type="submit" value="Login" />
    </form>
  </div>
</body></html>
<?php
exit;
};


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
  
  $totalAmount  = 0;
  $totalTax     = 0;
  $totalOrders  = 0;
  
  if($CurrentState === "all"){
    $addState = "";
  }else{
    $addState = '&WHERE_Column=o.ShipState&WHERE_Value='.$CurrentState;
  }


  $getdata = 'https://www.everythingattachments.com/net/WebService.aspx?Login='.$vUserName.'&EncryptedPassword='.$vPassword.'&API_Name=Generic\Orders&SELECT_Columns=o.OrderID,o.LastModified,o.OrderDate,o.OrderStatus,o.PaymentAmount,o.SalesTax1,o.SalesTaxRate,o.SalesTaxRate1,o.Shipped,o.ShipState,o.Total_Payment_Received'.$addState;
  $indexPage = curl_get_taxinfo($getdata);
  $xml = new SimpleXMLElement($indexPage);

foreach ($xml->Orders as $element){
  // ASSIGN VALUES
  $OrderID        = $element->OrderID;
  $OrderDate      = $element->OrderDate;
  $LastModified   = $element->LastModified;
  $OrderStatus    = $element->OrderStatus;
  $SalesTax1      = (float)$element->SalesTax1;
  $SalesTaxRate1  = (float)$element->SalesTaxRate1;
  $ShipState      = $element->ShipState;
  $Total_Payment_Received  = (float)$element->Total_Payment_Received;
  
  // GET ORDER DATE MONTH AND YEAR
  $CheckDate = strtotime($OrderDate);
  $thisDate  = date("m/d/Y", $CheckDate);
  $thisYear  = date("Y", $CheckDate);
  $thisMonth = date("m", $CheckDate);
  
  
 // echo "OrderDate: ".$OrderDate." - Year: ".$thisYear." - Month: ".$thisMonth." | Current YEAR & MONTH: ".$CurrentYear." - ".$CurrentMonth."<br />";
  // SET UP IF STATMENT
  if($CurrentMonth === "all"){ if($thisYear===$CurrentYear){ $addtabledata = "yes"; }else{ $addtabledata = "no"; } 
  }else{
    if($thisYear===$CurrentYear && $thisMonth===$CurrentMonth){ $addtabledata = "yes"; }else{ $addtabledata = "no"; } 
  }
  
  if($addtabledata == "yes"){
    $tabledata .= '<tr id="tr_'.$OrderID.'" class="orders '.$OrderStatus.'" data-tax="'.$SalesTax1.'" data-payment="'.$Total_Payment_Received.'">';
    $tabledata .= '<td><a add target="_blank" href="https://www.everythingattachments.com/admin/admindetails_processorder.asp?table=Orders&Page=1&ID='.$OrderID.'" target="_new">'.$OrderID.'</a></td><td>'.$thisDate.'</td>';
    $tabledata .= '<td>'.$OrderStatus.'</td><td class="center">'.$ShipState.'</td>';
    $tabledata .= '<td id="received_'.$OrderID.'">'.money_format('%.2n', $Total_Payment_Received).'</td><td>'.sprintf("%.2f%%", $SalesTaxRate1 * 100).'</td>';
    $tabledata .= '<td id="tax_'.$OrderID.'">'.money_format('%.2n', $SalesTax1).'</td></tr>';
    // GET TOTALS
    $totalAmount += $Total_Payment_Received;
    $totalOrders ++;
    $totalTax    += $SalesTax1;
  }
  
  //foreach($element as $key => $val) {  
   //echo "{$key}: {$val}<br />";
  //}
  //echo "<br />";
}

?>



<?php if($tabledata){ ?>
<div id="tabledatadiv">
  <table id="tabledata">
    <tr class="fixed"><th width="65">Order ID</th><th width="100">Order Date</th><th width="100">Order Status</th><th width="80">Ship State</th><th width="190">Total Payment Recieved</th><th width="75">Tax Rate</th><th width="100">Sales Tax</th></tr>
    <tr><td width="65"></td><td width="100"></td><td width="100"></td><td width="80"></td><td width="190"></td><td width="75"></td><td width="100"></td></tr>
    <?php echo $tabledata; ?>
  </table>
</div>
<div id="tabletotals">
  <h2>Totals</h2>
  <table>
    <tr>
      <td width="180"><div id="orderstotal">Orders: <?php echo $totalOrders; ?></div></td>
      <td width="100"></td><td width="80"></td>
      <td width="190"><div id="paymenttotal"><?php echo money_format('%.2n', $totalAmount); ?></div></td>
      <td width="75"></td>
      <td width="100"><div id="taxtotal"><?php echo money_format('%.2n', $totalTax); ?></div></td>
    </tr>
  </table>
  <br />
  <div class="print">Print Out</div>
  <strong>&nbsp;&nbsp; Order Status</strong> (Click Status to Show or Hide)<br />
    <div id="status_new" class="statustab">New</div>
    <div id="status_shipped" class="statustab">Shipped</div>
    <div id="status_processing" class="statustab">Processing</div>
    <div id="status_returned" class="statustab">Returned</div>
    <div id="status_cancelled" class="statustab">Cancelled</div>
  <br />
  
  <div id="choosedata">
    <form method="post">
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
            for ($x = 2015; $x <= $maxYear; $x++) {
              if($x==$CurrentYear){$yearselectclass = "selected"; }else{ $yearselectclass = ""; }
              echo  '<option value="'.$x.'" '.$yearselectclass.'>'.$x.'</option>';
            } 
        ?>
      </select>&nbsp;&nbsp; &nbsp;&nbsp; 
    
      <label for="choosemonth"> Select Month:</label>
      <select id="choosemonth" name="choosemonth">
        <option <?php if($CurrentMonth==='all'){ echo 'selected'; }?> value='all'>All</option>
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
       <input type="submit" value="Get Info" />
      </form>
  </div>
  <br />
  <a href="tax-worksheet.php" title="Product Liability Figure">Product Liability Figure</a>
</div>
<?php }else{ echo "no data"; } ?>





</body>
</html>
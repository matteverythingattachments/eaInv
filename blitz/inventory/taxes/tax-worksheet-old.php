<?php session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');

if($_POST['send']):
  if($_POST['username']==="GMiller" && $_POST['password']==="Jordan"):
    $_SESSION['loggedin'] = 1;
  endif;
endif;

$CurrentYear  = date("Y");
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
  
  $totalAmount    = 0;
  $totalStateTax  = 0;
  $totalCountyTax = 0;
  $totalTax       = 0;
  $totalOrders    = 0;
  
  if($CurrentState === "all"){
    $addState = "";
  }else{
    $addState = '&WHERE_Column=o.ShipState&WHERE_Value='.$CurrentState;
  }

  $getdata = 'https://www.everythingattachments.com/net/WebService.aspx?Login=scott@everythingattachments.com&EncryptedPassword=FA9EC3C9CF7B85717A1897818B4AD44D11EAC8126A944405F305956B444B10B9&API_Name=Generic\Orders&SELECT_Columns=o.OrderID,o.LastModified,o.OrderDate,o.OrderStatus,o.PaymentAmount,o.SalesTax1,o.SalesTaxRate,o.SalesTaxRate1,o.Shipped,o.ShipState,o.Total_Payment_Received'.$addState;
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
  $PaymentAmount  = (float)$element->PaymentAmount;
  $Total_Payment_Received  = (float)$element->Total_Payment_Received;
  
  $GrossReceipts = ($Total_Payment_Received - $SalesTax1);
  $StateTax      = ($GrossReceipts * .0475);
  $CountyTax     = ($GrossReceipts * .0225);
  
  
  // GET ORDER DATE MONTH AND YEAR
  $CheckDate = strtotime($OrderDate);
  $thisDate  = date("m/d/Y", $CheckDate);
  $thisYear  = date("Y", $CheckDate);
  $thisMonth = date("m", $CheckDate);
  
  
 // echo "OrderDate: ".$OrderDate." - Year: ".$thisYear." - Month: ".$thisMonth." | Current YEAR & MONTH: ".$CurrentYear." - ".$CurrentMonth."<br />";
  
  
  
  // SET UP IF STATMENT | CHECK IF ORDER HAS BEEN PAID IN FULL AND IF NOT DO NOT INCLUDE IN LIST
  if($PaymentAmount === $Total_Payment_Received){  
    if($CurrentMonth === "all"){ 
      if($thisYear===$CurrentYear){ $addtabledata = "yes"; }else{ $addtabledata = "no"; } 
    }else{
      if($thisYear===$CurrentYear && $thisMonth===$CurrentMonth){ $addtabledata = "yes"; }else{ $addtabledata = "no"; } 
    }
  }else{
    // PAYMENT HAS NOT BEEN PAID IN FULL AS OF YET SO DO NOT ADD IN THIS ORDER
    $addtabledata = "no";
  }
  
  if($addtabledata == "yes"){
    $tabledata .= '<tr id="tr_'.$OrderID.'" class="orders '.$OrderStatus.'" rel="'.$OrderID.'">';
    $tabledata .= '<td style="text-align:center;" class="orderid" rel="'.$OrderID.'">'.$OrderID.'</td>';
    $tabledata .= '<td id="totalrecipts_'.$OrderID.'" class="nonexempt" rel="'.$OrderID.'" data-price="'.money_format('%.2n', $GrossReceipts).'">'.money_format('%.2n', $GrossReceipts).'</td>';
    $tabledata .= '<td id="totalexempt_'.$OrderID.'" class="exempt" rel="'.$OrderID.'"></td>';
    $tabledata .= '<td><input type="text" value="" class="ptext" style="width:74px;" /></td>';
    
    $tabledata .= '<td id="totalstatetax_'.$OrderID.'" data-price="'.money_format('%.2n', $StateTax).'">'.money_format('%.2n', $StateTax).'</td>';
    $tabledata .= '<td id="totalcountytax_'.$OrderID.'" data-price="'.money_format('%.2n', $CountyTax).'">'.money_format('%.2n', $CountyTax).'</td>';
   
    $tabledata .= '<td><input type="text" value="" class="ptext" style="width:90px;" /></td>';
    $tabledata .= '<td id="totalsalestax_'.$OrderID.'" data-price="'.money_format('%.2n', $SalesTax1).'">'.money_format('%.2n', $SalesTax1).'</td>';
    $tabledata .= '<td><input type="text" id="totaldifferenttax_'.$OrderID.'" value="'.money_format('%.2n', $SalesTax1).'" class="ptext inputvar" style="width:95px;" /></td></tr>';
    
    
    // GET TOTALS
    $totalAmount    += $GrossReceipts;
    $totalStateTax  += $StateTax;
    $totalCountyTax += $CountyTax;
    $totalOrders    ++;
    $totalTax       += $SalesTax1;
  }
  
  //foreach($element as $key => $val) {  
   //echo "{$key}: {$val}<br />";
  //}
  //echo "<br />";
}

  $tabledata .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
  $tabledata .= '<tr class="bold"><td></td><td id="totalrecipts">'.money_format('%.2n', $totalAmount).'</td>';
  $tabledata .= '<td id="totalexempt"></td><td></td>';
  $tabledata .= '<td id="totalstatetax">'.money_format('%.2n', $totalStateTax).'</td><td id="totalcountytax">'.money_format('%.2n', $totalCountyTax).'</td>';
  $tabledata .= '<td></td><td id="totalsalestax">'.money_format('%.2n', $totalTax).'</td><td id="totaldifferenttax">'.money_format('%.2n', $totalTax).'</td></tr>';
  
  
?>



<?php if($tabledata){ ?>
<div id="tabledatadiv2">
  <table id="tabledata">
    <tr class="fixed"><th width="65">Order ID</th><th width="100">NC Gross Receipts</th><th width="100">Out of State / Tax Exempt Sales</th><th width="80">Epuipment Purchase</th><th width="100">State Tax 4.75%</th><th width="100">County Tax 2.25%</th><th width="100">Equipment Purchase Use Tax 1.00%</th><th width="100">Total 7.00%</th><th width="120">Sales Tax Charged on Invoice (if different)</th></tr>
    <tr class="toprow"><td width="65" height="57"></td><td width="100"></td><td width="100"></td><td width="80"></td><td width="100"></td><td width="100"></td><td width="100"></td><td width="100"></td><td width="120"></td></tr>
    <?php echo $tabledata; ?>
  </table>
</div>
<div id="tabletotals2">
  <h2>Totals  <div id="showall">( <span id="hidecount">0</span> ) Hidden</div></h2>
  <table>
    <tr>
      <td width="60"><div id="orderstotal"><?php echo $totalOrders; ?></div></td>
      <td width="100"><div id="paymenttotal"><?php echo money_format('%.2n', $totalAmount); ?></div></td>
      <td width="100"><div id="exempttotal"></div></td><td width="80"></td>
      <td width="100"><div id="statetaxtotal"><?php echo money_format('%.2n', $totalStateTax); ?></div></td>
      <td width="100"><div id="countytaxtotal"><?php echo money_format('%.2n', $totalCountyTax); ?></div></td>
      <td width="100"><div></div></td>
      <td width="100"><div id="taxtotal"><?php echo money_format('%.2n', $totalTax); ?></div></td>
      <td width="100"><div id="taxtotaldifferent"><?php echo money_format('%.2n', $totalTax); ?></div></td>
    </tr>
  </table>
  <br />
  <div style="float:right;" class="print">Print Out</div>
  <strong>&nbsp;&nbsp; Order Status</strong> (Click Status to Show or Hide)<br />
    <div id="status_new" class="statustab2">New</div>
    <div id="status_shipped" class="statustab2">Shipped</div>
    <div id="status_processing" class="statustab2">Processing</div>
    <div id="status_returned" class="statustab2">Returned</div>
    <div id="status_cancelled" class="statustab2">Cancelled</div>
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
  </div><br />
 <a href="index.php" title="Tax Information">Tax Information</a>
</div>
<?php }else{ echo "no data"; } ?>





</body>
</html>
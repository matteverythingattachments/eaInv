<?php session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


  setlocale(LC_MONETARY, 'en_US.UTF-8');

//if($_POST['send']):
//  if($_POST['username']==="GMiller" && $_POST['password']==="corriher"):
//    $_SESSION['loggedin'] = 1;
//  endif;
//endif;


  if(isset($_POST['startdate'])):
    $setstartdate = $_POST['startdate'];
  else:
    $setstartdate = '04/07/2018';
  endif;
  if(isset($_POST['enddate'])):
    $setenddate = $_POST['enddate'];
  else:
    $setenddate = '04/07/2019';
  endif;

if(isset($_POST['getinfo'])):
  $get_start_month = date("m", strtotime($setstartdate));
  $get_start_year = date("Y", strtotime($setstartdate));
  $get_start_day = date("d", strtotime($setstartdate));

  $get_end_month = date("m", strtotime($setenddate));
  $get_end_year = date("Y", strtotime($setenddate));
  $get_end_day = date("d", strtotime($setenddate));

include 'product_list.php';

  $asp = file("https://www.everythingattachments.com/v/vspfiles/schema/Generic/gregsorders.asp?startday=".$get_start_day."&startmonth=".$get_start_month."&startyear=".$get_start_year."&endday=".$get_end_day."&endmonth=".$get_end_month."&endyear=".$get_end_year."");

  //echo "https://www.everythingattachments.com/v/vspfiles/schema/Generic/gregsorders.asp?startday=".$get_start_day."&startmonth=".$get_start_month."&startyear=".$get_start_year."&endday=".$get_end_day."&endmonth=".$get_end_month."&endyear=".$get_end_year."";

$product_arr = array();
$totalvalue = 0;
  
$xml = simplexml_load_file('https://www.everythingattachments.com/net/WebService.aspx?Login=production@everythingattachments.com&EncryptedPassword=ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081&API_Name=Generic\gregsorders');

foreach($xml->Orders as $products):
  $quantity     = (int)$products->quantity;
  $productcode  = (string)$products->productcode;
  $productprice = (float)$products->productprice;

  if(array_key_exists($productcode,$products_array)):
    $thisprice = $productprice * $quantity;
    $totalvalue = $totalvalue + $thisprice;
  endif;
  //$product_arr[] = $productcode;
endforeach;
   
 // sort($product_arr);  
 // foreach($product_arr as $prods):
 //    $productlist .= $prods.'<br />';
 //  endforeach;

endif;

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

<?php if(!$_SESSION['loggedin']): ?>
  <div id="login">
    <form method="post" action="http://inventory.corimpco.net/taxes/"><input type="hidden" name="send" value="1" />
      <input type="text" name="username" placeholder="User Name" value="" /><br />
      <input type="password" name="password" placeholder="Password" value="" /><br />
      <input type="submit" value="Login" />
    </form>
  </div>
</body></html>
<?php
  exit;
endif;
?>

<style>
  #choosedata label{
    width: 140px;
    display: inline-block;
    flex-wrap: wrap;
  }
  #choosedata label span{
   font-size: .8rem; 
   font-weight:normal;
  }
</style>
<h1 style="text-align:center;">Product Liability Figure</h1>
  <div >
  <div id="choosedata">
    <form method="post" style="text-align:center;">
      <input type="hidden" name="getinfo" value="1" />
      <table style="margin: 0 auto;"><tr>
       <td><label for="startdate"> Select Start Date: <span>MM/DD/YYYY</span></label></td>
       <td><input id="startdate" name="startdate" type="text" style="width:110px;" value="<?php echo $setstartdate; ?>" /></td>
    
       <td><label for="enddate"> Select End Date: <span>MM/DD/YYYY</span></label></td>
       <td><input id="enddate" name="enddate" type="text" style="width:110px;" value="<?php echo $setenddate; ?>" /></td>
      
       <td><input type="submit" value="Submit" /></td>
      </tr></table>
      </form>
  </div><br />


<?php 
  if(isset($totalvalue)){ 
    echo '<p style="font-size:19px;text-align:center;font-weight:bold;">Total Sales -Taxes & -Shipping = ( ';
    echo money_format('%.2n', $totalvalue); 
    echo ' )</p>';
  } 
?>
    <br /><br />
 <a href="index.php" title="Tax Information"><- Back to Tax Information</a>
</div>
</body>
</html>
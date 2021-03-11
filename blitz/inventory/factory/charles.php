<?php
error_reporting(0);
// server should keep session data for AT LEAST 1 hour = 3600 - 12 hours = 43200 - week = 604800
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200);

session_start();
  setlocale(LC_MONETARY, 'en_US.UTF-8');
  $vPassword = "ACA051D7C73CFE239E145A6BC6F997D3CAC3819518FDD0D28AFC27C2067DC081";
  $vUserName = "production@everythingattachments.com";


if(empty($_SESSION['loggedin'])):
  header('Location:login.php'); exit;
endif;

    //Initializing variables
    $checkProduct = $getProductName = $tabledata = $ProductName = ""; 
    $quickwords = '<div>(Black)</div><div>w/ shielding</div><div>(Green)</div><div>(JD)</div><div>(Orange)</div><div>(Quick Attach)</div><div>(USSQA)</div>';

    include('charles_product_list.php');

    if(!empty($_SESSION['loggedin'])):
      if($_SESSION['loggedin']!=="admin"):
        $disableinput = 'disabled="disabled"';
        $hideelement = 'style="display:none;"';
      endif;
    endif;



include ("db_info.php");

// UPDATE DATABASE TO SET ITEM AS BEING CUT
if($_POST['cutid']):
   $get_dataid = $_POST['cutid'];
   $format_date = date("Y-m-d");
   $updatequery_prodcuts ="UPDATE production SET cut='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:

      echo '<div class="un_cut" title="Un-Mark" data-id="'.$get_dataid.'">'.date("m/d/Y").'</div>';
   endif;
exit;
endif;

// UPDATE DATABASE TO SET ITEM AS BEING CUT
if($_POST['uncutid']):
   $get_dataid = $_POST['uncutid'];
   $format_date = "0000-00-00";
   $updatequery_prodcuts ="UPDATE production SET cut='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:
      echo '<div class="send_cut" title="Mark Product as being Tacked" data-id="'.$get_dataid.'">Mark Tack</div>';
   endif;
exit;
endif;

// UPDATE  TO SET ITEM AS BEING Palleted (Tam)
if($_POST['palletid']):
   $get_dataid = $_POST['palletid'];
   $format_date = date("Y-m-d");
   $updatequery_prodcuts ="UPDATE production SET pallet='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:

      echo '<div class="un_pallet" title="Un-Mark" data-id="'.$get_dataid.'">'.date("m/d/Y").'</div>';
   endif;
exit;
endif;

// UPDATE DATABASE TO SET ITEM AS BEING un-Palleted (Tam)
if($_POST['unpalletid']):
   $get_dataid = $_POST['unpalletid'];
   $format_date = "0000-00-00";
   $updatequery_prodcuts ="UPDATE production SET pallet='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:
      echo '<div class="send_pallet" title="Mark Product as being palleted" data-id="'.$get_dataid.'">Mark Pal</div>';
   endif;
exit;
endif;

// UPDATE  TO SET ITEM AS BEING Painted (Tam)
if($_POST['paintedid']):
   $get_dataid = $_POST['paintedid'];
   $format_date = date("Y-m-d");
   $updatequery_prodcuts ="UPDATE production SET painted='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:

      echo '<div class="un_painted" title="Un-Mark" data-id="'.$get_dataid.'">'.date("m/d/Y").'</div>';
   endif;
exit;
endif;

// UPDATE DATABASE TO SET ITEM AS BEING un-Painted (Tam)
if($_POST['unpaintedid']):
   $get_dataid = $_POST['unpaintedid'];
   $format_date = "0000-00-00";
   $updatequery_prodcuts ="UPDATE production SET painted='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:
      echo '<div class="send_painted" title="Mark Product as being Painted" data-id="'.$get_dataid.'">Mark Assy</div>';
   endif;
exit;
endif;

// UPDATE DATABASE TO SET ITEM TOP AS BEING CUT
if($_POST['topid']):
   $get_dataid = $_POST['topid'];
   $format_date = date("Y-m-d");
   $updatequery_prodcuts ="UPDATE production SET top='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:

      echo '<div class="un_top" title="Un-Mark" data-id="'.$get_dataid.'">'.date("m/d/Y").'</div>';
   endif;
exit;
endif;

// UPDATE DATABASE TO SET ITEM TOP AS BEING CUT
if($_POST['untopid']):
   $get_dataid = $_POST['untopid'];
   $format_date = "0000-00-00";
   $updatequery_prodcuts ="UPDATE production SET top='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:
      echo '<div class="send_top" title="Mark Product Top as being Done" data-id="'.$get_dataid.'">Mark Top</div>';
   endif;
exit;
endif;


// UPDATE DATABASE TO SET ITEM TOP AS BEING CUT
if($_POST['bottomid']):
   $get_dataid = $_POST['bottomid'];
   $format_date = date("Y-m-d");
   $updatequery_prodcuts ="UPDATE production SET bottom='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:

      echo '<div class="un_bottom" title="Un-Mark" data-id="'.$get_dataid.'">'.date("m/d/Y").'</div>';
   endif;
exit;
endif;

// UPDATE DATABASE TO SET ITEM TOP AS BEING CUT
if($_POST['unbottomid']):
   $get_dataid = $_POST['unbottomid'];
   $format_date = "0000-00-00";
   $updatequery_prodcuts ="UPDATE production SET bottom='$format_date' WHERE id=$get_dataid";
   $updatethe_product = mysqli_query($conn, $updatequery_prodcuts) or die (mysqli_error());
   if(!$updatethe_product): 
      echo "Something went wrong!! Try again in a few minutes or call Tam";
   else:
      echo '<div class="send_bottom" title="Mark Product Bottom as being Done" data-id="'.$get_dataid.'">Mark Bottom</div>';
   endif;
exit;
endif;




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
      body, html{ background:#fff; }
      a { text-decoration:none; }
      .fixed{ position: relative; }
      #tabledata tr:nth-child(2) { display:none; }
      #tabledata th:nth-child(4), #tabledata td:nth-child(4) { width:auto; max-width:none; }
      #tabledata th:nth-child(5), #tabledata td:nth-child(5) { width:auto; }
      #tabledata th:nth-child(6), #tabledata td:nth-child(6) { width:auto; }
      #tabledata th:nth-child(7), #tabledata td:nth-child(7) { width:auto; }
      #tabledata th:nth-child(8), #tabledata td:nth-child(8) { width:auto; }
      #tabledata th, #tabledata td { display: table-cell; }
      .printhereon{ background:#efefef !important; }
      .setrows{ cursor:pointer; }
      
      .productlist{
          font-weight:bold;
          position: fixed;
          left: 54%;
          top: 75px;
          width: 325px;
          background: #efefef;
          border: solid 1px #d2d2d2;
          padding: 10px;
          z-index: 999;
          box-shadow: 0px 0px 6px 1px #848484;
      }
      .productlist a{ color:#0698a0; }
      
      .productlistbar{
        cursor:pointer;
        font-weight:bold;
        padding: 5px;
        background: #d4d4d4;
        border: solid 1px #bdbdbd;
      }
      .productlistbar span{
        float:right;
        font-size: 10px;
      }
      
      .productlist ul{ 
        height: 371px;
        overflow: auto;
        margin-left:-40px; 
      }
      .productlist li{ list-style:none; }
      .productlist li a{ display:block; padding: 5px; }
 
      .productlist li:nth-child(odd){ background:#e4e4e4; }
      .productlist li span{
        float:right;
        padding-right:10px;
      }
      .productlist .activelist{ 
        display:block;
        background:#b0e4e2;
      }
      
      @media print {
        body, html{ background:#fff; }
        #tabledata tr:nth-child(1) { display:none; }
        #tabledata th:nth-child(5){ display:none; }
        #tabledata td:nth-child(5){ display:none; }
        #tabledata th:nth-child(6){ display:none; }
        #tabledata td:nth-child(6){ display:none; }
        #tabledata th:nth-child(7){ display:none; }
        #tabledata td:nth-child(7){ display:none; }
        #tabledata th:nth-child(8){ display:none; }
        #tabledata td:nth-child(8){ display:none; }
        #tabledata tr{ background-color: #fff; }
        #tabledata th:nth-child(9), #tabledata td:nth-child(9){ width:auto; text-align:center; }
        #tabledata tr:nth-child(n + 2):hover { background-color: #fff; }
        .printhereon{ background:#fff !important; }
        .orders:not(.printhereon){ display:none; }
        .productlist{ display:none; }
      }
    </style>
    <script>
      <?php  include('javascript.js');  ?>
    </script>
   
    <script>
        jQuery(document).ready(function($){
          $(document).on('click', '.productlink', function(e){ 
            var getdata = $(this).attr("data-code");
            if($(".hideorders").hasClass('yes')){
              $(".hideorders").removeClass('yes');
              $('.orders').css('display','table-row');
            }else{
              $(".hideorders").addClass('yes');
              $('.orders').css('display','none');
              $('.'+getdata).slideDown();
            }
          });
          
          $(document).on('click', '.productlinklist', function(e){ 
            var getdata = $(this).attr("data-code");
            $('.activelist').removeClass('activelist');
            $(this).addClass('activelist');
            if(getdata == "all"){
              $(".hideorders").removeClass('yes');
              $('.orders').css('display','table-row');
            }else{
              $('.orders').css('display','none');
              $('.'+getdata).slideDown();
              $(".hideorders").addClass('yes');
            }
            
          });
          
          
          
          
          $(document).on('mousedown', '.setrows', function(e){ 
            e.preventDefault();
            var getdataid = $(this).attr("data-id");
            var getdatacode = $(this).attr("data-code");
            
            switch (event.which) {
              case 1: // LEFT CLICK
                  if($(".hideorders").hasClass('yes')){
                    if(!$(".printhereon").length){
                      $("#tr_"+getdataid).addClass('printhereon');
                      $("#tr_"+getdataid).nextAll(".orders."+getdatacode).addClass('printhereon');
                    }else{
                      $(".printhereon").removeClass("printhereon");
                    }
                  }
                  break;
              case 2: // MIDDLE CLICK 
                  // CODE HERE
                  break;
              case 3: // RIGHT CLICK
                 //disableContextMenu(true);
   
                  if($(".hideorders").hasClass('yes')){
                    if(!$(".printhereon").length){
                      $("#tr_"+getdataid).addClass('printhereon');
                    }else{
                      $("#tr_"+getdataid).removeClass("printhereon");
                    }
                  }
                  //disableContextMenu(false);
                  break;
              default:
                  alert('You have a strange Mouse!');
            }
            });
       
          
      $(document).on('click', '.productlistbar', function(e){ 
        if($('.productlist ul').is(":hidden")){
          $('.productlist').show();
        }else{
          $('.productlist').hide();
        }
      });
      $(document).on('click', '.disappear', function(e){ 
          if($('.summary').is(":hidden")){
          $('.summary').show();
        }else{
          $('.summary').hide();
        }
      });
            
      $(document).on('contextmenu', '.setrows', function(e){ 
	       return false;
	    }); 
      
      $(document).on('click', '.send_cut', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { cutid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#cut-"+getdata).html(data);
         });  
      });
      
      $(document).on('click', '.un_cut', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { uncutid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#cut-"+getdata).html(data);
         });  
      });
      
       $(document).on('click', '.send_top', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { topid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#top-"+getdata).html(data);
         });  
      });
      
      $(document).on('click', '.un_top', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { untopid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#top-"+getdata).html(data);
         });  
      });    
 
      $(document).on('click', '.send_bottom', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { bottomid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#bottom-"+getdata).html(data);
         });  
      });
      
      $(document).on('click', '.un_bottom', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { unbottomid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#bottom-"+getdata).html(data);
         });  
      }); 
      
            
       $(document).on('click', '.send_painted', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { paintedid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#painted-"+getdata).html(data);
         });  
      });
      
      $(document).on('click', '.un_painted', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { unpaintedid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#painted-"+getdata).html(data);
         });  
      });      
            
       $(document).on('click', '.send_pallet', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { palletid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#pallet-"+getdata).html(data);
         });  
      });
      
      $(document).on('click', '.un_pallet', function(e){ 
         var getdata = $(this).attr("data-id");
         $.post( "charles.php", { unpalletid: getdata }).done(function( data ) {
            //alert( "Data Loaded: " + data );
            $("#pallet-"+getdata).html(data);
         });  
      });             
            
            
      $( window ).bind( "beforeprint", function(e) { 
        if(!$(".printhereon").length){
            alert("NO ORDERS SELECTED!!! Click the Request Date on the heighest Order to print");
        };
      }); 
          
          
      });
    </script>
    
 </head>
<body><div id="overlay"></div>
  <div id="getinfoform">
    <div id="choosedata">
      <div id="closedata">X</div>
        <br /><Br />
      <div id="loadinfo"></div>
    </div>
  </div>
  
  <div class="hideorders"></div>

<?php

  
 // GET PRODUCTION INFORMATION FROM THE DATABASE 
  //$getquery = "SELECT * FROM production WHERE hide = 0 ";
  if(!empty($_GET['complete'])):
     $getquery = "SELECT * FROM production WHERE received != '0000-00-00' ORDER BY received";
  else:
     $getquery = "SELECT * FROM production WHERE received = '0000-00-00' ORDER BY request_date, order_id";
  
    // LIST ALL PRODUCTS TO GET COUNT PLACEMENT
     if ($countplacement_query = $conn->query("SELECT * FROM production WHERE received = '0000-00-00' ORDER BY order_id")):
        while ($get_counts = mysqli_fetch_assoc($countplacement_query)):
          $this_p_code = $get_counts['product_code'];
          $getting_count[$this_p_code][] = $get_counts['order_id']; 
        endwhile;
        $countplacement_query->close();
     endif;
  
  endif;
  
	$get_data = mysqli_query($conn, $getquery);
  
  $row_cnt0 = $get_data->num_rows;
  
  $showcount = 1;
  
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
    
      if($data['pallet'] === "0000-00-00")
      {
          $data_pallet = '<div class="send_pallet" title="Mark Product as being Palleted" data-id="'.$data_id.'">Mark Pal</div>';
      }
      else
      {
        $data_pallet   = '<div class="un_pallet" title="Un-Mark" data-id="'.$data_id.'">'.date("m/d/Y", strtotime($data['pallet'])).'</div>';
      }
    
      if($data['cut'] === "0000-00-00"):
        $data_cut   = '<div class="send_cut" title="Mark Product as being Tacked" data-id="'.$data_id.'">Mark Tack</div>';
      else:
        $data_cut   = '<div class="un_cut" title="Un-Mark" data-id="'.$data_id.'">'.date("m/d/Y", strtotime($data['cut'])).'</div>';
      endif;
    
      if($data['painted'] === "0000-00-00"):
    {
          $data_painted = '<div class="send_painted" title="Mark Product as being Painted" data-id="'.$data_id.'">Mark Assy</div>';
    }
      else:
    {
        $data_painted   = '<div class="un_painted" title="Un-Mark" data-id="'.$data_id.'">'.date("m/d/Y", strtotime($data['painted'])).'</div>';
    }
      endif;

  
      $formated_request_date = date("m/d/Y", strtotime($data_request_date));
      $request_date_month = date("m", strtotime($data_request_date));
      $request_date_day = date("d", strtotime($data_request_date));
      $request_date_year = date("Y", strtotime($data_request_date));
      $formated_received = date("m/d/Y", strtotime($data_received));
      
 
  
  
  
  // GET # OUT OF TOTAL OF THESE TYPE OF PRODUCTS 
    foreach($getting_count[$data_product_code] as $key => $value):
      if($value==$data_order_id):
        $getnum1 = (int)$key + 1;
      endif;
    endforeach;
  
    $getnum2 = count($getting_count[$data_product_code]);
    
    $p_name = (!empty($cproducts_array[$data_product_code])) ? $cproducts_array[$data_product_code] : "miscellaneous";
  
  // GET PRODUCT LIST FOF CHARLES PRINT SHEET
    if($get_product_list[$p_name]):
      $get_product_list[$p_name]++;
    else:
      $get_product_list[$p_name] = 1;
    endif;
  
  
      if($data_product_code !== "Not-Found"):
        $product_data = '<a href="#" class="productlink" data-code="'.$p_name.'" title="Number '.$getnum1.' out of '.$getnum2.' to be built">'.$data_product.'</a> <a href="https://www.everythingattachments.com/ProductDetails.asp?ProductCode='.$data_product_code.'" class="weblink">Web View</a>'; 
      else:
        $product_data = $data_product;
      endif;
  
      $tabledata .= '<tr id="tr_'.$data_id.'" class="orders '.$p_name.'">';
      $tabledata .= '<td><div class="setrows" data-id="'.$data_id.'" data-code="'.$p_name.'">'.$formated_request_date.'</div></td>';
  
      if(!empty($_SESSION['loggedin']) && ($_SESSION['loggedin']=='admin' || $_SESSION['loggedin']=='nate' || $_SESSION['loggedin']=='tam')):
        $tabledata .= '<td><div class="editdata" data-id="'.$data_order_id.'" id="td_'.$data_id.'">'.$data_order_id.'</div></td>';
      else:
        $tabledata .= '<td><a href="https://www.everythingattachments.com/admin/AdminDetails_ProcessOrder.asp?table=Orders&Page=1&ID='.$data_order_id.'" target="_blank" class="productlink'.$cproducts_array[$data_product_code].'" data-code="'.$p_name.'" title="'.$showcount.' out of '.$row_cnt0.' in total">'.$data_order_id.'</a></td>';
      endif;
  
      $tabledata .= '<td>'.$data_customername.'</td><td>'.$product_data.'</td>';
    
    $tabledata .= '<td>&nbsp;</td>';
      
      $tabledata .= '<td>'.$data_note.'</td>';
    
      $tabledata .= '<td id="pallet-'.$data_id.'">'.$data_pallet.'</td>';
  
      $tabledata .= '<td id="cut-'.$data_id.'">'.$data_cut.'</td>';
    
      $tabledata .= '<td id="painted-'.$data_id.'">'.$data_painted.'</td>';
  
      if(!empty($_GET['complete'])):
        $tabledata .= '<td class="center">'.$formated_received.'</td>';
      endif;
  
      $tabledata .= '<td class="center">'.$days_waiting.'</td></tr>';
      
      $showcount++;
      endwhile;
 
  
  //print_r($get_product_list);
  
  mysqli_close($conn);
  
?>

<div class="summary" id="sum" style="display: none;">
<div class="productlist">
  <div class="productlistbar">Product List <span style="float:right;font-size: 10px;"></span></div>
  <ul>
    <li><a href="#" class="productlinklist" data-code="all" title="Show All">Show All</a></li>
    <?php
      ksort($get_product_list);
      foreach($get_product_list as $key => $list_item):
        echo '<li><a href="#" class="productlinklist" data-code="'.$key.'" title="'.$key.'">'.$nproducts_array[$key].' <span>'.$list_item.'</span></a></li>';
      endforeach;
    ?>
  </ul>
</div> 
    </div>
  
  
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
      <th id="productcell">Product&nbsp;&nbsp;&nbsp;<?php echo '  ('.($showcount-1).' Items)'; ?><?php if(!empty($_SESSION['loggedin']) && $_SESSION['loggedin']=='admin'): ?>
        <form method="post" id="getorderinfoform">
          <input type="hidden" id="data_id" name="data_id" placeholder="Order ID#" value="" />
          <input type="text" id="orderid" name="orderid" style="width:100px;margin-left:8px;" value="" />
          <input type="submit" id="getorderinfo" value="Get Info" />
        </form>
        <?php endif; ?></th>
        <th class="disappear"><a style="padding: 0 100px 0 15px;color:#D5AAFF;" href="#" title="Non-Freight Products" ><img src="images/open.png" width="35" height="35" alt=""/></a></th>
      <th> Notes  <a href="other-products.php" title="Non-Freight Products" style="padding: 0 15px 0 15px;color:#ffeb3b;">Non-Freight Products</a></th>
        <th> Pallet </th>
      <th> Tack </th>
        <th> Assembled </th>
      <?php if(!empty($_GET['complete'])): ?>
      <th class="center topth">Received</th>
      <th class="center topth">Days</th>
      <?php else: ?>
      <th class="center topth">Days</th>
      <?php endif; ?>
      
     </tr>
     <tr><td><div id="divtd"></div></td><td></td><td></td><td class="center" id="cellsize"></td><td></td>
       <?php if(!empty($_GET['complete'])): ?><td></td><td></td><?php else: ?><td></td><?php endif; ?>
     </tr>
    <?php echo $tabledata; ?>
   
  </table>
  <br /><br /><br />
</div>
<div id="bottombar">
  <a href="index.php" title="Factory Product Schedule LTL">Factory Product Schedule LTL</a>
  <a href="index.php?complete=1" title="Complete Factory to Assembly">Complete Factory to Assembly</a>
  <a href="charles.php" class="selected" title="Charles List">Charles</a>
  
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
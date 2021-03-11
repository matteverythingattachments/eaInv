<?php 
error_reporting(0);
session_set_cookie_params(31556926,"/");//one year in seconds
session_start();
include('db.php');
include('challenge.php');
?>
<!doctype html>
<html>
<head>
 <style>
.content {
  max-width: 500px;
  margin: auto;
}
{box-sizing: border-box;}


/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  /* bring your own prefixes...haha */
  transform: translate(-50%, -50%);
  border: 1px solid #4E4C4C;
  padding:10px;
  background: #FFF;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 350px;
  padding: 10px 5px 5px 0px;
  background-color: #403E3E;
  
}
select {
  width: 50%;
  padding: 8px 10px;
  border: none;
  border-radius: 4px;
  background-color: #f1f1f1;
}


/* When the inputs get focus, do something */
.form-container select:focus, .form-container select:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the Yes button */
.form-container .btn {
  background-color: #28A745;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container cancel {
  background-color: #DB5C58;
      position: fixed;
  top: 50%;
  left: 50%;
  /* bring your own prefixes...haha */
  transform: translate(-50%, -50%);
}
.form-container .no {
  background-color: #DB5C58;
}
/* Add some hover effects to the mf's */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
     /* black background color to the top navigation */
.topnav {
  background-color: #244E6F;
  overflow: hidden;
}

/* Style the links inside the navigation bar */
.topnav a {
  float: left;
  margin-left: :0px;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

/* color of links on hover */
.topnav a:hover {
  background-color: #ddd;
  color: black;
}

/* color to the active/current link */
.topnav a.active {
  background-color: #3D81BB;
  color: white;
}
#scroll {
    position:fixed;
    right:10px;
    bottom:10px;
    cursor:pointer;
    width:50px;
    height:50px;
    background-color:#3498db;
    text-indent:-9999px;
    display:none;
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    border-radius:5px;
}
#scroll span {
    position:absolute;
    top:50%;
    left:50%;
    margin-left:-8px;
    margin-top:-12px;
    height:0;
    width:0;
    border:8px solid transparent;
    border-bottom-color:#ffffff
}
#scroll:hover {
    background-color:#e74c3c;
    opacity:1;
    filter:"alpha(opacity=100)";
    -ms-filter:"alpha(opacity=100)";    
</style>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
    <script type="text/javascript">
 
</script>   
<title>EA Call System Statistics</title>
</head>
<body>
    <?php if ($_SESSION['role'] != 'admin')
    {
      header('Location:index.php');
    }
    ?>
    <?php
       if ($_SESSION['role'] == 'admin') 
    {
        function getMinutes($date1, $date2)
        {
        //Some date stuff...calculated additional in case we ever need it       
        // two dates 
        $dateA = strtotime($date1);  
        $dateB = strtotime($date2);              
            
        // Difference between two dates 
        $diff = abs($dateB - $dateA);  


        // year - divide the resulting date into 
        // total seconds in a year (365*60*60*24) 
        $years = floor($diff / (365*60*60*24));  


        // month - subtract it from years and 
        // divide the resulting date into 
        // total seconds in a month (30*60*60*24) 
        $months = floor(($diff - $years * 365*60*60*24) 
                                       / (30*60*60*24));  


        // day, subtract it with years and  
        // months and divide the resulting date into 
        // total seconds in a day (60*60*24) 
        $days = floor(($diff - $years * 365*60*60*24 -  
                     $months*30*60*60*24)/ (60*60*24)); 


        // hour - subtract it with years,  
        // months & seconds and divide the result 
        // date into total seconds in an hour (60*60) 
        $hours = floor(($diff - $years * 365*60*60*24  
               - $months*30*60*60*24 - $days*60*60*24) 
                                           / (60*60));  


        // minutes - subtract it from years, 
        // months, seconds and hours and divide the  
        // resultant date into total seconds i.e. 60 
        $minutes = floor(($diff - $years * 365*60*60*24  
                 - $months*30*60*60*24 - $days*60*60*24  
                                  - $hours*60*60)/ 60);  


        // seconds - subtract it from years, 
        // months, seconds, hours and minutes  
        $seconds = floor(($diff - $years * 365*60*60*24  
                 - $months*30*60*60*24 - $days*60*60*24 
                        - $hours*60*60 - $minutes*60));
       
        return round($minutes,2);   
            
        }
    }
        ?>
    <div class="content">
    <img src="img/EALogoW.png" width="800" height="129" alt=""/>
        </div>

    <div style="background:#CCCCCC;height: 2px;">
      <hr>
    </div>
    <div style="margin-left: 0px;">
    <div class="topnav">
      <a href="index.php">Home</a>
      <a href="index.php#open">Open</a>
      <a href="index.php#history">History</a>
        <a class="active" href="#">Stats</a>
      <a href="http://groundbraker.com/blitz/eacalls/logout.php">Logout</a>
      <div style="color: #CCCCCC; font-size: 15pt; margin-left: 500px;margin-top: 12px">
      <?php
          $usr = $_SESSION['userName'];
          $roleC = $_SESSION['role'];
          $usr = ucwords($usr);
          if ($roleC == 'admin')
          {
    	       echo "<div style='color:red;'>Welcome, ".$usr."</div>";
          }
          else              
          {
              echo "Welcome, ".$usr;
          }
    $eaCallList = mysqli_query($mysqli, "SELECT * FROM eainternalcalls") or die(mysqli_error($mysqli));
    $callCount = mysqli_num_rows($eaCallList);
          
    $eaCallSales = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where complete=1 and sale=1") or die(mysqli_error($mysqli));
    $callSalesCount = mysqli_num_rows($eaCallSales);
          
    $totalMinAllSalesmen = 0;     
          
    //Travis    
    $eaCallSalesTravis = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'mtt'") or die(mysqli_error($mysqli));
    $callSalesCountTravis = mysqli_num_rows($eaCallSalesTravis);
          
    $eaCallSalesTravisTotal = mysqli_query($mysqli, "SELECT assigned,sale,complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'mtt' AND sale=1") or die(mysqli_error($mysqli));
    $callSalesCountTravisTotal = mysqli_num_rows($eaCallSalesTravisTotal);
    //Avg Duration
    $eaCallSalesTravisDurations = mysqli_query($mysqli, "SELECT Timestamp,completeTime FROM eainternalcalls
        WHERE complete=1 and assigned = 'mtt'") or die(mysqli_error($mysqli));
    $callSalesCountTravisDurations = mysqli_num_rows($eaCallSalesTravisDurations);      
          
    
    $callsTurnaroundTravis = array();
    while ($callAvgTravis =  mysqli_fetch_array($eaCallSalesTravisDurations))
    {
        $callsAvgTravis[] = $callAvgTravis;
    }
          
    $durationTravis = 0;
    foreach ($callsAvgTravis as $callAvgTravis)
    {    
       $durationTravis = $durationTravis + getMinutes($callAvgTravis['completeTime'], $callAvgTravis['Timestamp']);
       
    }
    $totalMinAllSalesmen =  $totalMinAllSalesmen + $durationTravis;
    $durationTravis = $durationTravis/$callSalesCountTravis;
    //Conversion rate
    $conversionTravis = $callSalesCountTravisTotal/(($callSalesCountTravis/100));
          
    //Rick   
    $eaCallSalesRick = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'rick'") or die(mysqli_error($mysqli));
    $callSalesCountRick = mysqli_num_rows($eaCallSalesRick);
          
    $eaCallSalesRickTotal = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'rick' AND sale=1") or die(mysqli_error($mysqli));
    $callSalesCountRickTotal = mysqli_num_rows($eaCallSalesRickTotal);     
    
        //Avg Duration
    $eaCallSalesRickDurations = mysqli_query($mysqli, "SELECT Timestamp,completeTime FROM eainternalcalls
        WHERE complete=1 and assigned = 'rick'") or die(mysqli_error($mysqli));
    $callSalesCountRickDurations = mysqli_num_rows($eaCallSalesRickDurations);      
          
    
    $callsTurnaroundRick = array();
    while ($callAvgRick =  mysqli_fetch_array($eaCallSalesRickDurations))
    {
        $callsAvgRick[] = $callAvgRick;
    }
          
    $durationRick = 0;
    foreach ($callsAvgRick as $callAvgRick)
    {    
       $durationRick = $durationRick + getMinutes($callAvgRick['completeTime'], $callAvgRick['Timestamp']);
       
    }
    $totalMinAllSalesmen =  $totalMinAllSalesmen + $durationRick;
    $durationRick = $durationRick/$callSalesCountRick;      
          
          
        //Conversion rate
    $conversionRick = $callSalesCountRickTotal/(($callSalesCountRick/100));      
          
    //RickH      
    $eaCallSalesRickH = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'rickh'") or die(mysqli_error($mysqli));
    $callSalesCountRickH = mysqli_num_rows($eaCallSalesRickH);
          
    $eaCallSalesRickHTotal = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'rickh' AND sale=1") or die(mysqli_error($mysqli));
    $callSalesCountRickHTotal = mysqli_num_rows($eaCallSalesRickHTotal);           
     
            //Avg Duration
    $eaCallSalesRickHDurations = mysqli_query($mysqli, "SELECT Timestamp,completeTime FROM eainternalcalls
        WHERE complete=1 and assigned = 'rickh'") or die(mysqli_error($mysqli));
    $callSalesCountRickHDurations = mysqli_num_rows($eaCallSalesRickHDurations);      
          
    
    $callsTurnaroundRickH = array();
    while ($callAvgRickH =  mysqli_fetch_array($eaCallSalesRickHDurations))
    {
        $callsAvgRickH[] = $callAvgRickH;
    }
          
    $durationRickH = 0;
    foreach ($callsAvgRickH as $callAvgRickH)
    {    
       $durationRickH = $durationRickH + getMinutes($callAvgRickH['completeTime'], $callAvgRickH['Timestamp']);
       
    }
    $totalMinAllSalesmen =  $totalMinAllSalesmen + $durationRickH;
    $durationRickH = $durationRickH/$callSalesCountRickH;      
          
          
        //Conversion rate
    $conversionRick = $callSalesCountRickTotal/(($callSalesCountRick/100));     
          
    //Conversion rate
    $conversionRickH = $callSalesCountRickHTotal/(($callSalesCountRickH/100));      
          
    //Jeremy      
    $eaCallSalesJeremy = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'jeremy'") or die(mysqli_error($mysqli));
    $callSalesCountJeremy = mysqli_num_rows($eaCallSalesJeremy);
          
    $eaCallSalesJeremyTotal = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'jeremy' AND sale=1") or die(mysqli_error($mysqli));
    $callSalesCountJeremyTotal = mysqli_num_rows($eaCallSalesJeremyTotal);   
    
    //Avg Duration
    $eaCallSalesJeremyDurations = mysqli_query($mysqli, "SELECT Timestamp,completeTime FROM eainternalcalls
        WHERE complete=1 and assigned = 'jeremy'") or die(mysqli_error($mysqli));
    $callSalesCountJeremyDurations = mysqli_num_rows($eaCallSalesJeremyDurations);      
          
    
    $callsTurnaroundJeremy = array();
    while ($callAvgJeremy =  mysqli_fetch_array($eaCallSalesJeremyDurations))
    {
        $callsAvgJeremy[] = $callAvgJeremy;
    }
          
    $durationJeremy = 0;
    foreach ($callsAvgJeremy as $callAvgJeremy)
    {    
       $durationJeremy = $durationJeremy + getMinutes($callAvgJeremy['completeTime'], $callAvgJeremy['Timestamp']);
       
    }
    $totalMinAllSalesmen =  $totalMinAllSalesmen + $durationJeremy;
    $durationJeremy = $durationJeremy/$callSalesCountJeremy;      
          
          
        //Conversion rate
    $conversionJeremy = $callSalesCountJeremyTotal/(($callSalesCountJeremy/100));          
          
        //Conversion rate
    $conversionJeremy = $callSalesCountJeremyTotal/(($callSalesCountJeremy/100));      
          
    $calls = array();
    while ($call =  mysqli_fetch_array($eaCallList))
    {
        $calls[] = $call;
    }
    //Garrett   
    $eaCallSalesGarrett = mysqli_query($mysqli, "SELECT eainternalcalls.assigned,eainternalcalls.sale,eainternalcalls.complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'garrett'") or die(mysqli_error($mysqli));
    $callSalesCountGarrett = mysqli_num_rows($eaCallSalesGarrett);
          
    $eaCallSalesGarrettTotal = mysqli_query($mysqli, "SELECT assigned,sale,complete FROM eainternalcalls
        WHERE complete=1 and assigned = 'garrett' AND sale=1") or die(mysqli_error($mysqli));
    $callSalesCountGarrettTotal = mysqli_num_rows($eaCallSalesGarrettTotal);
    //Avg Duration
    $eaCallSalesGarrettDurations = mysqli_query($mysqli, "SELECT Timestamp,completeTime FROM eainternalcalls
        WHERE complete=1 and assigned = 'garrett'") or die(mysqli_error($mysqli));
    $callSalesCountGarrettDurations = mysqli_num_rows($eaCallSalesGarrettDurations);      
          
    
    $callsTurnaroundGarrett = array();
    while ($callAvgGarrett =  mysqli_fetch_array($eaCallSalesGarrettDurations))
    {
        $callsAvgGarrett[] = $callAvgGarrett;
    }
          
    $durationGarrett = 0;
    foreach ($callsAvgGarrett as $callAvgGarrett)
    {    
       $durationGarrett = $durationGarrett + getMinutes($callAvgGarrett['completeTime'], $callAvgGarrett['Timestamp']);
       
    }
    $totalMinAllSalesmen =  $totalMinAllSalesmen + $durationGarrett;
    $durationGarrett = $durationGarrett/$callSalesCountGarrett;
    //Conversion rate
    $conversionGarrett = $callSalesCountGarrettTotal/(($callSalesCountGarrett/100));
    ?>      
      </div>
    </div>

    <br>
        <?php $percSalesperCalls = $callSalesCount/($callCount/100); ?>
        <div style="text-align:center;">All-time Call Count: <div style="font-weight: bold; font-size: 32px;"><?php echo $callCount ?></div>
        All-time Sales: <div style="font-weight: bold; font-size: 32px;"><?php echo $callSalesCount ?>&nbsp;Sales<br>(<?php echo round($percSalesperCalls,1)?>% Converted)</div>
        <?php
        $overAllTurnaround = $totalMinAllSalesmen/$callCount;   
        ?>
        Overall Turnaround (Avg):&nbsp;<div style="font-weight: bold; font-size: 32px;"><?php echo round($overAllTurnaround,1)?> minutes</div></div><br><br>

   <table class="table table-striped" style="filter: alpha(opacity=40); opacity: 0.95;border:1px #CDCACA solid;">
  <tbody>
    <tr style="background: #333;color:white;">
      <td><strong>Salesman</strong></td>
      <td><strong>Calls</strong></td>
      <td><strong>Sales</strong></td>
        <td><strong>Conversion (%)</strong></td>
        <td><strong>Turnaround (Avg Minutes)</strong></td>
    </tr>
    <tr>
      <td>Travis</td>
      <td><?php echo $callSalesCountTravis; ?></td>
      <td><?php echo $callSalesCountTravisTotal; ?></td>
      <td><?php echo round($conversionTravis,1).'%'; ?></td>
      <td><?php echo round($durationTravis,1).' min'; ?></td>
    </tr>
    <tr>
      <td>Rick R</td>
      <td><?php echo $callSalesCountRick; ?></td>
      <td><?php echo $callSalesCountRickTotal; ?></td>
        <td><?php echo round($conversionRick,1).'%'; ?></td>
        <td><?php echo round($durationRick,1).' min'; ?></td>
    </tr>
    <tr>
      <td>Rick H</td>
      <td><?php echo $callSalesCountRickH; ?></td>
      <td><?php echo $callSalesCountRickHTotal; ?></td>
      <td><?php echo round($conversionRickH,1).'%'; ?></td>
        <td><?php echo round($durationRickH,1).' min'; ?></td>
    </tr>
    <tr>
      <td>Jeremy</td>
      <td><?php echo $callSalesCountJeremy; ?></td>
      <td><?php echo $callSalesCountJeremyTotal; ?></td>
      <td><?php echo round($conversionJeremy,1).'%'; ?></td>
        <td><?php echo round($durationJeremy,1).' min'; ?></td>
    </tr>
          <tr>
      <td>Garrett</td>
      <td><?php echo $callSalesCountGarrett; ?></td>
      <td><?php echo $callSalesCountGarrettTotal; ?></td>
      <td><?php echo round($conversionGarrett,1).'%'; ?></td>
      <td><?php echo round($durationGarrett,1).' min'; ?></td>
    </tr>
  </tbody>
</table>
   
        </div>
</body>
</html>
<?php
// calcs secs * mins * hours * year * day(s)
$yearTimer = 60 * 60 * 24 * 365 * 1;
//error_reporting(0);
session_set_cookie_params($yearTimer, "/"); //one year in seconds
session_start();
function loginGate()
{
  if (!isset($_SESSION['userName']) || (!isset($_SESSION['role']))) {
    header('location: login.php');
    exit();
  }
}

// loginGate();
include('db.php');
include('challenge.php');
$usr = $_SESSION['userName'];
$role = $_SESSION['role'];
?>
<!doctype html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel='stylesheet' href="css/index-style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script defer src='js/index-script.js'></script>
  <title>EA Call System</title>
</head>

<body onLoad="autoload()">
  <div class="content">
    <img src="img/EALogoW.png" width="800" height="129" alt="" />
  </div>

  <div style="background:#CCCCCC;height: 2px;">
    <hr>
  </div>
  <div style="margin-left: 0px;">
    <div class="topnav">
      <a class="active" href="#home">Home</a>
      <a href="#open">Open</a>
      <a href="#history">History</a>
      <?php if ($_SESSION['role'] == "admin") { ?>
        <a href="stats.php">Stats</a>
      <?php } ?>
      <a href="logout.php">Logout</a>
      <div style="color: #CCCCCC; font-size: 15pt; margin-left: 500px;margin-top: 12px">
        <?php
        $usr = $_SESSION['userName'];
        $roleC = $_SESSION['role'];
        $usr = ucwords($usr);
        if ($roleC == 'admin') {
          echo "<div style='color:red;'>Welcome, " . $usr . "</div>";
        } else {
          echo "Welcome, " . $usr;
        }

        $UN = $_SESSION['userName'];
        $role = $_SESSION['role'];
        $sqlInject = " AND (assigned='$UN')";
        if ($role == 'admin') {
          $sqlInject = '';
        }

        $eaCallList = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where ((complete=0) AND (assigned='$UN')) Order by ID,Timestamp ASC") or die(mysqli_error($mysqli));
        $callCount = mysqli_num_rows($eaCallList);

        $eaCallOpen = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where complete=0 Order by ID,Timestamp ASC") or die(mysqli_error($mysqli));
        $openCallCount = mysqli_num_rows($eaCallOpen);

        $eaCallHistory = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where (complete=1 $sqlInject) Order by ID DESC") or die(mysqli_error($mysqli));
        $HistoryCallCount = mysqli_num_rows($eaCallHistory);

        $eaSalesOpenTravis = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where (complete=0 AND assigned='mtt')") or die(mysqli_error($mysqli));
        $SalesOpenCallCountTravis = mysqli_num_rows($eaSalesOpenTravis);

        $eaSalesOpenRick = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where (complete=0 AND assigned='rick')") or die(mysqli_error($mysqli));
        $SalesOpenCallCountRick = mysqli_num_rows($eaSalesOpenRick);

        $eaSalesOpenRickH = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where (complete=0 AND assigned='rickh')") or die(mysqli_error($mysqli));
        $SalesOpenCallCountRickH = mysqli_num_rows($eaSalesOpenRickH);

        $eaSalesOpenJeremy = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where (complete=0 AND assigned='jeremy')") or die(mysqli_error($mysqli));
        $SalesOpenCallCountJeremy = mysqli_num_rows($eaSalesOpenJeremy);

        $eaSalesOpenGarrett = mysqli_query($mysqli, "SELECT * FROM eainternalcalls where (complete=0 AND assigned='garrett')") or die(mysqli_error($mysqli));
        $SalesOpenCallCountGarrett = mysqli_num_rows($eaSalesOpenGarrett);
        ?>
      </div>
    </div>
    <br>
  </div><?php if ($_SESSION['role'] == "admin") { ?>
    <div style="margin-left: 0px;">
      <div class="form-group">
        <form method="get" name="newCall" action="insert_call.php" onsubmit="return validateForm()">
          <table style="filter: alpha(opacity=40); opacity: 0.95;border:1px #CDCACA solid; margin:auto;margin-top: -70px;">
            <tbody class="form-main">
              <div id="hintMsg" style="color:red; margin-left: 800px;margin-bottom: -200;font-weight: bold;">&nbsp;</div><br><br>
              <tr>
                <td style="text-align: center;font-weight: bold;">*Name:</td>
                <td><input type="text" class="form-control" name="name"></td>
              </tr>
              <tr>
                <td style="text-align: center">Email:</td>
                <td><input type="text" class="form-control" name="email"></td>
              </tr>
              <tr>
                <td style="text-align: center;font-weight: bold;">*Phone:</td>
                <td><input type="text" class="form-control" name="phone"></td>
              </tr>
              <tr>
                <td style="text-align: center">EA product</td>
                <td><input type="text" class="form-control" name="product"></td>
              </tr>
              <tr>
                <td style="text-align: center;font-weight: bold;">&nbsp;*Reason for Call&nbsp;&nbsp;</td>
                <td><textarea id="rCall" class="form-control" name="rCall" rows="4" cols="50"></textarea>
                </td>
              </tr>
              <tr>
                <td style="text-align: center">Part?</td>
                <td><input name="part" class="form-control" type="checkbox" value="1"></td>
              </tr>
              <tr>
                <td style="text-align: center">Tractor</td>
                <td><input type="text" class="form-control" name="tractor"></td>
              </tr>
              <tr>
                <td style="text-align: center;font-weight: bold;">*Category:</td>
                <td>
                  <div class="form-group">
                    <select class="combobox form-control" name="inherit">
                      <option value="" selected="selected">Select</option>
                      <option value="mtt">Grapple (Travis-<?php echo $SalesOpenCallCountTravis ?>)</option>
                      <option value="rick">General (Rick-<?php echo $SalesOpenCallCountRick ?>)</option>
                      <option value="rickh">Parts (RickH-<?php echo $SalesOpenCallCountRickH ?>)</option>
                      <option value="jeremy">Parts (Jeremy-<?php echo $SalesOpenCallCountJeremy ?>)</option>
                      <option value="garrett">Parts (Garrett-<?php echo $SalesOpenCallCountGarrett ?>)</option>
                    </select>
                  </div>
        </form>
      </div>
    </div>
    </td>
    </tr>
    <tr>
      <td style="text-align: center">&nbsp;</td>
      <td><input type="submit" class="btn btn-primary"></td>
    </tr>
    </tbody>

    </form>
    </div>
    </div>

    <br>
    <br>

  <?php }
  ?>
  <div id="dat">
    <?php



    if ($callCount > 0) {
    ?>
      <table class="table table-striped" style="filter: alpha(opacity=40); opacity: 0.95;border:1px #CDCACA solid; margin:auto">
        <thead>
          <tr>
            <th bgcolor="#CBC8C8">ID</th>
            <th bgcolor="#CBC8C8">Name</th>
            <th bgcolor="#CBC8C8">Email</th>
            <th bgcolor="#CBC8C8">Phone</th>
            <th bgcolor="#CBC8C8">Reason for Call</th>
            <th bgcolor="#CBC8C8">Product</th>
            <th bgcolor="#CBC8C8">Part?</th>
            <th bgcolor="#CBC8C8">Tractor</th>
            <th bgcolor="#CBC8C8">Time</th>
            <th bgcolor="#CBC8C8">Entered By</th>
            <?php if ($_SESSION['role'] == "admin") { ?>
              <th bgcolor="#CBC8C8">&nbsp;</th>
            <?php } ?>
            <th bgcolor="#CBC8C8">Disposition</th>
          </tr>
        </thead>

      <?php
    }
    $plural = '';
    if ($callCount > 1) {
      $plural = 's';
    }
    if ($callCount > 0) {
      $calls = array();
      while ($call =  mysqli_fetch_array($eaCallList)) {
        $calls[] = $call;
      }
      $partResponse = 'No';
      ?>
        <div style="background: #403E3E;margin-left:0px">
          <h1 style="color:white; margin-left: 0px;text-align: center"><?php echo $callCount ?>&nbsp;Call<?php echo $plural ?></h1>
        </div>
        <?php
        foreach ($calls as $call) {
        ?>
          <tbody>
            <form id="<?php echo $call['ID'] ?>">
              <tr>
                <input type="hidden" name="id" value="<?php echo $call['ID']; ?>" <?php echo $call['ID']; ?>>
                <th scope="row"><?php echo $call['ID']; ?></th>
                <td><?php echo $call['Name']; ?></td>
                <td><a href="mailto:<?php echo $call['Email']; ?>"><?php echo $call['Email']; ?></a></td>
                <td><?php echo $call['Phone']; ?></td>
                <td><textarea id="rCall" rows="4" cols="50"><?php echo $call['callReason']; ?></textarea>
                <td><?php echo $call['Product']; ?></td>
                <?php if ($call['Part'] == '1') {
                  $partResponse = 'Yes';
                } else {
                  $partResponse = 'No';
                } ?>
                <td><?php echo $partResponse; ?></td>
                <td><?php echo $call['Tractor']; ?></td>
                <td><?php echo $call['Timestamp']; ?></td>
                <td><?php echo $call['Enteredby']; ?></td>
                <?php if ($_SESSION['role'] == "admin") { ?>
                  <td><input type="button" value="Reassign" class="btn btn-warning"></td>
                <?php } ?>
                <td><input type="button" value="Completed" class="btn btn-danger" onClick="openForm('saleForm<?php echo $call['ID']; ?>');"></td>
              </tr>
            </form>

          </tbody>
          <div class="form-popup" id="saleForm<?php echo $call['ID']; ?>">
            <form action="complete.php" class="form-container">
              <h3>Did this call result in a sale?</h3>
              <input type="hidden" name="id" value="<?php echo $call['ID']; ?>" <?php echo $call['ID']; ?>>
              <button style="color:white;background: #28A745;" type="submit" name="sYes" value="1" class="btn">Yes</button>
              <button style="color:white;background:#D6433F;" type="submit" name="sYes" value="0" class="btn no">No</button>
              <button style="margin-left: 150px;background: #FFC107;" type="button" class="btn cancel" onclick="closeForm('saleForm<?php echo $call['ID']; ?>')">Cancel</button>
            </form>
            <br><br>
          </div>
        <?php
        }
      }

      if ($_SESSION['role'] == 'admin') {
        ?>
        <table class="table table-striped" style="filter: alpha(opacity=40); opacity: 0.95;border:1px #CDCACA solid; margin:auto">
          <thead>
            <tr>
              <th bgcolor="#CBC8C8">ID</th>
              <th bgcolor="#CBC8C8">Name</th>
              <th bgcolor="#CBC8C8">Email</th>
              <th bgcolor="#CBC8C8">Phone</th>
              <th bgcolor="#CBC8C8">Reason for Call</th>
              <th bgcolor="#CBC8C8">Product</th>
              <th bgcolor="#CBC8C8">Part?</th>
              <th bgcolor="#CBC8C8">Tractor</th>
              <th bgcolor="#CBC8C8">Time</th>
              <th bgcolor="#CBC8C8">Entered By</th>
              <th bgcolor="#CBC8C8">Assigned</th>
              <?php if ($_SESSION['role'] == "admin") { ?>
                <th bgcolor="#CBC8C8">&nbsp;</th>
              <?php } ?>
            </tr>
          </thead>
          <?php
          $plural = '';
          if ($openCallCount > 1) {
            $plural = 's';
          }
          if ($openCallCount > 0) {
            $opencalls = array();
            while ($opencall =  mysqli_fetch_array($eaCallOpen)) {
              $opencalls[] = $opencall;
            }
            $partResponse = 'No';
          ?>
            <div id=open style="background: #333;margin-left:0px;margin-bottom: 3px;margin-top: 3px">
              <h1 style="color:white; margin-left: 0px;text-align: center"><?php echo $openCallCount ?>&nbsp;Open&nbsp;Call<?php echo $plural ?></h1>
            </div>
            <?php
            foreach ($opencalls as $opencall) {
            ?>
              <tbody>
                <form id="<?php echo $opencall['ID'] ?>">
                  <tr>
                    <input type="hidden" name="id" value="<?php echo $opencall['ID']; ?>" <?php echo $opencall['ID']; ?>>
                    <th scope="row"><?php echo $opencall['ID']; ?></th>
                    <td><?php echo $opencall['Name']; ?></td>
                    <td><a href="mailto:<?php echo $opencall['Email']; ?>"><?php echo $opencall['Email']; ?></a></td>
                    <td><?php echo $opencall['Phone']; ?></td>
                    <td><textarea id="rCall" rows="4" cols="50"><?php echo $opencall['callReason']; ?></textarea>
                    <td><?php echo $opencall['Product']; ?></td>
                    <?php if ($opencall['Part'] == '1') {
                      $partResponse = 'Yes';
                    } else {
                      $partResponse = 'No';
                    } ?>
                    <td><?php echo $partResponse; ?></td>
                    <td><?php echo $opencall['Tractor']; ?></td>
                    <td><?php echo $opencall['Timestamp']; ?></td>
                    <td><?php echo $opencall['Enteredby']; ?></td>
                    <td style="color:red;font-weight: bold;"><?php echo $opencall['assigned']; ?></td>
                    <td><input type="button" value="Reassign" class="btn btn-warning" onClick="openForm('assignForm<?php echo $opencall['ID']; ?>');"></td>
                  </tr>
                </form>

              </tbody>
              <div class="form-popup" id="assignForm<?php echo $opencall['ID']; ?>">
                <form action="reassign.php" class="form-container">
                  <h3>Reassign to:</h3>
                  <input type="hidden" name="id" value="<?php echo $opencall['ID']; ?>" <?php echo $opencall['ID']; ?>>
                  <select id="assigned" name="assigned">
                    <option value="mtt">Travis</option>
                    <option value="rick">Rick</option>
                    <option value="jeremy">Jeremy</option>
                    <option value="rickh">RickH</option>
                    <option value="garrett">Garrett</option>
                  </select>
                  <button style="color:white;background: #28A745;" type="submit" name="sYes" value="1" class="btn">OK</button><br>
                  <button style="margin-left: 200px;margin-top:-57px; background: #FFC107;" type="button" class="btn cancel" onclick="closeForm('assignForm<?php echo $opencall['ID']; ?>')">Cancel</button>
                </form>
                <br><br>
              </div>
        <?php
            }
          }
        }
        ?>

        </table>

        <?php

        if ($HistoryCallCount > 1) {
        ?>
          <table class="table table-striped" style="filter: alpha(opacity=40); opacity: 0.95;border:1px #CDCACA solid; margin:auto">
            <thead>
              <tr>
                <th bgcolor="#CBC8C8">ID</th>
                <th bgcolor="#CBC8C8">Name</th>
                <th bgcolor="#CBC8C8">Email</th>
                <th bgcolor="#CBC8C8">Phone</th>
                <th bgcolor="#CBC8C8">Reason for Call</th>
                <th bgcolor="#CBC8C8">Product</th>
                <th bgcolor="#CBC8C8">Part?</th>
                <th bgcolor="#CBC8C8">Tractor</th>
                <th bgcolor="#CBC8C8">Time</th>
                <th bgcolor="#CBC8C8">Entered By</th>
                <th bgcolor="#CBC8C8">Assigned</th>
                <?php if ($_SESSION['role'] == "admin") { ?>
                  <th style="background:red;color:white;text-align:center;">Turnaround</th>
                <?php } ?>
              </tr>
            </thead>
          <?php
        }

        $plural = '';
        if ($HistoryCallCount > 1) {
          $plural = 's';
        }
        if ($HistoryCallCount > 0) {
          $historycalls = array();
          while ($historycall =  mysqli_fetch_array($eaCallHistory)) {
            $historycalls[] = $historycall;
          }
          $partResponse = 'No';
          ?>
            <div id=history style="background: #333;margin-left:0px;margin-bottom: 3px;margin-top: 3px">
              <h1 style="color:white; margin-left: 0px;text-align: center"><?php echo $HistoryCallCount ?>&nbsp;in&nbsp;History</h1>
            </div>
            <?php

            foreach ($historycalls as $historycall) {
              if ($_SESSION['role'] == 'admin') {
                //Some date stuff...calculated additional in case we ever need it       
                // two dates 
                $date1 = strtotime($historycall['Timestamp']);
                $date2 = strtotime($historycall['completeTime']);

                // Difference between two dates 
                $diff = abs($date2 - $date1);

                // year - divide the resulting date into 
                // total seconds in a year (365*60*60*24) 
                $years = floor($diff / (365 * 60 * 60 * 24));

                // month - subtract it from years and 
                // divide the resulting date into 
                // total seconds in a month (30*60*60*24) 
                $months = floor(($diff - $years * 365 * 60 * 60 * 24)
                  / (30 * 60 * 60 * 24));

                // To get the day, subtract it with years and  
                // months and divide the resulting date into 
                // total seconds in a day (60*60*24) 
                $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
                  $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

                // hour - subtract it with years,  
                // months & seconds and divide the result 
                // date into total seconds in an hour (60*60) 
                $hours = floor(($diff - $years * 365 * 60 * 60 * 24
                  - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
                  / (60 * 60));

                // minutes - subtract it from years, 
                // months, seconds and hours and divide the  
                // resultant date into total seconds i.e. 60 
                $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
                  - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
                  - $hours * 60 * 60) / 60);

                // seconds - subtract it from years, 
                // months, seconds, hours and minutes  
                $seconds = floor(($diff - $years * 365 * 60 * 60 * 24
                  - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
                  - $hours * 60 * 60 - $minutes * 60));
              }
            ?>

              <tbody>
                <form id="<?php echo $historycall['ID'] ?>">
                  <tr>
                    <input type="hidden" name="id" value="<?php echo $historycall['ID']; ?>" <?php echo $historycall['ID']; ?>>
                    <th scope="row"><?php echo $historycall['ID']; ?></th>
                    <td><?php echo $historycall['Name']; ?></td>
                    <td><a href="mailto:<?php echo $historycall['Email']; ?>"><?php echo $historycall['Email']; ?></a></td>
                    <td><?php echo $historycall['Phone']; ?></td>
                    <td><textarea id="rCall" rows="4" cols="50"><?php echo $historycall['callReason']; ?></textarea>
                    <td><?php echo $historycall['Product']; ?></td>
                    <?php if ($historycall['Part'] == '1') {
                      $partResponse = 'Yes';
                    } else {
                      $partResponse = 'No';
                    } ?>
                    <td><?php echo $partResponse; ?></td>
                    <td><?php echo $historycall['Tractor']; ?></td>
                    <td><?php echo $historycall['Timestamp']; ?></td>
                    <td><?php echo $historycall['Enteredby']; ?></td>
                    <td style="color:red;font-weight: bold;"><?php echo $historycall['assigned']; ?></td>
                    <td><?php
                        if ($_SESSION['role'] == 'admin') {
                          printf("%d days, %d hrs, "
                            . "%d min",  $days, $hours, $minutes);
                        }
                        ?>
                    </td>
                  </tr>
                </form>

              </tbody>
          <?php
            }
          } ?>
  </div>
  <script>
    function refresher() {
      <?php if ($_SESSION['role'] != "admin") { ?>
        window.location.reload();
      <?php } ?>
    }

    function autoload() {
      setInterval('refresher()', 180000);
    }
  </script>

  <a href="javascript:void(0);" id="scroll" title="Scroll to Top" style="display: none;z-index: 1;">Top<span></span></a>
  <?php
  $totalCount = $openCallCount + $HistoryCallCount;
  if ($totalCount == 1) { ?>
    No Results
  <?php }
  ?>

</body>

</html>
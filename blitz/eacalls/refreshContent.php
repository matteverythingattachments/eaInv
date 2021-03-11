<?php
session_set_cookie_params(31556926,"/");//one year in seconds
session_start();

include('db.php');
include('challenge.php');            

$UN = $_SESSION['userName'];
            $role = $_SESSION['role'];
            $sqlInject = " AND (assigned='$UN')";
            if ($role == 'admin')
            {
                $sqlInject = '';
            }

            $eaCallList = mysqli_query($mysqli, "SELECT * FROM EAInternalCalls where ((complete=0) AND (assigned='$UN')) Order by ID,Timestamp ASC") or die(mysqli_error($mysqli));
            $callCount = mysqli_num_rows($eaCallList);

            $eaCallOpen = mysqli_query($mysqli, "SELECT * FROM EAInternalCalls where complete=0 Order by ID,Timestamp ASC") or die(mysqli_error($mysqli));
            $openCallCount = mysqli_num_rows($eaCallOpen);

            $eaCallHistory = mysqli_query($mysqli, "SELECT * FROM EAInternalCalls where (complete=1 $sqlInject) Order by ID DESC") or die(mysqli_error($mysqli));
            $HistoryCallCount = mysqli_num_rows($eaCallHistory);
          
            $eaSalesOpenTravis = mysqli_query($mysqli, "SELECT * FROM EAInternalCalls where (complete=0 AND assigned='mtt')") or die(mysqli_error($mysqli));
            $SalesOpenCallCountTravis = mysqli_num_rows($eaSalesOpenTravis);
          
            $eaSalesOpenRick = mysqli_query($mysqli, "SELECT * FROM EAInternalCalls where (complete=0 AND assigned='rick')") or die(mysqli_error($mysqli));
            $SalesOpenCallCountRick = mysqli_num_rows($eaSalesOpenRick);
          
            $eaSalesOpenRickH = mysqli_query($mysqli, "SELECT * FROM EAInternalCalls where (complete=0 AND assigned='rickh')") or die(mysqli_error($mysqli));
            $SalesOpenCallCountRickH = mysqli_num_rows($eaSalesOpenRickH);
          
            $eaSalesOpenJeremy = mysqli_query($mysqli, "SELECT * FROM EAInternalCalls where (complete=0 AND assigned='jeremy')") or die(mysqli_error($mysqli));
            $SalesOpenCallCountJeremy = mysqli_num_rows($eaSalesOpenJeremy);
        ?>
 
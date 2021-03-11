<?php
//error_reporting(0);

function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}

include('../admin/scripts/access.php');
$entered_uname = $_POST['username'];
$pword = $_POST['password']; //md5(md5($_POST['password']));

if($entered_uname == '' and $pword == '') {
	header("Location:../index.php");
}
$auth_query = mysqli_query($conn,"SELECT * FROM users WHERE NAME = '$entered_uname' AND PWORD = '$pword'") or die(mysqli_error($conn));
if(mysqli_num_rows($auth_query) != 1)	{
	die('Authentication Failed');
}
else	{
	session_set_cookie_params(31556926,"/");//one year in seconds
    session_start();
    //$urights = $auth_query['rights'];
	$_SESSION['user'] = $entered_uname;
    //$_SESSION['rights'] = $auth_query['rights'];

    while($auth_list = mysqli_fetch_array($auth_query))	
    {
        $urights = $auth_list['rights'];
        break;
    }
    $_SESSION['rights'] = $urights;
    echo $_SESSION['user'].':'.$_SESSION['rights'];
	if($_SESSION['user'] == 'nate')	{
        redirect('../home3.php');

	}
	else	{
	 redirect('../home3.php');
	}
}
?>
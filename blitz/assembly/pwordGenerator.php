<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
<form enctype="multipart/form-data" action="pwordGenerator.php" method="get">
<input name="pword">
<input type="submit">
</form>
<?php
if(isset($_GET['pword']))	{
	echo '<h1>'.md5(md5($_GET['pword'])).'</h1>';
}
?>

</body>
</html>
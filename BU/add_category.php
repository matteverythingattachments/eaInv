<?php
session_start();
include('scripts/authent.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Category</title>
</head>

<body>
<form name="form1" method="post" action="process_category.php">
  <label for="category"></label>
  <input type="text" name="category" id="category">
  <input type="submit" name="submit" id="submit" value="Submit">
</form>
</body>
</html>
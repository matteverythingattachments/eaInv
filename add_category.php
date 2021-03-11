<?php
session_start();
include('scripts/authent.php');
?>
<!DOCTYPE HTML>
<html>
<head>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Category</title>
</head>

<body>
    <br>
<form name="form1" method="post" action="process_category.php">
  <label for="category"></label>
  <input type="text" size="40" name="category" id="category">
  <input type="submit" name="submit" class="btn btn-success pull-right" id="submit" value="Submit">
</form>
</body>
</html>
<?php 
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$id=$_GET['ID'];
$query = mysqli_query($conn, "SELECT * FROM BRADCO_PRODS WHERE ID = '$id'") or die(mysqli_error());
$info = mysqli_fetch_array($query);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Details for Everything Attachments <?php echo $info['Product'];?></title>
</head>

<body>
<form name="form1" method="post" action="b_prod_update.php?ID=<?php echo $id;?>">
  <table border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse;">
    <tr>
      <th scope="row">Product Name</th>
      <td><input type="text" name="Product" id="textfield" value="<?php echo $info['Product']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">On Hand</th>
      <td><input type="text" name="On_Hand" id="textfield3" value="<?php echo $info['On_Hand']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Ordered</th>
      <td><input type="text" name="Ordered" id="textfield4" value="<?php echo $info['Ordered']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Estimated Delivery</th>
      <td><input type="text" name="Est_Delivery" id="textfield2" value="<?php echo $info['Est_Delivery']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Price</th>
      <td><input type="text" name="Price" id="textfield5" value="<?php echo $info['Price']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Low Limit</th>
      <th scope="row"><label for="Low_Limit"></label>
      <input type="text" name="Low_Limit" id="Low_Limit" value="<?php echo $info['Low_Limit']?>"></th>
    </tr>
    <tr>
      <th colspan="2" scope="row"><input type="submit" name="button" id="button" value="Submit"></th>
    </tr>
  </table>
</form>
</body>
</html>
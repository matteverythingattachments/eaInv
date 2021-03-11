<?php 
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$id=$_GET['ID'];
$query = mysqli_query($conn, "SELECT * FROM ea_prods WHERE ID = '$id'") or die(mysqli_error($conn));
$info = mysqli_fetch_array($query);
?>
<!DOCTYPE HTML>
<html>
<head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Details for Everything Attachments <?php echo $info['Product'];?></title>
</head>

<body>
    <br><br>
<form name="form1" method="post" action="prod_update.php?ID=<?php echo $id;?>">
<input type="hidden" name="cat_id" value="<?php echo $info['Cat_ID'];?>">
  <table border="1" align="center" cellpadding="5" cellspacing="3" style="border-collapse:collapse;">
    <tr>
      <th scope="row">Product Name</th>
      <td><input type="text" name="Product"  size="35" id="textfield" value="<?php echo $info['Product']; ?>"></td>
    </tr>
    <tr>
      <th scope="row" style="color:white;background-color: black;" >Black Qty</th>
      <td><input type="text" name="Black" id="textfield3" value="<?php echo $info['Black']; ?>"></td>
    </tr>
    <tr>
      <th scope="row" style="color:black;background-color: #9BFF9B;">Green Qty</th>
      <td><input type="text"  name="Green" id="textfield4" value="<?php echo $info['Green']; ?>"></td>
    </tr>
    <tr>
      <th scope="row" style="color:black;background-color: #FFAD5B;">Orange Qty</th>
      <td><input type="text"  name="Orange" id="textfield2" value="<?php echo $info['Orange']; ?>"></td>
    </tr>
    <tr>
      <th scope="row" style="color:black;background-color: #FFFF8C;">Yellow Qty</th>
      <td><input type="text"  name="Yellow" id="textfield2" value="<?php echo $info['Yellow']; ?>"></td>
    </tr>
    <tr>
      <th scope="row" style="color:black;background-color:#C4C4C4;">Primer Qty</th>
      <td><input type="text" name="Primer"  id="textfield2" value="<?php echo $info['Primer']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Price</th>
      <td><input type="text" name="Price" id="textfield5" value="<?php echo $info['Price']; ?>"></td>
    </tr>
    <tr>
      <th colspan="2" scope="row">
      <input type="button" class="btn btn-danger pull-right" name="buttonDel" id="buttonDel" value="Delete" onClick="deleteP()">
      <input type="submit" class="btn btn-success pull-right" name="button2" id="button2" value="Update"></th>
    </tr>
  </table>
</form>
    <script>
function deleteP() {
var answer = confirm("Delete this product?")
    if (answer)
    {
        window.location = "delete_product.php?ID=<?php echo $id;?>";
    }

}
</script>
</body>

</html>
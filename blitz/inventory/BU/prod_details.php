<?php 
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');
$id=$_GET['ID'];
$query = mysqli_query($conn, "SELECT * FROM EA_PRODS WHERE ID = '$id'") or die(mysqli_error());
$info = mysqli_fetch_array($query);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Details for Everything Attachments <?php echo $info['Product'];?></title>
</head>

<body>
<form name="form1" method="post" action="prod_update.php?ID=<?php echo $id;?>">
<input type="hidden" name="cat_id" value="<?php echo $info['Cat_ID'];?>">
  <table border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse;">
    <tr>
      <th scope="row">Product Name</th>
      <td><input type="text" name="Product" id="textfield" value="<?php echo $info['Product']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Black Qty</th>
      <td><input type="text" name="Black" id="textfield3" value="<?php echo $info['Black']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Green Qty</th>
      <td><input type="text" name="Green" id="textfield4" value="<?php echo $info['Green']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Orange Qty</th>
      <td><input type="text" name="Orange" id="textfield2" value="<?php echo $info['Orange']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Yellow Qty</th>
      <td><input type="text" name="Yellow" id="textfield2" value="<?php echo $info['Yellow']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Primer Qty</th>
      <td><input type="text" name="Primer" id="textfield2" value="<?php echo $info['Primer']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">Price</th>
      <td><input type="text" name="Price" id="textfield5" value="<?php echo $info['Price']; ?>"></td>
    </tr>
    <tr>
      <th colspan="2" scope="row">
      <input type="button" name="buttonDel" id="buttonDel" value="Delete" onClick="deleteP()">
      <input type="submit" name="button2" id="button2" value="Update"></th>
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
<?php
include('db_access.php');

$custFile = simplexml_load_file('http://www.everythingattachments.com/net/WebService.aspx?Login=nate@everythingattachments.com&EncryptedPassword=57B4F916BB94710EA41011E234575789D420171A54D3F1F7D3003BCA58F0D579&EDI_Name=Generic\Customers&SELECT_Columns=CustomerID');
foreach($custFile->Customers as $custInfo)	{
	$email = $custInfo->EmailAddress;
	$custNumber = $custInfo->CustomerID;
	
	mysql_query("INSERT INTO emails VALUES(
	'$custNumber','$email')") or die(mysql_error());
}


$x =0;
$xml = simplexml_load_file('http://www.everythingattachments.com/net/WebService.aspx?Login=nate@everythingattachments.com&EncryptedPassword=57B4F916BB94710EA41011E234575789D420171A54D3F1F7D3003BCA58F0D579&EDI_Name=Generic\Orders&SELECT_Columns=o.OrderID,o.CustomerID,o.OrderDate,o.OrderStatus,o.PaymentAmount,o.PaymentMethodID,o.SalesTax1,o.ShipAddress1,o.ShipAddress2,o.ShipCity,o.ShipCompanyName,o.ShipCountry,o.ShipDate,o.ShipFaxNumber,o.ShipFirstName,o.ShipLastName,o.ShipPhoneNumber,o.ShippingMethodID,o.ShipPostalCode,o.ShipResidential,o.ShipState,od.OrderDetailID,od.Options,od.ProductCode,od.ProductName,od.ProductPrice,od.Quantity,od.ShipDate');
foreach($xml->Orders as $thing)	{
	$orderID = $thing->OrderID;
	$CustomerID = $thing->CustomerID;
	$OrderDate = explode(" ",$thing->OrderDate);
	$OD = explode("/", $OrderDate['0']);
	$correctOrderDate = $OD['2'].'-'.$OD['0'].'-'.$OD['1'];
	$OrderStatus = $thing->OrderStatus;
	$PaymentAmount = $thing->PaymentAmount;
	$PaymentMethodID = $thing->PaymentMethodID;
	$SalesTax1 = $thing->SalesTax1;
	$ShippingMethodID = $thing->ShippingMethodID;
	$ShipState = $thing->ShipState;
	$ShipLine1 = str_replace('\'','',$thing->ShipAddress1);
	$ShipLine2 = $thing->ShipAddress2;
	$ShipCity = str_replace('\'','',$thing->ShipCity);
	$ShipCompanyName = str_replace('\'','',$thing->ShipCompanyName);
	$ShipCountry = $thing->ShipCountry;
	$ShipFirstName = str_replace('\'','',$thing->ShipFirstName);
	$ShipLastName = str_replace('\'','',$thing->ShipLastName);
	$ShipName = str_replace('\'','',$ShipFirstName.' '.$ShipLastName);
	$ShipPhoneNum = $thing->ShipPhoneNumber;
	$ShipPostalCode = $thing->ShipPostalCode;
	
	$check_query = mysql_query("SELECT OrderID FROM orders WHERE OrderID = $orderID");
	if(mysql_num_rows($check_query) == 1)	{
		mysql_query("DELETE FROM orders WHERE OrderID = $orderID");
		mysql_query("DELETE FROM order_details WHERE OrderID = $orderID");
	}
mysql_query("INSERT INTO orders VALUES(
'$orderID','$CustomerID','$correctOrderDate','$OrderStatus','$PaymentAmount','$PaymentMethodID','$SalesTax1','$ShippingMethodID','$ShipState','$ShipLine1','$ShipLine2','$ShipCity','$ShipCompanyName','$ShipCountry','$ShipName','$ShipLastName','$ShipPhoneNum','$ShipPostalCode','','')") or die(mysql_error());

	foreach($thing->OrderDetails as $details)	{
		$OrderDetailID = $details->OrderDetailID;
		$Options = preg_replace('/[^a-zA-Z0-9_ -]/s','',$details->Options);
		$OrderID = $details->OrderID;
		$ProductCode = $details->ProductCode;
		$ProductName = preg_replace('/[^a-zA-Z0-9_ -]/s','',$details->ProductName);
		$ProductPrice = $details->ProductPrice;
		$Quantity = $details->Quantity;
		$ShipDate = $details->ShipDate;
		if(!isset($ShipDate))	{$ShipDate = ' ';}
	mysql_query("INSERT INTO order_details VALUES (
	'$OrderDetailID','$Options','$OrderID','$ProductCode','$ProductName','$ProductPrice','$Quantity','$ShipDate')") or die(mysql_error());
		}
		$x++;
}
echo $x.' Orders Imported';
?>
<?php
error_reporting(0);
$FO = fopen('csvimporttomin.csv','w+');

 $csvCreationQuery = mysqli_query($mysqli,"SELECT ID, Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, Location, round(MinQty/Qty, 3) AS Sev_In FROM parts WHERE Thickness = $_GET[thickness] AND is_tube = 'n' AND Qty < MinQty ORDER BY Qty");
  while($csvCreationArray = mysqli_fetch_array($csvCreationQuery))	{
   $qtyNeeded = $csvCreationArray['MinQty'] - $csvCreationArray['Qty'];

$text =  'LOAD,PART,'.$csvCreationArray['Name'].','.$qtyNeeded.'
';
fwrite($FO,$text);
 } 
fclose($FO);
?>

<br>
<a href="csvimporttomin.csv" download>Download CSV To Reach MinQty</a>

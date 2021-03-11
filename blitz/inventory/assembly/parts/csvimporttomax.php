<?php
$FO = fopen('csvimporttomax.csv','w+');

 $csvCreationQuery = mysqli_query($mysqli,"SELECT ID, Name, Thickness, Qty, MinQty, MaxQty FROM parts WHERE Thickness = $_GET[thickness] AND is_tube = 'n' AND Qty < MaxQty ORDER BY Qty");
  while($csvCreationArray = mysqli_fetch_array($csvCreationQuery))	{
   $qtyNeeded = $csvCreationArray['MaxQty'] - $csvCreationArray['Qty'];

$text =  'LOAD,PART,'.$csvCreationArray['Name'].','.$qtyNeeded.'
';
fwrite($FO,$text);
 } 
fclose($FO);
?>

<br>
<a href="csvimporttomax.csv" download>Download CSV To Reach MaxQty</a>

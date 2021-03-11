<?php
$FO = fopen('csvimportto0.csv','w+');
$thick = $_GET[thickness];
 
$csvCreationQuery = mysqli_query($mysqli,"SELECT ID, Name, Description, Thickness, Qty, MinQty, MaxQty, ReorderValue, Location, round(MinQty/Qty, 3) AS Sev_In FROM parts WHERE Thickness = $thick AND is_tube = 'n' AND Qty < 0 ORDER BY Qty");
  while($csvCreationArray = mysqli_fetch_array($csvCreationQuery))	
  {
      $qtyNeeded = $csvCreationArray['Qty'] * -1;
      $text =  'LOAD,PART,'.$csvCreationArray['Name'].','.$qtyNeeded;
      $val = explode(",",$text);
      fputcsv($FO, $val);
      //fwrite($FO,$text);
  } 

fclose($FO);
?>
<br>
<br>
<a href="csvimportto0.csv" download>Download CSV To Reach 0</a>

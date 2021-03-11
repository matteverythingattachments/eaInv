<?php
// Turn off all error reporting
error_reporting(E_ALL);
?>
<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');

$sql = "SELECT 
categories.CATEGORY,
ea_prods.Product,
ea_prods.Yellow,
ea_prods.Black,
ea_prods.Green,
ea_prods.Orange,
ea_prods.Primer,
ea_prods.Price
FROM
ea_prods
INNER JOIN categories ON categories.ID = ea_prods.Cat_ID
WHERE
((ea_prods.Yellow < 0) OR
(ea_prods.Black < 0) OR
(ea_prods.Green < 0) OR
(ea_prods.Orange < 0) OR
(ea_prods.Primer < 0))
ORDER BY
categories.ID ASC";
$query = mysqli_query($conn,$sql);
if ($query)
{
    $rowcount=mysqli_num_rows($query);
}
else
{
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}
if($rowcount > 0){
    $delimiter = ",";
    date_default_timezone_set('America/New_York');
    $date = date('M_d_Y').'-'.date("H:i");
    $filename = 'EA Out of Stock Report_'.$date.'.csv';

    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('Category','Product', 'Yellow', 'Black', 'Green', 'Orange', 'Primer','Price');
    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = mysqli_fetch_assoc($query)){
        //$status = ($row['status'] == '1')?'Active':'Inactive';
        $lineData = array($row['CATEGORY'], $row['Product'], $row['Yellow'], $row['Black'], $row['Green'], $row['Orange'],$row['Primer'],$row['Price']);
        fputcsv($f, $lineData, $delimiter);
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>EA Out of Stock Report</title>
</head>

<body>
</body>
</html>
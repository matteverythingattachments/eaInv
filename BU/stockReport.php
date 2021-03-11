<?php
// Turn off all error reporting
error_reporting(E_ALL);
?>
<?php
session_start();
include('scripts/authent.php');
include('admin/scripts/access.php');

$sql = "SELECT 
CATEGORIES.CATEGORY,
EA_PRODS.Product,
EA_PRODS.Yellow,
EA_PRODS.Black,
EA_PRODS.Green,
EA_PRODS.Orange,
EA_PRODS.Primer,
EA_PRODS.Price
FROM
EA_PRODS
INNER JOIN CATEGORIES ON CATEGORIES.ID = EA_PRODS.Cat_ID
WHERE
((EA_PRODS.Yellow < 0) OR
(EA_PRODS.Black < 0) OR
(EA_PRODS.Green < 0) OR
(EA_PRODS.Orange < 0) OR
(EA_PRODS.Primer < 0))
ORDER BY
CATEGORIES.ID ASC";
$query = mysqli_query($conn,$sql);
$rowcount=mysqli_num_rows($query);
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
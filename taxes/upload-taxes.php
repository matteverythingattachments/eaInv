<?php
error_reporting(0);
$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');

// UPLOAD AND UPZIP FILE OF TAX CVS -----------------------------------------------------------------------------------------------------
if($_FILES["zip_file"]["name"]) {
	$filename = $_FILES["zip_file"]["name"];
	$source = $_FILES["zip_file"]["tmp_name"];
	$type = $_FILES["zip_file"]["type"];
	
	$name = explode(".", $filename);
	$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
			$okay = true;
			break;
		} 
	}
	
	$continue = strtolower($name[1]) == 'zip' ? true : false;
	if(!$continue) {
		$message = "The file you are trying to upload is not a .zip file. Please try again.";
	}else{
    //call function to clear out the zip folder
    deleteAll('/home2/everyto7/public_html/inventorycorimpco/taxes/zip');
  }

  
  
	$target_path = "/home2/everyto7/public_html/inventorycorimpco/taxes/zip".$filename;  // change this to the correct site path
	if(move_uploaded_file($source, $target_path)) {
		$zip = new ZipArchive();
		$x = $zip->open($target_path);
		if ($x === true) {
			$zip->extractTo("/home2/everyto7/public_html/inventorycorimpco/taxes/zip"); // change this to the correct site path
			$zip->close();
	
			unlink($target_path);
		}
		$message = "Your .zip file was uploaded and unpacked.";
	} else {	
		$message = "There was a problem with the upload. Please try again.";
	}
  
}
// ---------------------------------------------------------------------------------------------------------------------------------------




// FUNCTION TO REMOVE FILES AND FOLDERS --------------------------------------------------------------------------------------------------
function deleteAll($str) {
    //It it's a file.
    if (is_file($str)) {
        //Attempt to delete it.
        return unlink($str);
    }
    //If it's a directory.
    elseif (is_dir($str)) {
        //Get a list of the files in this directory.
        $scan = glob(rtrim($str,'/').'/*');
        //Loop through the list of files.
        foreach($scan as $index=>$path) {
            //Call our recursive function.
            deleteAll($path);
        }
        //Remove the directory itself.
        //return @rmdir($str);
    }
}
// ----------------------------------------------------------------------------------------------------------------------------------


// OPEN ZIP FILES AND GATHER DATA TO CREATE CVS FILE TO UPLOAD TO VOLUSION ----------------------------------------------------------
if($_POST['create_cvs']){
  $today = date('n/j/Y g:i:s A');
  // SET UP CVS FILE
  $cvs_file = array('taxid,taxstateshort,taxstatelong,taxcountry,lastmodified,lastmodby,tax1_title,tax2_title,tax3_title,tax1_percent,tax2_percent,tax3_percent,taxpostalcode,taxdefault,isvat,tax2_includeprevious,tax3_includeprevious,tax1_ignorenotaxrules,tax2_ignorenotaxrules,tax3_ignorenotaxrules');
 
 $x = 1; 
  
  $files = listFolders("/home2/everyto7/public_html/inventorycorimpco/taxes/zip");

    foreach($files as $file) {

        if (($handle = fopen($file, "r")) !== FALSE) {
            //echo "<b>Filename: " . basename($file) . "</b><br><br>";
            while (($data = fgetcsv($handle, 4096, ",")) !== FALSE) {
                //echo implode("\t", $data);
                if($data[0] != "State"){
                  $tax_rate_title = 'Tax ('.$data[4]*100 .'%)';
                  $tax_rate       = $data[4]*100;
              
                  $cvs_file[] = $x.','.$data[0].','.$states[$data[0]].',United States,'.$today.',1,'.$tax_rate_title.',,,'.$tax_rate.',,,'.$data[1].',,,0,0,,,';
                  $x++;
                  //echo '<br />';
                }
            }
            //echo "<br /><br />";
            fclose($handle);
        } else {
            echo "Could not open file: " . $file;
        }
    }
  

  // OUT PUT THE CVS FILE TO DOWNLOAD --------
    $namefile = "volusion_tax_upload_".date('m')."_".date('d')."_".date('Y').".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$namefile.'"');
    $fp = fopen('php://output', 'wb');
    foreach ( $cvs_file as $line ) {
      $val = explode(",", $line);
      fputcsv($fp, $val);
    }
    fclose($fp);
    
  
    //foreach($cvs_file as $line){
    //  echo $line . '<br />';
    //}
 
  exit;
  
}
// ----------------------------------------------------------------------------------------------------------------------------------


// FUNCTION TO GET LIST OF ALL CVS FILES INSIDE OF ZIP FOLDER -----------------------------------------------------------------------
function listFolders($dir) {
    $directory = new RecursiveDirectoryIterator($dir);
    $directory->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);

    $files = new RecursiveIteratorIterator(
        $directory,
        RecursiveIteratorIterator::SELF_FIRST
    );

    $list = [];
    foreach ($files as $file) {
        if (
            $file->isDir() == false &&
            $file->getExtension() === 'csv'
        ) {
            $list[] = $dir.'/'.$directory.'/'.$file->getBasename();
        }
    }

    return $list;
}
// ----------------------------------------------------------------------------------------------------------------------------------


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Upload and Create CVS file for Volusion</title>
  <style>
     <?php include('styles.css'); ?>
    p{ line-height: 20px; }
  </style>
</head>
<body>
  <br />
  <div style="max-width: 700px; margin: 10px auto;">
    <a href="tax-data.php" title="Avalara Tax Info" style="color:blue;font-weight:bold;font-size:18px;text-decoration:none;"><< Avalara Tax Info</a>
  </div>
  <h1 style="text-align:center;">
    Creating Tax CSV File For Volusion
  </h1>
<div class="steps" style="max-width: 700px; margin: 40px auto;">
  <strong>Step 1. Upload Zip file containing .csv files of taxes. (From Avalara)</strong><br />
    <div style="line-height:25px;font-weight:bold;background:#f1f1f1;border: solid 1px #f8f8f8;padding: 10px;text-align:left;color:red;margin:20px;"><u>Important: csv files must be as such</u><br />State,ZipCode,TaxRegionName,StateRate,EstimatedCombinedRate,...</div>
  
  
  <span style="color:red;"><?php if($message) echo "<p>$message</p>"; ?></span>
<form enctype="multipart/form-data" method="post">
<label>Choose a zip file to upload:<br /><input type="file" name="zip_file" /></label>
<br />
<input type="submit" name="submit" value="Upload" /><br /><br />
</form>
  </div>

<div class="steps" style="max-width: 700px; margin: 40px auto;">  
  <strong>Step 2. Create new csv file to upload to Volusion. (Save it to desktop)</strong><br /><br />
  <form method="post">
    <input type="hidden" name="create_cvs" value="1" />
    <input type="submit" name="submit" value="Create Tax File For Volusion" />
  </form><br /><br />
</div> 
  
<div class="steps" style="max-width: 700px; margin: 40px auto;">  
  <strong>Step 3. Upload newly saved file to Volusion.</strong><br /><br />
      <ol style="text-align:left;">
        <li><a href="https://www.everythingattachments.com/admin/db_import.asp" title="Volusion Import" target="_blank">Click Here to open Volusion</a></li>
        <li>In the "Import To" dropdown select "Tax"</li>
        <li>Click "Choose File" and select the newly created csv file on your desktop.</li>
        <li>In the "Overwrite Existing Data" options choose the second option to replace any existing data.</li>
        <li>Click the "Import" button and that's it!</li>
      </ol>
  <br /><br />
</div>   
  
</body>
</html>
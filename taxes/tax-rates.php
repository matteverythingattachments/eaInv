<?php

$states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');

// OPEN ZIP FILES AND GATHER DATA TO CREATE CVS FILE TO UPLOAD TO VOLUSION ----------------------------------------------------------
if($_GET['zip']){
  $zip = $_GET['zip'];
  $files = listFolders("/home2/everyto7/public_html/inventorycorimpco/taxes/zip");

    foreach($files as $file) {

        if (($handle = fopen($file, "r")) !== FALSE) {
            //echo "<b>Filename: " . basename($file) . "</b><br><br>";
            while (($data = fgetcsv($handle, 4096, ",")) !== FALSE) {
                //echo implode("\t", $data);
                if($data[0] != "State"){
                  $tax_rate_title = 'Tax ('.$data[4]*100 .'%)';
                  $tax_rate       = $data[4]*100;
              
                  $cvs_file[$data[1]]['state']  = $states[$data[0]];
                  $cvs_file[$data[1]]['region'] = ucwords(strtolower($data[2]));
                  $cvs_file[$data[1]]['tax_t']  = $tax_rate_title;
                  $cvs_file[$data[1]]['tax_r']  = $tax_rate;
                  
                }
            }
            //echo "<br /><br />";
            fclose($handle);
        } else {
            echo "Could not open file: " . $file;
        }
    }
  
  
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
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">

    <title>Local Tax Rate Look Up</title>
    <style>
      body{ background:#f1f1f1;}
      #maindiv{
        text-align:center;
      }
      #content{
        margin: 15px auto;
      }
      #zipform{
        padding: 15px;
      }
      #zipform input[type='text']{
        border: solid 1px #c0c0c0;
        padding: 4px 4px 2px;
        font-weight: bold;
        font-size: 1rem;
      }
      #zipform input[type='submit']{
        border: solid 1px #66a222;
        border-radius: 4px;
        padding: 4px 11px 2px;
        font-weight: bold;
        font-size: 1rem;
        color: #fff;
        margin-left: 10px;
        background: #8BC34A;
        cursor:pointer;
      }
      #zipform input[type='submit']:hover{
        background: #75a939;
        border: solid 1px #63902f;
      }
      #frighttable{ 
        text-align:left; 
        margin: 0 auto;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      }
      #frighttable, #frighttable tr, #frighttable th, #frighttable td{ 
        border-collapse: collapse; border: solid 1px #c0c0c0; 
      }
      #frighttable th, #frighttable td{
        padding: 7px 15px;
        font-weight: bold;
      }
      #frighttable tr:nth-child(2n+3){
        background: #eee;
      }
      #frighttable tr:nth-child(2n+2){
        background: #fff;
      }
      #frighttable tr:first-child{
        background: #666;
        color:#fff;
      }
      
    </style>
  </head>
  <body>
    <div id="maindiv"><br />
      <div id="content" style="text-align:center;">
       
    <h1 style="font-size:1.7rem;"><strong>Everything Attachments Tax Look Up</strong></h1>
        <br />
    <span style="font-size:1.5rem;color:#666;"><strong>Enter A Zip Code</strong></span>
  <form method="get" id="zipform">
    <input id="searchzipinput" type="text" name="zip" data-type="zipcode" value="<?php echo $zip; ?>" placeholder="Zip" style="width:80px;text-align:center;" />
    <input id="searchzip" type="submit" value="SEARCH" />
  </form>
    <br /><br />
<?php
  if(!empty($zip)):
     echo '<table id="frighttable"><tr><th>State</th><th>Region</th><th>Zip</th><th>Tax Rate</th></tr>'; 
        echo '<tr><td>'.$cvs_file[$zip]['state'].'</td>';
        echo '<td>'.$cvs_file[$zip]['region'].'</td>';
        echo '<td>'.$zip.'</td>';
        echo '<td align="center">'.$cvs_file[$zip]['tax_r'].'%</td>';
        echo '</tr>';
     echo '</table>'; 
  endif;
    
?>
<br /><br />
        
      </div>
    </div>
  </body>
</html>
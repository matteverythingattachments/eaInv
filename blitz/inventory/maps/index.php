<?php 

$api_key = "AIzaSyB_3ItNBKcRNssf9sjooU7lp4DaLs09plI";
//$address = $_POST['address'];
//$city    = $_POST['city'];
//$state   = $_POST['state'];
$zip     = $_POST['zip'];

/*
$us_states = array(
	'AL'=>'ALABAMA', 'AK'=>'ALASKA', 'AS'=>'AMERICAN SAMOA', 'AZ'=>'ARIZONA', 'AR'=>'ARKANSAS', 'CA'=>'CALIFORNIA', 'CO'=>'COLORADO', 'CT'=>'CONNECTICUT', 'DE'=>'DELAWARE',
	'DC'=>'DISTRICT OF COLUMBIA', 'FM'=>'FEDERATED STATES OF MICRONESIA', 'FL'=>'FLORIDA', 'GA'=>'GEORGIA', 'GU'=>'GUAM GU', 'HI'=>'HAWAII', 'ID'=>'IDAHO','IL'=>'ILLINOIS',
	'IN'=>'INDIANA', 'IA'=>'IOWA', 'KS'=>'KANSAS', 'KY'=>'KENTUCKY', 'LA'=>'LOUISIANA', 'ME'=>'MAINE', 'MH'=>'MARSHALL ISLANDS', 'MD'=>'MARYLAND', 'MA'=>'MASSACHUSETTS',
	'MI'=>'MICHIGAN', 'MN'=>'MINNESOTA', 'MS'=>'MISSISSIPPI', 'MO'=>'MISSOURI', 'MT'=>'MONTANA', 'NE'=>'NEBRASKA', 'NV'=>'NEVADA', 'NH'=>'NEW HAMPSHIRE', 'NJ'=>'NEW JERSEY',
	'NM'=>'NEW MEXICO', 'NY'=>'NEW YORK', 'NC'=>'NORTH CAROLINA', 'ND'=>'NORTH DAKOTA', 'MP'=>'NORTHERN MARIANA ISLANDS', 'OH'=>'OHIO', 'OK'=>'OKLAHOMA', 'OR'=>'OREGON',
	'PW'=>'PALAU', 'PA'=>'PENNSYLVANIA', 'PR'=>'PUERTO RICO', 'RI'=>'RHODE ISLAND', 'SC'=>'SOUTH CAROLINA', 'SD'=>'SOUTH DAKOTA', 'TN'=>'TENNESSEE', 'TX'=>'TEXAS', 'UT'=>'UTAH',
	'VT'=>'VERMONT', 'VI'=>'VIRGIN ISLANDS', 'VA'=>'VIRGINIA', 'WA'=>'WASHINGTON', 'WV'=>'WEST VIRGINIA', 'WI'=>'WISCONSIN', 'WY'=>'WYOMING', 
  'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST','AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)','AP'=>'ARMED FORCES PACIFIC'
);
*/

// use this url to get geo location
// https://maps.googleapis.com/maps/api/geocode/json?address=28168&key='.$api_key

//https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=Museum%20of%20Contemporary%20Art%20Australia&inputtype=textquery&fields=photos,formatted_address,name,rating,opening_hours,geometry&key=AIzaSyB_3ItNBKcRNssf9sjooU7lp4DaLs09plI



//https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=estes%20express&inputtype=textquery&fields=formatted_address,name,opening_hours&locationbias=circle:2000@35.5432351,-81.40591859999999&key=AIzaSyB_3ItNBKcRNssf9sjooU7lp4DaLs09plI


//https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=40.6655101,-73.89188969999998&destinations=40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626&key=AIzaSyB_3ItNBKcRNssf9sjooU7lp4DaLs09plI



//var sydney = {lat: 35.5432351, lng: -81.40591859999999};

function getGeoLocation($addr, $api_key)
{
    $cleanAddress = str_replace (" ", "+", $addr);
    $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$cleanAddress."&key=".$api_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $geoloc = json_decode(curl_exec($ch), true);
  switch ($geoloc['status']) {
    case 'ZERO_RESULTS':
      return 0;
      break;
    case 'OK':
      return $geoloc['results'][0]['geometry']['location'];
      break;
  }
}

// LOOK UP CLOSEST FREIGHT COMPANY BY ZIP CODE
function FreightLookUp($new_locs, $company, $api_key)
{
    $cleanCompany = str_replace (" ", "+", $company);
    $details_url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=$cleanCompany&inputtype=textquery&fields=formatted_address,name,opening_hours,geometry&locationbias=circle:2000@".$new_locs['lat'].",".$new_locs['lng']."&key=".$api_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $geoloc = json_decode(curl_exec($ch), true);
  switch ($geoloc['status']) {
    case 'ZERO_RESULTS':
      return 0;
      break;
    case 'OK':
      return $geoloc['candidates'];
      break;
  }
}

// LOOK UP CLOSEST FREIGHT COMPANY BY ZIP CODE
function MeasureDistance($new_locs, $get_locs, $api_key)
{
    $cleanCompany = str_replace (" ", "+", $company);
    $details_url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$new_locs['lat'].",".$new_locs['lng']."&destinations=".$get_locs."&key=".$api_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $geoloc = json_decode(curl_exec($ch), true);
  switch ($geoloc['status']) {
    case 'ZERO_RESULTS':
      return 0;
      break;
    case 'OK':
      //return $geoloc['rows'][0]['elements'];
      return $geoloc;
      break;
  }
}


if(!empty($zip)):
  $new_locs = getGeoLocation($zip, $api_key);
  $estes = FreightLookUp( $new_locs, "Estes Express", $api_key );
  $fedex = FreightLookUp( $new_locs, "Fedex Freight", $api_key );
  $yrc   = FreightLookUp( $new_locs, "Yellow Freight", $api_key );
  $sala  = FreightLookUp( $new_locs, "Saia Freight Lines", $api_key );
  $southeastern = FreightLookUp( $new_locs, "Southeastern Freight Lines", $api_key );
  
  $freight_locs = array_merge((array)$estes,(array)$fedex,(array)$yrc,(array)$sala,(array)$southeastern);
  //print_r($freight_locs); exit;  

  // GATHER GEOLOCS FROM LOCATIONS AND CREATE STRING ---------------------------------------------------
  if(!empty($freight_locs)):  
    foreach( $freight_locs as $places ):
      if(!empty($places['formatted_address'])):
        if(!empty($get_locs)):
          $get_locs .= "%7C";
        endif;
          $get_locs .= $places['geometry']['location']['lat'].'%2C'.$places['geometry']['location']['lng'];
      endif;
    endforeach;
  endif;
        
  // GET DISTANCES FROM ZIP -------------------------------------
  $get_distances = MeasureDistance( $new_locs, $get_locs, $api_key );

  // LOOP THROUGH DISTANCES TO MATCH UP ADDRESSES
  foreach($get_distances['destination_addresses'] as $key => $listing):
    foreach( $freight_locs as $fkey => $places ):
      if($places['formatted_address'] == $listing):
          $freight_locs[$fkey]['miles'] = $get_distances['rows'][0]['elements'][$key]['distance']['text'];
          $freight_locs[$fkey]['time'] = $get_distances['rows'][0]['elements'][$key]['duration']['text'];
      endif;
    endforeach;
  endforeach;

endif;


?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Freight Terminal Locations</title>
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
    <div id="maindiv">
      <div id="content">
        
      <br />
    <h1 style="font-size:1.7rem;"><strong>Everything Attachments Terminal Locator</strong></h1>
        <br />
    <span style="font-size:1.5rem;color:#666;"><strong>Enter A Zip Code</strong></span>
  <form method="post" id="zipform">
    <?php /*
    <input type="text" name="address" value="<?php echo $address; ?>" placeholder="Address" style="width:200px;" /><br />
    <input type="text" name="city" value="<?php echo $city; ?>" placeholder="City" />,
    <select name="state">
       <?php foreach($us_states as $abv => $state):
            echo '<option value="'.$abv.'" title="'.$state.'">'.$abv.'</option>';
          endforeach;
        ?>
    </select>
    */ ?>
    <input type="text" name="zip" value="<?php echo $zip; ?>" placeholder="Zip" style="width:80px;" />
    <input type="submit" value="SEARCH" />
  </form>
    <br /><br />
<?php
  if(!empty($zip)):
     echo '<table id="frighttable"><tr><th>Freight Company</th><th>Address</th><th>City</th><th>State Zip</th><th>Distance</th><th>Duration</th></tr>'; 
  endif;
    
  if(!empty($freight_locs)):  
    //print_r($estes); exit;
    usort($freight_locs, function($a, $b) { return $a['miles'] - $b['miles']; });
    
    foreach( $freight_locs as $key => $places ):
      if(!empty($places['formatted_address'])):
    
        $thisaddress = (explode(", ",$places['formatted_address']));
        echo '<tr><td>'.$places['name'].'</td>';
        echo '<td>'.$thisaddress[0].'</td>';
        echo '<td>'.$thisaddress[1].'</td>';
        echo '<td>'.$thisaddress[2].'</td>';
        echo '<td>'.$places['miles'].'</td>';
        echo '<td>'.$places['time'].'</td>';
        echo '</tr>';
      endif;
    endforeach;
  endif;

    
  if(!empty($zip)):
     echo '</table>'; 
  endif;
    
?>
        <div style="display:none;">
          <?php print_r($sala); ?>
        </div>
        
      </div>
    </div>
  </body>
</html>
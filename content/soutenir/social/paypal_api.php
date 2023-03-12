<?php
$API_TRANS = "https://api.sandbox.paypal.com/v1/reporting/transactions";
$token = "EGaQ6hla5jbKDv_wbqSAe6V5PyvtYgEvph7jfGXPUO7WEyFh7sqPM3ZQIUT3wAJ3cgKUuhnc9rge8Wrb";
//setup the request, you can also use CURLOPT_URL
$startdate='2020-11-20T00:00:00';
$now = date("Y-m-dTH:i:s");
$enddate = $now;

$ch = curl_init($API_TRANS.'?start_date='. $startdate. '&end_date='.$enddate.'&fields=all&page_size=100&page=1');

// Returns the data/output as a string instead of raw data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Set your auth headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Authorization: Bearer ' . $token
   ));

// get stringified data/output. See CURLOPT_RETURNTRANSFER
$data = curl_exec($ch);

// get info about the request
$info = curl_getinfo($ch);
// close curl resource to free up system resources

printf ("%s", $info);

foreach ($info as $key=>$item){
    echo "$key => $item <br>";
}


foreach ($data as $key=>$item){
  echo "$key => $item <br>";
}


curl_close($ch);
?>

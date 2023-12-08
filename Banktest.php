<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://127.0.0.1:8000/api/user/getbankqr',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "trip_id":"1",
    "seat_id":[
        1,
        2,
        4,
        5,
        6
    ]
}',
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer 62|Dj1U8e3ITcoiefyrM85DC50emjehKA6BvgkivwhK92a718ec'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response);
echo "<pre>";
var_dump($response->data->qr,1);

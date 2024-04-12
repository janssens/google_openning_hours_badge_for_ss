<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/env.php';

if (!$google_place_id){
    echo json_encode(['success' => false]);
    die();
}

$language = 'en';
if (isset($argv[1])){
    $language = $argv[1];
}

$url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id='.$google_place_id.'&language='.$language.'&fields=opening_hours&key='.$api_key;


$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
curl_setopt($ch, CURLOPT_URL, $url);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response);

$format = 'json';
if (isset($argv[2]) && $argv[2] == 'html'){
    $format = 'html';
}

if ($format === 'json'){// json
    echo json_encode($data->result->opening_hours);
}else{//html
    echo '<html><head><meta charset="UTF-8"></head><body>';
    echo '<ul>';
    foreach ($data->result->opening_hours->weekday_text as $wt){
        echo '<li>'.$wt.'</li>';
    }
    echo '</ul>';
    echo '</body></html>';
}
die();
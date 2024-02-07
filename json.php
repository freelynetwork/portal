<?php

if (isset($_COOKIE['token'])) {
$token = $_COOKIE['token'];
  
// POSTデータの準備
$postData = [
  'i' => $token,
];

// POSTリクエストを送信
$apiUrl = "https://vocaloid.social/i";
$options = [
    'http' => [
        'header' => "Content-type: application/json",
        'method' => 'POST',
        'content' => json_encode($postData)
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);

header('Content-Type: application/json');

if ($response === FALSE) {
    echo json_encode(["error" => "Failed to fetch data from API"]);
} else {
    echo $response;
}
?>
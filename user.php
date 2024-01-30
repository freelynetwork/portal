<?php
// Load environment variables from config.php
include 'config.php';

// Initialize username variable
$username = '';

// Check if token exists in cookie
if (isset($_COOKIE['token'])) {
    $mkact = $_COOKIE['token'];

    // POST request to retrieve username
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, 'https://vocaloid.social/api/i');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array("i" => $mkact)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);

    $res = curl_exec($curl);

    // Check for errors and save to error.txt if any
    if ($res === false) {
        $error = curl_error($curl);
        file_put_contents('error.txt', $error);
        $username = 'Error retrieving username.';
    } else {
        // Decode response
        $arr = json_decode($res, true);

        // Check if username is retrieved successfully
        if (isset($arr['username'])) {
            $username = $arr['username'];
        } else {
            $username = 'Error retrieving username.'; // Default error message
        }
    }

    curl_close($curl);
} else {
    // Token does not exist in cookie
    $username = 'not login'; // Set username to 'not login'
}

// Save username for later use
$_SESSION['username'] = $username;

?>

<?php

require 'init.php';
require 'ssi_top.php'; 


if (!isset($_SESSION['user_id'])) {
    
    header("Location: index.php");
    exit;
}

// user is authenticated id
$userId = $_SESSION['user_id']; 


function isValidApiKey($apiKey, $userId) {
    $apiObj = new syed_final_api();
    return $apiObj->verifyToken($apiKey, $userId);
}


$apiToken = md5(uniqid(rand(), true));


setcookie('api_token', $apiToken, time() + 3600, '/');


$submittedApiKey = isset($_GET['token']) ? $_GET['token'] : '';


if (!isValidApiKey($submittedApiKey, $userId)) {
    $response = ['error' => 'Invalid API key'];
    echo json_encode($response);
    exit;
}

// Store the token syed_final_api class
$apiLogObj = new syed_final_api();
$apiLogObj->logApiHit($userId, $_SERVER['REMOTE_ADDR'], '1'); // Replace '1' with the actual query value
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Homepage</title>
    <style>
        #scenes {
            display: none;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
       
    </script>
</head>
<body>
    <a href="edit_profile.php">Edit Profile</a></li>
    <a href="shakespeare_api.php">API</a></li>
    <!-- <a href="api_summary.php">API Summary</a></li> -->
    

    <div>
        <strong>Generated API Token:</strong>
        <pre><?= $apiToken ?></pre>
    </div>


    <?php require 'ssi_bottom.php'; ?>
</body>
</html>

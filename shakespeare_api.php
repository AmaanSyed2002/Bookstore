<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'csci488_fall23');
define('DB_PASSWORD', 'DbFun2023');
define('DB_DATABASE', 'csci488_fall23');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check database connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit;
}

// Assuming the token is submitted as a GET parameter
$submittedToken = isset($_GET['token']) ? $mysqli->real_escape_string($_GET['token']) : null;

// Validate the token using the API token stored in syed_accounts table
if (!$submittedToken || !isValidToken($submittedToken)) {
    echo json_encode(["error" => "Invalid token"]);
    exit;
}


$work = isset($_GET['work']) ? $mysqli->real_escape_string($_GET['work']) : null;
$scene = isset($_GET['scene']) ? $mysqli->real_escape_string($_GET['scene']) : null;
$act = isset($_GET['act']) ? $mysqli->real_escape_string($_GET['act']) : null;

// Insert API log entry
insertApiLog($submittedToken, $work, $scene, $act);

if ($act !== null && $scene !== null && $work !== null) {
    $sql = "SELECT * FROM shakespeare_paragraphs 
            INNER JOIN shakespeare_chapters 
            ON chap_work_id = par_work_id 
            AND chap_act = par_act 
            AND chap_scene = par_scene 
            WHERE par_work_id = '$work' 
            AND par_act = '$act' 
            AND par_scene = '$scene' 
            ORDER BY `par_number` ASC";
} elseif ($work !== null) {
    $sql = "SELECT * FROM `shakespeare_chapters` 
            WHERE `chap_work_id` = '$work'";
} else {
    $sql = "SELECT * FROM `shakespeare_works`";
}

$query = $mysqli->query($sql);


if ($query === false) {
    echo "Query execution error: " . $mysqli->error;
    exit;
}

$json_outt = [];
$scene_location = null; 


while ($data = $query->fetch_array(MYSQLI_ASSOC)) {
    if ($act !== null) {
        $scene_location = $data['chap_description'];
        $par_number = $data['par_number'];
        $par_char_id = $data['par_char_id'];
        $par_text = $data['par_text'];
        $json_outt[] = response_para($par_number, $par_char_id, $par_text, $submittedToken);
    } elseif ($work !== null) {
        $scene_id = $data['chap_id'];
        $scene_work_id = $data['chap_work_id'];
        $scene_act = $data['chap_act'];
        $scene_scene = $data['chap_scene'];
        $scene_location = $data['chap_description'];
        $json_outt[] = response_scene($scene_id, $scene_work_id, $scene_act, $scene_scene, $scene_location, $submittedToken);
    } else {
        $work_id = $data['work_id'];
        $work_title = $data['work_title'];
        $work_long_title = $data['work_long_title'];
        $work_year = $data['work_year'];
        $work_genre = $data['work_genre'];
        $json_outt[] = response($work_id, $work_title, $work_long_title, $work_year, $work_genre, $submittedToken);
    }
}

$mysqli->close();


if ($act !== null) {
    $para_arr = ["scene_location" => $scene_location, "paragraphs" => $json_outt];
    echo json_encode($para_arr);
} else {
    echo json_encode($json_outt);
}


function isValidToken($token) {
    global $mysqli;

    $sql = "SELECT * FROM syed_accounts WHERE api_key = '$token'";
    $query = $mysqli->query($sql);

    return $query->num_rows > 0;
}


function insertApiLog($token, $work, $scene, $act) {
    global $mysqli;

   
    $clientIp = $_SERVER['REMOTE_ADDR'];

    
    $queryString = http_build_query(['token' => $token, 'work' => $work, 'scene' => $scene, 'act' => $act]);

    
    $timestamp = date('Y-m-d H:i:s');

    
    $sql = "INSERT INTO syed_final_apis (api_logs_access_key, api_logs_timestamp, api_logs_ip, api_logs_query) 
            VALUES ('$token', '$timestamp', '$clientIp', '$queryString')";

    
    if (!$mysqli->query($sql)) {
        error_log("Error inserting API log: " . $mysqli->error);
        echo "Error inserting API log: " . $mysqli->error;
    }
}

// Functions to format
function response_para($par_number, $par_char_id, $par_text, $token) {
    return [
        "par_number" => $par_number,
        "par_char_id" => $par_char_id,
        "par_text" => $par_text,
        "api_logs_access_key" => $token
    ];
}

function response_scene($scene_id, $scene_work_id, $scene_act, $scene_scene, $scene_location, $token) {
    return [
        "scene_id" => $scene_id,
        "scene_work_id" => $scene_work_id,
        "scene_act" => $scene_act,
        "scene_scene" => $scene_scene,
        "scene_location" => $scene_location,
        "api_logs_access_key" => $token
    ];
}

function response($work_id, $work_title, $work_long_title, $work_year, $work_genre, $token) {
    return [
        "work_id" => $work_id,
        "work_title" => $work_title,
        "work_long_title" => $work_long_title,
        "work_year" => $work_year,
        "work_genre" => $work_genre,
        "api_logs_access_key" => $token
    ];
}
?>

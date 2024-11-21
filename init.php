<?php
// INIT file loads resources needed by all PHP pages in a Web Application.

/******************************************************************************************
Database Connection
******************************************************************************************/
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'csci488_fall23');
define('DB_PASSWORD', 'DbFun2023');
define('DB_DATABASE', 'csci488_fall23');

// Initialize $mysqli as a global variable
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}

define('ACCOUNTS_TABLE', 'syed_accounts');
define('API_LOGS_TABLE', 'syed_final_api'); 



require_once 'class_data_operations.php'; // Parent Class for ORM/AR functionality
require_once 'class_lib.php';            // Wrapper for useful utility functions


require_once 'class_accounts_table.php';


require_once 'api_tokens_table.php';


session_start();

// Consolidate $_GET and $_POST super globals
$get_post = array_merge($_GET, $_POST);


?>

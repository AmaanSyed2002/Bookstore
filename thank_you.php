<?php
require 'init.php'; 
require 'ssi_top.php';
require_once 'class_accounts_table.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    
    $ppl = new people();

    // Attempt to load the record using the primary key
    $ppl->load($id);

    // Check if the record was successfully loaded
    if ($ppl->get_id_value() !== '') {
        echo "<h2>Thank You!</h2>";
        echo "<p>Your profile has been loaded successfully. High Five!!!! </p>";
        echo "<p>Name: " . htmlspecialchars($ppl->values['name']) . "</p>";
        echo "<p>Email: " . htmlspecialchars($ppl->values['email']) . "</p>";
        echo "<p>Time: " . htmlspecialchars($ppl->values['time']) . "</p>";
        echo "<p>IP: " . htmlspecialchars($ppl->values['ip']) . "</p>";

        // Create a logon record for the user
        create_logon_record($ppl->get_id_value(), $_SERVER['REMOTE_ADDR']);
    } else {
        echo "<h2>Error</h2>";
        echo "<p>Failed to load the profile with ID: {$id}</p>";
    }
} else {
    // If the required parameters are not provided, display a generic thank you message
    echo "<h2>Thank You!</h2>";
    echo "<p>Amazing! Your profile has been saved successfully. </p>";
}

require 'ssi_bottom.php';
?>

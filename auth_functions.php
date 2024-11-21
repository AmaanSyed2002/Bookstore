<?php



function generate_logon_token($email) {
    // Use md5 hash algorithm to generate a token (you can customize this method)
    return md5($email . time());
}


function set_remember_me_cookie($email) {
    // Set a persistent cookie with a one-year expiration
    setcookie('remembered_user', $email, time() + 365 * 24 * 3600);
}

// Function to create a logon record
function create_logon_record($profile_id, $ip_address) {
   
    require_once 'logon_records_table.php';

    // Create a logon_records object
    $logon = new logon_records();

    // Set logon record values
    $logon->values['logon_token'] = generate_logon_token($profile_id);
    $logon->values['profile_id'] = $profile_id;
    $logon->values['created_at'] = date('Y-m-d H:i:s');
    $logon->values['last_modified_at'] = $logon->values['created_at'];
    $logon->values['ip_address'] = $ip_address;

    // Save the logon record
    $logon->save();
}
?>

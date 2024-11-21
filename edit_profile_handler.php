<?php
require_once 'init.php';
require_once 'edit_profile_table.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new edit_profile_table();
    $user->load($_SESSION['user_id']);

   
    $newEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $newPassword = $_POST['password'];

    
    $user->values['email'] = $newEmail;
    $user->values['password'] = password_hash($newPassword, PASSWORD_DEFAULT);

    
    if ($user->save()) {
       
        header("Location: confirmation_page.php");
        exit;
    } else {
        
        header("Location: error_page.php");
        exit;
    }
} else {
    
    header("Location: edit_profile.php");
    exit;
}
?>

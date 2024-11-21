<?php
require 'init.php'; 
require_once 'auth_functions.php';
require_once 'logon_records_table.php';

// Hours Took 31(kind of embarrising but still I tried my best)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $rememberMe = isset($_POST['remember_me']);

    
    $user = new people();
    $user->load($email, 'email');

    
    if (!empty($user->values['email'])) {
       
        if (password_verify($password, $user->values['password'])) {
           

            // Create a logon record
            $logon = new logon_records();

            
            $loginToken = md5(time() . $email);
            $logon->set_id_value($loginToken);

            $logon->values['logins_user_id'] = $user->get_id_value();

            
            $existingLogon = new logon_records();
            $existingLogon->load($user->get_id_value(), 'logins_user_id');

            if (!$existingLogon->get_id_value()) {
                
                $logon->values['logins_created'] = date('Y-m-d H:i:s');
                $logon->values['logins_modified'] = $logon->values['logins_created'];
                $logon->values['logins_ip'] = $_SERVER['REMOTE_ADDR'];
                $logon->save();
            }

            
            $_SESSION['user_id'] = $user->get_id_value();

            // If "Remember Me" is checked, set a persistent cookie
            if ($rememberMe) {
                set_remember_me_cookie($email);
            }

            // Redirect to the thank you page
            header("Location: api_homepage.php");
            exit;
        } else {
            
            $loginError = "Invalid email/password combination. Please try again.";
        }
        
    } else {
        // User does not exist
        $loginError = "Invalid email/password combination. Please try again.";
    }
}

// If "Remember Me" cookie is set, populate the email field
if (isset($_COOKIE['remembered_user'])) {
    $rememberedUser = $_COOKIE['remembered_user'];
} else {
    $rememberedUser = '';
}


require 'ssi_top.php';
?>

<!-- HTML login form code -->
<form name="loginForm" action="index.php" method="POST">
    Email/Username: <input type="text" name="email" value="<?= htmlspecialchars($rememberedUser) ?>"><br>
    Password: <input type="password" name="password"><br>
    <input type="checkbox" name="remember_me" <?= isset($_COOKIE['remembered_user']) ? 'checked' : '' ?>> Remember Email<br>
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="Amaanpage_form.php">Sign up here</a></p>

<?php if (isset($loginError)) { ?>
    <div style="color: red;"><?= $loginError ?></div>
<?php } ?>

<?php

require 'ssi_bottom.php';
?>

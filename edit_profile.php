<?php
require_once 'init.php';
require_once 'edit_profile_table.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user = new edit_profile_table();
$user->load($_SESSION['user_id']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = isset($_POST['email']) ? trim($_POST['email']) : '';
    $newPassword = isset($_POST['password']) ? $_POST['password'] : '';

    
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
    } else {
       
        if ($newEmail !== $user->values['email']) {
            
            $newUser = new edit_profile_table(); // Use the correct class
            $newUser->load($newEmail, 'email');

            
            if (!empty($newUser->values['email'])) {
                $emailError = "Email is already in use. Please choose a different one.";
            } else {
                // Update the email since it's not a duplicate
                $user->values['email'] = $newEmail;
            }
        }
    }

    // Update the password
    if (!empty($newPassword)) {
        $user->values['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    
    $user->save();

   
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
  
    <script>
        
        function checkPasswordStrength(password) {
            
            if (password.length < 8) {
                return 'Weak';
            } else if (password.length < 12) {
                return 'Medium';
            } else {
                return 'Strong';
            }
        }

       
        function updatePasswordStrength() {
            var passwordInput = document.getElementById('password');
            var strengthMessage = document.getElementById('password-strength');

            var password = passwordInput.value;
            var strength = checkPasswordStrength(password);

            strengthMessage.innerHTML = 'Password Strength: ' + strength;
        }
    </script>
    <?php require 'ssi_top.php'; ?>
</head>
<body>
    <h2>Edit Profile</h2>
    <?php if (isset($emailError)) { ?>
        <div style="color: red;"><?= $emailError ?></div>
    <?php } ?>
    <form action="edit_profile.php" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($user->values['email']) ?>" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" oninput="updatePasswordStrength()" required>
        <!-- Display password strength -->
        <div id="password-strength"></div>
        <br>
        <button type="submit">Update Profile</button>
    </form>
</body>
</html>

<?php require 'ssi_bottom.php'; ?>

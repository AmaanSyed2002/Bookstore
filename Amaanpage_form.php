<?php
require 'init.php'; // Include the initialization file

// Define isValidEmail function
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

$task = isset($_POST['task']) ? $_POST['task'] : ''; // Check if 'task' index is set

// In SQL filter it to have the number of rows to be over 50. I thought it wasn't going in the DB, but I just made too many accounts.
switch ($task) {
    case 'save':
        // Save the form data then transfer to the listing page
        $ppl = new people();

        // Load the incoming form data into an ORM object
        $ppl->load_from_form_submit();

        if (!trim($ppl->values['name']) || !trim($ppl->values['email']) || !trim($ppl->values['password'])) {
            $message = "You must fill out all fields";
            break; 
        }

        if (strlen(trim($ppl->values['name'])) < 2) {
            $message = "Your name is too short, make it longer";
            break; 
        }

        if (strlen(trim($ppl->values['password'])) < 5) {
            $message = "Your password is too short, make it longer";
            break; // goes back to form
        }

        // Check if the email is already in use
        $existingPpl = new people();
        $existingPpl->load(trim($ppl->values['email']), 'email');

        if (!empty($existingPpl->values['email'])) {
            $message = "Email is already in use. Please choose a different one.";
            break;
        }

        // Check if the passwords match
        if (trim($ppl->values['password']) !== trim($_POST["same_password"])) {
            $message = "Passwords do not match";
            break;
        }

        // Use password_hash to generate a secure hash
        $hashedPassword = password_hash(trim($ppl->values['password']), PASSWORD_DEFAULT);

        // Set the hashed password to the ORM object
        $ppl->values['password'] = $hashedPassword;

        // Generate a random API key using md5(email + timestamp + random string)
        $randomString = bin2hex(random_bytes(16));
        $api_key = md5($ppl->values['email'] . time() . $randomString);

        // Set the API key to the ORM object
        $ppl->values['api_key'] = $api_key;

        // Set timestamp and IP address
        $ppl->values['time'] = date('Y-m-d H:i:s');
        $ppl->values['ip'] = $_SERVER['REMOTE_ADDR'];

        // Save the contents of the object to the database.
        $ppl->save();

        // Redirect to the thank you page with the 'id' parameter in the query string
        header("Location: index.php");
        exit;
}

require 'ssi_top.php';
?>

<!--  HTML form code  -->

<?php if (isset($message) && $message) { ?>
    <div style="color:red;"><?= $message ?></div><br>
<?php } ?>

<form name="form1" action="Amaanpage_form.php" method="POST">
    <input type="hidden" name="task" value="save">
    <input type="hidden" name="id" value="">

    Name: <input type="text" name="name" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
    <br>
    Email: <input type="email" name="email" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
    <span id="email-validation-message" style="color: red;"></span>
    <br><br>
    Password: <input type="password" name="password" id="password" oninput="checkPasswordStrength(this.value)">
    <br>
    <div id="power-point" style="width: 0%; height: 10px; background-color: #ccc;"></div>
    <br>
    <div id="password-strength-label"></div>
    <br>
    Confirm Password: <input type="password" name="same_password">
    <br><br>
    <input type="hidden" name="time" value="<?= isset($_POST['time']) ? $_POST['time'] : '' ?>">
    <input type="hidden" name="ip" value="<?= isset($_POST['ip']) ? $_POST['ip'] : '' ?>">

    <button type="submit" id="submit-button">Submit</button>
</form>

<!-- TBH this is kind of neat -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
<script>
    function checkPasswordStrength(password) {
        var result = zxcvbn(password); // uses this libary 
        var meter = document.getElementById("power-point");
        meter.style.width = (result.score + 1) * 20 + "%"; //convert the score (ranging from 0 to 4) into a percentage value
        meter.style.backgroundColor = getColor(result.score);

        // Update the password strength label
        document.getElementById("password-strength-label").innerHTML = "Password Strength: " + getStrengthLabel(result.score);
    }

    function getColor(score) {
        // Choose colors based on the password strength score
        switch (score) {
            case 0:
                return "#FF0000"; // Red (Very weak)
            case 1:
                return "#FFFF00"; // Yellow (Weak)
            case 2:
                return "#0000FF"; // Blue (Fair)
            case 3:
                return "#00FF00"; // Green (Strong)
            case 4:
                return "#097969"; // Green (Strong)
        }
    }

    function getStrengthLabel(score) {
        // Get the password strength label based on the score
        switch (score) {
            case 0:
                return "Very Weak";
            case 1:
                return "Weak";
            case 2:
                return "Fair";
            case 3:
                return "Good";
            case 4:
                return "Strong";
        }
    }

    // Email validation using JavaScript
    var emailInput = document.getElementById('email');
    var emailValidationMessage = document.getElementById('email-validation-message');

    emailInput.addEventListener('input', function () {
        var email = emailInput.value.trim();
        if (!isValidEmail(email)) {
            emailValidationMessage.textContent = "Invalid email address format";
            document.getElementById('submit-button').disabled = true;
        } else {
            emailValidationMessage.textContent = "";
            document.getElementById('submit-button').disabled = false;
        }
    });
    // Stack overflow came in clutch, lowkey assingmetn in indeed
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // looks like something out of Matrix movie, nah just kidding lol
        return emailRegex.test(email);
    }
</script>

<?php
require 'ssi_bottom.php';
?>

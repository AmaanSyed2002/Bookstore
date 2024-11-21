<?php
require 'init.php'; // database connection, etc
require_once 'class_accounts_table.php'; // Assuming your class file is named class_people.php

// Create an instance of the people class
$peopleTable = new people();

// Fetch all records from the 'people' table
$people_data = $peopleTable->load_table();


require 'ssi_top.php';
?>

<br>
<a href="Amaanpage_form.php">Go To New Person Form</a>
<br><br>

<?php if ($people_data->num_rows == 0) { ?>
    <b>No records were found in the database.</b>
<?php } else { ?>
    <b>Listing of Database Records:</b>

    <table width="" border="1" cellspacing="0" cellpadding="5">
        <tr valign="top">
            <td>Name</td>
            <td>Email</td>
            <td>Password</td>
            <td>Time</td>
            <td>IP</td>
        </tr>
        <?php while ($person = $people_data->fetch_assoc()) { ?>
            <tr valign="top">
                <td><?= $person['name'] ?></td>
                <td><?= $person['email'] ?></td>
                <td><?= $person['password'] ?></td>
                <td><?= $person['time'] ?></td>
                <td><?= $person['ip'] ?></td>
            </tr>
        <?php } // end while ?>
    </table>

<?php } // end else ?>

<?php
require 'ssi_bottom.php';
?>

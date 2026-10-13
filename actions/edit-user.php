<?php
include "../classes/User.php";

// create an obj
$user = new User();

// $_POST holds the first_name, last_name, username, and id of the user
// $_POST holds the profile pricture of the user
$user->update($_POST, $_FILES);

?>
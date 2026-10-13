<?php

include_once "../classes/User.php";

// create an obj
$user = new User();

// call the method
// $_POST holds all the data from the form in view/register.php
// $_POST = first_name = "John", last_name = "stephen", username = "john", password = 1234567
// al, san, al, 12345678
$user->store($_POST);
?>
<?php
// ask my teather!  Don't forget!!
include_once "../classes/User.php";

// create an obj
$user = new User();

// call the method
$user->login($_POST);

?>
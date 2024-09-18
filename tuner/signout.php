<?php
//start session on this page 
session_start();

// clear session variables 
$_SESSION = [];

//destroy session 
session_destroy();

// redirect to home page after sign out 
header("Location: index.php");
exit();
?>
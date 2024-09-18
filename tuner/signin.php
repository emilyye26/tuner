<?php
session_start();

include "db_auth.php";

// connect to database 
$conn = new mysqli($servername, $username, $password, $dbname);

// connect and verify connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get username and password of user from form submission
$USER = $_POST['Username'];
$PASSW = $_POST['Password'];

// check if the user is already in the database
$sql_check = "SELECT * FROM Users WHERE `username` = '$USER' AND `password` = '$PASSW'";
$result_check = $conn->query($sql_check);

// if details do not exist, something must be wrong 
if ($result_check->num_rows == 0) {
    echo '<script>alert("Username or password incorrect. Please try again.");</script>';
    echo '<script>window.location.href = "signin.html";</script>';
} else {
// get user data from database
    $user_data = $result_check->fetch_assoc();

// use info to start user session 
    $_SESSION["user_id"] = $user_data['user_id'];
    $_SESSION["username"] = $user_data['username'];
    // redirect to home after sign in
    header("Location: index.php");
    exit();
}

$conn->close();
?>

<?php
session_start();

// database connection details
$servername = "localhost";
$username = "uoo87t6cg9q6a";
$password = "Webprogramming!";
$dbname = "dbkkfhwoh96jgp";

// create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get form data
$UN = $_POST['Username'];
$PASS = $_POST['Password'];

// check if the username exists in the database
$sql_check = "SELECT * FROM Users WHERE `username` = '$UN'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    echo "Sorry, this username has already been taken. Please try a different username.";
} else {

    // username doesn't exist already, insert new user into the database
    $sql = "INSERT INTO  Users (username, password)
            VALUES ('$UN', '$PASS')";


    if ($conn->query($sql) === TRUE) {
        // get newly created user data from database
        $sql_check = "SELECT * FROM Users WHERE `username` = '$UN' AND `password` = '$PASS'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {

            $user_data = $result_check->fetch_assoc();
        }

        // set session variables to sign the user in
        $_SESSION["user_id"] = $user_data['user_id'];
        $_SESSION["username"] = $user_data['username'];

        // redirect to home page
        header("Location: index.php");

        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

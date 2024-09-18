<?php
session_start();

// database connection
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
$rating = $_POST['rating'];
$comments = $_POST['comments'];
$song_name = $_POST['song_name'];
$artist_name = $_POST['artist_name'];

$user_id = $_SESSION['user_id']; 

// does appopriate escape sequences to avoid SQL insertion errors
$artist_name = $conn->real_escape_string($artist_name);
$song_name = $conn->real_escape_string($song_name);
$comments =  $conn->real_escape_string($comments);

// Check if the song already exists in the database
$sql_check = "SELECT * FROM Songs WHERE song_name = '$song_name' AND artist = '$artist_name' AND user_id = '$user_id'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    // song already exists -- prevent form submission
    echo "<script>alert('You have already reviewed this song.');</script>";
    echo "<script>window.history.back();</script>";
} else {
    //get user id
    $user_id = $_SESSION['user_id']; 

    // song doesn't exist, insert comment, rating and userid into the database
    $sql = "INSERT INTO Songs (artist, song_name, song_info, rating, user_id)
            VALUES ('$artist_name', '$song_name', '$comments', '$rating', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        // get ratings for songs that match this song name and artist name
        $all_ratings = "SELECT rating FROM Songs WHERE song_name = '$song_name' AND artist = '$artist_name'";
        $result = $conn->query($all_ratings);
        
        // calculate new average rating and update average rating across all entries
        $total = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $total += $row['rating'];
            }
        }
            
        $average = $total / $result->num_rows;

        $insert_avg = "UPDATE Songs
                       SET average_rating = '$average'
                       WHERE song_name = '$song_name' AND artist = '$artist_name'";
        
        if ($conn->query($insert_avg) !== TRUE) {
            echo "Error updating average rating: " . $conn->error;
        }

        // Redirect to another page after successful submission
        echo '<script>window.location.assign("https://benjaminl.sgedu.site/tuner/library.php");</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


exit();
$conn->close();
?>

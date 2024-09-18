<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="icon" href="icon.png" type="image/icon type">
   <title>Library</title>
   <style>
    
    h2 {
        text-align: center;
    }
       body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        line-height: 1.6;
        background-color: #f8f8f8;
    }

    header {
        background-color: #333;
        color: #fff;
        padding: 30px 0;
        text-align: center;
        position: relative;
    }

    .logo-container {
        display: inline-block;
        vertical-align: middle;
        margin-right: 20px;
        float: left;
        margin-right: auto;
        position: absolute;
        left: 50px;
        top: 50%;
        transform: translateY(-50%);
    }

    .logo-container img {
        width: auto;
        height: 70px;
        vertical-align: middle;
    }

    .user-links {
        display: inline-block;
        vertical-align: middle;
        float: right;
        font-size: 17px;
        margin-left: auto;
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
    }

    .user-links a {
        color: #fff;
        text-decoration: none;
        margin-left: 10px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #5e88d6;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .user-links a:hover {
        background-color: #0056b3;
    }

    nav {
        background-color: #808080;
        padding: 10px;
        text-align: center;
    }

    nav form {
        display: inline-block;
    }

    nav input[type="text"] {
        padding: 8px;
        border-radius: 5px;
        border: none;
        background-color: #fff;
        margin-right: 5px;
    }

    nav button {
        padding: 8px 15px;
        border: none;
        background-color: #5e88d6;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }

    nav button:hover {
        background-color: #0056b3;
    }

    nav a {
        color: #fff;
        text-decoration: none;
        margin-right: 20px;
    }

    label {
        margin-right: 5px;
        color: #fff;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
    }

    .featured-songs {
        background-color: #fff;
        padding: 20px;
        margin-top: 20px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 20px;
    }

    .albums-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .albums-container a {
        color: #333;
        text-decoration: none;
        vertical-align: top;
        white-space: normal;
        margin-right: 10px;
        width: 200px;
        text-align: center;
    }

    .albums-container img {
        width: 100%;
        max-width: 200px;
        height: auto;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .song-info {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

    .song-info a {
        color: #333;
        text-decoration: underline;
    }

    .song-info a:hover {
        background-color: #fffd8d;
    }

    .song-details {
        margin-bottom: 10px;
    }

    .song-rating {
        font-weight: bold;
    }

    .song-comments {
        color: #777;
    }

    footer {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 10px;
        margin-top: 20px;
    }
    @media screen and (max-width: 768px) {
    .logo-container {
        float: none;
        display: block;
        margin-right: auto;
        position: relative;
        left: auto;
        transform: none;
        margin-bottom: 20px;
    }
    .user-links {
        float: none;
        top: auto;
        right: auto;
        width: auto;
        padding: 0;
        position: relative;
        transform: none;
        background-color: transparent;
    }
    .user-links a {
        display: inline-block;
        margin-bottom: 0;
    }
    .user-links span {
        display: inline-block;
    }
}
    </style>
</head>
<body>
<!-- header  -->
<header>
    <div class="container">
        <div class="logo-container">
            <a href="index.php">
                <img src="logo.png" alt="Logo">
            </a>
        </div>

        <div class="user-links">
            <?php
                if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                    echo '
                    Welcome, ' . $_SESSION['username'] . '!
                    <a href="library.php">My Library</a>
                    <a href="signout.php" id="signOut">Sign Out</a>';
                } else {
                    echo '<a href="https://benjaminl.sgedu.site/tuner/signin.html">Sign In</a>';
                }
            ?>
        </div>
    </div>
</header>

<nav>
    <!-- Search Bar -->
    <div class="container">
        <form id="searchForm" action="search-results.php" method="get">
            <label for="searchInput">Search:</label>
            <input type="text" id="searchInput" name="searchInput" required>
            <button type="submit">Go</button>
        </form>
    </div>
</nav>

<h2>Your Library</h2>

<?php
//  connect to database 
include "db_auth.php";

// establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get user ID and get corresponding reviews
$user_id = $_SESSION['user_id']; 
$sql = "SELECT song_name, artist, song_info, rating FROM Songs WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// display user's reviews
while ($row = $result->fetch_assoc()) {
    $songName = $row['song_name'];
    $artistName = $row['artist'];
    $songInfo = $row['song_info'];
    $songRating = $row['rating'];
// write out all of the fields to contain song information and write the information
    echo "<div class='song-info'>";
    echo "<div class='song-details'>";
    echo "<a href='song.php?name=" . urlencode($songName) . "&artist=" . urlencode($artistName) . "'>$songName</a><br>";
    echo "Artist: $artistName<br>";
    echo "<input type='hidden' class='song-name' value='$songName'>";
    echo "<input type='hidden' class='artist-name' value='$artistName'>";
    echo "<span class='song-rating'>Your rating:</span> $songRating<br>";
    echo "<span class='song-comments'>Your comments:</span> $songInfo<br>";
    echo "</div>";
    echo "</div>";
}
// close the connection 
$conn->close();
?>

<footer>
    <div class="container">
        <p>&copy; 2024 Tuner. All rights reserved.</p>
    </div>
</footer>
</body>
</html>

<script>
    // check and Parse search input 
document.addEventListener('DOMContentLoaded', function() {
   document.getElementById('searchForm').addEventListener('submit', function(event) {
       event.preventDefault(); // prevent default form submission
       const searchType = 'track';
    const query = document.getElementById('searchInput').value.trim();


    if (query !== '') {
        const searchUrl = `search-results.php?searchType=${searchType}&searchInput=${encodeURIComponent(query)}`;
        window.location.href = searchUrl;
    }
    });

    
});

</script>

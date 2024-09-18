<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="icon.png" type="image/icon type">
    <title>Song Details</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f8f8;
            color: #000;
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

        h1 {
            display: inline-block;
            font-size: 40px;
            margin: 0;
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
            margin-bottom: 20px; 
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

        .song-details {
            margin-bottom: 30px;
            position: relative; 
        }

        .song-details h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .song-details p {
            margin: 10px 0;
        }

        .song-details img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 20px;
        }

        form select,
        form textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            display: block;
        }
        
        form select {
            width: 100%;
            max-width: 60px;
        }

        form button[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #5e88d6;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .reviews h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .review {
            padding: 15px;
            background-color: #808080;
            border-radius: 8px;
            margin-bottom: 15px;
            color: #fff;
        }

        .review p {
            margin: 5px 0;
        }

        #averageRatingSquare {
            position: absolute;
            top: 20px; 
            right: 20px;
            width: 80px;
            height: 80px;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            line-height: 80px;
            font-size: 18px;
            color: black;
            font-weight: bold;
        }

        /* color based on rating */
        .green {
            background-color: #66FF00;
        }

        .yellow {
            background-color: yellow;
        }

        .red {
            background-color: red;
            color: white;
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

<header>
    <div class="container">
        <div class="logo-container">
            <a href="index.php">
                <img src="logo.png" alt="Logo">
            </a>
        </div>

        <div class="user-links">
            <?php
                // display based on if the user is logged in
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
    <!-- Search bar -->
    <div class="container">
        <form id="searchForm" action="search-results.php" method="get">
            <label for="searchInput">Search:</label>
            <input type="text" id="searchInput" name="searchInput" required>
            <button type="submit">Go</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="song-details">
        <h2>Song Details</h2>
        <div id="averageRatingSquare"></div>
        <!-- song information -->
        <p><strong>Song Name:</strong> <span id="songName"></span></p>
        <p><strong>Artist Name:</strong> <span id="artistName"></span></p>
        <img src="" alt="Song Image" id="songImage" width=300px>
    </div>
            
    <!-- song rating -->
    <form action="add_rating.php" method="post" onsubmit="return checkLoggedIn()">
        Rate this song:
        <select name="rating" id="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>

        Comments:
        <textarea id="comments" name="comments" rows="4" cols="50" required></textarea><br><br>

        <input type="hidden" name="song_name" value="<?php echo htmlspecialchars($_GET['name']); ?>">
        <input type="hidden" name="artist_name" value="<?php echo htmlspecialchars($_GET['artist']); ?>">

        <button type="submit">Submit Review</button>
    </form>
</div>

<div class="container">
    <div class="reviews">
        <h2>Reviews</h2>
        <?php
        // database connection
        include "db_auth.php";


        // create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // get song and artist name from URL parameters
        $artist_name = $_GET['artist'];
        $song_name = $_GET['name'];

        // does appopriate escape sequences to avoid SQL insertion errors
        $artist_name = $conn->real_escape_string($artist_name);
        $song_name = $conn->real_escape_string($song_name);

        // SQL query to select reviews for the specified song
        $sql = "SELECT song_info, rating, user_id, average_rating FROM Songs WHERE song_name = '$song_name' AND artist = '$artist_name'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // initialize variables for calculating average rating
            $total_ratings = 0;
            $num_reviews = 0;

            // loop through results and display each review
            while ($row = $result->fetch_assoc()) {
                $song_info = $row['song_info'];
                $rating = $row['rating'];
                $user_id = $row['user_id'];
                $avg_rating = $row['average_rating'];

                $total_ratings += $rating;
                $num_reviews++;

                //get username for the current user
                $user_query = "SELECT username FROM Users WHERE user_id = '$user_id'";
                $user_result = $conn->query($user_query);
                $user_row = $user_result->fetch_assoc();
                $username = $user_row['username'];

                // display the review
                echo "<div class='review'>";
                echo "<p><strong>Username:</strong> $username</p>";
                echo "<p><strong>Rating:</strong> $rating</p>";
                echo "<p><strong>Comments:</strong> $song_info";
                echo "</div>";
            }

            if ($num_reviews > 0) {
                // add corresponding color class based on rating
                $color_class = '';
                if ($avg_rating >= 4) {
                    $color_class = 'green';
                } elseif ($avg_rating >= 2) {
                    $color_class = 'yellow';
                } else {
                    $color_class = 'red';
                }
                echo "<script>document.getElementById('averageRatingSquare').innerText = 'Avg: $avg_rating';</script>";
                echo "<script>document.getElementById('averageRatingSquare').classList.add('$color_class');</script>";
            } else {
                // no reviews
                echo "<script>document.getElementById('averageRatingSquare').innerText = 'Avg: N/A';</script>";
            }
        } else {
            // no reviews
            echo "<script>document.getElementById('averageRatingSquare').innerText = 'Avg: N/A';</script>";
        }

        $conn->close();
        ?>
    </div>
</div>

<script>
    // get query parameters from URL
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const songName = urlParams.get('name');
    const artistName = urlParams.get('artist');

    document.getElementById('songName').textContent = songName;
    document.getElementById('artistName').textContent = artistName;

    // Call the function to fetch album art
    fetchAlbumArt(songName, artistName, document.getElementById('songImage'));

    // set song details on the page
    document.getElementById('songName').textContent = songName;
    document.getElementById('artistName').textContent = artistName;
    document.getElementById('songImage').src = decodeURIComponent(songImage);

    function checkLoggedIn() {
        // check if the user is logged in
        if ("<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>" !== 'true') {
            // display an error message
            alert("Please log in to submit a review.");
            return false; // prevent form submission
        }

        <?php
        // Database connection
        include "db_auth.php";

        // create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // get user ID from session
        $user_id = $_SESSION['user_id'];

        // get song name from URL parameter
        $song_name = $_GET['name']; 

        // SQL query to check if the user has already reviewed the song
        $sql_check_review = "SELECT * FROM Songs WHERE song_name = '$song_name' AND user_id = '$user_id'";
        $result_check_review = $conn->query($sql_check_review);

        if ($result_check_review->num_rows > 0) {
            echo "alert('You have already reviewed this song.');";
            echo "return false;";
        }

        // closes database connection
        $conn->close();
        ?>

        // allow form submission if the user has not reviewed the song
        return true;
    }

document.addEventListener('DOMContentLoaded', function() {
   document.getElementById('searchForm').addEventListener('submit', function(event) {
        // prevent default form submission
       event.preventDefault(); 

       const searchType = 'track';
       const query = document.getElementById('searchInput').value.trim();


       if (query !== '') {
           const searchUrl = `search-results.php?searchType=${searchType}&searchInput=${encodeURIComponent(query)}`;
           window.location.href = searchUrl; 
       }
   });
});

function fetchAlbumArt(songName, artistName, imgElement) {
    const apiKey = 'd6d301e46d6fbb9e3c5fefcbcaab2687';
    const apiUrl = `https://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=${apiKey}&artist=${encodeURIComponent(artistName)}&track=${encodeURIComponent(songName)}&format=json`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.track && data.track.album && data.track.album.image && data.track.album.image.length > 0) {
                //find the URL of largest image available
                const albumArtUrl = data.track.album.image.find(image => image.size === 'extralarge')['#text'];

                //display the album art image
                imgElement.src = albumArtUrl;
            } else {
                imgElement.src = "noimage.png";
            }
        })
        .catch(error => {
            console.error('Failed to fetch album art:', error);
        });
}
</script>

<footer>
    <div class="container">
        <p>&copy; 2024 Tuner. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tuner</title>
    <link rel="icon" href="icon.png" type="image/icon type">
   <style>
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

    /* main content styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
    }

    .featured-songs, .top-rated-songs {
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
        <div class="user-links" id="userLinks">
            <?php
                if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                    echo '<span>
                    Welcome, ' . $_SESSION['username'] . '!</span>
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
    <div class="container">
        <form id="searchForm" action="search-results.php" method="get">
            <label for="searchInput">Search:</label>
            <input type="text" id="searchInput" name="searchInput" required>
            <button type="submit">Go</button>
        </form>
    </div>
</nav>


<div class="container">
    <!-- display top 10 average rated songs -->
    <section class="top-rated-songs">
    <h2>Top 10 Rated Songs on Tuner</h2>
    <div class="albums-container" id="topRatedSongsContainer">
<?php
// database connection
$servername = "localhost";
$username = "uoo87t6cg9q6a";
$password = "Webprogramming!";
$dbname = "dbkkfhwoh96jgp";

// create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// SQL query to select top 10 rated songs
$sql = "SELECT DISTINCT song_name, artist, MAX(average_rating) as max_rating FROM Songs GROUP BY song_name, artist ORDER BY max_rating DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rank = 1;
    
// output each song w/ album art
while ($row = $result->fetch_assoc()) {
    $song_name = $row['song_name'];
    $artist = $row['artist'];
    $avg_rating = $row['max_rating'];

    // fetch album art using Last.fm API
    $apiKey = 'd6d301e46d6fbb9e3c5fefcbcaab2687';
    $apiUrl = "https://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key={$apiKey}&artist=" . urlencode($artist) . "&track=" . urlencode($song_name) . "&format=json";
    $json = file_get_contents($apiUrl);
    $data = json_decode($json, true);

    // check if album art is available
    if (isset($data['track']['album']['image'][2]['#text'])) {
        $imageURL = $data['track']['album']['image'][2]['#text'];
    } else {
        $imageURL = "noimage.png";
    }

    // display song with album art
    echo "<a href='song.php?name=" . urlencode($song_name) . "&artist=" . urlencode($artist) . "'>";
    echo "<img src='$imageURL' alt='$song_name Album Art' width='100px'>";
    echo "<p><strong>$rank. $song_name</strong> by $artist <br> (Average Rating: $avg_rating)</p>";
    echo "</a>";

    $rank++;
}
} else {
    echo "<p>No top rated songs found.</p>";
}

// close connection
$conn->close();
?>

</div>
</section>
    
    <!-- display featured songs -->
    <section class="featured-songs">
        <h2>Featured Songs</h2>
        <div class="albums-container" id="featuredSongsContainer">
            <!-- featured songs will be dynamically added here later -->
        </div>
    </section>
</div>

<footer>
    <div class="container">
        <p>&copy; 2024 Tuner. All rights reserved.</p>
    </div>
</footer>


<script>
function fetchFeaturedSongs() {
   const tag = 'new';
   const apiKey = 'd6d301e46d6fbb9e3c5fefcbcaab2687';
   const numTracks = 12;

   console.log('Fetching featured songs...');
   const apiUrl = `https://ws.audioscrobbler.com/2.0/?method=chart.gettoptracks&tag=${tag}&api_key=${apiKey}&format=json&limit=${numTracks}`;


   fetch(apiUrl)
       .then(response => {
           if (!response.ok) {
               throw new Error('Network response was not ok');
           }
           return response.json();
       })
       .then(data => {
           console.log('Received featured songs:', data);


           const featuredSongsContainer = document.getElementById('featuredSongsContainer');
           featuredSongsContainer.innerHTML = ''; // clear previous results


           if (data.tracks && data.tracks.track && data.tracks.track.length > 0) {
               data.tracks.track.forEach(async track => {
                   try {
                       // fetch track information
                       const trackInfoUrl = `https://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=${apiKey}&artist=${encodeURIComponent(track.artist.name)}&track=${encodeURIComponent(track.name)}&format=json`;

                       const trackInfoResponse = await fetch(trackInfoUrl);
                       const trackInfoData = await trackInfoResponse.json();

                       if (trackInfoData.track && trackInfoData.track.album) {
                           const album = trackInfoData.track.album;

                           // extract album artwork URL
                           const imageUrl = (album.image && Array.isArray(album.image) && album.image.length > 0) ? album.image[album.image.length - 1]['#text'] : null;

                           // create the song element with album artwork
                           const songElement = createSongElement(track, imageUrl);
                           featuredSongsContainer.appendChild(songElement);
                       }
                   } catch (error) {
                       console.error('Error fetching track info:', error);
                   }
               });
           } else {
               console.warn('No featured songs found:', data);
               featuredSongsContainer.innerHTML = '<p>No featured songs found.</p>';
           }
       })
       .catch(error => {
           console.error('Error fetching featured songs:', error);
           const featuredSongsContainer = document.getElementById('featuredSongsContainer');
           featuredSongsContainer.innerHTML = '<p>Failed to fetch data. Please try again later.</p>';
       });
}

function createSongElement(track, imageUrl) {
   const trackElement = document.createElement('div');
   trackElement.classList.add('albums-container');
  
   const encodedName = encodeURIComponent(track.name);
   const artistName = track.artist.name;

   trackElement.innerHTML = `
       <a href="song.php?name=${encodedName}&artist=${encodeURIComponent(artistName)}">
           ${imageUrl ? `<img src="${imageUrl}" alt="${track.name} Album Art">` : '<img src="default_image.jpg" alt="No Image Available">'}
           <p><strong>${track.name}</strong> by ${artistName}</p>
       </a>
   `;

   return trackElement;
}

// call fetchFeaturedSongs() on page load
window.onload = function() {
   fetchFeaturedSongs();
};

function fetchSearchResults(searchType, query) {
   const apiKey = 'd6d301e46d6fbb9e3c5fefcbcaab2687';
   let method = 'track.search';

   const apiUrl = `https://ws.audioscrobbler.com/2.0/?method=${method}&${searchType}=${encodeURIComponent(query)}&api_key=${apiKey}&format=json`;

   const searchResultsContainer = document.getElementById('searchResultsContainer');
   searchResultsContainer.innerHTML = ''; // clear previous results

   fetch(apiUrl)
       .then(response => response.json())
       .then(data => {
           if (data.results) {
               const searchResults = data.results[`${searchType}matches`][searchType];
               if (searchResults && searchResults.length > 0) {
                   searchResults.forEach(async result => {
                       let resultElement;
                       if (searchType === 'track') {
                           // fetch track info including album
                           const trackInfoUrl = `https://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=${apiKey}&artist=${encodeURIComponent(result.artist)}&track=${encodeURIComponent(result.name)}&format=json`;
                           
                           try {
                               const trackInfoResponse = await fetch(trackInfoUrl);
                               const trackInfoData = await trackInfoResponse.json();
                               
                               if (trackInfoData.track && trackInfoData.track.album) {
                           const album = trackInfoData.track.album;

                           // extract album artwork URL
                           const imageUrl = (album.image && Array.isArray(album.image) && album.image.length > 0) ? album.image[album.image.length - 1]['#text'] : null;


                           // create the song element with album artwork
                           const songElement = createSongElement(track, imageUrl);
                           featuredSongsContainer.appendChild(songElement);
                       }
                           } catch (error) {
                               console.error('Error fetching track info:', error);
                               resultElement = createTrackElement(result, null); // create track element without image
                           }
                       }
                       searchResultsContainer.appendChild(resultElement);
                   });
               } else {
                   searchResultsContainer.innerHTML = `<p>No ${searchType}s found for "${query}".</p>`;
               }
           } else {
               searchResultsContainer.innerHTML = `<p>No ${searchType}s found for "${query}".</p>`;
           }
       })
       .catch(error => {
           console.error(`Error fetching ${searchType} search data:`, error);
           searchResultsContainer.innerHTML = '<p>Failed to fetch data. Please try again later.</p>';
       });
}

function createTrackElement(track) {
    const trackElement = document.createElement('div');
    trackElement.classList.add('albums-container');

    const encodedName = encodeURIComponent(track.name);
    const encodedArtist = encodeURIComponent(track.artist);
    const imageUrl = track.image && track.image[1] && track.image[1]['#text'];

    trackElement.innerHTML = `
        <a href="song.php?name=${encodedName}&artist=${encodedArtist}">
            ${imageUrl ? `<img src="${imageUrl}" alt="${track.name} Album Art">` : ''}
            <p><strong>${track.name}</strong> by ${track.artist}</p>
        </a>
    `;

    return trackElement;
}

// handle form submission for search
document.addEventListener('DOMContentLoaded', function() {
   document.getElementById('searchForm').addEventListener('submit', function(event) {
       event.preventDefault(); // prevent default form submission

       const searchType = 'track';
       const query = document.getElementById('searchInput').value.trim();


       if (query !== '') {
           const searchUrl = `search-results.php?searchType=${searchType}&searchInput=${encodeURIComponent(query)}`;
           window.location.href = searchUrl; // redirect to search results URL
       }
   });
});

</script>

</body>
</html>

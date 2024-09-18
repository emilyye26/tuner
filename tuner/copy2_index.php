<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tuner</title>
   <link rel="stylesheet" href="styles.css">
    <style>
        /* Add some basic styling for the search bar */
        .search-bar {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ff6600;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .featured-albums-container, .search-results-container {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 20px;
        }
        .albums-container {
            display: inline-block;
            vertical-align: top;
            white-space: normal;
            margin-right: 10px;
            width: 200px; /* Adjust width of each album container */
            text-align: center; /* Center-align content within each album container */
        }
        .albums-container img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
       </style>
</head>
<body>

<header>
    <div class="container">
        <div class="left-side">
            <div class="logo">
                <img src="music.png" alt="Logo">
            </div>
            <div class="site-name">
                <h1>Tuner</h1>
            </div>
        </div>
        <div class="right-side">
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <?php
                        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                            echo '
                            <li><a href="#">Welcome, ' . $_SESSION['username'] . '!</a></li>
                            <li><a href="library.php">My Library</a></li>
                            <li><a href="signout.php" id="signOut">Sign Out</a></li>';
                        } else {
                            echo '<li><a href="https://benjaminl.sgedu.site/tuner/signin.html">Sign In</a></li>';
                        }
                    ?>
                </ul>
            </nav>
            <div class="search-bar">
                <form id="searchForm" action="search-results.php" method="get">
                    <label for="searchInput">Search:</label>
                    <input type="text" id="searchInput" name="searchInput" required>
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Display featured albums -->
<section class="featured-songs">
   <div class="container">
       <h2>Featured Songs</h2>
       <div id="featuredSongsContainer" class="featured-songs-container">
           <!-- Featured songs will be dynamically added here -->
       </div>
   </div>
</section>


<footer>
   <div class="container">
       <p>&copy; 2024 Tuner. All rights reserved.</p>
   </div>
</footer>


<script>
function fetchFeaturedSongs() {
   const tag = 'new'; // Use a relevant tag for new or recent releases
   const apiKey = 'd6d301e46d6fbb9e3c5fefcbcaab2687'; // Replace with your Last.fm API key
   const numTracks = 12; // Number of featured tracks to fetch


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
           featuredSongsContainer.innerHTML = ''; // Clear previous results


           if (data.tracks && data.tracks.track && data.tracks.track.length > 0) {
               data.tracks.track.forEach(async track => {
                   try {
                       // Fetch detailed track info including album
                       const trackInfoUrl = `https://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=${apiKey}&artist=${encodeURIComponent(track.artist.name)}&track=${encodeURIComponent(track.name)}&format=json`;


                       const trackInfoResponse = await fetch(trackInfoUrl);
                       const trackInfoData = await trackInfoResponse.json();


                       if (trackInfoData.track && trackInfoData.track.album) {
                           const album = trackInfoData.track.album;


                           // Extract album artwork URL
                           const imageUrl = (album.image && Array.isArray(album.image) && album.image.length > 0) ? album.image[album.image.length - 1]['#text'] : null;


                           // Create the song element with album artwork
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


// Call fetchFeaturedSongs() on page load
window.onload = function() {
   fetchFeaturedSongs();
};




function fetchSearchResults(searchType, query) {
   const apiKey = 'd6d301e46d6fbb9e3c5fefcbcaab2687'; // Replace with your Last.fm API key
   let method = 'track.search';


   const apiUrl = `https://ws.audioscrobbler.com/2.0/?method=${method}&${searchType}=${encodeURIComponent(query)}&api_key=${apiKey}&format=json`;


   const searchResultsContainer = document.getElementById('searchResultsContainer');
   searchResultsContainer.innerHTML = ''; // Clear previous results


   fetch(apiUrl)
       .then(response => response.json())
       .then(data => {
           if (data.results) {
               const searchResults = data.results[`${searchType}matches`][searchType];
               if (searchResults && searchResults.length > 0) {
                   searchResults.forEach(result => {
                       let resultElement;
                       if (searchType === 'track') {
                           resultElement = createTrackElement(result);
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


   trackElement.innerHTML = `
       <a href="song.php?name=${encodedName}&artist=${encodedArtist}">
           <p><strong>${track.name}</strong> by ${track.artist}</p>
       </a>
   `;


   return trackElement;
}


// Handle form submission for search
document.addEventListener('DOMContentLoaded', function() {
   document.getElementById('searchForm').addEventListener('submit', function(event) {
       event.preventDefault(); // Prevent default form submission behavior


       const searchType = 'track';
       const query = document.getElementById('searchInput').value.trim();


       if (query !== '') {
           const searchUrl = `search-results.php?searchType=${searchType}&searchInput=${encodeURIComponent(query)}`;
           window.location.href = searchUrl; // Redirect to search results URL in the same tab
       }
   });
});
</script>




</body>
</html>


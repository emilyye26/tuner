<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="icon.png" type="image/icon type">
    <title>Search Results</title>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .albums-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .album {
            width: calc(20% - 20px);
            text-align: center;
            margin-bottom: 20px;
        }

        .album img {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .album p {
            margin: 0;
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
    <div class="container">
        <form id="searchForm" action="search-results.php" method="get">
            <label for="searchInput" style="color: white">Search:</label>
            <input type="text" id="searchInput" name="searchInput" required>
            <button type="submit">Go</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="albums-container" id="searchResultsContainer">
        <!-- search results will be dynamically added here later -->
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; 2024 Tuner. All rights reserved.</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchType = urlParams.get('searchType');
        const searchInput = urlParams.get('searchInput');

        if (searchType && searchInput) {
            fetchSearchResults(searchType, searchInput);
        } else {
            const searchResultsContainer = document.getElementById('searchResultsContainer');
            searchResultsContainer.innerHTML = '<p>No search results found.</p>';
        }
    });

    function fetchSearchResults(searchType, query) {
        const apiKey = 'd6d301e46d6fbb9e3c5fefcbcaab2687';
        const apiUrl = `https://ws.audioscrobbler.com/2.0/?method=track.search&${searchType}=${encodeURIComponent(query)}&api_key=${apiKey}&format=json`;

        const searchResultsContainer = document.getElementById('searchResultsContainer');
        searchResultsContainer.innerHTML = ''; // clear previous results

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.results) {
                    const searchResults = data.results[`${searchType}matches`][searchType];
                    if (searchResults && searchResults.length > 0) {
                        searchResults.forEach(result => {
                            const resultElement = createTrackElement(result);
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
    trackElement.classList.add('album');

    // create an image element for the album art
    const albumArtImg = document.createElement('img');
    albumArtImg.alt = 'Album Art';
    albumArtImg.src = "noimage.png";
    trackElement.appendChild(albumArtImg);

    // call the function to fetch album art and update the image source
    fetchAlbumArt(track.name, track.artist, albumArtImg);

    //create a link element for the track
    const trackLink = document.createElement('a');
    trackLink.href = `song.php?name=${encodeURIComponent(track.name)}&artist=${encodeURIComponent(track.artist)}`;
    trackLink.textContent = track.name;
    trackElement.appendChild(trackLink);

    // create a paragraph element for the artist name
    const artistName = document.createElement('p');
    artistName.textContent = track.artist;
    trackElement.appendChild(artistName);

    return trackElement;
}

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
                            imgElement.src = "noimage.png";

            console.error('Failed to fetch album art:', error);
        });
}

    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault(); // prevent default form submission

        const query = document.getElementById('searchInput').value.trim();

        if (query !== '') {
            const searchUrl = `search-results.php?searchType=track&searchInput=${encodeURIComponent(query)}`;
            window.location.href = searchUrl; // redirect to search results URL in the same tab
        }
    });
</script>

</body>
</html>
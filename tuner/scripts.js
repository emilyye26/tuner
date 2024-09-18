document.addEventListener('DOMContentLoaded', function() {
    // Fetch and display featured albums on the main page
    fetchFeaturedAlbums();

    // Handle form submission for search on the main page
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission

        const searchType = document.getElementById('searchType').value;
        const query = document.getElementById('searchInput').value.trim();

        if (query !== '') {
            const searchUrl = `search-results.php?searchType=${searchType}&searchInput=${encodeURIComponent(query)}`;
            window.open(searchUrl, '_blank'); // Open search results in a new tab
        }
    });
});

function getArtistName(album) {
    if (album.artist) {
        if (typeof album.artist === 'string') {
            return album.artist; // Handle case where artist is a string
        } else if (album.artist.name) {
            return album.artist.name; // Retrieve artist name if available
        }
    }
    return 'Unknown Artist'; // Default to 'Unknown Artist' if artist name is not provided
}

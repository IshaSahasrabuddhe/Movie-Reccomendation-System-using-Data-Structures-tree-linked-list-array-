<?php
include_once 'db.php'; // Include database connection
include_once 'MovieNode.php'; // Include the MovieNode class
include_once 'MovieBST.php'; // Include the MovieBST class

// Initialize the binary search tree
$movieTree = new MovieBST();

// Fetch movies from the database and insert them into the tree
$sql = "SELECT * FROM mymoviedb";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming Genre is part of the row data
        $movieTree->insert($row['Title'], $row['Release_Date'], $row['Overview'], $row['Poster_Url'], $row['Genre']);
    }
}

// Search for the movie if a search term is submitted
$searchedMovie = null;
$recommendedMovies = [];
$searchTerm = ''; // Initialize $searchTerm for error handling
if (isset($_POST['searchTerm']) && !empty($_POST['searchTerm'])) { // Only search if a search term is provided
    $searchTerm = $_POST['searchTerm'];
    $searchedMovie = $movieTree->search($searchTerm);
    
    // Recommend similar movies based on genre
    if ($searchedMovie) {
        $genre = $searchedMovie->genre; // Access the genre of the searched movie
        $recommendedMovies = $movieTree->recommend($genre); // Use the recommend method
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Movies</title>
    <link rel="stylesheet" href="style.css"> <!-- Make sure you have the correct path -->

    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
    
}

.movie-card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px; /* Add some spacing between cards */
}

.movie-card {
    border: 1px solid #ddd;
    border-radius: 30px;
    padding: 16px;
    margin: 10px;
    max-width: 300px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    background-color:white;
    color:black
    
}

.movie-card img {
    width: 100%;
    height: auto;
    border-radius: 30px;
    
}

.movie-card:hover {
    transform: translateY(-10px); /* Adds a hover effect */
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

form {
    text-align: left;
    margin-bottom: 20px;
}

input[type="text"] {
    padding: 10px;
    width: 200px;
}

input[type="submit"] {
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>

</head>
<body style="background-color: black; padding-left: 100px; padding-right:100px ; color: white;">
    <!-- Search Form -->
    <nav style="background-color: black; padding: 30px; text-align: right; color: white">
        <a href="dashboard.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Dashboard</a>
        <a href="search.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Search</a>
        <a href="wish.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Wish List</a>
        <a href="watched.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Watched</a>
    </nav>

    <h1 style = "font-size: 70px; text-align: left;  ">Search for a Movie</h1>
    <form method="POST" action="search.php">
        <input type="text" name="searchTerm" placeholder="Enter movie title" required>
        <input type="submit" value="Search">
    </form>

    <!-- Search Results -->
    <?php if (isset($_POST['searchTerm']) && !empty($searchTerm)): ?> <!-- Show results only after search is submitted -->
        <h1 style = "font-size: 40px; text-align: left;  ">Search Results</h1>
        <div class="movie-card-container">
            <?php if ($searchedMovie): ?>
                <div class="movie-card">
                    <img src="<?php echo $searchedMovie->posterUrl; ?>" alt="<?php echo $searchedMovie->title; ?>" />
                    <h2><?php echo $searchedMovie->title; ?></h2>
                    <p><?php echo $searchedMovie->overview; ?></p>
                    <a href="post.php?title=<?php echo urlencode($searchedMovie->title); ?>">View Details</a>
                </div>
                
                <br> <br>
                <h3 style = "font-size: 50px; text-align: left;  ">Similar Movies</h3>
                <div class="recommended-movies movie-card-container">
                    <?php if (!empty($recommendedMovies)): ?>
                        <?php foreach ($recommendedMovies as $movie): ?>
                            <div class="movie-card">
                                <img src="<?php echo $movie->posterUrl; ?>" alt="<?php echo $movie->title; ?>" />
                                <h2><?php echo $movie->title; ?></h2>
                                <p><?php echo $movie->overview; ?></p>
                                <a href="post.php?title=<?php echo urlencode($movie->title); ?>">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No similar movies found for genre "<?php echo htmlspecialchars($searchedMovie->genre); ?>"</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>No movie found with the title "<?php echo htmlspecialchars($searchTerm); ?>"</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>

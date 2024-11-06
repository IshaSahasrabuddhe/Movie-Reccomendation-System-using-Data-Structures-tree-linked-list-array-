<?php
// dashboard.php
include 'db.php';

// Get filter values if set
$genreFilter = isset($_GET['genre']) ? $_GET['genre'] : '';
$ratingFilter = isset($_GET['rating']) ? $_GET['rating'] : '';

// Build the SQL query with filters
$sql = "SELECT * FROM mymoviedb WHERE 1=1";
if ($genreFilter) {
    $sql .= " AND Genre = '$genreFilter'";
}
if ($ratingFilter) {
    $sql .= " AND Vote_Average >= '$ratingFilter'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS here -->

    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
}

.movie-cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    
    color:black
}

.card {
    border: 1px solid #ccc;
    border-radius: 30px;
    width: 200px;
    background-color:white;
    color:black;
    
    text-align: center;
}

.card img {
    max-width: 100%;
    border-radius: 30px;
}

.card a {
    display: block;
    margin-top: 10px;
    text-decoration: none;
    color: blue;
}

    </style>



</head>
<body style="background-color: black; padding-left: 100px; padding-right:100px ; color: white;">
<nav style="background-color: black; padding: 30px; text-align: right; color: white">
        <a href="dashboard.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Dashboard</a>
        <a href="search.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Search</a>
        <a href="wish.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Wish List</a>
        <a href="watched.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Watched</a>
    </nav>
    <h1 style = "font-size: 70px; text-align: left;  ">DS-Movie Recommendation System</h1>
    <h2 style = "font-size: 60px; text-align: left; ">Movie Dashboard</h2>
    
    <!-- Filters -->
    <form method="GET" action="dashboard.php" style="background-color: black;   color: white">
        <select name="genre" style="color: white; margin: 10px;  font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px; background-color:black">
            <option value="">Select Genre</option>
            <option value="Action">Action</option>
            <option value="Comedy">Comedy</option>
            <option value="Drama">Drama</option>
            <!-- Add more genres as needed -->
        </select>
        <select name="rating" style="color: white; margin: 10px;  font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px; background-color:black">
            <option value="">Select Minimum Rating</option>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>
        <input type="submit" value="Filter" style="color: white; margin: 10px;  font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px; background-color:black">
    </form>

    <div class="movie-cards">
    <?php if ($result->num_rows > 0): ?>
        <?php 
        $skipFirstRow = true; // Flag to skip the first row
        while ($row = $result->fetch_assoc()): 
            if ($skipFirstRow) {
                $skipFirstRow = false; // Set the flag to false after skipping the first row
                continue; // Skip the first row
            }
        ?>
            <div class="card">
                <img src="<?php echo $row['Poster_Url']; ?>" alt="<?php echo $row['Title']; ?>">
                <h2><?php echo $row['Title']; ?></h2>
                <p><?php echo substr($row['Overview'], 0, 100) . '...'; ?></p>
                <a href="post.php?title=<?php echo urlencode($row['Title']); ?>">View More</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No movies found.</p>
    <?php endif; ?>
</div>

    <?php $conn->close(); ?>
</body>
</html>

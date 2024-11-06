<?php
session_start();

// initialize the watched queue if not already initialized
if (!isset($_SESSION['watchedQueue'])) {
    $_SESSION['watchedQueue'] = new SplQueue(); // use SplQueue for a real queue structure
}

// add a movie to the watched queue
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['poster_url'])) {
    $title = $_POST['title'];
    $posterUrl = $_POST['poster_url'];

    // enqueue a new movie
    $_SESSION['watchedQueue']->enqueue([
        'title' => $title,
        'posterUrl' => $posterUrl
    ]);
}

// remove a movie from the watched queue
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeTitle'])) {
    $titleToRemove = $_POST['removeTitle'];
    $_SESSION['watchedQueue'] = removeWatchedMovie($_SESSION['watchedQueue'], $titleToRemove);
}

//function to remove a movie from the watched queue
function removeWatchedMovie($queue, $titleToRemove) {
    $newQueue = new SplQueue(); // Create a new empty queue to hold the filtered movies
    foreach ($queue as $movie) {
        if ($movie['title'] !== $titleToRemove) {
            $newQueue->enqueue($movie); // Only enqueue movies that don't match the title to remove
        }
    }
    return $newQueue; // Return the new filtered queue
}

// Display watched queue with View More and Remove buttons
function displayWatchedQueue($queue) {
    foreach ($queue as $movie) {
        echo '<div class="card">';
        echo '<img src="' . $movie['posterUrl'] . '" alt="' . $movie['title'] . '">';
        echo '<h2>' . $movie['title'] . '</h2>';
        echo '<a href="post.php?title=' . urlencode($movie['title']) . '">View More</a>';
        echo '<form method="POST" style="display:inline;">';
        echo '<input type="hidden" name="removeTitle" value="' . $movie['title'] . '">';
        echo '<button type="submit">Remove</button>';
        echo '</form>';
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watched Movies</title>
    <style>
        .card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            display: inline-block;
            width: 200px;
        }
        .card img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body style="background-color: black; padding-left: 100px; padding-right:100px ; color: white;font-family: Arial, sans-serif;">
    <nav style="background-color: black; padding: 30px; text-align: right; color: white">
        <a href="dashboard.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Dashboard</a>
        <a href="search.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Search</a>
        <a href="wish.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Wish List</a>
        <a href="watched.php" style="color: white; margin: 10px; text-decoration: none; font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px">Watched</a>
    </nav>

    <h1 style="font-size: 70px; text-align: left;">Watched Movies</h1>
    <div class="watched-queue">
        <?php displayWatchedQueue($_SESSION['watchedQueue']); ?>
    </div><br>
    <a href="dashboard.php" style="background-color: lightblue; color: black; padding: 10px; border-radius:30px; text-decoration: none; margin-top: 50px;">Back to Dashboard</a>
</body>
</html>

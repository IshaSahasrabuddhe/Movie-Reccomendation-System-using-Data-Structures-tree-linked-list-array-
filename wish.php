<?php
session_start();

// Linked List Node for Wish List
class WishNode {
    public $title;
    public $posterUrl;
    public $next;

    public function __construct($title, $posterUrl) {
        $this->title = $title;
        $this->posterUrl = $posterUrl;
        $this->next = null;
    }
}

// Initialize the linked list if not already initialized
if (!isset($_SESSION['wishList'])) {
    $_SESSION['wishList'] = null;
}

// Initialize the watched queue if not already initialized
if (!isset($_SESSION['watchedQueue'])) {
    $_SESSION['watchedQueue'] = new SplQueue(); // Use SplQueue for a queue structure
}

// Add movie to the wish list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['poster_url'])) {
    $newNode = new WishNode($_POST['title'], $_POST['poster_url']);

    if ($_SESSION['wishList'] === null) {
        $_SESSION['wishList'] = $newNode;
    } else {
        $current = $_SESSION['wishList'];
        while ($current->next !== null) {
            $current = $current->next;
        }
        $current->next = $newNode;
    }
}

// Remove a movie from the wish list or mark it as watched
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeTitle'])) {
    $titleToRemove = $_POST['removeTitle'];
    $_SESSION['wishList'] = removeWishMovie($_SESSION['wishList'], $titleToRemove);
}

// Move movie to the watched list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['watchedTitle'])) {
    $titleToWatch = $_POST['watchedTitle'];
    $posterUrlToWatch = $_POST['posterUrlToWatch'];
    $_SESSION['wishList'] = removeWishMovie($_SESSION['wishList'], $titleToWatch);

    // Add the movie to the watched queue
    $_SESSION['watchedQueue']->enqueue([
        'title' => $titleToWatch,
        'posterUrl' => $posterUrlToWatch
    ]);
}

// Function to remove a movie from the wish list
function removeWishMovie($head, $titleToRemove) {
    if ($head === null) return null;

    if ($head->title === $titleToRemove) {
        return $head->next;
    }

    $current = $head;
    while ($current->next !== null && $current->next->title !== $titleToRemove) {
        $current = $current->next;
    }

    if ($current->next !== null) {
        $current->next = $current->next->next;
    }

    return $head;
}

// Display wish list with View More, Remove, and Watched buttons
function displayWishList($head) {
    $current = $head;
    while ($current !== null) {
        echo '<div class="card">';
        echo '<img src="' . $current->posterUrl . '" alt="' . $current->title . '">';
        echo '<h2>' . $current->title . '</h2>';
        echo '<a href="post.php?title=' . urlencode($current->title) . '">View More</a>';
        echo '<form method="POST" style="display:inline;">';
        echo '<input type="hidden" name="removeTitle" value="' . $current->title . '">';
        echo '<button type="submit">Remove</button>';
        echo '</form>';
        echo '<form method="POST" style="display:inline; margin-left: 10px;">';
        echo '<input type="hidden" name="watchedTitle" value="' . $current->title . '">';
        echo '<input type="hidden" name="posterUrlToWatch" value="' . $current->posterUrl . '">';
        echo '<button type="submit">Watched</button>';
        echo '</form>';
        echo '</div>';
        $current = $current->next;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wish List</title>
    <style>
        .card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            display: inline-block;
            width: 200px;
            background-color: white;
            color: black;
            border-radius: 30px;
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
<h1 style="font-size: 70px; text-align: left;">Your Wish List</h1>
<div class="wish-list">
    <?php displayWishList($_SESSION['wishList']); ?>
</div><br>
<a href="dashboard.php" style="background-color: lightblue; color: black; padding: 10px; border-radius:30px; text-decoration: none; margin-top: 350px;">Back to Dashboard</a>
</body>
</html>

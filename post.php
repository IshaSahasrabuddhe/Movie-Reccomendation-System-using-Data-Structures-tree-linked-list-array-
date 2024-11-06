<?php
// post.php
include 'db.php';

// Get the title from the URL
$title = isset($_GET['title']) ? $_GET['title'] : '';

// Fetch the movie details
$sql = "SELECT * FROM mymoviedb WHERE Title = '$title'";
$result = $conn->query($sql);
$movie = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
    <title><?php echo $movie['Title']; ?></title>
</head>
<body style = " background-color: black; color: white; padding-left: 100px; font-family: Arial, sans-serif;">
<div class="row">
    <div class="col-md-6"  >
        <h1 style = "font-size: 70px; text-align: left; "><?php echo $movie['Title']; ?></h1>
        <img src="<?php echo $movie['Poster_Url']; ?>" alt="<?php echo $movie['Title']; ?>" style ="height: 900px; width: 900px; border-radius: 50px;  display: flex; align-self: center;">
    </div>

    <div class="col-md-6" style= "padding-top: 100px; padding-left: 150px; text-align: justify; padding-right: 50px">
    <p><strong>Release Date:</strong> <?php echo $movie['Release_Date']; ?></p>
    <p><strong>Overview:</strong> <?php echo $movie['Overview']; ?></p>
    <p><strong>Popularity:</strong> <?php echo $movie['Popularity']; ?></p>
    <p><strong>Vote Count:</strong> <?php echo $movie['Vote_Count']; ?></p>
    <p><strong>Vote Average:</strong> <?php echo $movie['Vote_Average']; ?></p>
    <p><strong>Original Language:</strong> <?php echo $movie['Original_Language']; ?></p>
    <p><strong>Genre:</strong> <?php echo $movie['Genre']; ?></p>

    <!-- Wish List Button -->
    <form action="wish.php" method="POST" style="display:inline;">
        <input type="hidden" name="title" value="<?php echo $movie['Title']; ?>">
        <input type="hidden" name="poster_url" value="<?php echo $movie['Poster_Url']; ?>">
        <button type="submit" style="color: white; margin: 10px;  font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px; background-color:black">Add to Wish List</button>
    </form>

    <!-- Watched Button -->
    <form action="watched.php" method="POST" style="display:inline; ">
        <input type="hidden" name="title" value="<?php echo $movie['Title']; ?>">
        <input type="hidden" name="poster_url" value="<?php echo $movie['Poster_Url']; ?>">
        <button type="submit" style="color: black; margin: 10px;  font-weight: bold; border-radius: 20px; border: 2px solid white; padding: 10px; background-color:white">MARK as Watched</button>
    </form>

    <br><br>
    <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>
    <?php $conn->close(); ?>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

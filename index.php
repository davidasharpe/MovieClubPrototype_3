<?php

    $page_title = "Movie Database";
    // Include header
    include('inc/header.php');
    // Include functions
    require_once('inc/functions.php');
    // Include connection details
    require_once('inc/connection.php');
?>

<h1 class="center-block">Movie Club Database</h1>
<div class="row">

    <?php
    // Perform database query
    $movies_result = get_movie_info();
    
    // Get data from query
    while($movie = mysqli_fetch_assoc($movies_result)) {
        
        // Convert MySQL date to user friendly format
        $sql_date = $movie["Release_Date"];
        $new_date = strtotime($sql_date);
    ?>

    <!-- Display data from query -->
    <div class="col-sm-4">
        <div class="thumbnail">
            <!--<img class="movie-image" src="img/uploads/<?php echo $movie['Image_Name']; ?>" alt="<?php echo $movie['Title']; ?>">-->
            <div class="caption">
                <h2><?php echo $movie["Title"]; ?></h2>
                <p>
                    <strong>Release Date: </strong><?php echo date("j F Y", $new_date); ?><br>
                    <strong>Genre: </strong><?php echo $movie["Name"]; ?><br>
                    <strong>Running Time: </strong><?php echo $movie["Running_Time"] . " minutes"; ?><br>
                    <strong>Distributor: </strong><?php echo $movie["Dist_Name"]; ?><br>
                    <strong>Starring: </strong><?php echo get_movies_actors($movie["Movie_ID"]); ?><br>
                    <strong>Director(s): </strong><?php echo get_movies_directors($movie["Movie_ID"]); ?><br>
                    <strong>Producer(s): </strong><?php echo get_movies_producers($movie["Movie_ID"]); ?>
                </p>
            </div>
        </div>
    </div>

    <?php 
        // End while loop
        }
    ?>

</div>

<?php
    // Release returned data
    mysqli_free_result($movies_result);
    // Close database connection
    mysqli_close($connection);
    // Include footer
    include('inc/footer.php');
?>
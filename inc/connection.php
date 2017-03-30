<?php
    // Create a database connection
    $dbhost = "localhost";
    $dbuser = "admin";
    $dbpass = "password@2017";
    $dbname = "movie_club_2";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Test if connection occurred
    if (mysqli_connect_errno()) {
        die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
    }
?>
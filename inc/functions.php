<?php
    // Check if record already exists
    function record_exists($query) {
        global $connection;
        
        $record_exists_query = $query;
        
        // Perform database query
        $query_result = mysqli_query($connection, $record_exists_query);
        
        // Test if there was a query error
        confirm_query($query_result);

        // Check if record already exists
        if (mysqli_num_rows($query_result) > 0) {
            return true;
        }
        // Release returned data
        mysqli_free_result($query_result);      
    }


    // Test if there was a query error
    function confirm_query($results_set) {
        global $connection;
        if (!$results_set) {
            die("Database query failed. " . mysqli_error($connection));
        }
    }

    // Retrieve movie information
    function get_movie_info() {
        global $connection;
        
        $movies_query = "SELECT movies.Movie_ID, movies.Title, movies.Release_Date, movies.Running_Time, movies.Image_Name, genres.Name, distributors.Name AS Dist_Name
        FROM movies, genres, distributors
        WHERE movies.Genre_ID = genres.Genre_ID
        AND movies.Distributor_ID = distributors.Distributor_ID
        ORDER BY movies.Title ASC";

        // Perform database query
        $movies_result = mysqli_query($connection, $movies_query);

        // Test if there was a query error
        confirm_query($movies_result);
        
        // Return results
        return $movies_result;
    }

    // Retrieve movies actors information
    function get_movies_actors($movie_id){
        global $connection;
        
        $movies_actors_query = "SELECT * FROM movies_actors INNER JOIN actors ON actors.Actor_ID = movies_actors.Actor_ID
        WHERE movies_actors.Movie_ID = {$movie_id}";
        
        // Perform database query
        $movies_actors_result = mysqli_query($connection, $movies_actors_query);

        // Test if there was a query error
        confirm_query($movies_actors_result);
        
        $count = 0;
        // Get data from query
        while($actor = mysqli_fetch_assoc($movies_actors_result)) {
            // Insert commas after first result
            if ($count != 0) {
                echo ', '; 
            }
            
            $count++;
            echo $actor["First_Name"] . " " . $actor["Last_Name"];
        }
        // Release returned data
        mysqli_free_result($movies_actors_result);
    }


    // Retrieve movies directors information
    function get_movies_directors($movie_id){
        global $connection;
        
        $movies_directors_query = "SELECT * FROM movies_directors INNER JOIN directors ON directors.Director_ID = movies_directors.Director_ID
        WHERE movies_directors.Movie_ID = {$movie_id}";
        
        // Perform database query
        $movies_directors_result = mysqli_query($connection, $movies_directors_query);

        // Test if there was a query error
        confirm_query($movies_directors_result);
        
        $count = 0;
        // Get data from query
        while($director = mysqli_fetch_assoc($movies_directors_result)) {
            // Insert commas after first result
            if ($count != 0) {
                echo ', '; 
            }
            
            $count++;
            echo $director["First_Name"] . " " . $director["Last_Name"];
        }
        // Release returned data
        mysqli_free_result($movies_directors_result);
    }


    // Retrieve movies producers information
    function get_movies_producers($movie_id){
        global $connection;
        
        $movies_producers_query = "SELECT * FROM movies_producers INNER JOIN producers ON producers.Producer_ID = movies_producers.Producer_ID
        WHERE movies_producers.Movie_ID = {$movie_id}";
        
        // Perform database query
        $movies_producers_result = mysqli_query($connection, $movies_producers_query);

        // Test if there was a query error
        confirm_query($movies_producers_result);
        
        $count = 0;
        // Get data from query
        while($producer = mysqli_fetch_assoc($movies_producers_result)) {
            // Insert commas after first result
            if ($count != 0) {
                echo ', '; 
            }
            
            $count++;
            echo $producer["First_Name"] . " " . $producer["Last_Name"];
        }
        // Release returned data
        mysqli_free_result($movies_producers_result);
    }


    // Redirect to new page
    function redirect_to($new_location) {
        header("Location: " . $new_location);
        exit;
    }

    // Check if field is blank
    function is_blank($value) {
        return !isset($value) || $value === "";
    }

    // Display records in multiple select input
    function multiselect($table_query) {
        global $connection;
        $query = $table_query;
        // Perform database query
        $result = mysqli_query($connection, $query);

        // Test if there was a query error
        confirm_query($result);

        $items = "";

        while ($item = mysqli_fetch_row($result)){
            $items .= "<option value='{$item[0]}'>{$item[1]} {$item[2]}</option>";
        }
        return $items;
        // Release returned data
        mysqli_free_result($result);
    }

    // Display records in single select input
    function singleselect($table_query) {
        global $connection;

        $query = $table_query;
        // Perform database query
        $result = mysqli_query($connection, $query);

        // Test if there was a query error
        confirm_query($result);

        $items = "<option value='none'></option>";

        while($item = mysqli_fetch_row($result)) {
            $items .= "<option value='{$item[0]}'>{$item[1]}</option>";
        }
        return $items;
        // Release returned data
        mysqli_free_result($result);
    }

?>
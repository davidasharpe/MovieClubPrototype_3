<?php 

    $page_title = "Add Movie";

    // Include header
    include('inc/header.php');
    // Include functions
    require_once('inc/functions.php');
    // Include connection details
    require_once('inc/connection.php');



    // Check if form was submitted
    if (isset($_POST['submit'])) {
        
        // Remove whitespace from beginning and end of values
        $title = trim($_POST["title"]);
        $release_date = trim($_POST["release_date"]);
        $genre = trim($_POST["genre"]);
        $running_time = trim($_POST["running_time"]);
        $distributor = trim($_POST["distributor"]);
        
        // Escape strings and filter input to prevent SQL injection
        $title = mysqli_real_escape_string($connection, $title);
        $release_date = filter_var($release_date, FILTER_SANITIZE_NUMBER_INT);
        $running_time = intval($running_time);
        
        if (isset($_POST["actors"])) { $actors = $_POST["actors"]; }
        if (isset($_POST["producers"])) { $producers = $_POST["producers"]; }
        if (isset($_POST["directors"])) { $directors = $_POST["directors"]; }
            
        $form_errors = false;
        
        // Check if fields are blank
        if (is_blank($title) || is_blank($release_date) || is_blank($running_time) || $genre == "none" || $distributor == "none" || !isset($actors) || !isset($directors) || !isset($producers)) {
            $blank_message = "<p class='error-msg'>All fields are required.</p>";
            $form_errors = true;
        }
        
        // Check if running time is a valid number
        if (isset($running_time) && !filter_var($running_time, FILTER_VALIDATE_INT)) {
            $number_message = "<p class='error-msg'>Running time is not a valid number.</p>";
            $form_errors = true;
        }
        
        // Check if date is in the correct format
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $release_date)) {
            $date_message = "<p class='error-msg'>Release date is not in the correct format (yyyy-mm-dd).</p>";
            $form_errors = true;
        }
        
        // Check if movie already exists
        if (record_exists("SELECT * FROM movies WHERE movies.Title = '{$title}' AND movies.Release_Date = '{$release_date}'")) {
            $exists_message = "<p class='error-msg'>This movie already exists in the database.</p>";
            $form_errors = true;
        }
        
        
        if ($form_errors == false) {
            
            $insert_movie = "INSERT INTO movies (Title, Release_Date, Running_Time, Genre_ID, Distributor_ID) VALUES ('{$title}', '{$release_date}', {$running_time}, {$genre}, {$distributor})";
            
            if (mysqli_query($connection, $insert_movie)) {
                $movie_id = mysqli_insert_id($connection);
            
                foreach ($actors as $actor) {
                    $insert_movie_actors = "INSERT INTO movies_actors (Movie_ID, Actor_ID) VALUES ({$movie_id}, {$actor})";
                    mysqli_query($connection, $insert_movie_actors);
                }
                
                foreach ($directors as $director) {
                    $insert_movie_directors = "INSERT INTO movies_directors (Movie_ID, Director_ID) VALUES ({$movie_id}, {$director})";
                    mysqli_query($connection, $insert_movie_directors);
                }
                
                foreach ($producers as $producer) {
                    $insert_movie_producers = "INSERT INTO movies_producers (Movie_ID, Producer_ID) VALUES ({$movie_id}, {$producer})";
                    mysqli_query($connection, $insert_movie_producers);
                }
                
                $success_message = "<p class='success-msg'>The movie has been successfully added to the database.</p>";
            }
            else {
                $error_message = "<p class='error-msg'>Something went wrong. Please try again.</p>";
            }
        }
    }
 
?>

<h1 class="center-block">Add Movies to the Database</h1>
<form name="add-movie" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
    
<!--    // PUT ERRORS HERE-->
    <?php if (isset($blank_message)) { echo $blank_message; } ?>
    <?php if (isset($number_message)) { echo $number_message; } ?>
    <?php if (isset($date_message)) { echo $date_message; } ?>
    <?php if (isset($exists_message)) { echo $exists_message; } ?>
    <?php if (isset($success_message)) { echo $success_message; } ?>
    <?php if (isset($error_message)) { echo $error_message; } ?>
    
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="e.g. The Conjuring" data-validation="required" value="<?php if (isset($title)) { echo $title; } ?>">
    </div>
    <div class="form-group">
        <label for="release_date">Release Date</label><small> (YYYY-MM-DD)</small>
        <input type="text" class="form-control" id="release_date" name="release_date" placeholder="yyyy-mm-dd" data-validation="required date" data-validation-format="yyyy-mm-dd" value="<?php if (isset($release_date)) { echo $release_date; } ?>">
    </div>
    <div class="form-group">
        <label for="genre">Genre</label>
        <select class="form-control" id="genre" name="genre" data-validation="required">
            <?php echo singleselect("SELECT * FROM genres"); ?>
        </select>
        <a href="add_genre.php" target="_blank">Add New Genre</a>
    </div>
    <div class="form-group">
        <label for="running_time">Running Time</label><small> (mins)</small>
        <input type="number" maxlength="3" class="form-control" id="running_time" name="running_time" placeholder="e.g. 121" data-validation="required number" value="<?php if (isset($running_time)) { echo $running_time; } ?>">
    </div>
    <div class="form-group">
        <label for="distributor">Distributor</label>
        <select class="form-control" id="distributor" name="distributor" data-validation="required">
            <?php echo singleselect("SELECT * FROM distributors"); ?>
        </select>
        <a href="add_distributor.php" target="_blank">Add New Distributor</a>
    </div>
    <div class="form-group">
        <label for="actors">Starring</label> <small>Ctrl+click to select multiple items</small>
        <select multiple class="form-control" id="actors" name="actors[]" data-validation="required">
            <?php echo multiselect("SELECT * FROM actors ORDER BY actors.Last_Name ASC"); ?>
        </select>
        <a href="add_actor.php" target="_blank">Add New Actor</a>
    </div>
    <div class="form-group">
        <label for="directors">Director(s)</label> <small>Ctrl+click to select multiple items</small>
        <select multiple class="form-control" id="directors" name="directors[]" data-validation="required">
            <?php echo multiselect("SELECT * FROM directors ORDER BY directors.Last_Name ASC"); ?>
        </select>
        <a href="add_director.php" target="_blank">Add New Director</a>
    </div>
    <div class="form-group">
        <label for="producers">Producer(s)</label> <small>Ctrl+click to select multiple items</small>
        <select multiple class="form-control" id="producers" name="producers[]" data-validation="required">
            <?php echo multiselect("SELECT * FROM producers ORDER BY producers.Last_Name ASC"); ?>
        </select>
        <a href="add_producer.php" target="_blank">Add New Producer</a>
    </div>
    
    <input type="submit" class="btn btn-primary" name="submit">
</form>


<?php
    // Close database connection
    mysqli_close($connection);
    // Include footer
    include('inc/footer.php');
?>
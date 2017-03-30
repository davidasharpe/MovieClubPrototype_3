<?php 

    $page_title = "Add Director";

    // Include header
    include('inc/header.php');
    // Include functions
    require_once('inc/functions.php');
    // Include connection details
    require_once('inc/connection.php');

    // Check if form was submitted
    if (isset($_POST['submit'])) {
        
        // Remove whitespace from beginning and end of values
        $first_name = trim($_POST["first_name"]);
        $last_name = trim($_POST["last_name"]);
        
        // Escape strings and filter input to prevent SQL injection
        $first_name = mysqli_real_escape_string($connection, $first_name);
        $last_name = mysqli_real_escape_string($connection, $last_name);
        
        // Check if fields are blank        
        if (is_blank($first_name) || is_blank($last_name)) {
            $message = "<p class='error-msg'>All fields are required.</p>";
        }
        // Check if director already exists
        else if (record_exists("SELECT * FROM directors WHERE directors.First_Name = '{$first_name}' AND directors.Last_Name = '{$last_name}'")) {
            $message = "<p class='error-msg'>This director already exists in the database.</p>";
        }
        // Else, insert into database
        else {
            $insert_query = "INSERT INTO directors (First_Name, Last_Name) VALUES ('{$first_name}', '{$last_name}')";
            
            if (mysqli_query($connection, $insert_query)){
                $message = "<p class='success-msg'>Record was successfully added to the database.</p>";
            } else {
                $message = "<p class='error-msg'>Something went wrong. Please try again.</p>";
            }
        }
    }
?>


<h1 class="center-block">Add Director to the Database</h1>
<form name="add-director" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    
    <?php if (isset($message)) { echo $message; } ?>
    
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="e.g. Quentin" data-validation="required" value="<?php if (isset($first_name)) { echo $first_name; } ?>">
    </div>
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="e.g. Tarantino" data-validation="required" value="<?php if (isset($last_name)) { echo $last_name; } ?>">
    </div>
    
    <input type="submit" class="btn btn-primary" name="submit">
</form>

<?php
    // Close database connection
    mysqli_close($connection);
    // Include footer
    include('inc/footer.php');
?>
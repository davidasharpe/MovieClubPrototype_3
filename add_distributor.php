<?php 

    $page_title = "Add Distributor";

    // Include header
    include('inc/header.php');
    // Include functions
    require_once('inc/functions.php');
    // Include connection details
    require_once('inc/connection.php');

    // Check if form was submitted
    if (isset($_POST['submit'])) {
        
        // Remove whitespace from beginning and end of values
        $name = trim($_POST["name"]);
        
        // Escape strings and filter input to prevent SQL injection
        $name = mysqli_real_escape_string($connection, $name);
        
        // Check if fields are blank        
        if (is_blank($name)) {
            $message = "<p class='error-msg'>Name is required.</p>";
        } 
        // Check if distributor already exists
        else if (record_exists("SELECT * FROM distributors WHERE distributors.Name LIKE '%{$name}%'")) {
            $message = "<p class='error-msg'>This distributor already exists in the database.</p>";
        }
        // Else, insert into database
        else {
            $insert_query = "INSERT INTO distributors (Name) VALUES ('{$name}')";
            
            if (mysqli_query($connection, $insert_query)){
                $message = "<p class='success-msg'>Record was successfully added to the database.</p>";
            } else {
                $message = "<p class='error-msg'>Something went wrong. Please try again.</p>";
            }
        }
    }
?>


<h1 class="center-block">Add Distributor to the Database</h1>
<form name="add-producer" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    
    <?php if (isset($message)) { echo $message; } ?>
    
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Walt Disney" data-validation="required" value="<?php if (isset($name)) { echo $name; } ?>">
    </div>
    
    <input type="submit" class="btn btn-primary" name="submit">
</form>

<?php
    // Close database connection
    mysqli_close($connection);
    // Include footer
    include('inc/footer.php');
?>
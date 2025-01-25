<?php
include('partials/_connection.php');
?>
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('location: login.php');  // Redirect to login if not logged in
    exit();  // Stop further script execution
}

// Handle student deletion when the 'delete_projects_form' button is pressed.
if (isset($_POST['delete_projects_form'])) {
    // Get the student ID from the form and sanitize it to prevent SQL injection.
    $id = mysqli_real_escape_string($conn, $_POST['delete_projects_form']);

    // Create an SQL query to delete the student from the database using the ID.
    $querry = "DELETE FROM `projects_form` WHERE id='$id' ";
    $querry_run = mysqli_query($conn, $querry); // Execute the query.

    // Check if the student was deleted successfully.
    if ($querry_run) {
        // If successful, set a success message in the session and redirect to the main page.
        $_SESSION['message'] = "CAAC Form Deleted Successfully";
        $_SESSION['message_type'] = 'success';
    } else {
        // If deletion failed, set a failure message in the session.
        $_SESSION['message'] = "CAAC Form Not Deleted Successfully";
        $_SESSION['message_type'] = 'error';
    }
    header("Location: caac_details.php");
    exit(0); // Exit the script after redirection to prevent further execution.
}

?>
<?php include 'partials/_header.php'; ?>

<div class="container bg-light mt-4 py-4">
    <?php include 'partials/_nav.php'; ?>
    <hr>
    <div class="card-body">
        <div class="card-header">
            <h4>Form Details
                <a href="caac.php" class="btn btn-primary float-end ms-2">Add Form</a>
                <!-- Button to add new students -->
                <!-- The "Back" button redirects to the home.php page (which might show a list of projects_form). -->
                <a href="home.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>

    </div>

    <!-- Display success or error messages from session -->
    <?php if (isset($_SESSION['message'])): ?>
    <?php
        // Determine message class based on the message type
        $messageClass = $_SESSION['message_type'] == 'success' ? 'alert-success' : 'alert-danger';
        ?>
    <div class="alert <?php echo $messageClass; ?> alert-dismissible fade show" role="alert">
        <strong><?php echo $_SESSION['message_type'] == 'success' ? 'Success!' : 'Error!'; ?></strong>
        <?php echo $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
        // Clear the session message and type after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>

    <table class="table table-bordered table-striped" id="myTable">
        <thead>
            <tr>
                <th>S No.</th>
                <th>Company</th>
                <th>Chassis Number</th>
                <th>Fleet Number</th>
                <th>Course</th>
                <th>Action</th> <!-- Column for action buttons (view, edit, delete) -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Query to fetch all students from the database
            $query = "SELECT * FROM `projects_form`";
            $query_run = mysqli_query($conn, $query);

            if (mysqli_num_rows($query_run) > 0) {
                $id = 0; // Initialize id counter
                while ($projects_form = mysqli_fetch_assoc($query_run)) {
                    $id++;  // Increment id for each row
                    ?>
            <tr>
                <td>
                    <?php echo $id; ?>
                    <!-- Display Serial Number -->
                </td>
                <td>
                    <?php echo $projects_form['company']; ?>
                    <!-- Display projects_form name -->
                </td>
                <td>
                    <?php echo $projects_form['chassis']; ?>
                    <!-- Display projects_form chassis -->
                </td>
                <td>
                    <?php echo $projects_form['fleet']; ?>
                    <!-- Display projects_form fleet number -->
                </td>
                <td>
                    <?php echo $projects_form['fdate']; ?>
                    <!-- Display projects_form form_date -->
                </td>
                <td>
                    <!-- Action buttons for viewing, editing, or deleting a projects_form -->
                    <a href="view_caac.php?id=<?= $projects_form['id']; ?>" class="btn btn-info btn-sm">View</a>
                    <a href="edit_caac.php?id=<?= $projects_form['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                    <form action="home.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_projects_form" value="<?= $projects_form['id']; ?>"
                            class="btn btn-danger btn-sm">Delete</button> <!-- Delete projects_form -->
                    </form>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<h5>No record found</h5>"; // Show message if no projects_form are found
            }
            ?>
        </tbody>
    </table>
</div>
</div>

<?php include 'partials/_footer.php'; ?>
<?php
include('partials/_connection.php');
?>
<?php
session_start();

// SQL query to count the total submissions in form_data
$sql_form_data = "SELECT COUNT(*) AS total FROM form_data";
$result_form_data = mysqli_query($conn, $sql_form_data);

// SQL query to count the total submissions in projects_form
$sql_projects_form = "SELECT COUNT(*) AS total FROM projects_form";
$result_projects_form = mysqli_query($conn, $sql_projects_form);

// Fetch the results for both queries
if ($result_form_data && $result_projects_form) {
    $total_submissions_form_data = mysqli_fetch_assoc($result_form_data)['total'];
    $total_submissions_projects_form = mysqli_fetch_assoc($result_projects_form)['total'];
} else {
    $total_submissions_form_data = 0;
    $total_submissions_projects_form = 0;
    echo "Error fetching total submissions: " . mysqli_error($conn);
}

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
    header("Location: home.php");
    exit(0); // Exit the script after redirection to prevent further execution.
}

?>
<?php include 'partials/_header.php'; ?>

<div class="container bg-light mt-4 py-4">
    <?php include 'partials/_nav.php'; ?>

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

    <div class="alert alert-success mt-4" role="alert">
        <h4 class="alert-heading">Well done <?php echo $_SESSION['name']; ?>!</h4>
        <p>Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer
            so that you can see how spacing within an alert works with this kind of content.</p>
        <hr>
        <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
    </div>
    <div class="row justify-content-evenly text-center">
        <!-- EDA Section -->
        <div class="col-6 mb-3">
            <a class="btn btn-success w-100" href="view_eda.php">
                EDA +
                <div class="mt-3">
                    <strong>EDA Total Submissions: </strong>
                    <?php echo htmlspecialchars($total_submissions_form_data); ?>
                </div>
            </a>
        </div>

        <!-- CAAC Section 1 -->
        <div class="col-6 mb-3">
            <a class="btn btn-success w-100" href="caac_details.php">
                CAAC +
                <div class="mt-3">
                    <strong>CAAC Total Submissions: </strong>
                    <?php echo htmlspecialchars($total_submissions_projects_form); ?>
                </div>
            </a>
        </div>

        <!-- ET Section 2 (Repeat as needed or change content) -->
        <div class="col-6 mb-3">
            <a class="btn btn-success w-100" href="#">
                ET +
                <div class="mt-3">
                    <strong>ET Total Submissions: </strong>0
                </div>
            </a>
        </div>

        <!-- S/ DR Section 3 (Repeat as needed or change content) -->
        <div class="col-6 mb-3">
            <a class="btn btn-success w-100" href="#">
                S/ DR +
                <div class="mt-3">
                    <strong>S/ DR Total Submissions: </strong> 0
                </div>
            </a>
        </div>
    </div>

    <hr>


</div>

<?php include 'partials/_footer.php'; ?>
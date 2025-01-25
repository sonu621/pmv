<?php
include('partials/_connection.php'); // Ensure $conn is a mysqli object
session_start();

// // Fetch the total number of form submissions
// $total_sql = "SELECT COUNT(*) AS total FROM form_data"; // SQL query to count the total submissions
// $total_result = mysqli_query($conn, $total_sql);

// // Check if query was successful
// if ($total_result) {
//     $total_row = mysqli_fetch_assoc($total_result);
//     $total_submissions = $total_row['total'];
// } else {
//     $total_submissions = 0; // If there's an error, default to 0
//     echo "Error fetching total submissions: " . mysqli_error($conn);
// }

// Fetch data from the database
$sql = "SELECT * FROM form_data"; // Fetch all form data from the form_data table
$result = mysqli_query($conn, $sql);

// Check for query errors
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

include('partials/_header.php'); // Include header for consistency
?>

<div class="container bg-light py-4 mt-5">
    <?php include('partials/_nav.php'); // Include navbar ?>

    <div class="card-header mt-2">
        <h4>Add the Form Details
            <a href="eda.php" class="btn btn-primary float-end ms-2">Add Form</a>
            <!-- Button to add new students -->
            <a href="home.php" class="btn btn-danger float-end">Back</a>
        </h4>
    </div>

    <!-- Display total number of submissions
    <div class="container btn btn-primary mt-3">
        <strong>Total Submissions: </strong> <?php echo $total_submissions; ?>
    </div> -->

    <!-- Table to display the form data -->
    <table class="table table-striped" id="myTable">
        <thead>
            <tr>
                <th scope="col">S No.</th>
                <th scope="col">Username</th>
                <th scope="col">Recipient Username</th>
                <th scope="col">Vanity URL</th>
                <th scope="col">Amount</th>
                <th scope="col">Server Username</th>
                <th scope="col">Text Area</th>
                <th scope="col">File Path</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $counter = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display each row of data
                    echo "<tr>";
                    echo "<td>" . $counter . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['recipient_username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['vanity_url']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['server_username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['text_area']) . "</td>";
                    echo "<td><a href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>View File</a></td>";
                    echo "<td><a href='editEda.php?id=" . $row['id'] . "' class='btn btn-primary'>View</a></td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>

<?php include('partials/_footer.php'); // Include footer ?>
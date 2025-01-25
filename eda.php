<?php
include('partials/_connection.php'); // Ensure $conn is a mysqli object

session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data and sanitize it
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $recipient_username = mysqli_real_escape_string($conn, $_POST['recipient_username']);
    $vanity_url = mysqli_real_escape_string($conn, $_POST['vanity_url']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $server_username = mysqli_real_escape_string($conn, $_POST['server_username']);
    $text_area = mysqli_real_escape_string($conn, $_POST['text_area']);

    // Handle file upload if present
    $file_path = '';
    if (isset($_FILES['formFileMultiple']) && $_FILES['formFileMultiple']['error'] == 0) {
        $upload_dir = 'uploads/';

        // Check if the upload directory exists, if not, create it
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);  // Create the directory if it doesn't exist
        }

        $file_name = basename($_FILES['formFileMultiple']['name']);
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file
        if (move_uploaded_file($_FILES['formFileMultiple']['tmp_name'], $file_path)) {
            // File uploaded successfully
        } else {
            echo "Error uploading file: " . $_FILES['formFileMultiple']['error'];
        }
    }

    // Prepare the SQL query using MySQLi
    $sql = "INSERT INTO `form_data` (username, recipient_username, vanity_url, amount, server_username, text_area, file_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "sssssss", $username, $recipient_username, $vanity_url, $amount, $server_username, $text_area, $file_path);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Form data submitted successfully!');</script>";
    } else {
        echo "Error submitting form data: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}
?>

<?php include('partials/_header.php'); ?>

<div class="container bg-light py-4 mt-5">
    <?php include('partials/_nav.php'); ?>

    <form class="row g-3 needs-validation m-4" novalidate method="POST" action="eda.php" enctype="multipart/form-data"
        id="edaForm">
        <div class="card-header">
            <h4>EDA Form Details
                <!-- The "Back" button redirects to the home.php page (which might show a list of projects_form). -->
                <a href="view_eda.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>

        <!-- Username input group -->
        <div class="input-group mb-3 position-relative">
            <span class="input-group-text" id="basic-addon1">@</span>
            <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                aria-describedby="basic-addon1" name="username" required>
            <div class="invalid-feedback">
                Please provide a valid username.
            </div>
        </div>

        <!-- Recipient's username input group -->
        <div class="input-group mb-3 position-relative">
            <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username"
                aria-describedby="basic-addon2" name="recipient_username" required>
            <span class="input-group-text" id="basic-addon2">@catgroup.net</span>
            <div class="invalid-feedback">
                Please provide a recipient's username.
            </div>
        </div>

        <!-- Vanity URL input group -->
        <div class="mb-3 position-relative">
            <label for="basic-url" class="form-label">Your vanity URL</label>
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3">https://example.com/users/</span>
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" name="vanity_url"
                    required>
            </div>
            <div class="invalid-feedback">
                Please provide your vanity URL.
            </div>
        </div>

        <!-- Amount input group -->
        <div class="input-group mb-3 position-relative">
            <span class="input-group-text">$</span>
            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="amount" required>
            <span class="input-group-text">.00</span>
            <div class="invalid-feedback">
                Please provide an amount.
            </div>
        </div>

        <!-- Username and server input group -->
        <div class="input-group mb-3 position-relative">
            <input type="text" class="form-control" placeholder="Username" aria-label="Username" name="server_username"
                required>
            <span class="input-group-text">@</span>
            <input type="text" class="form-control" placeholder="Server" aria-label="Server" name="server_username"
                required>
            <div class="invalid-feedback">
                Please provide both username and server.
            </div>
        </div>

        <!-- Textarea input group -->
        <div class="input-group mb-3 position-relative">
            <span class="input-group-text">With textarea</span>
            <textarea class="form-control" aria-label="With textarea" name="text_area" required></textarea>
            <div class="invalid-feedback">
                Please provide some text in the textarea.
            </div>
        </div>

        <!-- File input -->
        <div class="mb-3 position-relative">
            <label for="formFileMultiple" class="form-label">Multiple files input example</label>
            <input class="form-control" type="file" id="formFileMultiple" name="formFileMultiple" multiple required>
            <div class="invalid-feedback">
                Please select some files.
            </div>
        </div>

        <!-- Submit button -->
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>

    <script>
    // Append domain to recipient username before submitting the form
    document.getElementById("edaForm").addEventListener("submit", function(event) {
        // Get the value of the input and append the domain from the span
        var recipientUsername = document.getElementsByName("recipient_username")[0];
        var domain = document.getElementById("basic-addon2").textContent; // @catgroup.net

        // Only append the domain if it's not already present
        if (!recipientUsername.value.includes(domain)) {
            recipientUsername.value = recipientUsername.value + domain;
        }

        // Validate the form manually
        var form = this;
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });
    </script>

    <?php include('partials/_footer.php'); ?>
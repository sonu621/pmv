<?php
include('partials/_connection.php');
session_start();

// Check if the session variable is set, if not set a default value
if (!isset($_SESSION['name'])) {
    $_SESSION['name'] = 'Guest';  // Default to 'Guest' if 'name' is not set
}

// Initialize message variable
$message = '';

// Form processing logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form values
    $company = $_POST['company'];
    $branch = $_POST['branch'];
    $chassis = $_POST['chassis'];
    $fleet = $_POST['fleet'];
    $totalloss = isset($_POST['totalloss']) ? 1 : 0;
    $addition = isset($_POST['addition']) ? 1 : 0;
    $fdate = $_POST['fdate'];
    $delivery = $_POST['delivery'];
    $remarks = $_POST['remarks'];

    // Prepare and bind the query to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO projects_form (company, branch, chassis, fleet, totalloss, addition, fdate, delivery, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiissss", $company, $branch, $chassis, $fleet, $totalloss, $addition, $fdate, $delivery, $remarks);

    // Execute the query
    if ($stmt->execute()) {
        // Set success message
        $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Hello ' . $_SESSION['name'] . '!</strong> Your form details have been submitted successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        // Set error message if insertion fails
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> ' . $stmt->error . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }

    // Close the statement
    $stmt->close();
}
?>

<?php include 'partials/_header.php'; ?>

<div class="container bg-light py-4 mt-5">
    <?php include 'partials/_nav.php'; ?>

    <!-- Show success/error message below navbar -->
    <?php if (!empty($message)): ?>
    <?php echo $message; ?>
    <?php endif; ?>

    <div class="card-header m-4">
        <h4>CAAC Form Details
            <!-- The "Back" button redirects to the home.php page (which might show a list of projects_form). -->
            <a href="caac_details.php" class="btn btn-danger float-end">Back</a>
        </h4>
    </div>
    <div class="container mt-2">
        <form action="" method="post">
            <!-- Company and Branch Input Fields -->
            <div class="row">
                <div class="col">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control" id="company" name="company" placeholder="Company name"
                        required>
                </div>
                <div class="col">
                    <label for="branch" class="form-label">Branch</label>
                    <input type="text" class="form-control" id="branch" name="branch" placeholder="Branch name"
                        required>
                </div>
            </div>

            <!-- Chassis and Fleet Number Fields -->
            <div class="row mt-3">
                <div class="col">
                    <label for="chassis" class="form-label">Chassis/ Machine No:</label>
                    <input type="text" class="form-control" id="chassis" name="chassis" placeholder="Chassis no."
                        required>
                </div>
                <div class="col">
                    <label for="fleet" class="form-label">Fleet Number</label>
                    <input type="number" class="form-control" id="fleet" name="fleet"
                        placeholder="Enter your fleet number" required>
                </div>
            </div>

            <!-- Checkbox Inputs -->
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" value="" id="totalloss" name="totalloss">
                <label class="form-check-label" for="totalloss">
                    Total Loss
                </label>
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="" id="addition" name="addition">
                <label class="form-check-label" for="addition">
                    Addition
                </label>
            </div>

            <!-- Date Input -->
            <div class="form-group mt-3">
                <label for="dateInput">Date</label>
                <input type="date" class="form-control" id="dateInput" name="fdate" required>
            </div>

            <!-- Dropdown Select -->
            <div class="form-group mt-3">
                <label for="delivery">Delivery</label>
                <select class="form-control" id="delivery" name="delivery" required>
                    <option value="">Select an option</option>
                    <option value="FOB">FOB</option>
                    <option value="CF">C and F</option>
                    <option value="CIF">CIF</option>
                </select>
            </div>

            <!-- Textarea for Special Remarks -->
            <div class="form-group mt-3">
                <label for="remarks" class="form-label">Special Remarks, If Any:</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php include 'partials/_footer.php'; ?>
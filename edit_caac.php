<?php
include('partials/_connection.php');
?>
<?php
session_start();

// Handle CAAC update when the 'update_details' button is pressed.
if (isset($_POST['update_details'])) {
    // Get the CAAC ID and other updated data from the form and sanitize them.
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $chassis = mysqli_real_escape_string($conn, $_POST['chassis']);
    $fleet = mysqli_real_escape_string($conn, $_POST['fleet']);
    $totalloss = isset($_POST['totalloss']) ? 1 : 0;
    $addition = isset($_POST['addition']) ? 1 : 0;
    $fdate = $_POST['fdate'];
    $delivery = $_POST['delivery'];

    // Clean up the remarks input by replacing line breaks (\r\n, \r, \n) with a single space or remove
    $remarks = isset($_POST['remarks']) ? str_replace(["\r\n", "\r", "\n"], ' ', $_POST['remarks']) : '';

    // Prepare the SQL query to update the CAAC details in the database
    $stmt = $conn->prepare("UPDATE `projects_form` SET company = ?, branch = ?, chassis = ?, fleet = ?, totalloss = ?, addition = ?, fdate = ?, delivery = ?, remarks = ? WHERE id = ?");
    $stmt->bind_param("sssiissssi", $company, $branch, $chassis, $fleet, $totalloss, $addition, $fdate, $delivery, $remarks, $id);

    // Check if the update was successful
    if ($stmt->execute()) {
        // If successful, set a success message and redirect to the main page
        $_SESSION['message'] = "Form Updated Successfully";
        header("Location: edit_caac.php");
        exit(0);
    } else {
        // If update failed, set a failure message and redirect to the main page
        $_SESSION['message'] = "Form Not Updated";
        header("Location: index.php");
        exit(0);
    }
}
?>

<?php include 'partials/_header.php'; ?>

<div class="container bg-light py-4 mt-5">
    <?php include 'partials/_nav.php'; ?>

    <!-- Show success/error message below navbar -->
    <?php if (!empty($_SESSION['message'])): ?>
    <div class="alert alert-info"><?= $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Card header with a title and a back button that redirects to the main page (home.php). -->
    <div class="card-header">
        <h4>CAAC Form Edit
            <!-- The back button redirects to the main page (home.php) -->
            <a href="home.php" class="btn btn-danger float-end">Back</a>
        </h4>
    </div>

    <p>Here are your projects...</p>
    <div class="container mt-2">
        <?php
        // Check if there's a CAAC ID in the URL (e.g., edit_caac.php?id=1)
        if (isset($_GET['id'])) {
            // Get the CAAC ID from the URL and sanitize it to prevent SQL errors
            $id = mysqli_real_escape_string($conn, $_GET['id']);

            // Query the database to get the CAAC details based on the ID
            $query = "SELECT * FROM `projects_form` WHERE id = '$id' ";
            $query_run = mysqli_query($conn, $query);

            // Check if the CAAC exists in the database
            if (mysqli_num_rows($query_run) > 0) {
                // Fetch the CAAC data from the database
                $caac = mysqli_fetch_assoc($query_run);
                ?>
        <form action="" method="post">
            <!-- Hidden input field to carry the CAAC's ID along with the form submission -->
            <input type="hidden" name="id" value="<?= $caac['id'] ?>">

            <div class="row">
                <div class="col">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control" id="company" name="company" value="<?= $caac['company']; ?>"
                        required>
                </div>
                <div class="col">
                    <label for="branch" class="form-label">Branch</label>
                    <input type="text" class="form-control" id="branch" name="branch" value="<?= $caac['branch']; ?>"
                        required>
                </div>
            </div>

            <!-- Chassis and Fleet Number Fields -->
            <div class="row mt-3">
                <div class="col">
                    <label for="chassis" class="form-label">Chassis/ Machine No:</label>
                    <input type="text" class="form-control" id="chassis" name="chassis" value="<?= $caac['chassis']; ?>"
                        required>
                </div>
                <div class="col">
                    <label for="fleet" class="form-label">Fleet Number</label>
                    <input type="number" class="form-control" id="fleet" name="fleet" value="<?= $caac['fleet']; ?>"
                        required>
                </div>
            </div>

            <!-- Checkbox Inputs -->
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" value="1" id="totalloss" name="totalloss"
                    <?= $caac['totalloss'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="totalloss">
                    Total Loss
                </label>
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="1" id="addition" name="addition"
                    <?= $caac['addition'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="addition">
                    Addition
                </label>
            </div>

            <!-- Date Input -->
            <div class="form-group mt-3">
                <label for="fdate">Date</label>
                <input type="date" class="form-control" id="fdate" name="fdate" value="<?= $caac['fdate']; ?>" required>
            </div>

            <!-- Dropdown Select -->
            <div class="form-group mt-3">
                <label for="delivery">Delivery</label>
                <select class="form-control" id="delivery" name="delivery" required>
                    <option value="FOB" <?= $caac['delivery'] == 'FOB' ? 'selected' : ''; ?>>FOB</option>
                    <option value="CF" <?= $caac['delivery'] == 'CF' ? 'selected' : ''; ?>>C and F</option>
                    <option value="CIF" <?= $caac['delivery'] == 'CIF' ? 'selected' : ''; ?>>CIF</option>
                </select>
            </div>

            <!-- Textarea for Special Remarks -->
            <div class="form-group mt-3">
                <label for="remarks">Special Remarks, If Any:</label>
                <textarea class="form-control" id="remarks" name="remarks"
                    rows="3"><?= htmlspecialchars($caac['remarks']); ?></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-3">
                <button type="submit" name="update_details" class="btn btn-primary">Update Details</button>
            </div>
        </form>
        <?php
            } else {
                echo "<h4>No such Id Found</h4>";
            }
        }
        ?>
    </div>
</div>

<?php include 'partials/_footer.php'; ?>
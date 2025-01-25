<?php
include('partials/_connection.php');
?>
<?php
session_start();

?>

<?php include 'partials/_header.php'; ?>

<div class="container bg-light py-4 mt-5">
    <?php include 'partials/_nav.php'; ?>
    <div class="container mt-2">
        <form action="caac.php" method="post">
            <div class="card-header">
                <h4>CAAC Form Details
                    <!-- The "Back" button redirects to the caac_details.php page (which might show a list of projects_form). -->
                    <a href="caac_details.php" class="btn btn-danger float-end">Back</a>
                </h4>
            </div>
            <?php
            // Check if there's an "id" in the URL (e.g., view.php?id=1).
            if (isset($_GET['id'])) {
                // Get the projects_form ID from the URL and clean it up to avoid any SQL errors or security issues.
                $id = mysqli_real_escape_string($conn, $_GET['id']);

                // Query the database to fetch the student's details based on the ID.
                $query = "SELECT * FROM `projects_form` WHERE id = '$id' ";
                $query_run = mysqli_query($conn, $query);

                // Check if a student was found with the given ID.
                if (mysqli_num_rows($query_run) > 0) {
                    // Fetch the projects_form record from the database.
                    $projects_form = mysqli_fetch_array($query_run);
                    ?>
                    <!-- Company and Branch Input Fields -->
                    <div class="row">
                        <div class="col">
                            <label for="company" class="form-label">Company</label>
                            <p class="form-control" id="company" name="company"><?= $projects_form['company'] ?></p>
                        </div>
                        <div class="col">
                            <label for="branch" class="form-label">Branch</label>
                            <p class="form-control" id="branch" name="branch"><?= $projects_form['branch'] ?></p>
                        </div>
                    </div>

                    <!-- Chassis and Fleet Number Fields -->
                    <div class="row mt-3">
                        <div class="col">
                            <label for="chassis" class="form-label">Chassis/ Machine No:</label>
                            <p class="form-control" id="chassis" name="chassis"><?= $projects_form['chassis'] ?></p>
                        </div>
                        <div class="col">
                            <label for="fleet" class="form-label">Fleet Number</label>
                            <p class="form-control" id="fleet" name="fleet"><?= $projects_form['fleet'] ?></p>
                        </div>
                    </div>

                    <!-- Checkbox Inputs -->
                    <div class="form-check mt-3">
                        <label class="form-check-label" for="totalloss">
                            Total Loss:
                        </label>
                        <p id="totalloss" class="form-control">
                            <?php echo isset($projects_form['totalloss']) && $projects_form['totalloss'] ? 'Yes' : 'No'; ?>
                        </p>
                    </div>
                    <div class="form-check mt-2">
                        <label class="form-check-label" for="addition">
                            Addition:
                        </label>
                        <p id="addition" class="form-control">
                            <?php echo isset($projects_form['addition']) && $projects_form['addition'] ? 'Yes' : 'No'; ?>
                        </p>
                    </div>

                    <!-- Date Input -->
                    <div class="form-group mt-3">
                        <label for="dateInput">Date:</label>
                        <p class="form-control" id="dateInput">
                            <?= isset($projects_form['fdate']) ? $projects_form['fdate'] : 'No date available'; ?>
                        </p>
                    </div>

                    <!-- Dropdown Select -->
                    <div class="form-group mt-3">
                        <label for="delivery">Delivery:</label>
                        <p class="form-control" id="delivery">
                            <?php
                            if (isset($projects_form['delivery'])) {
                                switch ($projects_form['delivery']) {
                                    case 'option1':
                                        echo 'FOB';
                                        break;
                                    case 'option2':
                                        echo 'C and F';
                                        break;
                                    case 'option3':
                                        echo 'CIF';
                                        break;
                                    default:
                                        echo 'No delivery option selected';
                                }
                            } else {
                                echo 'No delivery option selected';
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Textarea for Special Remarks -->
                    <div class="form-group mt-3">
                        <label for="remarks" class="form-label">Special Remarks, If Any:</label>
                        <p class="form-control" id="remarks">
                            <?= isset($projects_form['remarks']) ? nl2br(htmlspecialchars(trim($projects_form['remarks']))) : 'No remarks available'; ?>
                        </p>
                    </div>
                    <!-- Submit Button -->
                    <div class="mt-3">
                        <!-- <button type="submit" class="btn btn-primary disable">Submit</button> -->
                    </div>
                </form>
                <?php
                } else {
                    // Display an error message if no projects_form is found with the given ID.
                    echo "<h4>No such Id Found</h4>";
                }
            }
            ?>
    </div>
</div>

<?php include 'partials/_footer.php'; ?>
<?php
include('partials/_connection.php');
$success = 0;
$user = 0;
session_start();

if (isset($_GET['page']) && $_GET['page'] == 'sign') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Retrieve the form data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $password = mysqli_real_escape_string($conn, $_POST['password']); // Corrected to $_POST['password']

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the user already exists
        $sqli = "SELECT * FROM `users` WHERE `email` = '$email'";

        $result = mysqli_query($conn, $sqli); // Execute the query

        if ($result) {
            $num = mysqli_num_rows($result);
            if ($num > 0) {
                // If the user already exists, set $user to 1
                $user = 1;
            } else {
                // SQL query to insert the data into the database
                $sql = "INSERT INTO `users` (`name`, `email`, `country`, `password`, `tstamp`) 
                        VALUES ('$name', '$email', '$country', '$hashed_password', CURRENT_TIMESTAMP())";

                $insert_result = mysqli_query($conn, $sql); // Execute insert query

                if ($insert_result) {
                    // If insertion is successful, set $success to 1
                    $success = 1;
                } else {
                    // If there is an error with the insert query
                    die(mysqli_error($conn));
                }
            }
        } else {
            // If the SELECT query fails
            die(mysqli_error($conn));
        }
    }
}
?>

<?php include 'partials/_header.php'; ?>

<!-- Full Page Centered Container -->
<div class="container d-flex justify-content-center align-items-center bg-light py-2">
    <div class="row justify-content-center align-items-center">
        <!-- Display Alerts Above the Main Content -->
        <div class="container mt-3">
            <div class="row justify-content-center">
                <?php if ($user): ?>
                    <div class="col-auto">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ohh no Sorry!</strong> User already exists.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="col-auto">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> You are successfully signed up!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Logo and Welcome Message Section -->
        <div class="col-12 text-center mb-4">
            <img src="https://erpp.caterp.net/catportal/images/companylogo.png" alt="Company Logo"
                class="img-fluid mb-3" style="max-width: 150px;" />
            <h2 class="fs-3 fw-bold mb-2">Welcome PMV 👋</h2>
            <h3 class="fs-5 text-muted">Enter your details to sign up and join the system.</h3>
        </div>

        <!-- Sign Up Form Section -->
        <div class="col-12 col-md-6">
            <form action="sign.php?page=sign" method="POST">

                <!-- Name field -->
                <div class="mb-3">
                    <label for="exampleInputName" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="exampleInputName" name="name"
                        placeholder="Enter your name" required />
                </div>

                <!-- Email field -->
                <div class="mb-3">
                    <label for="exampleInputEmail" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="exampleInputEmail" name="email"
                        placeholder="Enter your email" required />
                </div>

                <!-- Country dropdown -->
                <div class="mb-3">
                    <label for="country" class="form-label">Choose your Country</label>
                    <select id="country" name="country" class="form-select" required>
                        <option value="">Select Country</option>
                        <option value="KSA">[KSA] Kingdom of Saudi Arabia</option>
                        <option value="LEB">[LEB] Lebanon</option>
                        <option value="NGR">[NGR] Nigeria</option>
                        <option value="QTR">[QTR] Qatar</option>
                        <option value="UAE">[UAE] United Arab Emirates</option>
                    </select>
                </div>

                <!-- Password field -->
                <div class="mb-3">
                    <label for="exampleInputPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword" name="password"
                        placeholder="Enter your password" required />
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
            </form>
            <!-- Link to Login Page -->
            <p class="mt-3">
                Already have an account? <a class="text-danger" href="login.php">Login here</a>
            </p>
        </div>
    </div>
</div>

<!-- Footer Section -->
<?php include 'partials/_footer.php'; ?>
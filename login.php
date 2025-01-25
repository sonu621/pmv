<?php
include('partials/_connection.php'); // Ensure proper DB connection

$login = false;
$invalid = 0;

if (isset($_GET['page']) && $_GET['page'] == 'login') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Check if form is submitted

        $email = $_POST['email'];
        $password = $_POST['password'];

        // Query to find user by email
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);

        // Step 2: Check if the username exists
        if ($num == 1) {

            // If the username exists, fetch the user data
            while ($row = mysqli_fetch_array($result)) {
                // Step 3: Verify the entered password with the hashed password in the database
                if (password_verify($password, $row['password'])) {
                    // If password matches, login is successful
                    $login = true;

                    // Start a session and store the login state
                    session_start();
                    $_SESSION['loggedin'] = true; // User is logged in
                    $_SESSION['email'] = $row['email']; // Store the username in the session
                    $_SESSION['name'] = $row['name'];

                    // Redirect to the welcome page (login successful)
                    header("location: home.php");
                    exit(); // Stop further script execution
                }
            }
        } else {
            // Invalid credentials
            $invalid = 1;
        }
    }
}
?>

<?php include 'partials/_header.php'; ?>



<div class="container text-center bg-light d-flex flex-column justify-content-center align-items-center py-4">
    <?php
    if ($invalid) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error </strong> Invalid credentials!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    ?>

    <?php
    if ($login) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success </strong> You are successfully signed up!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    ?>
    <!-- Logo and Welcome Message Section -->
    <div class="row-col mb-4">
        <div class="col">
            <img src="https://erpp.caterp.net/catportal/images/companylogo.png" alt="Company Logo" class="img-fluid"
                style="width: 150px; height: 150px" />
        </div>
        <div class="col">
            <h2 class="fs-4 fw-bold">Welcome PMV 👋</h2>
        </div>
        <div class="col">
            <h3 class="fs-6 fw-normal">
                Enter your ID and password to access your dashboard.
            </h3>
        </div>
    </div>

    <!-- Form Section -->
    <form action="login.php?page=login" method="POST">
        <!-- Email field -->
        <div class="mb-3 row">
            <label for="exampleInputEmail" class="col-sm-3 col-form-label text-start">Email address</label>
            <div class="col-12 col-sm-9">
                <input type="email" class="form-control" id="exampleInputEmail" name="email"
                    placeholder="Enter your email address" required />
            </div>
        </div>
        <!-- Password field -->
        <div class="mb-3 row">
            <label for="exampleInputPassword" class="col-sm-3 col-form-label text-start">Password</label>
            <div class="col-12 col-sm-9">
                <input type="password" class="form-control" id="exampleInputPassword" name="password"
                    placeholder="Enter your password" required />
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <p>Don't have an account? <a href="sign.php?page=signin">Sign Up</a></p>

    <?php include 'partials/_footer.php'; ?>
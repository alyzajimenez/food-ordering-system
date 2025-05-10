<?php
// Start the session
session_start();

// Include database connection and utility functions
include('../includes/db.php');
include('../includes/functions.php');

// Check if the user is already logged in (for redirects)
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to the appropriate dashboard
    header('Location: ../customer/index.php');
    exit();
}

$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if the email already exists in the database
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email already exists.";
        } else {
            // Insert new user into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $role = 'customer'; // Default role for registration

            $insert_query = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("sss", $email, $hashed_password, $role);

            if ($insert_stmt->execute()) {
                // Registration successful, redirect to login page
                header('Location: login.php');
                exit();
            } else {
                $error_message = "Error during registration. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="register-container">
        <h2>Customer Registration</h2>

        <?php if ($error_message) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <form action="register.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>

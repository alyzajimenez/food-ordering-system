<?php
session_start();  // Start the session

// Include database connection and functions
include('../includes/db.php');
include('../includes/functions.php');

// Check if the user is already logged in (for redirects)
if (isset($_SESSION['user_id'])) {
    // Redirect to the appropriate dashboard based on role
    if ($_SESSION['role'] == 'admin') {
        header('Location: ../admin/index.php');
    } else {
        header('Location: ../customer/index.php');
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hardcode the role as 'admin'
    $role = 'admin';

    // Authenticate user based on role
    $user = authenticate_user($email, $password, $role);

    if ($user) {
        // Set session variables for user
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect to admin dashboard
        header('Location: ../admin/index.php');
        exit();
    } else {
        $error_message = "Invalid email or password, or you are not authorized to log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script>
        // Function to display an alert if there's an error message
        function showError(message) {
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body onload="showError('<?php echo isset($error_message) ? addslashes($error_message) : ''; ?>')">

    <div class="login-container">
        <h2>Admin Login</h2>

        <!-- Login form -->
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="../customer/register.php">Register here</a></p>
    </div>

</body>
</html>

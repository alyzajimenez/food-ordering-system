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
    $role = $_POST['role'];  // 'customer' or 'admin'

    // Authenticate user based on role
    $user = authenticate_user($email, $password, $role);

    if ($user) {
        // Set session variables for user
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header('Location: ../admin/index.php');  // Admin dashboard
        } else {
            header('Location: ../customer/index.php');  // Customer dashboard
        }
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <?php if (isset($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Login form -->
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="role">Role</label>
            <select name="role" required>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="../customer/register.php">Register here</a></p>
    </div>

</body>
</html>

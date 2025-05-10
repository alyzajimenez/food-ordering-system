<?php
// Start the session
session_start();

// Include database connection and functions
include('../includes/db.php');

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

$error_message = ''; // Error message initialization

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];  // 'customer' or 'admin'

    // Debugging: Print the POST data to see what's being sent
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Role: " . htmlspecialchars($role) . "<br>";

    // Prepare and execute query to find the user by email and role
    $query = "SELECT * FROM users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists and verify the password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Debugging: Check the user data fetched
        echo "User Found: <br>";
        print_r($user);
        echo "<br>";

        // Check if the password is correct
        if ($password == $user['password']) {  // Not using hashing for simplicity
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the appropriate dashboard
            if ($user['role'] == 'admin') {
                header('Location: ../admin/index.php');  // Admin dashboard
            } else {
                header('Location: ../customer/index.php');  // Customer dashboard
            }
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No account found with that email and role combination.";
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

        <!-- Display error message if any -->
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

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

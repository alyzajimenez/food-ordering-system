<?php
session_start();

include('../includes/db.php');

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: ../admin/index.php');
    } else {
        header('Location: ../customer/index.php');  // Customer dashboard
    }
    exit();
}

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            if ($user['role'] == 'customer') {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                header('Location: ../customer/index.php');  // Customer dashboard
                exit();
            } else {
                $_SESSION['error_message'] = 'Admins cannot log in from this page. Please use the admin login page.';
            }
        } else {
            $_SESSION['error_message'] = 'Incorrect password.';
        }
    } else {
        $_SESSION['error_message'] = 'No account found with that email.';
    }

    header('Location: login.php');
    exit();
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

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success" role="alert">
                Registration successful! Please log in.
            </div>
        <?php endif; ?>

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

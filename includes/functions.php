<?php
// functions.php - Utility functions like authentication, etc.

function authenticate_user($email, $password, $role) {
    global $conn;
    $email = mysqli_real_escape_string($conn, $email);  // Prevent SQL injection
    $password = mysqli_real_escape_string($conn, $password);  // Prevent SQL injection

    // Query to check if the user exists and verify the password
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = '$role' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password (assuming plain text for now)
        if ($user['password'] === $password) {
            return $user;  // Return user data on success
        } else {
            return false;  // Incorrect password
        }
    } else {
        return false;  // User not found
    }
}
?>

<?php
session_start();  // Start the session

include('../includes/db.php');
include('../includes/functions.php');

if (isset($_SESSION['user_id'])) {
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
    $role = 'admin';

    $user = authenticate_user($email, $password, $role);

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

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
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo-container img {
            width: 90px;
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 95%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #fcb69f;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background-color: #e67e22;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #cf711f;
        }

        p {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        a {
            color: #e67e22;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function showError(message) {
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body onload="showError('<?php echo isset($error_message) ? addslashes($error_message) : ''; ?>')">

    <div class="login-container">
        <div class="logo-container">
            <img src="../assets/images/logo.png" alt="Logo"> <!-- Adjust logo path -->
        </div>

        <h2>Admin Login</h2>

        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

    </div>

</body>
</html>

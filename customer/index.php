<?php
session_start();

include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
/* Global Styles */
body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    color: #333;
    display: flex;
    flex-direction: row;
    overflow-x: hidden;
    height: 100vh;
    background-size: cover;
    background-position: center;
    background-image: url('../assets/images/orange.jpg'); 
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #d65108;
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    padding: 20px 0;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar .logo {
    width: 150px;
    margin: 0 auto;
    display: block;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin: 20px 0;
    text-align: center;
}

.sidebar ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 18px;
    display: block;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #b54507;
}


.main-content {
    display: none; 
}

    </style>
</head>
<body>

 <div class="container">
    <nav class="sidebar">
        <a href="index.php">
            <img src="../assets/images/logo.png" alt="Food Tiger Logo" class="logo">
        </a>
            <ul>
             <li>
                <a href="food-menu.php">
                    <i class="fas fa-utensils nav-icon"></i> 
                    Food Menu
                </a>
            </li>
            <li>
                <a href="order-history.php">
                    <i class="fas fa-history nav-icon"></i> 
                    Order History
                </a>
            </li>
            <li>
                <a href="cart.php">
                    <i class="fas fa-shopping-cart nav-icon"></i> 
                    Cart
                </a>
            </li>
            <li>
                <a href="explore-recipes.php">
                    <i class="fas fa-book-open nav-icon"></i>  
                    Explore Recipes
                </a>
            </li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
</body>
</html>

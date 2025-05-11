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
    <link rel="stylesheet" href="../assets/style.css">
    <style>
/* Global Styles */
body {
    background-size: cover;
    background-position: center;
    min-height: 100vh;
    margin: 0;
    font-family: 'Roboto', sans-serif;
    color: #333;
    display: flex;
    flex-direction: row; 
    overflow-x: hidden; 

/* Sidebar */
.sidebar {
    width: 250px; 
    background-color: #2c3e50;
    box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.1);
    position: fixed;
    height: 100vh; 
    top: 0;
    left: 0;
    z-index: 10; 
    text-align: center;
}

/* Logo Styling */
.sidebar .logo {
    width: 150px; 
    height: auto;
    margin-bottom: 30px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

/* Sidebar Links */
.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin: 20px 0;
    text-align: center;
}

.sidebar ul li a {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 18px;
    display: block;
    padding: 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #3498db;
}

/* Main Content */
.main-content {
    margin-left: 250px; 
    padding: 30px;
    width: calc(100% - 270px); 
    min-height: 100vh; 
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

/* Main Header */
header h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #34495e;
}

/* Main Message Section */
#main-message h3 {
    font-size: 36px;
    color: #2c3e50;
    margin-top: 20px;
}

#main-message p {
    font-size: 18px;
    color: #7f8c8d;
    margin-top: 10px;
    line-height: 1.6;
}

/* Image Styling */
.image-item {
    width: 100%;
    max-width: 400px;
    height: auto;
    object-fit: cover;
    margin-top: 20px;
}

/* Adjustments for Small Screens (Mobile Devices) */
@media (max-width: 768px) {
    body {
        flex-direction: column; 
    }

    .sidebar {
        width: 100%; 
        position: static; 
        border-radius: 0;
        box-shadow: none;
    }

    .main-content {
        margin-left: 0; 
        width: 100%; 
        padding: 20px;
    }
}

    </style>
</head>
<body>

    <div class="container">
        <nav class="sidebar">
            <img src="../assets/images/logo.png" alt="Food Tiger Logo" class="logo">
            <ul>
             <li>
                <a href="#menu">
                    <i class="fas fa-utensils nav-icon"></i> <
                    Food Menu
                </a>
            </li>
            <li>
                <a href="#order-history">
                    <i class="fas fa-history nav-icon"></i> 
                    Order History
                </a>
            </li>
            <li>
                <a href="#cart">
                    <i class="fas fa-shopping-cart nav-icon"></i> 
                    Cart
                </a>
            </li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

 <main class="main-content">
    <header>
        <h2>Welcome, <?php echo htmlspecialchars($user['email']); ?>!</h2>
    </header>

    <section id="main-message">
        <h3>Hungry? Let the Tiger Deliver!</h3>
        <p>Browse menus, order in minutes, and enjoy food delivered right to your door.</p>
    </section>
    
     <!-- Image Section -->
    <section id="image-gallery">
        <div class="image-container">
            <img src="../assets/images/burger.png" alt="Image 1" class="image-item">
            <img src="../assets/images/pizza.png" alt="Image 2" class="image-item">
            <img src="../assets/images/fries.png" alt="Image 3" class="image-item">
            <img src="../assets/images/chicken.png" alt="Image 4" class="image-item">
        </div>
    </section>
</main>

    </div>

</body>
</html>

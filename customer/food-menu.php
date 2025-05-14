<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $stmt = $conn->prepare("SELECT * FROM menu WHERE menu_id = ?");
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $menuItem = $result->fetch_assoc();

    if ($menuItem) {
        $_SESSION['cart'][$menu_id] = [
            'menu_id' => $menu_id,
            'menu_name' => $menuItem['name'],
            'price' => $menuItem['price'],
            'quantity' => ($_SESSION['cart'][$menu_id]['quantity'] ?? 0) + $quantity
        ];
    }

    header("Location: food-menu.php");
    exit;
}

$query = "SELECT * FROM menu WHERE availability = 1";
$result = $conn->query($query);

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu</title>
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
}
/* Sidebar */
.sidebar {
    width: 250px; 
    background-color:rgb(119, 79, 19);
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
.meal-details {
    display: none;
    position: fixed;
    top: 10%;
    left: 10%;
    width: 80%;
    background: #fff;
    padding: 20px;
    border: 2px solid #ccc;
    z-index: 1000;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.meal-details.showRecipe {
    display: block;
}
    </style>
</head>
<body class="p-4">
<div class="container">
    <nav class="sidebar">
        <a href="index.php">
            <img src="../assets/images/logo.png" alt="Food Tiger Logo" class="logo">
        </a>
        <ul>
            <li><a href="food-menu.php">Food Menu</a></li>
            <li><a href="order-history.php">Order History</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="explore-recipes.php">Explore Recipes</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <h2>Our Restaurant's Food Menu</h2>

        <div class="menu-items">
            <?php if (count($items) > 0): ?>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($items as $item): ?>
                        <li style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 10px;">
                            <form method="POST" style="margin: 0;">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p><?= htmlspecialchars($item['description']) ?></p>
                                <p>Price: ₱<?= number_format($item['price'], 2) ?></p>
                                <p>Category: <?= htmlspecialchars($item['category']) ?></p>
                                <input type="hidden" name="menu_id" value="<?= $item['menu_id'] ?>">
                                <label>Quantity:
                                    <input type="number" name="quantity" value="1" min="1" style="width: 60px;">
                                </label>
                                <br><br>
                                <button type="submit">Add to Cart</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No menu items available at the moment.</p>
            <?php endif; ?>
        </div>

        <a href="cart.php" style="display: inline-block; margin-top: 20px; text-decoration: none; font-weight: bold;">
            View Cart
        </a>
    </main>
</div>
</body>
</html>
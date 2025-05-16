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
            'image' => $menuItem['image'],
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
/* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
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

/* Main Content */
.main-content {
    margin-left: 250px;
    padding: 30px;
    width: calc(100% - 270px);
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 32px;
    color: #333;
    margin-bottom: 20px;
}

.menu-items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.meal-item {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.3s ease-in-out;
}

.meal-item:hover {
    transform: translateY(-10px);
}

.meal-img img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}



.meal-info h3 {
    font-size: 1.6rem;
    margin-bottom: 10px;
    color: #333;
}

.meal-info p {
    color: #777;
    font-size: 1rem;
    margin-bottom: 15px;
}

.meal-info .price {
    font-size: 1.4rem;
    font-weight: 500;
    color: #d65108;
    margin-bottom: 15px;
}

.meal-info .category {
    background-color: #f1f1f1;
    padding: 5px 10px;
    font-size: 0.9rem;
    border-radius: 20px;
    margin-bottom: 20px;
    color: #333;
}

.meal-info button {
    background-color: #d65108;
    color: white;
    border: none;
    padding: 10px 10px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 20px;
    transition: background-color 0.3s ease;
    align-self: center;  /* Ensures button is centered */
}

.meal-info button:hover {
    background-color: #b54507;
}

/* Footer Link */
.view-cart {
    text-align: center;
    margin-top: 30px;
}

.view-cart a {
    text-decoration: none;
    font-size: 1.2rem;
    color: #d65108;
    font-weight: 600;
}

/* Responsive Styling */
@media screen and (max-width: 768px) {
    .meal-info h3 {
        font-size: 1.4rem;
    }

    .meal-info p {
        font-size: 0.9rem;
    }

    .meal-info .price {
        font-size: 1.2rem;
    }

    .meal-info button {
        font-size: 1rem;
        padding: 8px 18px;
    }
}

    </style>
</head>
<body>
    <div class="sidebar">
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
    </div>

    <div class="main-content">
        <h2>Our Restaurant's Food Menu</h2>

        <div class="menu-items">
            <?php if (count($items) > 0): ?>
                <?php foreach ($items as $item): ?>
                    <div class="meal-item">
                        <form method="POST">
                            <?php if (!empty($item['image'])): ?>
                                <div class="meal-img">
                                    <img src="../api/assets/menu/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </div>
                            <?php endif; ?>
                            <div class="meal-info">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p><?= htmlspecialchars($item['description']) ?></p>
                                <p class="price">₱<?= number_format($item['price'], 2) ?></p>
                                <p class="category"><?= htmlspecialchars($item['category']) ?></p>
                                <input type="hidden" name="menu_id" value="<?= $item['menu_id'] ?>">
                                <label for="quantity">Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1" style="width: 60px; margin-bottom: 10px;">
                                <br>
                                <button type="submit">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No menu items available at the moment.</p>
            <?php endif; ?>
        </div>

        <div class="view-cart">
            <a href="cart.php">View Cart</a>
        </div>
    </div>
</body>
</html>

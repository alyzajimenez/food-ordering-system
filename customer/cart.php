<?php
session_start();

include('../includes/db.php');

// Redirect if not logged in or not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// Fetch user details
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../assets/style.css">
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
            background-color: rgb(119, 79, 19);
            box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 10;
            text-align: center;
        }

        .sidebar .logo {
            width: 150px;
            height: auto;
            margin: 30px auto;
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
            width: 100%;
            min-height: 100vh;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        header h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #34495e;
        }

        .cart-container {
            padding: 30px;
        }

        .cart-item {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .cart-item-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }

        .cart-item-details {
            flex-grow: 1;
        }

        .cart-item-quantity {
            width: 50px;
        }

        .cart-total {
            font-size: 1.5em;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        /* Responsive Design */
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
        <!-- Sidebar -->
        <nav class="sidebar">
            <a href="index.php">
                <img src="../assets/images/logo.png" alt="Food Tiger Logo" class="logo">
            </a>
            <ul>
                <li><a href="food-menu.php"><i class="fas fa-utensils nav-icon"></i> Food Menu</a></li>
                <li><a href="#order-history"><i class="fas fa-history nav-icon"></i> Order History</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart nav-icon"></i> Cart</a></li>
                <li><a href="explore-recipes.php"><i class="fas fa-book-open nav-icon"></i> Explore Recipes</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h2>Welcome, <?php echo htmlspecialchars($user['email']); ?>!</h2>
            </header>

            <section class="cart-container">
                <h2>Your Cart</h2>
                <?php if (empty($_SESSION['cart'])): ?>
                    <p>Your cart is empty</p>
                <?php else: ?>
                    <?php $total = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-img">
                            <div class="cart-item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>Quantity: 
                                    <input type="number" class="cart-item-quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                </p>
                                <p>Price: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            </div>
                            <button class="remove-from-cart" data-id="<?php echo $item['id']; ?>">Remove</button>
                        </div>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>

                    <div class="cart-total">
                        Total: $<?php echo number_format($total, 2); ?>
                    </div>

                    <button id="checkout-btn">Proceed to Checkout</button>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script>
        // JavaScript for cart functionality
        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function() {
                const mealId = this.getAttribute('data-id');
                fetch('remove-from-cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ meal_id: mealId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            });
        });
    </script>
</body>
</html>

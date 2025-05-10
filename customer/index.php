<?php
// Start the session
session_start();

// Include the database connection and functions
include('../includes/db.php');
include('../includes/functions.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    // If the user is not logged in or is not a customer, redirect to login page
    header('Location: login.php');
    exit();
}

// Get the logged-in customer's information
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// Optionally, you can fetch additional user data from the database if necessary
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
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['email']); ?>!</h2>

        <!-- Customer Menu -->
        <div class="menu">
            <h3>Food Menu</h3>
            <p>Browse our menu and place an order.</p>

            <?php
            // Fetch the menu items from the database
            $menu_query = "SELECT * FROM menu";  // Assuming you have a `menu` table
            $menu_result = $conn->query($menu_query);

            if ($menu_result->num_rows > 0) {
                echo "<ul>";
                while ($menu_item = $menu_result->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>" . htmlspecialchars($menu_item['item_name']) . "</strong><br>";
                    echo "$" . number_format($menu_item['price'], 2) . "<br>";
                    echo "<a href='order.php?item_id=" . $menu_item['item_id'] . "'>Order Now</a>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No menu items available at the moment.</p>";
            }
            ?>

        </div>

        <!-- Order History -->
        <div class="order-history">
            <h3>Your Order History</h3>
            <p>Check the status of your past orders.</p>

            <?php
            // Fetch past orders from the database
            $orders_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
            $orders_stmt = $conn->prepare($orders_query);
            $orders_stmt->bind_param("i", $user_id);
            $orders_stmt->execute();
            $orders_result = $orders_stmt->get_result();

            if ($orders_result->num_rows > 0) {
                echo "<ul>";
                while ($order = $orders_result->fetch_assoc()) {
                    echo "<li>";
                    echo "Order #" . $order['order_id'] . " - " . $order['status'] . "<br>";
                    echo "Placed on: " . $order['order_date'] . "<br>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>You have no past orders.</p>";
            }
            ?>

        </div>

        <!-- Logout Button -->
        <a href="logout.php" class="logout-btn">Logout</a>

    </div>

</body>
</html>

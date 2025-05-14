<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    $customer_name = $_POST['customer_name'];
    $address = $_POST['address'];

    $cart = $_SESSION['cart'];
    $total_price = array_sum(array_map(function($item) {
        return $item['price'] * $item['quantity'];
    }, $cart));

    //orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, customer_name, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $user_id, $total_price, $customer_name, $address);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    //order items
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt_item->bind_param("iiid", $order_id, $item['menu_id'], $item['quantity'], $item['price']);
        $stmt_item->execute();
    }

    unset($_SESSION['cart']);

    echo "<h2>Order placed successfully!</h2><p>Order ID: $order_id</p><a href='food-menu.php'>Back to Menu</a>";
} else {
    header("Location: cart.php");
    exit;
}
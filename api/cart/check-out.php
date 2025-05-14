<?php
session_start();
include('../includes/db.php');

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

    $_SESSION['user_id'];
    
    $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : null;
    $address = isset($_POST['address']) ? $_POST['address'] : null;

    $total_price = 0;

    //total price
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

    //insert in orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, customer_name, address) VALUES (?, ?, 'pending', ?, ?)");
    $stmt->bind_param("idss", $user_id, $total_price, $customer_name, $address);
    $stmt->execute();

    $order_id = $stmt->insert_id;

    //insert in order_items
    foreach ($_SESSION['cart'] as $item) {
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_item->bind_param("iiid", $order_id, $item['menu_id'], $item['quantity'], $item['price']);
        $stmt_item->execute();
    }

    //clear
    unset($_SESSION['cart']);

    echo "Order placed successfully!";
    echo "<br><a href='food-menu.php'>Back to Menu</a>";

} else {
    echo "Your cart is empty.";
}
?>
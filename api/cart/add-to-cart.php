<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_id = $_POST['menu_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $item = [
        'menu_id' => $menu_id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['menu_id'] == $menu_id) {
            $cartItem['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = $item;
    }

    header('Location: cart.php');
    exit();
}
?>
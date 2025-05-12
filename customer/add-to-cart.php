<?php
session_start();

header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate data
if (!isset($data['meal_id']) || !isset($data['meal_name']) || !isset($data['meal_img'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid meal data']);
    exit;
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if item already in cart
$itemExists = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $data['meal_id']) {
        $item['quantity'] += 1;
        $itemExists = true;
        break;
    }
}

// If not exists, add new item
if (!$itemExists) {
    $_SESSION['cart'][] = [
        'id' => $data['meal_id'],
        'name' => $data['meal_name'],
        'img' => $data['meal_img'],
        'quantity' => 1,
        'price' => 10.99 // You might want to get this from a database
    ];
}

echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
?>
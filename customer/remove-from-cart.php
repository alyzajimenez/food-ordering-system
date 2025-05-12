<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['meal_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid meal ID']);
    exit;
}

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $data['meal_id']) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            echo json_encode(['success' => true]);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
?>
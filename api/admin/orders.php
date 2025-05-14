<?php
require_once '../../includes/db.php';
header("Content-Type: application/json");

//view orders
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $orders = [];
    $result = $conn->query("SELECT * FROM orders WHERE status != 'completed' ORDER BY created_at DESC");

    while ($order = $result->fetch_assoc()) {
        $order_id = $order['order_id'];

        //get order items based on order
        $stmt = $conn->prepare("
            SELECT oi.order_item_id, oi.menu_id, m.name, oi.quantity, oi.price 
            FROM order_items oi 
            JOIN menu m ON oi.menu_id = m.menu_id 
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items_result = $stmt->get_result();

        $items = [];
        while ($item = $items_result->fetch_assoc()) {
            $items[] = $item;
        }

        $order['items'] = $items;
        $orders[] = $order;
    }

    echo json_encode(['orders' => $orders]);
}

//update order status
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->bind_param("si", $data['status'], $data['order_id']);
    $stmt->execute();
    echo json_encode(['message' => 'Order status updated']);
}
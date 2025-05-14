<?php
require_once '../../includes/db.php';
header("Content-Type: application/json");

$sql = "SELECT o.order_id, o.user_id, o.created_at,
               GROUP_CONCAT(oi.name SEPARATOR ', ') AS items,
               SUM(oi.price * oi.quantity) AS total
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.status = 'completed'
        GROUP BY o.order_id";

$result = $conn->query($sql);
$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}
echo json_encode(['history' => $history]);
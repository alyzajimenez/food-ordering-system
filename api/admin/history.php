<?php
require_once '../../includes/db.php';
header("Content-Type: application/json");

$sql = "SELECT o.order_id AS id, o.user_id, o.customer_name, u.email AS email, o.status, o.created_at, o.address,
               GROUP_CONCAT(m.name SEPARATOR ', ') AS items,
               SUM(oi.price * oi.quantity) AS total, oi.quantity AS quantity
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN menu m ON oi.menu_id = m.menu_id
        WHERE o.status = 'completed'
        GROUP BY o.order_id";

$result = $conn->query($sql);
$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}
echo json_encode(['history' => $history]);
?>
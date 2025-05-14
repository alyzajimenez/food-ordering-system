<?php
session_start();
include('../includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders of the logged-in user
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .order { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
        .order h3 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Your Order History</h2>

    <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="order">
            <h3>Order #<?= $order['order_id'] ?> - <?= $order['created_at'] ?></h3>
            <p>Status: <strong><?= htmlspecialchars($order['status']) ?></strong></p>

            <?php
            $order_id = $order['order_id'];
            $itemStmt = $conn->prepare("
                SELECT oi.*, m.name 
                FROM order_items oi 
                JOIN menu m ON oi.menu_id = m.menu_id 
                WHERE oi.order_id = ?
            ");
            $itemStmt->bind_param("i", $order_id);
            $itemStmt->execute();
            $items = $itemStmt->get_result();
            ?>

            <table>
                <thead>
                    <tr>
                        <th>Menu Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php while ($item = $items->fetch_assoc()): ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($subtotal, 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <p><strong>Total: ₱<?= number_format($total, 2) ?></strong></p>
        </div>
    <?php endwhile; ?>

    <p><a href="food-menu.php">← Back to Menu</a></p>
</body>
</html>
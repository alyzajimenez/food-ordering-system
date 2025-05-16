<?php
session_start();
include('../includes/db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* General Body Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2 {
            font-size: 2rem;
            color: #d65108;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Order Card Styles */
        .order {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
            border-left: 5px solid #d65108;
            transition: transform 0.3s ease-in-out;
        }

        .order:hover {
            transform: translateY(-10px);
        }

        .order h3 {
            font-size: 1.6rem;
            color: #333;
            margin-bottom: 10px;
        }

        .order p {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #666;
        }

        .order p strong {
            color: #d65108;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #d65108;
            color: white;
            font-size: 1.2rem;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table td {
            font-size: 1.1rem;
        }

        table .subtotal {
            font-weight: 600;
            color: #d65108;
        }

        /* Total Price */
        .order .total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #d65108;
            margin-top: 20px;
        }

        /* Back Button */
        a {
            display: inline-block;
            margin-top: 30px;
            font-size: 1.1rem;
            color: #d65108;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #f4f4f9;
            border-radius: 5px;
            border: 1px solid #d65108;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #d65108;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Your Order History</h2>

    <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="order">
            <h3>Order #<?= $order['order_id'] ?> - <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></h3>
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
                            <td class="subtotal">₱<?= number_format($subtotal, 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <p class="total"><strong>Total: ₱<?= number_format($total, 2) ?></strong></p>
        </div>
    <?php endwhile; ?>

    <p><a href="food-menu.php">← Back to Menu</a></p>
</body>
</html>

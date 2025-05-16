<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;

if (empty($cart)) {
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            font-size: 1.1rem;
        }

        table th {
            background-color: #d65108;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table td {
            border-bottom: 1px solid #ddd;
        }

        /* Customer Info Form */
        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .form-container h3 {
            color: #d65108;
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-size: 1.1rem;
            margin-bottom: 5px;
            display: block;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f4f4f9;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Button Styles */
        button {
            background-color: #d65108;
            color: white;
            padding: 12px 20px;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #9b3e06;
        }

        .back-btn {
            background-color: #f4f4f9;
            color: #d65108;
            padding: 10px 20px;
            border: 1px solid #d65108;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #d65108;
            color: white;
        }

        /* Total Section */
        .total-section {
            text-align: center;
            font-size: 1.3rem;
            font-weight: 600;
            color: #d65108;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            table th, table td {
                font-size: 1rem;
                padding: 10px;
            }

            button {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<h2>Checkout</h2>

<a href="cart.php" class="back-btn">← Back to Cart</a>

<form action="place-order.php" method="POST">
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $item) : 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['menu_name']) ?></td>
                    <td>₱<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₱<?= number_format($subtotal, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-section">
        <p><strong>Total: ₱<?= number_format($total, 2) ?></strong></p>
    </div>

    <div class="form-container">
        <h3>Customer Info</h3>

        <label for="customer_name">Name</label>
        <input type="text" id="customer_name" name="customer_name" required>

        <label for="mobile_number">Mobile Number</label>
        <input type="tel" id="mobile_number" name="mobile_number" pattern="09[0-9]{9}" placeholder="e.g. 09123456789" required>

        <label for="address">Address</label>
        <textarea id="address" name="address" required></textarea>

        <button type="submit">Place Order</button>
    </div>
</form>

</body>
</html>

<?php
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Handle updates to cart (update quantity or delete item)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        foreach ($_POST['quantity'] as $itemIndex => $quantity) {
            $quantity = max(1, (int)$quantity); // Ensure quantity is at least 1
            if (isset($cart[$itemIndex])) {
                $cart[$itemIndex]['quantity'] = $quantity;
            }
        }
    }

    if (isset($_POST['delete'])) {
        foreach ($_POST['delete'] as $itemIndex => $delete) {
            if ($delete == '1' && isset($cart[$itemIndex])) {
                unset($cart[$itemIndex]); // Remove item from cart
            }
        }
    }

    // Save the updated cart back to session
    $_SESSION['cart'] = $cart;

    // Refresh page to reflect changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
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

        /* Cart Total Styles */
        .cart-total {
            font-size: 1.3rem;
            font-weight: 600;
            color: #d65108;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 20px;
        }

        /* Buttons and Links */
        button {
            background-color: #d65108;
            color: white;
            padding: 12px 20px;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #9b3e06;
        }

        a {
            display: inline-block;
            margin-top: 20px;
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

        /* Empty Cart Message */
        .empty-cart {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
        }

        /* Back Button Styles */
        .back-btn {
            background-color: #f4f4f9;
            color: #d65108;
            padding: 10px 20px;
            border: 1px solid #d65108;
            border-radius: 5px;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 30px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #d65108;
            color: white;
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

            .back-btn {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<h2>Your Cart</h2>

<!-- Back Button -->
<a href="food-menu.php" class="back-btn">← Back to Menu</a>

<?php if (empty($cart)) : ?>
    <p class="empty-cart">Your cart is empty. <a href="food-menu.php">Go to menu</a></p>
<?php else : ?>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $index => $item) : 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['menu_name']) ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <input type="number" name="quantity[<?= $index ?>]" value="<?= $item['quantity'] ?>" min="1" style="width: 50px; text-align: center;">
                        </td>
                        <td>₱<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <label>
                                <input type="checkbox" name="delete[<?= $index ?>]" value="1">
                                Delete
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-total">
            <p><strong>Total: ₱<?= number_format($total, 2) ?></strong></p>
            <button type="submit" name="update">Update Cart</button>
        </div>
    </form>

    <div class="cart-total">
        <a href="checkout.php"><button>Proceed to Checkout</button></a>
    </div>
<?php endif; ?>

</body>
</html>

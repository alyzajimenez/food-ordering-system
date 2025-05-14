<?php
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<h2>Your Cart</h2>
<?php if (empty($cart)) : ?>
    <p>Your cart is empty. <a href="food-menu.php">Go to menu</a></p>
<?php else : ?>
    <table border="1" cellpadding="10">
        <tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
        <?php foreach ($cart as $item) : 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
            <tr>
                <td><?= $item['menu_name'] ?></td>
                <td>₱<?= $item['price'] ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₱<?= $subtotal ?></td>
            </tr>
        <?php endforeach; ?>
        <tr><td colspan="3"><strong>Total</strong></td><td><strong>₱<?= $total ?></strong></td></tr>
    </table>

    <a href="checkout.php"><button>Proceed to Checkout</button></a>
<?php endif; ?>
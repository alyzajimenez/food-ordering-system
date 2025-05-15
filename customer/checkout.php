<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;

if (empty($cart)) {
    header("Location: cart.php");
    exit;
}
?>

<h2>Checkout</h2>
<form action="place-order.php" method="POST">
    <table border="1" cellpadding="10">
        <tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
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
        <tr><td colspan="3">Total</td><td><strong>₱<?= $total ?></strong></td></tr>
    </table>

<h3>Customer Info</h3>

<label>Name</label><br>
<input type="text" name="customer_name" required><br>

<label>Mobile Number</label><br>
<input type="tel" name="mobile_number" pattern="09[0-9]{9}" placeholder="e.g. 09123456789" required><br>

<label>Address</label><br>
<textarea name="address" required></textarea><br><br>

<button type="submit">Place Order</button>

</form>
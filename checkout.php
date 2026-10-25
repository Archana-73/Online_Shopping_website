<?php
require_once 'config.php'; require_once 'functions.php';
if (empty($_SESSION['user_id'])) { $_SESSION['checkout_notice'] = true; header('Location: login.php'); exit; }
$cart = $_SESSION['cart'] ?? []; $items = []; $total = 0;
if ($cart) {
    $ids = array_values(array_filter(array_map('intval', array_keys($cart))));
    if ($ids) {
        $marks = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($marks)"); $stmt->execute($ids);
        foreach ($stmt->fetchAll() as $p) {
            $qty = min((int)$cart[$p['id']], (int)$p['stock']);
            if ($qty > 0) { $p['quantity'] = $qty; $items[] = $p; $total += $qty * (float)$p['price']; }
        }
    }
}
$error = '';
if (!$items) { header('Location: cart.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer = trim($_POST['customer_name'] ?? ''); $address = trim($_POST['address'] ?? '');
    if ($customer === '' || $address === '') { $error = 'Please enter your name and delivery address.'; }
    else {
        try {
            $pdo->beginTransaction();
            // Re-check stock while placing the demo order.
            foreach ($items as $item) {
                $check = $pdo->prepare('SELECT stock FROM products WHERE id = ? FOR UPDATE'); $check->execute([$item['id']]);
                if ((int)$check->fetchColumn() < (int)$item['quantity']) throw new RuntimeException('Not enough stock for ' . $item['name']);
            }
            $stmt = $pdo->prepare('INSERT INTO orders (user_id, customer_name, address, total) VALUES (?, ?, ?, ?)');
            $stmt->execute([$_SESSION['user_id'], $customer, $address, $total]); $orderId = (int)$pdo->lastInsertId();
            $line = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price) VALUES (?, ?, ?, ?, ?)');
            $stock = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
            foreach ($items as $item) {
                $line->execute([$orderId, $item['id'], $item['name'], $item['quantity'], $item['price']]);
                $stock->execute([$item['quantity'], $item['id']]);
            }
            $pdo->commit(); $_SESSION['cart'] = [];
            header('Location: order_success.php?id=' . $orderId); exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = $e instanceof RuntimeException ? $e->getMessage() : 'Could not place order. Please try again.';
        }
    }
}
$pageTitle = 'Checkout'; require 'header.php';
?>
<section class="page-heading"><p class="eyebrow">ALMOST THERE</p><h1>Checkout</h1><p>This is a demo checkout. No payment will be collected.</p></section>
<div class="checkout-layout"><form class="auth-card checkout-card" method="post"><h2>Delivery details</h2>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<label for="customer_name">Full name</label><input id="customer_name" name="customer_name" required value="<?= e($_SESSION['user_name'] ?? '') ?>">
<label for="address">Delivery address</label><textarea id="address" name="address" required rows="4" placeholder="House number, street, city, PIN code"></textarea>
<button class="button button-primary full-button" type="submit">Place demo order</button></form>
<aside class="order-summary"><h2>Your order</h2><?php foreach ($items as $item): ?><div class="summary-row"><span><?= e($item['name']) ?> × <?= (int)$item['quantity'] ?></span><strong><?= money($item['price'] * $item['quantity']) ?></strong></div><?php endforeach; ?><div class="summary-row total-row"><span>Total</span><strong><?= money($total) ?></strong></div></aside></div>
<?php require 'footer.php'; ?>

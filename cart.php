<?php
require_once 'config.php';
require_once 'functions.php';
$cart = $_SESSION['cart'] ?? [];
$items = []; $total = 0;
if ($cart) {
    $ids = array_values(array_filter(array_map('intval', array_keys($cart))));
    if ($ids) {
        $marks = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($marks)");
        $stmt->execute($ids);
        foreach ($stmt->fetchAll() as $p) {
            $qty = min((int)$cart[$p['id']], (int)$p['stock']);
            if ($qty > 0) { $p['quantity'] = $qty; $p['line_total'] = $qty * (float)$p['price']; $items[] = $p; $total += $p['line_total']; }
        }
    }
}
$pageTitle = 'Your Cart'; require 'header.php';
?>
<section class="page-heading"><p class="eyebrow">YOUR BAG</p><h1>Shopping cart</h1><p>Review your items before checkout.</p></section>
<?php if (!$items): ?>
  <div class="empty-state"><div class="empty-icon">🛍</div><h2>Your cart is empty</h2><p>Find something useful for your everyday.</p><a class="button button-primary" href="index.php">Continue shopping</a></div>
<?php else: ?>
  <div class="cart-layout">
    <div class="cart-items">
      <?php foreach ($items as $item): ?>
        <article class="cart-item">
          <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['name']) ?>">
          <div class="cart-item-main"><h3><?= e($item['name']) ?></h3><p><?= money($item['price']) ?> each</p>
            <form action="cart_action.php" method="post" class="quantity-form">
              <input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>"><input type="hidden" name="action" value="update">
              <label for="qty-<?= (int)$item['id'] ?>">Qty</label><input id="qty-<?= (int)$item['id'] ?>" name="quantity" type="number" min="1" max="<?= (int)$item['stock'] ?>" value="<?= (int)$item['quantity'] ?>">
              <button class="text-button" type="submit">Update</button>
            </form>
            <form action="cart_action.php" method="post"><input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>"><input type="hidden" name="action" value="remove"><button class="text-button danger" type="submit">Remove</button></form>
          </div>
          <strong class="line-total"><?= money($item['line_total']) ?></strong>
        </article>
      <?php endforeach; ?>
    </div>
    <aside class="order-summary"><h2>Order summary</h2><div class="summary-row"><span>Subtotal</span><strong><?= money($total) ?></strong></div><div class="summary-row"><span>Shipping</span><span>Free</span></div><div class="summary-row total-row"><span>Total</span><strong><?= money($total) ?></strong></div><a class="button button-primary full-button" href="checkout.php">Proceed to checkout</a><a class="back-link" href="index.php">Continue shopping</a></aside>
  </div>
<?php endif; ?>
<?php require 'footer.php'; ?>

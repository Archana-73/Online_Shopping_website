<?php
require_once 'config.php'; require_once 'functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
$stmt->execute([$id ?: 0, $_SESSION['user_id'] ?? 0]); $order = $stmt->fetch();
$pageTitle = 'Order confirmation'; require 'header.php';
?>
<div class="empty-state success-state"><div class="success-icon">✓</div><p class="eyebrow">ORDER PLACED</p><h1>Thank you for your order!</h1>
<?php if ($order): ?><p>Your demo order number is <strong>#<?= (int)$order['id'] ?></strong>.</p><p>Order total: <strong><?= money($order['total']) ?></strong></p><?php else: ?><p>We couldn't find that order for your account.</p><?php endif; ?>
<p class="muted">This project does not take real payments or ship products.</p><a class="button button-primary" href="index.php">Continue shopping</a></div>
<?php require 'footer.php'; ?>

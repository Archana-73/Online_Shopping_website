<?php
require_once 'config.php';
require_once 'functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id ?: 0]);
$product = $stmt->fetch();
if (!$product) { http_response_code(404); $pageTitle = 'Product not found'; require 'header.php'; echo '<div class="empty-state"><h2>Product not found</h2><a href="index.php">Back to shop</a></div>'; require 'footer.php'; exit; }
$pageTitle = $product['name'];
require 'header.php';
?>
<div class="product-detail">
  <div class="detail-image"><img src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>"></div>
  <div class="detail-copy">
    <p class="eyebrow"><?= e($product['category']) ?></p>
    <h1><?= e($product['name']) ?></h1>
    <p class="detail-price"><?= money($product['price']) ?></p>
    <p><?= e($product['description']) ?></p>
    <p class="stock-note"><?= (int)$product['stock'] > 0 ? 'In stock' : 'Currently unavailable' ?></p>
    <form action="cart_action.php" method="post" class="add-detail-form">
      <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
      <input type="hidden" name="action" value="add">
      <label for="quantity">Quantity</label><input id="quantity" name="quantity" type="number" min="1" max="<?= max(1, (int)$product['stock']) ?>" value="1">
      <button class="button button-primary" type="submit" <?= (int)$product['stock'] < 1 ? 'disabled' : '' ?>>Add to cart</button>
    </form>
    <a class="back-link" href="index.php">← Back to all products</a>
  </div>
</div>
<?php require 'footer.php'; ?>

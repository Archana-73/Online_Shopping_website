<?php
require_once 'config.php';
require_once 'functions.php';
$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ? ORDER BY id DESC");
    $term = "%$search%";
    $stmt->execute([$term, $term]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();
$pageTitle = 'Home';
require 'header.php';
?>
<section class="hero">
  <div class="hero-copy">
    <p class="eyebrow">YOUR EVERYDAY STORE</p>
    <h1>Good finds.<br><span>Better everyday.</span></h1>
    <p>Discover useful essentials for study, work, and home — all in one simple shop.</p>
    <a class="button button-primary" href="#products">Explore products <span aria-hidden="true">→</span></a>
  </div>
  <div class="hero-art" aria-label="Shopping bags illustration" role="img">
    <div class="sun"></div><div class="bag bag-one"></div><div class="bag bag-two"></div><div class="plant">✳</div>
    <span class="hero-note">Little things<br>make a difference.</span>
  </div>
</section>

<section class="benefits">
  <div><span class="benefit-icon">✓</span><span><strong>Easy shopping</strong><small>Simple, clear checkout</small></span></div>
  <div><span class="benefit-icon">♡</span><span><strong>Everyday picks</strong><small>Useful products for you</small></span></div>
  <div><span class="benefit-icon">↺</span><span><strong>Demo store</strong><small>Built for a college project</small></span></div>
</section>

<section id="products" class="products-section">
  <div class="section-heading">
    <div><p class="eyebrow">THE COLLECTION</p><h2>Shop our products</h2></div>
    <form class="search-form" action="index.php" method="get">
      <label class="sr-only" for="q">Search products</label>
      <input id="q" name="q" value="<?= e($search) ?>" placeholder="Search products..." type="search">
      <button class="button button-dark" type="submit">Search</button>
    </form>
  </div>
  <?php if (!$products): ?>
    <div class="empty-state"><h3>No products found</h3><p>Try another product name or category.</p><a href="index.php">Clear search</a></div>
  <?php else: ?>
    <div class="product-grid">
      <?php foreach ($products as $product): ?>
        <article class="product-card">
          <a class="product-image" href="product.php?id=<?= (int)$product['id'] ?>">
            <img src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
            <span class="category-pill"><?= e($product['category']) ?></span>
          </a>
          <div class="product-info">
            <h3><a href="product.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h3>
            <p><?= e($product['description']) ?></p>
            <div class="product-bottom"><strong><?= money($product['price']) ?></strong>
              <form action="cart_action.php" method="post">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="quantity" value="1">
                <button class="add-button" type="submit" aria-label="Add <?= e($product['name']) ?> to cart">+</button>
              </form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php require 'footer.php'; ?>

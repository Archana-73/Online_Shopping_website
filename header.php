<?php require_once __DIR__ . '/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle) . ' | ShopEase' : 'ShopEase | Online Shopping' ?></title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<header class="site-header">
  <div class="nav-wrap">
    <a class="brand" href="index.php"><span class="brand-mark">S</span> ShopEase</a>
    <button class="menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">☰</button>
    <nav class="nav-links">
      <a href="index.php">Home</a>
      <a href="index.php#products">Products</a>
      <a href="cart.php">Cart <span class="cart-count"><?= cart_count() ?></span></a>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <span class="welcome">Hi, <?= e($_SESSION['user_name'] ?? 'Customer') ?></span>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a class="nav-cta" href="register.php">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">

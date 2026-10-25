<?php
require_once 'config.php';
require_once 'functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$action = $_POST['action'] ?? '';
$id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
if ($action === 'remove') {
    unset($_SESSION['cart'][$id]);
} elseif ($action === 'update') {
    $qty = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    if ($id && $qty && $qty > 0) {
        $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = ?');
        $stmt->execute([$id]); $product = $stmt->fetch();
        if ($product) $_SESSION['cart'][$id] = min($qty, (int)$product['stock']);
    } else { unset($_SESSION['cart'][$id]); }
} elseif ($action === 'add' && $id) {
    $qty = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?: 1;
    $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = ?');
    $stmt->execute([$id]); $product = $stmt->fetch();
    if ($product && (int)$product['stock'] > 0) {
        $current = (int)($_SESSION['cart'][$id] ?? 0);
        $_SESSION['cart'][$id] = min($current + max(1, $qty), (int)$product['stock']);
    }
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'cart.php'));
exit;

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function money($amount): string {
    return '₹' . number_format((float)$amount, 2);
}
function cart_count(): int {
    return array_sum($_SESSION['cart'] ?? []);
}

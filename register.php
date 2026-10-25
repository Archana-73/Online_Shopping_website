<?php
require_once 'config.php'; require_once 'functions.php';
$error = ''; $name = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Enter your name, a valid email, and a password with at least 8 characters.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            session_regenerate_id(true); $_SESSION['user_id'] = (int)$pdo->lastInsertId(); $_SESSION['user_name'] = $name;
            header('Location: index.php'); exit;
        } catch (PDOException $e) { $error = 'That email may already be registered. Try logging in.'; }
    }
}
$pageTitle = 'Create account'; require 'header.php';
?>
<div class="auth-wrap"><form class="auth-card" method="post"><p class="eyebrow">JOIN SHOPEASE</p><h1>Create account</h1><p class="muted">Create an account to place a demo order.</p>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<label for="name">Full name</label><input id="name" name="name" required maxlength="100" value="<?= e($name) ?>" autocomplete="name">
<label for="email">Email address</label><input id="email" name="email" type="email" required value="<?= e($email) ?>" autocomplete="email">
<label for="password">Password (at least 8 characters)</label><input id="password" name="password" type="password" required minlength="8" autocomplete="new-password">
<button class="button button-primary full-button" type="submit">Create account</button><p class="auth-switch">Already registered? <a href="login.php">Log in</a></p></form></div>
<?php require 'footer.php'; ?>

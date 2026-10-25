<?php
require_once 'config.php'; require_once 'functions.php';
$error = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
    $stmt->execute([$email]); $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true); $_SESSION['user_id'] = (int)$user['id']; $_SESSION['user_name'] = $user['name'];
        header('Location: index.php'); exit;
    } else { $error = 'Email or password is incorrect.'; }
}
$pageTitle = 'Login'; require 'header.php';
?>
<div class="auth-wrap"><form class="auth-card" method="post"><p class="eyebrow">WELCOME BACK</p><h1>Log in</h1><p class="muted">Sign in to continue shopping.</p>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<label for="email">Email address</label><input id="email" name="email" type="email" required value="<?= e($email) ?>" autocomplete="email">
<label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password">
<button class="button button-primary full-button" type="submit">Log in</button><p class="auth-switch">New here? <a href="register.php">Create an account</a></p></form></div>
<?php require 'footer.php'; ?>

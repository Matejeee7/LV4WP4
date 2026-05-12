<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        redirect('index.php');
    } else $error = 'Pogrešan email ili lozinka.';
}
?>
<!doctype html>
<html lang="hr">
    <head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Prijava</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body><?php include 'includes/header.php'; ?>
<main class="main-content" style="max-width:700px;margin:2rem auto">
    <section class="intro"><h2>Prijava</h2><?php if(isset($_GET['registered'])): ?>
        <p class="message">Registracija je uspješna. Prijavi se.</p>
        <?php endif; ?><?php if($error): ?><p class="message" style="color:#b00020"><?=e($error)?></p>
            <?php endif; ?><form method="post" class="filters"><label>Email<input type="email" name="email" required>
        </label>
        <label>Lozinka<input type="password" name="password" required>
    </label>
    <button type="submit">Prijavi se</button>
</form>
<p>Test admin: admin@example.com / admin123
    <br>Test korisnik: korisnik@example.com / user123</p>
</section>
</main>
</body>
</html>

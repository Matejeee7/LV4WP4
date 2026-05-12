<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $email === '' || $password === '') $error = 'Sva polja su obavezna.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Email nije ispravan.';
    elseif (strlen($password) < 6) $error = 'Lozinka mora imati barem 6 znakova.';
    else {
        try {
            $stmt = $pdo->prepare('INSERT INTO users(username,email,password) VALUES(?,?,?)');
            $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
            redirect('login.php?registered=1');
        } catch (PDOException $e) { $error = 'Korisničko ime ili email već postoji.'; }
    }
}
?>
<!doctype html>
<html lang="hr">
    <head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Registracija</title><link rel="stylesheet" href="css/style.css">
</head>
<body><?php include 'includes/header.php'; ?>
<main class="main-content" style="max-width:700px;margin:2rem auto">
    <section class="intro"><h2>Registracija</h2><?php if($error): ?>
        <p class="message" style="color:#b00020"><?=e($error)?></p>
        <?php endif; ?><form method="post" class="filters">
            <label>Korisničko ime<input name="username" required></label>
            <label>Email<input type="email" name="email" required></label>
            <label>Lozinka<input type="password" name="password" required></label>
            <button type="submit">Registriraj se</button>
        </form>
    </section>
</main>
</body>
</html>

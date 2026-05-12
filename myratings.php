<?php
session_start(); 
require 'includes/db.php'; 
require 'includes/auth.php'; 
require 'includes/functions.php'; 
require_login();
$stmt=$pdo->prepare('SELECT r.*, i.title, i.filename FROM ratings r JOIN images i ON i.id=r.image_id WHERE r.user_id=? ORDER BY r.rated_at DESC'); $stmt->execute([current_user_id()]); $ratings=$stmt->fetchAll();
?>
<!doctype html>
<html lang="hr">
    <head>
        <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Moje ocjene</title>
        <link rel="stylesheet" href="css/style.css"></head>
        <body><?php include 'includes/header.php'; ?>
        <main class="main-content" style="max-width:1000px;margin:2rem auto">
            <section class="intro">
                <h2>Moje ocjene slika</h2>
                <div class="table-wrapper"><table>
                    <thead><tr>
                        <th>Slika</th>
                        <th>Ocjena</th>
                <th>Komentar</th>
                <th>Vrijeme</th>
            </tr>
        </thead>
        <tbody><?php foreach($ratings as $r): ?>
            <tr>
            <td><?=e($r['title'])?></td>
            <td><?=e($r['rating'])?> / 5</td>
            <td><?=e($r['comment'])?></td>
            <td><?=e($r['rated_at'])?></td>
        </tr><?php endforeach; ?></tbody>
    </table>
</div>
</section>
</main>
</body>
</html>

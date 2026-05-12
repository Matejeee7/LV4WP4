<?php
session_start(); 
require 'includes/db.php'; 
require 'includes/functions.php';
$id=(int)($_GET['id'] ?? 0); 
$message='';
$stmt=$pdo->prepare('SELECT * FROM images WHERE id=?'); $stmt->execute([$id]); $image=$stmt->fetch(); if(!$image) die('Slika ne postoji.');
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!isset($_SESSION['user_id'])) redirect('login.php');
    $rating=(int)($_POST['rating'] ?? 0); $comment=trim($_POST['comment'] ?? '');
    if($rating < 1 || $rating > 5) $message='Ocjena mora biti od 1 do 5.';
    else { $stmt=$pdo->prepare('INSERT INTO ratings(user_id,image_id,rating,comment) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE rating=VALUES(rating), comment=VALUES(comment), rated_at=CURRENT_TIMESTAMP'); $stmt->execute([$_SESSION['user_id'],$id,$rating,$comment]); $message='Ocjena je spremljena.'; }
}
$avgStmt=$pdo->prepare('SELECT COALESCE(AVG(rating),0) AS avg_rating, COUNT(*) AS total FROM ratings WHERE image_id=?'); $avgStmt->execute([$id]); $avg=$avgStmt->fetch();
$comments=$pdo->prepare('SELECT r.rating,r.comment,r.rated_at,u.username FROM ratings r JOIN users u ON u.id=r.user_id WHERE r.image_id=? AND r.comment IS NOT NULL AND r.comment<>"" ORDER BY r.rated_at DESC'); $comments->execute([$id]);
?>
<!doctype html>
<html lang="hr">
    <head>
        <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?=e($image['title'])?></title>
        <link rel="stylesheet" href="css/style-slike.css"><link rel="stylesheet" href="css/lv4-extra.css">
    </head>
    <body><?php include 'includes/header.php'; ?>
    <main class="gallery-page"><section class="gallery-section"><h2><?=e($image['title'])?></h2>
    <?php if($message): ?>
        <p class="message"><?=e($message)?></p>
        <?php endif; ?>
        <img class="detail-image" src="images/<?=e($image['filename'])?>" alt="<?=e($image['alt_text'])?>">
        <p><strong>Prosječna ocjena:</strong> <?=round((float)$avg['avg_rating'],2)?> / 5 <?=stars((float)$avg['avg_rating'])?> (<?=$avg['total']?> ocjena)</p>
        <form method="post" class="rating-form">
            <label>Ocjena<select name="rating" required>
                <option value="">Odaberi</option>
            <option value="1">1 ★</option>
            <option value="2">2 ★★</option>
            <option value="3">3 ★★★</option>
            <option value="4">4 ★★★★</option>
            <option value="5">5 ★★★★★</option>
        </select>
    </label>
    <label>Komentar<textarea name="comment" maxlength="500" placeholder="Opcionalni komentar"></textarea>
</label>
<button type="submit">Spremi ocjenu</button>
</form>
<h3>Komentari</h3>
<?php foreach($comments as $c): ?>
    <p class="comment"><strong><?=e($c['username'])?></strong> 
    (<?=e($c['rating'])?>/5): <?=e($c['comment'])?></p>
    <?php endforeach; ?><p><a class="more-btn" href="gallery.php">Natrag na galeriju</a></p>
</section>
</main>
</body>
</html>

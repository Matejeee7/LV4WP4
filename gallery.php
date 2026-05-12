<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';
$images = $pdo->query('SELECT i.*, COALESCE(AVG(r.rating),0) AS average_rating, COUNT(r.id) AS rating_count FROM images i LEFT JOIN ratings r ON r.image_id=i.id GROUP BY i.id ORDER BY i.id')->fetchAll();
?>
<!doctype html>
<html lang="hr"><head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="Galerija filmskih postera s ocjenjivanjem.">
    <title>Galerija</title>
    <link rel="stylesheet" href="css/style-slike.css"><link rel="stylesheet" href="css/lv4-extra.css">
</head>
<body><?php include 'includes/header.php'; ?>
<main class="gallery-page">
    <section class="gallery-section"><h2>Galerija filmskih postera</h2>
    <p class="gallery-description">Klikni na poster za detalje i ocjenjivanje. Ocjene se trajno spremaju u bazu.</p>
    <div class="gallery-grid"><?php foreach($images as $img): ?><figure class="gallery-item">
        <a href="photo.php?id=<?=$img['id']?>"><img src="images/<?=e($img['filename'])?>" alt="<?=e($img['alt_text'])?>" loading="lazy"></a>
        <figcaption><?=e($img['title'])?>
        <br><span class="stars"><?=stars((float)$img['average_rating'])?></span>
        <br>Prosjek: <?=round((float)$img['average_rating'],2)?> / 5 (<?=$img['rating_count']?>)</figcaption>
    </figure><?php endforeach; ?>
</div>
</section>
</main>
<footer class="site-footer">
    <p>&copy; 2026 Filmovi Web | Web programiranje</p>
</footer>
</body>
</html>

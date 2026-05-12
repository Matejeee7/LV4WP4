<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'])) {
    if (!isset($_SESSION['user_id'])) redirect('login.php');
    $movie_id = (int)$_POST['movie_id'];
    $stmt = $pdo->prepare('SELECT average_score,title FROM movies WHERE id=?');
    $stmt->execute([$movie_id]);
    $movie = $stmt->fetch();
    if ($movie) {
        try {
            $add = $pdo->prepare('INSERT INTO desired_movies(user_id,movie_id) VALUES(?,?)');
            $add->execute([$_SESSION['user_id'], $movie_id]);
            $message = $movie['average_score'] < 5 ? 'Film je dodan, ali ima nisku prosječnu ocjenu.' : 'Film je dodan u moju videoteku.';
        } catch (PDOException $e) { $message = 'Film je već u tvojoj videoteci.'; }
    }
}

$genre = trim($_GET['genre'] ?? '');
$type = trim($_GET['type'] ?? '');
$search = trim($_GET['search'] ?? '');
$year = (int)($_GET['year'] ?? 0);
$sort = $_GET['sort'] ?? 'year_desc';

$where = [];
$params = [];
if ($genre !== '') { $where[] = 'genre = ?'; $params[] = $genre; }
if ($type !== '') { $where[] = 'type = ?'; $params[] = $type; }
if ($search !== '') { $where[] = 'title LIKE ?'; $params[] = '%' . $search . '%'; }
if ($year > 0) { $where[] = 'release_year >= ?'; $params[] = $year; }
$order = match($sort) { 'year_asc' => 'release_year ASC', 'title' => 'title ASC', default => 'release_year DESC' };
$sql = 'SELECT * FROM movies' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . " ORDER BY $order";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$movies = $stmt->fetchAll();
$genres = $pdo->query('SELECT DISTINCT genre FROM movies ORDER BY genre')->fetchAll();
?>
<!doctype html>
<html lang="hr"><head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="LV4 PHP MySQL aplikacija za filmove.">
    <title>Filmovi Web | LV4</title>
    <link rel="stylesheet" href="css/style.css">
</head>
    <body><?php include 'includes/header.php'; ?>
<div class="page-layout">
    <main class="main-content"><section class="intro">
        <h2>O projektu</h2>
        <p>Ova verzija koristi PHP, MySQL, sesije, autentifikaciju i trajno spremanje podataka na serveru.</p>
        <?php if(isset($_SESSION['username'])): ?>
            <p>Prijavljen si kao <strong><?=e($_SESSION['username'])?></strong>.
        </p><?php endif; ?><?php if($message): ?>
            <p class="message" style="border:1px solid #b00020;padding:1rem;color:#b00020"><?=e($message)?></p>
            <?php endif; ?></section>
<section class="filter-section">
    <h2>Filtriranje i pretraživanje filmova</h2>
    <form method="get" class="filters"><label>Žanr<select name="genre"><option value="">Svi žanrovi</option>
    <?php foreach($genres as $g): ?>
        <option value="<?=e($g['genre'])?>" <?=$genre===$g['genre']?'selected':''?>><?=e($g['genre'])?></option>
        <?php endforeach; ?>
    </select>
</label>
        <label>Pretraži naslov<input name="search" value="<?=e($search)?>" placeholder="npr. Diner"></label>
        <label>Godina od<input type="number" name="year" min="1900" max="2030" value="<?=$year ?: ''?>"></label>
        <label>Tip<select name="type"><option value="">Sve</option><option value="Movie" <?=$type==='Movie'?'selected':''?>>Movie</option>
        <option value="TV Show" <?=$type==='TV Show'?'selected':''?>>TV Show</option>
    </select>
</label>
        <label>Sortiranje<select name="sort"><option value="year_desc" <?=$sort==='year_desc'?'selected':''?>>Novije prvo</option>
        <option value="year_asc" <?=$sort==='year_asc'?'selected':''?>>Starije prvo</option>
        <option value="title" <?=$sort==='title'?'selected':''?>>Naslov A-Z</option>
    </select>
</label>
<button type="submit">Filtriraj</button>
<a class="more-btn" href="index.php">Reset</a>
</form>
<p class="result-count">Broj rezultata: <?=count($movies)?></p>
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Naslov</th>
                <th>Godina</th>
                <th>Žanr</th>
                <th>Trajanje</th>
                <th>Država</th>
                <th>Oznaka</th>
                <th>Tip</th>
                <th>Ocjena</th>
                <th>Akcija</th>
            </tr>
        </thead>
        <tbody><?php foreach($movies as $m): ?><tr>
            <td><?=e($m['title'])?></td>
            <td><?=e($m['release_year'])?></td>
            <td><?=e($m['genre'])?></td>
            <td><?=e($m['duration'])?></td>
            <td><?=e($m['country'])?></td>
            <td><?=e($m['rating'])?></td>
            <td><?=e($m['type'])?></td>
            <td><?=e($m['average_score'])?></td>
            <td><form method="post"><input type="hidden" name="movie_id" value="<?=$m['id']?>">
            <button type="submit">Dodaj</button>
        </form>
    </td>
</tr><?php endforeach; ?>
</tbody>
</table>
</div>
</section>
</main>
<aside class="sidebar">
    <h2>Izdvojeni vizual</h2>
    <img src="images/cinema.jpg" alt="Kino">
    <p>Galerija postera ima sustav ocjenjivanja spremljen u MySQL bazu.</p>
    <a class="more-btn" href="gallery.php">Pogledaj galeriju</a>
</aside>
</div>
<footer class="site-footer"><p>&copy; 2026 Filmovi Web | Web programiranje</p></footer>
</body>
</html>

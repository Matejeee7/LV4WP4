<?php
session_start(); 
require 'includes/db.php'; 
require 'includes/auth.php'; 
require 'includes/functions.php'; 
require_login();
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_id'])) 
    { $stmt=$pdo->prepare('DELETE FROM desired_movies WHERE id=? AND user_id=?'); 
$stmt->execute([(int)$_POST['delete_id'], current_user_id()]); }
$stmt=$pdo->prepare('SELECT dm.id AS desired_id, m.* FROM desired_movies dm JOIN movies m ON m.id=dm.movie_id WHERE dm.user_id=? ORDER BY dm.added_at DESC'); 
$stmt->execute([current_user_id()]); 
$movies=$stmt->fetchAll();
?>
<!doctype html>
<html lang="hr">
    <head>
        <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Moja videoteka</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
        <body><?php include 'includes/header.php'; ?>
        <main class="main-content" style="max-width:1000px;margin:2rem auto">
            <section class="intro">
                <h2>Moja videoteka</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Naslov</th>
                                <th>Godina</th>
                                <th>Žanr</th>
                                <th>Ocjena</th>
                                <th>Akcija</th>
                            </tr>
                        </thead>
                        <tbody><?php foreach($movies as $m): ?>
                            <tr>
                            <td><?=e($m['title'])?></td>
                            <td><?=e($m['release_year'])?></td>
                            <td><?=e($m['genre'])?></td>
                            <td><?=e($m['average_score'])?></td>
                            <td><form method="post"><input type="hidden" name="delete_id" value="<?=$m['desired_id']?>">
                            <button>Ukloni</button>
                        </form>
                    </td>
                    </tr><?php endforeach; ?>
                </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>

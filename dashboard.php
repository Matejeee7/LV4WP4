<?php
session_start(); require 'includes/db.php'; require 'includes/auth.php'; require 'includes/functions.php'; require_admin();
$message='';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['upload_image'])){
    $title=trim($_POST['title'] ?? '');
    if($title==='' || empty($_FILES['image']['name'])) $message='Unesi naslov i odaberi sliku.';
    else {
        $file=$_FILES['image']; $allowed=['image/jpeg'=>'jpg','image/png'=>'png'];
        if(!isset($allowed[$file['type']])) $message='Dozvoljeni su samo JPG i PNG.';
        elseif($file['size'] > 5*1024*1024) $message='Slika ne smije biti veća od 5MB.';
        else { $safe=preg_replace('/[^A-Za-z0-9._-]/','_',basename($file['name'])); $name=time().'_'.$safe; if(move_uploaded_file($file['tmp_name'], __DIR__.'/images/'.$name)){ $stmt=$pdo->prepare('INSERT INTO images(filename,title,alt_text) VALUES(?,?,?)'); $stmt->execute([$name,$title,'Slika '.$title]); $message='Slika je dodana.'; } }
    }
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_image'])){ $stmt=$pdo->prepare('DELETE FROM images WHERE id=?'); $stmt->execute([(int)$_POST['delete_image']]); $message='Slika je obrisana iz baze.'; }
$images=$pdo->query('SELECT i.*, COALESCE(AVG(r.rating),0) avg_rating, COUNT(r.id) total FROM images i LEFT JOIN ratings r ON r.image_id=i.id GROUP BY i.id ORDER BY i.id DESC')->fetchAll();
$movies=$pdo->query('SELECT * FROM movies ORDER BY id DESC')->fetchAll();
?>
<!doctype html>
<html lang="hr">
    <head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
    <body><?php include 'includes/header.php'; ?><main class="main-content" style="max-width:1100px;margin:2rem auto"><section class="intro">
        <h2>Admin dashboard</h2>
        <?php if($message): ?>
            <p class="message"><?=e($message)?></p>
            <?php endif; ?>
            <h3>Upload nove slike</h3>
            <form method="post" enctype="multipart/form-data" class="filters"><input type="hidden" name="upload_image" value="1">
            <label>Naslov<input name="title" required></label>
            <label>Slika JPG/PNG do 5MB<input type="file" name="image" accept="image/jpeg,image/png" required></label>
            <button type="submit">Dodaj sliku</button>
        </form>
        <h3>Slike</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naslov</th>
                        <th>Datoteka</th>
                        <th>Prosjek</th>
                        <th>Ocjena</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody><?php foreach($images as $i): ?>
                    <tr>
                        <td><?=$i['id']?></td>
                        <td><?=e($i['title'])?></td>
                        <td><?=e($i['filename'])?></td>
                        <td><?=round((float)$i['avg_rating'],2)?></td>
                        <td><?=$i['total']?></td>
                        <td><form method="post">
                            <button name="delete_image" value="<?=$i['id']?>">Obriši</button>
                        </form>
                    </td>
                </tr><?php endforeach; ?></tbody>
            </table>
        </div>
        <h3>Filmovi u bazi</h3>
        <div class="table-wrapper"><table>
            <thead>
                <tr>
                    <th>Naslov</th>
                    <th>Godina</th>
                    <th>Žanr</th>
                    <th>Tip</th>
                    <th>Ocjena</th>
                </tr>
            </thead>
            <tbody><?php foreach($movies as $m): ?><tr>
                <td><?=e($m['title'])?></td>
                <td><?=e($m['release_year'])?></td>
                <td><?=e($m['genre'])?></td>
                <td><?=e($m['type'])?></td>
                <td><?=e($m['average_score'])?></td>
            </tr><?php endforeach; ?></tbody>
        </table>
    </div>
</section>
</main>
</body>
</html>

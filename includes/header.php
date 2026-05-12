<?php require_once __DIR__ . '/auth.php'; require_once __DIR__ . '/functions.php'; ?>
<header class="site-header">
    <div class="header-inner">
        <h1>Filmovi Web | LV4</h1>
        <p>PHP + MySQL aplikacija za filmove, osobnu videoteku i ocjenjivanje slika.</p>
    </div>
</header>
<div class="nav-wrapper">
    <button class="menu-toggle" aria-label="Prikaži navigaciju">☰ Menu</button>
    <nav class="main-nav" aria-label="Glavna navigacija">
        <ul>
            <li><a href="index.php">Početna / Filmovi</a></li>
            <li><a href="my_movies.php">Moja videoteka</a></li>
            <li><a href="gallery.php">Galerija</a></li>
            <li><a href="myratings.php">Moje ocjene</a></li>
            <?php if (is_admin()): ?><li><a href="dashboard.php">Admin</a></li><?php endif; ?>
            <?php if (is_logged_in()): ?>
                <li><a href="logout.php">Odjava</a></li>
            <?php else: ?>
                <li><a href="login.php">Prijava</a></li>
                <li><a href="register.php">Registracija</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

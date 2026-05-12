<?php
function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

function get_average_rating(PDO $pdo, int $image_id): float {
    $stmt = $pdo->prepare('SELECT AVG(rating) AS average_rating FROM ratings WHERE image_id = ?');
    $stmt->execute([$image_id]);
    return round((float)($stmt->fetch()['average_rating'] ?? 0), 2);
}

function stars(float $rating): string {
    $full = (int)round($rating);
    return str_repeat('★', $full) . str_repeat('☆', max(0, 5 - $full));
}
?>

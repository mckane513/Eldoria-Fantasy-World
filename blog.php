<?php
include 'config.php'; // Soubor pro připojení k databázi

$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
</head>
<body>
    <h1>Blog</h1>
    <?php foreach ($posts as $post): ?>
        <div>
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><em>Autor: <?php echo htmlspecialchars($post['author']); ?> | Datum: <?php echo $post['created_at']; ?></em></p>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <hr>
        </div>
    <?php endforeach; ?>
</body>
</html>

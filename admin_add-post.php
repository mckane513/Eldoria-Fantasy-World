<?php
session_start();

$correct_password = "8735"; // Změň na své heslo

if (!isset($_SESSION['logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] == $correct_password) {
        $_SESSION['logged_in'] = true;
    } else {
        echo '<form method="POST"><input type="password" name="password" placeholder="Heslo"><button type="submit">Přihlásit se</button></form>';
        exit;
    }
}
?>

include 'config.php'; // Soubor pro připojení k databázi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    $sql = "INSERT INTO posts (title, content, author) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $content, $author]);

    echo "Příspěvek byl úspěšně přidán!";
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat nový příspěvek</title>
</head>
<body>
    <form method="POST" action="admin_add_post.php">
        <label>Nadpis:</label><br>
        <input type="text" name="title" required><br>
        <label>Obsah:</label><br>
        <textarea name="content" required></textarea><br>
        <label>Autor:</label><br>
        <input type="text" name="author" required><br>
        <button type="submit">Přidat příspěvek</button>
    </form>
</body>
</html>

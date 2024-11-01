<?php
$host = 'localhost';
$db_name = 'jmeno_tve_databaze';
$username = 'uzivatelske_jmeno';
$password = 'heslo';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Připojení k databázi selhalo: " . $e->getMessage();
}
?>

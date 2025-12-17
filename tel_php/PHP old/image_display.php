<?php
require 'php/db-connect-pdo.php';
header("Content-Type: image/jpeg");

$id = $_GET['id'] ?? 0;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT photo FROM images WHERE idx = ?");
    $stmt->execute([$id]);
    $photo = $stmt->fetchColumn();

    if ($photo) {
        echo $photo; 
    } else {
        readfile("./images/clova.png");
    }

} catch (Exception $e) {
    readfile("./images/clova.png");
}

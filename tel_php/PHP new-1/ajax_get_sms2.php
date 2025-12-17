<?php
require './php/db-connect-pdo.php';

$exclude = trim($_GET['exclude'] ?? '');

$stmt = $pdo->prepare("SELECT tel FROM tel WHERE tel != ? AND tel != '' ");
$stmt->execute([$exclude]);

$nums = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $nums[] = $row['tel'];
}

echo implode(",", $nums);

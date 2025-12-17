<?php
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';

$exclude_idx = isset($_GET['exclude_idx']) ? (int)$_GET['exclude_idx'] : 0;
header('Content-Type: application/json; charset=utf-8');

try {
    if ($exclude_idx > 0) {
        $stmt = $pdo->prepare("SELECT tel FROM tel WHERE tel != '' AND idx != :exclude_idx ORDER BY name ASC");
        $stmt->execute([':exclude_idx'=>$exclude_idx]);
    } else {
        $stmt = $pdo->query("SELECT tel FROM tel WHERE tel != '' ORDER BY name ASC");
    }

    $phones = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['success'=>true,'phones'=>$phones], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['success'=>false,'error'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>

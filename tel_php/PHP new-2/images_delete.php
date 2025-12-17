<?php
// images_delete.php (단순 버전 - JSON 없이)
require 'php/db-connect-pdo.php';

$id = $_POST['imageId'] ?? null;

if($id){
    try {
        $stmt = $pdo->prepare("DELETE FROM images WHERE idx=?");
        $stmt->execute([$id]);
        echo "ok";
    } catch(Exception $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    echo "error: no id";
}
?>
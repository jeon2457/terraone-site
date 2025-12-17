<?php
require 'php/db-connect-pdo.php';
$id = $_POST['imageId'] ?? null;
$summary = $_POST['summary'] ?? null;
if($id && $summary!==null){
    $stmt = $pdo->prepare("UPDATE images SET notice=? WHERE idx=?");
    $stmt->execute([$summary,$id]);
    echo "ok";
} else { echo "error"; }
?>
<?php
require 'php/db-connect-pdo.php';
$id = $_POST['imageId'] ?? null;
if($id){
    $stmt = $pdo->prepare("DELETE FROM images WHERE idx=?");
    $stmt->execute([$id]);
    echo "ok";
}else{ echo "error"; }
?>

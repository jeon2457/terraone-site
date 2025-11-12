<?php

  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  
  


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결에 실패했습니다: " . $e->getMessage());
}

// 이미지 ID 가져오기
if (isset($_GET['idx'])) {
    $imageId = $_GET['idx'];

    // 이미지 정보 가져오기
    $stmt = $pdo->prepare("SELECT photo FROM images WHERE idx = ?");
    $stmt->execute([$imageId]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image && file_exists($image['photo'])) {
        $filePath = $image['photo'];
        $fileName = basename($filePath);

        // 헤더 설정
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        // 파일 스트리밍
        readfile($filePath);
        exit;
    } else {
        echo "이미지를 찾을 수 없습니다.";
    }
} else {
    echo "이미지 ID가 제공되지 않았습니다.";
}
?>
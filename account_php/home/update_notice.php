<?php
  
  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  
  


// PDO 객체 생성 및 데이터베이스 연결
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결에 실패했습니다: " . $e->getMessage());
}

// 이미지 ID와 요약 텍스트 가져오기
if (isset($_POST['imageId']) && isset($_POST['summary'])) {
  $imageId = $_POST['imageId'];
  $summary = $_POST['summary'];

    // 데이터베이스에 텍스트 업데이트
    // 요약(비고)란을 추가로 수정하거나 업데이트할경우 사용.
    $stmt = $pdo->prepare("UPDATE images SET notice = ? WHERE idx = ?");
    $stmt->bindParam(1, $summary);
    $stmt->bindParam(2, $imageId);
    if ($stmt->execute()) {
        echo "텍스트가 성공적으로 업데이트되었습니다.";
    } else {
        echo "이미지 ID와 요약 텍스트를 받아오지 못했습니다..";
    }

}

?>

<!-- ✅ 이페이지는 tel_edit.php(연락망 수정/삭제) 에서 편집한것을 여기서 처리해서 데이타베이스(DB)로 넘겨서 처리된다.-->

<!DOCTYPE html>

<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="직지35회" />
  <meta name="format-detection" content="telephone=no">
  <title>UPDATE</title> 
  
  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.png?v=2" />
  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

</head>
  
<body>

<?php

// ✅ 관리자 인증
require './php/auth_check.php';

// ✅ PDO DB 연결
require './php/db-connect-pdo.php';

try {

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $addr = $_POST['addr'];
    $remark = $_POST['remark']; // "비고" 데이터 추가
    $sms = $_POST['sms'];
    $sms_2 = $_POST['sms_2'];

    $sql = "UPDATE tel SET name = :name, tel = :tel, addr = :addr, remark = :remark, sms = :sms, sms_2 = :sms_2 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':addr', $addr);
    $stmt->bindParam(':remark', $remark); // "비고" 바인딩
    $stmt->bindParam(':sms', $sms);
    $stmt->bindParam(':sms_2', $sms_2);
    $stmt->execute();

    header("Location: tel_edit.php"); // 수정 후 목록으로 리다이렉트
    exit;
  }
} catch (PDOException $e) {
  echo "오류: " . $e->getMessage();
}
?>


</body>
</html>
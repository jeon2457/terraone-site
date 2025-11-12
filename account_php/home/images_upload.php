<!-- 1. ✅ 이페이지는 영수증 사진보기를 업로드(관리자),편집(관리자),열람페이지(회원) http://localhost/account_view.php/images_view.php 를 시작한다. ===> images_upload.php(사진입력) ==> images_edit.php(사진편집) ==> images_view.php(사진공개 열람)
2. 데이타베이스에서 사용내역서는 수입관련 테이블(income_table)/지출관련 테이블(expense_table)을 사용하고있고, 영수증사진 관련테이블은 images 이다. 
3. 이 데이타는 account_input.php, account_edit.php, account_view.php파일과 서로 연결되어있다.  -->

<?php

// ⭐ 맨 위에 반드시 이 인증절차 통과 코드가 있어야 합니다!
// ⭐ 관리자 인증 — 로그인 + 레벨10 확인
// 해당 연결DB(데이타베이스) member테이블에 회원등록이 되어있어야한다.
// level(레벨위치)이 10으로 지정한 관리자만 페이지접속권한이 있다.
require 'php/auth_check.php';  

  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  
  
date_default_timezone_set('Asia/Seoul'); // 시간출력을 명시적으로 한국시각으로 지정함!

// PDO 객체 생성 및 데이터베이스 연결
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("데이터베이스 연결에 실패했습니다: " . $e->getMessage());
}

// 파일 저장 경로
$uploadDir = 'data/profile/';

// 이미지 업로드 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 이미지 파일, 날짜, 비고 모두 입력되었는지 확인
  if (!empty($_FILES['photo']['name']) && !empty($_POST['selected_date']) && isset($_POST['notice'])) {
    $filename = $_FILES['photo']['name'];
    $tmpFilePath = $_FILES['photo']['tmp_name'];
    $newFilePath = $uploadDir . $filename;

    // 이미지 파일을 지정된 폴더로 이동
    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
      
      // 사용자가 선택한 날짜와 비고 가져오기
     $selectedDate = $_POST['selected_date'];
     $selectedTime = $_POST['selected_time']; // 사용자가 선택한 시간
     $fullDateTime = $selectedDate . ' ' . $selectedTime;


    

      $notice = $_POST['notice'];

      // 데이터베이스에 이미지 파일 경로, 날짜, 비고 저장 (중복 체크 제거)
      $stmt = $pdo->prepare("INSERT INTO images (photo, date, notice) VALUES (?, ?, ?)");
      $stmt->bindParam(1, $newFilePath);
      $stmt->bindParam(2, $fullDateTime);
      $stmt->bindParam(3, $notice);

      if ($stmt->execute()) {
        // 리다이렉션 처리
        header("Location: images_edit.php");
        exit;
      } else {
        echo "이미지 업로드 중 오류가 발생했습니다.";
      }
    } else {
      echo "이미지 업로드 중 오류가 발생했습니다.";
    }
  } else {
    echo "이미지, 날짜, 비고를 모두 입력해주세요.";
  }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="J.S.J" />
  <title>영수증업로드</title>
  <link rel="stylesheet" href="css/images_upload.css" />
  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  
  <!-- 부트스트랩 CDN 링크 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.ico?v=2" />

  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />

  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

</head>
<body>
  <div class="upload-container">
    <form class="upload-form" action="" method="POST" enctype="multipart/form-data">
      <h2>이미지 입력</h2>
      <div class="mb-3">
        <label for="photo" class="form-label">이미지 선택:</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
      </div>
      <div class="mb-3">
        <label for="selected_date" class="form-label">날짜 선택:</label>
        <input type="date" class="form-control" id="selected_date" name="selected_date" required>
      </div>

      <div class="mb-3">
        <label for="selected_time" class="form-label">시간 선택:</label>
        <input type="time" class="form-control" id="selected_time" name="selected_time" required>
      </div>

      <div class="mb-3">
        <label for="notice" class="form-label">비고:</label>
        <textarea class="form-control" id="notice" name="notice" rows="3" placeholder="비고를 입력하세요"></textarea>
      </div>
      <input type="submit" class="btn btn-success" value="업로드">
    </form>
  </div>
  
  <!-- Bootstrap JS (번들) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
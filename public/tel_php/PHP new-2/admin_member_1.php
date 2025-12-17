<?php
// require __DIR__ . '/php/auth_check.php'; // ✅ 권한 체크!
// require __DIR__ . '/php/db-connect-pdo.php';
require './php/auth_check.php';   // 로그인 + 관리자 레벨 확인
require './php/db-connect-pdo.php'; // PDO 연결

date_default_timezone_set('Asia/Seoul');


// 로그아웃 처리
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

// 로그인 검증
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'] ?? $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>관리자 페이지</title>

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    min-height: 100vh;
    font-family: 'Noto Sans KR', sans-serif;
}
.header {
    background: white;
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.header h2 {
    margin: 0;
    font-weight: 700;
    color: #667eea;
}
.logout-btn {
    background: #f44336;
    color: white;
    border-radius: 8px;
    padding: 8px 18px;
    font-weight: 600;
    transition: 0.3s;
}
.logout-btn:hover { background: #d32f2f; }
.menu-container {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(220px,1fr));
    gap: 20px;
}
.menu-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    text-align: center;
    font-weight: 600;
    font-size: 1.1rem;
    color: #333;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    transition: 0.3s;
}
.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
}
.menu-card a {
    text-decoration: none;
    color: inherit;
}
.menu-icon {
    font-size: 2.5rem;
    margin-bottom: 12px;
    color: #667eea;
}

.btn-back {
  padding: 12px 24px;
  border-radius: 12px;
  border: 2px solid #667eea;
  background: white;
  color: #667eea;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s;
  display: inline-block;
}

.btn-back:hover {
  background: #667eea;
  color: white;
}

</style>

<!-- 아이콘용 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>
<body>
<div class="container py-5">

    <div class="header" mt-5>
        <h2>관리자 페이지 - <?=htmlspecialchars($user_name)?></h2>
        <a href="?action=logout" class="logout-btn">로그아웃</a>
    </div>

    <div class="menu-container">
        
        <div class="menu-card mt-3">
            <i class="bi bi-journal-plus menu-icon"></i>
            <a href="account_input_1.php">사용내역서 입력</a>
        </div>
        <div class="menu-card mt-3">
            <i class="bi bi-pencil-square menu-icon"></i>
            <a href="account_edit_1.php">사용내역서 편집</a>
        </div>
        <div class="menu-card mt-3">
            <i class="bi bi-journal-text menu-icon"></i>
            <a href="account_view_1.php">사용내역서 보기</a>
        </div>
        <div class="menu-card mt-3">
            <i class="bi bi-camera-fill menu-icon"></i>
            <a href="images_upload_1.php">사진 보내기</a>
        </div>
        <div class="menu-card mt-3">
            <i class="bi bi-pencil-fill menu-icon"></i>
            <a href="images_edit_1.php">사진 편집</a>
        </div>
        <div class="menu-card mt-3">
            <i class="bi bi-images menu-icon"></i>
            <a href="images_view_1.php">사진 보기</a>
        </div>
    </div>

    <!-- 이전페이지로 이동하기 버튼 -->
     <div class="text-center mt-5 mb-3">
        <a href="tel_member.php" class="btn-back">← 돌아가기</a>
     </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

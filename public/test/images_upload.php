<?php
// ⭐ 관리자 인증 — 로그인 + 레벨10 확인
require 'php/auth_check.php';
require 'php/db-connect.php';
date_default_timezone_set('Asia/Seoul');

$uploadDir = 'data/profile/'; // 이미지 저장 경로
$successMessage = '';
$errorMessage = '';

// 이미지 업로드 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['photo']['name']) && !empty($_POST['selected_date']) && !empty($_POST['selected_time'])) {
        $filename = $_FILES['photo']['name'];
        $tmpFilePath = $_FILES['photo']['tmp_name'];
        $newFilePath = $uploadDir . basename($filename);

        $selectedDate = $_POST['selected_date'];
        $selectedTime = $_POST['selected_time'];
        $fullDateTime = $selectedDate . ' ' . $selectedTime;

        $notice = $_POST['notice'];

        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            $stmt = $pdo->prepare("INSERT INTO images (photo, date, notice) VALUES (?, ?, ?)");
            if ($stmt->execute([$newFilePath, $fullDateTime, $notice])) {
                $successMessage = "이미지 업로드가 완료되었습니다.";
            } else {
                $errorMessage = "데이터베이스 저장 중 오류가 발생했습니다.";
            }
        } else {
            $errorMessage = "이미지 업로드 중 오류가 발생했습니다.";
        }
    } else {
        $errorMessage = "이미지, 날짜, 시간을 모두 입력해주세요.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="J.S.J" />
<title>영수증 업로드</title>
<link rel="stylesheet" href="css/images_upload.css" />
<link rel="manifest" href="manifest.json">
<meta name="msapplication-config" content="/browserconfig.xml">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- 파비콘 -->
<link rel="icon" href="/favicon.ico?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

<style>
body {
    background-color: #f2f2f2;
    font-family: 'Noto Sans KR', sans-serif;
    padding: 20px;
}
.upload-container {
    max-width: 600px;
    margin: 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.upload-container h2 {
    text-align: center;
    margin-bottom: 20px;
}
.upload-form .form-label {
    font-weight: 500;
}
.upload-form input, .upload-form textarea {
    border-radius: 6px;
}
.upload-form .btn-success {
    width: 100%;
    margin-top: 15px;
}
.message {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}
.message.success { color: green; }
.message.error { color: red; }
</style>
</head>
<body>

<div class="upload-container">
    <h2>영수증 입력</h2>

    <?php if ($successMessage): ?>
        <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form class="upload-form" action="" method="POST" enctype="multipart/form-data">
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

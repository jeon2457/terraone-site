<?php
//  /terraone_php/jikji35_account/login.php
session_start();
// 🔥[중요!] 인증(세션) 관련 코드는 반드시 HTML 출력보다 먼저 실행해야 합니다. <?php 코드는 무조건 1행에 공백없이 제일앞에 와야함!
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>관리자 로그인</title>

<link rel="manifest" href="manifest.json" />
<meta name="theme-color" content="#ffffff" />


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
html, body {
    height: 100%;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f4f6f9;
}
.login-container {
    width: 500px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 12px;
    text-align: center;
    background-color: #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
p.login_text {
    font-size: 22px;
    color: #6c757d;
    margin-bottom: 20px;
}
.login-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 20px;
    border-radius: 50%;
    overflow: hidden;
}
.login-icon img {
    width: 100%;
    height: auto;
    display: block;
}
@media (max-width: 480px) {
    .login-container { width: 95%; }
}
</style>
</head>
<body>
<div class="login-container">
    <p class="login_text">관리자 로그인</p>
    <div class="login-icon">
        <img src="./images/clova.jpg" alt="로그인 아이콘">
    </div>

    <!-- 변경됨: home 폴더 제거 -->
    <form action="./php/login_check.php" method="post">
        <div class="mb-3 text-start">
            <label for="id" class="form-label">아이디:</label>
            <input type="text" class="form-control" id="id" name="id" required>
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label">비밀번호:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="text-start mb-3" style="font-size:12px;">
            <span style="color:red;">[알림] </span>
            http://localhost/terraone_php/ <br>에서 운영 가동 중인 테스트용 서버입니다.<br>
            DB명: terraone<br>
            모든 회비 사용내역서, 영수증사진, 회원연락망 편집/열람 가능-전송방식 PDO사용<br>
            전화/사용내역서/영수증 관련 테이블: <span style="background-color:black;color:orange;">expense_table, income_table, images, tel 사용중</span>
        </div>

        <div class="d-grid gap-3 mt-4">
            <button type="submit" class="btn btn-primary">관리자 로그인</button>
        </div>
    </form>
</div>
</body>
</html>

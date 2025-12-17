<?php
session_start();
require './php/auth_check.php'; // 로그인 + 관리자 레벨 확인
// 여기서는 auth_check.php 경로를 /tel을 선택했다. 
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원 회계관리</title>

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
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea, #764ba2);
      --secondary-gradient: linear-gradient(135deg, #f6d365, #fda085);
      --dark-bg: #2c2f33;
    }

    body {
      font-family: 'Noto Sans KR', sans-serif;
      background: var(--dark-bg);
      color: #f0f0f0;
      min-height: 100vh;
    }

    .page-header {
    background: linear-gradient(135deg, #3d5e40ff 0%, #04472fff 100%); /* 소제목과 어울리는 진보라~보라 */
    color: #f88e47ff;
    padding: 30px 20px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    text-align: center;
    margin-top: 40px; /* 상단 여백 조절 */
    margin-bottom: 40px; /* 하단 여백 */
    margin-left: 0; /* 좌측은 필요 없으면 0 */
    margin-right: 0; /* 우측도 0 */
}


    .btn-card {
      background: #3a3d42;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.4);
      padding: 25px 15px;
      transition: all 0.3s ease;
      text-align: center;
    }

    .btn-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.6);
    }

    .btn-card .btn {
      width: 100%;
      font-weight: 700;
      padding: 15px 0;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .btn-primary-gradient {
      background: var(--primary-gradient);
      color: #fff;
      border: none;
    }

    .btn-primary-gradient:hover {
      opacity: 0.9;
    }

    .btn-secondary-gradient {
      background: var(--secondary-gradient);
      color: #fff;
      border: none;
    }

    .btn-secondary-gradient:hover {
      opacity: 0.9;
    }

    .btn-back {
      display: block;
      width: 200px;
      margin: 50px auto 20px auto;
      padding: 12px 20px;
      border-radius: 12px;
      background: #667eea;
      color: #fff;
      font-weight: 700;
      text-align: center;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-back:hover {
      background: #556cd6;
      text-decoration: none;
      color: #fff;
    }

    @media (max-width: 768px) {
      .btn-card { margin-bottom: 20px; }
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="page-header">
    <h1>회원 회계관리</h1>
  </div>

  <div class="row g-4">
    <div class="col-lg-4 col-md-6">
      <div class="btn-card">
        <button class="btn btn-primary-gradient" onclick="location.href='account_input.php'">
          거래명세서 입력
        </button>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="btn-card">
        <button class="btn btn-primary-gradient" onclick="location.href='account_edit.php'">
          거래명세서 편집
        </button>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="btn-card">
        <button class="btn btn-primary-gradient" onclick="location.href='account_view.php'">
          거래명세서 보기
        </button>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="btn-card">
        <button class="btn btn-secondary-gradient" onclick="location.href='images_upload.php'">
          영수증사진 업로드
        </button>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="btn-card">
        <button class="btn btn-secondary-gradient" onclick="location.href='images_edit.php'">
          영수증사진 편집
        </button>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="btn-card">
        <button class="btn btn-secondary-gradient" onclick="location.href='images_view.php'">
          영수증사진 보기
        </button>
      </div>
    </div>
  </div>

  <!-- 되돌아가기 버튼 -->
  <a href="./tel_select.php" class="btn-back">← 되돌아가기</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

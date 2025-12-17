<?php
session_start();
// ⭐ 맨 위에 반드시 이 코드가 있어야 합니다!
require 'php/auth_check.php';
?>
<!-- ✅1. 이페이지는 계모임에서 총무담당 사용지출내역을 관리하고, http://localhost/account_input.php 에서 관리자페이지로 사용내역서를 입력할수있다.
2. account_edit.php 에서는 관리자페이지로 편집(수정/삭제)을 한다.
3. account_view.php 에서는 회원들에게 공개적으로 보여주는 페이지이다.
4. 영수증 사진보기를 클릭하면 http://localhost/account_input/images_view.php 페이지를 회원들에게 보여준다. ===> images_upload.php(사진입력) ==> images_edit.php(사진편집) ==> images_view.php(사진공개 열람)
5. 데이타베이스의 사용내역서는 수입관련 테이블(income_table)/지출관련 테이블(expense_table)을 사용하고있고, 영수증사진 관련테이블은 images 이다. -->

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="J.S.J" />
  <title>사용내역서입력</title>
  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  
  <!-- 부트스트랩 CDN 링크 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-YvpCrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  />

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="./favicons/2/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="./favicons/2/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="./favicons/2/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="./favicons/2/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="./favicons/2/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="./favicons/2/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="./favicons/2/apple-icon-72x72.png">


  <style>
    * {
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 11px 0;
    }
    
    .form-container {
      max-width: 600px;
      margin: 30px auto;
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
      padding: 40px;
      animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .form-container h1 {
      text-align: center;
      color: #333;
      font-weight: 700;
      margin-bottom: 30px;
      font-size: 28px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      font-weight: 600;
      color: #555;
      margin-bottom: 8px;
      display: block;
    }
    
    .form-control, .form-select {
      border-radius: 12px;
      border: 2px solid #e0e0e0;
      padding: 12px 16px;
      font-size: 15px;
      transition: all 0.3s ease;
      width: 100%;
      box-sizing: border-box;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    /* 🔹 공통 제목 스타일 (상단 '사용내역서 입력' 디자인) */
    .section-title {
        text-align:center; 
        color:#007bff; 
        font-weight:700; 
        margin-bottom:30px; 
        padding:10px; 
        background:#e9f3ff; 
        border-radius:10px;
        border:1px solid #c9e3ff;
    }

    
    /* ⭐ 버튼 통일 (크기, 폭, 정렬) */
    .button-group {
        text-align: center;
        margin-top: 25px;
    }

    /* 🔵 두 버튼 공통 스타일 (원래 버튼 모양 유지) */
    .btn-submit,
    .btn-back {
        display: block;
        width: 100%;
        max-width: 250px;
        margin: 10px auto;
        padding: 14px;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        box-sizing: border-box;
    }

    /* 🟥 저장하기 버튼 (원래 스타일 그대로) */
    .btn-submit {
        background: #007bff;
        color: #fff;
    }

    /* 🟩 되돌아가기 버튼 (여기서만 조절 가능) */
    .btn-back {
        background: #6c757d;
        color: white;
        margin-top: 18px;
        margin-bottom: 5px;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    .user-info {
      text-align: right;
      margin: 20px 15px;
      padding: 15px 22px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .user-info span {
      color: #555;
      font-weight: 500;
    }
    
    .btn-logout {
      border-radius: 8px;
      padding: 5px 15px;
      font-size: 14px;
    }
    
    .success-message {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
      text-align: center;
      z-index: 9999;
      animation: popIn 0.3s ease;
    }
    
    @keyframes popIn {
      from { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
      to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    }
    
    .success-message p {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 20px;
    }
    
    .success-message button {
      padding: 10px 30px;
      border-radius: 12px;
      font-weight: 600;
    }
    
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 9998;
    }

    /* 안내문 박스 */
    .info-box {
        margin-top: 1px;
        margin-bottom: 11px;
        padding: 12px 16px;
        background: #f4faff;
        border-left: 4px solid #007bff;
        border-radius: 6px;
        font-size: 14px;
        line-height: 1.4;
        box-sizing: border-box;
    }
    .info-box strong {
        color: #0056ff;
        font-weight: bold;
    }
  
    @media (max-width: 576px) {
      .form-container {
        padding: 20px;
        margin: 10px;
      }
      
      .form-container h1 {
        font-size: 24px;
      }

      .form-control, .form-select {
        padding: 10px 12px;
        font-size: 14px;
      }

      .user-info {
        margin: 10px;
        padding: 12px 15px;
      }
    }

  </style>
</head>

<body>
<?php
  
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');
  
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

$showSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $date = $_POST['date'];
  $time = $_POST['time'] ?? '00:00';
  $type = $_POST['type'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $amount = $_POST['amount'];

  $datetime = $date . ' ' . $time . ':00';

  if (empty($date) || empty($type) || empty($category) || empty($amount)) {
      echo '<p style="text-align: center; color: red;">일자, Type, 항목, 금액은 필수 입력 사항입니다.</p>';
  } else {
      if ($type === '수입') {
          $table = 'income_table';
      } else if ($type === '지출') {
          $table = 'expense_table';
      }

      $stmt = $pdo->prepare("INSERT INTO $table (date, category, description, amount) VALUES (?, ?, ?, ?)");
      if ($stmt->execute([$datetime, $category, $description, $amount])) {
          $showSuccess = true;
      } else {
          echo '<p style="text-align: center; color: red;">데이터 저장 중 오류가 발생했습니다.</p>';
      }
  }
}
?>

<div class="container">
  <div class="user-info">
    <span><?php echo htmlspecialchars($_SESSION['user_name']); ?>님 (Webmaster) | </span>
    <a href="./logout.php" class="btn btn-sm btn-outline-secondary btn-logout">로그아웃</a>
  </div>
  
  <div class="form-container">
    <h1 class="section-title">💰 사용내역서 입력</h1>
    
    <form method="POST" action="">
        <div class="form-group">
          <label for="date">📅 일자</label>
          <input type="date" class="form-control" id="date" name="date" required>
        </div>

        <div class="form-group">
          <label for="time">🕐 시간</label>
          <input type="time" class="form-control" id="time" name="time" value="00:00" required>
        </div>
        
        <div class="form-group">
          <label for="type">📊 Type</label>
          <select class="form-select" id="type" name="type" required>
            <option value="수입">수입</option>
            <option value="지출">지출</option>
          </select>
        </div>

        <div class="form-group">
          <label for="category">📝 항목</label>
          <input type="text" class="form-control" id="category" name="category" required placeholder="예: 월회비,회식비,식사비,찬조금 등...">
        </div>

        <div class="form-group">
          <label for="description">📌 비고</label>
          <input type="text" class="form-control" id="description" name="description" placeholder="추가 설명 (선택사항)">
        </div>

        <div class="form-group">
          <label for="amount">💵 금액</label>
          <input type="number" class="form-control" id="amount" name="amount" required placeholder="숫자만 입력">
        </div>

        <div class="info-box">
            <strong>모임 사용내역서 작성은 여기뿐만 아니라 
              /new_terraone_php/1/account_input.php, 
              /new_terraone_php/2/account_input.php,
              Google의 스프레드시트 에서도 gagebu, 황악회원 입금현황 
              파일로 만들어져있는데 이것을 활용할 수도있다.
        </div>

        <div class="button-group">
            <button type="submit" class="btn-submit">저장하기</button>
            <a href="./select.php" class="btn-back">⏪ 되돌아가기</a>
        </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="modalOverlay"></div>

<div class="success-message" id="successMessage">
  <p>✅ 전송을 완료하였습니다.</p>
  <button class="btn btn-success" onclick="reloadPage()">확인</button>
</div>

<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
  crossorigin="anonymous"
></script>

<script>
  window.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    
    const dateInput = document.getElementById('date');
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    dateInput.value = `${year}-${month}-${day}`;
    
    const timeInput = document.getElementById('time');
    const hours = String(today.getHours()).padStart(2, '0');
    const minutes = String(today.getMinutes()).padStart(2, '0');
    timeInput.value = `${hours}:${minutes}`;
  });

  <?php if ($showSuccess): ?>
    document.getElementById('successMessage').style.display = 'block';
    document.getElementById('modalOverlay').style.display = 'block';
  <?php endif; ?>

  function reloadPage() {
    window.location.href = 'account_input.php';
  }
</script>

</body>
</html>

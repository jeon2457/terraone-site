<?php
// ⭐ 맨 위에 반드시 이 코드가 있어야 합니다!
require 'php/auth_check.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="J.S.J" />
  <title>사용내역서입력</title>
  <link rel="stylesheet" href="css/account_input.css" />
  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  
  <!-- 부트스트랩 CDN 링크 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-YvpCrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  />

  <!-- 파비콘 아이콘들 -->
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
      background: linear-gradient(to right, #f8f9fa, #e9ecef);
      min-height: 100vh;
      font-family: 'Noto Sans KR', sans-serif;
    }
    .container-card {
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #343a40;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .btn-submit {
      width: 100%;
    }
    .success-message {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .success-message p {
      color: #fff;
      font-size: 1.2rem;
      margin-bottom: 20px;
    }
    .success-message .btn {
      width: 120px;
    }
    .text-end {
      text-align: right;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<?php
require 'php/db-connect.php';

// PDO 객체 생성
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$showSuccess = false;

// 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $date = $_POST['date'];
  $type = $_POST['type'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $amount = $_POST['amount'];

  if (empty($date) || empty($type) || empty($category) || empty($amount)) {
      echo '<p style="text-align: center; color: red;">일자, Type, 항목, 금액은 필수 입력 사항입니다.</p>';
  } else {
      $table = ($type === '수입') ? 'income_table' : 'expense_table';
      $stmt = $pdo->prepare("INSERT INTO $table (date, category, description, amount) VALUES (?, ?, ?, ?)");
      if ($stmt->execute([$date, $category, $description, $amount])) {
          $showSuccess = true;
      } else {
          echo '<p style="text-align: center; color: red;">데이터 저장 중 오류가 발생했습니다.</p>';
      }
  }
}
?>

<div class="container-card">
  <!-- 상단 로그인 정보 -->
  <div class="text-end">
    <span class="text-muted"><?php echo htmlspecialchars($_SESSION['user_name']); ?>님 (Webmaster) | </span>
    <a href="../member/logout.php" class="btn btn-sm btn-outline-secondary">로그아웃</a>
  </div>

  <h1>사용내역서 입력</h1>

  <!-- 입력 폼 -->
  <form method="POST" action="">
    <div class="form-group">
      <label for="date">일자:</label>
      <input type="date" class="form-control" id="date" name="date" required>
    </div>

    <div class="form-group">
      <label for="type">Type:</label>
      <select class="form-control" id="type" name="type" required>
        <option value="수입">수입</option>
        <option value="지출">지출</option>
      </select>
    </div>

    <div class="form-group">
      <label for="category">항목:</label>
      <input type="text" class="form-control" id="category" name="category" required>
    </div>

    <div class="form-group">
      <label for="description">비고:</label>
      <input type="text" class="form-control" id="description" name="description">
    </div>

    <div class="form-group">
      <label for="amount">금액:</label>
      <input type="number" class="form-control" id="amount" name="amount" required>
    </div>

    <button type="submit" class="btn btn-success btn-submit">저장</button>
  </form>
</div>

<!-- 성공 메시지 모달 -->
<div class="success-message" id="successMessage">
  <div style="background:#28a745; padding:30px; border-radius:12px; text-align:center;">
    <p>전송을 완료하였습니다.</p>
    <button class="btn btn-light" onclick="reloadPage()">확인</button>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>

<script>
  <?php if ($showSuccess): ?>
    document.getElementById('successMessage').style.display = 'flex';
  <?php endif; ?>

  function reloadPage() {
    window.location.href = 'account_input.php';
  }
</script>

</body>
</html>

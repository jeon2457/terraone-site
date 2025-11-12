<?php
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
  <link rel="stylesheet" href="css/account_input.css" />
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
<?php
  
require 'php/db-connect.php'; // DB 접속 정보 불러오기
  
// PDO 객체 생성
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

$showSuccess = false; // 성공 메시지 표시 여부

// 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $date = $_POST['date'];
  $type = $_POST['type'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $amount = $_POST['amount'];

  // 유효성 검사
  if (empty($date) || empty($type) || empty($category) || empty($amount)) {
      echo '<p style="text-align: center; color: red;">일자, Type, 항목, 금액은 필수 입력 사항입니다.</p>';
  } else {
      // 데이터베이스에 데이터 저장
      if ($type === '수입') {
          $table = 'income_table';
      } else if ($type === '지출') {
          $table = 'expense_table';
      }

      $stmt = $pdo->prepare("INSERT INTO $table (date, category, description, amount) VALUES (?, ?, ?, ?)");
      if ($stmt->execute([$date, $category, $description, $amount])) {
          $showSuccess = true; // 성공 시 메시지 표시 플래그 설정
      } else {
          echo '<p style="text-align: center; color: red;">데이터 저장 중 오류가 발생했습니다.</p>';
      }
  }
}
?>

<div class="container">
  <!-- 상단 로그인 정보 표시 -->
  <div class="text-end mb-3 mt-3">
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
      <button type="submit" class="btn btn-success">저장</button>
  </form>
</div>

<!-- 성공 메시지 모달 -->
<div class="success-message" id="successMessage">
  <p>전송을 완료하였습니다.</p>
  <button class="btn btn-success" onclick="reloadPage()">확인</button>
</div>

<!-- ✅ Bootstrap JS (번들) -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
  crossorigin="anonymous"
></script>

<script>
  // 전송 성공 시 메시지 표시
  <?php if ($showSuccess): ?>
    document.getElementById('successMessage').style.display = 'block';
  <?php endif; ?>

  // 확인 버튼 클릭 시 페이지 새로고침
  function reloadPage() {
    window.location.href = 'account_input.php';
  }
</script>

</body>
</html>